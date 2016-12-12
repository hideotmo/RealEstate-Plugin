<?php
header("Content-type: text/css; charset: UTF-8");
$body               = '#'.$_GET['body'];
$preset             = isset($_GET['preset']) ? $_GET['preset'] : '';
$customColors       = $_GET['custom_colors'];

if(!$preset or $preset==='0'){
    $main_color         = ($_GET['main_color']=='29a9df' ? '' : '#'.$_GET['main_color']);
    $footer_bg          = '#'.$_GET['footer_bg'];
    $footertop_bg       = '#'.(isset($_GET['footertop_bg']) ? $_GET['footertop_bg'] : '');
    $footer_fontc        = '#'.(isset($_GET['footer_fontc']) ? $_GET['footer_fontc'] : '');
}

if($customColors == 0){
    $main_color = '';
    $body       = '#ffffff';
}

switch ($preset) {
    case '1':
        $main_color         = '#f8a70a';
        $footer_bg          = '#041126';
        break;
    case '2':
        $main_color         = '#3ab34a';
        $footer_bg          = '#191818';
        break;
    case '3':
        $main_color         = '#df640a';
        $footer_bg          = '#191818';
        break;
    case '4':
        $main_color         = '#f42323';
        $footer_bg          = '#0c0c0c';
        break;
    case '5':
        $main_color         = '#e18612';
        $footer_bg          = '#312c28';
        break;
    default:
        # code...
        break;
}

$page_width         = isset($_GET['width']) ? $_GET['width'] : 1100;
$body_pattern       = $_GET['body_pattern'];
$body_pattern_value = isset($_GET['body_pattern_value']) ? $_GET['body_pattern_value'] : '1';
$main_font          = isset($_GET['main_font']) ? $_GET['main_font'] : 'Lato';
$main_font_size     = isset($_GET['main_font_size']) ? $_GET['main_font_size'] : '13';
$header_font        = isset($_GET['header_font']) ? $_GET['header_font'] : 'Lato';
$header_font_size   = isset($_GET['header_font_size']) ? $_GET['header_font_size'] : '13';
$footer_font        = isset($_GET['footer_font']) ? $_GET['footer_font'] : 'Lato';
$footer_font_size   = isset($_GET['footer_font_size']) ? $_GET['footer_font_size'] : '13';

$plisting_font      = isset($_GET['plisting_font']) ? $_GET['plisting_font'] : 'BenchNine';
$plisting_font_size = isset($_GET['plisting_font_size']) ? $_GET['plisting_font_size'] : '3';

$pshow_price_font       = isset($_GET['pshow_price_font']) ? $_GET['pshow_price_font'] : 'BenchNine';
$pshow_price_font_size  = isset($_GET['pshow_price_font_size']) ? $_GET['pshow_price_font_size'] : '3';


