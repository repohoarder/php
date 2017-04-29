<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Wizardforms {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
		public function createWizardInput($config)
		{
			$default = isset($config['default']) ? $config['default'] : '';
			return "<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">{$config['title']}:</label>
						<div class=\"controls\">
							<input type=\"text\" name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\" value=\"$default\"/>
							<span class=\"help-block\" id=\"help{$config['fieldname']}\">{$config['helpblock']}</span>
						</div>
					</div>";
		}
		public function createWizardPassword($config)
		{
			return "<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">Password</label>
						<div class=\"controls\">
							<input type=\"password\" placeholder=\"Password\" class=\"span8\" id=\"pass_check\" name=\"pass_check\" style=\"width:100%\"/>
							<div id=\"pass_progress\" class=\"progress progress-danger\" style=\"width:100%\">
							<div class=\"bar\" style=\"width: 0\"></div>
						</div>
					</div>";
		}
		function createWizardTextarea($config){
			$default = isset($config['default']) ? $config['default'] : '';
			return "
				<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">{$config['title']}:</label>
						<div class=\"controls\">
						<textarea id=\"{$config['fieldname']}\" class=\"span8\" rows=\"3\" cols=\"10\" name=\"{$config['fieldname']}\">$default</textarea>
						<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
		}
		function createWizardDropDown($config){
			$default = isset($config['default']) ? $config['default'] : '';
			$return ="
				<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">{$config['title']}:</label>
						<div class=\"controls\">";
			$add_top_option = isset($config['add_top_option']) ? $config['add_top_option'] : true;			
			$return .="<select name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\"  class=\"multiselect\">";
			$return .= ($add_top_option) ? "<option value=''>Select One</option>" : "";
			foreach ($config['valuearray'] as $value=>$title):
					$c = $default == $value ? ' selected="selected"':'';
					$return .="<option value='$value'$c>$title</option>";
			endforeach;
			$return .="</select>
					<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
			 return $return;
		}
		function createWizardCheckBox($config){
			
			return "
				<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">&nbsp;</label>
						<div class=\"controls\">
					<label class=\"checkbox\">
							<input type=\"checkbox\" id=\"{$checkboxes['fieldname']}\" name=\"{$checkboxes['fieldname']}\" value=\"1\">
								{$config['title']}
						</label>
					</div>
			    </div>";
		
						
		}
		function createWizardHidden($config){
			$default = isset($config['default']) ? $config['default'] : '';
			return "<input type='hidden' id='{$config['fieldname']}' name='{$config['fieldname']}' value=\"$default\">";
		}
		function createWizardClassInput($config,$class){
			$default = isset($config['default']) ? $config['default'] : '';
			return "
				<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">{$config['title']}:</label>
						<div class=\"controls\">
						<input type=\"text\" name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\" class=\"$class\" value=\"$default\"/>
						<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
			
		}
		function createWizardDollarField($config){
			$default = isset($config['default']) ? $config['default'] : '';
			return "
				<div class=\"formSep control-group\">
					<label for=\"v_username\" class=\"control-label\">{$config['title']}:</label>
						<div class=\"controls\">
						<span class=\"add-on\">$</span>
						<input type=\"text\" class=\"span3\" size=\"16\"  name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\">
						<span class=\"add-on\">.00</span>
						<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
		}
}