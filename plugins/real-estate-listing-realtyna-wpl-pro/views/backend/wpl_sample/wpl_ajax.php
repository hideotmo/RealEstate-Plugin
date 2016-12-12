<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.items');
_wpl_import('libraries.flex');

class wpl_wpl_sample_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.wpl_sample.tmpl';
	var $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		if($function == 'save_sample_data') self::save_sample_data();
	}
	
	private function save_sample_data()
	{
		$wpl_sample = wpl_request::getVar('wpl_sample');
		
		$count = $wpl_sample['count'] ? $wpl_sample['count'] : 50;
		$listing_type = $wpl_sample['listing_type'];
        $kind = isset($wpl_sample['kind']) ? $wpl_sample['kind'] : 0;
		$property_type = $wpl_sample['property_type'];
		$user_id = $wpl_sample['user_id'];
		$image_count = min($wpl_sample['image_count'], 10);
		$property_tag = $wpl_sample['property_tag'];
		
		$geo_center = $wpl_sample['geo_center'];
		$radius = $wpl_sample['radius'];
		$latlng_center = wpl_locations::get_LatLng($geo_center);
		$latitude = (float) $latlng_center[0];
		$longitude = (float) $latlng_center[1];
		
		$lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
		$lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
		$lat_min = $latitude - ($radius / 69);
		$lat_max = $latitude + ($radius / 69);

		$listing_type_data = (array) wpl_global::get_listings($listing_type);
		$property_type_data = (array) wpl_global::get_property_types($property_type);
		
		if($listing_type_data['parent'] == 1) $listing_type_parent_type = 'sale';
		elseif($listing_type_data['parent'] == 2) $listing_type_parent_type = 'rental';
		elseif($listing_type_data['parent'] == 3) $listing_type_parent_type = 'vacational';
		
		if($property_type_data['parent'] == 1) $property_type_parent_type = 'residential';
		elseif($property_type_data['parent'] == 2) $property_type_parent_type = 'commercial';
		elseif($property_type_data['parent'] == 3) $property_type_parent_type = 'land';
		
		$path_to_sample_images = WPL_ABSPATH. 'views' .DS. 'backend' .DS. 'wpl_sample' .DS. 'images';
		$images = wpl_folder::files($path_to_sample_images, '.png$|.gif$|.jpg$|.jpeg$', false, false);
		
		for($i=1; $i<=$count; $i++)
		{
			$property_id = wpl_property::create_property_default('', $kind);
			$rooms = 0;
            $max_rooms = 0;
			$bedrooms = 0;
            $max_bedrooms = 0;
			$bathrooms = 0;
            $max_bathrooms = 0;
			$price = 0;
            $max_price = 0;
			$price_type = '';
			$built_up_area = '';
            $max_built_up_area = '';
			$lot_area = '';
            $max_lot_area = '';
			$view = rand(1, 3);
            $second_view = rand(1, 3);
            $third_view = rand(1, 3);
			$price_type_array = array(1, 7, 30, 365);
			
			if($property_type_parent_type == 'residential')
			{
				$bedrooms = rand(1, 5);
                $max_bedrooms = rand($bedrooms, ($bedrooms+3));
                
				$bathrooms = rand(1, 3);
                $max_bathrooms = rand($bathrooms, ($bathrooms+3));
                
				$built_up_area = rand(200, 800);
                $max_built_up_area = rand($built_up_area, ($built_up_area+300));
			}
			elseif($property_type_parent_type == 'commercial')
			{
				$rooms = rand(1, 4);
                $max_rooms = rand($rooms, ($rooms+2));
                
				$bathrooms = rand(1, 2);
                $max_bathrooms = rand($bathrooms, ($bathrooms+3));
                
				$built_up_area = rand(100, 400);
                $max_built_up_area = rand($built_up_area, ($built_up_area+200));
			}
			elseif($property_type_parent_type == 'land')
			{
				$lot_area = rand(1000, 5000);
                $max_lot_area = rand($lot_area, ($lot_area+2000));
			}
			
			if($listing_type_parent_type == 'sale')
			{
				$price = rand(45000, 385000);
                $max_price = rand($price, ($price+200000));
			}
			elseif($listing_type_parent_type == 'rental')
			{
				$price = rand(200, 3000);
                $max_price = rand($price, ($price+2000));
                
				$price_type = $price_type_array[rand(1, 3)];
			}
			elseif($listing_type_parent_type == 'vacational')
			{
				$price = rand(200, 1500);
                $max_price = rand($price, ($price+1000));
                
				$price_type = $price_type_array[rand(0, 1)];
			}
			
			$q = "";
			
			/** locations **/
			$lt = self::random($lat_min, $lat_max);
			$ln = self::random($lng_min, $lng_max);
			
			$location = wpl_locations::get_address($lt, $ln);
			$location1 = $location['location1'];
			$location2 = $location['location2'];
			$location3 = $location['location3'];
			$location1_id = wpl_locations::get_location_id($location1, '', 1);
			$location2_id = wpl_locations::get_location_id($location2, $location1_id, 2);
			$location3_id = wpl_locations::get_location_id($location3, $location2_id, 3);
			
			$q .= "`listing`='$listing_type', `property_type`='$property_type', `user_id`='$user_id', `bedrooms`='$bedrooms', ";
			$q .= "`bathrooms`='$bathrooms', `rooms`='$rooms', `field_7`='$view', `living_area`='$built_up_area', `lot_area`='$lot_area', ";
			$q .= "`price_period`='$price_type', `price`='$price', `googlemap_lt`='$lt', `googlemap_ln`='$ln', ";
			$q .= "`location1_id`='$location1_id', `location2_id`='$location2_id', `location3_id`='$location3_id', `location1_name`='$location1', `location2_name`='$location2', `location3_name`='$location3', ";
			
            if($kind == 1)
            {
                $q .= "`bedrooms_max`='$max_bedrooms', `second_view`='$second_view', `third_view`='$third_view', `price_max`='$max_price',";
                $q .= "`bathrooms_max`='$max_bathrooms', `rooms_max`='$max_rooms', `living_area_max`='$max_built_up_area', `lot_area_max`='$max_lot_area', ";
            }
            
			/** description **/
			$description = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.";
			$q .= "`field_308`='$description', ";
			
			/** features **/
			$features = array(131,132,133,135,136,137,138,139,142,143,144,146,147,148,149,154,155,156,157,158,160,161,162,163,164,165,166,167,168,169);
			shuffle($features);
			
			$feature_rand = rand(3, 9);
			
			for($k=1; $k<=$feature_rand; $k++)
			{
				$feature = $features[$k];
				$q .= "`f_".$feature."`='1', ";
			}
			
			/** neighborhoods **/
			$neighborhoods = array(100,101,102,103,105,106,107,108,109,110,111,112,113,114,115);
			shuffle($neighborhoods);
			
			$neighborhood_rand = rand(2, 6);
			
			for($k=1; $k<=$neighborhood_rand; $k++)
			{
				$neighborhood = $neighborhoods[$k];
				$q .= "`n_".$neighborhood."`='1', `n_".$neighborhood."_distance`='".rand(3, 15)."', `n_".$neighborhood."_distance_by`='".rand(1, 3)."', ";
			}
			
			/** Property tags **/
			if($property_tag == 'random')
			{
				$property_tags = array('sp_featured','sp_openhouse','sp_hot','sp_forclosure','','','','','','');
				shuffle($property_tags);
				
				if(trim($property_tags[0]) != '') $q .= "`".$property_tags[0]."`='1', ";
			}
			elseif($property_tag) $q .= "`".$property_tag."`='1', ";
			
			$query = "UPDATE `#__wpl_properties` SET ".trim($q, ', ')." WHERE `id`='$property_id'";
			wpl_db::q($query);
			
			/** add images **/
			$property_folder = wpl_items::get_path($property_id);
			shuffle($images);
			
			for($j=0; $j<$image_count; $j++)
			{
				$image = $images[$j];
				$image_path = $path_to_sample_images .DS. $image;
				$dest = $property_folder.$image;
				
				if(wpl_file::copy($image_path, $dest))
				{
					$index = floatval(wpl_items::get_maximum_index($property_id, 'gallery', 0, 'image'))+1.00;
					$item = array('parent_id'=>$property_id, 'parent_kind'=>$kind, 'item_type'=>'gallery',
							'item_cat'=>'image', 'item_name'=>$image, 'creation_date'=>date("Y-m-d H:i:s"), 'index'=>$index);
					
					wpl_items::save($item);
				}
			}
			
			/** add rooms **/
			$query = "SELECT * FROM `#__wpl_room_types` WHERE `enabled`='1' ORDER BY RAND() LIMIT ".rand(2, 5);
			$rooms = wpl_db::select($query, 'loadAssocList');
			
			foreach($rooms as $room)
			{
				$x = rand(2,4);
				$y = rand(2,4);
				
				$item = array('parent_id'=>$property_id, 'parent_kind'=>$kind, 'item_type'=>'rooms', 'item_cat'=>$room['id'], 'item_name'=>$room['name'], 'creation_date'=>date("Y-m-d H:i:s"), 'index'=>'1.00', 'item_extra1'=>$x, 'item_extra2'=>$y);
				$id = wpl_items::save($item);
			}
			
			/** finalize property **/
			wpl_property::finalize($property_id, 'add', $user_id);
		}
		
		$res = 1;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		echo json_encode($response);
		exit;
	}
	
	private function random($min, $max)
	{
		return ($min+lcg_value()*(abs($max-$min)));
	}
}