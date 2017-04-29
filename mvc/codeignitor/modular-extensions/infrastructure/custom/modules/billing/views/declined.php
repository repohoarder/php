
<div class="padder">

	<div class="cc_ceclined_page">

		<img src="/resources/brainhost/img/declined/cc_declined_av.jpg" alt="<?php echo $this->lang->line('declined_img_alt');?>" class="cc_declined_av" border="0" height="203" width="277">

		<div class="cc_declined_hd_txt">
			<h1><?php echo $this->lang->line('declined_sorry');?></h1>
			<h2><?php echo $this->lang->line('declined_failed');?></h2>
		</div><!-- .cc_declined_hd_txt -->
		
		<p class="top_p">
			<?php echo $this->lang->line('declined_reasons');?>
		</p>

		<div style="clear: both; margin-top: 25px;"></div>

		<div class="cc_hr"><hr></div>
		<div class="cc_declined_contact_us">

			<?php 

			if (isset($partner_info['website']['support_phone']) && $partner_info['website']['support_phone']):

				echo $this->lang->line('declined_phone', _format_phone($partner_info['website']['support_phone']));

			else:

				// set support email variable
				$support_email 	= ($partner_info['website']['support_email'])? $partner_info['website']['support_email']: 'support@brainhost.com';

				echo '<center>'.$this->lang->line('declined_email', $support_email).'</center>';

			endif;

			?>

		</div>

		<div class="cc_hr"><hr></div>

		<p style="margin-top: 25px;">
			<?php echo $this->lang->line('declined_reps');?>
		</p>

		<p>
			<?php echo $error; ?>
		</p>


	</div>




<?php

/**
 * This function formats the phone number
 * @param  [type] $phone [description]
 * @return [type]        [description]
 */
function _format_phone($phone)
{

	$phone = str_replace(array('-',')','(','.'),'',$phone);

	if (strlen($phone) < 10):

		return $phone;

	endif;

	$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $phone);

	return $phone;
}

?>