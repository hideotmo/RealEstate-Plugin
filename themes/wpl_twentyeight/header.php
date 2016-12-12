<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();

// Get Theme options
$theme_options = wpl_theme::get_wpl_theme_options();

if($wpl_helper->getVar('theme', '') == 'dark')
    $theme_options['theme']='dark';

?>

<!doctype html>
<html <?php $wpl_helper->language_attributes(); ?> class="wpl-28-template-wp<?php
            echo ($wpl_helper->is_front_page() ? ' wpl-28-front-page' : '');
            echo ($theme_options['theme'] ? ' wpl-28-color-' . $theme_options['theme'] : '');
            echo ($theme_options['layout_listing_hover'] ? ' wpl-28-listing-layout-'. $theme_options['layout_listing_hover'] : '');
            echo ($theme_options['layout_listing_list_boxes'] ? ' wpl-28-listing-'. $theme_options['layout_listing_list_boxes'] : '');
            echo ($theme_options['layout_m_carousel_text'] ? ' wpl-28-m-carousel-'. $theme_options['layout_m_carousel_text'] : '');
            echo ($theme_options['h_transparent'] == 1 ? ' wpl-28-header-transparent' : '');
            echo ($theme_options['sticky_header'] == 1 ? ' wpl-28-header-sticky' : '');
            echo ($theme_options['default_listing_layout'] ? ' wpl-28-listing-d-layout-' . $theme_options['default_listing_layout'] : '');
            echo ' wpl_28_'.wpl_theme::getVar('wplview');
        ?>">
<head>
    <meta charset="utf-8">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <link id="wpl-28-favicon" rel="shortcut icon" href="<?php echo ($theme_options['favicon'])? $theme_options['favicon'] : get_template_directory_uri().'/assets/images/favicon.ico'  ?>" />
    <?php $wpl_helper->wpl_head(); ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            // Property Listing Google Map

            _mapHeight = parseInt(jQuery('.wpl_googlemap_container.wpl_googlemap_plisting').attr('data-wpl-height'));

            jQuery('.wpl_googlemap_container.wpl_googlemap_plisting').prepend('<div id="google_map_handle"><?php _e('Properties On Map', 'wplt') ?></div>');


            var _maxHeight = parseInt(jQuery('.wpl_googlemap_container.wpl_googlemap_plisting').attr('data-wpl-height')),
                _minHeight = 0;

            jQuery('.wpl_googlemap_container.wpl_googlemap_plisting #google_map_handle').click(function () {
                var _currentHeight = jQuery('.wpl_googlemap_container.wpl_googlemap_plisting').outerHeight();

                if (_currentHeight < _maxHeight)
                    _minHeight = _currentHeight;

                if (jQuery(this).hasClass('active')) {
                    jQuery(this).removeClass('active').parent('.wpl_googlemap_plisting').css("max-height", _minHeight).animate({height: '15%'});
                } else {
                    jQuery(this).addClass('active').parent('.wpl_googlemap_plisting').css("max-height", "none").animate({height: '100%'});
                }
            })
        });
    </script>
    <!--[if lte IE 8]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <?php if(isset($pie) and $pie==1): ?>
    <style>
        {
            behavior: url(js/PIE.htc)
        ;
        }
    </style>
    <?php endif; ?>
    <![endif]-->
    <?php
    echo(isset($theme_options['head_code']) ? stripslashes($theme_options['head_code']) : '');
    if (isset($theme_options['css_code'])) {
        echo '<style type="text/css">' . $theme_options['css_code'] . '</style>';
    }
    ?>
