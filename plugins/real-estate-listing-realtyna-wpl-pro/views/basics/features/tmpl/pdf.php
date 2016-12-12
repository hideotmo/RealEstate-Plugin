<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$build_up_area = isset($this->wpl_properties['current']['materials']['living_area']['value']) ? $this->wpl_properties['current']['materials']['living_area']['value'] : NULL;
$bedroom = isset($this->wpl_properties['current']['materials']['bedrooms']['value']) ? $this->wpl_properties['current']['materials']['bedrooms']['value'] : NULL;
$bathroom = isset($this->wpl_properties['current']['materials']['bathrooms']['value']) ? $this->wpl_properties['current']['materials']['bathrooms']['value'] : NULL;
$listing_id = isset($this->wpl_properties['current']['materials']['mls_id']['value']) ? $this->wpl_properties['current']['materials']['mls_id']['value'] : NULL;
$price = isset($this->wpl_properties['current']['materials']['price']['value']) ? $this->wpl_properties['current']['materials']['price']['value'] : NULL;
$price_type = isset($this->wpl_properties['current']['materials']['price_period']['value']) ? $this->wpl_properties['current']['materials']['price_period']['value'] : NULL;
$location_string = isset($this->wpl_properties['current']['location_text']) ? $this->wpl_properties['current']['location_text'] : '';

$logo_pos = $this->settings['pdf_logo_position'];
$copyright_pos = $this->settings['pdf_copyright_position'];

$co_info[] = $this->settings['pdf_tel'];
$co_info[] = $this->settings['pdf_address'];
if(count($co_info) > 0) $co_info = implode(' - ', $co_info);

$copyright = $this->settings['pdf_copyright'];

$copyright_class = 'wpl-util-left';
if($copyright_pos == 'bottom_right') $copyright_class = 'wpl-util-right';
?>
<html>
<head>
    <title><?php echo $this->property['property_page_title']; ?></title>
    <meta charset="UTF-8"/>
    <style type="text/css">
        <?php include WPL_ABSPATH.'assets/css/pdf.css'; ?>
        <?php include WPL_ABSPATH.'assets/css/ui_customizer/wpl.css'; ?>
    </style>
