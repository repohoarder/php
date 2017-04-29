<?php
// grab partner details from session
$partner 	= $this->session->userdata('partner');
?>


<h1>Partner Support</h1>
<?php if($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; ?>
<section id="pnl-accordion">
	
<!-- Sales Statistics view ************************************************************************************* -->

	<h2>Partner Support</h2>
	<div class="module s-support">
		<div class="pad">
			<p>At All Phase Hosting, you have a dedicated Account Manager who provides quality guidance and support every step of the way.</p>
			<hr />
			<div class="nav-manager" style="float:left;width:100%;padding:0 0 25px 0;">
				<hgroup>
					<h3>Your Account Manager Is:</h3>
					<h4><?php echo $partner['manager']['first_name'].' '.$partner['manager']['last_name']; ?></h4>
				</hgroup>
				<img src="/resources/allphase/img/managers/<?php echo $partner['manager']['image']; ?>" alt="<?php echo $partner['manager']['first_name'].' '.$partner['manager']['last_name']; ?>" height="78" width="111" />
				<div class="info">
					<p class="email"><?php echo $partner['manager']['email']; ?></p>
					<p class="phone"><?php echo $partner['manager']['phone']; ?></p>
					<p class="chat"><?php echo $partner['manager']['skype_name']; ?></p>
				</div>
			</div>
			<hr />
			<p>You may also open a support ticket here. Please fill out the form below.</p>
		</div>
		<?php



echo form_open('support');

echo '<label for="subject">Subject</label>'.form_input(
	array(
		'name'			=> 'subject',
		'id'			=> 'subject',
		'type'			=> 'text',
		'value'			=> '',
		'placeholder'	=> 'Subject'
	)
).'<br><br>';

echo '<label for="message">Message</label>'.form_textarea(
	array(
		'name'			=> 'message',
		'id'			=> 'message',
		'value'			=> '',
		'placeholder'	=> 'Message',
		'rows'			=> 10,
		'cols'			=> 50
	)
).'<br><br>';

echo form_input(
	array(
		'name'			=> 'submit',
		'id'			=> 'submit',
		'type'			=> 'submit',
		'class'			=> 'btn-contact',
		'value'			=> 'Send'
	)
);

echo form_close();
	?>
	</div>
</section>
