<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = $wpl_helper ? $wpl_helper : new wpl_helper();

$wpl_helper->get_head();
?>
<section id="main_box_container">
    <div id="main_not_found" class="container">
        <h1>
            <?php _e('Page not found', 'wplt'); ?>
        </h1>

        <div class="not_found_l col-md-6">
            <div class="icon_404"><?php _e('404', 'wplt'); ?></div>
        </div>
        <div class="not_found_r col-md-6">
            <?php
            get_search_form();
            wp_nav_menu(array(
                'theme_location'    =>'404-menu',
                'items_wrap'        => '<div id="not_found_menu_title">'.__('Here are some useful links:', 'wplt').'</div><ul id="%1$s" class="%2$s">%3$s</ul>'));
            ?>
        </div>
    </div>
</section>
<?php
$wpl_helper->get_footer();