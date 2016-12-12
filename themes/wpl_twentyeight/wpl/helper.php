<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/** including theme libraries **/
require_once WPLT_ABSPATH. 'wpl' .DS. 'theme.php';
require_once WPLT_ABSPATH. 'wpl' .DS. 'scss.php';

// Get Theme options
$theme_options = wpl_theme::get_wpl_theme_options();

class wpl_helper extends wpl_theme
{
	var $theme_name = "WPL TwentyEight";
	var $theme_options = array();

	public function init()
	{



		add_action('admin_enqueue_scripts', array($this, 'admin_script_initialize'));
		add_action('wp_enqueue_scripts', array($this, 'wpl_theme_header'));
		add_action('widgets_init', array($this, 'register_sidebars'));
		add_action('init', array($this, 'register_menus'));
		add_action('after_setup_theme', array($this, 'theme_features'));
		add_action('admin_menu', array($this, 'set_theme_menu'));
		add_action('admin_init', array($this, 'register_theme_configs'));
		add_action('after_setup_theme', array($this, 'theme_activated'));

        add_filter('wp_title', array($this, 'wpl_title'), 10, 2);


        if(!is_admin())
        {
            add_filter('script_loader_src', array($this, 'wpl_remove_script_version'), 15, 1);
            add_filter('style_loader_src', array($this, 'wpl_remove_script_version'), 15, 1);
        }

		$this->theme_options = wpl_theme::get_wpl_theme_options();

		$function = wpl_theme::getVar('wpl_function');
		if($function == 'save_theme_options')
		{
			$this->theme_options = wpl_theme::save_theme_options();
		}

		add_shortcode('wpl_fs', array($this, 'felx_slider'));
		add_filter('widget_text', 'do_shortcode');

		/** add page builder **/
		if(file_exists(WPLT_ABSPATH. 'plugins' .DS. 'RTPB' .DS. 'RTPB.php'))
		{
			include_once WPLT_ABSPATH. 'plugins' .DS. 'RTPB' .DS. 'RTPB.php';
		}
        
        /** Make the theme updatable **/
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_latest_version'));
	}
    
    public function check_latest_version($transient)
    {
        if(empty($transient->checked)) return $transient;
        
        $slug = basename(dirname(dirname(__FILE__)));
        
        $args = array('command'=>'wp_update', 'format'=>'serialize', 'theme'=>'wpl_twentyeight', 'slug'=>$slug, 'action'=>'check-latest-version', 'api-key'=>md5(get_bloginfo('url')));
        $response = $this->update_api_request($args);
        
        if($response === false) return $transient;
        if(version_compare($response->new_version, $transient->checked[$slug], '>')) $transient->response[$slug] = (array) $response;
        
        return $transient;
    }
    
    public function update_api_request($args)
    {
        // Send request
        $request = wp_remote_post('http://billing.realtyna.com/io/io.php', array('body'=>$args));
        if(is_wp_error($request) or 200 != wp_remote_retrieve_response_code($request)) return false;

        $response = unserialize(wp_remote_retrieve_body($request));
        
        if(is_object($response)) return $response;
        else return false;
    }

	public function set_theme_menu()
	{
		add_menu_page((isset($theme_name) ? $theme_name : '' ).'WPL Theme Configuration', __('WPL28 Settings', 'wplt'), 'manage_options', 'wpl_theme_config', array($this, 'get_config_page'), get_template_directory_uri() . '/assets/images/wpl-28-svg/admin-logo.svg', 59);
	}

	public function get_config_page()
	{
		if(!current_user_can('manage_options'))
		{
			wp_die(__('You do not have sufficient permissions to access this page.', 'wplt'));
		}
		
		include WPLT_ABSPATH. 'wpl' .DS. 'config_page.php';
	}

	public function register_theme_configs()
	{
		// Register the settings with Validation callback
		register_setting('wpl_theme_options', 'wpl_theme_options', 'wpl_validate_settings');
	}

