<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-payments-bank-transactions-container">
    <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
    <div class="pagination-wp">
        <?php echo $this->pagination->show(); ?>
    </div>
    <?php endif; ?>
    <div class="wpl_item_list"><div class="wpl_show_message"></div></div>
    <table class="widefat page">
        <thead>
            <tr>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('ID', 'wpl'), 'id'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('User', 'wpl'), 'user_id'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Amount', 'wpl'), 'amount'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Status', 'wpl'), 'status'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Paid Date', 'wpl'), 'paid_date'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Title', 'wpl'), 'title'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Gateway', 'wpl'), 'gateway'); ?></th>
                <th class="manage-column" scope="col"><?php echo wpl_global::order_table(__('Bank Receipt', 'wpl'), 'gtid'); ?></th>
                <th class="manage-column wpl_manager_td" scope="col"><?php echo __('Actions', 'wpl'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!count($this->transactions)): ?>
            <tr>
                <td colspan="9" class="wpl-text-center"><?php echo __('No Transaction found!', 'wpl'); ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach($this->transactions as $transaction): $user_data = wpl_users::get_user($transaction->user_id); ?>
            <tr id="item_row_<?php echo $transaction->id; ?>">
                <td class="wpl_id_td"><?php echo $transaction->id; ?></td>
                <td class="wpl_user_td">
                    <?php if($transaction->user_id and $user_data): ?>
                    <a href="<?php echo wpl_global::add_qs_var('sf_select_user_id', $transaction->user_id); ?>"><?php echo (trim($user_data->wpl_data->first_name) != '' ? $user_data->wpl_data->first_name.' '.$user_data->wpl_data->last_name : $user_data->user_login); ?></a>
                    <?php elseif($transaction->user_id): ?>
                    <span><?php echo __('Unknown', 'wpl'); ?></span>
                    <?php else: ?>
                    <span><?php echo __('Guest', 'wpl'); ?></span>
                    <?php endif; ?>
                </td>
                <td class="wpl_price_td wpl_price_td<?php echo $transaction->mode; ?>"><?php echo ($transaction->mode == 1 ? '+ ' : ($transaction->mode == 2 ? '- ' : '')).wpl_render::render_price($transaction->amount, $transaction->unit_id); ?></td>
                <td class="wpl_status_td wpl_status_td<?php echo $transaction->status; ?>"><?php echo $this->status_labels[$transaction->status]; ?></td>
                <td title="<?php echo $transaction->paid_date; ?>"><?php echo current(explode(' ', $transaction->paid_date)); ?></td>
                <td title="<?php echo strip_tags($transaction->description); ?>"><?php echo $transaction->title; ?></td>
                <td><?php echo $transaction->gateway; ?></td>
                <td><?php echo $transaction->gtid; ?></td>
                <td class="wpl_manager_td">
                    <?php if($transaction->status == '1'): ?>
                    <span class="action-btn icon-enabled disable cursor-none" title="<?php echo __('Already Confirmed.', 'wpl'); ?>"></span>
                    <?php elseif($transaction->status == '0'): ?>
                    <span class="action-btn icon-disabled disable cursor-none" title="<?php echo __('Already Rejected.', 'wpl'); ?>"></span>
                    <?php else: ?>
                    <span id="icon_enabled<?php echo $transaction->id; ?>" class="action-btn icon-enabled" onclick="wpl_transaction_bank_confirm(<?php echo $transaction->id; ?>, 1);" title="<?php echo __('Confirm', 'wpl'); ?>"></span>
                    <span id="icon_disabled<?php echo $transaction->id; ?>" class="action-btn icon-disabled" onclick="wpl_transaction_bank_confirm(<?php echo $transaction->id; ?>, 0);" title="<?php echo __('Reject', 'wpl'); ?>"></span>
                    <span id="wpl_bank_receipt_ajax_loader<?php echo $transaction->id; ?>"></span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
    <div class="pagination-wp">
        <?php echo $this->pagination->show(); ?>
    </div>
    <?php endif; ?>
</div>