</head>
<body <?php ($theme_options['layout'] == '2' ? $wpl_helper->body_class('boxed') : $wpl_helper->body_class()) ?> >
<div id="wrapper" <?php echo($theme_options['layout'] == '2' ? 'class="boxed container"' : '');
if ($theme_options['layout'] == '2' and $theme_options['width']) {
    echo 'style="max-width:' . $theme_options['width'] . 'px"';
}
?>>
    <header id="header"  class="wpl-28-header-wp<?php
        echo ($theme_options['layout'] == '2' ? ' boxed container' : '');
        echo ($theme_options['header'] ? ' wpl-28-header-type-' . $theme_options['header'] . '-wp' : '');
    ?>"
        <?php if ($theme_options['layout'] == '2' and $theme_options['width']) echo 'style="max-width:' . $theme_options['width'] . 'px"';  ?> >
        <div class="wpl-28-header-holder">

        <?php
        # Header Content

        switch ($theme_options['header']) {
            case '0':
                ?>
                <div class="header_type_0 header_cont container clearfix ">
                    <div class="col-md-4">
                        <div class="header_top_left">
                            <?php if (is_home() || is_front_page()) { ?>
                                <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                            <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="wpl-28-main-menu-wp wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                            <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                        </div>

                    </div>
                    <div class="col-md-2 visible-md-block visible-lg-block">
                        <div id="login_box">
                            <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link">
                                    <i class="icon-login"></i>
                                </a>
                                <?php if (get_option('users_can_register')): ?>
                                    <a href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link">
                                        <i class="icon-user"></i>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                    <i class="icon-logout"></i>
                                </a>
                                <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                    $membership = new wpl_addon_membership(); ?>
                                    <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                        <i class="icon-user"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php
                break;
            case '1':
                ?>
                <div class="header_type_1 container header_cont clearfix">
                    <div class="row">
                        <div class="header_top col-md-4">
                            <div class="header_top_left">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                        </div>
                        <div class="header_bot col-md-8">
                            <div class="header_bot_left wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>
                            <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                <?php dynamic_sidebar("sidebar-8"); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <?php
                break;
            case '2':
                ?>
                <div class="container header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top clearfix">
                            <div class="col-md-4 header_top_left">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                            <div class="col-md-8 header_top_right">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <div id="login_box">
                                    <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                        <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link"><i
                                                class="icon-login"></i><?php _e('Login to Account', 'wplt') ?>
                                        </a>
                                        <?php if (get_option('users_can_register')): ?>
                                            <a href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link"><i
                                                    class="icon-user"></i><?php _e('Register', 'wplt') ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link"><i
                                                class="icon-logout"></i><?php _e('Logout', 'wplt') ?></a>
                                        <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                            $membership = new wpl_addon_membership(); ?>
                                            <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                                <i class="icon-user"></i><?php _e('Dashboard', 'wplt') ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div id="top_social_icon">
                                    <?php echo $wpl_helper->wpl_social_icon('menu-social-icons'); ?>
                                </div>

                            </div>
                        </div>
                        <div class="header_bot clearfix">
                            <div class="header_bot_left col-md-8 wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>
                            <div class="header_bot_right col-md-4">
                                <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                    <div class="top_phone"><i class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                <?php
                                endif;
                                if (isset($theme_options['email']) and trim($theme_options['email']) != ''):
                                    if ($theme_options['mailto'] == 1) {
                                        echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                    } else {
                                        echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                    }
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '3':
                ?>
                <div class="header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top_bar clearfix">
                            <div class="container">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <div id="top_social_icon">
                                    <?php echo $wpl_helper->wpl_social_icon('menu-social-icons'); ?>
                                </div>
                                <div class="header_top_bar_r">
                                    <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                        <div class="top_phone"><i
                                                class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                    <?php
                                    endif;
                                    if (isset($theme_options['email']) and trim($theme_options['email']) != ''):
                                        if ($theme_options['mailto'] == 1) {
                                            echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                        } else {
                                            echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                        }
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="header_top clearfix">
                                <div class="col-md-4 header_top_left">
                                    <?php if (is_home() || is_front_page()) { ?>
                                        <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                    <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                                </div>
                                <div class="col-md-8 header_top_right  visible-md-block visible-lg-block">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                            <div class="header_bot clearfix">
                                <div class="header_bot_left wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                    <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '4':
                ?>
                <div class="header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top_bar clearfix">
                            <div class="container">
                                <div id="top_social_icon" class="col-md-6">
                                    <?php echo $wpl_helper->wpl_social_icon('menu-social-icons'); ?>
                                </div>
                                <div class="header_top_bar_r col-md-6">
                                    <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                        <?php dynamic_sidebar("sidebar-8"); ?>
                                    </div>
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="header_top clearfix">
                                <div class="header_top_left">
                                    <?php if (is_home() || is_front_page()) { ?>
                                        <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                    <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                                </div>
                            </div>
                            <div class="header_bot clearfix">
                                <div class="header_bot_left wpl-28-main-menu-has-overflow  visible-md-block visible-lg-block">
                                    <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '5':
                ?>
                <div class="header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="header_top clearfix">
                        <div class="container">
                            <div class="header_bot_right col-md-4">
                                <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                    <div class="top_phone"><i
                                            class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                <?php
                                endif;
                                if (isset($theme_options['email']) and trim($theme_options['email']) != ''):
                                    ?>
                                    <div class="top_email"><i class="icon-contact"></i>
                                        <?php
                                        if ($theme_options['mailto'] == 1) {
                                            echo '<a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a>';
                                        } else {
                                            echo antispambot($theme_options['email']);
                                        }
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="col-md-8 header_top_right">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <?php echo $wpl_helper->wpl_social_icon('menu-social-icons'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="header_bot clearfix">
                            <div class="col-md-4">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                            <div class="header_bot_left col-md-8 wpl-28-main-menu-has-overflow  visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
                break;
            case '6':
                ?>
                <div class="header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top_bar clearfix">
                            <div class="container">
                                <div id="top_social_icon" class="col-md-8">
                                    <?php echo $wpl_helper->wpl_social_icon('menu-social-icons'); ?>
                                </div>
                                <div class="header_top_bar_r">
                                    <div id="login_box">
                                        <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                            <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link"><i
                                                    class="icon-login"></i><?php _e('Login to Account', 'wplt') ?>
                                            </a>
                                            <?php if (get_option('users_can_register')): ?><a
                                                href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link"><i
                                                    class="icon-user"></i><?php _e('Register', 'wplt') ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                                <i class="icon-logout"></i><?php _e('Logout', 'wplt') ?>
                                            </a>
                                            <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                                $membership = new wpl_addon_membership(); ?>
                                                <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                                    <i class="icon-user"></i><?php _e('Dashboard', 'wplt') ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="header_top clearfix">
                                <div class="col-md-4 header_top_left">
                                    <?php if (is_home() || is_front_page()) { ?>
                                        <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                    <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                                </div>
                                <div class="col-md-8 header_top_right visible-md-block visible-lg-block">
                                    <div class="header_lang_unit_switcher">
                                        <?php dynamic_sidebar("sidebar-8"); ?>
                                    </div>
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                            <div class="header_bot clearfix">
                                <div class="header_bot_left col-md-8 wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                    <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                                </div>
                                <div class="header_bot_right col-md-4">
                                    <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                        <div class="top_phone"><i
                                                class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                    <?php
                                    endif;
                                    if (isset($theme_options['email']) and trim($theme_options['email']) != ''):

                                        if ($theme_options['mailto'] == 1) {
                                            echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                        } else {
                                            echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                        }

                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '7':
                ?>
                <div class="container header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top clearfix">
                            <div class="col-md-4 header_top_left">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                            <div class="col-md-8 header_top_right wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>
                        </div>
                        <div class="header_bot clearfix">
                            <div class="header_bot_left col-md-6">
                                <div id="login_box">
                                    <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                        <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link"><i
                                                class="icon-login"></i><?php _e('Login to Account', 'wplt') ?>
                                        </a>
                                        <?php if (get_option('users_can_register')): ?><a
                                            href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link"><i
                                                class="icon-user"></i><?php _e('Register', 'wplt') ?>
                                            </a><?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                            <i class="icon-logout"></i><?php _e('Logout', 'wplt') ?>
                                        </a>
                                        <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                            $membership = new wpl_addon_membership(); ?>
                                            <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                                <i class="icon-user"></i><?php _e('Dashboard', 'wplt') ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="header_bot_right col-md-6">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                    <div class="top_phone"><i
                                            class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                <?php
                                endif;
                                if (isset($theme_options['email']) and trim($theme_options['email']) != ''):

                                    if ($theme_options['mailto'] == 1) {
                                        echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                    } else {
                                        echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                    }

                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '8':
                ?>
                <div class="container header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top_bar clearfix">
                            <div id="login_box" class="col-md-6">
                                <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                    <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link"><i
                                            class="icon-login"></i><?php _e('Login to Account', 'wplt') ?></a>
                                    <?php if (get_option('users_can_register')): ?><a
                                        href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link"><i
                                            class="icon-user"></i><?php _e('Register', 'wplt') ?>
                                        </a><?php endif; ?>
                                <?php else: ?>
                                    <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                        <i class="icon-logout"></i><?php _e('Logout', 'wplt') ?>
                                    </a>
                                    <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                        $membership = new wpl_addon_membership(); ?>
                                        <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                            <i class="icon-user"></i><?php _e('Dashboard', 'wplt') ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="header_top_bar_r col-md-6">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                    <div class="top_phone"><i
                                            class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                <?php
                                endif;
                                if (isset($theme_options['email']) and trim($theme_options['email']) != ''):
                                    if ($theme_options['mailto'] == 1) {
                                        echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                    } else {
                                        echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                    }
                                endif;
                                ?>
                            </div>
                        </div>
                        <div class="header_top clearfix">
                            <div class="col-md-4 header_top_left">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                            <div class="col-md-8 header_top_right">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        <div class="header_bot clearfix">
                            <div class="header_bot_left wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '9':
                ?>
                <div class="container header_cont clearfix <?php echo($theme_options['header'] ? 'header_type_' . $theme_options['header'] : ''); ?>">
                    <div class="row">
                        <div class="header_top_bar clearfix">
                            <div id="login_box" class="col-md-6">
                                <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                    <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link"><i
                                            class="icon-login"></i><?php _e('Login to Account', 'wplt') ?></a>
                                    <?php if (get_option('users_can_register')): ?>
                                        <a href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link"><i
                                            class="icon-user"></i><?php _e('Register', 'wplt') ?>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                        <i class="icon-logout"></i><?php _e('Logout', 'wplt') ?>
                                    </a>
                                    <?php if(class_exists('wpl_global') and wpl_global::check_addon('membership') and wpl_global::get_setting('membership_user_action_urls') == '1'):
                                        $membership = new wpl_addon_membership(); ?>
                                        <a href="<?php echo $membership->URL('dashboard'); ?>" id="membership_link">
                                            <i class="icon-user"></i><?php _e('Dashboard', 'wplt') ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="header_top_bar_r col-md-6">
                                <div class="header_lang_unit_switcher visible-md-block visible-lg-block">
                                    <?php dynamic_sidebar("sidebar-8"); ?>
                                </div>
                                <?php if (isset($theme_options['tel']) and trim($theme_options['tel']) != ''): ?>
                                    <div class="top_phone"><i
                                            class="icon-mobile"></i><?php echo $theme_options['tel'] ?></div>
                                <?php
                                endif;
                                if (isset($theme_options['email']) and trim($theme_options['email']) != ''):
                                    if ($theme_options['mailto'] == 1) {
                                        echo '<div class="top_email"><i class="icon-contact"></i><a href="mailto:' . $theme_options['email'] . '">' . antispambot($theme_options['email']) . '</a></div>';
                                    } else {
                                        echo '<div class="top_email"><i class="icon-contact"></i>' . antispambot($theme_options['email']) . '</div>';
                                    }
                                endif;
                                ?>
                            </div>
                        </div>
                        <div class="header_top clearfix">
                            <div class="col-md-4 header_top_left">
                                <?php if (is_home() || is_front_page()) { ?>
                                    <h1 id="logo"><?php echo $wpl_helper->wpl_top_logo(); ?></h1>
                                <?php } else { ?> <?php echo $wpl_helper->wpl_top_logo(); ?> <?php } ?>
                            </div>
                            <div class="col-md-8 header_top_right">
                                <?php
                                if ($wpl_helper->wpl_top_second_logo(true)) {
                                    echo '<h3 style="float:right;margin: -1.5rem 0 0 0;">';
                                    echo $wpl_helper->wpl_top_second_logo();
                                    echo '</h3>';
                                } ?>
                            </div>
                        </div>
                        <div class="header_bot clearfix">
                            <div class="header_bot_left col-md-9 wpl-28-main-menu-has-overflow visible-md-block visible-lg-block">
                                <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>
                            </div>
                            <div class="header_bot_right col-md-3" >
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
            case '10':
                    // Add your custom header codes in here. (Important: You should use this customization in child-theme)
                break;
        }
        // End of Header
        ?>

            <div class="wpl-28-resp-menu-wp hidden-md hidden-lg">
                <button class="wpl-28-resp-menu-btn"></button>
                <div class="wpl-28-resp-menu-overlay-wp">
                    <button class="wpl-28-resp-menu-close-btn"></button>
                    <div class="wpl-28-resp-menu">
                        <?php echo $wpl_helper->wpl_top_logo(); ?>

                        <?php $wpl_helper->get_menu(array('theme_location' => 'top-menu', 'menu_class' => 'nav-menu')); ?>

                        <div class="wpl-28-resp-login-wp">
                            <ul class="nav-menu">
                                <?php if (!$wpl_helper->is_user_logged_in()): ?>
                                    <li>
                                        <a href="<?php echo $wpl_helper->wp_login_url(); ?>" id="login_link">
                                            <i class="icon-login"></i>
                                            <span><?php echo __('Login to Account', 'wplt') ?></span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="<?php echo $wpl_helper->wp_logout_url(); ?>" id="logout_link">
                                            <i class="icon-logout"></i>
                                            <span><?php echo __('Logout', 'wplt') ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (!$wpl_helper->is_user_logged_in() && get_option('users_can_register')): ?>
                                    <li>
                                        <a href="<?php echo $wpl_helper->wp_registration_url(); ?>" id="register_link">
                                            <i class="icon-user"></i>
                                            <span><?php echo __('Register', 'wplt') ?></span>
                                        </a>
                                    </li>

                                <?php endif; ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </header>


    <?php if(is_active_sidebar('sidebar-3') || is_active_sidebar('sidebar-7')): ?>
        <section id="carousel_box">
            <?php
            if ($wpl_helper->is_front_page()) {
                $wpl_helper->load_sidebar('sidebar-3');
            }
            ?>
            <div id="right_fixed_sidebar">
                <?php $wpl_helper->load_sidebar('sidebar-7'); ?>
            </div>
        </section>
    <?php endif ?>

    <?php if(is_active_sidebar('sidebar-6')): ?>
        <section id="left_slide_col">
            <?php $wpl_helper->load_sidebar('sidebar-6'); ?>
        </section>
    <?php endif ?>

    <?php
    if ((!is_home() && !is_front_page()) && !wpl_theme::getVar('wplview')) {
        echo '<section id="breadcrump" class="container clearfix">';
        $wpl_helper->wpl_breadcrumbs();
        echo '</section>';
    }
    ?>
