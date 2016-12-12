<?php
/**
 *  Style Selector
 */
    echo '
    		<div id="style_selector">
				<div id="style_selector_handle" class="closed"></div>
				<div class="styles_selector_main_title">'.__('Style Selector', 'wplt' ).'</div>
				<div class="styles_selector_boxes">
					<div class="styles_selector_title">'.__('Select the layout', 'wplt' ).'</div>
					<div class="styles_selector_cont">
						<select id="options_layout">
			                <option value="1" '.((isset($this->theme_options['layout']) and $this->theme_options['layout']==='1' ) ? 'selected="selected"' : '').'>'.__('Wide', 'wplt').'</option>
			                <option value="2" '.((isset($this->theme_options['layout']) and $this->theme_options['layout']==='2' ) ? 'selected="selected"' : '').'>'.__('Boxed', 'wplt').'</option>
			            </select>
					</div>
					<div class="styles_selector_title">'.__('Choose the theme', 'wplt' ).'</div>
					<div class="styles_selector_cont">
						<select id="options_theme">
			                <option value="'.home_url('/').'" '.((isset($this->theme_options['theme']) and $this->theme_options['theme']==='1' ) ? 'selected="selected"' : '').'>'.__('Light', 'wplt').'</option>
			                <option value="'.home_url('/').'?theme=dark" ';
    if(wpl_theme::getVar('theme') =='dark' or ($this->theme_options['theme']==='dark'))
    {
        echo 'selected="selected"';
    }
    echo '>'.__('Dark', 'wplt').'</option>
			            </select>
					</div>
				</div>
				<div class="styles_selector_boxes">
					<div class="styles_selector_title">'.__('Preset colors', 'wplt' ).'</div>
					<div class="styles_selector_cont">
						<div id="preset_0">'.__('No Preset', 'wplt').'</div>';
    for($i=1;$i<=5;$i++)
    {
        echo '<div id="preset_'.$i.'"></div>';
    }
    echo '</div>
				</div>
				<div class="styles_selector_boxes pattern">
					<div class="styles_selector_title">'.__('Select the pattern', 'wplt' ).'</div>
					<div class="styles_selector_cont">';
    for($i=1;$i<=10;$i++)
    {
        echo '<div id="pattern_'.$i.'"></div>';
    }
    echo '</div>
				</div>
			</div>';
?>