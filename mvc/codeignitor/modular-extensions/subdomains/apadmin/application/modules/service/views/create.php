<script type='text/javascript'>
			var config_dir  = '';
			var target = 'roles';
		$(document).ready(function() {
			$('#loginsave_form').validate({
				onsubmit: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
				},
				errorPlacement: function(error, element) {
					$(element).closest('div').append(error);
				},
				rules: {
					uber_plan_id:{required:true},	
					slug:{required:true},
					name:{required:true},
			<?php   foreach($num_months as $month) : ?>
					price<?php echo $month; ?> :{required:true,number:true},
					setup_fee<?php echo $month; ?> :{required:true,number:true},
					variant<?php echo $month; ?> :{required:true},
			<?php	endforeach; ?>
					cost_type:{required:true},
					brand_id:{required:true},
					type:{required:true},
					cost:{required:true,number:true}
				}
			});
			$("#servicesave").click(function(){
				if (!$('#loginsave_form').valid()) {
						return;
					}
				document.getElementById('loginsave_form').submit();
			});
			
			$(".showit").click(function(){
				var id = $(this).attr('id').replace('show','');
				$("#recurrance" + id).toggle();
			})
			
		});
	
</script>
<?php
$servicetype = array(
				''			=> 'Select Type',
				'UPSELL'	=> 'Upsell',
				'DOMAIN'	=> 'Domain',
				'HOSTING'	=> 'Hosting',
				'SUPPORT'	=> 'Support'
		);

