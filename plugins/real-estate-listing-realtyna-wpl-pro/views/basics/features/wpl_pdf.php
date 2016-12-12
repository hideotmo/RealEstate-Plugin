<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.images');
_wpl_import('libraries.activities');
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
        $this->tpl = 'pdf';
        
        /** Bypass server limitation **/
        @ini_set('memory_limit', '-1');
        set_time_limit(0);
        
		/** property listing model **/
		$this->model = new wpl_property;
		$this->pid = wpl_request::getVar('pid', 0);
		
		$listing_id = wpl_request::getVar('mls_id', 0);
		if(trim($listing_id)) $this->pid = wpl_property::pid($listing_id);
		
		$property = $this->model->get_property_raw_data($this->pid);
		
		/** no property found **/
		if(!$property or $property['finalized'] == 0 or $property['confirmed'] == 0 or $property['deleted'] == 1)
		{
			/** import message tpl **/
			echo __("No property found or it's not available now!", 'wpl');
			exit;
		}
		
		$this->pdf_fields = $this->model->get_pdf_fields('', $property['kind']);
		$this->pdf_categories = wpl_flex::get_categories('', '', " AND `enabled`>='1' AND `kind`='".$property['kind']."' AND `pdf`='1'");
		$wpl_properties = array();
		
		/** define current index **/
		$wpl_properties['current']['data'] = (array) $property;
		$wpl_properties['current']['raw'] = (array) $property;
        
        $find_files = array();
		$rendered_fields = $this->model->render_property($property, $this->pdf_fields, $find_files, true);
        
		$wpl_properties['current']['rendered_raw'] = $rendered_fields['ids'];
        $wpl_properties['current']['materials'] = $rendered_fields['columns'];
		
		foreach($this->pdf_categories as $pdf_category)
		{
			$pdf_cat_fields = $this->model->get_pdf_fields($pdf_category->id, $property['kind']);
			$wpl_properties['current']['rendered'][$pdf_category->id]['self'] = (array) $pdf_category;
			$wpl_properties['current']['rendered'][$pdf_category->id]['data'] = $this->model->render_property($property, $pdf_cat_fields);
		}
		
		$wpl_properties['current']['items'] = wpl_items::get_items($this->pid, '', $property['kind'], '', 1);
        /** property location text **/ $wpl_properties['current']['location_text'] = $this->model->generate_location_text((array) $property);
		/** property full link **/ $wpl_properties['current']['property_link'] = $this->model->get_property_link((array) $property);
        /** property page title **/ $wpl_properties['current']['property_page_title'] = $this->model->update_property_page_title($property);
        /** property title **/ $wpl_properties['current']['property_title'] = $this->model->update_property_title($property);
        
		/** apply filters (This filter must place after all proccess) **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('property_listing_after_render', array('wpl_properties'=>$wpl_properties)));
		
		$this->wpl_properties = $wpl_properties;
		$this->kind = $property['kind'];
		$this->property = $wpl_properties['current'];
        
		/** updating the visited times and etc **/
		wpl_property::property_visited($this->pid);
		
        /** WPL Settings **/
        $this->settings = wpl_global::get_settings();
        
        global $pdfflyer;
        $pdfflyer = true;
        
		$wplpdf = new wpl_pdf();
        
		$wplpdf->page_title = $this->property['property_page_title'];
		$wplpdf->pdf_subject = $this->property['property_page_title'];
		$wplpdf->pdf_header_title = $this->property['property_page_title'];
		
        $pdf = $wplpdf->get_PDF_object();
        
        /** import tpl **/
		$html = parent::render($this->tpl_path, $this->tpl, false, true);
        
        try
        {
            $pdf->WriteHTML($html);
            
            $file_name = 'listing_'.$this->property['data']['mls_id'].'.pdf';
            $pdf->Output($file_name, 'I');
        }
        catch(exception $error)
        {
            echo $error;
        }
		
		exit;
	}
}