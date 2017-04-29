<?php $this->lang->load('clients_menu'); ?>

<!--sideLeft-->
<aside id="sideLeft">

	<!--Menu-->
	<a name="menu"></a>
	<ul class="menu" >
		
		<li>
			<a href="#"><span class="four-prong"><?php echo $this->lang->line('clients_menu_advertising'); ?></span></a>
		</li>
			
		<li>
			<a href="#"><span class="four-prong"><?php echo $this->lang->line('clients_menu_billing'); ?></span></a>
			<ul class="acitem">
				<li><a href="#"><?php echo $this->lang->line('clients_menu_pay_methods'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_invoices'); ?></a></li>
			</ul>
		</li>
		
		<li>
			<a href="#"><span class="four-prong"><?php echo $this->lang->line('clients_menu_account'); ?></span></a>
			<ul class="acitem">
				<li><a href="#"><?php echo $this->lang->line('clients_menu_password'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_contact'); ?></a></li>
			</ul>
		</li>
		
		<li>
			<a href="#"><span class="four-prong"><?php echo $this->lang->line('clients_menu_support'); ?></span></a>
			<ul class="acitem">
				<li><a href="#"><?php echo $this->lang->line('clients_menu_faq'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_open_ticket'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_view_tickets'); ?></a></li>
			</ul>
		</li>
		
		<li>
			<a href="#"><span class="four-prong"><?php echo $this->lang->line('clients_menu_services'); ?></span></a>
		</li>

		
		<li class="expand">
			<a href="#"><span class="four-prong">Testdomain.com</span></a>
			<ul class="acitem">
				<li class="select_item"><a href="#"><?php echo $this->lang->line('clients_menu_cpanel'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_seo'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_admin'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_email'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_ftp'); ?></a></li>
			</ul>
		</li>
		
		<li>
			<a href="#"><span class="four-prong">Exampledomain.com</span></a>
			<ul class="acitem">
				<li><a href="#"><?php echo $this->lang->line('clients_menu_cpanel'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_seo'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_admin'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_email'); ?></a></li>
				<li><a href="#"><?php echo $this->lang->line('clients_menu_ftp'); ?></a></li>
			</ul>
		</li>
		
	</ul>	
	<!--Menu END-->

	
</aside><!-- #sideLeft END-->