<?php defined('_WPLEXEC') or die('Restricted access'); /** no direct access **/
$full_url = wpl_global::get_full_url();
header('Content-type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8" ?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="<?php echo str_replace('&', '&amp;', $full_url); ?>" rel="self" type="application/rss+xml" />
        <title><?php echo $this->rss['title']; ?></title>
        <link><?php echo $this->rss['link']; ?></link>
        <description><?php echo $this->rss['desc']; ?></description>
        <generator><?php echo __('WPL', 'wpl'); ?></generator>
        <?php
        foreach($this->properties as $property)
        {
            /** Get property title **/
            $title = isset($property['property_title']) ? $property['property_title'] : '';
            $link = isset($property['property_link']) ? $property['property_link'] : '';
            
            if(!trim($title) or !trim($link)) continue;
            
            /** Get property description **/
            $description_column = 'field_308';
            if(wpl_global::check_multilingual_status()) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);
            
            if(isset($property['data'][$description_column])) $description = $property['data'][$description_column];
            elseif(isset($property['raw'][$description_column])) $description = $property['raw'][$description_column];
            else $description = __('No description is available...', 'wpl');
            ?>
            <item>
                <title><?php echo $title; ?></title>
                <link><?php echo $link; ?></link>
                <guid><?php echo $link; ?></guid>
                <description>
                    <?php 
                    /** Print property images if available **/
                    $activities = wpl_activity::get_activities('pshow_gallery_rss');
                    foreach ($activities as $activity)
                    {
                        $gallery = wpl_activity::render_activity($activity, array('wpl_properties'=>$property));
                        echo htmlspecialchars(html_entity_decode($gallery), ENT_QUOTES, 'UTF-8');
                    }
                    echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
                    ?>
                </description>
            </item>
            <?php
        }
        ?>
    </channel>
</rss>