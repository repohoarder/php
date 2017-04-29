<?php 

$is_lanty = in_array($this->session->userdata('_pre_arpu_partner_id'), array('251', '169', '225'));

$headline = $this->lang->line('suggestion_title');

if ($is_lanty):

	$headline = 'Cash Gaps Still Available';

endif;

?>

<form action="" method="post">
	<fieldset>
		<h2><?php echo $headline; ?></h2>

		<?php if ($is_lanty): ?>
			
			<div style="text-align:center;font-size:1.3em;line-height:1.6em;">

				<p style="text-align:center;margin:0 0 10px;padding:0">Looks like the cash gap you chose is already taken. <br/> Don't worry though; here are several more available in your area:</p>
				
				<div style="font-weight:bold;background:#3276B1;color:#fff;padding:10px;border-radius:10px;border:2px solid #5095CE;margin:0 40px 10px"><p style="padding:0;margin:0;">Choose one of the few remaining cash gaps in your area, click continue and <br/> let's get you plugged in and making money right away!</p></div>

			</div>

		<?php else: ?>

			<div class="errors_wrapper">
				<ul class="errors">

					<li><?php echo $this->lang->line('suggestion_sorry'); ?></li> 
					<li><?php echo $this->lang->line('suggestion_available'); ?></li>

				</ul>
				<div style="clear:both;"></div>
			</div>

		<?php endif; ?>

		<table class="suggestions">
			<tbody>

				<?php 
				$check       = 'checked="checked"';
				
				$suggestions = (isset($suggestions) && is_array($suggestions)) ? $suggestions : array();
				
				$num_suggs  = count($suggestions);
				$mid         = ($num_suggs > 1) ? ceil($num_suggs/2) : 0;
				
				$count       = 0;

				foreach ($suggestions as $key => $suggestion): 

					$count++;?> 

					<tr>
						<td>
							<input type="radio" name="core_domain" id="radSuggested<?php echo $key; ?>" value="<?php echo $suggestion;?>" <?php echo $check; ?>/>
						</td>
						<td>
							<label for="radSuggested<?php echo $key; ?>"><?php echo $suggestion;?></label>
						</td>
					</tr>
				
					<?php 

					$check = '';

					if ($count==$mid): ?>

								</tbody>
							</table>
							<table class="suggestions">
								<tbody>

						<?php

					endif;

				endforeach; ?>
			</tbody>
		</table>

		<div style="clear:both;"></div>

		<input type="hidden" name="core_type" value="register" />
		<input type="hidden" name="suggestions" value="1" />

		<input type="hidden" name="action_id" value="52"/>
		<input type="submit" value="<?php echo $this->lang->line('suggestion_continue'); ?>" class="center" />
	</fieldset>
</form>