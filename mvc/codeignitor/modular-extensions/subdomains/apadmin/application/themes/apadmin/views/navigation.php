<header>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="/home"><i class="icon-home icon-white"></i>     All Phase Admin Panel</a>
                <ul class="nav user_menu pull-right">
                    <li class="hidden-phone hidden-tablet">
                        <div class="nb_boxes clearfix">
                            <!-- <a data-toggle="modal" data-backdrop="static" href="#myMail" class="label ttip_b" oldtitle="New messages">25 <i class="splashy-mail_light"></i></a> -->
                            <!-- <a data-toggle="modal" data-backdrop="static" href="#myTasks" class="label ttip_b" oldtitle="New tasks">10 <i class="splashy-calendar_week"></i></a> -->
                        </div>
                    </li>
                    
                    <li class="divider-vertical hidden-phone hidden-tablet"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/img/user_avatar.png" alt="" class="user_avatar"><?php echo $this->session->userdata('name'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">My Profile</a></li>
                            <li><a href="#">Calendar</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $this->config->item('subdir'); ?>/logout">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
                <a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
                    <span class="icon-align-justify icon-white"></span>
                </a>
                <nav>
                    <div class="nav-collapse">
                        <ul class="nav">
							<?php
									$login_id = $this->session->userdata('login_id');
									$apc = "menutop_$login_id";
									//if( apc_exists($apc)):
									//	echo apc_fetch($apc);
									//else:
										$this->load->library('menu');
										$menu = $this->menu->startTopMenu(0,'TOP',$login_id);
										//apc_add($apc,$menu);
										echo $menu;
									//endif;
									/*	
								?>
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-off icon-white"></i> Admin <b class="caret"></b></a>
                                <ul class="dropdown-menu">
									
								
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/administration/menus/edit">Menus</a></li>
                                    <li class="dropdown sub-dropdown">
                                        <a href="#">Users <b class="caret-right"></b></a>
                                        <ul class="dropdown-menu sub-menu">
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/administration/logins/adminlogins">Admin Logins</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/administration/logins/roles">Roles</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/administration/logins/privileges">Privileges</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
							
                            <!--
                            <li>
                                <a href="/calendar/view"><i class="icon-th icon-white"></i> Calendar</a>
                            </li> 
                            -->

                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-list-alt icon-white"></i> Fulfillment <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown sub-dropdown">
                                        <a href="#">Builder <b class="caret-right"></b></a>
                                        <ul class="dropdown-menu sub-menu">
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/builder/create">Create</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/builder/queue">Queue</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/builder/view">View</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown sub-dropdown">
                                        <a href="#">Partner <b class="caret-right"></b></a>
                                        <ul class="dropdown-menu sub-menu">
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/partner/errors">Errors</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/partner/queue">Queue</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown sub-dropdown">
                                        <a href="#">Service <b class="caret-right"></b></a>
                                        <ul class="dropdown-menu sub-menu">
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/service/errors">Errors</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/fulfillment/service/queue">Queue</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-wrench icon-white"></i> Funnels <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/funnels/assign">Assign</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/funnels/create">Create</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/funnels/statistics">Statistics</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/funnels/view">View</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-file icon-white"></i> Pages <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/page/create">Create</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/page/view">View</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-user icon-white"></i> Partners <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <!--
                                    <li class="dropdown sub-dropdown">
                                        <a href="#">Affiliates <b class="caret-right"></b></a>
                                        <ul class="dropdown-menu sub-menu">
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/logins<?php echo $this->config->item('subdir'); ?>logins">Create</a></li>
                                            <li><a href="<?php echo $this->config->item('subdir'); ?>/logins<?php echo $this->config->item('subdir'); ?>logins">View</a></li>
                                        </ul>
                                    </li>
                                    -->
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/partner/create">Create</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/partner/view">View</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">
                                <i class="icon-book icon-white"></i> Services <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/service/create">Create</a></li>
                                    <li><a href="<?php echo $this->config->item('subdir'); ?>/service/view">View</a></li>
                                </ul>
                            </li>
							*/ ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="modal hide fade" id="myMail">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>New messages</h3>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
            <table class="table table-condensed table-striped" data-rowlink="a">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Size</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Declan Pamphlett</td>
                        <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                        <td>23/05/2012</td>
                        <td>25KB</td>
                    </tr>
                    <tr>
                        <td>Erin Church</td>
                        <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                        <td>24/05/2012</td>
                        <td>15KB</td>
                    </tr>
                    <tr>
                        <td>Koby Auld</td>
                        <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                        <td>25/05/2012</td>
                        <td>28KB</td>
                    </tr>
                    <tr>
                        <td>Anthony Pound</td>
                        <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                        <td>25/05/2012</td>
                        <td>33KB</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="btn">Go to mailbox</a>
        </div>
    </div>
    <div class="modal hide fade" id="myTasks">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>New Tasks</h3>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
            <table class="table table-condensed table-striped" data-rowlink="a">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Summary</th>
                        <th>Updated</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>P-23</td>
                        <td><a href="javascript:void(0)">Admin should not break if URL…</a></td>
                        <td>23/05/2012</td>
                        <td class="tac"><span class="label label-important">High</span></td>
                        <td>Open</td>
                    </tr>
                    <tr>
                        <td>P-18</td>
                        <td><a href="javascript:void(0)">Displaying submenus in custom…</a></td>
                        <td>22/05/2012</td>
                        <td class="tac"><span class="label label-warning">Medium</span></td>
                        <td>Reopen</td>
                    </tr>
                    <tr>
                        <td>P-25</td>
                        <td><a href="javascript:void(0)">Featured image on post types…</a></td>
                        <td>22/05/2012</td>
                        <td class="tac"><span class="label label-success">Low</span></td>
                        <td>Updated</td>
                    </tr>
                    <tr>
                        <td>P-10</td>
                        <td><a href="javascript:void(0)">Multiple feed fixes and…</a></td>
                        <td>17/05/2012</td>
                        <td class="tac"><span class="label label-warning">Medium</span></td>
                        <td>Open</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="btn">Go to task manager</a>
        </div>
    </div>
</header>