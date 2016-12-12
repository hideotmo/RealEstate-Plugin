/*!
 * WPL28 JS Library
 * @Copyright Realtyna Inc. Co 2015
 * @Author UI Department of Realtyna Inc.
 * @Version 3.1.0
 */


// Update header / Footer / Preset / Pattern value
function update_values(field, id) {
    field_select_val = id.replace(field + '_', '');
    jQuery('.' + field + '_select div').removeClass('selected');
    jQuery('#' + id).addClass('selected');
    jQuery('.' + field + '_select input[type="hidden"]').val(field_select_val);
}

function wpl_run_ajax_query(url, request_str, ajax_loader, data_type, ajax_type) {
    if (!data_type)
        data_type = "JSON";
    if (!ajax_type)
        ajax_type = "POST";
    ajax_result = jQuery.ajax(
        {
            type: ajax_type,
            dataType: data_type,
            url: url,
            data: request_str,
            success: function (data) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (ajax_loader)
                    jQuery(ajax_loader).html('');
            }
        });
    return ajax_result;
}


function document_ready() {
    jQuery("#wpl_theme_options_container select").chosen({
        disable_search_threshold: 10,
        no_results_text: "Oops, nothing found!"
    });
}

jQuery(document).ready(function () {
    jQuery("body").bind("ajaxComplete", function (e, xhr, settings) {
        setTimeout('document_ready()', 50);
    });

    document_ready();
});


//region Closure Codes

(function($, window, document){

    var version = '3.1.0';
    //This should be ID of main elements.
    var newVersionArray = [
        'wpl_theme_options_footer_columns',
        'wpl_theme_options_instagram',
        'wpl_theme_options_telegram'
    ];



    $(function(){

        for(var i=0; i < newVersionArray.length; ++i){
            $('label[for="'+ newVersionArray[i]+ '"]').after('<div class="wpl-28-config-it-new">New</div>');
        }


        //Initialize WP Color Picker
        $('.my-color-field').wpColorPicker();

        // Use bootstrap tab function for template backend
        $('.left-panel .tab-container a').click(function (e) {
            e.preventDefault()
            $(this).tab('show');
        })

        //region = Uploader

        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        $('.wpl-28-upload-btn').on('click', function (e) {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(this);
            var id = button.attr('id').replace('_button', '');

            _custom_media = true;
            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    jQuery("#" + id).val(attachment.url);
                    if (jQuery("#" + id + "_img").length > 0) {
                        jQuery("#" + id + "_img").attr('src', attachment.url);
                    }
                    else {
                        button.parent('.wpl-28-config-uploader').prepend('<i class="glyphicon glyphicon-remove"></i><img src="' + attachment.url + '" id="' + id + '_img" />');
                    }
                } else {
                    return _orig_send_attachment.apply(this, [props, attachment]);
                };
            }

            wp.media.editor.open(button);
            return false;
        });

        //Remove selected images
        $('.wpl-28-config-uploader .glyphicon.glyphicon-remove').on('click', function () {
            $(this).next('img').remove();
            $(this).next('input[type="hidden"]').val('');
            $(this).remove();
        });

        $('#wpl_theme_options_flex_slider_button').click(function (e) {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = jQuery(this);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {

                    button.parent('.wpl-28-config-uploader').next('.img_container').append('<div class="flex_slider_imgs"><i class="glyphicon glyphicon-remove"></i><img src="' + attachment.url + '" class="img_flex_slider" /></div>');
                    if (button.next("#" + id).val()) {
                        button.next("#" + id).val(button.next("#" + id).val() + ';' + attachment.url);
                    }
                    else {
                        button.next("#" + id).val(attachment.url);
                    }
                } else {
                    return _orig_send_attachment.apply(this, [props, attachment]);
                }
                ;
            }

            wp.media.editor.open(button);
            return false;
        });

        $('.img_container').on("click", '.glyphicon.glyphicon-remove', function () {
            image = jQuery(this).next('img').attr('src');
            images_values = jQuery('#wpl_theme_options_flex_slider').val();

            jQuery(this).parent('.flex_slider_imgs').remove();

            var new_image_values = images_values.replace(image + ';', '');
            new_image_values = new_image_values.replace(image, '');

            jQuery('#wpl_theme_options_flex_slider').val(new_image_values);
        });

        $('.add_media').on('click', function () {
            _custom_media = false;
        });

        //endregion

        if($('.wpl-28-theme-config-wp').length > 0){
            var _leftSideBarTop = $('.wpl-28-theme-config-wp .left-panel').offset().top - 32,
                _leftSideBarHeight = $('.wpl-28-theme-config-wp .left-panel').outerHeight();

            if($(document).scrollTop() > _leftSideBarTop){
                $('.wpl-28-theme-config-wp .left-panel').addClass('wpl-28-config-sticky');
                $('.wpl-28-theme-config-wp .left-panel').css('top', $(document).scrollTop() - _leftSideBarTop + 20);
            }

            $(window).scroll(function(){
                if($(document).scrollTop() > _leftSideBarTop && $('.wpl-28-theme-config-wp .right-panel .active').outerHeight() > _leftSideBarHeight) {
                    $('.wpl-28-theme-config-wp .left-panel').addClass('wpl-28-config-sticky');
                    $('.wpl-28-theme-config-wp .left-panel').css('top', $(document).scrollTop() - _leftSideBarTop + 20);
                } else{
                    $('.wpl-28-theme-config-wp .left-panel').removeClass('wpl-28-config-sticky');
                    $('.wpl-28-theme-config-wp .left-panel').css('top', 0);
                }
            });
        }

    });

})(jQuery, window, document);

//endregion