	public function get_head($name = NULL)
	{
		get_header($name);
	}

	public function get_footer($name = NULL)
	{
		get_footer($name);
	}

	public function wpl_title($title, $sep)
	{
		global $paged, $page;
		if(is_feed()) return $title;

		// Add the site name.
		$title .= get_bloginfo('name');

		// Add the site description for the home/front page.
		$site_description = get_bloginfo('description', 'display');
		if($site_description && (is_home() or is_front_page())) $title = "$title $sep $site_description";

		// Add a page number if necessary.
		if($paged >= 2 or $page >= 2)
			$title = "$title $sep " . sprintf(__('Page %s', 'wplt'), max($paged, $page));

		return $title;
	}

	public function get_sidebar($sidebar_name = 'sidebar-1')
	{
		include WPLT_ABSPATH. 'wpl' .DS. 'sidebar.php';
	}

	public function register_sidebars()
	{
		global $theme_options;
		register_sidebar(array(
			'name'          => __('Main Widget Area', 'wplt'),
			'id'            => 'sidebar-1',
			'description'   => __('Appears in the footer section of the site.', 'wplt'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));

		register_sidebar(array(
			'name'          => __('Secondary Widget Area', 'wplt'),
			'id'            => 'sidebar-2',
			'description'   => __('Appears on posts and pages in the sidebar.', 'wplt'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));

		register_sidebar(array(
			'name'          => __('Carousel', 'wplt'),
			'id'            => 'sidebar-3',
			'description'   => __('Appears on the bottom of Main Menu.', 'wplt'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));

        register_sidebar(array(
            'name'          => __('Top Footer Rows', 'wplt'),
            'id'            => 'sidebar-5',
            'description'   => __('Appears on top of the Top footer sidebar.', 'wplt'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>',
        ));

		register_sidebar(array(
			'name'          => __('Top Footer', 'wplt'),
			'id'            => 'sidebar-4',
			'description'   => __('Appears on the top of Footer.', 'wplt'),
			'before_widget' => '<div class="footer-column col-sm-12 col-md-6 col-lg-'.(12/(isset($theme_options['footer_columns']) ? $theme_options['footer_columns'] : 3)).'">
									<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '	</div>
								</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
		register_sidebar(array(
			'name'          => __('Left Slide', 'wplt'),
			'id'            => 'sidebar-6',
			'description'   => __('Appears on the left of page and slides to right by clicking.', 'wplt'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">
			                        <div id="left_slide_handle" class="left_slide_handle closed"></div>',
			'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
		register_sidebar(array(
			'name'          => __('Right Side', 'wplt'),
			'id'            => 'sidebar-7',
			'description'   => __('Appears on the right of page and slides to left by clicking.', 'wplt'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
		register_sidebar(array(
			'name'          => __('Unit and Language Switcher', 'wplt'),
			'id'            => 'sidebar-8',
			'description'   => __('Appears on header.', 'wplt'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
	}

	public function register_menus()
	{
		register_nav_menus(array(
			'top-menu'    => __('Top Menu', 'wplt'),
			'footer-menu' => __('Footer Menu', 'wplt'),
			'social-menu' => __('Social Menu', 'wplt'),
			'feature-menu' => __('Feature Menu', 'wplt'),
			'404-menu'    => __('Not Found Page Menu', 'wplt')
		));
	}

	/** Register Theme Features **/
	public function theme_features()
	{
		global $wp_version;

		// Add theme support for Automatic Feed Links
        add_theme_support('automatic-feed-links');

		// Add theme support for Post Formats
		$formats = array('status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside', 'chat');
		add_theme_support('post-formats', $formats);

		// Add theme support for Featured Images
		add_theme_support('post-thumbnails');

		// Add theme support for Semantic Markup
		$markup = array('search-form', 'comment-form', 'comment-list');
		add_theme_support('html5', $markup);

		// Add theme support for Translation
		load_theme_textdomain('wplt', WPLT_ABSPATH. 'languages');
	}
	
	// Add template js and style files
	public function wpl_theme_header()
	{
		// Loads template main JavaScripts.

		wp_enqueue_script('comment-reply');

		//wp_enqueue_script('wpl-theme-script-merge12js', get_template_directory_uri().'/assets/js/wpl28-merge12-js.min.js', array(), '');


		wp_enqueue_script('wpl-theme-script-imagesloaded', get_template_directory_uri().'/assets/js/libraries/imagesloaded.pkgd.min.js', array(), '');
		wp_enqueue_script('wpl-theme-script-jquery-easign', get_template_directory_uri().'/assets/js/libraries/jquery.easing.min.js', array(), '');
		wp_enqueue_script('wpl-theme-script-scrollstop', get_template_directory_uri().'/assets/js/libraries/jquery.scrollstop.js', array(), '');
		wp_enqueue_script('wpl-theme-script-scrollsnap', get_template_directory_uri().'/assets/js/libraries/jquery.scrollsnap.js', array(), '');
		wp_enqueue_script('wpl-theme-script-modernizr', get_template_directory_uri().'/assets/js/modernizr-2.6.2.js', array(), '');
		wp_enqueue_script('wpl-theme-script-bootstrap', get_template_directory_uri().'/assets/js/bootstrap.min.js', array('jquery'), '');
		wp_enqueue_script('wpl-theme-script-bxslider', get_template_directory_uri().'/assets/js/jquery.bxslider.min.js', array('jquery'), '');
		//wp_enqueue_script('wpl-theme-script-chosen', get_template_directory_uri().'/assets/js/chosen.jquery.min.js', array('jquery'), '');
		wp_enqueue_script('wpl-theme-script-easing', get_template_directory_uri().'/assets/js/jquery.easing.1.3.js', array('jquery'), '');
		wp_enqueue_script('wpl-theme-script-equalHeight', get_template_directory_uri().'/assets/js/jQuery.equalHeights.js', array('jquery'), '');
		wp_enqueue_script('wpl-theme-script-uitotop', get_template_directory_uri().'/assets/js/jquery.ui.totop.min.js', array('jquery'), '');
        wp_enqueue_script('wpl-theme-script-grid-licious', get_template_directory_uri().'/assets/js/jquery.grid-a-licious.min.js', array('jquery'), '');
		wp_enqueue_script('wpl-theme-frontend-js', get_template_directory_uri().'/assets/js/template.min.js', array('jquery'), '');


		// Loads template main stylesheet.
        if(!is_admin())
        {
            // Load required google font
            wp_enqueue_style('wpl_theme_google_fonts', '//fonts.googleapis.com/css?family=Lato:400,700,900,400italic|Droid+Serif:400,400italic,700italic|BenchNine');

			if(!isset($theme_options['theme']))
        	{
        		$theme_options['theme'] = 1;
        	}

        	/*if((wpl_theme::getVar('theme') and wpl_theme::getVar('theme') === 'dark') or  $this->theme_options['theme'] === '2')
        	{
        		wp_enqueue_style('wpl-theme-style', get_template_directory_uri().'/assets/css/template-dark.css', array(), '');
        	}
        	else
        	{
        		wp_enqueue_style('wpl-theme-style', get_template_directory_uri().'/assets/css/template.css', array(), '');
        	}*/

			$template_setting ='';
			$ignored_option = array('tel', 'email', 'footer_menu', 'footer_html', 'tracking_code', 'head_code', 'body_code', 'css_code', 'logo', 'retina_logo','facebook','twitter','gplus','flickr', 'rss', 'vimeo', 'youtube', 'pinterest', 'tumblr', 'dribbble', 'digg', 'linkedin', 'blogger', 'skype', 'forrst', 'myspace', 'deviantart', 'yahoo', 'reddit', 'fs_animation', 'fs_slideshow', 'fs_controlnav', 'fs_directionnav', 'fs_randomize', 'fs_slideshowspeed', 'fs_animationduration', 'flex_slider', 'show_navigation', 'show_author', 'show_date', 'sticky_header', 'enable_footer_logo', 'mailto', 'default_listing_layout', 'layout_m_carousel_text', 'layout_listing_list_boxes', 'layout_listing_hover', 'footer_columns');
			
            foreach($this->theme_options as $key => $value)
            {
                if(in_array($key, $ignored_option) or trim($value) == '')
                {
                	continue;
                }
                
                $template_setting .= $key.'='.trim(sanitize_text_field($value), '#').'&';
            }
			// Load our main stylesheet.
			wp_enqueue_style( 'wpl-theme-main-style', get_stylesheet_uri(), array(), '' );
            
            $font_array = array($this->theme_options['main_font'], $this->theme_options['header_font'], $this->theme_options['footer_font'], 'Montserrat:400,700');
            $google_fonts = '';
            foreach($font_array as $fonts){
                $google_fonts .= str_replace(' ','+',$fonts).'|';
            }

            wp_enqueue_style('wpl-theme-fonts-dynamic', '//fonts.googleapis.com/css?family='.trim($google_fonts,'|'), array(), '');
            wp_enqueue_style('wpl-theme-style-dynamic', get_template_directory_uri().'/assets/css/template.php?'.trim($template_setting,'&'), array(), '');


        }

        // Add support for featured content.
        add_theme_support( 'featured-content', array(
			'featured_content_filter' => 'wpl_get_featured_posts',
			'max_posts'               => 6,
        ) );
	}

    public function get_template_default_value()
    {
        $default_options = array (
            "layout"            => "1",
            "theme"             => "1",
            "body"              => "#ffffff",
            "main_color"        => "#29a9df",
            "footer_bg"         => "#191818",
            "header"            => "4",
            "footer"            => "1",
            "footer_html"       => "Copyright by Realtyna Inc. All rights reserved.",
			"footer_columns"    => "3",
            "footer_menu"       => "1",
            "main_font"         => "Open Sans",
            "main_font_size"    => "13",
            "header_font"       => "PT Sans Narrow",
            "header_font_size"  => "16",
            "footer_font"       => "Open Sans",
            "footer_font_size"  => "13"
        );
		
        return $default_options;
    }

    /** write after activate codes in this function **/
    public function theme_activated()
    {
        if(isset($_GET['activated']) and $_GET['activated'] == 'true')
        {
            add_option( 'wpl_theme_options', self::get_template_default_value() , '', 'yes' );
            return;
        }
    }

    public function wpl_get_featured_posts()
    {
        return add_filter('wpl_get_featured_posts', array($this, 'wpl_get_featured_posts'));
    }

    public function wpl_has_featured_posts()
    {
        return !is_paged() && (bool) self::wpl_get_featured_posts();
    }

    public function admin_script_initialize()
    {
        if(wpl_theme::getVar('page') == 'wpl_theme_config')
        {
	        wp_enqueue_style('wp-color-picker');
	        wp_enqueue_script('wp-color-picker');
			
	        if(function_exists('wp_enqueue_media'))
	        {
	            wp_enqueue_media();
	        }
	        else
	        {
	            wp_enqueue_style('thickbox');
	            wp_enqueue_script('media-upload');
	            wp_enqueue_script('thickbox');
	        }
			wp_enqueue_style('wpl-theme-style', get_template_directory_uri().'/style.css', array(), '');
	        wp_enqueue_script('wpl-theme-script-bootstrap', get_template_directory_uri().'/assets/js/bootstrap.min.js', array('jquery'), '');
	        wp_enqueue_script('wpl-theme-script-chosen', get_template_directory_uri().'/assets/js/chosen.jquery.min.js', array('jquery'), '');
	        wp_enqueue_script('wpl-theme-backend-js', get_template_directory_uri().'/assets/js/wp_backend.js', array(), '');
        }
    }
}