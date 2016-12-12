/*!
 * WPL28 JS Library
 * @Copyright Realtyna Inc. Co 2014
 * @Author UI Department of Realtyna Inc.
 */

jQuery(document).ready(function()
{
	jQuery().UItoTop({
		text:'',
		easingType: 'easeOutQuart'
	});

	current_url = window.location;

	//jQuery('body.blog #main_box, #wpl_property_listing_container, #wpl_profile_listing_main_container, .wpl_prp_container_content, #wpl_prp_show_container .tabs_box, #wpl_profile_show_container').addClass('container');
	//jQuery('.wpl_view_container').addClass('container');

	jQuery('[id*="social-icons"]').tooltip();


	// Style Selector
	jQuery('#style_selector #options_layout').change(function()
	{
		container_selector  = jQuery('#wrapper');
		header_selector		= jQuery('header#header, #top_bar_bg');
        patterns_selector   = jQuery('#style_selector .styles_selector_boxes.pattern');
		if(jQuery(this).val() == 2){
			container_selector.css('max-width','1200px').addClass('boxed container');
			header_selector.addClass('boxed container').css('max-width','1200px');
            patterns_selector.fadeIn();

		}else{
			container_selector.removeClass('boxed container').css('max-width','none');
			header_selector.removeClass('boxed container').css('max-width','none');
            jQuery('#top_bar_bg').css('width', '100%');
            patterns_selector.fadeOut();
        }
	});
	jQuery('#style_selector #options_theme').change(function()
	{
		if(jQuery(this).val() == 1){
			current_url = jQuery('#wpl-theme-style-css').attr('href');
			jQuery('#wpl-theme-style-css').attr('href', current_url.replace('-dark', ''))
		}else{
			window.location = jQuery(this).val();
		}
	});
	jQuery('[id*=pattern_]').click(function()
	{
        pattern_bg = jQuery(this).css('background-image');
		jQuery('body').css({
			'background-image'	: pattern_bg ,
			'background-repeat'	:'repeat'
		});
	});
	jQuery('[id*=preset_]').click(function()
	{
		preset_id 		= jQuery(this).attr('id').replace('preset_', '');
		theme_style 	= jQuery('#wpl-theme-style-dynamic-css').attr('href');
		current_preset 	= theme_style.substring(theme_style.indexOf("preset=") + 7, theme_style.indexOf("preset=") + 8);
		new_preset		= jQuery('#wpl-theme-style-dynamic-css').attr('href').replace("preset="+ current_preset, "preset="+ preset_id);
		jQuery('#wpl-theme-style-dynamic-css').attr('href',new_preset);
        jQuery('[id*=preset_]').removeClass('selected');
        jQuery(this).addClass('selected');
	});
	jQuery('#style_selector_handle').click(function()
	{
		if(jQuery(this).hasClass('closed'))
		{
			jQuery('#style_selector').animate({left:'0'});
			jQuery(this).removeClass('closed')
		}
		else
		{
			jQuery('#style_selector').animate({left:'-170'});
			jQuery(this).addClass('closed')
		}
	});
	// End of Style Selector

    // Left slide
    jQuery('.left_slide_handle').on('click',function(){
        if(jQuery(this).hasClass('closed'))
        {
            jQuery(this).parent("div").animate({left:'0'});
            jQuery(this).removeClass('closed')
        }
        else
        {
            jQuery(this).parent("div").animate({left:'-290'});
            jQuery(this).addClass('closed')
        }
    });
    // End of left slide
	// Right slide
	jQuery('.wpl-credential-handle,.wpl-disclaimer-handle').on('click',function(){
		if(jQuery(this).hasClass('closed'))
		{
			jQuery(this).parents(".wpl-right-slide").animate({right:'0'});
			jQuery(this).removeClass('closed')
		}
		else
		{
			jQuery(this).parents(".wpl-right-slide").animate({right:'-135px'});
			jQuery(this).addClass('closed')
		}
	});
	// End of Right slide

    // Parallax setting for Agent
    jQuery('#top_footer_rows .widget_wpl_agents_widget').prepend('<div class="background"></div>');

    jQuery(window).scroll(function() {
        var parallax_elemets = jQuery("#top_footer_rows .widget_wpl_agents_widget .background, #top_footer_rows .widget_wpl_carousel_widget");
        var yPos = -(jQuery(window).scrollTop() / 15);
        var coords = 'center '+ yPos + 'px';

        parallax_elemets.css({ backgroundPosition: coords });
    });

    // Top footer rows add classes
    parent_container = jQuery('#top_footer_rows .widget');
    child_boxes_selectors = 'ul, #calendar_wrap, .search-form, .tagcloud, .menu-long-menu-container'
    parent_container.children(child_boxes_selectors).addClass('container');

	// Equal heights for New Featured
	parent_container = jQuery('#top_footer_rows .feature_container');
	parent_container.find('> div').equalHeight();

});

