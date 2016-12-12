<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$main_user_id                     = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties              = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$profile_picture_width       = isset($params['picture_width']) ? $params['picture_width'] : '90';
$profile_picture_height      = isset($params['picture_height']) ? $params['picture_height'] : '100';

/** getting user id from current property (used in property_show and property_listing) **/
if(!trim($main_user_id)) $main_user_id = $wpl_properties['current']['data']['user_id'];

$user_ids = array();
$user_ids[] = $main_user_id;

// Add additional agents to the agent contact information
if(wpl_global::check_addon('multi_agents'))
{
    _wpl_import('libraries.addon_multi_agents');

    $multi = new wpl_addon_multi_agents($wpl_properties['current']['data']['id']);
    $additional_agents = $multi->get_agents();

    foreach($additional_agents as $additional_agent) $user_ids[] = $additional_agent;
    
    $user_ids = array_unique($user_ids);
}

$pshow_fields = wpl_users::get_pshow_fields();

$users_data = array();
foreach($user_ids as $user_id)
{
    $wpl_user = wpl_users::full_render($user_id, $pshow_fields, NULL, array(), true);

    $params                      = array();
    $params['image_parentid']    = $user_id;
    $params['image_name']        = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
    $profile_path                = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';

    $profile_image               = wpl_images::create_profile_images($profile_path, $profile_picture_width, $profile_picture_height, $params);
    if(trim($profile_image) != '') $profile_image = wpl_pdf::get_fixed_url($profile_image);
    
    $users_data[] = array('wpl_user'=>$wpl_user, 'profile_image'=>$profile_image);
}

$is_multi_agent = (count($users_data) > 1 ? true : false);
?>
<?php foreach($users_data as $user_data): ?>
	<?php if($is_multi_agent) echo '<div class="wpl_multi_agent_info">'; ?>
	<table class="wpl_agent_info">
		<tr>
			<td class="wpl_agent_info_td_l <?php if(!$user_data['profile_image']): echo 'wpl_agent_info_td_no_image'; endif; ?>" style="width: <?php echo $profile_picture_width; ?>px; height:<?php echo $profile_picture_height; ?>px;">
				<?php if($user_data['profile_image']): ?>
				<div class="wpl_agent_info_l">
					<img src="<?php echo $user_data['profile_image']; ?>" class="profile_image" />
				</div>
				<?php else: ?>
					<span class="no_image"><?php echo __('No Image', 'wpl') ?></span>
				<?php endif; ?>
			</td>
			<td class="wpl_agent_info_td_r">
				<div class="wpl_agent_info_r">
					<div class="name"><?php echo (isset($user_data['wpl_user']['materials']['first_name']['value']) ? $user_data['wpl_user']['materials']['first_name']['value'] : '').' '.(isset($user_data['wpl_user']['materials']['last_name']['value']) ? $user_data['wpl_user']['materials']['last_name']['value'] : ''); ?>
					</div>

					<?php if(isset($user_data['wpl_user']['materials']['website']['value'])): ?>
					<div class="website">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/website.png")); ?>"></td>
								<td><?php echo $user_data['wpl_user']['materials']['website']['value']; ?></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>

					<?php if(isset($user_data['wpl_user']['materials']['tel']['value'])): ?>
					<div class="tel">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/phone.png")); ?>"></td>
								<td><?php echo $user_data['wpl_user']['materials']['tel']['value']; ?></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>

					<?php if(isset($user_data['wpl_user']['materials']['mobile']['value'])): ?>
					<div class="mobile">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/phone.png")); ?>"></td>
								<td><?php echo $user_data['wpl_user']['materials']['mobile']['value']; ?></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>

					<?php if(isset($user_data['wpl_user']['materials']['fax']['value'])): ?>
					<div class="fax">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/phone.png")); ?>"></td>
								<td><?php echo $user_data['wpl_user']['materials']['fax']['value']; ?></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>

					<?php if(isset($user_data['wpl_user']['main_email_url'])): ?>
					<div class="email">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/email.png")); ?>" /></td>
								<td><img src="<?php echo wpl_pdf::get_fixed_url($user_data['wpl_user']['main_email_url']); ?>" class="email_url" /></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>

					<?php if(isset($user_data['wpl_user']['second_email_url'])): ?>
					<div class="second_email">
						<table>
							<tr>
								<td><img src="<?php echo wpl_pdf::get_fixed_url(wpl_global::get_wpl_asset_url("img/pdf/email.png")); ?>" /></td>
								<td><img src="<?php echo wpl_pdf::get_fixed_url($user_data['wpl_user']['second_email_url']); ?>" class="email_url" /></td>
							</tr>
						</table>
					</div>
					<?php endif; ?>
				</div>
			</td>
		</tr>
	</table>
	<?php if($is_multi_agent) echo '</div>'; ?>
<?php endforeach; ?>