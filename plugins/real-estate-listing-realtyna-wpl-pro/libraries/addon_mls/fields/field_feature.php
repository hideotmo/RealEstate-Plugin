<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** MLS field mapper **/
class wpl_mls_feature_map extends wpl_addon_mls_mapper
{
	/** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
		$wpl_value=0;
		
		//Boolean features
		if(strtolower(trim($mls_value)) == 'n' or strtolower(trim($mls_value)) == 'no' or empty($mls_value)) return array('value'=>$wpl_value);
		
		else
		{
			$wpl_value=1;			
			if(strtolower(trim($mls_value)) == 'y' or strtolower(trim($mls_value)) == 'yes' or $mls_value == 1) return array('value'=>$wpl_value);
		
			else
			{
				$select_options = json_decode($wpl_field['options'], true);
				$mls_array = wpl_addon_mls_mapper::toArray($mls_value);
				$options_key = array();	
				
				foreach($mls_array as $mls_item)
				{
					$max_id = 0;
					$exist_item=0;
			
					foreach($select_options['values'] as $item)
    		    	{
        		    	if(strtolower(trim($item['value'])) == strtolower(trim($mls_item))) 
						{
							$options_key[] = $item['key'];
							$exist_item = 1;
						}
						
    	    	    	$max_id = max($max_id, $item['key'])+1;
			        }
			
			        /** Add the option into field options **/
    			    if(!$exist_item)
        			{
		    	        $select_options['values'][$max_id] = array('value'=>ucfirst(strtolower($mls_item)), 'key'=>$max_id, 'enabled'=>1);
	    		        wpl_flex::update('wpl_dbst', $wpl_field['id'], 'options', json_encode($select_options));
        	    
		    	        $options_key[] = $max_id;
    		    	}
				}
			}
			$options_key=','.implode(',',$options_key).',';
		
			return array('value'=>array($wpl_value,$options_key),'table_column'=>array($wpl_field['table_column'],$wpl_field['table_column'].'_options'));
		}
	}
}