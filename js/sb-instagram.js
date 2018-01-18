function headerHTML(data, feedOptions)
{
    var html = '';
    html += '<a href="https://instagram.com/' + data.data.username + '" target="_blank" title="@' + data.data.username + '" class="sbi_header_link">';
    html += '<div class="sbi_header_text">';
    html += '<h3 ';
    if (data.data.bio.length === 0 || feedOptions.showbio !== 'true') {
        html += ' class="sbi_no_bio"';
    }
    html += '>@' + data.data.username + '</h3>';
    if (data.data.bio.length && feedOptions.showbio === 'true') {
        html += '<p class="sbi_bio">' + data.data.bio + '</p>';
    }
    html += '</div>';
    html += '<div class="sbi_header_img">';
    html += '<div class="sbi_header_img_hover"><i></i></div>';
    html += '<img src="' + data.data.profile_picture + '" alt="' + data.data.full_name + '" width="50" height="50">';
    html += '</div>';
    html += '</a>';

    return html;
}

function getImageResolution(feedWidth)
{
    var colWidth = feedWidth;

    var sbiWindowWidth = Math.floor(document.querySelector('body').getBoundingClientRect().width);
    if (sbiWindowWidth < 640) {
        // Need this for mobile so that image res is right on mobile, as the number of cols isn't always accurate on mobile as they are changed using CSS
        if ((feedWidth > 320 && feedWidth < 480) && sbiWindowWidth < 480) {
            colWidth = 480; // Use full size images
        }
        if (feedWidth < 320 && sbiWindowWidth < 480) {
            colWidth = 300; // Use medium size images
        }
    }

    var imgRes = 'standard_resolution';
    if (colWidth < 150) {
        imgRes = 'thumbnail';
    } else if (colWidth < 320) {
        imgRes = 'low_resolution';
    }

    // If the feed is hidden (eg; in a tab) then the width is returned as 100, and so auto set the res to be medium to cover most bases
    if (feedWidth <= 100) {
        imgRes = 'low_resolution';
    }

    return imgRes;
}

function filterImage(image)
{
    // Create time for sorting
    var date = new Date(image.created_time * 1000);
    image.created_time_raw = date.getTime();

    // Remove " in caption so doesn't cause issue in alt tag
    // Always check to make sure it exists
    if (image.caption != null) {
        image.caption.text = image.caption.text.replace(/"/g, '\\"');
    }

    // Remove caching key from image sources to prevent duplicate content issue
    image.images.thumbnail.url = image.images.thumbnail.url.split('?ig_cache_key')[0];
    image.images.standard_resolution.url = image.images.standard_resolution.url.split('?ig_cache_key')[0];
    image.images.low_resolution.url = image.images.low_resolution.url.split('?ig_cache_key')[0];

    return true;
}

function generateHeader(userID, accessToken, feedOptions)
{
    var sbi_page_url = 'https://api.instagram.com/v1/users/' + userID + '?access_token=' + accessToken;

    var ajax = new XMLHttpRequest();
    ajax.open('GET', sbi_page_url, true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState !== 4) {
            return;
        }
        var data = JSON.parse(ajax.responseText);
        var sbiErrorResponse = data.meta.error_message;
        if (typeof sbiErrorResponse === 'undefined') {
            var igEl = document.getElementById('sb_instagram');
            var innerHtml = igEl.innerHTML;
            igEl.innerHTML = headerHTML(data, feedOptions) + innerHtml;
        }
    };
    ajax.send(null);
}

function getTemplateString()
{
    var templateString = '<div class="sbi_item sbi_type_{{model.type}}" id="sbi_{{id}}" data-date="{{model.created_time_raw}}">';
    templateString += '<figure class="sbi_photo_wrap">';
    templateString += '<a class="sbi_photo" href="{{link}}" target="_blank"><img src="{{image}}" alt="{{caption}}" width="200" height="200">';
    templateString += '<figcaption><div class="sbi_username">{{model.user.username}}</div><div>{{caption}}</div></figcaption></a>';
    templateString += '</figure></div>';

    return templateString;
}

