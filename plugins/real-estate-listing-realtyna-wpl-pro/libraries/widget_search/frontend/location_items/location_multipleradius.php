<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'location_multipleradius' and !$done_this)
{
	/** add scripts and style sheet **/
	wp_enqueue_script('jquery-ui-autocomplete');

	$location_level = wpl_global::get_setting('multiple_radius_location_level');
	$current_value = stripslashes(wpl_request::getVar('sf_select_location'.$location_level.'_name', ''));

	$locations = wpl_db::select("SELECT `location".$location_level."_name` FROM `#__wpl_properties` WHERE `location".$location_level."_name`!='' GROUP BY `location".$location_level."_name` ORDER BY `location".$location_level."_name` ASC", 'loadColumn');

	$html .= '<label for="sf_select_location'.$location_level.'_name">'.__($location_settings['location'.$location_level.'_keyword'], 'wpl').'</label>
        <select name="sf_select_location'.$location_level.'_name" id="sf_select_location'.$location_level.'_name" class="wpl_search_widget_field_'.$field['id'].'_select">
            <option value="" '.($current_value == '' ? 'selected="selected"' : '').'>'.__($location_settings['location'.$location_level.'_keyword'], 'wpl').'</option>';

	foreach($locations as $location)
	{
		$complete_address = '';
		$address = '';

		$location_id = wpl_locations::get_location_id($location, '', $location_level);
		$location_data = wpl_locations::get_location($location_id, $location_level);

		$location_parent = $location_level == 1 ? $location_level : $location_level-1;
		$location_tree = wpl_locations::get_location_tree($location_data->parent, $location_parent);

		if($location_tree)
		{
			foreach($location_tree as $add) $address .= $add['name'].', ';

			$complete_address = $location.', '.trim($address, ', ');
		}

		$html .= '<option value="'.$location.'" '.($current_value == $location ? 'selected="selected"' : '').' address="'.$complete_address.'">'.__($location, 'wpl').'</option>';
	}

	$html .= '</select>';

	$html .= '
	<script type="text/javascript">
	if(typeof wpl_current_list_'.$widget_id.' == "undefined") var wpl_current_list_'.$widget_id.' = [];
	if(typeof wpl_locations_values_'.$widget_id.' == "undefined") var wpl_locations_values_'.$widget_id.' = [];
	if(typeof wpl_set_cities_to_param_'.$widget_id.' == "undefined") var wpl_set_cities_to_param_'.$widget_id.' = {};
	if(typeof wpl_created_box_radius_'.$widget_id.' == "undefined") var wpl_created_box_radius_'.$widget_id.' = [];
        
    if(typeof wpl_multiple_radius_loaded_html_'.$widget_id.' == "undefined") var wpl_multiple_radius_loaded_html_'.$widget_id.' = 0;
        
	wplj(document).ready(function(){
        // adding the current search list for showing the selected locations and the box radius as well
        if(wpl_multiple_radius_loaded_html_'.$widget_id.' == 0)
        {
            var html_main_boxes = \'<div id="wpl_currect_search_list'.$widget_id.'" class="wpl-current-search-list"></div>\';
            html_main_boxes += \'<div id="wpl_open_box_radius'.$widget_id.'" class="wpl-box-radius" style="z-index:1">\'+
            \'<div class="wpl-radius-box-info">\'+
                \'<h1 class="wpl-radius-box-title">'.__('Edit Search Radius','wpl').'</h1>\'+
               "<span class=\"realtyna-lightbox-close-btn\" onclick=\"wplj(\'#wpl_open_box_radius'.$widget_id.'\').fadeOut()\"></span>"+
                \'<div class="wpl-radius-box-cnt" id="wpl_search_radius_box_content'.$widget_id.'"></div>\'+
                \'<div class="wpl-radius-box-btn">\'+
                   "<span class=\"btn btn-primary wpl-radius-box-submit\" onclick=\"wpl_do_search_'.$widget_id.'(); wplj._realtyna.lightbox.close(); wplj(\'#wpl_open_box_radius'.$widget_id.'\').fadeOut();\">'.__('Show Result', 'wpl').'</span>"+
                \'</div>\'+
            \'</div></div>\';
            wplj("#wpl_advanced_search'.$widget_id.' .container").prepend(html_main_boxes);
                
            wpl_multiple_radius_loaded_html_'.$widget_id.' = 1
        }

        wplj(\'#more_search_option'.$widget_id.'\').live("click", function(event)
        {
            wplj(\'#wpl_form_override_search'.$widget_id.' #sf_select_location'.$location_level.'_name\').val("");
        });
        
        /** select the location field to search one location **/
        wplj(\'#wpl_default_search_'.$widget_id.' #sf_select_location'.$location_level.'_name\').live("change", function(event)
        {
            for(var l in wpl_locations_values_'.$widget_id.') delete wpl_locations_values_'.$widget_id.'[l];

            for(var b in wpl_created_box_radius_'.$widget_id.') delete wpl_created_box_radius_'.$widget_id.'[b];

            for(var city in wpl_set_cities_to_param_'.$widget_id.') delete wpl_set_cities_to_param_'.$widget_id.'[city];

            wplj(".wpl-currect-search-list-fields").html("");
            wplj("#wpl_search_radius_box_content'.$widget_id.'").html("");

            wplj(".wpl-current-search-list").html("");

            wplj("#sf_multipleradiussearch").val(""); 
        });
        
        /** select the location field for multiple radius **/
        wplj(\'#wpl_form_override_search'.$widget_id.' #sf_select_location'.$location_level.'_name\').live(\'change\', function(event)
        {
            element_id = event.target.id;

            value = wplj("#"+element_id).val();

            if(value != \'-1\' && value != \'0\' && wplj.trim(value) != \'\')
            {
                wplj(\'#wpl_currect_search_list'.$widget_id.'\').fadeIn(\'normal\');

                if(wplj.inArray(element_id, wpl_current_list_'.$widget_id.') == \'-1\') wpl_current_list_'.$widget_id.'.push(element_id);

                if(wplj.inArray(value, wpl_locations_values_'.$widget_id.') == \'-1\') wpl_locations_values_'.$widget_id.'.push(value);

                wplj(\'#wpl_form_override_search'.$widget_id.' #\'+element_id).find(\'option:selected\').removeAttr(\'selected\');
                wplj("#"+element_id).val("");
                wplj(\'#wpl_form_override_search'.$widget_id.' #\'+element_id).trigger(\'chosen:updated\');
            }

	        wpl_save_search_to_list'.$widget_id.'();
        });

        /** Clear all current listings **/
        wplj(".wpl-current-search-clear-btn").live("click", function ()
        {
            for(var l in wpl_locations_values_'.$widget_id.') delete wpl_locations_values_'.$widget_id.'[l];

            for(var b in wpl_created_box_radius_'.$widget_id.') delete wpl_created_box_radius_'.$widget_id.'[b];

            for(var city in wpl_set_cities_to_param_'.$widget_id.') delete wpl_set_cities_to_param_'.$widget_id.'[city];

            wplj(".wpl-currect-search-list-fields").html("");
            wplj("#wpl_search_radius_box_content'.$widget_id.'").html("");

            wplj(".wpl-current-search-list").html("");

            wplj("#sf_multipleradiussearch").val("");
            ';

            if($this->ajax == 2) $html .= 'wpl_do_search_'.$widget_id.'();';
	$html .= '
        });

        get_multipleradius_values_'.$widget_id.'();
	});

	/** Get and generate the html of selected cities/locations **/
	function wpl_save_search_to_list'.$widget_id.'()
	{
	    html_fields = "";

        for (var c in wpl_locations_values_'.$widget_id.') html_fields += wpl_create_html_current_search_'.$widget_id.'(wpl_locations_values_'.$widget_id.'[c], "location");
	    
	    if (html_fields)
	    {
            text_html = \'<div class="inform_multiple_search_text"><b>Note: </b><q><i>By default, 5 miles radius is set, you can change the radius by clicking on each city</i></q></div>\';
            
	        html_fields = \'<div class="wpl-currect-search-list-inner clearfix">\'
	                      + text_html
	                      + \'<div class="wpl-currect-search-list-fields">\'+ html_fields+\'</div>\'
	                      +\'<span class="wpl-current-search-clear-btn">'.__("Clear All",'wpl').'</span></div>\';

            wplj("#wpl_currect_search_list'.$widget_id.'").html(html_fields);
        }
	}

	/** Generate the html of selected cities/locations **/
	function wpl_create_html_current_search_'.$widget_id.'(text, type)
	{
	    close_button = "<span class=\"wpl-delete-current-search-field\" onclick=\"return wpl_delete_location_search_'.$widget_id.'(\'"+text+"\');\"></span>";
        radius_class = \'wpl-open-box-radius\';
	    onclick_city = "return wpl_open_radius_boxes_'.$widget_id.'(\'"+text+"\');";

		rendered_id = \'wpl_radius\'+text.replace(" ", "_") + \'_'.$widget_id.'\';
		rendered_id_box = \'wpl_radius_box_\'+text.replace(" ", "_") + \'_'.$widget_id.'\';
		rendered_id_to_param = text.replace(" ", "_");

		if(wplj.inArray(rendered_id_box, wpl_created_box_radius_'.$widget_id.') == "-1")
		{
			html = \' <div id="\'+rendered_id_box+\'"><p>\'+text+\' radius</p>\'+
				"<select name=\'"+rendered_id+"\' id=\""+rendered_id+"_select\" onchange=\"return wpl_select_radius_cities_'.$widget_id.'(\'"+rendered_id_to_param+"\', this.value, this.id);\">"+
					\' <option value="0">0 Miles</option> \'+
					\' <option value="5">5 Miles</option> \'+
					\' <option value="15">15 Miles</option> \'+
					\' <option value="20">20 Miles</option> \'+
					\' <option value="25">25 Miles</option> \'+
		            \' <option value="30">30 Miles</option> \'+
					\' <option value="35">35 Miles</option> \'+
					\' <option value="40">40 Miles</option> \'+
					\' <option value="45">45 Miles</option> \'+
					\' <option value="50">50 Miles</option> \'+
					\'</select> \'+
					\' <hr/> </div>\';
            
			wplj("#wpl_search_radius_box_content'.$widget_id.'").prepend(html);
                
            var select_field = rendered_id+\'_select\';
            wpl_select_radius_cities_'.$widget_id.'(rendered_id_to_param, 5, select_field);

			wpl_created_box_radius_'.$widget_id.'.push(rendered_id_box);
		}

		html_fields = \'<div class="wpl-current-search-fields \'+radius_class+\'" id="\'+rendered_id+\'" rel="\'+text+\'"> \'+ close_button +
		" <span class=\"wpl-open-box-radius-text\" onclick=\""+onclick_city+"\">"+text.replace(\'_\', \' \')+"</span> </div>";

		return html_fields;
	}

	/** Delete the location **/
	function wpl_delete_location_search_'.$widget_id.'(city)
	{
	    for(var c in wpl_locations_values_'.$widget_id.')
	    {
	        if(wpl_locations_values_'.$widget_id.'[c] == city)
	        {
	            delete wpl_locations_values_'.$widget_id.'[c];

	            rendered_id = \'wpl_radius\'+city.replace(\' \', \'_\') + \'_'.$widget_id.'\';
	            rendered_id_box = \'wpl_radius_box_\'+city.replace(\' \', \'_\') + \'_'.$widget_id.'\';
	            for(var b in wpl_created_box_radius_'.$widget_id.')
	            {
	                if(wpl_created_box_radius_'.$widget_id.'[b] == rendered_id_box) delete wpl_created_box_radius_'.$widget_id.'[b];
	            }

	            wplj("#"+rendered_id).hide();
	            wplj("#"+rendered_id_box).remove();

	            var i = 0;
	            wplj(".wpl-currect-search-list-fields div.wpl-current-search-fields").each(function(ind, element){
	                if(!wplj(this).is(":hidden")) i++;
	            });

	            // delete from cities array
	            city = city.replace(\' \', \'_\');
	            delete wpl_set_cities_to_param_'.$widget_id.'[city];
	            generate_multipleradius_value_hidden_'.$widget_id.'();

	            if(i == 0) wplj(".wpl-current-search-clear-btn").trigger("click");
	            else
	            {
	                ';

            if($this->ajax == 2) $html .= 'wpl_do_search_'.$widget_id.'();';
	$html .= '
	            }
                break;
            }
        }

	    if(wpl_locations_values_'.$widget_id.'.length == 0) wplj("#sf_multipleradiussearch").remove();

	    return false;
	}

	/** open the radius box to set radius values **/
	function wpl_open_radius_boxes_'.$widget_id.'()
	{
	    wplj("#wpl_open_box_radius'.$widget_id.'").fadeIn("normal");
	}

	/** generate an array for selected cities **/
	function wpl_select_radius_cities_'.$widget_id.'(city, radius, id)
	{
        wplj("#"+id+" option").removeAttr("selected");
        wplj("#"+id+" option").each(function(ind, el){
            var val = wplj(this).val();
            if(val == radius) wplj(this).attr("selected", "selected");
        });
        
	    id = "sf_select_location'.$location_level.'_name";
	    var selected = wplj(\'#\'+id+\' option[value="\'+city.replace(\'_\', \' \')+\'"]\');
	    var address = wplj(selected).attr("address");

	    if(typeof wpl_set_cities_to_param_'.$widget_id.'[city] == \'undefined\')
	    {
	        array = {\'location\':city , \'radius\':radius, \'complete_address\':address};
	        wpl_set_cities_to_param_'.$widget_id.'[city] = [];
	        wpl_set_cities_to_param_'.$widget_id.'[city].push(array);
	    }
	    else
	    {
	        delete wpl_set_cities_to_param_'.$widget_id.'[city];
	        array = {\'location\':city , \'radius\': radius, \'complete_address\':address};
	        wpl_set_cities_to_param_'.$widget_id.'[city] = [];
	        wpl_set_cities_to_param_'.$widget_id.'[city].push(array);
	    }

	    generate_multipleradius_value_hidden_'.$widget_id.'();
	}

	/** Create the sf input field to select multipleradius **/
	function generate_multipleradius_value_hidden_'.$widget_id.'()
	{
	    get_multi_search_radius = wplj(\'#wpl_searchwidget_'.$widget_id.' #sf_multipleradiussearch\');
	    if(get_multi_search_radius.length == 0)
	    {
	        html_multi_search_radius = \'<input type="hidden" id="sf_multipleradiussearch" name="sf_multipleradiussearch">\';
	        wplj(\'#wpl_searchwidget_'.$widget_id.'\').append(html_multi_search_radius);
	    }

	    var string = \'\';

	    for(var i in wpl_set_cities_to_param_'.$widget_id.')
	    {
	        if(typeof wpl_set_cities_to_param_'.$widget_id.'[i] == \'undefined\') continue;

	        var location = wpl_set_cities_to_param_'.$widget_id.'[i][0].location;
	        var radius = wpl_set_cities_to_param_'.$widget_id.'[i][0].radius;
	        var address = wpl_set_cities_to_param_'.$widget_id.'[i][0].complete_address;

	        if(wplj.trim(address) == "") address = location;

	        string += \'city-\'+location+\':radius-\'+radius+\':address-\'+address+\'|\';
	    }
	    wplj(\'#wpl_searchwidget_'.$widget_id.' #sf_multipleradiussearch\').val(encodeURIComponent(string));
	}

	/** Get the default value from URL if exist **/
	function get_multipleradius_values_'.$widget_id.'()
	{

	if(typeof wpl_locations_values_'.$widget_id.' === "undefined" || wpl_locations_values_'.$widget_id.'.length > 0) return;
	';
	    $sf_multipleradiussearch = wpl_request::getVar('sf_multipleradiussearch', '');

	    if($sf_multipleradiussearch):
	    $sf_multipleradiussearch = trim(urldecode($sf_multipleradiussearch), '|');

	    $array_locations = explode('|', $sf_multipleradiussearch);
	    $exp_location_data = array();

	    foreach($array_locations as $location)
	    {
	        $exp_loc = explode(':', $location);
	        $city = str_replace('city-', '', $exp_loc[0]);
	        $radius = str_replace('radius-', '', $exp_loc[1]);
	        $complete_address = str_replace('address-', '', $exp_loc[2]);

	        $exp_location_data[$city] = array('location'=>$city, 'radius'=>$radius, 'complete_address'=>$complete_address);
	    }

	    $html .= '
	    radius = \''.json_encode($exp_location_data).'\';

	    element_id = "text_location'.$location_level.'_name";

	    wplj(\'#wpl_currect_search_list'.$widget_id.'\').show();

	    wpl_current_list_'.$widget_id.'.push(element_id);

	    var radius_multiple = JSON.parse(radius);
	    if (radius_multiple != \'\')
	    {
	        for (var i in radius_multiple)
	        {
	            if(!radius_multiple[i]) continue;

	            wpl_locations_values_'.$widget_id.'.push(radius_multiple[i].location);
	            wpl_set_cities_to_param_'.$widget_id.'[radius_multiple[i].location] = [];
	            wpl_set_cities_to_param_'.$widget_id.'[radius_multiple[i].location].push(radius_multiple[i]);
	        }
	    }

	    generate_multipleradius_value_hidden_'.$widget_id.'();
	    wpl_save_search_to_list'.$widget_id.'();
	    ';
	    endif;

	$html .= '
	}
	</script>
	';
	
	$done_this = true;

}