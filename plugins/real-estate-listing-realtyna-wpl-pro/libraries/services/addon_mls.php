<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_mls');

/**
 * MLS service
 * @author Howard <howard@realtyna.com>
 * @date 12/21/2013
 * @package MLS Add-on
 */
class wpl_service_addon_mls
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
    public function run()
    {
		if(wpl_global::get_setting('mls_auto_sync') or wpl_request::getVar('rets_cron_job') == 1)
		{
			if(wpl_request::getVar('rets_cron_job_type') == 'offline')
				$this->run_offline();
	    	else
	    		$this->run_online();	
		}
		elseif(wpl_request::getVar('rets_import_cron_job') == 1)
			$this->import_data();
		elseif(wpl_request::getVar('rets_import_images_cron_job') == 1)
			$this->import_images();
    }

    /**
     * Online RETS import process
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run_online()
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_queries` WHERE (`last_sync_date`='0000-00-00' OR DATE_ADD(`last_sync_date`, INTERVAL `sync_period` DAY)<'".date("Y-m-d H:i:s")."') AND `enabled`>='1'";
		$mls_queries = wpl_db::select($query);
		$rets_objects = array();
		$connection = 0;
        
		foreach($mls_queries as $mls_query)
		{
			/** load rets object **/
			if($rets_objects[$mls_query->mls_server_id]) $wplrets = $rets_objects[$mls_query->mls_server_id];
			else $wplrets = new wpl_addon_mls($mls_query->mls_server_id);
			
			if(trim($wplrets->mls_server_data->mls_unique_field) == '') continue;
			
			/** set to rets objects **/
			$rets_objects[$mls_query->mls_server_id] = $wplrets;
			
			/** connect **/
			if(!$connection) $connection = $wplrets->connect();
			
			if(!empty($mls_query->selects)) $options = array('Count'=>1, 'Offset'=>$mls_query->offset, 'Select'=>$mls_query->selects);
            else $options = array('Count'=>1, 'Offset'=>$mls_query->offset);
			
			/** set query **/
			$mls_query_string = $mls_query->query;
			$date1 = ($mls_query->last_sync_date == '0000-00-00' ? date('Y-m-01') : $mls_query->last_sync_date);
			$date2 = date('Y-m-d', strtotime('+'.$mls_query->sync_period.' day', strtotime($date1)));
			
			if(strstr($mls_query_string, 'dateT') != '')
			{
				$mls_query_string = str_replace('dateT', $date1.'T00:00:00-'.$date2.'T', $mls_query_string);
				$mls_query_string = str_replace($date2.'T00:00:00+', $date2.'T00:00:00', $mls_query_string);
			}
			else
				$mls_query_string = str_replace('date+', $date1.'-'.$date2, $mls_query_string);

			$search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $mls_query->mls_class_id, $mls_query_string, $options);
			$count = $wplrets->rets->TotalRecordsFound();
			
			if(!$count and wpl_request::getVar('rets_cron_job') != 1) continue;
			
			$results = array();
			while($row = $wplrets->rets->FetchRow($search)) $results[$row[$wplrets->mls_server_data->mls_unique_field]] = $row;
			$wplrets->rets->FreeResult($search);

			/** map data **/
			$mapped = $wplrets->map($results, $mls_query->id);
			
			/** import properties **/
			$pids = $wplrets->import_mapped_data($mapped, $mls_query->id, '', $mls_query_string);
			
			/** download images **/
			if(trim($mls_query->images)) $wplrets->import_properties_images($pids, $mls_query->mls_server_id, $mls_query->images, false);
			
			/** finalizing properties **/
			foreach($pids as $pid) $wplrets->finalize($pid);
			
			/** update **/
			if($count <= $mls_query->limit+$mls_query->offset) self::mls_query_is_done($mls_query->id, $date2);
			else self::update_query($mls_query->id, ($mls_query->offset+$mls_query->limit));
			
			//Remove Expired Listings
			$mls_query_fetch_listings = str_replace('date+', '2000-01-01+', $mls_query->query);

			$offset_checked = 0;

			$result = self::fetch_class_listings($wplrets, $mls_query->mls_class_id, $mls_query_fetch_listings, $offset_checked);

			if(self::all_properties_retrieved(count($result), 100) and $offset_checked)
			{
				$division_factor = count($result);
				while(self::all_properties_retrieved(count($result), $division_factor))
				{
					$offset_delete = count($result)+1;
					$second_array = self::fetch_class_listings($wplrets, $mls_query->mls_class_id, $mls_query_fetch_listings, $offset_checked, $offset_delete);
					$result = array_merge($result, $second_array);
				}
			}

			$wpl_unique_field = wpl_addon_mls::get_wpl_unique_field($mls_query->id,$mls_query->mls_server_id);

			if(count($result) > 0)
			{
				$where = "WHERE `mls_query_id`='".$mls_query->id."' AND `".$wpl_unique_field['table_column']."` NOT IN('".implode("','", $result)."');";
				$query_delete = "SELECT `id` from `#__wpl_properties` ".$where;
				$expired_properties = wpl_db::select($query_delete);

				foreach($expired_properties as $expired_property) wpl_property::purge($expired_property->id);
			}
		}
		
		/** Disconnect **/
		if(isset($wplrets)) $wplrets->rets->Disconnect();
        
        /** Delete expired temporary directories **/
        wpl_global::delete_expired_tmp();
        
        /** cPanel cronjob **/
        if(wpl_request::getVar('rets_cron_job') == 1) exit;
	}
	
	/**
	 * Offline RETS import process
	 * @author Steve A. <steve@realtyna.com>
	 * @return void
	 */
	public function run_offline()
	{
		$ids = wpl_request::getVar('rets_query_ids');

		$query = "SELECT * FROM `#__wpl_addon_mls_queries` WHERE `enabled`>='1'";
		if(is_numeric(str_replace(',', '', $ids))) $query .= " AND `id` IN ({$ids})";

		$mls_queries = wpl_db::select($query);
		$rets_objects = array();
		$connection = 0;
        
		foreach($mls_queries as $mls_query)
		{
			$ids = wpl_db::select("SELECT `unique_value` FROM `#__wpl_addon_mls_data` WHERE `mls_query_id` = '{$mls_query->id}'", 'loadColumn');

			/** load rets object **/
			if($rets_objects[$mls_query->mls_server_id]) $wplrets = $rets_objects[$mls_query->mls_server_id];
			else $wplrets = new wpl_addon_mls($mls_query->mls_server_id);
			
			if(trim($wplrets->mls_server_data->mls_unique_field) == '') continue;
			
			/** set to rets objects **/
			$rets_objects[$mls_query->mls_server_id] = $wplrets;
			
			/** connect **/
			if(!$connection) $connection = $wplrets->connect();
			
            if(!empty($mls_query->selects)) $options = array('Count'=>1, 'Offset'=>$mls_query->offset, 'Select'=>$mls_query->selects);
            else $options = array('Count'=>1, 'Offset'=>$mls_query->offset);
            
			$today = date('Y-m-d H:i:s');
			
			/** set query **/
			$mls_query_string = $mls_query->query;
			$date = ($mls_query->last_sync_date == '0000-00-00' ? date('1950-01-01') : $mls_query->last_sync_date);
            $date2 = date('Y-m-d', strtotime('+'.$mls_query->sync_period.' day', strtotime($date)));
			
            if(stristr($mls_query_string, 'date+s'))
            {
                if($date2 > $today) continue;
                
                $mls_query_string = str_replace('date+s', $date.'-'.$date2, $mls_query_string);
                $today = date('Y-m-d H:i:s', strtotime($date2));
            }
            else $mls_query_string = str_replace('date+', $date.'+', $mls_query_string);

			$search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $mls_query->mls_class_id, $mls_query_string, $options);
			$count = $wplrets->rets->TotalRecordsFound();
			
			if($wplrets->rets->Error() and false === stristr($mls_query->query, 'date+s')) print_r($wplrets->rets->Error());
			if(!$count and false === stristr($mls_query->query, 'date+s'))
			{
				self::mls_query_is_done($mls_query->id, $today);
				continue;
			}

			$num = 0;
			while($row = $wplrets->rets->FetchRow($search)) 
			{
				$unique_value = $row[$wplrets->mls_server_data->mls_unique_field];
				$data = base64_encode(json_encode($row));
				
				if(in_array($unique_value, $ids))
					wpl_db::q("UPDATE `#__wpl_addon_mls_data` SET `content` = '{$data}', `date` = '{$today}', `imported` = 0 WHERE `unique_value` = '{$unique_value}' AND `mls_query_id` = '{$mls_query->id}'");
				else
					wpl_db::q("INSERT INTO `#__wpl_addon_mls_data` (`mls_query_id`, `unique_value`, `content`, `date`) VALUES ('{$mls_query->id}', '{$unique_value}', '{$data}', '{$today}')");

				$ids[] = $unique_value;
				$num++;
			}
			$wplrets->rets->FreeResult($search);
			
			/** update **/
			if($count <= $num) self::mls_query_is_done($mls_query->id, $today);
			else self::update_query($mls_query->id, ($mls_query->offset+$num));
			
			// Remove Expired Listings
			$offset_checked = 0;
            
			if(stristr($mls_query->query, 'date+s')) $mls_query_fetch_listings = str_replace('date+s', '1950-01-01+', $mls_query->query);
			else $mls_query_fetch_listings = str_replace('date+', '1950-01-01+', $mls_query->query);
            
			$result = self::fetch_class_listings($wplrets, $mls_query->mls_class_id, $mls_query_fetch_listings, $offset_checked);
			$wpl_unique_field = wpl_addon_mls::get_wpl_unique_field($mls_query->id,$mls_query->mls_server_id);

			if(count($result) < $count)
			{
				$offset = 0;
				while(count($result) > $offset and count($result) < $count)
				{
					$offset = count($result);
					$second_array = self::fetch_class_listings($wplrets, $mls_query->mls_class_id, $mls_query_fetch_listings, 1, $offset);
					$result = array_merge($result, $second_array);
				}
			}

			if(count($result) > 0)
			{
				$result = implode("','", $result);

				$where = "WHERE `mls_query_id`='".$mls_query->id."' AND `".$wpl_unique_field['table_column']."` NOT IN('{$result}');";
				$query_delete = "SELECT `id` from `#__wpl_properties` ".$where;
				$expired_properties = wpl_db::select($query_delete);
				
				foreach($expired_properties as $expired_property) wpl_property::purge($expired_property->id);
				wpl_db::delete('wpl_addon_mls_data', '', "AND `mls_query_id` = '{$mls_query->id}' AND `unique_value` NOT IN('{$result}')");
			}
		}
		
		/** Disconnect **/
		if(isset($wplrets)) $wplrets->rets->Disconnect();
        
        /** Delete expired temporary directories **/
        wpl_global::delete_expired_tmp();
        
        /** cPanel cronjob **/
        if(wpl_request::getVar('rets_cron_job') == 1) exit;
	}

    /**
     * Sets an MLS query to done.
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $query_id
     * @param date $last_sync_date
     */
	public static function mls_query_is_done($query_id, $last_sync_date)
	{
		$query = "UPDATE `#__wpl_addon_mls_queries` SET `last_sync_date`='".$last_sync_date."', `offset`='0' WHERE `id`='$query_id'";
		wpl_db::q($query);
	}
	
    /**
     * Updates MLS query
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $query_id
     * @param int $offset
     */
	public static function update_query($query_id, $offset)
	{
		$query = "UPDATE `#__wpl_addon_mls_queries` SET `offset`='".$offset."' WHERE `id`='".$query_id."'";
		wpl_db::q($query);
	}
	
	/**
     * Fetch all listing for each class based on offset MLS query
     * @author Natan <natan@realtyna.com>
     */
	public function fetch_class_listings($wplrets, $class_id, $query, $offset_support=1,$offset=1)
	{
		$results = array();
		
		if($offset_support) $search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $class_id,$query, array("Select" => $wplrets->mls_server_data->mls_unique_field, "Offset" => $offset));
		else $search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $class_id,$query, array("Select" => $wplrets->mls_server_data->mls_unique_field));
		
		while($row = $wplrets->rets->FetchRow($search)) $results[] = $row[$wplrets->mls_server_data->mls_unique_field];

		/* Checking if the Result count less than Total count, then fetching all Result */
		$count_all = $wplrets->rets->TotalRecordsFound();
		$count_res = count($results);
		$limit = $count_res;

		if(!empty($results) && $count_all > $count_res)
		{
			$round = round($count_all / $count_res);
			for($i=0; $i <= $round; $i++)
			{
				if($count_all > count($results))
				{
					$offset += $count_res;
					if(!$offset_support) $search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $class_id,$query, array("Select" => $wplrets->mls_server_data->mls_unique_field, "Offset" => $offset, 'Limit'=>$limit));
					else $search = $wplrets->rets->SearchQuery($wplrets->mls_server_data->resource, $class_id,$query, array("Select" => $wplrets->mls_server_data->mls_unique_field, 'Limit'=>$limit));

					while($row = $wplrets->rets->FetchRow($search)) $results[] = $row[$wplrets->mls_server_data->mls_unique_field];
				}
				else continue;
			}
			$results = array_unique($results);
		}

		$wplrets->rets->FreeResult($search);

		return $results;
	}
	
	/**
     * checking to grab all listing
     * @author Natan <natan@realtyna.com>
     */
	public function all_properties_retrieved($total_properties, $division_factor)
	{
		$fraction = $total_properties/$division_factor;
		return is_int($fraction);
	}

	/**
	 * Import properties from offline data
	 * @author Steve A. <steve@realtyna.com>
	 * @return void
	 */
	public function import_data()
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_queries` WHERE `enabled`>='1'";
		$mls_queries = wpl_db::select($query);
		$rets_objects = array();
		$connection = 0;
        
		foreach($mls_queries as $mls_query)
		{
			$query = "SELECT * FROM `#__wpl_addon_mls_data` WHERE `mls_query_id` = '{$mls_query->id}' AND `imported` = 0 ORDER BY `date` LIMIT {$mls_query->import_limit}";
			$mls_data = wpl_db::select($query);
			if(!$mls_data) continue;
			$results = array();
			$ids = array();

			foreach($mls_data as $data)
			{
				$results[$data->unique_value] = (array)json_decode(base64_decode($data->content));
				$ids[] = $data->id;
			}
			
			/** load rets object **/
			if($rets_objects[$mls_query->mls_server_id]) $wplrets = $rets_objects[$mls_query->mls_server_id];
			else $wplrets = new wpl_addon_mls($mls_query->mls_server_id);

			if(trim($wplrets->mls_server_data->mls_unique_field) == '') continue;
			
			/** set to rets objects **/
			$rets_objects[$mls_query->mls_server_id] = $wplrets;

			/** connect **/
			if(!$connection) $connection = $wplrets->connect();

			/** map data **/
			$mapped = $wplrets->map($results, $mls_query->id);
			
			/** import properties **/
			$pids = $wplrets->import_mapped_data($mapped, $mls_query->id);
			
			/** download images **/
			if(trim($mls_query->images)) $wplrets->import_properties_images($pids, $mls_query->mls_server_id, $mls_query->images, false);
			
			/** finalizing properties **/
			foreach($pids as $pid) $wplrets->finalize($pid);
		
			/** update imported field **/
			wpl_db::q("UPDATE `#__wpl_addon_mls_data` SET `imported` = '1' WHERE `id` IN ('".implode("','", $ids)."')");
		}

		if(wpl_request::getVar('rets_import_cron_job') == 1) exit;
	}

	/**
	 * Download property images only
	 * @author Steve A. <steve@realtyna.com>
	 * @return void
	 */
	public function import_images()
	{
		$query = "SELECT * FROM `#__wpl_addon_mls_queries` WHERE `enabled`>='1'";
		$mls_queries = wpl_db::select($query);
		$rets_objects = array();
		$connection = 0;
        
		foreach($mls_queries as $mls_query)
		{
			$query = "SELECT `id` FROM `#__wpl_properties` WHERE `pic_numb` = '0' AND `mls_query_id` = '{$mls_query->id}' LIMIT {$mls_query->import_limit}";
			$pids = wpl_db::select($query, 'loadColumn');

			/** load rets object **/
			if($rets_objects[$mls_query->mls_server_id]) $wplrets = $rets_objects[$mls_query->mls_server_id];
			else $wplrets = new wpl_addon_mls($mls_query->mls_server_id);
			
			/** set to rets objects **/
			$rets_objects[$mls_query->mls_server_id] = $wplrets;

			/** connect **/
			if(!$connection) $connection = $wplrets->connect();

			/** download images **/
			if(trim($mls_query->images)) $wplrets->import_properties_images($pids, $mls_query->mls_server_id, $mls_query->images, false);

			/** finalizing properties **/
			foreach($pids as $pid) $mls->finalize($pid);
		}

		if(wpl_request::getVar('rets_import_images_cron_job') == 1) exit;
	}
}