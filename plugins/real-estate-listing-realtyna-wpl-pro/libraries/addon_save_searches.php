<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');

/**
 * Save Searches Add-on Library
 * @author Howard <howard@realtyna.com>
 * @package Save Searches Add-on
 */
class wpl_addon_save_searches
{
    /**
     * Search ID
     * @var int
     */
    public $id;
    
    /**
     * Addon Table Name
     * @var string
     */
    public $table = 'wpl_addon_save_searches';
    
    /**
     * Pagination
     * @var object
     */
    public $pagination;

    /**
     * Total Rows
     * @var integer
     */
    public $total;

    /**
     * Constructor method
     * @author Howard <howard@realtyna.com>
     */
    public function __construct($id = 0)
    {
        $this->id = $id;
    }
    
    /**
     * Saves a search record
     * @param array $values
     * @param int $id
     * @return int
     */
    public function save($values, $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        $exists = wpl_db::exists($id, $this->table);
        
        if($exists) return $this->update($values, $id);
        else return $this->create($values);
    }
    
    /**
     * Creates a new search record
     * @author Howard <howard@realtyna.com>
     * @param array $values
     * @return int
     */
    public function create($values = array())
    {
        $user_id = isset($values['user_id']) ? $values['user_id'] : 0;
        $kind = isset($values['kind']) ? $values['kind'] : 0;
        $name = isset($values['name']) ? trim($values['name']) : '';
        $alias = isset($values['alias']) ? wpl_global::url_encode($values['alias']) : '';
        $criteria = isset($values['criteria']) ? $values['criteria'] : array();
        $url = isset($values['url']) ? urldecode($values['url']) : '';
        $email_notification = isset($values['email_notification']) ? $values['email_notification'] : 1;
        $sms_notification = isset($values['sms_notification']) ? $values['sms_notification'] : 1;
        $enabled = isset($values['enabled']) ? $values['enabled'] : 1;
        
        $now = date('Y-m-d H:i:s');
        $creation_date = $now;
        $last_notify_date = $now;
        
        $query = "INSERT INTO `#__".$this->table."` (`user_id`,`kind`,`name`,`alias`,`criteria`,`url`,`creation_date`,`last_notify_date`,`email_notification`,`sms_notification`,`enabled`)"
                . " VALUES ('$user_id','$kind','$name','$alias','".json_encode($criteria)."','$url','$creation_date','$last_notify_date','$email_notification','$sms_notification','$enabled')";
        
        $inserted_id = wpl_db::q($query, "INSERT");
        
        /** Event Trigger **/
        wpl_events::trigger('save_searches_create', array('id'=>$inserted_id));
        
        return $inserted_id;
    }
    
    /**
     * Updates one search
     * @author Howard <howard@realtyna.com>
     * @param array $values
     * @param int $id
     * @return int
     */
    public function update($values = array(), $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        $possible_columns = wpl_db::columns($this->table);
        
        $q = '';
        foreach($values as $column=>$value)
        {
            if(!in_array($column, $possible_columns)) continue;
            $q .= "`$column`='".wpl_db::escape($value)."',";
        }
        
        $q = trim($q, ',');
        $query = "UPDATE `#__".$this->table."` SET $q WHERE `id`='$id'";
        
        $result = wpl_db::q($query, "UPDATE");
        
        /** Event Trigger **/
        wpl_events::trigger('save_searches_update', array('id'=>$id, 'values'=>$values));
        
        return $result;
    }
    
    /**
     * Returns list of save searches based on criteria
     * @author Howard <howard@realtyna.com>
     * @param array $criteria
     * @param string $select
     * @return array
     */
    public function get_save_searches($criteria = array(), $select = '*', $limit = 0, $order = '`creation_date` DESC')
    {
        $where = wpl_db::create_query($criteria);
        
        $query = "SELECT ".$select." FROM `#__".$this->table."` WHERE 1".(trim($where) ? " ".$where : "")." ORDER BY {$order} ";
        
        $res = wpl_db::select($query, 'loadAssocList');
        $this->total = count($res);
        if($limit)
        {
            $this->pagination = wpl_pagination::get_pagination($this->total, $limit);
            $query .= $this->pagination->limit_query;
        }

        return wpl_db::select($query, 'loadObjectList');
    }
    
    /**
     * Returns default membership id for registered users
     * @author Howard <howard@realtyna.com>
     * @return int
     */
    public function get_default_membership_id()
    {
        return -1;
    }
    
