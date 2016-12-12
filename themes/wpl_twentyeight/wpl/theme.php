<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

/**
** theme Library
** Developed 09/30/2013
**/
class wpl_theme
{
	private $template_ver = '3.2.0';

	public function get_version(){
		return $this->template_ver;
	}

	public function get_head()
	{
		get_header();
	}
	
	public function get_footer()
	{
		get_footer();
	}
	
	public function wpl_head()
	{
		wp_head();
	}
	
	public function wpl_title($title, $sep)
	{
		wp_title();
	}

    public function get_search_form()
    {
        get_search_form();
    }

	public function get_sidebar($sidebar_name = 'sidebar-1')
	{
		get_sidebar();
	}
	
	public function get_menu($args = array())
	{
		wp_nav_menu($args);
	}

	public static function get_wpl_theme_options()
	{
		$options = get_option('wpl_theme_options', array());
        
        $escaped = array();
        foreach($options as $key=>$value) $escaped[$key] = stripslashes($value);
        
        return $escaped;
	}
	
    public function is_user_logged_in()
    {
        return is_user_logged_in();
    }
    
    public function wp_logout_url($redirect = NULL)
    {
        return wp_logout_url($redirect);
    }
    
	/** 
		wpl import function it used for importing overrided files automatically 
	**/
	public function _wpl_import($include, $override = true, $return_path = false)
	{
		$original_exploded = explode('.', $include);
		$path              = WPL_ABSPATH . implode(DS, $original_exploded) . '.php';
		
		if($override)
		{
			$overrided_exploded = explode('.', 'overrides.'.$include);
			$overrided_path     = WPL_ABSPATH . implode(DS, $overrided_exploded) . '.php';
			if(file_exists($overrided_path))
			{
				$path = $overrided_path;
			}
			
			/** theme overrides just for tmpl files **/
			if(strpos($include, '.tmpl.') !== false)
			{
				/** main theme **/
				$wp_theme_path           = get_template_directory();
				$overrided_file_in_theme = str_replace('views.', 'wplhtml.', $include);
				$overrided_file_in_theme = str_replace('tmpl.', '', $overrided_file_in_theme);
				if(substr($overrided_file_in_theme, 0, 8) == 'widgets.')
				{
					$overrided_file_in_theme = 'wplhtml.'.$overrided_file_in_theme;
				}
				
				$theme_exploded = explode('.', $overrided_file_in_theme);
				$theme_path     = $wp_theme_path .DS. implode(DS, $theme_exploded) . '.php';
				
				if(file_exists($theme_path))
				{
					$path = $theme_path;	
				}
				
				/** child theme **/
				$wp_stylesheet = get_option('stylesheet');
				if(strpos($wp_stylesheet, '-child') !== false)
				{
					$wp_theme_name    = get_option('template');
					$child_theme_path = $wp_theme_path. '-child' .DS. implode(DS, $theme_exploded) . '.php';
					$child_theme_path = str_replace($wp_theme_name. '-child', $wp_stylesheet, $child_theme_path);
					
					if(file_exists($child_theme_path))
					{
						$path = $child_theme_path;	
					}
				}
			}
		}
		
		if($return_path)
		{
			return $path;
		}
		
		if(file_exists($path))
		{
			require_once $path;	
		}
	}

	/**
	* get a variable
	*/
	public static function getVar($name, $default = null, $hash = 'default')
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper($hash);
		
		if ($hash === 'METHOD')
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		// Get the input hash
		switch ($hash)
		{
			case 'GET':
				$input = &$_GET;
				break;

			case 'POST':
				$input = &$_POST;
				break;

			case 'FILES':
				$input = &$_FILES;
				break;

			case 'COOKIE':
				$input = &$_COOKIE;
				break;

			case 'ENV':
				$input = &$_ENV;
				break;

			case 'SERVER':
				$input = &$_SERVER;
				break;

			default:
				$input = &$_REQUEST;
				$hash  = 'REQUEST';
				break;
		}

