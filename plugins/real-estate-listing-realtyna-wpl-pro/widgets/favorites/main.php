<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.widgets');

class wpl_favorites_widget extends wpl_widget
{
    public $wpl_tpl_path = 'widgets.favorites.tmpl';
    public $wpl_backend_form = 'widgets.favorites.form';
    public $widget_id;
    public $widget_uq_name; # widget unique name
    public $data;
    
    public function __construct()
    {
        parent::__construct('wpl_favorites_widget', __('(WPL) Favorites', 'wpl'), array('description'=>__('Favorite widget to add property to a list by anonymous user.', 'wpl')));
    }

    /**
     * How to display the widget on the screen.
     */
    public function widget($args, $instance)
    {
        $this->widget_id = $this->number;
        if($this->widget_id < 0) $this->widget_id = abs($this->widget_id)+1000;
        
        $this->widget_uq_name = 'wplf'.$this->widget_id;
        $this->pids = wpl_addon_pro::favorite_get_pids();

        $this->compare_url = wpl_addon_pro::compare_get_url();
        
        $widget_id = $this->widget_id;
        $this->css_class = isset($instance['data']['css_class']) ? $instance['data']['css_class'] : '';
        
        echo $args['before_widget'];

        $this->title = apply_filters('widget_title', $instance['title']);

        $data = $instance['data'];
        $this->wpltarget = isset($instance['wpltarget']) ? $instance['wpltarget'] : 0;
        $this->data = $data;
        
        $layout = 'widgets.favorites.tmpl.' . $instance['layout'];
        $layout = _wpl_import($layout, true, true);

        if(!wpl_file::exists($layout)) $layout = _wpl_import('widgets.favorites.tmpl.default', true, true);
        if(wpl_file::exists($layout)) require $layout;
        else echo __('Widget Layout Not Found!', 'wpl');

        echo $args['after_widget'];
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    public function form($instance)
    {
        $this->widget_id = $this->number;
        
        /** Set up some default widget settings. **/
        if(!isset($instance['layout']))
        {
            $instance = array('title'=>__('Favorites', 'wpl'), 'layout'=>'default.php',
                'data'=>array(
                    'max_char_title'=>'15',
                    'image_width'=>'32',
                    'image_height'=>'32',
					'show_contact_form'=>'1',
					'show_compare'=>'0'
            ));
			
			$defaults = array();
            $instance = wp_parse_args((array) $instance, $defaults);
        }

        $path = _wpl_import($this->wpl_backend_form, true, true);

        ob_start();
        include $path;
        echo $output = ob_get_clean();
    }

    /**
     * Update the widget settings.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['layout'] = $new_instance['layout'];
        $instance['wpltarget'] = $new_instance['wpltarget'];
        $instance['data'] = (array) $new_instance['data'];
        
        return $instance;
    }
}