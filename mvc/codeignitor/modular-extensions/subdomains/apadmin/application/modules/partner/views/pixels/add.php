<?php
$pixeltype = array(
				'thank_you'=> 'Thank You',
				'all'	   => 'All',
				'landing'  => 'Landing',
				'billing'  => 'Billing',
				'website'  => 'Funnel'
		);
?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Add Partner Pixel</h3>
		<div class="row-fluid">
			<div class="span8">
				<form class="form-horizontal" id="loginsave_form" method="post" action="/partner/pixels/add">
					<fieldset>
					<div class="control-group formSep">
							<label class="control-label">&nbsp;</label>
							<div class="controls text_line" id="errorloginsave">
								<strong><?php echo $error;?></strong>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">Pixel Name</label>
							<div class="controls">
								<input type="text" id="name" name="name" class="input-xlarge" value="<?php echo (isset($pixel['name'])) ? $pixel['name']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">Partner Name</label>
							<div class="controls">
							<select id="chosen_a"  class="multiselect" data-placeholder="Choose a Partner..." name="partner_id">
								<option value=''>Choose a Partner</option>
							<?php
							$partnerid =  (isset($pixel['partner_id'])) ? $pixel['partner_id']:'';
							foreach($partners as $key=>$partner):
								
								$c = $partnerid == $partner['id'] ? 'selected="selected"' :'';
								echo "<option value='{$partner['id']}'$c>{$partner['company']}</option>";
								
							endforeach;
							?>
							</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Affiliate ID</label>
							<div class="controls">
								<input type="text" id="affiliate_id" name="affiliate_id" class="input-xlarge" value="<?php echo (isset($pixel['affiliate_id'])) ? ( empty($pixel['affiliate_id']) ? '' : $pixel['affiliate_id'] ) :'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Offer ID</label>
							<div class="controls">
								<input type="text" id="offer_id" name="offer_id" class="input-xlarge" value="<?php echo (isset($pixel['offer_id'])) ? (empty($pixel['offer_id']) ? '' : $pixel['offer_id']) :'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Type</label>
							<div class="controls">
							<select id="type"  name="type">
								<?php 
								$pixel_type = isset($pixel['type']) ? $pixel['type'] :'';
								foreach ($pixeltype as $val=>$title) :
									
									$c = $pixel_type == $val ? ' selected="selected"':'';
									echo "<option value='$val'$c>$title</option>";
								
								endforeach;
								?>
							</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Pixel</label>
							<div class="controls">
								<textarea id="pixel" name="pixel" class='span8' rows="20" cols="40"><?php echo (isset($pixel['pixel'])) ? htmlentities(stripslashes($pixel['pixel'])):'';?></textarea>
								<input type='hidden' id='pixel_id' name='pixel_id' value="<?php echo (isset($pixel['id'])) ? $pixel['id']:'0';?>">
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-gebo" id="loginsave" type="submit">Add Pixel</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
