<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'suggestion_search' and !$done_this)
{
	/** import library **/
	_wpl_import('libraries.locations');
	_wpl_import('libraries.addon_aps');

	/** add scripts and style sheet **/
	wp_enqueue_script('jquery-ui-autocomplete');

	$location_settings = wpl_global::get_settings('3'); # location settings

	/** Select the radius search params **/
    $radius_unit = $options['radius_unit'];
    $radius_distance = $options['radius_distance'];
	$kind = $field_data['kind'];

	/** Create the search fields **/
    $APS = new wpl_addon_aps();
	$search_fields = $APS->get_suggestion_search_fields($options['values']);

	/** Create the Search by select and respective options **/
	$html .= '<select id="" onchange="change_suggestion_search_type'.$widget_id.'(this.value)">';
	$html .= '<option value="-1">'.__('Search by', 'wpl').'</option>';

	foreach($search_fields as $field_name=>$search_field_data)
	{
		if(!$search_field_data['options']['enabled']) continue;

		if(stristr($field_name, 'location') or $field_name == 'zip_name')
		{
			$field_name_rendered = isset($location_settings[$field_name.'_keyword']) ? $location_settings[$field_name.'_keyword'] : ucfirst(str_replace('_', ' ', $field_name));

			if($field_name == 'zip_name') $field_name_rendered = isset($location_settings['locationzips_keyword']) ? $location_settings['locationzips_keyword'] : ucfirst(str_replace('_', ' ', $field_name));

			$field_name_rendered = $field_name_rendered == '' ? $field_name : $field_name_rendered;
			$html .= '<option value="'.$field_name.$widget_id.'">'.__($field_name_rendered, 'wpl').'</option>';
		}
		else
		{
			$field_name_rendered = __(ucfirst(str_replace('_', ' ', $field_name)), 'wpl');
			if($field_name == 'mls') $field_name_rendered = __(strtoupper($field_name), 'wpl').'#';

			$html .= '<option value="'.$field_name.$widget_id.'">'.$field_name_rendered.'</option>';
		}
	}

	$html .= '</select>
	<div id="box_show_text_suggestion_search'.$widget_id.'" class="wpl-suggestion-search-txt">';

	/** Create the input boxes **/
	$html .= '<input type="text" id="search_all'.$widget_id.'" name="sf'.$widget_id.'_textsearch_textsearch" class="show-input-text-field'.$widget_id.' suggestion-fields-autocomplete'.$widget_id.'" placeHolder="'.$search_fields['search_all']['options']['placeholder'].'" rel="search_all">';

	foreach($search_fields as $field_name=>$search_field_data)
	{
		if($field_name == 'search_all' or !$search_field_data['options']['enabled']) continue;

		$class = 'show-input-text-field'.$widget_id.' ';
		if($field_name != 'mls' or $field_name != 'nearby' or $field_name != 'keyword') $class .= 'suggestion-fields-autocomplete'.$widget_id;

		if(stristr($field_name, 'location'))
		{
			$field_sf_name = 'sf'.$widget_id.'_select_'.$field_name.'_name';
			$value = wpl_request::getVar($field_sf_name, '');
		}
		elseif(stristr($field_name, 'zip_name'))
		{
			$field_sf_name = 'sf'.$widget_id.'_select_zip_name';
			$value = wpl_request::getVar($field_sf_name, '');
		}
		elseif(stristr($field_name, 'mls'))
		{
			$field_sf_name = 'sf'.$widget_id.'_select_mls_id';
			$value = wpl_request::getVar($field_sf_name, '');
		}
		elseif(stristr($field_name, 'keyword'))
		{
			$field_sf_name = 'sf'.$widget_id.'_text_meta_keywords';
			$value = wpl_request::getVar($field_sf_name, '');
		}
		elseif(stristr($field_name, 'address'))
		{
			$field_sf_name = 'sf'.$widget_id.'_locationtextsearch';
			$value = wpl_request::getVar($field_sf_name, '');
		}
		else
		{
			$field_sf_name = 'sf'.$widget_id.'_select_'.$field_name;
			$value = wpl_request::getVar($field_sf_name, '');
		}

		if($field_name == 'feature')
		{
			$value = wpl_request::getVar('sf'.$widget_id.'_suggestion_search_feature', '');

			$rendered_value = explode('(', $value);
			$rendered_value = str_replace(')', '', $rendered_value);

			$main_feature_value = isset($rendered_value[1]) ? $rendered_value[1] : '';

			if($value != '') $hidden_value = 1;
			else $hidden_value = '-1';

			$html .= '
			<input type="text" id="'.$field_name.$widget_id.'" name="sf'.$widget_id.'_suggestion_search_feature" class="'.$class.'" placeHolder="'.__($search_field_data['options']['placeholder'], 'wpl').'" style="display:none;" value="'.$value.'"  rel="'.$field_name.'">
			<input type="hidden" value="'.$hidden_value.'" id="hidden_field_feature_suggestion'.$widget_id.'" name="sf'.$widget_id.'_select_'.$main_feature_value.'">';
		}
		elseif($field_name == 'neighborhood')
		{
			$value = wpl_request::getVar('sf'.$widget_id.'_suggestion_search_neighborhood', '');

			if($value != '') $hidden_value = 1;
			else $hidden_value = '';
			
			$html .= '
			<input type="text" id="'.$field_name.$widget_id.'" name="sf'.$widget_id.'_suggestion_search_neighborhood" class="'.$class.'" placeHolder="'.__($search_field_data['options']['placeholder'], 'wpl').'" style="display:none;" value="'.$value.'" rel="'.$field_name.'">
			<input type="hidden" value="'.$hidden_value.'" id="hidden_field_neighborhood_suggestion'.$widget_id.'" name="sf'.$widget_id.'_textsearch_neighborhood_ids">';
		}
		else $html .= '<input type="text" id="'.$field_name.$widget_id.'" name="'.$field_sf_name.'" class="'.$class.'" placeHolder="'.__($search_field_data['options']['placeholder'], 'wpl').'" style="display:none;" value="'.$value.'" rel="'.$field_name.'">';
	}

	$html .= '
	<div id="show_geoLocation_error'.$widget_id.'"></div>
	</div>
	<script type="text/javascript">
	var wpl_autocomplete_cache'.$widget_id.' = {};
	var wpl_field_element_rel'.$widget_id.' = "";

	(function($,window, document)
    {
       	$(function()
        {
       		$(".suggestion-fields-autocomplete'.$widget_id.'").autocomplete(
			{
				search : function(){},
				open : function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
				close : function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all");},
				source: function(request, response)
				{
					wpl_field_element_rel'.$widget_id.' = wplj(this.element[0]).attr("rel");
					var term = request.term.toUpperCase(), items = [];
					
					if(typeof wpl_autocomplete_cache'.$widget_id.'[wpl_field_element_rel'.$widget_id.'] == "undefined") wpl_autocomplete_cache'.$widget_id.'[wpl_field_element_rel'.$widget_id.'] = [];

					for(var key in wpl_autocomplete_cache'.$widget_id.'[wpl_field_element_rel'.$widget_id.'])
					{
						if(key === term)
						{
							response(wpl_autocomplete_cache'.$widget_id.'[wpl_field_element_rel'.$widget_id.'][key]);
							return;
						}
					}
					
					$.ajax(
					{
						type: "GET",
						url: "'.wpl_global::get_wp_site_url().'?wpl_format=f:property_listing:ajax_aps&wpl_function=suggestion_search_autocomplete&term="+request.term+"&field_name="+wpl_field_element_rel'.$widget_id.'+"&kind='.$kind.'",
						contentType: "application/json; charset=utf-8",
						success: function (msg)
						{
						   response($.parseJSON(msg));
						   result = $.parseJSON(msg);

						   wpl_autocomplete_cache'.$widget_id.'[wpl_field_element_rel'.$widget_id.'][request.term.toUpperCase()] = $.parseJSON(msg);
						},
						error: function (msg)
						{
						}
					});
				},
				select: function(event, ui)
				{
					if(wpl_field_element_rel'.$widget_id.' == "feature") $("#hidden_field_feature_suggestion'.$widget_id.'").attr({"name":"sf'.$widget_id.'_select_f_"+ui.item.id, "value":"1"});
				   	else if(wpl_field_element_rel'.$widget_id.' == "neighborhood") $("#hidden_field_neighborhood_suggestion'.$widget_id.'").attr("value", "["+ui.item.id+"]");
				},
				width: 260,
				matchContains: true,
				minChars: 0,
				delay: 300
			});
		});

        $(".suggestion-fields-autocomplete'.$widget_id.'").blur(function()
        {
            var element_id = $(this).attr("rel");
            if($.trim($(this).val()) == "")
            {
                if(element_id == "feature")
                {
                    $("#hidden_field_feature_suggestion'.$widget_id.'").attr("name", "empty");
                    $("#hidden_field_feature_suggestion'.$widget_id.'").val("");
                }
                else if(element_id == "neighborhood") $("#hidden_field_neighborhood_suggestion'.$widget_id.'").val("");
            }
        });
	})(jQuery, window, document);
	
	var wpl_geolocation_latitude'.$widget_id.' = "";
	var wpl_geolocation_longitude'.$widget_id.' = "";

	function change_suggestion_search_type'.$widget_id.'(value)
 	{
 	    wplj(".wpl-suggestion-search-txt > input").each(function(ind, element){
 	        wplj(this).val("");
 	    });
 		wplj("#show_geoLocation_error'.$widget_id.'").html("");
 		wplj(".show-input-text-field'.$widget_id.'").hide();
 		wplj("#"+value).show();

		if(value == "nearby'.$widget_id.'" && wpl_geolocation_latitude'.$widget_id.' == "")
 		{
 			wplj("#nearby'.$widget_id.'").val("'.__("Please wait...", 'wpl').'");

 			if (navigator.geolocation)
			{
				navigator.geolocation.getCurrentPosition(wpl_set_values_to_search'.$widget_id.', wpl_geoLocationError'.$widget_id.');
			}
	    	else wplj("#show_geoLocation_error'.$widget_id.'").html("'.__("Geolocation is not supported by this browser.", 'wpl').'");
 		}
 	}

 	function wpl_set_values_to_search'.$widget_id.'(position)
 	{
	    wpl_geolocation_latitude'.$widget_id.' = position.coords.latitude;
	    wpl_geolocation_longitude'.$widget_id.' = position.coords.longitude;

	    if(wpl_geolocation_latitude'.$widget_id.' != "")
	    {
	    	input_html = "<input type=\"hidden\" name=\"sf_radiussearchunit\" value=\"'.$radius_unit.'\"> ";
		    input_html += "<input type=\"hidden\" name=\"sf_radiussearchradius\" value=\"'.$radius_distance.'\"> ";
		    input_html += "<input type=\"hidden\" name=\"sf_radiussearch_lat\" value=\""+wpl_geolocation_latitude'.$widget_id.'+"\"> ";
		    input_html += "<input type=\"hidden\" name=\"sf_radiussearch_lng\" value=\""+wpl_geolocation_longitude'.$widget_id.'+"\"> ";

		    wplj("#box_show_text_suggestion_search'.$widget_id.'").append(input_html);

	    	wplj("#nearby'.$widget_id.'").attr("disabled", "disabled").val("'.__("We've got the location", 'wpl').'");
	    }
	}

	function wpl_geoLocationError'.$widget_id.'(error)
	{
		text_error = "";

		switch(error.code)
		{
	        case error.PERMISSION_DENIED:
	            text_error = "'.__("You have denied the request for Geolocation.", 'wpl').'";
	            break;
	        case error.POSITION_UNAVAILABLE:
	            text_error = "'.__("Location information is unavailable.", 'wpl').'";
	            break;
	        case error.TIMEOUT:
	            text_error = "'.__("The request to get user location timed out.", 'wpl').'";
	            break;
	        case error.UNKNOWN_ERROR:
	            text_error = "'.__("An unknown error occurred.", 'wpl').'"
	            break;
    	}

    	wplj("#show_geoLocation_error'.$widget_id.'").html(text_error);
	}
	</script>';
    
	$done_this = true;
}