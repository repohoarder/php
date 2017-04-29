<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Formbuild {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
		public function createInput($config)
		{
			
			return "<div class=\"row-fluid\">
						<div class=\"span8\">
							<label>{$config['title']} <span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\" class=\"span12\" />
							<span class=\"help-block\" id=\"help{$config['fieldname']}\">{$config['helpblock']}</span>
						</div>
					</div>";
		}
		public function createPassword($config)
		{
			return "<div class=\"row-fluid\">
						<div class=\"span8\">
							<label>Password <span class=\"f_req\">* Enter new password to change</span></label>
							<input type=\"password\" placeholder=\"Password\" class=\"span8\" id=\"pass_check\" name=\"pass_check\" style=\"width:100%\"/>
							<div id=\"pass_progress\" class=\"progress progress-danger\" style=\"width:100%\">
							<div class=\"bar\" style=\"width: 0\"></div>
						</div>
					</div>";
		}
		function createTextarea($config){
			return "
				<div class=\"row-fluid\">
					<div class=\"span8\">
						<label>{$config['title']}</label>
						<textarea id=\"{$config['fieldname']}\" class=\"span12\" rows=\"3\" cols=\"10\" name=\"{$config['fieldname']}\"></textarea>
						<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
		}
		function createDropDown($config){
			$return ="
				<div class=\"row-fluid\">
					<div class=\"span8\">
						<label>{$config['title']}</label>";
			$add_top_option = isset($config['add_top_option']) ? $config['add_top_option'] : true;	
			$multi = isset($config['multi']) ? " class=\"multiselect\"":'';
			$return .="<select name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\" $multi>";
			$return .= ($add_top_option) ? "<option value=''>Select One</option>" : "";
			foreach ($config['valuearray'] as $value=>$title):
					$return .="<option value='$value'>$title</option>";
			endforeach;
			$return .="</select>
					<span class=\"help-block\">{$config['helpblock']}</span>
					</div>
			    </div>";
			 return $return;
		}
		function createCheckBox($config){
			
			return "
				<div class=\"row-fluid\">
					<div class=\"span8\">
					<label class=\"checkbox\">
							<input type=\"checkbox\" id=\"{$checkboxes['fieldname']}\" name=\"{$checkboxes['fieldname']}\" value=\"1\">
								{$config['title']}
						</label>
					</div>
			    </div>";
		
						
		}
		function createHidden($config){
			$default = isset($config['default']) ? $config['default']:'';
			return "<input type='hidden' id='{$config['fieldname']}' name='{$config['fieldname']}' value='$default'>";
		}
		function createClassInput($config,$class){
			$default = isset($config['default']) ? $config['default']:'';
			return "<div class=\"row-fluid\">
						<div class=\"span8\">
							<label>{$config['title']} <span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\" class=\"$class\" value=\"$default\"/>
						<span class=\"help-block\">{$config['helpblock']}</span>
						</div>
					</div>";
		}
		function createStartStop($config){
			$default = isset($config['default']) ? $config['default']:'';
			return "<div class=\"row-fluid\">
						<div class=\"span4\">
							<label>Start Date<span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"startdate\" id=\"startdate\" class=\"datepicker span12\" value=\"$default\"/>
						
						</div>
						<div class=\"span4\">
							<label>Start Time <span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"starttime\" id=\"starttime\" class=\"timepick span12\" value=\"\"/>
						</div>
					</div>
					<div class=\"row-fluid\">
						<div class=\"span4\">
							<label>End Date<span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"enddate\" id=\"enddate\" class=\"datepicker span12\" value=\"$default\"/>
						
						</div>
						<div class=\"span4\">
							<label>End Time <span class=\"f_req\">*</span></label>
							<input type=\"text\" name=\"endtime\" id=\"endtime\" class=\"timepick span12\" value=\"\"/>
						</div>
					</div>";
		}
function createDollarField($config){
			$default = isset($config['default']) ? $config['default'] : '';
			return "
				<div class=\"row-fluid\">
						<div class=\"span8\">
							<label>{$config['title']}:</label>
							<div class=\"controls\">
							<span class=\"add-on\">$</span>
							<input type=\"text\" class=\"span3\" size=\"16\"  name=\"{$config['fieldname']}\" id=\"{$config['fieldname']}\">
							<span class=\"add-on\">.00</span>
							<span class=\"help-block\">{$config['helpblock']}</span>
						</div>
				    </div>";
		}

}