    /**
     * Runs after proeprty finalize and sends new listing notifications
     * @author Howard <howard@realtyna.com>
     */
    public function notify()
    {
        $now = time();
        $func_last_run_time = wpl_global::get_wp_option('wpl_ss_last_run_time', 0);
        
        // Return if notify function runs less than 5 minutes before
        if($func_last_run_time and $now - $func_last_run_time < 300) return;
        update_option('wpl_ss_last_run_time', $now);
        
        $query = "SELECT `id`, `kind`, `criteria`, `last_notify_date` FROM `#__".$this->table."` "
                . "WHERE 1 AND `enabled`>='1' AND `last_notify_date`<='".date('Y-m-d H:i:s', strtotime('-24 Hours'))."' ORDER BY `creation_date` DESC";
        $searches = wpl_db::select($query, 'loadAssocList');
        
        foreach($searches as $search)
        {
            $search_id = $search['id'];
            
            /** Update last_notify_date **/
            $this->update(array('last_notify_date'=>date('Y-m-d H:i:s')), $search_id);
            
            $this->kind = $search['kind'];
            $this->criteria = json_decode($search['criteria'], true);
            $this->last_notify_date = $search['last_notify_date'];
            $news = $this->search();
            
            if(!count($news)) continue;
            
            /** Trigger event for sending notification **/
            wpl_events::trigger('save_searches_new_results', array('id'=>$search_id, 'new_results'=>$news));
        }
    }
    
    /**
     * Searches through properties using search criteria and latest notify date
     * @author Howard <howard@realtyna.com>
     * @return array
     */
    public function search()
    {
        /** global settings **/
		$settings = wpl_settings::get_settings();
        
        /** property listing model **/
		$this->model = new wpl_property;
		
		$where = array(
            'sf_select_confirmed'=>1,
            'sf_select_finalized'=>1,
            'sf_select_deleted'=>0,
            'sf_select_expired'=>0,
            'sf_select_kind'=>$this->kind,
            'sf_rawdatemin_add_date'=>date('Y-m-d', strtotime($this->last_notify_date)),
            'sf_rawdatemax_add_date'=>date('Y-m-d')
        );
        
		/** start search **/
		$this->model->start(0, 100, $settings['default_orderby'], $settings['default_order'], array_merge($this->criteria, $where), $this->kind);
		$this->model->select = 'p.`id`';
		
		/** run the search **/
		$this->model->query();
		return $this->model->search();
    }
    
    /**
     * Returns saved search by id or all saved searches of a certain user
     * @author Howard <howard@realtyna.com>
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function get($id = NULL, $user_id = NULL)
    {
        if(!trim($id))
		{
            $user_id = trim($user_id) ? $user_id : wpl_users::get_cur_user_id();
            
			$query = "SELECT * FROM `#__".$this->table."` WHERE `user_id`='$user_id' ORDER BY `creation_date` DESC";
			return wpl_db::select($query, 'loadAssocList');
		}
		else
		{
			return wpl_db::get('*', $this->table, 'id', $id, false);
		}
    }
    
    /**
     * Deletes saved search by id or all saves searches of a certain user
     * @author Howard <howard@realtyna.com>
     * @param int $id
     * @param int $user_id
     * @return boolean|int
     */
    public function delete($id = NULL, $user_id = NULL)
    {
        /** Event Trigger **/
        wpl_events::trigger('save_searches_before_delete', array('id'=>$id, 'user_id'=>$user_id));
            
        if(!trim($id) and trim($user_id))
		{
			$query = "DELETE FROM `#__".$this->table."` WHERE `user_id`='$user_id'";
			return wpl_db::q($query);
		}
		elseif(trim($id)) return wpl_db::delete($this->table, $id);
        else return false;
    }
    
    /**
     * Returns URL of Saved search
     * @author Howard <howard@realtyna.com>
     * @param int $id
     * @return string
     */
    public function URL($id)
    {
        $url = wpl_sef::get_wpl_permalink(true);
        $nosef = wpl_sef::is_permalink_default();

        $wpl_main_page_id = wpl_sef::get_wpl_main_page_id();
        $home_type = wpl_global::get_wp_option('show_on_front', 'posts');
        $home_id = wpl_global::get_wp_option('page_on_front', 0);
        $save_search = $this->get($id);
        
        if(!$nosef)
        {
            if($home_type == 'page' and $home_id == $wpl_main_page_id) $url = $save_search['url'];
            else $url = trim($url, '/').'/search/'.$id.(trim($save_search['alias']) ? '-'.$save_search['alias'] : '').'/';
        }
        else
        {
            $url = $save_search['url'];
        }
        
        return $url;
    }
    
    public function render_listings($property_ids = NULL)
    {
        /** First Validation **/
        if(!is_array($property_ids)) return false;
        $path = _wpl_import('views.frontend.addon_save_searches.tmpl.internal_notify_listings', true, true);
        
        ob_start();
        include $path;
        return $output = ob_get_clean();
    }
}