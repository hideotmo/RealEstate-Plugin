<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Advanced Portal Search Add-on Library
 * @author Howard <howard@realtyna.com>
 * @package Advanced Portal Search Add-on
 */
class wpl_addon_aps
{
    /**
     * Constructor method
     * @author Howard <howard@realtyna.com>
     */
    public function __construct()
    {
    }
    
    /**
     * Runs after finalizing a porperty
     * @author Howard <howard@realtyna.com>
     * @param array $params
     * @return int
     */
    public function finalize($params)
    {
        $property_id = $params[0];
        
        return wpl_db::q("UPDATE `#__wpl_properties` SET `geopoints`=Point(`googlemap_ln`, `googlemap_lt`) WHERE `id`='$property_id'", 'UPDATE');
    }
    
    /**
     * Converts string points to polygons array
     * @author Howard <howard@realtyna.com>
     * @param string $raw_points
     * @return array
     */
    public function toPolygons($raw_points)
    {
        $raw_polygons = explode(']', $raw_points);
        $points = array();
        
        $i = 0;
        foreach($raw_polygons as $raw_polygon)
        {
            $polygon_raw_points = explode(';', trim($raw_polygon, '[];, '));
            $polygon_points = array();
            $first_point = NULL;
            
            $j = 1;
            foreach($polygon_raw_points as $polygon_raw_point)
            {
                $latlng = explode(',', $polygon_raw_point);
                
                if(!isset($latlng[1])) continue;
                
                if($j === 1) $first_point = $latlng;
                $polygon_points[] = $latlng;
                $j++;
            }
            
            /** Close the Polygon with first Point **/
            if($first_point) $polygon_points[] = $first_point;
            
            if(count($polygon_points))
            {
                $points[$i] = $polygon_points;
                $i++;
            }
        }
        
        return $points;
    }

    /**
     * Get fields of suggestion search types
     * @author Matthew N. <matthew@realtyna.com>
     * @param  array $values
     * @return array of rendered fields
     */
    public function get_suggestion_search_fields($values)
    {
        $values = is_array($values) ? $values : array();

        $list_of_fields = array(
            'location1',
            'location2',
            'location3',
            'location4',
            'location5',
            'location6',
            'location7',
            'zip_name',
            'neighborhood',
            'nearby',
            'mls',
            'feature',
            'keyword',
            'address',
            'search_all'
        );

        $search_fields = array();
        foreach($list_of_fields as $field_name) $search_fields[$field_name] = array('index'=>$values[$field_name]['index'], 'options'=>array('enabled'=>$values[$field_name]['enabled'], 'placeholder'=>__($values[$field_name]['placeholder'], 'wpl')));

        return wpl_global::sort_array_by_key($search_fields, 'index', true);
    }
}