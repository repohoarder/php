<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Ajaxsave {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	/**
	 * This function will create a javascript snipt that will look thru input select and textareas of a specified form ID
	 * Post to an ajax page and retrieve Json. It will then input an error if there is one that exists. 
	 * form needs id="{$buttonclick}form"
	 * button   id="$buttonclick"
	 * error:   id="error$buttonclick"
	 *
	 * @param string $targeturl
	 * @param string $buttonclick  
	 * @return unknown
	 */
	public function renderSimpleJavascriptSubmitJson($targeturl,$buttonclick,$redirect='',$requiredfields=array())
	{
		$redir = empty($redirect) ? "": "location.href='$redirect';";
		$form = str_replace("save_","",$buttonclick);
		$required = $this->requiredFields($requiredfields);
		$targeturl = $this->CI->config->item('subdir'). $targeturl;
		return " 
		$(document).ready(function() {
			$required
	    	$('#$buttonclick').click(function(){
		    	var query_string='';
		    	$('#{$form}_form input,select,textarea').each(function() {
		        	query_string = query_string + $(this).attr('name') + '=' +$(this).val() +'&';
		       });
		       $.getJSON('$targeturl?'+query_string,function(data){
					if(data.error){
					$(\"#error$buttonclick\").html(data.error);
					}
					$redir
				});	 
	   	 	});
	  	});";
	}
	function createSimpleModelSubmitJson($target,$targeturl,$redirect,$requiredfields= array()){
		$redir = empty($redirect) ? "": "location.href='$redirect';";
		$required = $this->requiredFields($requiredfields);
		$targeturl = $this->CI->config->item('subdir'). $targeturl;
		return "
		
		$(document).ready(function() {
			$required
	    	$('#save_'+ target).click(function(){
				if (!$('#' + target + '_dialog > form.form_validation_reg').valid()) {
						return;
					}
				else
				{
			    	var query_string='';
			    	$('#'+ target +'_form input,select,textarea').each(function() {
			        	query_string = query_string + $(this).attr('name') + '=' + escape($(this).val()) +'&';
			       });
			       $.getJSON('$targeturl?'+query_string,function(data){
						if(data.error){
						$(\"#error\" + target).html(data.error);
						}
						$redir
					});	 
				}
	   	 	});
	  	});";
	}
	/**
	 * This function builds a save Chunk 0' C0de f0r the simple add update delete View .
	 * 
	 * Author: Jamie Rohr
	 * Date : 10-2-2012
	 *
	 * @param string $target
	 * @param array $configArr
	 * @param booleon $sortable
	 * @return string
	 */
	
	public function buildSaveJs($target,$configArr,$sortable = false,$blur=array()){
	
		// load wizard library for create blur function
		$this->CI->load->library('wizard');
		
		$redirect = isset($configArr['redirect']) ? $configArr['redirect']:'';
		$returnJs = "var target = '$target';\n";
		$returnJs .="		$(document).ready(function() {\n"; 	
		$returnJs .= $this->CI->wizard->createBlurJavascript($blur);											  // Piece O' Code is just a piece of code roflcoptor!!!!!!!!
		$returnJs .= !$sortable ? "": $this->sortable($configArr['parent']); 								 // this will add a sortable call to reorder ranks Piece O' Code
		$returnJs .= $this->requiredFields($configArr['required']); 										// this will return a required fields javascript Piece O' Code
		$returnJs .= $this->saveClickFunction($configArr['post'],$configArr['build'],$configArr['assoc'],$redirect); // This will return a modal save posting page Piece O' Code
		return $returnJs;
	}
	/**
	 * This function is called by setting the sortable variable to true in buildSaveJs()
	 * This takes a sortable table and reorders the elements to the specified drag and drop method
	 * 
	 * Author: Jamie Rohr
	 * DATE : 10-10-2012
	 *
	 * @param unknown_type $parentid
	 * @return unknown
	 */
	private function sortable($parentid){
		$targeturl = $this->CI->config->item('subdir');
		return "$(\".sortable\").sortable({
					items: 'tr:not(.nosort)',
					tolerance: 'pointer',
					stop: function(event, ui) { 
						var children = '\"' + $(this).sortable('toArray') + '\"';
						$.post('$targeturl/ajax/apadmin/reorder/'+ target, { parent_id: $parentid, children: children })
					}
				});";
	}
	/**
	 * Ths function here creates a required field javascript snippet for form validation. The input is a required array field
	 * 
	 * Author: Jamie Rohr
	 * DATE  : 10-03-2012
	 * 
	 * @param array $requiredFields
	 * @return string
	 */
	private function requiredFields($requiredFields)
	{
		if(!empty($requiredFields))
		{
			$return = "			$('#' + target + '_dialog > form.form_validation_reg').validate({
						onsubmit: false,
						errorClass: 'error',
						validClass: 'valid',
						highlight: function(element) {
							$(element).closest('div').addClass(\"f_error\");
						},
						unhighlight: function(element) {
							$(element).closest('div').removeClass(\"f_error\");
						},
		                errorPlacement: function(error, element) {
		                    $(element).closest('div').append(error);
		                },
						rules: {";
				$returns = array();
				foreach ($requiredFields as $field=>$extrafields)
				{
					$returns [] = "$field:{required:true$extrafields}";
				}
				$return .= implode(",",$returns);
							
			$return .="		}
					});";
			return $return;
		}
	}
	/**
	 * This function here generates a jquery click function that creates a json object, adds table row of new record and then closes the 
	 * popup modal window.
	 *
	 * Author: Jamie Rohr
	 * DATE  : 10-03-2012
	 * @param array $postfields  // builds the post array
	 * @param array $buildFields  // builds table cells
	 * @param array $assoc  // associate one to many database tables
	 * @return string
	 */
	private function saveClickFunction($postfields,$buildFields,$assoc,$redirect)
	{
		$targeturl = $this->CI->config->item('subdir');
		$return = "$(\"#save_\" + target).click(function(){
					if (!$('#' + target + '_dialog > form.form_validation_reg').valid()) {
						return;
					}
					$('#error' + target).hide();
					// get id of record we're editing (id is 0 if add)
					var id = $('#id').val();
					
					// get values to save from add/edit form\n";
		$jsonfields = array('id:id');
		foreach ($postfields as $field ):
			$return .="var $field = $('#$field').val();\n";
			$jsonfields[] = "$field : $field";
		endforeach;
		$return .="	
					$.post('$targeturl/ajax/apadmin/savemodal/' + target + 'save', 
						{\n"; 
		$return .= implode(",\n",$jsonfields);
		$return .="	\n},
						function(data) {
							// if add/update was successful add a row to the table and/or
							// update row values from add/edit form
				        	if (!data.error) {
				        		//$(\".modal\").modal('hide');
								// add new row to table if this is a record add
								if (id == 0) {
									var tr = document.createElement('tr');
									$(tr).attr('id', 'record_'+ data.id);";

	        				if ($this->CI->pageauth->loginHasPrivileges('edit')) : 
								$return .="	$(tr).append(constructEditCell(data.id));";
								
								 endif; 
							foreach ($buildFields as $field):
							$return .="td = document.createElement('td');
		        					td.className = '$field';
		        					$(tr).append(td);";
							endforeach;
							
							
							$count = count($assoc);
							if ($this->CI->pageauth->loginHasPrivileges('edit')) : 
								for ($i=0;$i<$count;$i++){	
	
			        				$return .="	td = document.createElement('td');
			        					td.className = 'assoc';
			        					$(td).click(function() { doAssociate(this,'{$assoc[$i]['type']}','{$assoc[$i]['title']}'); });
					        			$(td).text('Assign');
					        			$(tr).append(td);";
								}
							 endif; 


	        			if ($this->CI->pageauth->loginHasPrivileges('delete')) : 
									$return .="$(tr).append(constructDeleteCell(data.id));";
									endif; 
									
		        				$return .="	$('#' + target + '_table tbody').append(tr);
								}

								// edit table row for record with updated values from add/edit								
								var tr = $('#edit_' + data.id).parent().parent();";
								foreach ($buildFields as $field):
									$field2 = ($field == 'id') ? "data.id" : $field;
									if($field == 'children' ){
										$return .="if(id == 0) {
													tr.find('td.$field').html('<a href=\'$targeturl/administration/menus/edit?parent_id=' + data.id + '\'><img src=\'$targeturl/resources/apadmin/img/down.png\' alt=\'Edit Children\' title=\'Edit Children\' class=\'edit\' /></a><span class=\'num_children\' style=\'display:block;\'>0</span>');
												}";
									}else{
										$return .="tr.find('td.$field').text($field2);";
									}
							endforeach;
							if(!empty($redirect)){
								$redirect = $targeturl.$redirect;
								$return .=" location.href='$redirect';";
							}
							$return .="	
							$(\".modal\").modal('hide');
							clearFormFields();
				        	} else {
					        	// error on add/update - alert user and close dialog
					        	$('#error' + target).show();
					        	$('#error' + target).html(data.error);
				        	}
						
						}, 'json'
					);
				});";
		 return $return;
	}
}