function wpl_run_ajax_query(url, request_str, ajax_loader, data_type, ajax_type){
    if (!data_type)
    {
    	data_type = "JSON";
    }
        
    if (!ajax_type)
    {
    	ajax_type = "POST";
    }        
    ajax_result = jQuery.ajax(
            {
                type: ajax_type,
                dataType: data_type,
                url: url,
                data: request_str,
                success: function(data)
                {
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    if (ajax_loader)
                    {
                    	jQuery(ajax_loader).html('');
                    }
                }
            });
    return ajax_result;
}

(function($,window, document){


	$(function(){

		//region = Responsive Menu

		$('.wpl-28-resp-menu-overlay-wp').appendTo('body');

		$('.wpl-28-resp-menu-btn').on('click', function(){
			$('.wpl-28-resp-menu-overlay-wp').fadeIn(300, function(){
				$('.wpl-28-resp-menu').toggleClass('wpl-28-resp-menu-show');
				$('body,html').addClass('wpl-28-resp-menu-enable');
			});
		});

		$('.wpl-28-resp-menu-overlay-wp').on('click', function(e){
			e.stopPropagation();

			if(e.target == $('.wpl-28-resp-menu-overlay-wp').get(0)){
				$('.wpl-28-resp-menu').toggleClass('wpl-28-resp-menu-show');
				$('.wpl-28-resp-menu-overlay-wp').fadeOut();
				$('body,html').removeClass('wpl-28-resp-menu-enable');
			}

		});

		$('.wpl-28-resp-menu-close-btn').on('click', function(){
			$('.wpl-28-resp-menu').toggleClass('wpl-28-resp-menu-show');
			$('.wpl-28-resp-menu-overlay-wp').fadeOut();
			$('body,html').removeClass('wpl-28-resp-menu-enable');
		});

		//endregion

		/*$('.wpl_property_listing_list_view_container').imagesLoaded().always( function( instance ) {
			var imageHeight = $('.grid_box').eq(0).find('.wpl_gallery_container img').height();
			//$('.grid_box .wpl_prp_top').css('height', imageHeight);
		});*/

		if($.fn.hasOwnProperty('chosen')){
			$('.wpl_page_size_options').chosen({
				disable_search_threshold: 10
			});
			$('.header_lang_unit_switcher select').chosen();
		}
		$('.wpl-28-resp-menu .menu-item-has-children').each(function(){
			$(this).children('a').append('<span class="wpl-28-resp-menu-collapsible-btn"></span>');
		});
		$('.wpl-28-resp-menu .wpl-28-resp-menu-collapsible-btn').off('click').on('click', function(e){
			if($(this).hasClass('wpl-28-resp-menu-expand'))
			{
				$(this).closest('li').children('ul').slideUp();
				$(this).removeClass('wpl-28-resp-menu-expand');
			}
			else
			{
				$(this).closest('li').children('ul').slideDown();
				$(this).addClass('wpl-28-resp-menu-expand');
			}
			e.preventDefault();
		});

		// Add Snap class to necessary elements
		//$('.wpl_sort_options_container').prepend('<div class="wpl-28-snap" style="height: 1px; width: 100%;" />');


		/*var _ignoreFields = 'search_field_container_11';
		$('.wpl_search_from_box_bot').find('.datepicker_type').addClass('wpl-28-search-w-1-1');
		$('.wpl_search_from_box_bot').find('.minmax_type').addClass('wpl-28-search-w-1-2');
		$('.wpl_search_from_box_bot').find('.select_type').addClass('wpl-28-search-w-1-3');
		$('.wpl_search_from_box_bot').find('.minmax_selectbox_plus_type, .yesno_type').addClass('wpl-28-search-w-1-4');*/


		//Overflow of menu
		//if($('.header_type_0').length > 0 || $('.header_type_1').length > 0){

			var _menuWidth = 0,
				_menuContainerWidth = 0,
				_menuItems = $('.wpl-28-main-menu-has-overflow .nav-menu > li');

			_menuContainerWidth = $('.wpl-28-main-menu-has-overflow > div').outerWidth();

			_menuItems.each(function(){
				_menuWidth += $(this).outerWidth();
				console.log('Main Item: ' ,$(this).find('a').text());
			});

			if(_menuWidth > (_menuContainerWidth)){
				$('.wpl-28-main-menu-has-overflow .nav-menu').append('<li class="wpl-28-menu-overflow"><button></button><ul class="sub-menu" style="display: none;"></ul></li>')

				var _tempWidth = 0,
					_overflowIndex = -1;

				_menuItems.each(function(index){
					_tempWidth += $(this).outerWidth();

					if(_overflowIndex === -1 && _tempWidth > _menuContainerWidth){
						_overflowIndex = index;
					}

				});

				for(var i = _overflowIndex; i < _menuItems.length; ++i){
					console.log(_menuItems.eq(i).find('a').text());
					_menuItems.eq(i).appendTo('.wpl-28-menu-overflow ul');
				}
			}

			$('.wpl-28-main-menu-has-overflow, .wpl-28-menu-overflow ul').show();

		//}

		//region = Set Default Listing Layout
		var _maxHasClickCheck = 0;
		var hasClick = setInterval(function () {

			_maxHasClickCheck++;

			if(document.getElementById('list_view') !== null){
				ev = $._data(document.getElementById('list_view'), 'events');

				if(ev && ev.click){

					//region = Manage Change Layout

					$('.wpl-28-listing-outstanding #map_view').on('click', function(){
						$('.wpl_prp_cont').removeClass('grid_box row_box').addClass('map_box');
					});

					$('.wpl-28-listing-outstanding #list_view').on('click', function(){
						$('.wpl_prp_cont').removeClass('grid_box map_box').addClass('row_box');
					});

					$('.wpl-28-listing-outstanding #grid_view').on('click', function(){
						$('.wpl_prp_cont').removeClass('row_box map_box').addClass('grid_box');
					});

					//endregion

					$('.wpl-28-listing-d-layout-grid #grid_view').trigger('click');
					$('.wpl-28-listing-d-layout-list #list_view').trigger('click');
					$('.wpl-28-listing-d-layout-map #map_view').trigger('click');

					clearInterval(hasClick);
				}else if(_maxHasClickCheck === 10){
					clearInterval(hasClick);
				};
			}else{
				clearInterval(hasClick);
			}


		},500);
		//endregion



		$(window).load(function(){

			/*var _headerHeight = $('.wpl-28-header-transparent .wpl-28-header-holder').eq(0).outerHeight();

			if($('.wpl-28-header-transparent #header').length > 0 && $('.wpl-28-front-page').length === 0){

				$('#main_box_container').css({
					marginTop: _headerHeight
				});
			}

			var _snapElements = ['.wpl_googlemap_plisting'];
			$(_snapElements.join(',')).addClass('wpl-28-snap');

			$(document).scrollsnap({
				snaps: '.wpl-28-snap',
				proximity: 90,
				offset: -90
			});*/

			var _headerHeight = $('.wpl-28-header-holder').eq(0).outerHeight();
			$(window).scroll(function()
			{
				if($(document).scrollTop() > _headerHeight)
				{
					$('.wpl-28-header-sticky #header').addClass('sticky');
				}
				else
				{
					$('.wpl-28-header-sticky #header').removeClass('sticky');
				}
			});

		});
	});

})(jQuery, window, document);

