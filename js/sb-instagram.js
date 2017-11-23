var sbi_js_exists = (typeof sbi_js_exists !== 'undefined');
if (!sbi_js_exists) {
    function sbi_init()
    {
        // used to track multiple feeds on the page
        window.sbiFeedMeta = {};

        jQuery('#sb_instagram.sbi').each(function($i) {

            var $self = jQuery(this),
                $target = $self.find('#sbi_images'),
                imgRes = 'standard_resolution',
                cols = 1,
                //Convert styles JSON string to an object
                feedOptions = JSON.parse(this.getAttribute('data-options')),
                getType = 'user',
                sortby = 'none',
                user_id = this.getAttribute('data-id'),
                num = this.getAttribute('data-num'),
                $header = '',
                morePosts = []; //Used to determine whether to show the Load More button when displaying posts from more than one id/hashtag. If one of the ids/hashtags has more posts then still show button.

            jQuery(this).attr('data-sbi-index', $i);
            // setting up some global objects to keep track of various statuses used for the caching system
            feedOptions.feedIndex = $i;
            window.sbiFeedMeta[$i] = {
                'error'    : {},
                'idsInFeed' : []
            };

            if (feedOptions.sortby !== '') {
                sortby = feedOptions.sortby;
            }

            switch (this.getAttribute('data-res')) {
                case 'auto':
                    var feedWidth = $self.innerWidth(),
                        colWidth = $self.innerWidth() / cols;

                    //Check if page width is less than 640. If it is then use the script above
                    var sbiWindowWidth = jQuery(window).width();
                    if (sbiWindowWidth < 640) {
                        //Need this for mobile so that image res is right on mobile, as the number of cols isn't always accurate on mobile as they are changed using CSS
                        if ((feedWidth > 320 && feedWidth < 480) && sbiWindowWidth < 480) {
                            colWidth = 480; //Use full size images
                        }
                        if (feedWidth < 320 && sbiWindowWidth < 480) {
                            colWidth = 300; //Use medium size images
                        }
                    }

                    if (colWidth < 150) {
                        imgRes = 'thumbnail';
                    } else if (colWidth < 320) {
                        imgRes = 'low_resolution';
                    } else {
                        imgRes = 'standard_resolution';
                    }

                    //If the feed is hidden (eg; in a tab) then the width is returned as 100, and so auto set the res to be medium to cover most bases
                    if (feedWidth <= 100) {
                        imgRes = 'low_resolution';
                    }

                    break;
                case 'thumb':
                    imgRes = 'thumbnail';
                    break;
                case 'medium':
                    imgRes = 'low_resolution';
                    break;
                default:
                    imgRes = 'standard_resolution';
            }

            //Split comma separated hashtags into array
            var ids_arr = user_id.replace(/ /g,'').split(",");
            var looparray = ids_arr;

            //Get page info for first User ID
            var headerStyles = '',
                sbi_page_url = 'https://api.instagram.com/v1/users/' + ids_arr[0] + '?access_token=' + sb_instagram_js_options.sb_instagram_at;

            jQuery.ajax({
                method: "GET",
                url: sbi_page_url,
                dataType: "jsonp",
                success: function(data) {
                    var sbiErrorResponse = data.meta.error_message;
                    if (typeof sbiErrorResponse === 'undefined') {
                        $header = '<a href="https://instagram.com/' + data.data.username + '" target="_blank" title="@' + data.data.username + '" class="sbi_header_link">';
                        $header += '<div class="sbi_header_text">';
                        $header += '<h3 ' + headerStyles;
                        if (data.data.bio.length == 0 || feedOptions.showbio !== "true") {
                            $header += ' class="sbi_no_bio"';
                        }
                        $header += '>@' + data.data.username + '</h3>';
                        if (data.data.bio.length && feedOptions.showbio === "true") {
                            $header += '<p class="sbi_bio" ' + headerStyles + '>' + data.data.bio + '</p>';
                        }
                        $header += '</div>';
                        $header += '<div class="sbi_header_img">';
                        $header += '<div class="sbi_header_img_hover"><i></i></div>';
                        $header += '<img src="' + data.data.profile_picture + '" alt="' + data.data.full_name + '" width="50" height="50">';
                        $header += '</div>';
                        $header += '</a>';
                        //Add the header
                        $self.find('.sb_instagram_header').prepend($header);
                    }
                }
            });

            //Loop through User IDs
            jQuery.each(looparray, function(index, entry) {
                window.sbiFeedMeta[$i].idsInFeed.push(entry);

                var templateString = '<div class="sbi_item sbi_type_{{model.type}} sbi_new" id="sbi_{{id}}" data-date="{{model.created_time_raw}}">';
                templateString += '<div class="sbi_photo_wrap">';
                templateString += '<a class="sbi_photo" href="{{link}}" target="_blank"><img src="{{image}}" alt="{{caption}}" width="200" height="200"></a>';
                templateString += '</div></div>';

                var userFeed = new instagramfeed({
                    target: $target,
                    get: getType,
                    sortBy: sortby,
                    resolution: imgRes,
                    limit: parseInt(num, 10),
                    template: templateString,
                    filter: function(image) {
                        //Create time for sorting
                        var date = new Date(image.created_time*1000),
                            time = date.getTime();
                        image.created_time_raw = time;

                        //Remove all special chars in caption so doesn't cause issue in alt tag
                        //Always check to make sure it exists
                        if (image.caption != null) {
                            image.caption.text = image.caption.text.replace(/[^a-zA-Z ]/g, "");
                        }

                        //Remove caching key from image sources to prevent duplicate content issue
                        image.images.thumbnail.url = image.images.thumbnail.url.split("?ig_cache_key")[0];
                        image.images.standard_resolution.url = image.images.standard_resolution.url.split("?ig_cache_key")[0];
                        image.images.low_resolution.url = image.images.low_resolution.url.split("?ig_cache_key")[0];

                        return true;
                    },
                    userId: parseInt(entry, 10),
                    accessToken: sb_instagram_js_options.sb_instagram_at,
                    after: function() {

                        $self.find('.sbi_loader').remove();

                        /* Load more button */
                        if (this.hasNext()) {
                            morePosts.push('1');
                        }

                        //Only check the width once the resize event is over
                        var sbi_delay = (function() {
                            var sbi_timer = 0;
                            return function(sbi_callback, sbi_ms) {
                                clearTimeout(sbi_timer);
                                sbi_timer = setTimeout(sbi_callback, sbi_ms);
                            };
                        })();

                        jQuery(window).resize(function() {
                            sbi_delay(function() {
                                sbiSetPhotoHeight();
                            }, 500);
                        });

                        //Resize image height
                        function sbiSetPhotoHeight()
                        {

                            if (imgRes !== 'thumbnail') {
                                var sbi_photo_width = $self.find('.sbi_photo').eq(0).innerWidth();

                                //Figure out number of columns for either desktop or mobile
                                var sbiWindowWidth = jQuery(window).width();

                                //Figure out what the width should be using the number of cols
                                var sbi_photo_width_manual = $self.find('#sbi_images').width();

                                //If the width is less than it should be then set it manually
                                if (sbi_photo_width <= sbi_photo_width_manual) {
                                    sbi_photo_width = sbi_photo_width_manual;
                                }
                            }

                        }
                        sbiSetPhotoHeight();

                        /* Detect when element becomes visible. Used for when the feed is initially hidden, in a tab for example. https://github.com/shaunbowe/jquery.visibilityChanged */
                        !function(i){var n={callback:function(){},runOnLoad:!0,frequency:100,sbiPreviousVisibility:null},c={};c.sbiCheckVisibility=function(i,n){if(jQuery.contains(document,i[0])){var e=n.sbiPreviousVisibility,t=i.is(":visible");n.sbiPreviousVisibility=t,null==e?n.runOnLoad&&n.callback(i,t):e!==t&&n.callback(i,t),setTimeout(function(){c.sbiCheckVisibility(i,n)},n.frequency)}},i.fn.sbiVisibilityChanged=function(e){var t=i.extend({},n,e);return this.each(function(){c.sbiCheckVisibility(i(this),t)})}}(jQuery);

                        //If the feed is initially hidden (in a tab for example) then check for when it becomes visible and set then set the height
                        jQuery(".sbi").filter(':hidden').sbiVisibilityChanged({
                            callback: function(element, visible) {
                                sbiSetPhotoHeight();
                            },
                            runOnLoad: false
                        });


                        //Fade photos on hover
                        jQuery('#sb_instagram .sbi_photo').each(function() {
                            $sbi_photo = jQuery(this);
                            $sbi_photo.hover(function() {
                                jQuery(this).fadeTo(200, 0.85);
                            }, function() {
                                jQuery(this).stop().fadeTo(500, 1);
                            });

                            //Add video icon to videos
                            if ($sbi_photo.closest('.sbi_item').hasClass('sbi_type_video')) {
                                if (!$sbi_photo.find('.sbi_playbtn').length) {
                                    $sbi_photo.append('<i class="fa fa-play sbi_playbtn"></i>');
                                }
                            }
                        });

                        //Sort posts by date
                        //only sort the new posts that are loaded in, not the whole feed, otherwise some photos will switch positions due to dates
                        $self.find('#sbi_images .sbi_item.sbi_new').sort(function (a, b) {
                            var aComp = jQuery(a).data('date'),
                                bComp = jQuery(b).data('date');

                            if (sortby === 'none') {
                                //Order by date
                                return bComp - aComp;
                            } else {
                                //Randomize
                                return (Math.round(Math.random())-0.5);
                            }

                        }).appendTo($self.find("#sbi_images"));

                        //Remove the new class after 500ms, once the sorting is done
                        setTimeout(function() {
                            jQuery('#sbi_images .sbi_item.sbi_new').removeClass('sbi_new');
                            //Reset the morePosts variable so we can check whether there are more posts every time the Load More button is clicked
                            morePosts = [];
                        }, 500);

                        function sbiGetItemSize()
                        {
                            $self.removeClass('sbi_small sbi_medium');
                            var sbiItemWidth = $self.find('.sbi_item').innerWidth();
                            if (sbiItemWidth > 120 && sbiItemWidth < 240) {
                                $self.addClass('sbi_medium');
                            } else if (sbiItemWidth <= 120) {
                                $self.addClass('sbi_small');
                            }
                        }
                        sbiGetItemSize();

                    }, // End 'after' function
                    error: function(data) {
                        var sbiErrorMsg = '',
                            sbiErrorDir = '',
                            sbiErrorResponse = data;

                        if (sbiErrorResponse.indexOf('access_token') > -1) {
                            sbiErrorMsg += '<p><b>Error: Access Token is not valid or has expired</b><br /><span>This error message is only visible to WordPress admins</span>';
                            sbiErrorDir = "<p>There's an issue with the Instagram Access Token that you are using. Please obtain a new Access Token on the plugin's Settings page.<br>If you continue to have an issue with your Access Token then please see <a href='https://smashballoon.com/my-instagram-access-token-keep-expiring/' target='_blank'>this FAQ</a> for more information.";
                            jQuery('#sb_instagram').empty().append('<p style="text-align: center;">Unable to show Instagram photos</p><div id="sbi_mod_error">' + sbiErrorMsg + sbiErrorDir + '</div>');
                            return;
                        } else if (sbiErrorResponse.indexOf('user does not exist') > -1 || sbiErrorResponse.indexOf('you cannot view this resource') > -1) {
                            window.sbiFeedMeta[$i].error = {
                                errorMsg    : '<p><b>Error: User ID <span class="sbiErrorIds">'+window.sbiFeedMeta[$i].idsInFeed[index]+'</span> does not exist, is invalid, or is private</b><br /><span>This error is only visible to WordPress admins</span>',
                                errorDir    : "<p>Please double check the Instagram User ID that you are using and ensure that it is valid and not from a private account. To find your User ID simply enter your Instagram user name into this <a href='https://smashballoon.com/instagram-feed/find-instagram-user-id/' target='_blank'>tool</a>.</p>"
                            };
                            if (!$self.find('#sbi_mod_error').length) {
                                $self.prepend('<div id="sbi_mod_error">'+window.sbiFeedMeta[$i].error.errorMsg+window.sbiFeedMeta[$i].error.errorDir+'</div>');
                            } else if ($self.find('.sbiErrorIds').text().indexOf(window.sbiFeedMeta[$i].idsInFeed[index]) == -1) {
                                $self.find('.sbiErrorIds').append(','+window.sbiFeedMeta[$i].idsInFeed[index]);
                            }
                        } else if (sbiErrorResponse.indexOf('No images were returned') > -1) {
                            window.sbiFeedMeta[$i].error = {
                                errorMsg    : '<p><b>Error: User ID <span class="sbiErrorNone">'+window.sbiFeedMeta[$i].idsInFeed[index]+'</span> has no posts</b><br /><span>This error is only visible to WordPress admins</span>',
                                errorDir    : "<p>If you are the owner of this account, make a post on Instagram to see it in your feed.</p>"
                            };
                            if (!$self.find('#sbi_mod_error.sbi_error_none').length) {
                                $self.prepend('<div id="sbi_mod_error" class="sbi_error_none">'+window.sbiFeedMeta[$i].error.errorMsg+window.sbiFeedMeta[$i].error.errorDir+'</div>');
                            } else if ($self.find('.sbiErrorNone').text().indexOf(window.sbiFeedMeta[$i].idsInFeed[index]) == -1) {
                                $self.find('.sbiErrorNone').append(','+window.sbiFeedMeta[$i].idsInFeed[index]);
                            }
                        }
                    }
                });

                userFeed.run();

            }); //End User ID array loop
        
        });

    }

    jQuery(document).ready(function() {
        sbi_init();
    });
} // end sbi_js_exists check
