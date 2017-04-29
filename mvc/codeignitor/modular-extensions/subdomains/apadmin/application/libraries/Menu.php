<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Menu {
	
	/**
	 * The Codeignitor Object
	 */
	var $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
	}
	public function changeTemplate(){
		return '<div class="style_switcher">
			<div class="sepH_c">
				<p>Colors:</p>
				<div class="clearfix">
					<a href="javascript:void(0)" class="style_item jQclr blue_theme style_active" title="blue">blue</a>
					<a href="javascript:void(0)" class="style_item jQclr dark_theme" title="dark">dark</a>
					<a href="javascript:void(0)" class="style_item jQclr green_theme" title="green">green</a>
					<a href="javascript:void(0)" class="style_item jQclr brown_theme" title="brown">brown</a>
					<a href="javascript:void(0)" class="style_item jQclr eastern_blue_theme" title="eastern_blue">eastern blue</a>
					<a href="javascript:void(0)" class="style_item jQclr tamarillo_theme" title="tamarillo">tamarillo</a>
				</div>
			</div>
			<div class="sepH_c">
				<p>Backgrounds:</p>
				<div class="clearfix">
					<span class="style_item jQptrn style_active ptrn_def" title=""></span>
					<span class="ssw_ptrn_a style_item jQptrn" title="ptrn_a"></span>
					<span class="ssw_ptrn_b style_item jQptrn" title="ptrn_b"></span>
					<span class="ssw_ptrn_c style_item jQptrn" title="ptrn_c"></span>
					<span class="ssw_ptrn_d style_item jQptrn" title="ptrn_d"></span>
					<span class="ssw_ptrn_e style_item jQptrn" title="ptrn_e"></span>
				</div>
			</div>
			<div class="sepH_c">
				<p>Layout:</p>
				<div class="clearfix">
					<label class="radio inline"><input type="radio" name="ssw_layout" id="ssw_layout_fluid" value="" checked /> Fluid</label>
					<label class="radio inline"><input type="radio" name="ssw_layout" id="ssw_layout_fixed" value="gebo-fixed" /> Fixed</label>
				</div>
			</div>
			<div class="sepH_c">
				<p>Sidebar position:</p>
				<div class="clearfix">
					<label class="radio inline"><input type="radio" name="ssw_sidebar" id="ssw_sidebar_left" value=""  /> Left</label>
					<label class="radio inline"><input type="radio" name="ssw_sidebar" id="ssw_sidebar_right" value="sidebar_right" checked /> Right</label>
				</div>
			</div>
			<div class="sepH_c">
				<p>Show top menu on:</p>
				<div class="clearfix">
					<label class="radio inline"><input type="radio" name="ssw_menu" id="ssw_menu_click" value="" checked /> Click</label>
					<label class="radio inline"><input type="radio" name="ssw_menu" id="ssw_menu_hover" value="menu_hover" /> Hover</label>
				</div>
			</div>
			
			<div class="gh_button-group">
				<a href="#" id="showCss" class="btn btn-primary btn-mini">Show CSS</a>
				<a href="#" id="resetDefault" class="btn btn-mini">Reset</a>
			</div>
			<div class="hide">
				<ul id="ssw_styles">
					<li class="small ssw_mbColor sepH_a" style="display:none">body {<span class="ssw_mColor sepH_a" style="display:none"> color: #<span></span>;</span> <span class="ssw_bColor" style="display:none">background-color: #<span></span> </span>}</li>
					<li class="small ssw_lColor sepH_a" style="display:none">a { color: #<span></span> }</li>
				</ul>
			</div>
		</div>';
	}
	public function gettopmenu()
			{
		
		$menu = $this->CI->platform->post('apadmin/menu/rendermenu');
		
		if($menu['success']) :
			$menuitems = $menu['data'];
			return $menuitems;
		endif;
	}
	public function getmenuaccess($userid){
		
		$has_access = array();
		
		// check for access
		$chk =$this->CI->platform->post('apadmin/menu/hasaccess',array('login_id'=>$userid));
		if ($chk['success']) :
			$has_access = $chk['data'];
		endif;
		return $has_access;
	}
	public function renderTop($userid){
		
			$html = $this->startTopMenu();     
            return $html;
	}
	public function sideBar(){
		return '<!-- sidebar -->
            <a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
            <div class="sidebar">
				
				<div class="antiScroll">
					<div class="antiscroll-inner">
						<div class="antiscroll-content">
					
							<div class="sidebar_inner">
							<!--
								<form action="index.php?uid=1&amp;page=search_page" class="input-append" method="post" >
									<input autocomplete="off" name="query" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
								</form>
								<div id="side_accordion" class="accordion">
									
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
												<i class="icon-folder-close"></i> Content
											</a>
										</div>
										<div class="accordion-body collapse" id="collapseOne">
											<div class="accordion-inner">
												<ul class="nav nav-list">
													<li><a href="javascript:void(0)">Articles</a></li>
													<li><a href="javascript:void(0)">News</a></li>
													<li><a href="javascript:void(0)">Newsletters</a></li>
													<li><a href="javascript:void(0)">Comments</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapseTwo" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
												<i class="icon-th"></i> Modules
											</a>
										</div>
										<div class="accordion-body collapse" id="collapseTwo">
											<div class="accordion-inner">
												<ul class="nav nav-list">
													<li><a href="javascript:void(0)">Content blocks</a></li>
													<li><a href="javascript:void(0)">Tags</a></li>
													<li><a href="javascript:void(0)">Blog</a></li>
													<li><a href="javascript:void(0)">FAQ</a></li>
													<li><a href="javascript:void(0)">Formbuilder</a></li>
													<li><a href="javascript:void(0)">Location</a></li>
													<li><a href="javascript:void(0)">Profiles</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapseThree" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
												<i class="icon-user"></i> Account manager
											</a>
										</div>
										<div class="accordion-body collapse" id="collapseThree">
											<div class="accordion-inner">
												<ul class="nav nav-list">
													<li><a href="javascript:void(0)">Members</a></li>
													<li><a href="javascript:void(0)">Members groups</a></li>
													<li><a href="javascript:void(0)">Users</a></li>
													<li><a href="javascript:void(0)">Users groups</a></li>
												</ul>
												
											</div>
										</div>
									</div>
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapseFour" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
												<i class="icon-cog"></i> Configuration
											</a>
										</div>
										<div class="accordion-body collapse" id="collapseFour">
											<div class="accordion-inner">
												<ul class="nav nav-list">
													<li class="nav-header">People</li>
													<li class="active"><a href="javascript:void(0)">Account Settings</a></li>
													<li><a href="javascript:void(0)">IP Adress Blocking</a></li>
													<li class="nav-header">System</li>
													<li><a href="javascript:void(0)">Site information</a></li>
													<li><a href="javascript:void(0)">Actions</a></li>
													<li><a href="javascript:void(0)">Cron</a></li>
													<li class="divider"></li>
													<li><a href="javascript:void(0)">Help</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapseLong" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
												<i class="icon-leaf"></i> Long content (scrollbar)
											</a>
										</div>
										<div class="accordion-body collapse" id="collapseLong">
											<div class="accordion-inner">
												Some text to show sidebar scroll bar<br>
												Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus rhoncus, orci ac fermentum imperdiet, purus sapien pharetra diam, at varius nibh tellus tristique sem. Nulla congue odio ut augue volutpat congue. Nullam id nisl ut augue posuere ullamcorper vitae eget nunc. Quisque justo turpis, tristique non fermentum ac, feugiat quis lorem. Ut pellentesque, turpis quis auctor laoreet, nibh erat volutpat est, id mattis mi elit non massa. Suspendisse diam dui, fringilla id pretium non, dapibus eget enim. Duis fermentum quam a leo luctus tincidunt euismod sit amet arcu. Duis bibendum ultricies libero sed feugiat. Duis ut sapien risus. Morbi non nulla sit amet eros fringilla blandit id vel augue. Nam placerat ligula lacinia tellus molestie molestie vestibulum leo tincidunt.
												Duis auctor varius risus vitae commodo. Fusce nec odio massa, ut dapibus justo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur dapibus, mauris sit amet feugiat tempor, nulla diam gravida magna, in facilisis sapien tellus non ligula. Mauris sapien turpis, sodales ac lacinia sit amet, porttitor in lacus. Pellentesque tincidunt malesuada magna, in egestas augue sodales vel. Praesent iaculis sapien at ante sodales facilisis.
											</div>
										</div>
									</div>-->
									<div class="accordion-group">
										<div class="accordion-heading">
											<a href="#collapse7" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
											   <i class="icon-th"></i> Calculator
											</a>
										</div>
										<div class="accordion-body collapse" id="collapse7">
											<div class="accordion-inner">
												<form name="Calc" id="calc">
													<div class="formSep control-group input-append">
														<input type="text" style="width:142px" name="Input" /><button type="button" class="btn" name="clear" value="c" onclick="Calc.Input.value = \'\'"><i class="icon-remove"></i></button>
													</div>
													<div class="control-group">
														<input type="button" class="btn btn-large" name="seven" value="7" onclick="Calc.Input.value += \'7\'" />
														<input type="button" class="btn btn-large" name="eight" value="8" onclick="Calc.Input.value += \'8\'" />
														<input type="button" class="btn btn-large" name="nine" value="9" onclick="Calc.Input.value += \'9\'" />
														<input type="button" class="btn btn-large" name="div" value="/" onclick="Calc.Input.value += \' / \'">
													</div>
													<div class="control-group">
														<input type="button" class="btn btn-large" name="four" value="4" onclick="Calc.Input.value += \'4\'" />
														<input type="button" class="btn btn-large" name="five" value="5" onclick="Calc.Input.value += \'5\'" />
														<input type="button" class="btn btn-large" name="six" value="6" onclick="Calc.Input.value += \'6\'" />
														<input type="button" class="btn btn-large" name="times" value="x" onclick="Calc.Input.value += \' * \'" />
													</div>
													<div class="control-group">
														<input type="button" class="btn btn-large" name="one" value="1" onclick="Calc.Input.value += \'1\'" />
														<input type="button" class="btn btn-large" name="two" value="2" onclick="Calc.Input.value += \'2\'" />
														<input type="button" class="btn btn-large" name="three" value="3" onclick="Calc.Input.value += \'3\'" />
														<input type="button" class="btn btn-large" name="minus" value="-" onclick="Calc.Input.value += \' - \'" />
													</div>
													<div class="formSep control-group">
														<input type="button" class="btn btn-large" name="dot" value="." onclick="Calc.Input.value += \'.\'" />
														<input type="button" class="btn btn-large" name="zero" value="0" onclick="Calc.Input.value += \'0\'" />
														<input type="button" class="btn btn-large" name="DoIt" value="=" onclick="Calc.Input.value = Math.round( eval(Calc.Input.value) * 1000)/1000" />
														<input type="button" class="btn btn-large" name="plus" value="+" onclick="Calc.Input.value += \' + \'" />
													</div>
												</form>
											</div>
										 </div>
									</div>
								</div>
								
								<div class="push"></div>
							</div>
							<!--   
							<div class="sidebar_info">
								<ul class="unstyled">
									<li>
										<span class="act act-warning">65</span>
										<strong>New comments</strong>
									</li>
									<li>
										<span class="act act-success">10</span>
										<strong>New articles</strong>
									</li>
									<li>
										<span class="act act-danger">85</span>
										<strong>New registrations</strong>
									</li>
								</ul>
							</div> 
						-->
						</div>
					</div>
				</div>
			
			</div>';
	}
	/**
	 * This function will create an unordered list with menu checkboxes for associations with roles
	 *
	 * @param int $parent_id
	 * @param int $login_id
	 * @param int $role_id
	 * @return html
	 */
	public function startTopMenu($parent_id=0,$position="TOP",$login_id){
		
		
		$menuitems = $this->gettopmenu();
		$has_access = $this->getmenuaccess($login_id);
		//echo "<pre>";print_r($menuitems);
		$html = array();
		
			
			foreach($menuitems[$position][0] as $key=>$row) :
				
				if(in_array($row['id'],$has_access)):
					if(empty($row['num_children'])):
					$html[] = "<li>
								<a href=\"".$this->CI->config->item('subdir')."{$row['target_path']}\"><i class=\"{$row['icon']} icon-white\"></i> {$row['link_text']}</a>
							</li>";
					else : 
					$html[]= "<li class=\"dropdown\">
	                            <a data-toggle=\"dropdown\" class=\"dropdown-toggle\" href=\"{$row['target_path']}\">
	                            	<i class=\"{$row['icon']} icon-white\"></i> {$row['link_text']} <b class=\"caret\"></b></a>
	                               	<ul class=\"dropdown-menu\">";
	                $children = array();
					$html[] = 		$this->renderMenuTop($row['id'], $has_access,$menuitems,$position);
					$html[] = "		</ul>
								</li>";
					endif;
				endif;
			endforeach;
			
		return implode("\n", $html);
	
	}
	private function renderMenuTop($parent_id, $has_access,$menuitems,$position){
		$html = array();
		
		if (!in_array($parent_id, $has_access)) {
			return '';
		}
	
	
			foreach ($menuitems[$position][$parent_id] as $key=>$row) :
				if(in_array($row['id'],$has_access)):
					if($row['num_children'] > 0) :
					$children[] = "<li class=\"dropdown\">
									<a href=\"#{$row['target_path']}\">{$row['link_text']} <b class=\"caret-right\"></b></a>
									<ul class=\"dropdown-menu\">";
					$children[] =  $this->renderMenuTop($row['id'], $has_access,$menuitems,$position);
					$children[] = "		</ul>
								</li>";
					else:
					$children[] =  "<li><a href=\"".$this->CI->config->item('subdir')."{$row['target_path']}\">{$row['link_text']}</a></li>";
					endif;
				endif;
			endforeach;
			
		return implode("\n", $children);
	}
	public function startTopCheck($records){
		// create html array
		$html = array();
		$html[] = '<ul style="list-style-type:none;">';
		
		foreach ($records[0] as $row):
			$is_member = $row['member'] > 0;
			$html[] = $this->renderTopCheck($records,$row['id'],$row['link_text'], $is_member);
		endforeach;
		
		$html[] = '</ul>';
		
		return implode("\n", $html);
	}
	/**
	 * This function here will call itself recursively to create subchildren of parent menu items
	 * Author : Jamie Rohr
	 * 
	 * @param int $parent_id
	 * @param string $text
	 * @param int $role_id
	 * @param BOOL $is_member
	 * @return html
	 */
	private function renderTopCheck($records, $parent_id, $text, $is_member){
		
		$html = array();
		$checked = $is_member ? 'checked="checked"' : '';
		$html[] = "<li><label for='assoc_{$parent_id}' class='checkbox'><input type='checkbox' id='assoc_{$parent_id}' class='assoc_checked' {$checked} /> {$text}</label></li>";
		
		if( isset($records[$parent_id])) :
		
		$html[] = '<ul style="list-style-type:none;">';
		
			foreach ($records[$parent_id] as $row) :
			
				$is_member = $row['member'] > 0;
				$html[] = $this->renderTopCheck($records,$row['id'], $row['link_text'], $is_member);
				
			endforeach;
			
			$html[] = '</ul>';
			
		endif;
		$html[] = '</li>';
		
		return implode("\n", $html);
	}
	/**
	 * This function will create a breadcrumb for all the pages
	 * Author: Jamie Rohr
	 * Date : 10-4-2012
	 * 
	 * @param array $linkArr
	 * @return html
	 */
	function createBreadCrumb($linkArr){
		$breadcrumb = " <nav>
                        <div id=\"jCrumbs\" class=\"breadCrumb module\">
                            <ul>";
		$breadcrumb .=" <li>
                            <a href=\"/dashboard\"><i class=\"icon-home\"></i></a>
                        </li>";
		foreach ($linkArr as $title=>$link){
			if(empty($link)){
				$breadcrumb .=" <li>
                                    $title
                                </li>";
			}else{
				$breadcrumb .= " <li>
                                    <a href=\"$link\">$title</a>
                                </li>";
			}
		}
        $breadcrumb .="                     
                            </ul>
                        </div>
                    </nav>";
       return $breadcrumb;
	}
	function adminBreadCrumb($parent_id,$return){
		
		$array = $this->CI->platform->post('apadmin/menu/getadminbreadcrumb',array("parent_id" => $parent_id ));
		return $array['data'];
	}
}