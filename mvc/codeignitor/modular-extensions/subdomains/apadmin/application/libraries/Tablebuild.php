<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library will load a table for one of your view admin pages. Will check for admin edit, add, delete access.
 * The configArr must consist
 * Author : Jamie Rohr
 * Date : 9-28-2012
 * 
 *
 */
class Tablebuild {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	
	public function generateTable($target,$configArr,$extrarows=array(),$export=''){
		// if you wish to remove the datatable simply put into the config array $configArr['datatable'] = false; 
		$overrides = isset($configArr['overrides']) ? $configArr['overrides'] : array();
		$datatable = (!isset($configArr['datatable']))? 'dTableR' : $configArr['datatable'];
		// generate header
		$table = $this->buildHeader($target,$configArr['tableheaders'],$datatable,$extrarows,$overrides,$export);
		// generate body
		$table .= $this->buildBody($target,$configArr['records'],$configArr['assoc'],$extrarows,$overrides);
		return $table;
	}
	private function buildHeader($target,$headers,$datatable,$extrarows,$overrides=array(),$export){
		
		$html = "<table class=\"table table-striped table-bordered table-condensed $datatable\" id=\"{$target}_table\">
				<thead>
					<tr class=\"nosort\">
						<th style=\"width:36px;cursor:pointer;\">";
		// if this user has add privileges lets let them have it muhahaha!!!
		if ($this->CI->pageauth->loginHasPrivileges('add')) : 
			$html .= isset($overrides['add']) ? $overrides['add'] :	"<a role=\"button\"  data-toggle=\"modal\" data-target=\"#{$target}_dialog\" id=\"add{$target}\" class=\"clearfields\"><i class=\"splashy-add clearfields\"></i></a>";
		endif;
		$html .="		</th>";
		// loop thru header titles.
		foreach ($headers as $v=>$k):
			$style = $k == "&nbsp;" ? " style=\"width:16px;\"" : "";
			$html .=	"<th$style>$k</th>";
			endforeach;	
		foreach ($extrarows as $title=>$htmlv){
			$html .=	"<th>$title</th>";
		}
		$html .="<th style=\"width:16px;\">&nbsp;$export</th>
					</tr>
				</thead>
				<tbody>";
		return $html;
	}
	private function buildBody($target,$records,$assoc,$extrarows,$overrides=array()){
		//The overrides array will let you add a different chunk of html code instead of the built in.
		$html='';
		$dissallowed= array("iconurl");
		foreach ($records as $id=>$arr){
			$html.="	<tr id=\"record_$id\">";
				$html .="<td>";
			if ($this->CI->pageauth->loginHasPrivileges('edit')) : 
				
				$html .= isset($overrides['edit']) ? str_replace("[ID]",$id,$overrides['edit']) :"<a href=\"javascript:void(0);\" id='edit_$id' onclick='doEdit(this);' role=\"button\"  data-toggle=\"modal\" data-target=\"#myModal_dialog\"><i class=\"splashy-pencil img\"></i></a>";
			endif;
			$html .="</td>";
			foreach ($records[$id] as $key=>$value)
			{
				$style = $key == "children" ? " style='text-align:center;'" : '';
				if(!in_array($key,$dissallowed))
				{
					$html .="	<td class='$key' $style>&nbsp;$value</td>";
				}
					
				
			}
			
			if ($this->CI->pageauth->loginHasPrivileges('edit')) : 
				$count = count($assoc);
				for ($i=0;$i<$count;$i++)
				{	
					$html .="<td class='assoc' onclick='doAssociate(this, \"{$assoc[$i]['type']}\",\"{$assoc[$i]['title']}\");' style='cursor:pointer;text-decoration:underline;'>Assign</td>";
				}
			endif;
			
			foreach ($extrarows as $title=>$htmlv){
				$key = preg_replace("/[^\d\w]/","",strtolower($title));
				$value = str_replace("IDVALUE",$id,$htmlv);
				$iurl='';
				if(isset($records[$id]['iconurl'])){
					$iurl = "<i class='{$records[$id]['iconurl']}'></i>";
				}
				$value = str_replace("ICONFIELD","$iurl"." Edit",$value);
				
				$html .="	<td class='$key' id='ex$key$id'>$value</td>";
			}
			//create delete cell
			$html .="<td>";
			if ($this->CI->pageauth->loginHasPrivileges('delete')) : 
				$html .= isset($overrides['delete']) ? str_replace("[ID]",$id,$overrides['delete']) :"<i title=\"Delete Record\" class=\"splashy-remove img\" id=\"delete_$id\" onClick=\"doDelete(this);\"></i>";
			endif;
			$html .="</td></tr>";
		}
		return $html."</tbody></table>";
	}
}