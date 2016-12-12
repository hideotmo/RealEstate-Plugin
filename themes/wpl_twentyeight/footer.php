<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** get theme helper **/
global $wpl_helper;
$wpl_helper = isset($wpl_helper) ? $wpl_helper : new wpl_helper();

// Get Theme options
$theme_options = wpl_theme::get_wpl_theme_options();
?>
        <section id="top_footer_rows">
            <?php
            if($wpl_helper->is_front_page())
            {
                $wpl_helper->load_sidebar('sidebar-5');
            }
            ?>
        </section>
        <section id="top_footer">
            <div class="top_footer_cont container clearfix" itemscope itemtype="http://schema.org/RealEstateAgent">
                <?php
                    $wpl_helper->get_sidebar('sidebar-4');
                ?>
            </div>
        </section>
		<footer id="footer">
			<div class="footer_cont container clearfix <?php echo ($theme_options['footer'] ? 'footer_type_'.$theme_options['footer'] : ''); ?>">
				<!-- Footer Content -->
                <div class="row">

				<?php
				$theme_options['footer'] !== '3' ? $wpl_helper->wpl_social_icon('footer-menu-social-icons') : '';
                $realtyna_url = "http://wpl.realtyna.com";

				if($theme_options['footer_menu'])
				{
				?>
					<div class="footer_cont_left_top clearfix">
						<?php $wpl_helper->get_menu(array('theme_location'=>'footer-menu', 'menu_class'=>'nav-menu')); ?>	
					</div>
				<?php
				}
				if( $theme_options['footer_html'] )
				{
					echo '<div class="copyright">'.$theme_options['footer_html'].'</div>';	
				}
                if( $theme_options['footer'] !== '2')
                {
                    echo $wpl_helper->wpl_footer_logo();
                }
				?>

                </div>
				<!-- End of Footer Content -->
			</div>	
		</footer>
	</div>
    <?php
    $wpl_helper->style_selector_inc();
    $wpl_helper->wp_footer();

    echo (isset($theme_options['tracking_code']) ? $theme_options['tracking_code'] : '' );
    echo (isset($theme_options['body_code']) ? $theme_options['body_code'] : '' );  ?>
</body>
</html>