</head>
<body>
<div class="wpl-pdf-container">

    <?php if($logo_pos == 'top' && trim($this->settings['pdf_logo'])): ?>
    <div class="wpl-pdf-logo wpl-pdf-logo-top">
        <img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url('img/system/'.$this->settings['pdf_logo'])); ?>" />
    </div>
    <?php endif; ?>

    <div class="wpl-pdf-header">

        <div class="wpl-pdf-header-left">
            <div class="wpl-pdf-title"><?php echo $this->wpl_properties['current']['property_title']; ?></div>
            <div class="wpl-pdf-location-build"><?php echo $build_up_area . ' - ' . $location_string; ?></div>
        </div>

        <div class="wpl-pdf-header-right">
            <?php
            $activities = wpl_activity::get_activities('pdf_position_header_right');

            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                if(trim($content) != '') echo '<div class="wpl-pdf-box-wp"><div class="wpl-pdf-box-cnt">' . $content . '</div></div>';
            }
            ?>
        </div>

        <div class="wpl-pdf-clear"></div>
    </div>

    <div class="wpl-pdf-gallery">
        <?php /** load gallery position **/ wpl_activity::load_position('pshow_gallery_pdf', array('wpl_properties'=>$this->wpl_properties)); ?>
        <div class="wpl-pdf-clear"></div>
    </div>

    <?php
    $boxes = array();
    $pdf_box_col = 0;
    
    $description_column = 'field_308';
    if(wpl_global::check_multilingual_status()) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);
                    
    if($this->wpl_properties['current']['data'][$description_column])
    {
        $boxes[] = '<div class="wpl-pdf-box-wp wpl-pdf-left wpl-pdf-box-desc">
                        <div class="wpl-pdf-box-title"><span>' . __('Property', 'wpl') . '</span> ' . __('Description', 'wpl') . '</div>
                        <div class="wpl-pdf-box-cnt">' . apply_filters('the_content', stripslashes($this->wpl_properties['current']['data'][$description_column])) . '</div>
                    </div>';

        $pdf_box_col = 1;
    }

    $i = 0;
    $details_boxes_num = count($this->wpl_properties['current']['rendered']);

    foreach($this->wpl_properties['current']['rendered'] as $values)
    {
        $detail_boxes = '';

        /** skip empty categories **/
        if(!count($values['data'])) continue;

        /** skip location if property address is hiden **/
        if($values['self']['prefix'] == 'ad' and !$this->wpl_properties['current']['raw']['show_address']) continue;

        if($pdf_box_col == 0)
        {
            $pdf_box_col_class = 'wpl-pdf-left';
            $pdf_box_col = 1;
        }
        else
        {
            $pdf_box_col_class = 'wpl-pdf-right';
            $pdf_box_col = 0;
        }

        $detail_boxes .= '<div class="wpl-pdf-box-wp '.$pdf_box_col_class.'"><div class="wpl-pdf-box-title">';

        $title_parts = explode(' ', __($values['self']['name'], 'wpl'));
        $i_title = 0;

        foreach($title_parts as $title_part)
        {
            if($i_title == 0) $detail_boxes .= '<span>' . $title_part . '</span> ';
            else $detail_boxes .= $title_part . ' ';

            $i_title++;
        }

        $detail_boxes .= '</div><div class="wpl-pdf-box-cnt">';

        $all_items = '';
        $col_type = 'odd';
        $i = 0;

        foreach($values['data'] as $key=>$value)
        {
            $item = '';
            $i++;

            if($col_type == 'odd') $col_type = 'even';
            else $col_type = 'odd';

            $classes = ' wpl-pdf-' . $col_type;

            if($i == count($values['data'])) $classes .= ' wpl-pdf-last';
            elseif($i == 1) $classes .= ' wpl-pdf-first';

            if(!isset($value['type']) or $value['type'] == 'separator') continue;
            elseif($value['type'] == 'neighborhood')
            {
                $item .= '<div class="wpl-pdf-field-neighborhood' . $classes . '"><div class="wpl-pdf-field-title">' . __($value['name'], 'wpl') . (isset($value['distance']) ? ':</div><div class="wpl-pdf-field-value ' . $value['vehicle_type'] . '">' . $value['distance'] . ' ' . __('Minutes', 'wpl') . '</div>' : '') . '<div class="wpl-pdf-clear"></div></div>';
            }
            elseif($value['type'] == 'feature')
            {
                if($i == 1 or $i == 2) $classes = ' wpl-pdf-feature-first';
                else $classes = '';

                if(isset($value['values'][0]))
                {
                    $item .= '<div class="wpl-pdf-field-feature-by-value' . $classes . '">';
                    $html = '';
                    $item .= '<div class="wpl-pdf-field-tick"><img src="' . wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/check_icon.png")) . '" /></div><div class="wpl-pdf-field-title">' . __($value['name'], 'wpl') . ':</div><div class="wpl-pdf-field-value">';
                    foreach ($value['values'] as $val) $html .= __($val, 'wpl') . ', ';
                    $html = rtrim($html, ', ');
                    $item .= $html;
                    $item .= '</div>';
                }
                else
                {
                    $item .= '<div class="wpl-pdf-field-feature' . $classes . '">';
                    $item .= '<div class="wpl-pdf-field-tick"><img src="' . wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/check_icon.png")) . '" /></div><div class="wpl-pdf-field-title">' . __($value['name'], 'wpl') . '</div>';
                }

                $item .= '<div class="wpl-pdf-clear"></div></div>';

            }
            elseif($value['type'] == 'locations')
            {
                foreach($value['locations'] as $ii=>$lvalue)
                {
                    $i = 0;
                    $i++;

                    if($col_type == 'odd') $col_type = 'even';
                    else $col_type = 'odd';

                    $classes = ' wpl-pdf-' . $col_type;

                    if($i == count($value['locations'])) $classes .= ' wpl-pdf-last';

                    $item .= '<div class="wpl-pdf-field-location' . $classes . '"><div class="wpl-pdf-field-title">' . __($value['keywords'][$ii], 'wpl') . ':</div>';
                    $item .= '<div class="wpl-pdf-field-value">' . $lvalue . '</div>';
                    $item .= '<div class="wpl-pdf-clear"></div></div>';
                }
            }
            else
            {
                $item .= '<div class="wpl-pdf-field-other' . $classes . '"><div class="wpl-pdf-field-title">' . __($value['name'], 'wpl') . ':</div><div class="wpl-pdf-field-value">' . __((isset($value['value']) ? $value['value'] : ''), 'wpl') . '</div><div class="wpl-pdf-clear"></div></div>';
            }

            $all_items .= $item;
        }

        $detail_boxes .= $all_items . '</div></div>';

        $i++;
        $boxes[] = $detail_boxes;
    }

    foreach($boxes as $box) echo $box;

    // Echo all activity in PDF-Position-1
    $boxes = array();
    $activities = wpl_activity::get_activities('pdf_position1');

    foreach($activities as $activity)
    {
        $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
        if(trim($content) != '')
        {
            $activity_title = explode(':', $activity->activity);
            $boxes_content = '';
            $boxes_content .= '<div class="wpl-pdf-box-wp">';

            if($activity->show_title and trim($activity->title) != '')
            {
                $boxes_content .= '<div class="wpl-pdf-box-title">';
                $title_parts = explode(' ', __($activity->title, 'wpl'));
                $i_title = 0;

                foreach($title_parts as $title_part)
                {
                    if($i_title == 0) $boxes_content .= '<span>' . $title_part . '</span> ';
                    else $boxes_content .= $title_part . ' ';

                    $i_title++;
                }

                $boxes_content .= '</div>';
            }

            $boxes_content .= '<div class="wpl-pdf-box-cnt">' . $content . '</div></div>';
            $boxes[] = $boxes_content;
        }
    }

    foreach($boxes as $box) echo '<div class="wpl-pdf-clear"></div><div class="wpl-pdf-pos-1">' . $box . '<div class="wpl-pdf-clear"></div></div>';
    ?>

    <?php if(count($co_info)): ?>
    <div class="wpl-pdf-co-info">
        <?php echo $co_info; ?>
    </div>
    <?php endif; ?>

    <div class="wpl-pdf-copyright <?php echo $copyright_class; ?>">
        <?php if($logo_pos == 'bottom' && trim($this->settings['pdf_logo'])): ?>
        <div class="wpl-pdf-logo wpl-util-float-left">
            <img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url('img/system/'.$this->settings['pdf_logo'])); ?>" />
        </div>
        <?php endif; ?>

        <div class="wpl-pdf-copyright-text">
            <?php echo $copyright; ?>
        </div>
    </div>

</div>
</body>
</html>