<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');
_wpl_import('libraries.addon_pro');
_wpl_import('libraries.pdf');

class wpl_features_controller extends wpl_controller
{
	public $tpl_path = 'views.basics.features.tmpl';
	public $tpl;
	
	public function display()
	{
		if(!wpl_global::check_addon('pro'))
        {
            echo __('PRO Addon must be installed for this!', 'wpl');
            exit;
        }

		$function = wpl_request::getVar('wpl_function', 'show');
		if($function == 'show') $this->show();
	}
	
	private function show()
	{
        /** Bypass server limitation **/
        @ini_set('memory_limit', '-1');
        set_time_limit(0);
        
		$this->tpl = 'print';
		$this->settings = wpl_settings::get_settings(9);

		/** Get Properties **/
		wpl_request::setVar('wplmethod', 'get_listings');
        
        _wpl_import('views.frontend.property_listing.wpl_get');
        $model = new wpl_property_listing_controller();
        $this->wpl_properties = $model->display();

        if(!is_array($this->wpl_properties))
        {
        	echo __('Error loading print view!', 'wpl');
        	exit;
        }
        
        /** import tpl **/
        $html = parent::render($this->tpl_path, $this->tpl, false, true);
        
        if($this->settings['pdf_results_page_method'] == 'pdf')
        {
            global $pdfflyer;
            $pdfflyer = true;

            $wplpdf = new wpl_pdf();
            $pdf = $wplpdf->get_PDF_object();

            try
            {
                $pdf->WriteHTML($html);
                $pdf->Output('listings.pdf', 'I');
            }
            catch(exception $error)
            {
                echo $error;
            }
        }
        else
        {
            echo $html;
        }
        
		exit;
	}
}