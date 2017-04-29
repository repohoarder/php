<?php
// fix for subdirectories
$subdir 	= ($this->config->item('subdir'))? $this->config->item('subdir'): '';
?>

		<header id="t-branding" class="custom-border1">
			<div class="center-width">
				<?php
					$partner_info	= $this->session->userdata('partner_info');
					$partner_id		= $this->session->userdata('partner_id');
					$company		= $partner_info['website']['company_name'];
					$logo_type		= $partner_info['website']['logo_type'];
					$logo			= $partner_info['website']['logo'];
					$logo_file		= $partner_info['website']['logo_file'];
					$domain 		= $partner_info['website']['domain'];
				?>
				<a href="http://www.<?php echo $domain; ?>">
					<?php if (!$partner_info || $partner_id==1): ?>
						<img src="/resources/allphase_funnel/img/logo.png" alt="All Phase Web Hosting, LLC" />
					<?php elseif ($logo_type=='upload'): ?>
						<img src="<?php echo $logo_file; ?>" alt="<?php echo $company; ?>" />
					<?php elseif (!$logo): ?>
						<span><?php echo $company; ?></span>
					<?php else: ?>

						<!--<span style="text-decoration:none;"><?php //echo $this->_format_logo($logo); ?></span>-->
						<span><?php echo $logo; ?></span>

					<?php endif; ?>
				</a>
			</div>
		</header>