$costtypes = array(''=>'Select Cost Type',"%"=>"%","$"=>"$")
?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Add Services</h3>
		<div class="row-fluid">
			<div class="span8" id="service_block">
				<form class="form-horizontal" id="loginsave_form" method="post" action="<?php echo $this->config->item('subdir'); ?>/service/create">
					
					<div class="control-group formSep">
							<label class="control-label">&nbsp;</label>
							<div class="controls text_line" id="errorloginsave">
								<strong><?php echo $error;?></strong>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Brands</label>
							<div class="controls">
							<select id="brand_id"  name="brand_id">
								<?php 
								$brand_id = isset($service['brand_id']) ? $service['brand_id'] :'4';
								foreach ($brands as $key=>$title) :
									
									$c = $brand_id == $title['id'] ? ' selected="selected"':'';
									echo "<option value='{$title['id']}'$c>{$title['name']}</option>";
								
								endforeach;
								?>
							</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Uber Plan ID</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<input size="16" class="span5" type="text" id="uber_plan_id" name="uber_plan_id" value="<?php echo (isset($service['uber_plan_id'])) ?  $service['uber_plan_id'] :'';?>">
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label class="control-label">Service Name</label>
							<div class="controls">
								<input type="text" id="name" name="name" class="input-xlarge" value="<?php echo (isset($service['name'])) ? $service['name']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Slug</label>
							<div class="controls">
								<input type="text" id="slug" name="slug" class="input-xlarge" value="<?php echo (isset($service['slug'])) ? ( empty($service['slug']) ? '' : $service['slug'] ) :'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Type</label>
							<div class="controls">
							<select id="type" name="type">
								<?php 
								$service_type = isset($service['type']) ? $service['type'] :'';
								foreach ($servicetype as $val=>$title) :
									
									$c = $service_type == $val ? ' selected="selected"':'';
									echo "<option value='$val'$c>$title</option>";
								
								endforeach;
								?>
							</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Cost</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" name="cost" id="cost" value="<?php echo (isset($service['cost'])) ?  $service['cost'] :'';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Cost Type</label>
							<div class="controls">
							<select id="cost_type" name="cost_type">
								<?php 
								$cost_type = isset($service['cost_type']) ? $service['cost_type'] :'';
								foreach ($costtypes as $val=>$title) :
									
									$c = $cost_type == $val ? ' selected="selected"':'';
									echo "<option value='$val'$c>$title</option>";
								
								endforeach;
								?>
							</select>
							</div>
						</div>
						<?php foreach($num_months as $month) : ?>
						<div class="control-group formSep">
							<label class="control-label" style="font-size:1.1em;">Recurrence : 	<a href='javascript:void(0);'  title="Expand this to work it man!" class='showit' id='show<?php echo $month;?>'><i class="splashy-download"></i></a></label>
							<div class="controls">
								<label class="checkbox inline">
									<input type="checkbox" name="num_months<?php echo $month;?>" class='hideit' id="num_months<?php echo $month;?>" value="<?php echo $month;?>"<?php echo (isset($service['defaults'][$month]['num_months'])) ?  " checked" :'';?>>
								<?php echo $month;?> Months
								</label>
								<?php if(isset($service['defaults'][$month]['id'])) : ?>
								<input type="hidden" name="id<?php echo $month;?>" value="<?php echo $service['defaults'][$month]['id']; ?>">
								<?php endif; ?>
							</div>
						</div>
						<div id="recurrance<?php echo $month;?>" style='display:none;'>
						<div class="control-group formSep element<?php echo $month;?>">
							<label for="u_email" class="control-label">Default Price</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" name="price<?php echo $month;?>" id="price<?php echo $month;?>" value="<?php echo (isset($service['defaults'][$month]['price'])) ?  $service['defaults'][$month]['price'] :'0';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>
						<div class="control-group formSep element<?php echo $month;?>">
							<label for="u_email" class="control-label">Setup Fee</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" id="setup_fee<?php echo $month;?>" name="setup_fee<?php echo $month;?>" value="<?php echo (isset($service['defaults'][$month]['setup_fee'])) ?  $service['defaults'][$month]['setup_fee'] :'0';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>
					<div class="control-group formSep element<?php echo $month;?>">
							<label for="u_email" class="control-label">Cost</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" id="cost<?php echo $month;?>" name="cost<?php echo $month;?>" value="<?php echo (isset($service['defaults'][$month]['cost'])) ?  $service['defaults'][$month]['cost'] :'0';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>
						<div class="control-group formSep element<?php echo $month;?>">
							<label for="u_email" class="control-label">Variant</label>
							<div class="controls">
								
								<input type="text" id="variant<?php echo $month;?>" name="variant<?php echo $month;?>" class="input-xlarge" value="<?php echo (isset($service['defaults'][$month]['variant'])) ? $service['defaults'][$month]['variant'] :'default';?>" />
							</div>
						</div>
						</div>
						<?php endforeach; ?>
						<?php 
						$service['variations'][]['id'] = 0;
						$x=25;
						if(isset($service['variations']) ) :
							
							foreach($service['variations'] as $var) : 
							$x++;
							//echo $index['id'];
							?>
							<div class="control-group formSep">
								<label class="control-label" style="font-size:1.1em;">Recurrence :<a href='javascript:void(0);' title="Expand this to work it man!" class='showit' id='show<?php echo $x;?>'><i class="splashy-download"></i></a></label>
								<div class="controls">
									<?php if(isset($var['id'])) : 
										
										$idv = $var['id'];
									?>
									<input type="hidden" name="variations[]" value="<?php echo $var['id']; ?>">
									<?php endif; ?>
									<div class="input-prepend input-append">
									<input size="16" class="span5" type="text" name="numonths[<?php echo $idv;?>]"  value="<?php echo (isset($var['num_months'])) ?  $var['num_months'] :'0';?>">
									<?php if($idv == 0 ) : echo "<input type='checkbox' name='addit'> check to add "; endif; ?>
									</div>
									
								</div>
							</div>
							<div id="recurrance<?php echo $x;?>" style='display:none;'>
							<div class="control-group formSep">
								<label for="u_email" class="control-label">Default Price</label>
								<div class="controls">

									<div class="input-prepend input-append">
										<span class="add-on">$</span><input size="16" class="span5" type="text" name="prices[<?php echo $idv;?>]"  value="<?php echo (isset($var['price'])) ?  $var['price'] :'0';?>"><span class="add-on">.00</span>
									</div>
								</div>
							</div>
							<div class="control-group formSep">
								<label for="u_email" class="control-label">Setup Fee</label>
								<div class="controls">

									<div class="input-prepend input-append">
										<span class="add-on">$</span><input size="16" class="span5" type="text"  name="setup_fees[<?php echo $idv;?>]" value="<?php echo (isset($var['setup_fee'])) ?  $var['setup_fee'] :'0';?>"><span class="add-on">.00</span>
									</div>
								</div>
							</div>
							<div class="control-group formSep">
								<label for="u_email" class="control-label">Cost</label>
								<div class="controls">

									<div class="input-prepend input-append">
										<span class="add-on">$</span><input size="16" class="span5" type="text"  name="costs[<?php echo $idv;?>]" value="<?php echo (isset($var['cost'])) ?  $var['cost'] :'0';?>"><span class="add-on">.00</span>
									</div>
								</div>
							</div>
							<div class="control-group formSep">
								<label for="u_email" class="control-label">Variant</label>
								<div class="controls">

									<input type="text"  name="variants[<?php echo $idv;?>]" class="input-xlarge" value="<?php echo (isset($var['variant'])) ? $var['variant'] :'default';?>" />
								</div>
							</div>
							</div>
							<?php endforeach; 
						endif;
						?>
						<!--
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Min $</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" name="min" value="<?php echo (isset($service['min'])) ?  $service['min'] :'';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="u_email" class="control-label">Max $</label>
							<div class="controls">
								
								<div class="input-prepend input-append">
									<span class="add-on">$</span><input size="16" class="span5" type="text" name="max" value="<?php echo (isset($service['max'])) ?  $service['max'] :'';?>"><span class="add-on">.00</span>
								</div>
							</div>
						</div>-->
						
						<div class="control-group">
							<div class="controls">
								<input type="hidden" id='brand_service_id' name='brand_service_id' value="<?php echo (isset($service['brand_service_id'])) ?  $service['brand_service_id'] :'';?>">
								<input type="hidden" id='service_id' name='service_id' value="<?php echo (isset($service['service_id'])) ?  $service['service_id'] :'';?>">
								<button class="btn btn-gebo" id="servicesave" type="button">Add / Update Service</button>
							</div>
						</div>	
				</form>
			</div>
		</div>
	</div>
</div>