		$var = isset($input[$name]) ? $input[$name] : $default;
		return $var;
	}

	/**
	* get a variable array
	*/
	public static function get($hash = 'default')
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper($hash);

		if ($hash === 'METHOD')
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		switch ($hash)
		{
			case 'GET':
				$input = $_GET;
				break;

			case 'POST':
				$input = $_POST;
				break;

			case 'FILES':
				$input = $_FILES;
				break;

			case 'COOKIE':
				$input = $_COOKIE;
				break;

			case 'ENV':
				$input = &$_ENV;
				break;

			case 'SERVER':
				$input = &$_SERVER;
				break;

			default:
				$input = $_REQUEST;
				break;
		}

		return $input;
	}

	/**
	developed by howard 10/4/2012
	**/
	public function get_full_url($apply_port = false)
	{
		/** get $_SERVER **/
		$server = self::get('SERVER');
		
		$page_url = 'http';
		if(isset($server['HTTPS']) and $server['HTTPS'] == 'on')
		{
			$page_url .= 's';
		}
		
        $site_domain = (isset($server['HTTP_HOST']) and trim($server['HTTP_HOST']) != '') ? $server['HTTP_HOST'] : $server['SERVER_NAME'];
        
		$page_url .= '://';
		if($server['SERVER_PORT'] != '80' and $apply_port)
		{
			$page_url .= $site_domain.':'.$server['SERVER_PORT'].$server['REQUEST_URI'];
		}
		else
		{
			$page_url .= $site_domain.$server['REQUEST_URI'];
		}
		
		return $page_url;
	}
	
	/**
		developed by howard 03/10/2013
	**/
	public function get_asset_url($asset)
	{
        return get_template_directory_uri().'/assets/'.$asset;
	}
	

	public function save_theme_options()
	{
		$wpl_theme_options = self::getVar('wpl_theme_options', array());
		$wpl_theme_options['version'] = $this->template_ver;
        
		delete_option('wpl_theme_options');
		add_option('wpl_theme_options', $wpl_theme_options);
		
		$res = 1;
		$message = $res ? __('Saved.', 'wplt') : __('Error Occured.', 'wplt');
		$data = NULL;

		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		echo json_encode($response);
		exit;
	}

	/**
	** FINAL FUNCTIONS
	** These are WP functions
	**/
	final public function is_active_sidebar($sidebar_name)
	{
		return is_active_sidebar($sidebar_name);
	}
	
	final public function load_sidebar($sidebar_name)
	{
		dynamic_sidebar($sidebar_name);
	}
	
	/** use this function for including theme parts (child theme compatible) **/
	final public function get_template_part($slug, $name)
	{
		get_template_part($slug, $name);
	}
	
	final public function have_posts()
	{
		return have_posts();
	}
	
	final public function the_post()
	{
		the_post();
	}
	
	final public function the_title()
	{
		the_title();
	}
	
	final public function the_content()
	{
		the_content();
	}
	
	final public function the_permalink()
	{
		the_permalink();
	}
	
	final public function the_time($d)
	{
		the_time($d);
	}
	
	final public function the_author_posts_link()
	{
		the_author_posts_link();
	}
	
	final public function the_category($separator = '', $parents = '', $post_id = false)
	{
		the_category($separator, $parents, $post_id);
	}
	
	final public function the_title_attribute($args = array())
	{
		the_title_attribute($args);
	}
	
	final public function the_excerpt()
	{
		the_excerpt();
	}
	
	final public function body_class($class = NULL)
	{
		body_class($class);
	}
	
	final public function the_ID()
	{
		the_ID();
	}
	
	/** getting the post id **/
	final public function get_the_ID()
	{
		return get_the_ID();
	}
	
	final public function wp_footer()
	{
		wp_footer();
	}
	
	final public function language_attributes($doctype = 'html')
	{
		language_attributes($doctype);
	}
	
	final public function home_url($path = '', $scheme = NULL)
	{
		return home_url($path, $scheme);
	}
	
	final public function comments_template($file = '/comments.php ', $separate_comments = false)
	{
		comments_template($file, $separate_comments);
	}
	
	final public function wp_login_url($redirect = NULL)
	{
		return wp_login_url($redirect);
	}
	
	final public function wp_registration_url()
	{
		return wp_registration_url();
	}
	
	final public function is_front_page()
	{
		return is_front_page();
	}
	
	final public function get_theme_url()
	{
		return get_template_directory_uri();
	}

    /** Template functions */
    public function wpl_post_thumbnail()
	{
        if(post_password_required() || !has_post_thumbnail())
        {
            return;
        }

        if(is_singular()):
		?>
        <div class="post-thumbnail">
            <?php
            if((!is_active_sidebar('sidebar-2') || is_page_template('page-templates/full-width.php')))
            {
                the_post_thumbnail('wpl-full-width');
            }
            else
            {
                the_post_thumbnail();
            }
            ?>
        </div>
        <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>">
            <?php
            if((!is_active_sidebar('sidebar-2') || is_page_template('page-templates/full-width.php')))
            {
                the_post_thumbnail('wpl-full-width');
            }
            else
            {
                the_post_thumbnail();
            }
            ?>
        </a>
        <?php endif; // End is_singular()
    }

    /**
    * @return boolean true if blog has more than 1 category
    */
    public function wpl_categorized_blog()
	{
        if(false === ($all_the_cool_cats = get_transient('wpl_category_count')))
        {
            // Create an array of all the categories that are attached to posts
            $all_the_cool_cats = get_categories(array(
                'hide_empty' => 1,
            ));

            // Count the number of categories that are attached to the posts
            $all_the_cool_cats = count( $all_the_cool_cats );
            set_transient('wpl_category_count', $all_the_cool_cats);
        }

        if(1 !== (int) $all_the_cool_cats)
        {
            // This blog has more than 1 category so wpl_categorized_blog should return true
            return true;
        }
        else
        {
            // This blog has only 1 category so wpl_categorized_blog should return false
            return false;
        }
    }

    public function wpl_posted_on()
	{
        // Set up and print post meta information.
        printf('<span class="entry-date"><a href="%1$s" rel="bookmark"><time datetime="%2$s">%3$s</time></a></span>',
            esc_url(get_permalink()),
            esc_attr(get_the_date('c')),
            esc_html(get_the_date())
        );
    }

    public function wpl_posted_by()
	{
        if(is_sticky() && is_home() && !is_paged())
        {
            echo '<span class="featured-post">' . __( 'Sticky', 'wplt' ) . '</span>';
        }
		
        // Set up and print post meta information.
        printf('<span class="byline"><span class="author vcard">%1$s<a class="url fn n" href="%2$s" rel="author">%3$s</a></span></span>',
            __('By : ','wplt'),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            get_the_author()
        );
    }

    public function wpl_paging_nav()
	{
        // Don't print empty markup if there's only one page.
        if($GLOBALS['wp_query']->max_num_pages < 2)
        {
            return;
        }

        $paged        = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args   = array();
        $url_parts    = explode('?', $pagenum_link);

        if(isset($url_parts[1]))
        {
            wp_parse_str( $url_parts[1], $query_args );
        }

		$pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link).'%_%';
		
		$format       = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format      .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links(array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $GLOBALS['wp_query']->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => __( '&larr; Previous', 'wplt' ),
			'next_text' => __( 'Next &rarr;', 'wplt' ),
        ));

        if($links)
        {
            ?>
            <nav class="navigation paging-navigation" role="navigation">
                <div class="pagination loop-pagination">
                    <?php echo $links; ?>
                </div><!-- .pagination -->
            </nav><!-- .navigation -->
        <?php
        }
    }

    public function wpl_get_featured_posts()
    {
        /**
         * @param array|bool $posts Array of featured posts, otherwise false.
         */
        return apply_filters( 'wpl_get_featured_posts', array() );
    }

    /**
     * A helper conditional function that returns a boolean value.
     * @return bool Whether there are featured posts.
     */
    public function wpl_has_featured_posts()
    {
        return ! is_paged() && (bool) self::wpl_get_featured_posts();
    }

    public function wpl_the_attached_image()
    {
        $post                = get_post();
        /**
         *
         * @param array $dimensions {
         *     An array of height and width dimensions.
         *
         *     @type int $height Height of the image in pixels. Default 810.
         *     @type int $width  Width of the image in pixels. Default 810.
         * }
         */
        $attachment_size     = apply_filters( 'wpl_attachment_size', array( 810, 810 ) );
        $next_attachment_url = wp_get_attachment_url();

        /*
         * Grab the IDs of all the image attachments in a gallery so we can get the URL
         * of the next adjacent image in a gallery, or the first image (if we're
         * looking at the last image in a gallery), or, in a gallery of one, just the
         * link to that image file.
         */
        $attachment_ids = get_posts( array(
            'post_parent'    => $post->post_parent,
            'fields'         => 'ids',
            'numberposts'    => -1,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => 'ASC',
            'orderby'        => 'menu_order ID',
        ) );

        // If there is more than 1 attachment in a gallery...
        if ( count( $attachment_ids ) > 1 )
        {
            foreach ( $attachment_ids as $attachment_id )
            {
                if ( $attachment_id == $post->ID )
                {
                    $next_id = current( $attachment_ids );
                    break;
                }
            }

            // get the URL of the next image attachment...
            if ( $next_id )
            {
                $next_attachment_url = get_attachment_link( $next_id );
            }

            // or get the URL of the first image attachment.
            else
            {
                $next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
            }
        }

        printf( '<a href="%1$s" rel="attachment">%2$s</a>',
            esc_url( $next_attachment_url ),
            wp_get_attachment_image( $post->ID, $attachment_size )
        );
    }

    /**
	* Check variable if isset 
    **/
    public function isset_variable($key, $params)
    {
    	if(isset($params[$key]))
    	{
    		return $params[$key];
    	}
    }

    /**
     * Display navigation to next/previous post when applicable.
     * @return void
     */
    public function wpl_post_nav()
    {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
        $next     = get_adjacent_post( false, '', false );

        if ( ! $next && ! $previous )
        {
            return;
        }

        ?>
        <nav class="navigation post-navigation clearfix" role="navigation">
            <div class="nav-links">
                <?php
                if ( is_attachment() ) 
                {
                    previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'wplt' ) );
                }
                else
                {
                    previous_post_link( '%link', __( '%title', 'wplt' ) );
                    next_post_link( '%link', __( '%title', 'wplt' ) );
                }
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
    <?php
    }

    /**
    * Display Google font.
    */
    public function wpl_google_font_select( $select_id , $selected_val )
    {
        WP_Filesystem();
        global $wp_filesystem;

        $fonts = $wp_filesystem->get_contents(WPLT_ABSPATH.'wpl'.DS.'fonts.txt');
        if(!$fonts) $fonts = file_get_contents(WPLT_ABSPATH.'wpl'.DS.'fonts.txt');
        
        $fonts = json_decode($fonts,true);

        $items = $fonts['items'];
        $i = 0;
        echo '<select id="wpl_theme_options_'.$select_id.'" name="wpl_theme_options['.$select_id.']">';
        foreach ($items as $item)
        {
            $i++;
            echo '<option value="'.$item['family'].'" '.($selected_val == $item['family'] ? 'selected="selected"' : '').'>'.$item['family'].'</option>';
        }
        echo '</select>';
    }

    /**
     * Display font size.
     */
    public function wpl_font_size( $select_id , $selected_val, $val_type='px',$max_val=100 ,$weight=1)
    {
        echo '<select id="wpl_theme_options_'.$select_id.'" name="wpl_theme_options['.$select_id.']">';
        for($i = 1; $i <= $max_val ; $i=$i+$weight)
        {
            echo '<option value="' . $i . '" ' . ($selected_val == (string) $i ? 'selected="selected"' : '') . '>' . $i . $val_type . '</option>';
        }
        echo '</select>';
    }

    /**
    * Display Social icons.
    */
    public function wpl_social_icon($id)
    {
        echo '
			<ul id="'.$id.'">';
        if(isset($this->theme_options['twitter']) and trim($this->theme_options['twitter'])!==''):
            echo '<li><a href="'.$this->theme_options['twitter'].'" class="twitter" target="_blank">'.__('Twitter', 'wplt').'</a></li>';
        endif;
		if(isset($this->theme_options['instagram']) and trim($this->theme_options['instagram'])!==''):
			echo '<li><a href="'.$this->theme_options['instagram'].'" class="instagram" target="_blank">'.__('instagram', 'wplt').'</a></li>';
		endif;
        if(isset($this->theme_options['facebook']) and trim($this->theme_options['facebook'])!==''):
            echo '<li><a href="'.$this->theme_options['facebook'].'" class="facebook" target="_blank">'.__('Facebook', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['gplus']) and trim($this->theme_options['gplus'])!==''):
            echo '<li><a href="'.$this->theme_options['gplus'].'" class="g_plus" target="_blank">'.__('Google Plus', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['flickr']) and trim($this->theme_options['flickr'])!==''):
            echo '<li><a href="'.$this->theme_options['flickr'].'" class="flickr" target="_blank">'.__('Flickr', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['rss']) and trim($this->theme_options['rss'])!==''):
            echo '<li><a href="'.$this->theme_options['rss'].'" class="rss" target="_blank">'.__('RSS', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['vimeo']) and trim($this->theme_options['vimeo'])!==''):
            echo '<li><a href="'.$this->theme_options['vimeo'].'" class="vimeo" target="_blank">'.__('Vimeo', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['youtube']) and trim($this->theme_options['youtube'])!==''):
            echo '<li><a href="'.$this->theme_options['youtube'].'" class="youtube" target="_blank">'.__('YouTube', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['pinterest']) and trim($this->theme_options['pinterest'])!==''):
            echo '<li><a href="'.$this->theme_options['pinterest'].'" class="pinterest" target="_blank">'.__('Pinterest', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['tumblr']) and trim($this->theme_options['tumblr'])!==''):
            echo '<li><a href="'.$this->theme_options['tumblr'].'" class="tumblr" target="_blank">'.__('Tumblr', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['dribbble']) and trim($this->theme_options['dribbble'])!==''):
            echo '<li><a href="'.$this->theme_options['dribbble'].'" class="dribbble" target="_blank">'.__('Dribbble', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['digg']) and trim($this->theme_options['digg'])!==''):
            echo '<li><a href="'.$this->theme_options['digg'].'" class="digg" target="_blank">'.__('Digg', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['linkedin']) and trim($this->theme_options['linkedin'])!==''):
            echo '<li><a href="'.$this->theme_options['linkedin'].'" class="linkedin" target="_blank">'.__('Linkedin', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['blogger']) and trim($this->theme_options['blogger'])!==''):
            echo '<li><a href="'.$this->theme_options['blogger'].'" class="blogger" target="_blank">'.__('Blogger', 'wplt').'</a></li>';
        endif;
      /*  if(isset($this->theme_options['skype']) and trim($this->theme_options['skype'])!==''):
            echo '<li><a href="'.$this->theme_options['skype'].'" class="skype" target="_blank">'.__('Skype', 'wplt').'</a></li>';
        endif;*/
        if(isset($this->theme_options['forrst']) and trim($this->theme_options['forrst'])!==''):
            echo '<li><a href="'.$this->theme_options['forrst'].'" class="forrst" target="_blank">'.__('Forrst', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['myspace']) and trim($this->theme_options['myspace'])!==''):
            echo '<li><a href="'.$this->theme_options['myspace'].'" class="myspace" target="_blank">'.__('Myspace', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['deviantart']) and trim($this->theme_options['deviantart'])!==''):
            echo '<li><a href="'.$this->theme_options['deviantart'].'" class="deviantart" target="_blank">'.__('Deviantart', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['yahoo']) and trim($this->theme_options['yahoo'])!==''):
            echo '<li><a href="'.$this->theme_options['yahoo'].'" class="yahoo" target="_blank">'.__('Yahoo', 'wplt').'</a></li>';
        endif;
        if(isset($this->theme_options['reddit']) and trim($this->theme_options['reddit'])!==''):
            echo '<li><a href="'.$this->theme_options['reddit'].'" class="reddit" target="_blank">'.__('Reddit', 'wplt').'</a></li>';
        endif;
		if(isset($this->theme_options['telegram']) and trim($this->theme_options['telegram'])!==''):
            echo '<li><a href="'.$this->theme_options['telegram'].'" class="telegram" target="_blank">'.__('Telegram', 'wplt').'</a></li>';
        endif;
        echo '</ul>';
    }

	/**
    * Top Logo
    */
    public function wpl_top_logo()
    {
        echo '<a itemprop="url" href="'.$this->home_url().'" id="top_logo"';
        if(!isset($this->theme_options['logo']) or (isset($this->theme_options['logo']) and $this->theme_options['logo'] ==''))
        {
            echo ' class="top_logo"';
        }
        echo '>';
        echo ((isset($this->theme_options['logo']) and trim($this->theme_options['logo']) !=='') ? '<img itemprop="logo" src="'.$this->theme_options['logo'].'" id="normal_top_logo" alt="'.__(get_bloginfo( 'name' ), 'wplt').'" />' : '');
        echo ((isset($this->theme_options['retina_logo']) and trim($this->theme_options['retina_logo']) !=='') ? '<img itemprop="logo" src="'.$this->theme_options['retina_logo'].'" id="retina_top_logo" alt="'.__(get_bloginfo( 'name' ), 'wplt').'" />' : ((isset($this->theme_options['logo']) and trim($this->theme_options['logo']) !=='') ? '<img itemprop="logo" src="'.$this->theme_options['logo'].'" id="retina_top_logo" alt="'.__(get_bloginfo( 'name' ), 'wplt').'" />' : ''));
        echo '</a>';
    }
    
    /**
    * Top Second Logo 
    */
    public function wpl_top_second_logo($bool_active=false,$second_logo_link='')
    {
    	if ((isset($this->theme_options['logo2']) and trim($this->theme_options['logo2']) !==''))
    	{
    		if($bool_active)
    			return true;
    		else
    		{
	    		if ((isset($this->theme_options['logo2_link']) and trim($this->theme_options['logo2_link']) !==''))
	    			$second_logo_link=$this->theme_options['logo2_link'];
	    		else
	    			$second_logo_link=$this->home_url();
    			echo '<a itemprop="url" href="'.$second_logo_link.'" id="top_second_logo" target="_blank"><img itemprop="logo" src="'.$this->theme_options['logo2'].'" id="normal_top_logo" alt="'.__(get_bloginfo( 'name' ), 'wplt').'" /></a>';
    		}
    	}
    	else
    		return false;
    }

    /**
     * Footer Logo
     */
    public function wpl_footer_logo()
    {
        if(isset($this->theme_options['enable_footer_logo']) and $this->theme_options['enable_footer_logo'] and isset($this->theme_options['footer_logo']) and trim($this->theme_options['footer_logo']) !=='')
        {
            echo '<a itemprop="url" href="'.$this->home_url().'" title="'.__(get_bloginfo( 'name' ), 'wplt').'" id="footer_logo" title="'.__(get_bloginfo( 'name' ), 'wplt').'" >';
            echo '<img itemprop="logo" src="'.$this->theme_options['footer_logo'].'" id="normal_footer_logo" alt="'.__(get_bloginfo( 'name' ), 'wplt').'" />';
            echo '</a>';
        }
    }

    /**
	*  Flex Slider
    */
    public function felx_slider()
    {
		/** Some set-up **/
		$wpl_theme_options_fs = self::get_wpl_theme_options();
		if(trim($wpl_theme_options_fs['flex_slider']) == '') return;
		
		/** Add the javascript files! **/
		wp_enqueue_script('flex_slider_js', get_template_directory_uri() . '/sliders/flexSlider/jquery.flexslider-min.js', array('jquery'));

		/** Add the hooks to place the javascript in the header **/
		echo '<script type="text/javascript" charset="utf-8">
		  jQuery(window).load(function() {
		    jQuery(\'.flexslider\').flexslider({
		    	animation:"'.$wpl_theme_options_fs['fs_animation'].'",
		    	slideshow:'.($wpl_theme_options_fs['fs_slideshow'] ? 'true' : 'false').',
		    	slideshowSpeed:'.($wpl_theme_options_fs['fs_slideshowspeed'] ? $wpl_theme_options_fs['fs_slideshowspeed'] : 7000).',
		    	animationDuration:'.($wpl_theme_options_fs['fs_animationduration'] ? $wpl_theme_options_fs['fs_animationduration'] : 600).',
		    	directionNav:'.($wpl_theme_options_fs['fs_directionnav'] ? 'true' : 'false').',
		    	controlNav:'.($wpl_theme_options_fs['fs_controlnav'] ? 'true' : 'false').',
		    	randomize:'.($wpl_theme_options_fs['fs_randomize'] ? 'true' : 'false').'
		    });
		  });
		</script>';

		$slider = '<div class="flexslider'.($wpl_theme_options_fs['layout']=='2' ? ' boxed container' : '' ).'"><ul class="slides">';

		$flex_slider_images = explode(';', trim($wpl_theme_options_fs['flex_slider'], '; '));
		foreach ($flex_slider_images as $flex_slider_img)
		{
			if(trim($flex_slider_img) == '') continue;
			$slider .= '<li><img src="'. $flex_slider_img .'" /></li>';
		}

		$slider .= '</ul>
		</div>';

		echo $slider;
    }

    function is_subpage()
    {
        global $post;                              // load details about this page

        if ( is_page() && $post->post_parent )
        {   // test to see if the page has a parent
            return $post->post_parent;             // return the ID of the parent post

        }
        else
        {                                   // there is no parent so ...
            return false;                          // ... the answer to the question is false
        }
    }

    // Remove version query from files

    function wpl_remove_script_version( $src )
    {
        return remove_query_arg( 'ver',  $src  );
    }

    // Breadcrumbs

    function wpl_breadcrumbs()
    {

		$delimiter   = '&raquo;';
		$home        = __('Home','wplt');
		$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
		$before      = '<span class="current">'; // tag before the current crumb
		$after       = '</span>'; // tag after the current crumb

        global $post, $data, $wp_query;
        $homeLink = home_url();

        if ( is_front_page() )
        {

            echo '<ul class="breadcrumbs fixclear"><li><a rel="v:url" property="v:title" href="' . $homeLink . '">' . $home . '</a></li></ul>';

        }
        elseif ( is_home() )
        {

            if ( function_exists ('icl_t') )
            {
                $title = icl_t('wplt', 'Archive Page Title', do_shortcode(stripslashes($data['archive_page_title'])));
            }
            else
            {
                $title = do_shortcode(stripslashes($data['archive_page_title']));
            }
            echo '<ul class="breadcrumbs fixclear"><li><a rel="v:url" property="v:title" href="' . $homeLink . '">' . $home . '</a></li><li>'.$title.'</li></ul>';
        }
        else
        {

            echo '<ul class="breadcrumbs fixclear"><li><a rel="v:url" property="v:title" href="' . $homeLink . '">' . $home . '</a></li>';

            if ( is_category() )
            {

                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) $cats = get_category_parents($thisCat->parent, TRUE, '|');

                $cats = get_category_parents($thisCat, TRUE, '|');

                $cats = explode ( '|',$cats );

                foreach ( $cats as $s_cat )
                {
                    if ( !empty ( $s_cat ) )
                    {
                        $s_cat = str_replace ( '<a', '<a rel="v:url" property="v:title" ' , $s_cat );
                        echo '<li>'.$s_cat.'</li>';
                    }
                }

                echo '<li>'. __("Archive from category ",'wplt').'"' . single_cat_title('', false) . '"</li>';

            }
            elseif ( is_tax('product_cat') )
            {

                echo $prepend;

				$term    = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				
				$parents = array();
				$parent  = $term->parent;
                while ( $parent )
                {
                    $parents[] = $parent;
                    $new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
                    $parent = $new_parent->parent;
                }

                if ( ! empty( $parents ) )
                {
                    $parents = array_reverse( $parents );
                    foreach ( $parents as $parent )
                    {
                        $item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
                        echo '<li><a rel="v:url" property="v:title"  href="' . get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a></li>';
                    }
                }

                $queried_object = $wp_query->get_queried_object();
                echo '<li>'. $queried_object->name . '</li>';

            }
            elseif ( is_tax('project_category') || is_post_type_archive('portfolio') )
            {

                $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

                if ( !empty($term->parent) )
                {

                    $parents = array();
                    $parent = $term->parent;
                    while ( $parent )
                    {
                        $parents[] = $parent;
                        $new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
                        $parent = $new_parent->parent;
                    }

                    if ( ! empty( $parents ) )
                    {
                        $parents = array_reverse( $parents );
                        foreach ( $parents as $parent )
                        {
                            $item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
                            echo '<li><a rel="v:url" property="v:title"  href="' . get_term_link( $item->slug, 'product_cat' ) . '">' . $item->name . '</a></li>';
                        }
                    }

                }

                $queried_object = $wp_query->get_queried_object();
                echo '<li>'. $queried_object->name . '</li>';

            }
            elseif ( is_tax('documentation_category') )
            {

				$term    = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				
				$parents = array();
				$parent  = $term->parent;
                while ( $parent )
                {
					$parents[]  = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
					$parent     = $new_parent->parent;
                }

                if ( ! empty( $parents ) )
                {
                    $parents = array_reverse( $parents );
                    foreach ( $parents as $parent )
                    {
                        $item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
                        echo '<li><a rel="v:url" property="v:title"  href="' . get_term_link( $item->slug, 'documentation_category' ) . '">' . $item->name . '</a></li>';
                    }
                }

                $queried_object = $wp_query->get_queried_object();
                echo '<li>'. $queried_object->name . '</li>';

            }
            elseif ( is_search() )
            {
                echo '<li>'. __("Search results for ",'wplt').'"' . get_search_query() . '"</li>';

            }
            elseif ( is_day() )
            {
                echo '<li><a rel="v:url" property="v:title"  href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
                echo '<li><a rel="v:url" property="v:title"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li>';
                echo '<li>'.get_the_time('d').'</li>';

            }
            elseif ( is_month() )
            {
                echo '<li><a rel="v:url" property="v:title"  href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
                echo '<li>'.get_the_time('F').'</li>';

            }
            elseif ( is_year() )
            {
                echo '<li>'.get_the_time('Y').'</li>';
            }
            elseif ( is_single() && !is_attachment() )
            {

                if ( get_post_type() != 'post' )
                {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    //print_r($slug);
                    echo '<li><a rel="v:url" property="v:title" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
                    if ($showCurrent == 1) echo '<li>'.get_the_title().'</li>';
                }
                else
                {


                    // Show category name
                    $cat = get_the_category(); $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, '|');

                    $cats = explode ( '|',$cats );
                    foreach ( $cats as $s_cat )
                    {
                        if ( !empty ( $s_cat ) )
                        {
                            $s_cat = str_replace ( '<a', '<a rel="v:url" property="v:title" ' , $s_cat );
                            echo '<li>'.$s_cat.'</li>';
                        }
                    }
                    // Show post name
                    echo '<li>' . get_the_title() . '</li>';
                }

            }
            elseif
            ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() )
            {
                $post_type = get_post_type_object(get_post_type());
                if ( !empty ( $post_type->labels->singular_name ) )
                {
                    echo '<li>'.$post_type->labels->singular_name . '</li>';
                }

            }
            elseif ( is_attachment() )
            {
				$parent = get_post($post->post_parent);
				
				$cat    = get_the_category($parent->ID);
                if ( !empty($cat) )
                {
                    $cat = $cat[0];
                    echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                    echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
                    echo '<li>' . get_the_title() .'</li>';
                }
                else
                {
                    echo '<li>' . get_the_title() .'</li>';
                }

            }
            elseif ( is_page() && !self::is_subpage() )
            {
                if ($showCurrent == 1) echo '<li>'. get_the_title() . '</li>';

            }
            elseif ( is_page() && self::is_subpage() )
            {
				$parent_id   = $post->post_parent;
				$breadcrumbs = array();
                while ($parent_id)
                {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<li><a rel="v:url" property="v:title" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++)
                {
                    echo $breadcrumbs[$i];
                    //if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
                }
                if ($showCurrent == 1) echo  '<li>' . get_the_title() . '</li>';

            }
            elseif ( is_tag() )
            {
                echo '<li>'. __("Posts tagged ",'wplt').'"'.single_tag_title('', false).'"</li>';


            }
            elseif ( is_author() )
            {
                global $author;
                $userdata = get_userdata($author);
                echo '<li>'. __("Articles posted by ",'wplt') . $userdata->display_name .'</li>';

            }
            elseif ( is_404() )
            {
                echo '<li>'. __("Error 404 ",'wplt') .'</li>';
            }

            if ( get_query_var('paged') )
            {
                echo '<li>'.__('Page','wplt') . ' ' . get_query_var('paged').'</li>';
            }

            echo '</ul>';

        }
    }

    /** include dependencies file **/
    function style_selector_inc()
    {
        if(file_exists(WPLT_ABSPATH. 'wpl' .DS. 'style_selector.php'))
        {
            include_once WPLT_ABSPATH. 'wpl' .DS. 'style_selector.php';
        }
    }
}