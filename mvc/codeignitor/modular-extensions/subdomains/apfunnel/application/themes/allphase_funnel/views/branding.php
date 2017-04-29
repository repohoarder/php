		<?php 

		$is_lanty = in_array($this->session->userdata('_pre_arpu_partner_id'), array('251', '169', '225'));
		//$is_lanty = ($this->session->userdata('_pre_arpu_partner_id') == '251'); 

		if ( ! $is_lanty): ?>

			<header id="t-branding">
				<div class="center-width">
					<?php

						$partner_info	= $this->session->userdata('partner_info');
						$partner_id		= $this->session->userdata('partner_id');
						$company		= $partner_info['website']['company_name'];
						$logo_type		= $partner_info['website']['logo_type'];
						$logo			= $partner_info['website']['logo'];
						$logo_file		= $partner_info['website']['logo_file'];
						$domain 		= $partner_info['website']['domain'];

						if ($is_lanty && ( ! $partner_info || $Partner_id == 1)):

							$logo    = 'Cool Step';
							$company = 'Cool Step';
							$domain  = 'coolstep.com';

						endif;

					?>
					
					<a href="http://www.<?php echo $domain; ?>">
						<?php if ( ! $is_lanty && (!$partner_info || $partner_id==1)): ?>
							<img src="/resources/allphase_funnel/img/logo.png" alt="All Phase Web Hosting, LLC" />
						<?php elseif ($logo_type=='upload' ): ?>
							<img src="<?php echo $logo_file; ?>" alt="<?php echo $company; ?>" />
						<?php elseif (!$logo): ?>
							<span><?php echo $company; ?></span>
						<?php else: ?>

							<!--<span style="text-decoration:none;"><?php echo _format_logo($logo); ?></span>-->
							<span><?php echo $logo; ?></span>

						<?php endif; ?>
					</a>
				</div>
			</header>

		<?php endif; ?>


		<?php

		function _format_logo($logo)
		{
			// explode the company name
			$exploded 	= explode(' ',$logo);

			// grab word count
			$count 		= count($exploded);

			// initialize variables
			$logo 		= '<span style="color: #333333;font-family: Copperplate / Copperplate Gothic Light, sans-serif;">';

			// iterate words
			foreach ($exploded AS $key => $value):

				// if this is the last word, we need to wrap it with different styles
				if (($key+1) == $count):

					// close the original span
					$logo 	.= '</span>';

					// create new span with different styles
					$logo 	.= '<span style="color: #333333;font-family: ‘Arial Black’, Gadget, sans-serif;">'.$value.'</span>';

				else: 	// this isn't the last word

					$logo 	.= $value.' ';

				endif;

			endforeach;

			return $logo;
		}