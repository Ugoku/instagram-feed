jQuery(document).ready(function($) {
    // Autofill the token and id
    var hash = window.location.hash;
    var token = hash.substring(14);
    var id = token.split('.')[0];

    function sbSaveToken(token) {
        jQuery.ajax({
            url: sbiA.ajax_url,
            type: 'post',
            data: {
                action: 'sbi_auto_save_tokens',
                access_token: token,
                just_tokens: true
            },
            success: function (data) {
                jQuery('.sb_get_token').append('<span class="sbi-success"><i class="fa fa-check-circle"></i> saved</span>');
                jQuery('#sb_instagram_at').after('<span class="sbi-success"><i class="fa fa-check-circle"></i> saved</span>');
            }
        });
    }

    // If there's a hash then autofill the token and id
    if (hash && !jQuery('#sbi_just_saved').length) {
        //$('#sbi_config').append('<div id="sbi_config_info"><p><b>Access Token: </b><input type="text" size=58 readonly value="'+token+'" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."></p><p><b>User ID: </b><input type="text" size=12 readonly value="'+id+'" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."></p><p><i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp; <b><span style="color: red;">Important:</span> Copy and paste</b> these into the fields below and click <b>"Save Changes"</b>.</p></div>');
        $('#sbi_config').append('<div id="sbi_config_info"><p class="sb_get_token"><b>Access Token: </b><input type="text" size=58 readonly value="'+token+'" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."></p><p><b>User ID: </b><input type="text" size=12 readonly value="'+id+'" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."></p></div>');
        if (jQuery('#sb_instagram_at').val() == '' && token.length > 40) {
            jQuery('#sb_instagram_at').val(token);
            var currentIDs = jQuery('#sb_instagram_user_id').val();
            if (!currentIDs) {
                jQuery('#sb_instagram_user_id').val(id);
            } else {
                jQuery('#sb_instagram_user_id').val(currentIDs + ',' + id);
            }
            sbSaveToken(token);
        } else {
            jQuery('.sb_get_token').append('<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Use This Token"></p>');
        }

    }

    $('.sb_get_token #submit').click(function(event) {
        event.preventDefault();
        $(this).closest('.submit').fadeOut();
        jQuery('#sb_instagram_at').val(token);
        sbSaveToken(token);
    });
    
    //Tooltips
    jQuery('#sbi_admin .sbi_tooltip_link').click(function() {
        jQuery(this).siblings('.sbi_tooltip').slideToggle();
    });

    //Shortcode labels
    jQuery('#sbi_admin label').click(function() {
		var $sbi_shortcode = jQuery(this).siblings('.sbi_shortcode');
		if ($sbi_shortcode.is(':visible')) {
		  jQuery(this).siblings('.sbi_shortcode').css('display', 'none');
		} else {
		  jQuery(this).siblings('.sbi_shortcode').css('display', 'block');
		}
	});
	jQuery('#sbi_admin label').hover(function() {
		if (jQuery(this).siblings('.sbi_shortcode').length > 0) {
			jQuery(this).attr('title', 'Click for shortcode option').append('<code class="sbi_shortcode_symbol">[]</code>');
		}
	}, function() {
		jQuery(this).find('.sbi_shortcode_symbol').remove();
	});


    //Check User ID is numeric
    jQuery("#sb_instagram_user_id").change(function() {
        var sbi_user_id = jQuery('#sb_instagram_user_id').val();
        var $sbi_user_id_error = $(this).closest('td').find('.sbi_user_id_error');

        if (sbi_user_id.match(/[^0-9, _.-]/)) {
            $sbi_user_id_error.fadeIn();
        } else {
            $sbi_user_id_error.fadeOut();
        }
    });

    //Support tab show video
    jQuery('#sbi-play-support-video').on('click', function(e) {
        e.preventDefault();
        jQuery('#sbi-support-video').show().attr('src', jQuery('#sbi-support-video').attr('src')+'&amp;autoplay=1');
    });
});
