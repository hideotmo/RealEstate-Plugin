<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.render');

/**
 * Payments Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 25/10/2014
 * @package WPL PRO
 */
class wpl_payments
{
    /**
     * WPL format path of Gateways
     * @var string
     */
    public $gateways_wplpath = 'libraries.payments.gateways';
    
    /**
     * WPL format path of Assets
     * @var string
     */
    public $assets_wplpath = 'libraries.payments.assets';
    
    /**
     * WPL Gateways
     * @var array
     */
    public $gateways = array();
    
    /**
     * Full path of Payments directory
     * @var string
     */
    public $payments_path;
    
    /**
     * Full path of Gateways directory
     * @var string
     */
    public $gateways_path;
    
    /**
     * Full path of Assets directory
     * @var string
     */
    public $assets_path;
    
    /**
     * Full URL of Payments directory
     * @var string
     */
    public $payments_url;
    
    /**
     * Full URL of Gateways directory
     * @var string
     */
    public $gateways_url;
    
    /**
     * @author Howard <howard@realtyna.com>
     */
    public function __construct()
    {
        $this->payments_path = WPL_ABSPATH. 'libraries' .DS. 'payments' .DS;
        $this->gateways_path = $this->payments_path. 'gateways' .DS;
        $this->assets_path = $this->payments_path. 'assets' .DS;
        
        $this->payments_url = wpl_global::get_wpl_url(). 'libraries/payments/';
        $this->gateways_url = $this->payments_url. 'gateways/';
        
        $this->gateways = $this->get_gateways();
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param type $gateway
     * @return \class_name|boolean
     */
    public function get($gateway = 'paypal')
    {
        $gateway = strtolower($gateway);
        $wplpath = $this->wplpath($gateway, NULL, $gateway);
        
        /** Gateway object **/
        _wpl_import($wplpath, true);
        $class_name = 'wpl_payments_'.$gateway;
        
        if(class_exists($class_name)) return new $class_name();
        else return false;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @return array
     */
    public function get_gateways()
    {
        $gateways = wpl_folder::folders($this->gateways_path);
        
        $wplpath = $this->assets('gateways_sort');
        $path = _wpl_import($wplpath, true, true);
        
        $sorted = array();
        include_once $path;
        return $sorted;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param string $gateway
     * @return array
     */
    public function get_settings($gateway = NULL)
    {
        $encoded = wpl_global::get_setting('wpl_payments');
        $settings = json_decode($encoded, true);
        
        if(!$gateway) return $settings;
        elseif(isset($settings[$gateway])) return $settings[$gateway];
        else array();
    }
    
    /**
     * Returns request string
     * @author Howard <howard@realtyna.com>
     * @param array $vars
     * @return string
     */
    public function get_request_string($vars)
    {
        $string = '';
        foreach($vars as $var=>$val) $string .= '&'.$var.'='.urlencode(stripslashes($val));
        
        return $string;
    }
    
    /**
     * @author Howard <howard@realtyna.com>
     */
    public function log()
    {
    }
    
    /**
     * Validates paid amount with transaction amount
     * @author Howard <howard@realtyna.com>
     * @param int $transaction_id
     * @param double $amount
     * @param int $status
     * @param int $unit_id
     * @return boolean
     */
    public function validate($transaction_id, $amount, $status = 1, $unit_id = 260)
    {
        if(!trim($status)) return false;
        
        $validate = false;
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        /** Convert paid amount to USD **/
        $amount_si = wpl_units::convert($amount, $unit_id, 260);
        
        /** Compare **/
        if($amount_si >= $transaction['amount_si']) $validate = true;
        
        return $validate;
    }
    
    /**
     * @author Howard <howard@realtyna.com>
     * @param int $transaction_id
     */
    public function payment_form($transaction_id = NULL)
    {
        if($transaction_id)
        {
            $this->transaction_id = $transaction_id;
            $this->transactions = new wpl_transactions($this->transaction_id);
            $this->transaction = $this->transactions->get();
            
            $this->amount = wpl_render::render_price($this->transaction['amount'], $this->transaction['unit_id']);
            $this->system = json_decode($this->transaction['system'], true);
        }
        
        $wplpath = $this->wplpath('default', 'payment');
        $path = _wpl_import($wplpath, true, true);
        
        $settings = $this->get_settings($this->get_gateway_key());
        include $path;
    }
    
    /**
     * @author Howard <howard@realtyna.com>
     */
    public function settings_form()
    {
        $wplpath = $this->wplpath('default', 'settings');
        $path = _wpl_import($wplpath, true, true);
        
        $settings = $this->get_settings($this->get_gateway_key());
        include_once $path;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @return string
     */
    public function get_gateway_label()
    {
        return $this->gateway_label;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @return int
     */
    public function get_gateway_id()
    {
        return $this->gateway_id;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @return string
     */
    public function get_gateway_key()
    {
        return $this->gateway_key;
    }
    
    /**
     * Returns Gateway class
     * @author Howard <howard@realtyna.com>
     * @return string
     */
    public function get_gateway_class_name()
    {
        return 'wpl_payments_'.$this->gateway_key;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param type $section
     * @param string $file
     * @param string $gateway
     * @return string
     */
    public function url($section = NULL, $file = NULL, $gateway = NULL)
    {
        if(!$gateway) $gateway = $this->get_gateway_key();
        if(!$file) $file = 'default.php';
        
        return $this->gateways_url .$gateway .'/'. $section .'/'. $file;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param string $section
     * @param string $file
     * @param string $gateway
     * @return string
     */
    public function path($section = NULL, $file = NULL, $gateway = NULL)
    {
        if(!$gateway) $gateway = $this->get_gateway_key();
        if(!$file) $file = 'default.php';
        
        return $this->gateways_path . $gateway .DS. $section .DS. $file;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @param string $section
     * @param string $gateway
     * @return string
     */
    public function wplpath($file = NULL, $section = NULL, $gateway = NULL)
    {
        if(!$gateway) $gateway = $this->get_gateway_key();
        if(!$file) $file = 'default';
        
        return $this->gateways_wplpath.'.'.$gateway.($section ? '.'.$section : '').'.'.$file;
    }
    
    /**
     * 
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @param string $section
     * @return string
     */
    public function assets($file = NULL, $section = NULL)
    {
        if(!$file) $file = 'default';
        
        return $this->assets_wplpath.($section ? '.'.$section : '').'.'.$file;
    }
    
    /**
     * 
     * @param string $key
     * @return string
     */
    public function getter($key)
    {
        $value = NULL;
        
        if($key == 'item_name') $value = (isset($this->transaction['title']) and trim($this->transaction['title'])) ? $this->transaction['title'] : 'Products';
        elseif($key == 'item_number') $value = 1;
        elseif($key == 'business') $value = (isset($this->settings['account']) and trim($this->settings['account'])) ? $this->settings['account'] : NULL;
        elseif($key == 'username') $value = (isset($this->settings['username']) and trim($this->settings['username'])) ? $this->settings['username'] : NULL;
        elseif($key == 'password') $value = (isset($this->settings['password']) and trim($this->settings['password'])) ? $this->settings['password'] : NULL;
        elseif($key == 'signature') $value = (isset($this->settings['signature']) and trim($this->settings['signature'])) ? $this->settings['signature'] : NULL;
        elseif($key == 'amount') $value = (isset($this->transaction['amount']) and trim($this->transaction['amount'])) ? $this->transaction['amount'] : '0';
        elseif($key == 'title') $value = (isset($this->transaction['title']) and trim($this->transaction['title'])) ? $this->transaction['title'] : NULL;
        elseif($key == 'currency_code')
        {
            $unit_id = (isset($this->transaction['unit_id']) and trim($this->transaction['unit_id'])) ? $this->transaction['unit_id'] : '260';
            $unit = wpl_units::get_unit($unit_id);
            
            $value = (isset($unit['extra']) and trim($unit['extra'])) ? $unit['extra'] : 'USD';
        }
        elseif($key == 'custom') $value = base64_encode(json_encode(array('transaction_id'=>$this->transaction['id'])));
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_getter', array('value'=>$value, 'key'=>$key, 'transaction'=>$this->transaction, 'gateway_key'=>$this->get_gateway_key())));
        
        return $value;
    }
    
    /**
     * 
     * @param string $method
     * @return string
     */
    public static function frontend($method = 'cancel')
    {
        $payment_main_page = wpl_global::get_setting('payments_main_page');
        
        if(!trim($payment_main_page))
        {
            $url = wpl_sef::get_wpl_permalink(true);
            $nosef = wpl_sef::is_permalink_default();
            
            if(!$nosef) $url = trim($url, '/').'/v/payments/';
            else $url = wpl_global::add_qs_var('wplview', 'payments', $url);
            
            $url = wpl_global::add_qs_var('wplmethod', $method, $url);
        }
        else
        {
            $url = wpl_sef::get_page_link($payment_main_page);
            $url = wpl_global::add_qs_var('wplmethod', $method, $url);
        }
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_frontend_url', array('url'=>$url, 'method'=>$method)));
        
        return $url;
    }
}

/**
 * Gateways Interface
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 24/10/2014
 * @package WPL PRO
 */
interface wpl_payments_interface
{
    public function get_settings($gateway);
    public function settings_form();
    public function payment_form($transaction_id);
    public function validate($transaction_id, $amount, $status, $unit_id);
    public function log();
    public function get_gateway_label();
    public function get_gateway_id();
    public function get_gateway_key();
}

/**
 * Transactions Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 26/10/2014
 * @package WPL PRO
 */
class wpl_transactions
{
    /**
     * Transaction ID
     * @var int
     */
    public $id = 0;
    
    /**
     * Current User ID
     * @var int
     */
    public $user_id = 0;
    
    /**
     * @author Howard <howard@realtyna.com>
     * @param int $id
     * @param int $user_id
     */
    public function __construct($id = 0, $user_id = 0)
    {
        $this->id = $id;
        $this->user_id = $user_id;
    }
    
    /**
     * Get Transaction Info
     * @author Howard <howard@realtyna.com>
     * @param int $id
     * @return array
     */
    public function get($id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        $query = "SELECT * FROM `#__wpl_transactions` WHERE `id`='$id'";
        return wpl_db::select($query, 'loadAssoc');
    }
    
    /**
     * Create a new Transaction
     * @author Howard <howard@realtyna.com>
     * @param array $values
     * @return int
     */
    public function create($values = array())
    {
        $user_id = isset($values['user_id']) ? $values['user_id'] : 0;
        $mode = isset($values['mode']) ? $values['mode'] : 0;
        $type = isset($values['type']) ? $values['type'] : '';
        $amount = isset($values['amount']) ? $values['amount'] : 0;
        $unit_id = isset($values['unit_id']) ? $values['unit_id'] : 260; #USD
        $amount_si = isset($values['amount_si']) ? $values['amount_si'] : wpl_units::convert($amount, $unit_id, 260);
        $status = isset($values['status']) ? $values['status'] : 2;
        $creation_date = isset($values['creation_date']) ? $values['creation_date'] : date("Y-m-d H:i:s");
        $title = isset($values['title']) ? $values['title'] : '';
        $description = isset($values['description']) ? $values['description'] : '';
        $params = (isset($values['params']) and is_array($values['params'])) ? json_encode($values['params']) : json_encode(array());
        $system = (isset($values['system']) and is_array($values['system'])) ? json_encode($values['system']) : json_encode(array());
        
        $query = "INSERT INTO `#__wpl_transactions` (`user_id`,`mode`,`type`,`amount`,`amount_si`,`unit_id`,`status`,`creation_date`,`title`,`description`,`params`,`system`)"
                . "VALUES ('$user_id','$mode','$type','$amount','$amount_si','$unit_id','$status','$creation_date','$title','$description','$params','$system')";
        
        $id = wpl_db::q($query, "INSERT");
        
        /** trigger event **/
        wpl_global::event_handler('transaction_created', array('id'=>$id));
        
        return $id;
    }
    
    /**
     * Update a Transaction
     * @author Howard <howard@realtyna.com>
     * @param array $values
     * @param int $id
     * @return int
     */
    public function update($values = array(), $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        $possible_columns = wpl_db::columns('wpl_transactions');
        
        $q = '';
        foreach($values as $column=>$value)
        {
            if(!in_array($column, $possible_columns)) continue;
            $q .= "`$column`='".wpl_db::escape($value)."',";
        }
        
        $q = trim($q, ',');
        $query = "UPDATE `#__wpl_transactions` SET $q WHERE `id`='$id'";
        
        return wpl_db::q($query, "UPDATE");
    }
    
    /**
     * Save (Insert/Update) a Transaction
     * @author Howard <howard@realtyna.com>
     * @param array $values
     * @param int $id
     * @return int
     */
    public function save($values, $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        $exists = wpl_db::exists($id, 'wpl_transactions');
        
        if($exists) return $this->update($values, $id);
        else return $this->create($values);
    }
    
    /**
     * Transaction is done.
     * @author Howard <howard@realtyna.com>
     * @param int $status
     * @param string $timestamp
     * @param int $id
     * @return boolean
     */
    public function done($status, $timestamp = NULL, $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        /** Transaction not found **/
        if(!wpl_db::exists($id, 'wpl_transactions')) return false;
        
        /** Data validations **/
        if(!trim($timestamp)) $timestamp = date('Y-m-d H:i:s');
        if(!is_numeric($status)) $status = (int) $status;
        
        $query = "UPDATE `#__wpl_transactions` SET `status`='$status', `paid_date`='$timestamp' WHERE `id`='$id'";
        wpl_db::q($query, 'UPDATE');
        
        /** trigger event **/
        wpl_global::event_handler('transaction_done', array('id'=>$id));
            
        if(!$status)
        {
            /** trigger event **/
            wpl_global::event_handler('transaction_failed', array('id'=>$id));
            return false;
        }
        
        if($status == 1)
        {
            /** Run system functions **/
            $transaction = $this->get($id);
            
            /** Add plus/minus records in ewallet system **/
            if($transaction['type'] != 'ewallet')
            {
                $payments = new wpl_payments();
                $ewallet = $payments->get('ewallet');
                
                /** If gateway is not ewallet **/
                if($transaction['gateway'] != 'ewallet') $ewallet->plus($id);
                $ewallet->minus($id);
            }
            
            $systems = json_decode($transaction['system'], true);
            
            if(isset($systems['callbacks']))
            {
                foreach($systems['callbacks'] as $system)
                {
                    if(isset($system['class_path']) and trim($system['class_path'])) _wpl_import($system['class_path']);
                    if(!class_exists($system['class_name'])) continue;

                    $params = isset($system['function_params']) ? $system['function_params'] : array();
                    $class_name = $system['class_name'];
                    $obj = new $class_name();

                    call_user_func_array(array($obj, $system['function_name']), $params);
                }
            }
            
            /** trigger event **/
            wpl_global::event_handler('transaction_succeed', array('id'=>$id));
        }
        
        return true;
    }
    
    /**
     * Set Gateway of transaction
     * @author Howard <howard@realtyna.com>
     * @param string $gateway
     * @param int $id
     * @return boolean
     */
    public function gateway($gateway, $id = NULL)
    {
        /** Set ID **/
        if(is_null($id)) $id = $this->id;
        
        /** Transaction not found **/
        if(!wpl_db::exists($id, 'wpl_transactions')) return false;
        
        $query = "UPDATE `#__wpl_transactions` SET `gateway`='$gateway' WHERE `id`='$id'";
        wpl_db::q($query, 'UPDATE');
        
        return true;
    }
}