<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create a 
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Wizard {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	
	public function createWizardJavascript($wizard){
		$html = "$(document).ready(function() {
				//* wizard with validation
				gebo_wizard.validation();
				//* add step numbers to titles
				gebo_wizard.steps_nb();
				});";
		$html .="gebo_wizard = {
		validation: function(){
			$('#validate_wizard').stepy({
				nextLabel:      'Forward <i class=\"icon-chevron-right icon-white\"></i>',
				backLabel:      '<i class=\"icon-chevron-left\"></i> Backward',
				block		: true,
				errorImage	: true,
				titleClick	: true,
				validate	: true
			});
			stepy_validation = $('#validate_wizard').validate({
				onsubmit: false,
				onfocusout: false,
				errorPlacement: function(error, element) {
					error.appendTo( element.closest(\"div.controls\") );
				},
				highlight: function(element) {
					$(element).closest(\"div.control-group\").addClass(\"error f_error\");
					var thisStep = $(element).closest('form').prev('ul').find('.current-step');
					thisStep.addClass('error-image');
				},
				unhighlight: function(element) {
					$(element).closest(\"div.control-group\").removeClass(\"error f_error\");
					if(!$(element).closest('form').find('div.error').length) {
						var thisStep = $(element).closest('form').prev('ul').find('.current-step');
						thisStep.removeClass('error-image');
					};
				},
				rules: {";
				foreach ($wizard['required'] as $field=>$value){
					$fieldArr[] = "'$field' : 'required'";
					$fieldReq[] = "'$field'	: { required:  '$value' }";
				}	
				$html .= implode(",\n",$fieldArr);	
				$html .="}, messages: {";
				$html .= implode(",\n",$fieldReq);
			$html .="	},
				ignore				: ':hidden'
			});
		},
		//* add numbers to step titles
		steps_nb: function(){
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class=\"stepNb\">'+myIndex+'</span>');
				})
			})
		}
	};";
		return $html;
	}
	public function createWizard($wizard,$steps){
		$html  = $this->renderHeader($wizard);
		$html .= $this->renderSteps($wizard['forms'],$steps);
		$html .= $this->renderFooter($wizard);
		return $html;
	}
	
	private function renderHeader($wizard){
		$return= "<div class=\"row-fluid\">
						<div class=\"span12\">";
		$return .= empty($wizard['heading'])? "" : "<h3 class=\"heading\">{$wizard['heading']}</h3>";
		$return .="					<div class=\"row-fluid\">
								<div class=\"span2\"></div>
								<div class=\"span8\">
									<form id=\"validate_wizard\" class=\"stepy-wizzard form-horizontal\" action=\"#\" method=\"post\">";
	return $return;
	}
	private function renderSteps($wizard,$steps)
	{
		$this->CI->load->library('wizardforms');
		$html='';
		$count = count($wizard);
		for($i=0;$i<$count;$i++){
			$html .="<fieldset title=\"{$steps[$i]['heading']}\">
											<legend class=\"hide\">&nbsp;{$steps[$i]['foot']}</legend>";
			$countsep = count($wizard[$i]);
			for($ii=0;$ii<$countsep;$ii++){
				$type = $wizard[$i][$ii]['type'];
				switch ($type){
					case 'input' :
						$html .= $this->CI->wizardforms->createWizardInput($wizard[$i][$ii]);
						break;
					case 'textarea' :
						$html .= $this->CI->wizardforms->createWizardTextarea($wizard[$i][$ii]);
						break;
					case 'password' :
						$html .= $this->CI->wizardforms->createWizardPassword($wizard[$i][$ii]);
						break;
					case 'select' :
						$html .= $this->CI->wizardforms->createWizardDropDown($wizard[$i][$ii]);
					break;
					case 'checkbox' :
						$html .= $this->CI->wizardforms->createWizardCheckBox($wizard[$i][$ii]);
						break;
					case 'hidden' :
						$html .= $this->CI->wizardforms->createWizardHidden($wizard[$i][$ii]);
						break;
					case 'date' :
						$html .= $this->CI->wizardforms->createWizardClassInput($wizard[$i][$ii],'datepicker');
						break;
					case 'time' :
						$html .= $this->CI->wizardforms->createWizardClassInput($wizard[$i][$ii],'timepick');
						break;
					case 'amount' :
						$html .= $this->CI->wizardforms->createWizardDollarField($wizard[$i][$ii]);
						break;
				}
			}
			$html .="</fieldset>";
		}
		return $html;

	}
	private function renderFooter($wizard){
		return "<button type=\"button\" class=\"finish btn btn-primary\" id=\"wizardsubmit\"><i class=\"icon-ok icon-white\"></i> {$wizard['sendbutton']}</button>
									</form>
								</div>
							</div>
						</div>
					</div>";
	}
	public function renderJavascriptSubmit($targeturl,$redirecturl,$blur= array())
	{
		$blurJS = $this->createBlurJavascript($blur);
		return " 
		$(document).ready(function() {
			$blurJS
	    	$('#wizardsubmit').click(function(){
			if (!$('#validate_wizard').valid()) {
						return;
					}
		    	var query_string='';
		    	$('#validate_wizard input,select,textarea').each(function() {
		        	query_string = query_string + $(this).attr('name') + '=' +$(this).val() +'&';
		       });
		        	$.ajax({
				          type: \"POST\",
				          url: \"$targeturl\",
				          data: query_string,
					      success: function(data){
					          if(data == 'Success')
					          {
					        	window.location.href= '$redirecturl';
					          }else{
					          		alert(data);
					          		$('#wizarderror').html(data);
					          }
				          }
				    	});      
		    		return false;  	 
	   	 	});
	  	});";
	}
	public function createBlurJavascript($blur){
		$html='';
		if(!empty($blur)):
			foreach ($blur as $fieldname):
				$html .="$('#$fieldname').blur(function(){
			    		query_string = \"search=\" + $(this).val();
			        	$.ajax({
					          type: \"POST\",
					          url: \"/ajax/search/$fieldname\",
					          data: query_string,
						      success: function(data){
						          $(\"#help$fieldname\").html(data);
						          $('#help$fieldname').css('color','red');
					          }
					    	});      
			    		return false;  	 
		   	 	});";
		   	endforeach;
	   	endif;
	   	return $html;
	}
}