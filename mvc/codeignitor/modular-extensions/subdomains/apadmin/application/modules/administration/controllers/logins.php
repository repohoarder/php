<?php 
ini_set("display_errors",'on');
class Logins extends MX_Controller
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
	function adminlogins(){
		// check privileges
		$target = 'logins';
		$this->pageauth->checkprivileges($target);
		//load database array  for tableview
		
		$logins = $this->platform->post('apadmin/users/getlogins',array('login_id'=> $this->session->userdata('login_id')));
		
		$loginRecords = $logins['data'];
		
		// set up config for creating tables modals javascript
		$requiredFields = array("first_name"=>"","last_name"=>"","username"=>",minlength:3","email"=>",email:true");// required fields for javascript validate
		$postFields = array('first_name','last_name','email','username','pass_check','phone');// post field array for javascript submit
		$buildFields = array('id','first_name','last_name','email','username');// build fields for table
		$tableFields = array('ID','First Name','Last Name','Email','Username'); //  array for table headers
		
		if ($this->pageauth->loginHasPrivileges('edit')) : 
			$tableFields[] = 'Assign Role';
		endif;
		
		$assocFields[0] = array('type'=>'rolestologins',"title"=>"Associate User to Roles");
		$configArr = array('build'=>$buildFields,"post"=>$postFields,"required"=>$requiredFields,'assoc'=>$assocFields,'records'=>$loginRecords,'tableheaders'=>$tableFields);
		$breadcrumb = array("Admin Logins"=>"");
		
		$data = array();
		$data['pagetitle'] = "Admin Logins";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		$data['table'] = $this->tablebuild->generateTable($target,$configArr);
		$data['javascriptsave'] = $this->ajaxsave->buildSaveJs($target,$configArr);	
		$data['addmodal'] = '
						
						<div  id="'.$target.'_dialog" class="modal hide fade" role="dialog">
						<form class="form_validation_reg" id="'.$target.'_form" method="post" action="#">
						    <div class="modal-header">
						    	<button type="button" class="close closemodal" data-dismiss="modal">x</button>
						    	<h3 id="myModalLabel">Administrative Logins</h3>
						    </div>
						    <div class="modal-body">
						  		<div class="alert alert-error" id="error'.$target.'" style="display:none;"></div>
								<div class="formSep">
										<div class="row-fluid">
											<div class="span4">
												<label>First name <span class="f_req">*</span></label>
												<input type="text" name="first_name" id="first_name" class="span12" />
											
												<span class="help-block"></span>
											</div>
											<div class="span4">
												<label>Last name <span class="f_req">*</span></label>
												<input type="text" name="last_name" id="last_name" class="span12" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span8">
												<label>Email <span class="f_req">*</span></label>
												<input type="text" name="email" id="email" class="span12" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span8">
												<label>phone <span class="f_req">*</span></label>
												<input type="text" name="phone" id="phone" class="span12" />
											</div>
										</div>
								</div>
								<div class="formSep">
								<div class="row-fluid">
											<div class="span8">
												<label>Username <span class="f_req">*</span></label>
												<input type="text" name="username" id="username" class="span12" />
											</div>
										</div>
									<div class="row-fluid">
										<div class="span8">
											<label>Password <span class="f_req">* Enter new password to change</span></label>
											<input type="password" placeholder="Password" class="span8" id="pass_check" style="width:100%"/>
											<div id="pass_progress" class="progress progress-danger" style="width:100%">
											<div class="bar" style="width: 0"></div>
										</div>
										</div>
								</div>
								</div>
						    </div>
						    <div class="modal-footer">
						    <input type="hidden" name="id" id="id">
						    <button class="btn btn-danger closemodal" type="button">Close</button>
						    <button class="btn btn-inverse" id="save_'.$target.'" type="button">Save changes</button>
						   
						    </div>
						   </form>
					    </div>
					    
					    ';
		$data['addmodal'] .= $this->modalsbuild->generateAssocModal();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}
	function roles()
	{
		// check privileges
		$target = 'roles';
		$this->pageauth->checkprivileges($target);
		
		// load database arra for table
		$role = $this->platform->post('apadmin/role/getroles',array('login_id'=> $this->session->userdata('login_id')));
		$roles = $role['data'];
		
		// set other configuration arrays
		$requiredFields = array("name"=>""); // required fields for javascript validate
		$postFields = array('name','level'); // post field array for javascript submit
		$buildFields = array('id','name','level'); // build fields for table
		$tableFields = array('ID','Name','Level'); // array for table headers
		if ($this->pageauth->loginHasPrivileges('edit')) : 
			$tableFields[] = 'Assign Menus';
			$tableFields[] = 'Assign Logins';
			$tableFields[] = 'Assign Privileges';
		endif;
		// set up associate arrays for table associations
		
		$assocFields[] = array('type'=>'menutoroles',"title"=>"Associate Role to Menus");
		$assocFields[] = array('type'=>'loginstoroles',"title"=>"Associate Role to Users");
		$assocFields[] = array('type'=>'privilegestoroles',"title"=>"Associate Role to Privileges");
		// create main config Array
		$configArr = array('build'=>$buildFields,"post"=>$postFields,"required"=>$requiredFields,'assoc'=>$assocFields,'records'=>$roles,'tableheaders'=>$tableFields);
		// Modal Configuration Array
		$modal['formtitle'] = 'Roles Form';
		$modal['forms'][0][] = array("type"=>"input","fieldname"=>"name","helpblock"=>"","title"=>"Role Name");
		$levels = array();// create level array for dropdown menu
		for($i=1;$i<=10;$i++){
			$levels[$i] = $i;
		}
		$modal['forms'][0][] = array('type'=>"select","fieldname"=>"level","valuearray"=>$levels,"title"=>"Permission Level","helpblock"=>"1 meaning full access");
		$breadcrumb = array("System Roles"=>"");
		//set data to load into view
		$data = array();
		$data['pagetitle'] = "Roles";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		$data['table'] = $this->tablebuild->generateTable($target,$configArr);
		$data['javascriptsave'] = $this->ajaxsave->buildSaveJs($target,$configArr);	
		$data['addmodal'] = $this->modalsbuild->generateModal($target,$modal);
		$data['addmodal'] .= $this->modalsbuild->generateAssocModal();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}
	function privileges(){
		// check privileges
		$target = 'privileges';
		$this->pageauth->checkprivileges($target);
		
		// load database arra for table
		$role = $this->platform->post('apadmin/privilege/getprivileges',array('login_id'=> $this->session->userdata('login_id')));
		$roles = $role['data'];
		
		// set other configuration arrays
		$requiredFields = array("name"=>"","tablename"=>"","action"=>""); // required fields for javascript validate
		$postFields = array('name','tablename','action',"createall"); // post field array for javascript submit
		$buildFields = array('id','name','tablename','action'); // build fields for table
		$tableFields = array('ID','Name','Tablename','Action'); // array for table headers
		if ($this->pageauth->loginHasPrivileges('edit')) : 
			$tableFields[] = 'Assign Role';
		endif;
		
		// set up associate arrays for table associations
		$assocFields = array();
		$assocFields[0] = array('type'=>'rolestoprivileges',"title"=>"Associate Role to Privileges");
		
		// create main config Array
		$configArr = array('build'=>$buildFields,"post"=>$postFields,"required"=>$requiredFields,'assoc'=>$assocFields,'records'=>$roles,'tableheaders'=>$tableFields);
		
		// Modal Configuration Array ( in other words create an array to make form elements )
		$modal['formtitle'] = 'Privileges Form';
		$modal['forms'][0][] = array("type"=>"input","fieldname"=>"name","helpblock"=>"","title"=>"Privilege Name");
		$modal['forms'][0][] = array("type"=>"input","fieldname"=>"tablename","helpblock"=>"","title"=>"Table Name");
		$actions = array("view"=>"View","add"=>"Add","edit"=>"Edit","delete"=>"Delete");
		$modal['forms'][0][] = array('type'=>"select","fieldname"=>"action","valuearray"=>$actions,"title"=>"Action","helpblock"=>"","add_top_option"=>false);
		$levels = array('1'=>"Yes",'0'=>"No");// create level array for dropdown menu
		$modal['forms'][1][] = array('type'=>"select","fieldname"=>"createall","valuearray"=>$levels,"title"=>"Create All Actions","helpblock"=>"Select yes to create add, view,update,delete<strong><br>This will only work on creating a new privilege</strong>","add_top_option"=>false);
		$breadcrumb = array("System Privileges"=>"");
		
		//set data to load into view
		$data = array();
		$data['pagetitle'] = "Privileges";
		$data['breadcrumb'] = $this->menu->createBreadCrumb($breadcrumb);
		$data['table'] = $this->tablebuild->generateTable($target,$configArr);
		$data['javascriptsave'] = $this->ajaxsave->buildSaveJs($target,$configArr);	
		$data['addmodal'] = $this->modalsbuild->generateModal($target,$modal);
		$data['addmodal'] .= $this->modalsbuild->generateAssocModal();
		
		// set template layout to use
		$this->template->set_layout('default');
		
		// load view
		$this->template->build('administration/users', $data);
	}
}
