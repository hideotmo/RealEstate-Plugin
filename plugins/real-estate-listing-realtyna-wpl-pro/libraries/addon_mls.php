<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.addon_mls.mapper');
_wpl_import('libraries.property');
_wpl_import('libraries.logs');

/**
 * MLS addon Library
 * @author Howard <howard@realtyna.com>
 * @package MLS Add-on
 */
class wpl_addon_mls
{
	var $rets;
	var $tmp_path;
	var $agent_username = 'PHRETS/1.0.1';
	var $rets_version = 'RETS/1.5';
	var $search;
	var $results = array();
	var $agent_password;
    
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-12-09
		Description: Constructor method
	**/
	public function __construct($mls_server_id = NULL)
	{
		/** settings **/
		@ini_set('memory_limit', '-1');
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
		
		$this->mls_server_id = $mls_server_id;
		$this->rets = $this->get_RETS();
		$this->tmp_path = wpl_global::init_tmp_folder();
		
		if($this->mls_server_id)
		{
			$this->mls_server_data = $this->get_servers($this->mls_server_id);
			$this->login_url = $this->mls_server_data->url;
			$this->username = $this->mls_server_data->username;
			$this->password = $this->mls_server_data->password;
			$this->rets_version = $this->mls_server_data->rets_version;
			$this->agent_username = $this->mls_server_data->agent_username;
			$this->agent_password = $this->mls_server_data->agent_password;
		}
	}
	
	/**
		Developed by : Howard
		Inputs : mls_server_id
		Outputs : mls servers
		Date : 2013-12-09
	**/
	public static function get_servers($mls_server_id = NULL)
	{
		$query = "SELECT * FROM `#__wpl_addon_mls` WHERE 1 ".($mls_server_id ? "AND `id`='$mls_server_id'" : "")." ORDER BY `id` ASC";
		
		$output = $mls_server_id ? 'loadObject' : 'loadObjectList';
		return wpl_db::select($query, $output);
	}
	
