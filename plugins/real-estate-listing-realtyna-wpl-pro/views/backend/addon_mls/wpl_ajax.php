<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_mls');
_wpl_import('libraries.flex');
_wpl_import('libraries.property');

class wpl_addon_mls_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.addon_mls.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		$function = wpl_request::getVar('wpl_function');
		
		/** server functions **/
		if($function == 'generate_modify_page') $this->generate_modify_page();
		elseif($function == 'save_mls') $this->save();
		elseif($function == 'remove_mls_server') $this->remove_mls_server();
		elseif($function == 'test_connection') $this->test_connection();
		elseif($function == 'generate_params_page') $this->generate_params_page();
		/** mapping functions **/
		elseif($function == 'generate_classes') $this->generate_classes();
		elseif($function == 'generate_mapping_page') $this->generate_mapping_page();
		elseif($function == 'save_mapping') $this->save_mapping();
		elseif($function == 'auto_create') $this->auto_create();
		/** query functions **/
		elseif($function == 'generate_modify_page_query') $this->generate_modify_page_query();
		elseif($function == 'save_query') $this->save();
		elseif($function == 'remove_mls_query') $this->remove_mls_query();
		elseif($function == 'generate_params_page_query') $this->generate_params_page_query();
		elseif($function == 'query_enabled') $this->query_enabled();
		elseif($function == 'generate_query_wizard') $this->generate_query_wizard();
		/** Import functions **/
		elseif($function == 'init_import') $this->init_import();
		elseif($function == 'import') $this->import();
        elseif($function == 'import_mapping') $this->import_mapping();
		elseif($function == 'export_mapping') $this->export_mapping();
		elseif($function == 'create_default_query') $this->create_default_query();
	}
	
	private function generate_modify_page()
	{
		$this->id = wpl_request::getVar('id', '0');
		$this->mls_server = wpl_addon_mls::get_servers($this->id);
		
		/** include the layout **/
		parent::render($this->tpl_path, 'internal_modify_mls');
		exit;
	}
	
	public function generate_advanced_tab()
	{
		/** include the layout **/
		parent::render($this->tpl_path, 'internal_setting_advanced');
		exit;
	}
	
	private function save()
	{
		$id = wpl_request::getVar('id', '0');
		$table = wpl_request::getVar('table', 'wpl_addon_mls');
		$post = wpl_request::get('POST');
        $columns = wpl_db::columns($table);
		
		$q1 = '';
		$q2 = '';
		
		foreach($post as $key=>$value)
		{
			if(substr($key, 0, 4) != 'fld_') continue;
			$table_field = substr($key, 4);
            
            /** Not valid column **/
			if(!in_array($table_field, $columns)) continue;
            
			if($id)
			{
				$q1 .= "`$table_field`='$value',";
			}
			else
			{
				$q1 .= "`$table_field`,";
				$q2 .= "'$value',";
			}
		}
		
		$q1 = trim($q1, ', ');
		$q2 = trim($q2, ', ');
		
		if($id)
        {
            $query = "UPDATE `#__".$table."` SET ".$q1." WHERE `id`='$id'";
            
            /** run save query **/
            wpl_db::q($query);
        }
		else
        {
            $query = "INSERT INTO `#__".$table."` (".$q1.") VALUES (".$q2.")";
            
            /** run save query **/
            $id = wpl_db::q($query, 'insert');
        }
        
        /** trigger event **/
		wpl_global::event_handler('mls_server_saved', array('id'=>$id));
		
		echo json_encode(array('success'=>1, 'message'=>''));
		exit;
	}
	
	private function remove_mls_server()
	{
		$id = wpl_request::getVar('id');
		
		/** run delete function **/
		wpl_addon_mls::remove_server($id);
		
		echo json_encode(array('success'=>1, 'message'=>''));
		exit;
	}
	
	private function test_connection()
	{
		$id = wpl_request::getVar('id');
		
		$wplrets = new wpl_addon_mls($id);
		$mls_server = $wplrets->get_servers($id);
		
		$wplrets->login_url = $mls_server->url;
		$wplrets->username = $mls_server->username;
		$wplrets->password = $mls_server->password;
		$wplrets->rets_version = $mls_server->rets_version;
		if ($wplrets->agent_username == '')
		{
			$wplrets->agent_username = 'PHRETS/1.0.1';	
		}
		else
		{
			$wplrets->agent_username = $mls_server->agent_username;
			$wplrets->agent_password = $mls_server->agent_password;
		}
		/** test connection **/
		$result = $wplrets->test_connection($id);
		
		$success = $result ? 1 : 0;
		$message = $result ? __('Successfully connected and imported the fields!', 'wpl') : __('Something is wrong! Please check your username and password.', 'wpl');
		
		/** set connection status in db **/
		wpl_db::set('wpl_addon_mls', $id, 'connection_status', $success);
		
		/** import basic data **/
		if($success) $wplrets->import_basic_data($id);
		
		echo json_encode(array('success'=>$success, 'message'=>$message));
		exit;
	}
	
	private function generate_params_page()
	{
		$id = wpl_request::getVar('id');
		
		$params = array('element_class'=>'wpl_params_cnt', 'js_function'=>'wpl_save_params', 'id'=>$id, 'table'=>'wpl_addon_mls', 'html_path_message'=>'dont_show', 'close_fancybox'=>true);
		wpl_global::import_activity('params:default', '', $params);
		exit;
	}
	
	private function generate_classes()
	{
		$id = wpl_request::getVar('id');
		$classes = wpl_addon_mls::get_classes($id);
		
		$html = '<select name="mls_class_id" id="mls_class_id" onchange="wpl_mls_class_select(this.value);">';
		$html .= '<option value="">----</option>';
		
		foreach($classes as $key=>$class)
		{
			$class_name = $class ? $class : $key;
			$html .= '<option value="'.$key.'">'.$class_name.'</option>';
		}
		
		$html .= '</select>';
		
		echo json_encode(array('success'=>1, 'html'=>$html));
		exit;
	}
	
	private function generate_mapping_page()
	{
		$this->id = wpl_request::getVar('id', '0');
		$this->field_data = wpl_addon_mls::get_field($this->id);
		$this->mls_server = wpl_addon_mls::get_servers($this->field_data->mls_server_id);
		$this->wpl_categories = wpl_flex::get_categories('', '', "AND `enabled`>='1' AND `kind`='0' AND `pshow`='1'");
		$this->location_settings = wpl_global::get_settings('3'); # location settings
		$this->mapped_fields = wpl_addon_mls::get_wpl_selected_fields($this->field_data->mls_server_id, $this->field_data->mls_class_id);
		
		
		/** include the layout **/
		parent::render($this->tpl_path, 'internal_mapping');
		exit;
	}
	
	private function save_mapping()
	{
		$wpl_field_id = wpl_request::getVar('wpl_field_id');
		$id = wpl_request::getVar('id');
		$custom1 = wpl_request::getVar('custom1');
		
		// save mls field mapping to WPL
		wpl_addon_mls::save_mapping($id, $wpl_field_id, $custom1);
		
        // Get names
        $data = wpl_flex::get_names($wpl_field_id);
        
		echo json_encode(array('success'=>1, 'message'=>'', 'data'=>$data));
		exit;
	}
	
	private function auto_create()
	{
        $wpl_field_type = wpl_request::getVar('wpl_field_type', NULL);
        
		$mls_field_ids = trim(wpl_request::getVar('mls_field_ids', ''), ', ');
		$mls_field_ids = explode(',', $mls_field_ids);
		
		foreach($mls_field_ids as $mls_field_id)
		{
			/** create field **/
			wpl_addon_mls::create_field($mls_field_id, $wpl_field_type);
		}
		
		echo json_encode(array('success'=>1, 'message'=>''));
		exit;
	}
	
	private function generate_modify_page_query()
	{
		$this->id = wpl_request::getVar('id', '0');
		$this->mls_query = wpl_addon_mls::get_mls_queries($this->id);
		$this->server_id = ($this->mls_query ? $this->mls_query->mls_server_id : 0);
		$this->mls_class_id = ($this->mls_query ? $this->mls_query->mls_class_id : 0);
		$this->mls_servers = wpl_addon_mls::get_servers();
		$this->classes = wpl_addon_mls::get_classes($this->server_id);
		$this->users = wpl_users::get_wpl_users();
		
		/** include the layout **/
		parent::render($this->tpl_path, 'internal_modify_query');
		exit;
	}
	
	private function generate_params_page_query()
	{
		$id = wpl_request::getVar('id');
		
		$params = array('element_class'=>'wpl_params_cnt', 'js_function'=>'wpl_save_params', 'id'=>$id, 'table'=>'wpl_addon_mls_queries', 'html_path_message'=>'dont_show', 'close_fancybox'=>true);
		wpl_global::import_activity('params:default', '', $params);
		exit;
	}
	
	private function remove_mls_query()
	{
		$id = wpl_request::getVar('id');
		
		/** run delete function **/
		wpl_db::delete('wpl_addon_mls_queries', $id);
		
        /** trigger event **/
		wpl_global::event_handler('mls_query_removed', array('id'=>$id));
        
		echo json_encode(array('success'=>1, 'message'=>''));
		exit;
	}
	
	private function query_enabled()
	{
		$id = wpl_request::getVar('id');
		$enabled = wpl_request::getVar('enabled', 1);
		
		/** run delete function **/
		wpl_db::set('wpl_addon_mls_queries', $id, 'enabled', $enabled);
		
		echo json_encode(array('success'=>1, 'message'=>''));
		exit;
	}
	
	private function generate_query_wizard()
	{
		$mls_class_id = wpl_request::getVar('mls_class_id');
		$mls_server_id = wpl_request::getVar('mls_server_id');
		$mls_query_id = wpl_request::getVar('mls_query_id');
		
		$mls_query = wpl_addon_mls::get_mls_queries($mls_query_id);
		$fields = wpl_addon_mls::get_fields('', "AND `mls_server_id`='$mls_server_id' AND `mls_class_id`='$mls_class_id'");
		
		/** creating query parameters array **/
		$query_str = trim($mls_query->query,' ');
		$query_str = substr($mls_query->query, 2, -2);
		$query_arr = explode('),(', $query_str);
		
		$query_options = array();
		foreach($query_arr as $query_value)
		{
			$query_arr2 = explode('=', $query_value);
			
			$operator = 1;
			if(isset($query_arr2[1]) and substr($query_arr2[1], -1, 1) == '+') $operator = 2;
			elseif(isset($query_arr2[1]) and substr($query_arr2[1], -1, 1) == '-') $operator = 3;
			elseif(isset($query_arr2[1]) and substr($query_arr2[1], -1, 1) == '*') $operator = 4;
			
			$query_options[$query_arr2[0]] = array('value'=>(isset($query_arr2[1]) ? trim($query_arr2[1], '+*- ') : ''), 'operator'=>$operator);
		}
		
		$html = '';
		foreach($fields as $field)
		{
			$html .= '<div class="fanc-row" id="'.$field->field_id.'">';
			$html .= '<label for="qw'.$field->field_id.'" title="'.$field->field_sample_data.'">'.$field->field_name.' :</label>';
			
			if(strtoupper(substr($field->field_id, -2, 2)) == 'YN' or strtoupper(substr($field->field_name, -2, 2)) == 'YN')
			{
				$html .= '<select class="qw_operator_select" id="qw_operator_select'.$field->field_id.'">';
				$html .= '<option value="1">=</option>';
				$html .= '</select>';
				
				$html .= '&nbsp;<select id="qw'.$field->field_id.'" name="qw'.$field->field_id.'" class="qw_value_select">';
				$html .= '<option value="">-----</option>';
				$html .= '<option value="Yes" '.($query_options[$field->field_id]['value'] == 'Yes' ? 'selected="selected"' : '').'>'.__('Yes', 'wpl').'</option>';
				$html .= '<option value="No" '.($query_options[$field->field_id]['value'] == 'No' ? 'selected="selected"' : '').'>'.__('No', 'wpl').'</option>';
				$html .= '</select>';
			}
			else
			{
				$html .= '<select class="qw_operator_select" id="qw_operator_select'.$field->field_id.'">';
				$html .= '<option value="1" '.((isset($query_options[$field->field_id]['operator']) and $query_options[$field->field_id]['operator'] == '1') ? 'selected="selected"' : '').'>=</option>';
				
				if(strtolower($field->field_type) != 'character')
				{
					$html .= '<option value="2" '.((isset($query_options[$field->field_id]['operator']) and $query_options[$field->field_id]['operator'] == '2') ? 'selected="selected"' : '').'>>=</option>';
					$html .= '<option value="3" '.((isset($query_options[$field->field_id]['operator']) and $query_options[$field->field_id]['operator'] == '3') ? 'selected="selected"' : '').'><=</option>';
				}
				
				if(strtolower($field->field_type) == 'character')
				{
					$html .= '<option value="4" '.((isset($query_options[$field->field_id]['operator']) and $query_options[$field->field_id]['operator'] == '4') ? 'selected="selected"' : '').'>'.__('Contain', 'wpl').'</option>';
				}
				
				$html .= '</select>';
				$html .= '&nbsp;<input type="text" id="qw'.$field->field_id.'" name="qw'.$field->field_id.'" class="qw_value_text" value="'.(isset($query_options[$field->field_id]['value']) ? $query_options[$field->field_id]['value'] : '').'" />';
			}
			
			$html .= '</div>';
		}
		
		echo json_encode(array('success'=>1, 'html'=>$html));
		exit;
	}
	
	private function init_import()
	{
		$id = wpl_request::getVar('id');
		$mls_query = wpl_addon_mls::get_mls_queries($id);
		
		$wplrets = new wpl_addon_mls($mls_query->mls_server_id);
		$mls_server = $wplrets->get_servers($mls_query->mls_server_id);
		
		$wplrets->rets_version = $mls_server->rets_version;
		
		/** connect **/
		$connection = $wplrets->connect();
		if(!$connection)
		{
			echo json_encode(array('success'=>0, 'message'=>__('MLS connection failed!', 'wpl')));
			exit;
		}
		
		if(trim($mls_server->mls_unique_field) == '')
		{
			echo json_encode(array('success'=>0, 'message'=>__('MLS unique field must be specified!', 'wpl')));
			exit;
		}
		
		$options = array('Count'=>2, 'Select'=>"{$mls_server->mls_unique_field}");
		$search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $mls_query->mls_class_id, $mls_query->query, $options);
		
		$total = $wplrets->rets->TotalRecordsFound();
		$message = __('Successfully connected.', 'wpl');
		
		/** Disconnect **/
		$wplrets->rets->Disconnect();
		
		echo json_encode(array('success'=>1, 'message'=>$message, 'data'=>array('total'=>$total)));
		exit;
	}
	
	private function import()
	{
		$id = wpl_request::getVar('id');
		$mls_query = wpl_addon_mls::get_mls_queries($id);
		
		$limit = wpl_request::getVar('limit', $mls_query->limit);
		$offset = wpl_request::getVar('offset', $mls_query->offset);
		$mappings = wpl_addon_mls::get_fields('', " AND `mls_server_id`='{$mls_query->mls_server_id}' AND `mls_class_id`='{$mls_query->mls_class_id}'");
		
		$wplrets = new wpl_addon_mls($mls_query->mls_server_id);
		$mls_server = $wplrets->get_servers($mls_query->mls_server_id);
		
		$wplrets->rets_version = $mls_server->rets_version;
		
		/** connect **/
		$connection = $wplrets->connect();
		
		$options = array('Count'=>1, 'Limit'=>$limit, 'Offset'=>$offset);
		$search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $mls_query->mls_class_id, $mls_query->query, $options);
		
		$results = array();
        $i = 1;
		while($row = $wplrets->rets->FetchRow($search))
        {
            if($i == 1 and !isset($row[$wplrets->mls_server_data->mls_unique_field]))
            {
                echo json_encode(array('success'=>0, 'message'=>__("MLS unique field does not exist on MLS server.", 'wpl')));
                exit;
            }
            
            $results[$row[$wplrets->mls_server_data->mls_unique_field]] = $row;
            $i++;
        }
        
		$wplrets->rets->FreeResult($search);
		
		/** map data **/
		$mapped = $wplrets->map($results, $id);
		
		/** import properties **/
		$pids = $wplrets->import_mapped_data($mapped, $id);
		
		/** download images **/
		$images = $wplrets->import_properties_images($pids, $mls_query->mls_server_id, $mls_query->images, false);
		
		/** finalizing properties **/
		foreach($pids as $pid) $wplrets->finalize($pid);
		
		/** Disconnect **/
		$wplrets->rets->Disconnect();
		
		$message = $limit.' '.__('Listings imported', 'wpl');
		echo json_encode(array('success'=>1, 'message'=>$message));
		exit;
	}
    
    /**
     * Import the MLS mapping per category.
     * @author: Matthew <matthew@realtyma.com>
     */
    private function import_mapping()
    {
		$file = wpl_request::getVar('wpl_import_mapping_file', '', 'FILES');
		$mls_class_id = wpl_request::getVar('mls_class_id', '');
        
		$tmp_directory = wpl_global::init_tmp_folder();
		$ext = strtolower(wpl_file::getExt($file['name']));
		$mapping_file = $tmp_directory.'mapping.'.$ext;
		
		$response = wpl_global::upload($file, $mapping_file, array('json', 'xml'), 20971520); #20MB
        
		if(trim($response['error']) != '')
		{
			echo json_encode($response);
			exit;
		}
        
		if(wpl_addon_mls::import_mapping($mapping_file))
		{
			$error = '';
        	$message = __('Mappings have been imported successfuly!', 'wpl');
		}
        else
        {
        	$error = '1';
        	$message = __('Cannot import mapping!', 'wpl');
        }

		echo json_encode(array('error'=>$error, 'message'=>$message));
		exit;
    }
    
    /**
     * Export the MLS mapping per category.
     * @author: Matthew <matthew@realtyma.com>
     */
    private function export_mapping()
    {
        $format = wpl_request::getVar('mls_mapping_export_format', 'json');
        $mls_class_id = wpl_request::getVar('mls_class_id', 'Listing');
        
        if(empty($mls_class_id)) return;
        
        $output = wpl_addon_mls::export_mapping($mls_class_id, $format);

    	if($format == 'json')
    	{
    		header('Content-disposition: attachment; filename=mapping_'.$mls_class_id.'.json');
			header('Content-type: application/json');	
    	}
    	elseif($format == 'xml')
    	{
    		header('Content-disposition: attachment; filename=mapping_'.$mls_class_id.'.xml');
			header('Content-type: application/xml');
    	}

    	echo $output;
		exit;
    }
    
    /**
     * Create a defult query and generate relevant Status Query!
     * @author Matthew <matthew@realtyna.com>
     * @return echo json
     */
    private function create_default_query()
    {
        $mls_server_id = wpl_request::getVar('mls_server_id', '');
        $mls_class_id = wpl_request::getVar('mls_class_id', '');
        $mls_class_name = wpl_request::getVar('mls_class_name', '');
        
        $addon_mls = new wpl_addon_mls();
        echo $addon_mls->create_default_query($mls_server_id, $mls_class_id, $mls_class_name);
    }
}