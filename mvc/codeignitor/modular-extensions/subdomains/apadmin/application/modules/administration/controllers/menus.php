<?php 
ini_set("display_errors",'on');
class Menus extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageauth');
		$this->load->library('menu');
		$this->load->library('tablebuild');
		$this->load->library('ajaxsave');
		$this->load->library('modalsbuild');
	}
	function index(){
		// needs called in every function will redirect to login
		$tablename = "denyaccess";
		$this->pageauth->checkprivileges($tablename);
	}
	function edit(){
		// check privileges
		$target = 'menus';
		$this->pageauth->checkprivileges($target);
		
		
		$parent = $this->input->get('parent_id') ? $this->input->get('parent_id') : '0';
		$parent = $parent == '' ? 0 : $parent;
		
		
		$menu =  $this->platform->post('apadmin/menu/getmenus',array('parent'=>$parent));
		$menus = $menu['data'];
		
		
		$glyph = $this->platform->post('apadmin/glyphish/getglyphs');
		
		$glyphs = $glyph['data'];
		
		// set other configuration arrays
		$requiredFields = array("link_text"=>""); // required fields for javascript validate
		$postFields = array('link_text','target_path','mtype','parent_id'); // post field array for javascript submit
		$buildFields = array('id','children','link_text','target_path','mtype'); // build fields for table
		$tableFields = array('ID','&nbsp;','Link Text','Target Path','Placement','Access'); // array for table headers
		// create extra array for extra table cell
		$extraRow = array("Icon"=>"<a href='javascript:void(0);' onClick=\"openIcon('IDVALUE');\">ICONFIELD</a>");
		// set up associate arrays for table associations
		
		$assocFields[] = array('type'=>'menuitemtorole',"title"=>"Associate Menu to Roles");
		// create main config Array $configArr['datatable']
		$configArr = array('parent'=>$parent,'datatable'=>"dTableR sortable",'build'=>$buildFields,"post"=>$postFields,"required"=>$requiredFields,'assoc'=>$assocFields,'records'=>$menus,'tableheaders'=>$tableFields);
		// Modal Configuration Array
		$modal['formtitle'] = 'Menus Form';
		$modal['forms'][0][] = array("type"=>"input","fieldname"=>"link_text","helpblock"=>"","title"=>"Link Text");
		$modal['forms'][0][] = array("type"=>"input","fieldname"=>"target_path","helpblock"=>"","title"=>"Target Path");
		$modal['forms'][0][] = array("type"=>"hidden","fieldname"=>"parent_id","helpblock"=>"","title"=>"","default"=>$parent);
		$levels = array("TOP"=>"Top Navigation","SIDEBAR"=>"Side Bar Navigation");// create level array for dropdown menu
		$modal['forms'][1][] = array('type'=>"select","fieldname"=>"mtype","valuearray"=>$levels,"title"=>"Navigation Location","helpblock"=>"All children menu links will get the parent location.","add_top_option"=>false);
		$earr = array();
		$breadcrumb = $this->menu->adminBreadCrumb($parent,$earr);
		
		//set data to load into view
		$data = array();
		$data['pagetitle'] = "Menus";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		$data['table'] = $this->tablebuild->generateTable($target,$configArr,$extraRow);
		$data['javascriptsave'] = $this->ajaxsave->buildSaveJs($target,$configArr,true);	
		$data['addmodal'] = $this->modalsbuild->generateModal($target,$modal);
		$data['addmodal'] .= $this->modalsbuild->generateAssocModal();
		$data['addmodal'] .= $this->modalsbuild->generateModalIcons("icon",$glyphs);
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}
}