	/**
		Developed by : Howard
		Inputs : mls_query_id
		Outputs : mls queries
		Date : 2013-12-14
	**/
	public static function get_mls_queries($mls_query_id = NULL)
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_queries` WHERE 1 ".($mls_query_id ? "AND `id`='$mls_query_id'" : "")." ORDER BY `id` ASC";
		
		$output = $mls_query_id ? 'loadObject' : 'loadObjectList';
		return wpl_db::select($query, $output);
	}
	
	/**
		Developed by : Howard
		Inputs : mls_server_id
		Outputs : void
		Date : 2013-12-09
	**/
	public static function remove_server($mls_server_id = NULL)
	{
		wpl_db::delete('wpl_addon_mls', $mls_server_id);
        
        /** trigger event **/
		wpl_global::event_handler('mls_server_removed', array('id'=>$mls_server_id));
	}
	
	/**
		Developed by : Howard
		Inputs : mls_server_id
		Outputs : array result
		Date : 2013-12-09
	**/
	public function test_connection()
	{
		$rets_connection = $this->connect();
        $this->rets = $this->get_RETS();
        return $rets_connection;
	}
	
	/**
		Developed by : Howard
		Inputs : mls_server_id, condition
		Outputs : mls fields
		Date : 2013-12-10
	**/
	public static function get_fields($mls_server_id = NULL, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = "";
			if(trim($mls_server_id) != '') $condition .= "AND `mls_server_id`='$mls_server_id' ";
		}
		
		$query = "SELECT * FROM `#__wpl_addon_mls_mappings` WHERE 1 ".$condition;
		return wpl_db::select($query, 'loadObjectList');
	}
	
	/**
		Developed by : Howard
		Inputs : field_id
		Outputs : field data
		Date : 2013-12-10
	**/
	public static function get_field($field_id = NULL)
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_mappings` WHERE 1 AND `id`='$field_id'";
		return wpl_db::select($query, 'loadObject');
	}
	
	/**
		Developed by : Howard
		Inputs : mls_server_id, condition
		Outputs : mls classes
		Date : 2013-12-10
	**/
	public static function get_classes($mls_server_id = NULL)
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_mappings` WHERE 1 AND `mls_server_id`='$mls_server_id' GROUP BY `mls_class_id`";
		$results = wpl_db::select($query, 'loadObjectList');
		
		$classes = array();
		foreach($results as $result)
		{
			$classes[$result->mls_class_id] = $result->mls_class_name;
		}
		
		return $classes;
	}
	
	/**
		Developed by : Howard
		Inputs : field_id, wpl_field id
		Outputs : boolean
		Date : 2013-12-10
	**/
	public static function save_mapping($field_id, $wpl_field_id, $custom1 = '')
	{
		/** map the field itself **/
		$query = "UPDATE `#__wpl_addon_mls_mappings` SET `wpl_field_id`='$wpl_field_id', `custom1`='$custom1' WHERE `id`='$field_id'";
		wpl_db::q($query);
		
		/** map same fields on other mls classes if they're not mapped **/
		$field_data = wpl_addon_mls::get_field($field_id);
		$query = "UPDATE `#__wpl_addon_mls_mappings` SET `wpl_field_id`='$wpl_field_id', `custom1`='$custom1' WHERE `mls_server_id`='".$field_data->mls_server_id."' AND `field_id`='".$field_data->field_id."' AND `field_type`='".$field_data->field_type."' AND `wpl_field_id`=''";
		wpl_db::q($query);
		
        /** trigger event **/
		wpl_global::event_handler('mls_mapping_saved', array('field_id'=>$field_id, 'wpl_field_id'=>$wpl_field_id, 'custom1'=>$custom1));
        
		return true;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : connection
		Date : 2013-12-09
	**/
	public function connect($new_rets = false)
	{
		// Headers for vesrsion and user agent
		$this->rets->AddHeader('Accept', '*/*');
		$this->rets->AddHeader('RETS-Version', $this->rets_version);
		$this->rets->AddHeader('User-Agent', $this->agent_username);

		// set path for cookies
		$this->rets->SetParam('cookie_file', $this->tmp_path.'phrets_cookies.txt');
		
		/** Connect **/

		return $this->rets->Connect($this->login_url, $this->username, $this->password, $this->agent_password);
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : search result
		Date : 2013-12-12
	**/
	public function search($limit = 10, $query = '')
	{
		$this->search = $this->rets->SearchQuery($this->mls_server_data->resource, $this->mls_class_id, $query, array('Limit'=>$limit, 'Count'=>1));
		return $this->search;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : search result
		Date : 2013-12-12
	**/
	public function results()
	{
		$this->results = array();
		while($result = $this->rets->FetchRow($this->search)) $this->results[] = $result;
		
		return $this->results;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : PHRETS object
		Date : 2013-12-09
	**/
	public static function get_RETS()
	{
		/** import PHRETS library **/
		_wpl_import('libraries.addon_mls.phrets');

		/** Initialize Object **/
		return new PHRETS;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-12-09
	**/
	public function import_basic_data()
	{
		$mls_server = self::get_servers($this->mls_server_id);
		
		$this->connect();
		$mls_classes = $this->rets->GetMetadataClasses($mls_server->resource);
        $mls_info = !empty($mls_server->mls_info) ? json_decode($mls_server->mls_info, true) : '';
        
        if(!isset($mls_info['objects']) or empty($mls_info['objects']))
        {
            $objects = $this->rets->GetMetadataObjects($mls_server->resource);
            foreach($objects as $object) $mls_info['objects'][] = array('ObjectType'=>$object['ObjectType'], 'Description'=>$object['Description']);
        }
        if(!isset($mls_info['classes']) or empty($mls_info['classes']))
        {
            foreach($mls_classes as $mls_class) $mls_info['classes'][] = array('ClassName'=>$mls_class['ClassName'], 'Description'=>$mls_class['Description']);
            
        }
        
        $mls_info = json_encode($mls_info);
        $query = "UPDATE `#__wpl_addon_mls` SET `mls_info`='".wpl_db::escape($mls_info)."' WHERE `id`='".$this->mls_server_id."'";
        wpl_db::q($query);
        
        
		/********
		* importing mls fields
		********/
		foreach($mls_classes as $mls_class)
		{
			$mls_class_id = $mls_class['ClassName'];
			$mls_class_name = trim($mls_class['Description']) != '' ? $mls_class['Description'] : $mls_class['ClassName'];

			$fields = $this->rets->GetMetadataTable($mls_server->resource, $mls_class_id);
            
            $imported_unique_field = false;
            
			foreach($fields as $field)
			{
				$field_id = wpl_db::escape($field['SystemName']);
				$field_name = wpl_db::escape($field['LongName']);
				$field_type = $field['DataType'];
                $field_lookupName = wpl_db::escape($field['LookupName']);
                $field_Searchable = $field['Searchable'];
                
                if($field['Unique'] and !$imported_unique_field)
                {
                    $query = "SELECT `mls_unique_field` FROM `#__wpl_addon_mls` WHERE `id`='".$this->mls_server_id."'";
		            $unique_field =  wpl_db::select($query, 'loadResult');
                    if(!empty($unique_field)) $imported_unique_field = true;
                    
                    if(!$imported_unique_field) wpl_db::update('wpl_addon_mls', array('mls_unique_field'=>$field_id), 'id', $this->mls_server_id);
                }
                
				$num_query = "SELECT COUNT(id) FROM `#__wpl_addon_mls_mappings` WHERE `mls_server_id`='{$this->mls_server_id}' AND `mls_class_id`='$mls_class_id' AND `field_id`='$field_id'";
				if(wpl_db::num($num_query)) continue;
				
				$query = "INSERT INTO `#__wpl_addon_mls_mappings` (`mls_server_id`,`field_id`,`field_name`,`field_type`,`field_sample_data`,`mls_class_id`,`mls_class_name`,`mls_field_lookup_name`,`mls_field_searchable`) VALUES ('{$this->mls_server_id}','$field_id','$field_name','$field_type','','$mls_class_id','$mls_class_name','$field_lookupName','$field_Searchable')";
				wpl_db::q($query);
                
                /**
                 * Adding the values for each MLS field
                 */
                $this->add_mls_field_values($mls_server, $field_lookupName, $field_id, $mls_class_id);
			}
            
			/********
			* importing sample data
			********/
			$search = $this->rets->SearchQuery($mls_server->resource, $mls_class_id, (trim($mls_server->mls_sample_query) ? $mls_server->mls_sample_query : '*'), array('Limit'=>10));
			
			$sample_values = array();
			for($i=1; $i<=10; $i++)
			{
				$listing = $this->rets->FetchRow($search);
				if(!$listing) continue;
				
				foreach($listing as $key=>$value) $sample_values[$key][] = $value;
			}
			
			foreach($sample_values as $key=>$values)
			{
				$values_str = '';
				$values = array_unique($values);
				foreach($values as $value) if(trim($value)) $values_str .= '['.trim($value).']';
				
				$query = "UPDATE `#__wpl_addon_mls_mappings` SET `field_sample_data`='".wpl_db::escape($values_str)."' WHERE `field_id`='".$key."' AND `mls_server_id`='".$this->mls_server_id."' AND `mls_class_id`='".$mls_class_id."' AND `field_sample_data`=''";
				wpl_db::q($query);
			}
			
			$this->rets->FreeResult($search);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : mls_field_id
		Outputs : boolean
		Date : 2013-12-10
	**/
	public static function create_field($mls_field_id = NULL, $dbst_type = NULL)
	{
		$mls_field_data = wpl_addon_mls::get_field($mls_field_id);
		
		$category = wpl_addon_mls::get_wpl_mls_category();
		if(!trim($dbst_type)) $dbst_type = wpl_addon_mls::detect_wpl_field_type($mls_field_data->field_type);
		$dbst_kind = wpl_addon_mls::get_wpl_mls_kind();
		$dbst_id = wpl_flex::create_default_dbst(NULL, 0); #WPL field Created
		
		$q = '';
		$q .= "`kind`='".$dbst_kind."', `name`='".$mls_field_data->field_name."', `type`='".$dbst_type."', `category`='".$category."'";
		
		$query = "UPDATE `#__wpl_dbst` SET ".$q." WHERE `id`='$dbst_id'";
		wpl_db::q($query, 'update');
		
		/** run queries **/
		wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'add');
		
		/** Save mapping **/
		wpl_addon_mls::save_mapping($mls_field_id, $dbst_id);
		
		/** trigger event **/
		wpl_global::event_handler('dbst_modified', array('id'=>$dbst_id, 'mode'=>'add', 'kind'=>$dbst_kind, 'type'=>$dbst_type));
		
		return true;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : dbcat id of MLS addon
		Date : 2013-12-10
	**/
	public static function get_wpl_mls_category()
	{
		return 31;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : kind id of MLS addon
		Date : 2013-12-10
	**/
	public static function get_wpl_mls_kind()
	{
		return 0;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : dbcat id of MLS addon
		Date : 2013-12-10
	**/
	public static function detect_wpl_field_type($mls_type)
	{
		$mls_type = strtolower($mls_type);
		$wpl_types = array('int'=>'number', 'character'=>'text', 'small'=>'number', 'datetime'=>'datetime', 'date'=>'date', 'decimal'=>'number');
		
		if(isset($wpl_types[$mls_type])) return $wpl_types[$mls_type];
		else return 'text';
	}
	
	#TODO
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-12-15
	**/
	public static function sync()
	{
		
	}
	
	/**
		Developed by : Howard
		Inputs : mls listings object, query id
		Outputs : mapped data
		Date : 2013-12-16
	**/
	public static function map($mls_listings, $mls_query_id)
	{
		/** global vars **/
		global $mls_query;
		global $mls_server;
		global $mls_fields;
		global $wpl_fields;
		global $mls_sorted_fields;
		
		/** force to array **/
		$mls_listings = (array) $mls_listings;
		
		/** first validation **/
		if(trim($mls_query_id) == '') return false;
		
		/** including mapper classes **/
		$path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'fields'.DS;
		
		$files = wpl_folder::files($path);
		foreach($files as $file)
		{
			/** get overrode file **/
			$overrode_path = $path.'overrides'.DS.$file;
			
			if(wpl_file::exists($overrode_path)) include_once $overrode_path;
			else include_once $path.$file;
		}
		
		$mls_query = wpl_addon_mls::get_mls_queries($mls_query_id);
		$mls_server = wpl_addon_mls::get_servers($mls_query->mls_server_id);
		
		$mls_fields = wpl_addon_mls::get_fields('', " AND `mls_server_id`='{$mls_query->mls_server_id}' AND `mls_class_id`='{$mls_query->mls_class_id}' AND `wpl_field_id`!=''");
		$wpl_fields = (array) wpl_flex::get_fields();
		
        /** Get Mapper Object **/
        $mapper = new wpl_addon_mls_mapper();
        
		$mls_sorted_fields = array();
		foreach($mls_fields as $mls_field) $mls_sorted_fields[$mls_field->field_id] = (array) $mls_field;
		
		$mapped = array();
		foreach($mls_listings as $mls_listing)
		{
			$wpl_listing = array();
			
			/** run before mapping function and set the default values **/
			$default_values = $mapper->before_mapping($mls_listing, $mls_query_id, $mls_listings);
			$wpl_listing = array_merge($wpl_listing, $default_values);
			
			foreach($mls_listing as $mls_field_id=>$mls_value)
			{
				$wpl_field_id = isset($mls_sorted_fields[$mls_field_id]['wpl_field_id']) ? $mls_sorted_fields[$mls_field_id]['wpl_field_id'] : NULL;
				if(!trim($wpl_field_id)) continue;
				
				$wpl_field = (array) $wpl_fields[$wpl_field_id];
				$mls_field = $mls_sorted_fields[$mls_field_id];
				
				$arr = array();
				$arr['mls_value'] = $mls_value;
				$arr['mls_field'] = $mls_field;
				$arr['wpl_field'] = $wpl_field;
				
				/** edit values **/
				$class_name = 'wpl_mls_'.$wpl_field['type'].'_map';
				
				$return = array();
				if(class_exists($class_name)) $class_obj = new $class_name();
				else $class_obj = new wpl_addon_mls_mapper();
				
                $mls_value = utf8_decode($mls_value);
				$return = $class_obj->map($wpl_field, $mls_value, $mls_listing, $mls_field);
			
				if(isset($return['value']) and is_array($return['value']))
                {
                    $i = 0;
                    foreach($return['value'] as $value)
                    {
                        $arr['wpl_value'] = isset($value) ? $value : $mls_value;
                        $wpl_table_column = isset($return['table_column'][$i]) ? $return['table_column'][$i] : $wpl_field['table_column'];
                        $i++;
                        
                        /** skip to next field **/
                        if(!trim($wpl_table_column)) continue;

                        $arr['wpl_table_column'] = $wpl_table_column;
                        $wpl_listing[$wpl_table_column] = $arr;
                    }
				}
				else
				{
					$arr['wpl_value'] = isset($return['value']) ? $return['value'] : $mls_value;
					$wpl_table_column = isset($return['table_column']) ? $return['table_column'] : $wpl_field['table_column'];
					$arr['wpl_table_column'] = $wpl_table_column;
				
					$wpl_listing[$wpl_table_column] = $arr;
				}
			}
			
			/** run after mapping function **/
			$wpl_listing = $mapper->after_mapping($wpl_listing, $mls_query_id, $mls_listing);
			
			/** add listing to the final results **/
			$mapped[] = $wpl_listing;
		}
		
		return $mapped;
	}
	
	/**
		Developed by : Howard
		Inputs : mls_query_id
		Outputs : select text
		Date : 2013-12-16
	**/
	public static function generate_selects_query($mls_query_id)
	{
		/** first validation **/
		if(trim($mls_query_id) == '') return false;
		
		$mls_query = wpl_addon_mls::get_mls_queries($mls_query_id);
		$mappings = wpl_addon_mls::get_fields('', " AND `mls_server_id`='{$mls_query->mls_server_id}' AND `mls_class_id`='{$mls_query->mls_class_id}' AND `wpl_field_id`!=''");
		
		$selects = '';
		foreach($mappings as $mapping)
		{
			$selects .= $mapping->field_id.', ';
		}
		
		$selects = trim($selects, ', ');
		wpl_db::set('wpl_addon_mls_queries', $mls_query_id, 'selects', $selects);
		
		return $selects;
	}
	
	/**
		Developed by : Howard
		Inputs : mapped_data, mls_query_id and wpl_unique_table_column
		Outputs : wpl property ids
		Date : 2013-12-17
	**/
	public static function import_mapped_data($mapped_data, $mls_query_id = NULL, $wpl_unique_table_column = NULL, $mls_query = '')
	{
		$wpl_unique_field = wpl_addon_mls::get_wpl_unique_field($mls_query_id);
		$unique_table_column = $wpl_unique_table_column ? $wpl_unique_table_column : $wpl_unique_field['table_column'];
		$row_query = wpl_addon_mls::get_mls_queries($mls_query_id);
		$mls_category = $row_query->mls_class_name;
		
		//Logs
		if($mls_query)
		{
			$section = 'Cron Job';
		}
		else
		{
			$section = 'Backend Import';
			$mls_query = $row_query->query;	
		}
		
        $log_params = array();
		$log_params['Query'] = $mls_query;
		$log_params['Category'] = $mls_category;
		$log_params['Section'] = $section;
		$log_params['User_id'] = $row_query->default_user_id;
        
		return wpl_property::import($mapped_data, $unique_table_column, '', 'mls', false, $log_params);
	}
	
	/**
		Developed by : Howard
		Inputs : mls_query_id, mls_server_id
		Outputs : wpl unique field name
		Date : 2013-12-17
		Description : Use this function for converting mls usnique field to WPl unique field for example "ListingID" to "mls_id"
	**/
	public static function get_wpl_unique_field($mls_query_id, $mls_server_id = '')
	{
		if(!$mls_server_id)
		{
			$mls_query = wpl_addon_mls::get_mls_queries($mls_query_id);
			$mls_server = wpl_addon_mls::get_servers($mls_query->mls_server_id);
			
			$query = "SELECT `wpl_field_id` FROM `#__wpl_addon_mls_mappings` WHERE 1 AND `mls_server_id`='{$mls_query->mls_server_id}' AND `mls_class_id`='{$mls_query->mls_class_id}' AND `field_id`='{$mls_server->mls_unique_field}'";
		}
		else
		{
			$mls_server = wpl_addon_mls::get_servers($mls_server_id);
			
			$query = "SELECT `wpl_field_id` FROM `#__wpl_addon_mls_mappings` WHERE 1 AND `mls_server_id`='$mls_server_id' AND `field_id`='{$mls_server->mls_unique_field}' LIMIT 1";
		}
		
		$wpl_field_id = wpl_db::select($query, 'loadResult');
		$wpl_field = wpl_flex::get_field($wpl_field_id);
		
		return array('wpl_field_id'=>$wpl_field_id, 'table_column'=>$wpl_field->table_column);
	}
	
	/**
		Developed by : Howard
		Inputs : property_ids, mls_server_id, import_limit, force_to_update
		Outputs : count of images array
		Date : 2013-12-17
		Description : Use this function for saving image of some properties
	**/
	public function import_properties_images($property_ids, $mls_server_id, $import_limit = -1, $force_to_update = false)
	{
		$results = array();
		foreach($property_ids as $property_id)
		{
			$results[$property_id] = $this->import_property_images($property_id, $mls_server_id, $import_limit, $force_to_update);
		}
		
		return $results;
	}
	
	/**
		Developed by : Howard
		Inputs : property_id, mls_server_id, import_limit, force_to_update
		Outputs : count images
		Date : 2013-12-17
		Description : Use this function for saving image of one property
	**/
	public function import_property_images($property_id, $mls_server_id, $import_limit = -1, $force_to_update = false)
	{
        if($this->mls_server_data->custom_image_resource != null)
		{
			$this->custom_import_property_images($property_id, $mls_server_id, $import_limit = -1, $force_to_update = false);
			return;
		}
        
		/** remove current images **/
		$query = "SELECT * FROM `#__wpl_items` WHERE 1 AND `parent_id`='$property_id' AND `parent_kind`='0' AND `item_type`='gallery' AND `item_extra3`='mls' OR `item_name` LIKE '%MLS_external%' ORDER BY `index` ASC";
		$galleries = wpl_db::select($query, 'loadObjectList');
		
		/** skip if force to update is false and images already imported **/
		if(!$force_to_update and count($galleries) >= $import_limit and $import_limit != -1) return count($galleries);

		$unique_filed = $this->get_wpl_unique_field('', $mls_server_id);
		$listing_id = wpl_db::get($unique_filed['table_column'], 'wpl_properties', 'id', $property_id);

		if($listing_id == 0 or empty($listing_id) ) return;

		$images = $this->rets->GetObject($this->mls_server_data->resource, $this->mls_server_data->image_resource, $listing_id, '*', $this->mls_server_data->image_location);
		$property_folder = wpl_items::get_path($property_id);
        
		if($images[0]['Success']) foreach($galleries as $gallery) wpl_items::delete_file($gallery->item_name, $property_id, 0);
        
        /** Deleting property thumbnails */
        $clear_property_thumbnails = wpl_global::get_setting('clear_thumbnails_after_update', 1);
        if($clear_property_thumbnails) wpl_property::clear_property_thumbnails($property_id);

		$i = 1;
		if(is_array($images) and count($images) > 0)
		{
			foreach($images as $image)
			{
				if($import_limit != -1 and $i > $import_limit) break;
				
				$content_type = $image['Content-Type'] ? strtolower($image['Content-Type']) : 'image/jpeg';
				
                $ex_ct = explode(';', $content_type);
                $content_type = $ex_ct[0];
        
				if($content_type == 'image/jpeg' or $content_type == 'image/jpg' or $content_type == 'image/pjpeg') $extension = 'jpg';
				elseif($content_type == 'image/png') $extension = 'png';
				elseif($content_type == 'image/gif') $extension = 'gif';
                
				if($extension)
				{
                    // External Image
                    if($this->mls_server_data->image_location)
                    {
                        $item_name = 'MLS_external'.$i.'.'.$extension;
                        wpl_items::save(array('parent_kind'=>'0', 'parent_id'=>$property_id, 'item_type'=>'gallery', 'item_cat'=>'external', 'item_name'=>$item_name, 'item_extra3'=>$image['Location'], 'index'=>$image['Object-ID']));
                    }
                    else
                    {
                        $item_name = 'MLS'.$i.'.'.$extension;
                        wpl_file::write($property_folder .DS. $item_name, $image['Data']);
                        wpl_items::save(array('parent_kind'=>'0', 'parent_id'=>$property_id, 'item_type'=>'gallery', 'item_cat'=>'image', 'item_name'=>$item_name, 'item_extra3'=>'mls', 'index'=>$i));
                    }
                    
					$i++;
				}
			}
		}
		
		return count($images);
	}
	
	/**
		Developed by : Howard
		Inputs : property_id
		Outputs : void
		Date : 2013-12-18
		Description : Use this function for finalizing the properties after import
	**/
	public static function finalize($property_id, $mode = 'edit')
	{
		$user_id = wpl_property::get_property_user($property_id);
		
        /** Get Mapper Object **/
        $mapper = new wpl_addon_mls_mapper();
        
		/** run MLS before finalize function **/
		$mapper->before_finalize($property_id, $mode, $user_id);
		
		/** run WPL finalize function **/
		wpl_property::finalize($property_id, $mode, $user_id);
		
		/** run MLS after finalize function **/
		$mapper->after_finalize($property_id, $mode, $user_id);
	}
	
	/**
     * Returns WPL selected fields of MLS
     * @author Natan <natan@realtyna.com>
     * @static
     * @param int $mls_server_id, varchar $mls_class_id
     * @return array
    */
    public static function get_wpl_selected_fields($mls_server_id, $mls_class_id)
    {
        $query = "SELECT `wpl_field_id` FROM `#__wpl_addon_mls_mappings` WHERE 1 AND `mls_server_id`='$mls_server_id' AND `mls_class_id`='$mls_class_id' AND `wpl_field_id`!=''";
		$fields = wpl_db::select($query, 'loadAssocList');
        
        $wpl_fields = array();
        foreach($fields as $field)
        {
            $wpl_fields[] = $field['wpl_field_id'];
        }
        
        return $wpl_fields;
    }
	
	/**
		Developed by : Natan
		Inputs : Added unique_ids, Updated unique_ids, Params
		Outputs : void
		Date : 2014-09-28
		Description : Use this function for Logging after import
	**/
	public static function log($added, $updated, $log_params)
	{
		$log = '';
		if(count($added) > 0) $log .= implode(', ', $added).(count($added) > 1 ? ' were ':' was ').'added AND ';
		else $log .= 'Nothing added AND ';
        
		if(count($updated) > 0) $log .= implode(', ', $updated).(count($updated) > 1 ? ' were ':' was ').'updated.';
		else $log .= 'Nothing updated.';
		
		$params='Query: '.$log_params['Query'].', MLS Class Name: '.$log_params['Category'];
		wpl_logs::add($log, $log_params['Section'], 1, $log_params['User_id'], 1, 3, $params);
	}
    
    /**
	 * Use this function for saving image of one property from other MLS resource
	 * @author Matthew N. <matthew@realtyna.com>
	 * @param  int  $property_id
	 * @param  int  $mls_server_id
	 * @param  int $import_limit
	 * @param  boolean $force_to_update
	 * @return count of images            
	 */
	public function custom_import_property_images($property_id, $mls_server_id, $import_limit = -1, $force_to_update = false)
	{
		$custom_recource = trim($this->mls_server_data->custom_image_resource);
		if(!stristr($custom_recource, ':')) return false;

		$resuorce_fields = explode(':', $custom_recource);

		/** remove current images **/
		$query = "SELECT * FROM `#__wpl_items` WHERE 1 AND `parent_id`='$property_id' AND `parent_kind`='0' AND `item_type`='gallery' AND (`item_extra3`='mls' OR `item_cat`='external') ORDER BY `index` ASC";
		$galleries = wpl_db::select($query, 'loadObjectList');

		/** skip if force to update is false and images already imported **/
		if(!$force_to_update and count($galleries) >= $import_limit and $import_limit != -1) return count($galleries);

		foreach($galleries as $gallery) wpl_items::delete_file($gallery->item_name, $property_id, 0);
            
        /** Deleting property thumbnails */
        $clear_property_thumbnails = wpl_global::get_setting('clear_thumbnails_after_update', 1);
        if($clear_property_thumbnails) wpl_property::clear_property_thumbnails($property_id);

		$unique_filed = $this->get_wpl_unique_field('', $mls_server_id);
		$listing_id = wpl_db::get($unique_filed['table_column'], 'wpl_properties', 'id', $property_id);
		
		/**
		 * $resuorce_fields[0] = MLS photo resource
		 * $resuorce_fields[1] = MLS photo class
		 * $resuorce_fields[2] = MLS photo listing id field
		 * @var [type]
		 */
		$search_photos = $this->rets->SearchQuery($resuorce_fields[0], $resuorce_fields[1], "(".$resuorce_fields[2]."=$listing_id)");

		$property_folder = wpl_items::get_path($property_id);

		$i = 1;
		
		while ($photos_object = $this->rets->FetchRow($search_photos))
		{
			if($import_limit != -1 and $i > $import_limit) break;
			
			$content_type = 'image/jpeg';
			
			if($photos_object['FileExtension'] == '.jpeg'
			or $photos_object['FileExtension'] == '.jpg') $extension = 'jpg';
			elseif($photos_object['FileExtension'] == '.png') $extension = 'png';
			elseif($photos_object['FileExtension'] == '.gif') $extension = 'gif';
			
			if ($extension)
			{
				$item_name = 'MLS'.$i.'.'.$extension;

				wpl_file::write($property_folder .DS. $item_name, file_get_contents($photos_object['MediaURL']));
				wpl_items::save(array('parent_kind'=>'0', 'parent_id'=>$property_id, 'item_type'=>'gallery', 'item_cat'=>'image', 'item_name'=>$item_name, 'item_extra3'=>'mls'));
								
				$i++;
			}
		}
		return count($photos_object);
	}
    
    /**
     * Export Mapping for a file
     * @author Matthew  <matthew@realtyna.com>
     * @static
     * @param  string $format File Format
     * @return object 		  Settings File
     */
    public static function export_mapping($mls_class_id, $format = 'json')
    {
        $query_select_fields = "SELECT `mapping`.`field_id`, `mapping`.`wpl_field_id`, `mapping`.`mls_class_id`, `mapping`.`custom1`, `dbst`.`id` AS `dbst_id`, `dbst`.`kind`, `dbst`.`name`, `dbst`.`options`, `dbst`.`table_column`, `dbst`.`type`, `dbst`.`category`, `dbst`.`searchmod` FROM `#__wpl_dbst` AS `dbst` INNER JOIN `#__wpl_addon_mls_mappings` AS `mapping` ON `dbst`.`id`=`mapping`.`wpl_field_id` WHERE `mapping`.`mls_class_id`='$mls_class_id'";
        $fields = wpl_db::select($query_select_fields, 'loadObjectList');
        
        $generate_fields = array();
        foreach($fields as $field) $generate_fields[$field->dbst_id] = $field;
        
    	if($format == 'json') return json_encode($generate_fields);
    	elseif($format == 'xml')
    	{
    		$xml = new SimpleXMLElement('<wpl_mls_mapping/>');
    		foreach($generate_fields as $k=>$v) $xml->addChild($k, htmlspecialchars($v));
            
		    return $xml->asXML();
    	}
    	else return NULL;
    }
    
    /**
     * Import Settings from a file
     * @author Matthew  <matthew@realtyna.com>
     * @static
     * @param  string  $file Settings File
     * @return boolean		 Result
     */
    public static function import_mapping($file)
    {
    	$content = wpl_file::read($file);
    	$ext = wpl_file::getExt($file);

    	if($ext == 'json')
    	{
    		$mappings = json_decode($content);
	    	if(!$mappings) return false;
    	}
    	elseif($ext == 'xml')
    	{
    		$mappings = simplexml_load_string($content);
			if(!$mappings) return false;
            
			$mappings = (array) $mappings;
    	}
    	else return false;
        
    	foreach($mappings as $id=>$value)
        {
            $mls_field_id = $value->field_id;
            $mls_field_class_id = $value->mls_class_id;
            $mls_field_custom1 = $value->custom1;
            $wpl_field_name = $value->name;
            $wpl_field_id = $id;
            $wpl_field_type = $value->type;
            $wpl_field_options = $value->options;
            $wpl_field_kind = $value->kind;
            $wpl_field_category = $value->category;
            $wpl_field_table_column = $value->table_column;
            $wpl_field_table_searchmod = isset($value->searchmod) ? $value->searchmod : 0;
            
            $check_exist_wpl_field = wpl_flex::get_field($wpl_field_id);
            
            if(!empty($check_exist_wpl_field))
            {
                $wpl_field_array_update = array(
                    'name'=>$wpl_field_name,
                    'type'=>$wpl_field_type,
                    'options'=>$wpl_field_options,
                    'kind'=>$wpl_field_kind,
                    'category'=>$wpl_field_category,
                    'searchmod'=>$wpl_field_table_searchmod
                );
                wpl_db::update('wpl_dbst', $wpl_field_array_update, 'id', $wpl_field_id);
                
                $mls_field_array_update = array(
                    'wpl_field_id'=>$wpl_field_id,
                    'custom1'=>$mls_field_custom1
                );
                wpl_db::update('wpl_addon_mls_mappings', $mls_field_array_update, 'field_id', $mls_field_id);
            }
            else
            {
                $wpl_field_id = wpl_flex::create_default_dbst($wpl_field_id, $wpl_field_table_searchmod);
                wpl_flex::run_dbst_type_queries($wpl_field_id, $wpl_field_type, $wpl_field_kind, 'add');
                
                $wpl_field_array_update = array(
                    'name'=>$wpl_field_name,
                    'options'=>$wpl_field_options,
                    'category'=>$wpl_field_category,
                    'type'=>$wpl_field_type
                );
                wpl_db::update('wpl_dbst', $wpl_field_array_update, 'id', $wpl_field_id);
                
                $mls_field_array_update = array(
                    'wpl_field_id'=>$wpl_field_id,
                    'custom1'=>$mls_field_custom1
                );
                wpl_db::update('wpl_addon_mls_mappings', $mls_field_array_update, 'field_id', $mls_field_id);
            }
        }
        
    	return true;
    }
    
    /**
     * @author Matthew <matthew@realtyna.com>
     * @param array $mls_server
     * @param string $field_lookupName
     * @param string/int $field_id
     * @param int $mls_class_id
     * @return type
     */
    public function add_mls_field_values($mls_server, $field_lookupName, $field_id, $mls_class_id)
    {
        if(empty($field_lookupName)) return;
                
        $field_values = $this->rets->GetLookupValues($mls_server->resource, $field_lookupName);
        if(!is_array($field_values) or empty($field_values)) $field_values = array();

        $values_str = json_encode($field_values);

        $query = "UPDATE `#__wpl_addon_mls_mappings` SET `mls_field_values`='".wpl_db::escape($values_str)."' WHERE `field_id`='".$field_id."' AND `mls_server_id`='".$this->mls_server_id."' AND `mls_class_id`='".$mls_class_id."' ";
        wpl_db::q($query);
    }
    
    /**
     * Create a defult query and generate relevant Status Query!
     * @author Matthew <matthew@realtyna.com>
     * @return echo json
     */
    public function create_default_query($mls_server_id, $mls_class_id, $mls_class_name)
    {
        if(!$mls_class_id) return;
        
        $query = "SELECT `id` FROM `#__wpl_addon_mls_queries` WHERE mls_class_id = '$mls_class_id'";
        $exist_mls_class = wpl_db::select($query, 'loadResult');
        
        if($exist_mls_class) return json_encode(array('success'=>0, 'message'=>__('This Category is already existed!', 'wpl')));

        $query = $this->generate_mls_class_query($mls_class_id);
        $insert_query = "INSERT INTO `#__wpl_addon_mls_queries` (`mls_server_id`,`mls_class_id`,`mls_class_name`,`default_user_id`,`images`,`enabled`,`last_sync_date`,`query`,`limit`,`import_limit`) VALUES ('$mls_server_id','$mls_class_id','$mls_class_name','1','-1','1','2015-01-01','$query',10,10)";
        $id = wpl_db::q($insert_query, 'insert');
        
        if(!empty($id) and $id != 0) return json_encode(array('success'=>1, 'message'=>__('The Query is added successfully!', 'wpl')));
        else return json_encode(array('success'=>0, 'message'=>__('An error is occured! Try again.', 'wpl')));
    }
    
    /**
     * Generate a default query/criteria for created MLS query
     * @author Matthew <matthew@realtyna.com>
     * @param int $mls_class_id
     * @return string
     */
    private function generate_mls_class_query($mls_class_id)
    {
        if(empty($mls_class_id)) return;
        
        $query = "SELECT `field_id`,`mls_field_values` FROM `#__wpl_addon_mls_mappings` WHERE `mls_class_id`='$mls_class_id' AND `field_name` LIKE '%status%' AND `mls_field_searchable`=1";
        
        $result = wpl_db::select($query, 'loadAssoc');
        
        if(!empty($result))
        {
            $values = json_decode($result['mls_field_values'], true);
            
            $status_query = '';
            $active_values = array('a','act','active');
            
            foreach($values as $value)
            {
                if(in_array(strtolower($value['LongValue']), $active_values))
                {
                    $status_query = '(';
                    $status_query .= $result['field_id'].'=|'.$value['Value'];
                    $status_query .= '),';
                    break;
                }
            }
            $status_query = rtrim($status_query, ',');
            $main_query = '('.$status_query.')';
            
            return $main_query;
        }
        else return;
    }
}