$font_array = array($main_font , $header_font , $footer_font);
$google_fonts='';
foreach($font_array as $fonts){
    $google_fonts .=str_replace(' ','+',$fonts).'|';
}
?>
    /* Font Setting */
    .wpl-28-template-wp body{font-family:"<?php echo $main_font ?>";font-size:<?php echo $main_font_size.'px';?>;}
    .wpl-28-template-wp #header{font-family:"<?php echo $header_font ?>";font-size:<?php echo $header_font_size.'px';?>;}
    .wpl-28-template-wp #footer{font-family:"<?php echo $footer_font ?>";font-size:<?php echo $footer_font_size.'px';?>;}

    .wpl-28-template-wp .wpl_prp_title{font-family:"<?php echo $plisting_font ?>" !important;font-size:<?php echo $plisting_font_size; ?>em !important;}

    .wpl-28-template-wp .wpl_prp_show_container .price_box{font-family:"<?php echo $pshow_price_font; ?>" !important;font-size:<?php echo $pshow_price_font_size; ?>em !important;}

    /* Main Body Background */
    .wpl-28-template-wp body.boxed{background:<?php echo $body; ?>;}


    .wpl-28-template-wp .wpl-template-28 #header.sticky.boxed{left:50%;margin-left:-<?php echo $page_width / 2; ?>px;width:<?php echo $page_width; ?>px; max-width: 100%;}
    @media (min-width: 1200px){.wpl-28-template-wp .container, #main_box.container_full_width .wpl_plisting_top_sidebar_container, .wpl_property_show_default #main_box.container .tabs_box, .wpl_property_show_default #main_box.container .wpl_prp_container_content {width:<?php echo $page_width; ?>px !important;}}
<?php if($body_pattern){?>
    /* Body Pattern */
    .wpl-28-template-wp body.boxed{background-image:url(../images/pattern/<?php echo $body_pattern_value ?>/<?php echo $body_pattern_value ?>.png);background-repeat:repeat;}
<?php } ?>

    /* Main Dynamic Color */
<?php if($main_color){ ?>
    .wpl-28-template-wp #wpl_theme_options_container header{border-bottom:3px solid <?php echo $main_color; ?>;}

    .wpl-28-template-wp #wpl_theme_options_container header h2 ,
    .wpl-28-template-wp a:hover,.wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover ,
    .wpl-28-template-wp #header .header_top_right #top_social_icon #menu-social-icons li:hover ,
    .wpl-28-template-wp #header .header_bot_right .top_email i,.wpl-28-template-wp #header .header_bot_right .top_phone i ,
    .wpl-28-template-wp #commentform .required ,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata>a:before,.wpl-28-template-wp .comment-list li article footer .comment-metadata>.edit-link:before ,
    .wpl-28-template-wp #footer .footer_cont_left .footer_cont_left_top .nav-menu li a:hover,.wpl-28-template-wp #footer .footer_cont_left .footer_cont_left_top .nav-menu .comment-list li article footer .comment-metadata .edit-link:hover,.wpl-28-template-wp .comment-list #footer .footer_cont_left .footer_cont_left_top .nav-menu li article footer .comment-metadata .edit-link:hover ,
    .wpl-28-template-wp #main_box .post .entry-title a:hover,.wpl-28-template-wp #main_box .post .entry-title #content #content_l .comment-list li article footer .comment-metadata .edit-link:hover,.wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .post .entry-title .edit-link:hover ,
    .wpl-28-template-wp #main_box .post .entry-meta .byline a,.wpl-28-template-wp #main_box .post .entry-meta .byline #content #content_l .comment-list li article footer .comment-metadata .edit-link,.wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .post .entry-meta .byline .edit-link ,
    .wpl-28-template-wp #main_box .post .entry-date:before,.wpl-28-template-wp #main_box .post .comments-link:before,.wpl-28-template-wp #main_box .post .edit-link:before,.wpl-28-template-wp #main_box .post .post-format.gallery:before,.wpl-28-template-wp #main_box .post .post-format.aside:before,.wpl-28-template-wp #main_box .post .post-format.link:before,.wpl-28-template-wp #main_box .post .post-format.image:before,.wpl-28-template-wp #main_box .post .post-format.quote:before,.wpl-28-template-wp #main_box .post .post-format.video:before,.wpl-28-template-wp #main_box .post .post-format.audio:before,.wpl-28-template-wp #main_box .post footer.entry-meta .tag-links:before ,
    .wpl-28-template-wp .not_found_l .icon_404 ,
    .wpl-28-template-wp .not_found_r .menu-not-found-menu-container ul li:before ,
    .wpl-28-template-wp #main_not_found h2 span ,
    .wpl-28-template-wp #top_footer .widget .widget-title ,
    .wpl-28-template-wp #footer .footer_cont .nav-menu li > a:hover,
    .wpl-28-template-wp #top_social_icon li > a:hover,
    .wpl-28-template-wp #footer-menu-social-icons li > a:hover,
    .wpl-28-template-wp #toTop:hover ,
    .wpl-28-template-wp #top_footer .widget #footer_contact .phone ,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container .wpl_plist_sort_active ,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_listing_image_caption span ,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.feature span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.hot_offer span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.hot_offer span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.hot_offer span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.hot_offer span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.open_house span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.open_house span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.open_house span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.open_house span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.forclosure span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.forclosure span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.forclosure span,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.forclosure span,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Car::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Train::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Walk::after,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.agent_info .wpl_agent_info .wpl_agent_info_r .name,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.listing_rooms .wpl_rooms_list_container li .room_size,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.listing_attachments .wpl_attachments_list_container li .wpl_attachment_size ,

    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.feature.single::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.hot_offer::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.hot_offer::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.hot_offer::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.hot_offer::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.open_house::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.open_house::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.open_house::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.open_house::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.forclosure::after,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .wpl_prp_listing_image .rows.single.forclosure::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.forclosure::after,
    .wpl-28-template-wp .wpl_prp_container_content .wpl_prp_listing_image .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.single.forclosure::after,

    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes_title .title_color ,

    .wpl-28-template-wp .wpl_profile_container .wpl_profile_container_title span,
    .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container .wpl_plist_sort_active,
    .wpl-28-template-wp .wpl_profile_show_container .wpl_profile_container_title span

    .wpl-28-template-wp #main_box .post .entry-meta .byline a,
    .wpl-28-template-wp #main_box .post .entry-meta .byline #content #content_l .comment-list li article footer .comment-metadata .edit-link,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .post .entry-meta .byline .edit-link,
    .wpl-28-template-wp #main_box .page .entry-meta .byline a,
    .wpl-28-template-wp #main_box .page .entry-meta .byline #content #content_l .comment-list li article footer .comment-metadata .edit-link,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .page .entry-meta .byline .edit-link

    .wpl-28-template-wp #main_box .post .entry-date:before,
    .wpl-28-template-wp #main_box .post .comments-link:before,
    .wpl-28-template-wp #main_box .post .edit-link:before,
    .wpl-28-template-wp #main_box .post .post-format.gallery:before,
    .wpl-28-template-wp #main_box .post .post-format.aside:before,
    .wpl-28-template-wp #main_box .post .post-format.link:before,
    .wpl-28-template-wp #main_box .post .post-format.image:before,
    .wpl-28-template-wp #main_box .post .post-format.quote:before,
    .wpl-28-template-wp #main_box .post .post-format.video:before,
    .wpl-28-template-wp #main_box .post .post-format.audio:before,
    .wpl-28-template-wp #main_box .post footer.entry-meta .tag-links:before,
    .wpl-28-template-wp #main_box .page footer.entry-meta #main_box .post .tag-links:before,
    .wpl-28-template-wp #main_box .page .entry-date:before,
    .wpl-28-template-wp #main_box .page .comments-link:before,
    .wpl-28-template-wp #main_box .page .edit-link:before,
    .wpl-28-template-wp #main_box .page .post-format.gallery:before,
    .wpl-28-template-wp #main_box .page .post-format.aside:before,
    .wpl-28-template-wp #main_box .page .post-format.link:before,
    .wpl-28-template-wp #main_box .page .post-format.image:before,
    .wpl-28-template-wp #main_box .page .post-format.quote:before,
    .wpl-28-template-wp #main_box .page .post-format.video:before,
    .wpl-28-template-wp #main_box .page .post-format.audio:before,
    .wpl-28-template-wp #main_box .post footer.entry-meta #main_box .page .tag-links:before,
    .wpl-28-template-wp #main_box .page footer.entry-meta .tag-links:before,

    .wpl-28-template-wp #main_box .post .entry-title a:hover,
    .wpl-28-template-wp #main_box .post .entry-title #content #content_l .comment-list li article footer .comment-metadata .edit-link:hover,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .post .entry-title .edit-link:hover,
    .wpl-28-template-wp #main_box .page .entry-title a:hover,
    .wpl-28-template-wp #main_box .page .entry-title #content #content_l .comment-list li article footer .comment-metadata .edit-link:hover,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .page .entry-title .edit-link:hover,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes.details .wpl_prp_right_boxe_details_bot .price_box,
    #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_right .wpl_prp_right_boxes.details .wpl_prp_right_boxe_details_bot .price_box,
    .wpl-28-template-wp .nothing-found,
    .wpl-28-template-wp #main_box .page-content.nothing-found .search-not-found ul li:before,
    .wpl-28-template-wp .not_found_r ul li:before,
    .wpl-28-template-wp .wpl_prp_title,
    .wpl-28-template-wp .wpl_profile_container .wpl_profile_container_title .title,
    .wpl-28-template-wp body #breadcrump .breadcrumbs li,
    .wpl-28-template-wp #main_box .page-title.search-result-title span,
    .wpl-28-template-wp #top_footer_rows div.feature_container .theme_features .title,
    .wpl-28-template-wp .wpl_agents_widget_container .wpl_profile_container .wpl_profile_container_title .title,
    .wpl-28-template-wp #wpl_profile_listing_container .wpl_profile_container .wpl_profile_container_title a,
    .wpl-28-template-wp .wpl_profile_show_container .wpl_profile_container_title,
    .wpl-28-template-wp #header .top_email i, #header .top_phone i,
    .wpl-28-template-wp #header .header_type_8 #login_box a:hover, 
    .wpl-28-template-wp #header .header_type_8 #login_box .comment-list li article footer .comment-metadata .edit-link:hover, 
    .wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata #header .header_type_8 #login_box .edit-link:hover,
    .wpl-28-template-wp .wpl_prp_cont .price_box span,
    .wpl-28-template-wp .wpl_sort_options_container ul li div:hover,
    .wpl-28-template-wp .wpl_pagination_container .pagination li.active a,
    .wpl-28-template-wp #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_right .wpl_prp_right_boxes .wpl_prp_right_boxes_title,
    .wpl-28-template-wp #style_selector .styles_selector_main_title,
    .wpl-28-template-wp #header .header_type_6 #login_box a:hover,
    .wpl-28-template-wp .wpl_list_grid_switcher > div.active:before,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container ul li div:hover,
    .wpl-28-template-wp .jssor_nav_prev:before,.wpl-28-template-wp .jssor_nav_next:before,
    .wpl-28-template-wp .wpl-28-header-wp .nav-menu li > a:hover,
    .wpl-28-template-wp .wpl-28-header-wp .header_cont .nav-menu li.current_page_item > a,
    .wpl-28-template-wp .wpl-28-resp-menu-close-btn,
    .wpl-28-template-wp .wpl-28-resp-menu-btn:hover,
    #main_infowindow .main_infowindow_r .main_infowindow_title, #main_infowindow .main_infowindow_r .main_infowindow_r_b div.price,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail:hover,
    .wpl-28-header-wp .nav-menu li.current-menu-parent > a,
    .wpl-28-template-wp .wpl_prp_show_layout2_container .wpl_prp_show_detail_boxes_cont .rows span,
    .wpl-28-template-wp .wpl_prp_show_container .wpl-gallery-pshow-wp .lSSlideOuter .lSPager.lSpg>li.active a::before,
    .wpl-28-template-wp .wpl-crm-form .prow-separator,
    .wpl-28-template-wp .wpl-complex-unit-title,
    .wpl-28-template-wp .wpl-complex-unit-view-detail:after,
    .wpl-28-template-wp #wpl_profile_listing_main_container .wpl_sort_options_container ul li .wpl_plist_sort_active,
    .wpl-28-template-wp .wpl-save-search-wp .wpl-save-search-link,
    .wpl-28-template-wp #wpl_profile_show_container .wpl_profile_show_container_box .wpl_agent_info_c .wpl_profile_container_title,
    .wpl-28-template-wp #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.feature.single:after,
    .wpl-28-template-wp #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Car:after, #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Train:after, #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_cont .rows.neighborhood .Walk:after,
    .wpl-28-template-wp #wpl_prp_show_container .wpl_prp_container .wpl_prp_container_content .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.listing_rooms .wpl_rooms_list_container li .room_size
    {color:<?php echo $main_color; ?>;}

    .wpl-28-template-wp #wpl_theme_options_container footer:before ,
    .wpl-28-template-wp #content #content_l .comments-title::after ,
    .wpl-28-template-wp .comment-list li article .reply ,
    .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"]:hover ,
    .wpl-28-template-wp #commentform p input[type="submit"]:hover ,
    .wpl-28-template-wp #header .header_top_right #login_box a:hover,.wpl-28-template-wp #header .header_top_right #login_box .comment-list li article footer .comment-metadata .edit-link:hover,.wpl-28-template-wp .comment-list li article footer .comment-metadata #header .header_top_right #login_box .edit-link:hover ,
    .wpl-28-template-wp #reply-title::after,.wpl-28-template-wp #content #content_l #right_col aside .search-form #reply-title.screen-reader-text::after,.wpl-28-template-wp #content #right_col aside .search-form #content_l #reply-title.screen-reader-text::after ,
    .wpl-28-template-wp #main_box .single nav.navigation a:hover,.wpl-28-template-wp #main_box .single nav.navigation #content #content_l .comment-list li article footer .comment-metadata .edit-link:hover,.wpl-28-template-wp .comment-list li article footer .comment-metadata .single nav.navigation .edit-link:hover ,
    .wpl-28-template-wp .post-content-box .more-link,.wpl-28-template-wp  .post-content-box #content #content_l .comment-list li article footer .comment-metadata .more-link.edit-link, .wpl-28-template-wp .comment-list li article footer .comment-metadata #main_box .post .post-content-box .more-link.edit-link ,
    .wpl-28-template-wp #main_not_found h2::after ,
    .wpl-28-template-wp .not_found_r .search-form input[type="submit"]:hover ,
    .wpl-28-template-wp .flex-control-nav li .active,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container .wpl_plist_sort_active:before ,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_container .wpl_prp_listing_location .view_detail ,
    .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_title::after ,
    .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container .wpl_plist_sort_active:before,
    .wpl-28-template-wp #main_infowindow .main_infowindow_bott .view_detail,
    .wpl-28-template-wp .wpl_search_from_box .search_submit_box,
    .wpl-28-template-wp .wpl_search_from_box .search_submit_box .wpl_search_widget_submit,
    .wpl-28-template-wp #toTop:hover,
    .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit:hover,
    .wpl-28-template-wp #main_box .navigation span:hover,
    .wpl-28-template-wp #main_box .navigation .prev:hover,
    .wpl-28-template-wp #main_box .navigation .next:hover,
    .wpl-28-template-wp #main_box .navigation a:hover,
    .wpl-28-template-wp #main_box .navigation span:hover,
    .wpl-28-template-wp #main_box .navigation .prev:hover,
    .wpl-28-template-wp #main_box .navigation .next:hover,
    .wpl-28-template-wp .search-result-list .search-form .search-submit:hover,
    .wpl-28-template-wp .ei-slider-thumbs li.ei-slider-element,
    .wpl-28-template-wp div.ei-title a.more_info,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_top .wpl_prp_top_boxes.back,
    .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs li:before,
    .wpl-28-template-wp .wpl_profile_container ul li::after,
    .wpl-28-template-wp .wpl_profile_container ul li::after,
    .wpl-28-template-wp .wpl_profile_container .wpl_profile_picture .back,
    .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:hover:before,
    .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before,
    .wpl-28-template-wp .comment-list li article .reply a:hover,
    .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link:hover,
    .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link:hover,
    .wpl-28-template-wp #header .header_type_4 #top_bar_bg,
    .wpl-28-template-wp .wpl_agents_widget_container .wpl_profile_container ul li,
    .wpl-28-template-wp .wpl_agents_widget_container .wpl_profile_container .wpl_profile_picture .back,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.agent_info .wpl_agent_info .wpl_agent_info_l:hover .company_details .company_name,
    .wpl-28-template-wp .wpl_gallery_container #bx-pager .active,
    .wpl-28-template-wp .wpl-28-template-wp #commentform p input[type="submit"],
    .wpl-28-template-wp .wpl_search_slider_container .wpl_span_block .ui-slider .ui-slider-range,
    .wpl-28-template-wp .wpl_gallery_container #bx-pager a:hover,
    .wpl-28-template-wp input.btn-primary,
    .wpl-28-template-wp .jssor_thumb_prototype.av .jssor_thumb_frame,
    .wpl-28-template-wp .jssor_caption.more_info,
    .wpl-28-template-wp .wpcf7 input[type="submit"],
    .wpl-28-template-wp a#toTop:hover,
    .wpl-28-template-wp .wpl-slider-bx-pager-wp a.active, #bx-pager a.active,
    .wpl_prp_show_container .wpl-gallery-pshow-wp .lSSlideOuter .lslide::after,
    .wpl-crm-form-frontend .wpl-crm-grid-tab>ul>li.wpl-gen-tab-active-parent .wpl-crm-tab-number,
    .wpl-crm-form-submit-btn,.wpl-crm-form-submit-btn:hover,
    .wpl-28-template-wp #commentform p input[type="submit"],
    .wpl-28-template-wp .wpl-complex-tabs-wp>ul>li>a.wpl-gen-tab-active,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_property_listing_listings_container .grid_box .wpl_prp_top .wpl_prp_top_boxes.back .view_detail,
    .wpl-28-template-wp #wpl_profile_listing_container .wpl_profile_container ul li:after,
    .wpl-28-template-wp #wpl_profile_listing_main_container .wpl_sort_options_container ul li .wpl_plist_sort_active:before,
    .wpl-28-template-wp #wpl_profile_listing_container .wpl_profile_container .wpl_profile_picture div.back,
    .wpl-28-template-wp .wpl-save-search-wp .wpl-save-search-link:hover,
    .wpl-28-template-wp #wpl_profile_show_container .wpl_profile_show_container_box .wpl_agent_info_c ul li:before
    {background:<?php echo $main_color; ?>;}


    /* Border Color */
    .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs li.active,
    .wpl-28-template-wp #toTop,
    .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont:hover,
    .wpl-28-template-wp .wpl_profile_container:hover,
    .wpl-28-template-wp #top_footer_rows div.feature_container .theme_features:hover .icon,
    .wpl-28-template-wp #top_footer_rows #our_partner_icons .partner_container a:hover,
    .wpl-28-template-wp .wpl_pagination_container .pagination li.active a,
    .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.agent_info .wpl_agent_info .wpl_agent_info_l:hover .company_details,
    .wpl-28-template-wp #header,
    .wpl-28-template-wp .wpl_favorites_items,
    .wpl-28-template-wp input.btn-primary,
    .wpl-28-template-wp a#toTop,
    .wpl-complex-tabs-wp,
    .wpl-28-template-wp .wpl-save-search-wp .wpl-save-search-link:hover
    {border-color : <?php echo $main_color; ?>;}

    /* Extra */
    .wpl-28-template-wp .wpl_gallery_container #bx-pager .active img,
    .wpl-28-template-wp #content #right_col aside h3,.wpl-28-template-wp  #content #main_widget_area aside h3
    {border-color: <?php echo $main_color; ?>;}
    .wpl-28-template-wp .wpl-save-search-wp .wpl-save-search-link:hover
    {color:#fff;}
<?php } ?>
    .wpl-28-template-wp .wpl_profile_container ul li:hover::after{
        background-color:<?php echo $footer_bg; ?>;
    }
    /* Footer Background Color */
    .wpl-28-template-wp #footer{background:<?php echo $footer_bg; ?>;}
    .wpl-28-template-wp #top_footer{background:<?php echo $footertop_bg; ?>;}
    .wpl-28-template-wp #footer .nav-menu li a,.wpl-28-template-wp #footer [id*="social-icons"] li a,.wpl-28-template-wp #footer .copyright{color:<?php echo $footer_fontc; ?>;}

    .wpl-28-template-wp .wpl-28-resp-menu{background:<?php echo $main_color; ?>;opacity:0.9 !important;}

<?php
switch($preset){
    case(1):
        echo '
        /* Dark Blue Preset */
        /*.wpl-28-template-wp body, #carousel_box,.wpl-28-template-wp #main_box_container,.wpl-28-template-wp body #breadcrump,.wpl-28-template-wp #top_footer_rows{background-color:#e7e9ed;}*/
        .wpl-28-header-wp a,.wpl-28-template-wp #footer a{color:white;}
        .wpl-28-header-wp a:hover,.wpl-28-template-wp #footer a:hover{color:#f8a70a;}
        .wpl-28-template-wp #header{background:#0f264b;border-color:#f8a70a;color:white;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;}
        .wpl-28-template-wp #header .header_cont{background:#0f264b}
        .wpl-28-header-wp .header_type_0 .header_top_left::before,.wpl-28-header-wp .header_type_0 .header_top_left{background-color: rgba(0,0,0,0.2);}
        .wpl-28-header-wp .header_type_0 .header_top_left::after{border-left-color:rgba(0,0,0,0.2);}
        .wpl-28-header-wp .header_type_0 .col-md-4:after{display:none;}
        .wpl-28-header-wp .nav-menu li>ul{background:#0f264b}
        .wpl-28-header-wp .header_type_4 .header_top_bar{background:#f8a70a}

        .wpl-28-template-wp #header .header_bot_left .nav-menu li > ul{background:#0f264b;border-color:#0d192b}
        .wpl-28-template-wp #header .header_bot_left .nav-menu li::after,
        .wpl-28-template-wp #header .header_top ,
        .wpl-28-template-wp #header .header_bot_left .nav-menu li > ul li,
        .wpl-28-template-wp #top_footer .widget,
        .wpl-28-template-wp #top_footer .widget #footer_contact,
        .wpl-28-template-wp input[type="text"], .wpl-28-template-wp input[type="password"], .wpl-28-template-wp input[type="email"], .wpl-28-template-wp input[type="url"], .wpl-28-template-wp input[type="search"], .wpl-28-template-wp textarea
        {border-color:#0d192b}
        .wpl-28-template-wp #header .header_top_right #login_box a{background:#0a1b34;color:white;}
        .wpl-28-template-wp #header .header_top_right #login_box a:hover{background:#f8a70a;}
        .wpl-28-template-wp #header .header_bot_right .top_email, .wpl-28-template-wp #header .header_bot_right .top_phone{background:none;}
        .wpl-28-template-wp #header .header_bot_right .top_email i, .wpl-28-template-wp #header .header_bot_right .top_phone i{color:#f8a70a}
        .wpl-28-template-wp #toTop,
        .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"],
        .wpl-28-template-wp #commentform p input[type="submit"]
        {background:#0F264B;}
        .wpl-28-template-wp #toTop:hover{border-color:#0F264B;}
        .wpl-28-template-wp body #breadcrump{border-color:#0d192b;color:#f8a70a;}
        .wpl-28-template-wp body #breadcrump .breadcrumbs li{color:#f8a70a;}
        .wpl-28-template-wp body #breadcrump a{color:#0d192b;}

        .wpl-28-template-wp #content #right_col aside,
        .wpl-28-template-wp #content #right_col aside h3,
        .wpl-28-template-wp #content #right_col aside .search-form .screen-reader-text
        {border-color:#0F264B;}
        .wpl-28-template-wp .wpl_carousel_container .bx-wrapper{border-color:#f8a70a;}
        .wpl-28-template-wp .ei-slider-thumbs li a{background:#0F264B;}
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .wpl_search_from_box{border-bottom:1px solid #0f264b;}
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .widget-title,
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget #content #right_col aside .search-form .widget-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside .search-form #carousel_box .widget_wpl_search_widget .widget-title.screen-reader-text
        {background:#000f28;}
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .widget-title::after,
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget #content #right_col aside .search-form .widget-title.screen-reader-text::after,
        .wpl-28-template-wp #content #right_col aside .search-form #carousel_box .widget_wpl_search_widget .widget-title.screen-reader-text::after
        {border-color: #000f28 rgba(0,0,0,0) rgba(0,0,0,0) rgba(0,0,0,0);}
        .wpl-28-template-wp .wpl_search_from_box .more_search_option{border:1px solid #0c0c0c;color:#0c0c0c;}
        .wpl-28-template-wp .wpl_search_from_box .chosen-container::after{color:#0D192B;}
        .wpl-28-template-wp .wpl_search_from_box .chosen-drop,.wpl-28-template-wp  .chosen-container-single .chosen-search input[type="text"]{box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;color:black;}

        .wpl-28-template-wp #header .header_type_3 #top_bar_bg{background: #0D192B;}
        .wpl-28-template-wp #header .top_email, .wpl-28-template-wp #header .top_phone{background: none;}
        .wpl-28-template-wp #header .top_email i, .wpl-28-template-wp #header .top_phone i{color: #F8A70A;}
        .wpl-28-template-wp #header .search-form .search-field{background: #0D192B;color: white;border:none;}
        .wpl-28-template-wp #header .header_cont ::-webkit-input-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont :-moz-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont ::-moz-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont :-ms-input-placeholder {color: #FFFFFF !important;}

        .wpl-28-template-wp #header .header_type_4 #top_bar_bg{background: #0D192B;}
        .wpl-28-template-wp #header .header_type_4 .search-form .search-field{border: 1px solid #0F264B;}

        .wpl-28-template-wp #header .header_type_5 #top_bar_bg, .wpl-28-template-wp #header .header_type_5 .header_bot .nav-menu li::after{border-color: #0D192B;}
        .wpl-28-template-wp #header .header_type_5 .nav-menu li ul , .wpl-28-template-wp #header .header_type_7 .nav-menu li ul{background: #0F264B;border-color: #0D192B;}
        .wpl-28-template-wp #header .header_type_5 .nav-menu li ul li , .wpl-28-template-wp #header .header_type_7 .nav-menu li ul li{border-color: #0D192B;}

        .wpl-28-template-wp #header .header_type_6 #top_bar_bg{background: #F8A70A;}
        .wpl-28-template-wp #header .header_type_6 .header_top_bar a:hover{color: #0F264B !important;}

        .wpl-28-template-wp #header .header_type_7 .header_bot #login_box a , .wpl-28-template-wp #header .header_type_8 #login_box a{color: white;}
        .wpl-28-template-wp #header .header_type_7 .header_bot #login_box a:hover , .wpl-28-template-wp #header .header_type_8 #login_box a:hover{color: #F8A70A;}
        .wpl-28-template-wp #header .header_type_8 .header_top_bar{border-color: #0D192B;}

        .wpl-28-template-wp #top_footer_rows div.feature_container .theme_features .icon{background-color:#e7e9ed;box-shadow:0 0 0 2px #e7e9ed;-moz-box-shadow:0 0 0 2px #e7e9ed;-webkit-box-shadow:0 0 0 2px #e7e9ed;}
        .wpl-28-header-transparent .wpl-28-header-wp a,.wpl-28-header-transparent.wpl-28-template-wp #header,.wpl-28-header-transparent.wpl-28-template-wp #header .header_type_8 #login_box a{color:#666}
        
        /* 404 page*/
        .wpl-28-template-wp #main_not_found h1{border-color:#0F264B;background:none;color:#0F264B}
        .wpl-28-template-wp .not_found_r .search-form{border-color:#0F264B;background:#ffffff;}
        .wpl-28-template-wp .not_found_r .search-form input[type="submit"]{background:#0F264B;}
        .wpl-28-template-wp .not_found_r ul li a{color:#0F264B;}
        .wpl-28-template-wp .not_found_r ul li a:hover{color:#E18612;}

        /* property listing */
        .wpl-28-template-wp .wpl_property_listing_container .wpl_googlemap_container.wpl_googlemap_plisting,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont{border-color:#0F264B;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont:hover{border-color:#F8A70A;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container{border-color:#0F264B;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title{color:#0f264b;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont{border-color:#7082a0;background:#ffffff;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail{background:#0f264b;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li a{border-color:#0f264b;color:#0f264b;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.prev a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.next a{background:#0f264b;color:white !important;border-color:#0F264B;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont{padding-top:5px;}
        .wpl-28-template-wp #content .wpl_prp_top_boxes.back a.view_detail,.wpl-28-template-wp #content .wpl_prp_top_boxes.back a.view_detail:hover{color:#fff;}

        /* property Show */
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_container,
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs{border-color:#0f264b;}
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs li::after{background:#0f264b;}
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_container_content_title{border:1px solid #0f264b;background:white;color:#0f264b;}
        .wpl-28-template-wp .wpl_prp_show_container a{color:#0f264b;}
        .wpl-28-template-wp .wpl_prp_show_container a:hover{color:#f8a70a;}
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes,
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes:last-child{background:white;border-color:#0f264b;}
        .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_title{border-color:#0f264b;}
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.listing_attachments .wpl_attachments_list_container li{background:none;}

        /* Profile listing */
        //.wpl-28-template-wp .wpl_profile_container{background:#FFFFFF;border-color:#0f264b;}
        .wpl-28-template-wp .wpl_profile_container:hover{border-color:#F8A70A;}
        .wpl-28-template-wp .wpl_profile_container ul li{background:#0f264b;}
        .wpl-28-template-wp .wpl_profile_container ul li:hover,.wpl_profile_container ul li:hover::after{background:#F8A70A;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container{border-color:#0f264b;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container .wpl_sort_options_container_title{color:#0f264b;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li a{border-color:#0f264b;color:#0f264b;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.prev a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.next a{background:#0f264b;color:white;}

        /* Profile Show */
        .wpl-28-template-wp .wpl_profile_show_container{border-bottom:1px solid #0F264B;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_profile_container_title{color:#0F264B;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before{background:#0F264B;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:hover:before{background:#F8A70A;}

        /* Pages */
        .wpl-28-template-wp #content #right_col aside,.wpl-28-template-wp  .post,.wpl-28-template-wp  #main_box .page{background:white;}
        .wpl-28-template-wp #main_box .page.single-post , .wpl-28-template-wp .post.single-post{background:none;}
        .wpl-28-template-wp .post.single-post header.entry-header .entry-meta,
        .wpl-28-template-wp #main_box .page.single-post header.entry-header .entry-meta,
        .wpl-28-template-wp #content #content_l .comments-title,
        .wpl-28-template-wp #reply-title,
        .wpl-28-template-wp #content #content_l #right_col aside .search-form #reply-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside .search-form #content_l #reply-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside:last-child,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form,
        .wpl-28-template-wp #main_box .navigation,
        .wpl-28-template-wp #main_box .navigation a,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .edit-link,
        .wpl-28-template-wp #main_box .navigation span
        {border-color:#0f264b;}
        .wpl-28-template-wp .comment-list li article .comment-content{background:white;}
        .wpl-28-template-wp .comment-list li article .reply a,
        .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit,
        .wpl-28-template-wp .search-result-list .search-form .search-submit
        {background:#0f264b;}
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form{background:#ffffff;}
        .wpl-28-template-wp #top_footer{background:#0f264b;}

        .wpl-28-template-wp #main_box .navigation a,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .edit-link,
        .wpl-28-template-wp #main_box .navigation span,
        .wpl-28-template-wp #main_box .navigation .prev,
        .wpl-28-template-wp #main_box .navigation .next,
        .wpl-28-template-wp #main_box .navigation .prev,
        .wpl-28-template-wp #main_box .navigation .next
        {background:#0f264b;color:#ffffff;border-color:#0f264b;}

        .wpl-28-template-wp #main_box .navigation .current,
        .wpl-28-template-wp #main_box .navigation .current{border-color:#0f264b;color:#0f264b;}
        .wpl-28-template-wp .search-result-list .search-form{background:#ffffff;border-color:#0f264b;margin-bottom:10px;}';
        break;
    case(2):
        echo '
        /* Light Green Preset */
        .wpl-28-template-wp #header{border-color:#3ab34a;}
        /*.wpl-28-template-wp #header .top_logo{background-position:0 -359px;}*/
        .wpl-28-template-wp #header #login_box a{background:#3ab34a;color:white;}
        .wpl-28-template-wp #header #login_box a:hover{background:#666;}
        .wpl-28-template-wp #header .top_email, #header .top_phone{background:none;border:1px solid #c4e8c8;margin-left:2px;}
        .wpl-28-template-wp #header .top_email i, #header .top_phone i{color:#3ab34a}

        .wpl-28-template-wp #header .header_type_4 #top_bar_bg{background:#666666;}
        .wpl-28-template-wp #header .header_type_6 #top_bar_bg{background:#3ab34a;}
        .wpl-28-template-wp #header .header_type_7 #login_box a, .wpl-28-template-wp #header .header_type_8 #login_box a{color: #fff;}
        .wpl-28-template-wp #header .header_type_7 #login_box a:hover, .wpl-28-template-wp #header .header_type_8 #login_box a:hover{color: #3AB34A !important;background:none;}

        /* property listing */
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li span:hover
        {border-color:#3AB34A;color:#3AB34A}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail{background: #666666;}

        /* Profile listing */
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li span:hover
        {border-color:#3AB34A;color:#3AB34A}
        .wpl-28-template-wp .wpl_profile_container ul li,.wpl_profile_container ul li:hover::after{background: #666666;}

        /* Profile Show */
        .wpl-28-template-wp .wpl_profile_show_container  .wpl_profile_container_title{color:#3AB34A;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before{background:#666666;}

        .wpl-28-template-wp #commentform p input[type="submit"],
        .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"],
        .wpl-28-template-wp .comment-list li article .reply a,
        .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link,
        .wpl-28-template-wp .search-result-list .search-form .search-submit,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit,
        .wpl-28-template-wp .not_found_r .search-form input[type="submit"]{background:#666666;}

        .wpl-28-template-wp #main_box .navigation .current,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link,
        .wpl-28-template-wp #main_box .navigation .current{border-color:#3AB34A;color:#3AB34A}

        .wpl-28-template-wp #main_box .navigation .current:hover,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link:hover,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link:hover,
        .wpl-28-template-wp #main_box .navigation .current:hover{color:#FFFFFF}

        .wpl-28-template-wp #content #right_col aside h3,
        .wpl-28-template-wp #content #right_col aside .search-form .screen-reader-text{border-color:#666666;}
        .wpl-28-template-wp #top_footer{background:#3ab34a;border-top:3px solid #191818;}
        .wpl-28-template-wp #top_footer a{color:white;}
        .wpl-28-template-wp #top_footer a:hover{color:#191818;} 
        .wpl-28-template-wp #top_footer .widget h3, .wpl-28-template-wp #top_footer .widget #footer_contact .phone{color:#191818;text-shadow:none;-moz-text-shadow:none;-webkit-text-shadow:none;}
        .wpl-28-template-wp #top_footer .widget, .wpl-28-template-wp #top_footer .widget #footer_contact{border-color:#75ca81;}';
        break;
    case(3):
        echo '
        /* Light Orange Preset */
        .wpl-28-template-wp #header{border-color:#df640a;}
        /*.wpl-28-template-wp #header .top_logo{background-position:0 -419px;}*/
        .wpl-28-template-wp #header #login_box a{background:#df640a;color:white;}
        .wpl-28-template-wp #header #login_box a:hover{background:#666;}
        .wpl-28-template-wp #header .top_email, .wpl-28-template-wp #header .top_phone{background:none;margin-left:2px;}
        .wpl-28-template-wp #header .top_email i, .wpl-28-template-wp #header .top_phone i{color:#df640a}

        .wpl-28-template-wp #header .header_type_4 #top_bar_bg{background:#666666;}
        .wpl-28-template-wp #header .header_type_6 #top_bar_bg{background:#3ab34a;}
        .wpl-28-template-wp #header .header_type_7 #login_box a, .wpl-28-template-wp #header .header_type_8 #login_box a{color: #fff;}
        .wpl-28-template-wp #header .header_type_7 #login_box a:hover, .wpl-28-template-wp #header .header_type_8 #login_box a:hover{color: #df640a !important;background:none;}

        /* property listing */
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li span:hover
        {border-color:#DF640A;color:#DF640A}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail{background: #666666;}

        /* Profile listing */
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li span:hover
        {border-color:#DF640A;color:#DF640A}
        .wpl-28-template-wp .wpl_profile_container ul li,.wpl_profile_container ul li:hover::after{background: #666666;}

        /* Profile Show */
        .wpl-28-template-wp .wpl_profile_show_container  .wpl_profile_container_title{color:#DF640A;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before{background:#666666;}

        .wpl-28-template-wp #commentform p input[type="submit"],
        .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"],
        .wpl-28-template-wp .comment-list li article .reply a,
        .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link,
        .wpl-28-template-wp .search-result-list .search-form .search-submit,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit,
        .wpl-28-template-wp .not_found_r .search-form input[type="submit"]{background:#666666;}

        .wpl-28-template-wp #main_box .navigation .current,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link,
        .wpl-28-template-wp #main_box .navigation .current{border-color:#df640a;color:#df640a}

        .wpl-28-template-wp #main_box .navigation .current:hover,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link:hover,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link:hover,
        .wpl-28-template-wp #main_box .navigation .current:hover{color:#FFFFFF}

        .wpl-28-template-wp #content #right_col aside h3,
        .wpl-28-template-wp #content #right_col aside .search-form .screen-reader-text{border-color:#666666;}
        .wpl-28-template-wp #top_footer{background:#302f2f;}';
        break;
    case(4):
        echo '
        /* Light Red Preset */

        .wpl-28-template-wp #header{border-color:#F42323;}
        /*.wpl-28-template-wp #header .top_logo{background-position:0 -479px;}*/
        .wpl-28-template-wp #header #login_box a{background:#f42323;color:white;}
        .wpl-28-template-wp #header #login_box a:hover{background:#666;}
        .wpl-28-template-wp #header .top_email, #header .top_phone{background:none;margin-left:2px;}
        .wpl-28-template-wp #header .top_email i, #header .top_phone i{color:#F42323}

        .wpl-28-template-wp #header .header_type_4 #top_bar_bg{background:#666666;}
        .wpl-28-template-wp #header .header_type_6 #top_bar_bg{background:#3ab34a;}
        .wpl-28-template-wp #header .header_type_7 #login_box a, .wpl-28-template-wp #header .header_type_8 #login_box a{color: #fff;}
        .wpl-28-template-wp #header .header_type_7 #login_box a:hover, .wpl-28-template-wp #header .header_type_8 #login_box a:hover{color: #F42323 !important;background:none;}

        /* property listing */
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.active a
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li span:hover
        {border-color:#F42323;color:#F42323}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail{background: #666666;}

        /* Profile listing */
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li a:hover,
        .wpl-28-template-wp .wpl-28-template-wp .comment-list li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li article footer .comment-metadata .edit-link:hover,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li span:hover
        {border-color:#F42323;color:#F42323}

        .wpl-28-template-wp .wpl_profile_container ul li,.wpl_profile_container ul li:hover::after{background: #666666;}

        /* Profile Show */
        .wpl-28-template-wp .wpl_profile_show_container  .wpl_profile_container_title{color:#F42323;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before{background:#666666;}

        .wpl-28-template-wp #commentform p input[type="submit"],
        .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"],
        .wpl-28-template-wp .comment-list li article .reply a,
        .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link,
        .wpl-28-template-wp .search-result-list .search-form .search-submit,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit,
        .wpl-28-template-wp .not_found_r .search-form input[type="submit"]{background:#666666;}

        .wpl-28-template-wp #main_box .navigation .current,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link,
        .wpl-28-template-wp #main_box .navigation .current{border-color:#F42323;color:#F42323}

        .wpl-28-template-wp #main_box .navigation .current:hover,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .current.edit-link:hover,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .current.edit-link:hover,
        .wpl-28-template-wp #main_box .navigation .current:hover{color:#FFFFFF}

        .wpl-28-template-wp #content #right_col aside h3,
        .wpl-28-template-wp #content #right_col aside .search-form .screen-reader-text{border-color:#666666;}
        .wpl-28-template-wp #top_footer{background:#272727;}';
        break;
    case(5):
        echo '
        /* Brown Preset */
        /*.wpl-28-template-wp body, #carousel_box, #main_box_container, #breadcrump, #top_footer_rows{background-color:#e7e9ed;}*/
        .wpl-28-template-wp a{color:white;}
        .wpl-28-template-wp a:hover{color:#f8a70a;}
        .wpl-28-template-wp #header{background:#312C28;color:white;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;}
        .wpl-28-template-wp #header .header_cont{background:#312C28;}
        .wpl-28-header-wp .header_type_0 .header_top_left::before,.wpl-28-header-wp .header_type_0 .header_top_left{background-color: rgba(0,0,0,0.2);}
        .wpl-28-header-wp .header_type_0 .header_top_left::after{border-left-color:rgba(0,0,0,0.2);}
        .wpl-28-header-wp .header_type_0 .col-md-4:after{display:none;}
        .wpl-28-header-wp .nav-menu li>ul{background:#312C28;border-color:#5C5553}

        .wpl-28-template-wp #header .header_bot_left .nav-menu li > ul{background:#312C28;border-color:#5C5553}
        .wpl-28-template-wp #header .header_bot_left .nav-menu li:after,
        .wpl-28-template-wp #header .header_top ,
        .wpl-28-template-wp #header .header_bot_left .nav-menu li > ul li,
        .wpl-28-template-wp #top_footer .widget,
        .wpl-28-template-wp #top_footer .widget #footer_contact,
        .wpl-28-template-wp input[type="text"], input[type="password"], .wpl-28-template-wp input[type="email"], .wpl-28-template-wp input[type="url"], .wpl-28-template-wp input[type="search"], .wpl-28-template-wp textarea
        {border-color:#5C5553}
        .wpl-28-template-wp #header .header_top_right #login_box a{background:#0a1b34;color:white;}
        .wpl-28-template-wp #header .header_top_right #login_box a:hover{background:#f8a70a;}
        .wpl-28-template-wp #header .header_bot_right .top_email, .wpl-28-template-wp #header .header_bot_right .top_phone{background:none;}
        .wpl-28-template-wp #header .header_bot_right .top_email i, .wpl-28-template-wp #header .header_bot_right .top_phone i{color:#f8a70a}
        .wpl-28-template-wp #toTop,
        .wpl-28-template-wp #content #right_col aside .search-form input[type="submit"],
        .wpl-28-template-wp #commentform p input[type="submit"]
        {background:#49423F;}
        .wpl-28-template-wp #toTop:hover{border-color:#0F264B;}
        .wpl-28-template-wp body #breadcrump{border-color:#0d192b;color:#312C28;}
        .wpl-28-template-wp body #breadcrump a{color:#312C28;}

        .wpl-28-template-wp #content #right_col aside,
        .wpl-28-template-wp #content #right_col aside h3,
        .wpl-28-template-wp #content #right_col aside .search-form .screen-reader-text
        {border-color:#312C28;}

        .wpl-28-template-wp .wpl_carousel_container .bx-wrapper{border-color:#f8a70a;}

        .wpl-28-template-wp .ei-slider-thumbs li a{background:#312C28;}

        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .wpl_search_from_box{border-bottom:1px solid #312C28;}
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .widget-title,
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget #content #right_col aside .search-form .widget-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside .search-form #carousel_box .widget_wpl_search_widget .widget-title.screen-reader-text
        {background:#000f28;}
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget .widget-title::after,
        .wpl-28-template-wp #carousel_box .widget_wpl_search_widget #content #right_col aside .search-form .widget-title.screen-reader-text::after,
        .wpl-28-template-wp #content #right_col aside .search-form #carousel_box .widget_wpl_search_widget .widget-title.screen-reader-text::after
        {border-color: #000f28 rgba(0,0,0,0) rgba(0,0,0,0) rgba(0,0,0,0);}
        .wpl-28-template-wp .wpl_search_from_box .more_search_option{border:1px solid #312C28;border-top-color:#e7e9ed;color:#312C28;}
        /*.wpl-28-template-wp .wpl_search_from_box .search_submit_box .wpl_search_widget_submit{padding:0.4em 0 1em 1em;}*/
        .wpl-28-template-wp .wpl_search_from_box .chosen-container::after{color:#0D192B;}
        .wpl-28-template-wp .wpl_search_from_box .chosen-drop, .wpl-28-template-wp .chosen-container-single .chosen-search input[type="text"]{box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;color:black;}

        .wpl-28-template-wp #header .header_type_3 #top_bar_bg{background: #0D192B;}
        .wpl-28-template-wp #header .top_email, .wpl-28-template-wp #header .top_phone{background: none;}
        .wpl-28-template-wp #header .top_email i, .wpl-28-template-wp #header .top_phone i{color: #F8A70A;}
        .wpl-28-template-wp #header .search-form .search-field{background: #49423F;color: white;border:none;}
        .wpl-28-template-wp #header .header_cont ::-webkit-input-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont :-moz-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont ::-moz-placeholder {color: #FFFFFF !important;}
        .wpl-28-template-wp #header .header_cont :-ms-input-placeholder {color: #FFFFFF !important;}

        .wpl-28-template-wp #header .header_type_4 #top_bar_bg{background: #E18612;}
        .wpl-28-template-wp #header .header_type_4 .search-form .search-field{border: 1px solid #312C28;}

        .wpl-28-template-wp #header .header_type_5 #top_bar_bg, #header .header_type_5 .header_bot .nav-menu li::after{border-color: #E18612;}
        .wpl-28-template-wp #header .header_type_5 .nav-menu li ul , .wpl-28-template-wp #header .header_type_7 .nav-menu li ul{background: #0F264B;border-color: #0D192B;}
        .wpl-28-template-wp #header .header_type_5 .nav-menu li ul li , .wpl-28-template-wp #header .header_type_7 .nav-menu li ul li{border-color: #0D192B;}

        .wpl-28-template-wp #header .header_type_6 #top_bar_bg{background: #F8A70A;}
        .wpl-28-template-wp #header .header_type_6 .header_top_bar a:hover{color: #0F264B !important;}

        .wpl-28-template-wp #header .header_type_7 .header_bot #login_box a , .wpl-28-template-wp #header .header_type_8 #login_box a{color: white;}
        .wpl-28-template-wp #header .header_type_7 .header_bot #login_box a:hover , .wpl-28-template-wp #header .header_type_8 #login_box a:hover{color: #F8A70A;}
        .wpl-28-template-wp #header .header_type_8 .header_top_bar{border-color: #5C5553;}

        .wpl-28-template-wp #top_footer_rows div.feature_container .theme_features .icon{background-color:#e7e9ed;box-shadow:0 0 0 2px #e7e9ed;-moz-box-shadow:0 0 0 2px #e7e9ed;-webkit-box-shadow:0 0 0 2px #e7e9ed;}
        .wpl-28-header-transparent .wpl-28-header-wp a,.wpl-28-header-transparent.wpl-28-template-wp #header,.wpl-28-header-transparent.wpl-28-template-wp #header .header_type_8 #login_box a{color:#666}
        /* 404 page*/
        .wpl-28-template-wp #main_not_found h1{border-color:#312C28;background:none;color:#312C28}
        .wpl-28-template-wp .not_found_r .search-form{border-color:#312C28;background:#ffffff;}
        .wpl-28-template-wp .not_found_r .search-form input[type="submit"]{background:#312C28;}
        .wpl-28-template-wp .not_found_r ul li a{color:#312C28;}
        .wpl-28-template-wp .not_found_r ul li a:hover{color:#E18612;}

        /* property listing */
        .wpl-28-template-wp .wpl_property_listing_container .wpl_googlemap_container.wpl_googlemap_plisting,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont{border-color:#312C28;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container{border-color:#312C28;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title{color:#312C28;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont{border-color:#312C28;background:#ffffff;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_prp_cont .wpl_prp_bot .view_detail{background:#312C28;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li a{border-color:#312C28;color:#312C28;}
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.prev a,
        .wpl-28-template-wp .wpl_property_listing_container .wpl_pagination_container .pagination li.next a{background:#312C28;color:white;border-color:#312C28;}
        .wpl-28-template-wp #content .wpl_prp_top_boxes.back a.view_detail,.wpl-28-template-wp #content .wpl_prp_top_boxes.back a.view_detail:hover{color:#fff;}

        /* property Show */
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_container,
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs{border-color:#312C28;}
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_show_tabs .tabs_box .tabs li::after{background:#312C28;}
        .wpl-28-template-wp .wpl_prp_show_container .wpl_prp_container_content .wpl_prp_container_content_title{border:1px solid #312C28;background:white;color:#312C28;}
        .wpl-28-template-wp .wpl_prp_show_container a{color:#312C28;}
        .wpl-28-template-wp .wpl_prp_show_container a:hover{color:#f8a70a;}
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes,
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_right_boxes:last-child{background:white;border-color:#312C28;}
        .wpl-28-template-wp .wpl_prp_container_content_left .wpl_prp_show_detail_boxes .wpl_prp_show_detail_boxes_title{border-color:#312C28;}
        .wpl-28-template-wp .wpl_prp_container_content_right .wpl_prp_show_position2 .wpl_prp_right_boxes.listing_attachments .wpl_attachments_list_container li{background:none;}

        /* Profile listing */
        //.wpl-28-template-wp .wpl_profile_container{background:#FFFFFF;border-color:#312C28;}
        .wpl-28-template-wp .wpl_profile_container ul li,.wpl-28-template-wp .wpl_profile_container ul li:hover::after{background:#312C28;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container{border-color:#312C28;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_sort_options_container .wpl_sort_options_container_title{color:#312C28;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li a{border-color:#0f264b;color:#312C28;}
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.active a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.prev a,
        .wpl-28-template-wp .wpl-profile-listing-wp .wpl_pagination_container .pagination li.next a{background:#312C28;color:white;border-color:#0f264b;}

        /* Profile Show */
        .wpl-28-template-wp .wpl_profile_show_container{border-bottom:1px solid #312C28;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_profile_container_title{color:#312C28;}
        .wpl-28-template-wp .wpl_profile_show_container .wpl_agent_info_c ul li:before{background:#312C28;}

        /* Pages */
        .wpl-28-template-wp #content a{color:#312C28;}
        .wpl-28-template-wp #content a:hover{color:#f8a70a;}
        .wpl-28-template-wp #content #right_col aside, .wpl-28-template-wp .post, #main_box .page{background:white;}
        .wpl-28-template-wp #main_box .page.single-post , .wpl-28-template-wp .post.single-post{background:none;}
        .wpl-28-template-wp .post.single-post header.entry-header .entry-meta,
        .wpl-28-template-wp #main_box .page.single-post header.entry-header .entry-meta,
        .wpl-28-template-wp #content #content_l .comments-title,
        .wpl-28-template-wp #reply-title,
        .wpl-28-template-wp #content #content_l #right_col aside .search-form #reply-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside .search-form #content_l #reply-title.screen-reader-text,
        .wpl-28-template-wp #content #right_col aside:last-child,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form,
        .wpl-28-template-wp #main_box .navigation,
        .wpl-28-template-wp #main_box .navigation a,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .edit-link,
        .wpl-28-template-wp #main_box .navigation span
        {border-color:#312C28;}
        .wpl-28-template-wp .comment-list li article .comment-content{background:white;}
        .wpl-28-template-wp .comment-list li article .reply a,
        .wpl-28-template-wp .comment-list li article .reply footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .reply .edit-link,
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form .search-submit,
        .wpl-28-template-wp .search-result-list .search-form .search-submit
        {background:#312C28;}
        .wpl-28-template-wp #main_box .page-content.nothing-found .search-form{background:#ffffff;}
        .wpl-28-template-wp #top_footer{background:#49423F;}

        .wpl-28-template-wp #main_box .navigation a,
        .wpl-28-template-wp #main_box .navigation #content #content_l .comment-list li article footer .comment-metadata .edit-link,
        .wpl-28-template-wp .comment-list li article footer .comment-metadata .navigation .edit-link,
        .wpl-28-template-wp #main_box .navigation span,
        .wpl-28-template-wp #main_box .navigation .prev,
        .wpl-28-template-wp #main_box .navigation .next,
        .wpl-28-template-wp #main_box .navigation .prev,
        .wpl-28-template-wp #main_box .navigation .next
        {background:#312C28;color:#ffffff;border-color:#312C28;}

        .wpl-28-template-wp #main_box .navigation .current,
        .wpl-28-template-wp #main_box .navigation .current{border-color:#0f264b;color:#0f264b;}
        .wpl-28-template-wp .search-result-list .search-form{background:#ffffff;border-color:#0f264b;margin-bottom:10px;}';
        break;
}
?>