function initInstagram()
{
    // Used to track multiple feeds on the page
    window.sbiFeedMeta = {};

    var thisEl = document.getElementById('sb_instagram');
    var $self = jQuery('#sb_instagram');
    // Convert styles JSON string to an object
    var feedOptions = JSON.parse(thisEl.getAttribute('data-options'));
    var sortby = 'date';

    if (feedOptions.sortby !== '') {
        sortby = feedOptions.sortby;
    }

    var imgRes = getImageResolution(thisEl.getBoundingClientRect().width);

    // Split comma separated hashtags into array
    var userIDs = thisEl.getAttribute('data-id').replace(/ /g, '').split(',');

    if (document.querySelector('.sb_instagram_header')) {
        generateHeader(userIDs[0], instagramAccessToken, feedOptions);
    }

    // Loop through User IDs
    userIDs.forEach(function(userID) {
        var userFeed = new instagramfeed({
            target: thisEl.querySelector('#sbi_images'),
            get: 'user',
            sortBy: sortby,
            resolution: imgRes,
            limit: parseInt(thisEl.getAttribute('data-num'), 10),
            template: getTemplateString(),
            filter: filterImage,
            userId: parseInt(userID, 10),
            accessToken: instagramAccessToken,
            after: function() {
                var loaderEl = thisEl.querySelector('.sbi_loader');
                loaderEl.parentNode.removeChild(loaderEl);

                // Add video icon to videos
                var photos = document.querySelectorAll('#sb_instagram .sbi_photo');
                for (var i = 0; i < photos.length; i++) {
                    var photo = photos[i];
                    if (!photo.parentNode.parentNode.classList.contains('sbi_type_video')) {
                        continue;
                    }
                    if (!photo.querySelector('.sbi_playbtn')) {
                        photo.innerHTML += '<i class="sbi_playbtn"></i>';
                    }
                }
                // Sort posts by date
                // only sort the new posts that are loaded in, not the whole feed, otherwise some photos will switch positions due to dates
                $self.find('#sbi_images .sbi_item').sort(function (a, b) {
                    var aComp = parseInt(a.dataset.date, 10);
                    var bComp = parseInt(b.dataset.date, 10);

                    if (sortby === 'date' || sortby === 'none') { // 'none' for backwards compatibility
                        return bComp - aComp;
                    } else {
                        // Randomize
                        return (Math.round(Math.random()) - 0.5);
                    }
                }).appendTo($self.find('#sbi_images'));
            },
            error: function(sbiErrorResponse) {
                var error;
                if (sbiErrorResponse.indexOf('access_token') > -1) {
                    var sbiErrorMsg = '<p><b>Error: Access Token is not valid or has expired</b><br><span>This error message is only visible to WordPress admins</span>';
                    var sbiErrorDir = "<p>There's an issue with the Instagram Access Token that you are using. Please obtain a new Access Token on the plugin's Settings page.<br>If you continue to have an issue with your Access Token then please see <a href='https://smashballoon.com/my-instagram-access-token-keep-expiring/' target='_blank'>this FAQ</a> for more information.";
                    document.getElementById('sb_instagram').innerHTML = '<p style="text-align: center;">Unable to show Instagram photos</p><div id="sbi_mod_error">' + sbiErrorMsg + sbiErrorDir + '</div>';
                } else if (sbiErrorResponse.indexOf('user does not exist') > -1 || sbiErrorResponse.indexOf('you cannot view this resource') > -1) {
                    error = {
                        msg: '<p><b>Error: User ID <span class="sbiErrorIds">' + userID + '</span> does not exist, is invalid, or is private</b><br><span>This error is only visible to WordPress admins</span>',
                        dir: "<p>Please double check the Instagram User ID that you are using and ensure that it is valid and not from a private account. To find your User ID simply enter your Instagram user name into this <a href='https://smashballoon.com/instagram-feed/find-instagram-user-id/' target='_blank'>tool</a>.</p>"
                    };
                    if (!$self.find('#sbi_mod_error').length) {
                        $self.prepend('<div id="sbi_mod_error">' + error.msg + error.dir + '</div>');
                    } else if ($self.find('.sbiErrorIds').text().indexOf(userID) === -1) {
                        $self.find('.sbiErrorIds').append(',' + userID);
                    }
                } else if (sbiErrorResponse.indexOf('No images were returned') > -1) {
                    error = {
                        msg: '<p><b>Error: User ID <span class="sbiErrorNone">' + userID + '</span> has no posts</b><br><span>This error is only visible to WordPress admins</span>',
                        dir: "<p>If you are the owner of this account, make a post on Instagram to see it in your feed.</p>"
                    };
                    if (!$self.find('#sbi_mod_error.sbi_error_none').length) {
                        $self.prepend('<div id="sbi_mod_error" class="sbi_error_none">' + error.msg + error.dir + '</div>');
                    } else if ($self.find('.sbiErrorNone').text().indexOf(userID) === -1) {
                        $self.find('.sbiErrorNone').append(',' + userID);
                    }
                }
            }
        });

        userFeed.run();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initInstagram();
});
