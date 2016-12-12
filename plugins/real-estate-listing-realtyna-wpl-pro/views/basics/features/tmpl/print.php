<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$users_pshow_fields = wpl_users::get_pshow_fields();
?>
<html>
<head>
    <title><?php echo $this->property['property_page_title']; ?></title>
    <meta charset="UTF-8"/>
    <style type="text/css">
        <?php include WPL_ABSPATH.'assets/css/frontend.css'; ?>
        body{display:table;font:14px Lato,Arial;margin:0;padding:20pt 0;width:100%;min-width:770px}
        body ul,body li{list-style:none;margin:0}
        .container{margin-right:auto;margin-left:auto;padding-left:15px;padding-right:15px}
        .wpl-print-page-container .wpl_prp_title{font:20px Arial;margin-top:10px}
        .wpl_prp_cont .wpl_prp_listing_location{font-size:10pt!important}
        .wpl_prp_listing_icon_box{display:table;margin:0 auto}
        .print_view_rooms{text-align:center;min-width:250px;margin:0 auto;display:table}
        .print_view_rooms div{display:block;float:left;font-size:9pt;font-family:Lato,Arial;width:33.3%;text-align:center;margin:0}
        .wpl_gallery_container{height:260px;width:100%;overflow:visible;position:relative}
        .wpl_gallery_container .wpl_gallery_image{position:relative;z-index:99;display:block;width:250px!important;height:200px!important}
        .wpl_gallery_container .wpl-listing-tags-wp{display:none}
        .wpl_prp_cont{margin:0 1%;border:none!important}
        .wpl_prp_cont > div{float:left;width:100%}
        .wpl_prp_cont .price_box{position:relative;border-bottom:1px solid #e6e6e6;height:auto;padding:10px 0;}
        .wpl-print-page-container.container{display:table;margin:10px auto}
        .print_view_images{float:left;text-align:center;width:100%}
        .print_view_images img{height:250px;margin:0 auto;width:360px;max-width:100%}
        @media (min-width: 768px){.container{width:750px}}
        @media (min-width: 992px){.container{width:970px}}
        @media (min-width: 1200px){.container{width:1170px}}
    </style>
    <link rel='stylesheet' href='<?php echo wpl_global::get_wpl_asset_url("css/ui_customizer/wpl.css") ?>' type='text/css' media='all' />
    
</head>
<body>
<div class="wpl-print-page-container container" style="padding-top:50px;">
    <?php
    foreach($this->wpl_properties as $key=>$property)
    {
        if($key == 'current') continue;

        /** unset previous property **/
        unset($this->wpl_properties['current']);

        /** set current property **/
        $this->wpl_properties['current'] = $property;
        
        $room = isset($property['materials']['bedrooms']) ? '<div class="bedroom">'.$property['materials']['bedrooms']['name'].': '.$property['materials']['bedrooms']['value'].'</div>' : '';
        if((!isset($property['materials']['bedrooms']) or (isset($property['materials']['bedrooms']) and $property['materials']['bedrooms']['value'] == 0)) and (isset($property['materials']['rooms']) and $property['materials']['rooms']['value'] != 0)) $room = '<div class="room" >'.$property['materials']['rooms']['name'].': '.$property['materials']['rooms']['value'].'</div>';
        
        $bathroom = isset($property['materials']['bathrooms']) ? '<div class="bathroom">'.$property['materials']['bathrooms']['name'].': '.$property['materials']['bathrooms']['value'].'</div>' : '';
        
        $parking_number = isset($property['materials']['f_150']) ? $property['materials']['f_150']['values'][0] : NULL;
        $parking = (isset($property['raw']['f_150']) and trim($property['raw']['f_150_options'])) ? '<div class="parking">'.$property['materials']['f_150']['name'].': '.$parking_number.'</div>' : '';
        $pic_count = '<div class="pic_count">'.__('Pictures', 'wpl').': '.$property['raw']['pic_numb'].'</div>';
        
        $user_id = $property['data']['user_id'];
        $wpl_user = wpl_users::full_render($user_id, $users_pshow_fields, NULL, array(), true);
            $array_image = wpl_items::get_gallery($property['data']['id']);
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $array_image[0]['raw']['item_name'];
            $params['image_parentid'] = $array_image[0]['raw']['parent_id'];
            $params['image_parentkind'] = $array_image[0]['raw']['parent_kind'];
            $params['image_source'] = $array_image[0]['path'];
            
            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallery_image('360', '250', $params, 0, 0);
        
        ?>
        <div class="wpl_prp_cont <?php echo ((isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box'))) ? $this->property_css_class : ''); ?>" id="wpl_prp_cont<?php echo $property['data']['id']; ?>" itemscope itemtype="https://schema.org/TradeAction">
            <div class="print_view_images">
                <img src="<?php echo $image_url ?>" alt="img" width="250" height="200">
            </div>
            <div class="wpl_prp_bot">
                <?php
                echo '<a id="prp_link_id_'.$property['data']['id'].'_view_detail" href="'.$property['property_link'].'" class="view_detail" title="'.$property['property_title'].'">
                  <h3 class="wpl_prp_title" itemprop="name">'.$property['property_title'].'</h3></a>';
                echo '<h4 class="wpl_prp_listing_location" itemprop="location">'.$property['location_text'].'</h4>';
                ?>
                <div class="print_view_rooms"><?php echo $room . $bathroom . $parking . $pic_count; ?></div>
                <?php if(isset($property['materials']['price'])): ?><div class="price_box"><span itemprop="price" content="<?php echo $property['materials']['price']['value']; ?>"><?php echo $property['materials']['price']['value']; ?></span></div><?php endif; ?>
            </div>
            
            <div class="wpl_agent_container">
                <div class="wpl_agent_biography">
                    <?php if(isset($wpl_user['profile_picture'])): ?><img class="wpl_agent_profile_picture" src="<?php echo $wpl_user['profile_picture']['url']; ?>" /><?php endif; ?>
                 </div>
                <div class="wpl_agent_contact">
                    <ul>
                        <li class="wpl_agent_profile_name">
                            <a href="<?php echo wpl_users::get_profile_link($user_id); ?>"><?php echo trim($wpl_user['materials']['first_name']['value'].' '.$wpl_user['materials']['last_name']['value']); ?></a>
                        </li>

                        <?php if(isset($wpl_user['materials']['website']['value'])): ?>
                        <li class="website"><a itemprop="url"  href="<?php echo $wpl_user['materials']['website']['value']; ?>" target="_blank"><?php echo $wpl_user['materials']['website']['value']; ?></a></li>
                        <?php endif; ?>

                        <?php if(isset($wpl_user['materials']['mobile']['value'])): ?>
                        <li itemprop="telephone" class="mobile"><?php echo $wpl_user['materials']['mobile']['value']; ?></li>
                        <?php endif; ?>

                        <?php if(isset($wpl_user['main_email_url'])): ?>
                        <li class="email">
                            <img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo trim($wpl_user['materials']['first_name']['value'].' '.$wpl_user['materials']['last_name']['value']); ?>" />
                        </li>
                        <?php endif;?>

                        <?php /* if(isset($wpl_user['materials']['tel']['value'])): ?>
                        <li itemprop="telephone" class="tel"><?php echo $wpl_user['materials']['tel']['value']; ?></li>
                        <?php endif; ?>

                        <?php if(isset($wpl_user['materials']['fax']['value'])): ?>
                        <li itemprop="faxNumber" class="fax"><?php echo $wpl_user['materials']['fax']['value']; ?></li>
                        <?php endif; ?>

                        <?php if(isset($wpl_user['main_email_url'])): ?>
                        <li class="email">
                            <img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo trim($wpl_user['materials']['first_name']['value'].' '.$wpl_user['materials']['last_name']['value']); ?>" />
                        </li>
                        <?php endif;

                        <?php if(isset($wpl_user['second_email_url'])): ?>
                        <li class="second_email">
                            <img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo trim($wpl_user['materials']['first_name']['value'].' '.$wpl_user['materials']['last_name']['value']); ?>" />
                        </li>
                        <?php endif; ?>  */ ?>
                    </ul>
                </div>
            </div>
            

        </div>
        <?php
    }
    ?>
</div>

</body>
</html>