<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Energy Tag Library
 * @author Francis <francis@realtyna.com>
 * @package WPL PRO
 */
class wpl_energy_tag
{
    public $pid;
    public $image_width;
    public $image_height;
    public $energy_value;
    public $text_font_size;
    public $bar_width;
    public $first_bar_length;
    public $length_increment;
    public $vertical_distance;
    public $peak;
    public $energy_font_size;
    public $texts;
    public $format = 'url';
	public $show_energy_value;
    /**
     * Creates energy tag
     * @author Francis <francis@realtyna.com>
     * @return string $image_url URL of energy tag image
     */
	public function create_energy_tag()
	{

		$image = imagecreate($this->image_width, $this->image_height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 0, 0, 0);
		$dark_grey = imagecolorallocate($image, 30, 30, 30);
		
		/** creating colors for each energy consumption range **/
		$color_a = imagecolorallocate($image, 50, 152, 55);
		$color_b = imagecolorallocate($image, 87, 175, 55);
		$color_c = imagecolorallocate($image, 198, 211, 0);
		$color_d = imagecolorallocate($image, 242, 229, 0);
		$color_e = imagecolorallocate($image, 255, 203, 3);
		$color_f = imagecolorallocate($image, 243, 151, 57);
		$color_g = imagecolorallocate($image, 228, 37, 31);
		
		/** creating a box for the image **/
		imagerectangle($image, 1, 1,$this->image_width-1, $this->image_height-1, $black);
		
		/** setting the width of each bar **/
		$width = ($this->bar_width > 0 ? $this->bar_width : 20);
		
		/** setting the length of the first bar **/
		$length = ($this->first_bar_length > 0 ? $this->first_bar_length : 70);
		
		/** setting incremet value for the new bars **/
		$increment = ($this->length_increment > 0 ? $this->length_increment : 10);
		
		/** setting the length of each bar peak **/
		$peak = ($this->peak > 0 ? $this->peak : 10);
		
		/** distance between bars **/
		$distance = ($this->vertical_distance > 0 ? $this->vertical_distance : 3);
		
		/** size of font for letters A to G **/
		$letter_font_size = $this->text_font_size + (0.3 * $this->text_font_size);
		
		/** margin from beginning of each bar **/
		$start_text_margin = $this->text_font_size / 2;
		
		/** margin for writng letters A to G **/
		$start_letter_margin = $this->first_bar_length + $peak - 2 * $this->text_font_size;

		/** font for texts **/
		$font = wpl_global::get_wpl_root_path().'views'.DS.'activities'.DS.'energy_tag'.DS.'arial.ttf';

		/** setting the start point of drawing **/
		$start_point_x = 8;
		$start_point_y = 38;
		$first_start_point_y = 38;
		
		/** adding the top texts **/
		$text = $this->texts[7];
						
		/** writing bold with normal font to reduce package size **/
		imagettftext($image, $this->text_font_size, 0, ($start_point_x), ($start_point_y - $width), $black, $font, $text);
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + 1), ($start_point_y - $width), $black, $font, $text);
		$text = $this->texts[8];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x), ($start_point_y - $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		
		/** calculating the end point for the first bar **/
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		
		/** creating a filled rectangle with the first color for teh first bar **/
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_a);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x, $end_point_y);

		/** creating the triangle peak **/
		imagefilledpolygon($image,$points, 3, $color_a);
								
		/** writing text to the image **/
		$text = $this->texts[0];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'A';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$length += $increment;
		$start_point_y += $width + $distance;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_b);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image,$points, 3, $color_b);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[1];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'B';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$length += $increment;
		$start_point_y += $width + $distance;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_c);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image,$points, 3, $color_c);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[2];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'C';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$length += $increment;
		$start_point_y += $width + $distance;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_d);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image, $points, 3, $color_d);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[3];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'D';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$length += $increment;
		$start_point_y += $width + $distance;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_e);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image, $points, 3, $color_e);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[4];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'E';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$length += $increment;
		$start_point_y += $width + $distance;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_f);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image,$points, 3, $color_f);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[5];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
		$text = 'F';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $black, $font, $text);
		$start_point_y += $width + $distance;
		$length += $increment;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		imagefilledrectangle($image, $start_point_x, $start_point_y, $end_point_x, $end_point_y, $color_g);
		$points = array($end_point_x, $start_point_y, ($end_point_x + $peak), ($start_point_y + $width / 2), $end_point_x,$end_point_y);
		imagefilledpolygon($image,$points, 3, $color_g);
		
		/** writing text to the image **/
		$start_letter_margin += $increment;
		$text = $this->texts[6];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x + $start_text_margin), ($start_point_y + $width / 2 + $this->text_font_size / 2), $white, $font, $text);
		$text = 'G';
		imagettftext($image, $letter_font_size, 0, ($start_point_x + $start_letter_margin), ($start_point_y + $width / 2 + $letter_font_size / 2), $white, $font, $text);
		$start_point_y += $width + $distance;
		$length += $increment;
		$end_point_x = $start_point_x + $length;
		$end_point_y = $start_point_y + $width;
		
		/** adding the top texts **/
		$text = $this->texts[9];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x), ($end_point_y), $black, $font, $text);
		$text = $this->texts[10];
		imagettftext($image, $this->text_font_size, 0, ($start_point_x), ($end_point_y + $width / 2 + $this->text_font_size / 2), $black, $font, $text);
	
		/** creating the reverse pointer the starting point of the pointer rectangle **/
		$pointer_margin = $this->image_width - 50;
		
		/** the width of the pointer **/
		$pointer_width = 20;
		
		/** the length of the pointer rectangle **/
		$pointer_length = 35;
		
		/** starting point of pointer height **/
		$pointer_height = 100;
		
		if ($this->energy_value <= 50)
			$pointer_height = $first_start_point_y;
		else if ($this->energy_value > 50 && $this->energy_value <= 90)
			$pointer_height = $first_start_point_y + $width + $distance;
		else if ($this->energy_value > 90 && $this->energy_value <= 150)
			$pointer_height = $first_start_point_y + 2 * ($width + $distance);
		else if ($this->energy_value > 151 && $this->energy_value <= 230)
			$pointer_height = $first_start_point_y + 3 * ($width + $distance);
		else if ($this->energy_value > 231 && $this->energy_value <= 330)
			$pointer_height = $first_start_point_y + 4 * ($width + $distance);
		else if ($this->energy_value > 331 && $this->energy_value <= 450)
			$pointer_height = $first_start_point_y + 5 * ($width + $distance);
		else if ($this->energy_value > 450)
			$pointer_height = $first_start_point_y + 6 * ($width + $distance);
			
		imagefilledrectangle($image, $pointer_margin, $pointer_height, $pointer_margin + $pointer_length, $pointer_height + $pointer_width,$dark_grey);
		$points = array($pointer_margin, $pointer_height, $pointer_margin - $peak, $pointer_height + $pointer_width / 2, $pointer_margin, $pointer_height + $pointer_width);
		
		/** creating the triangle peak **/
		imagefilledpolygon($image, $points, 3, $dark_grey);
		$text = $this->energy_value;

        if($this->show_energy_value)
        {
            imagettftext($image, $this->energy_font_size, 0, $pointer_margin + 2, $pointer_height + $this->energy_font_size + 4, $white, $font, $text);
        }

		$path = wpl_items::get_path($this->pid).'energy_tag.png';
		imagepng($image, $path);
        
		if($this->format == 'url') return "<img src='" . wpl_items::get_folder($this->pid)."/energy_tag.png" . "' width='$this->image_width' height='$this->image_height' />";
	}
}