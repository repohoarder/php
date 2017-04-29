<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will create modal popups based on an input array
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 10-02-2012
 * 
 *
 */
class Modalsbuild {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	public function generateAssocModal(){
		return '<div  id="assoc_dialog" class="modal hide fade" role="dialog">
						<form  id="record_assoc_form" method="post" action="#">
						    <div class="modal-header">
						    	<button type="button" class="close closemodal" data-dismiss="modal">x</button>
						    	<h3 id="assocLabel">Administrative Logins</h3>
						    </div>
						    <div class="modal-body">
							    <table cellpadding="2" cellspacing="2" id="assoc_grid"><tr><td></td></tr></table>	
						   </div>
						    <div class="modal-footer">
						    <input type="hidden" id="assoc_id" /> 
						    <input type="hidden" id="assoc_type" />
						    <button class="btn btn-danger closemodal" type="button">Close</button>
						    <button class="btn btn-inverse" id="save_assoc" type="button">Save changes</button>
						   
						    </div>
						   </form>
					    </div>';
	}
	/**
	 * This function will generate the edit model based off of the configArr
	 *
	 * @param array $configArr
	 */
	public function generateModalIcons($target,$config){
		$html  = $this->modalHeader($target,'Choose top level menu icon');
		$html .= $this->modalBodyIcon($config);
		$html .= $this->modalFooterIcon($target);
		return $html;
	}
	public function generateModal($target,$configArr){
		$html  = $this->modalHeader($target,$configArr['formtitle']);
		$html .= $this->modalBody($configArr['forms']);
		$html .= $this->modalFooter($target,$configArr);
		return $html;
	}
	public function generateEmptyModal($target,$title,$buttonclick=''){
		$html  = $this->modalHeader($target,$title);
		$html .= $this->modalFooter($target,'',$buttonclick);
		return $html;
	}
	public function emptyModalOpen($target,$loadurl){
		
		$loadurl = $this->CI->config->item('subdir').$loadurl;
		return "function openModal(id){
				$(\"#{$target}_dialog\").modal('show');
				$('#id').val(id);
				$.ajax({
			          type: \"POST\",
			          url: \"$loadurl\",
						data: 'id='+id ,
				      success: function(data){
				      		$(\"#modal-body$target\").html(data);
				          }
			          
			    	});
				}";
	
	}
	private function modalHeader($target,$formtitle){
		return "<div  id=\"{$target}_dialog\" class=\"modal hide fade\" role=\"dialog\">
						<form class=\"form_validation_reg\" id=\"{$target}_form\" method=\"post\" action=\"#\">
						    <div class=\"modal-header\">
						    	<button type=\"button\" class=\"close closemodal\" data-dismiss=\"modal\">x</button>
						    	<h3 id=\"myModalLabel\">{$formtitle}</h3>
						    </div>
						    <div class=\"modal-body\" id=\"modal-body$target\">
						  		<div class=\"alert alert-error\" id=\"error{$target}\" style=\"display:none;\"></div>
						    ";
	}
	private function modalBodyIcon($config){
		$return='';
		foreach ($config as $id=>$icon){
			$return .="<div style='padding:5px;float:left;'><a href='javascript:void(0);' onClick=\"chooseIcon('$id','$icon');\"><i class=\"$icon\"></i></a></div>";
		}
		return $return;
	}
	private function modalBody($configArr){
		//print_r($configArr);
		$this->CI->load->library('formbuild');
		$html='';
		$count = count($configArr);
		for($i=0;$i<$count;$i++){
			$html .="<div class=\"formSep\">";
			$countsep = count($configArr[$i]);
			for($ii=0;$ii<$countsep;$ii++){
				$type = $configArr[$i][$ii]['type'];
				switch ($type){
					case 'input' :
						$html .= $this->CI->formbuild->createInput($configArr[$i][$ii]);
						break;
					case 'textarea' :
						$html .= $this->CI->formbuild->createTextarea($configArr[$i][$ii]);
						break;
					case 'password' :
						$html .= $this->CI->formbuild->createPassword($configArr[$i][$ii]);
						break;
					case 'select' :
						$html .= $this->CI->formbuild->createDropDown($configArr[$i][$ii]);
					break;
					case 'checkbox' :
						$html .= $this->CI->formbuild->createCheckBox($configArr[$i][$ii]);
						break;
					case 'hidden' :
						$html .= $this->CI->formbuild->createHidden($configArr[$i][$ii]);
						break;
					case 'date' :
						$html .= $this->CI->formbuild->createClassInput($configArr[$i][$ii],'datepicker');
						break;
					case 'time' :
						$html .= $this->CI->formbuild->createClassInput($configArr[$i][$ii],'timepick');
						break;
					case 'amount' :
						$html .= $this->CI->formbuild->createDollarField($configArr[$i][$ii]);
						break;
					case 'startstop' : 
						$html .= $this->CI->formbuild->createStartStop($configArr[$i][$ii]);
						break;
						
				}
			}
			$html .="</div>";
		}
		return $html;
	}
	private function modalFooter($target,$configArr,$configButton='')
	{
		$return = "  </div>
					<div class=\"modal-footer\">
					<input type=\"hidden\" name=\"id\" id=\"id\">
					<button class=\"btn btn-danger closemodal\" type=\"button\">Close</button>";
		if(!empty($configArr))
		{
			$return .="	<button class=\"btn btn-inverse\" id=\"save_{$target}\" type=\"button\">Save changes</button>";
		}
		if(!empty($configButton)) // create a javascript button and you can pull the id from the above hidden field
		{ 
			$return .="	<button class=\"btn btn-inverse\" onClick='$configButton' type=\"button\">Edit</button>";
		}
		$return .="</div>
			   </form>
		    </div>";
		return $return;
	}
	private function modalFooterIcon($target){
		return "  </div>
					<div class=\"modal-footer\">
					    <input type=\"hidden\" name=\"id\" id=\"id\">
					    <button class=\"btn btn-danger closemodal\" type=\"button\">Close</button>
					</div>
		   </form>
	    </div>";
	}
	
}