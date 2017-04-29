<?php

//$this->debug->show($services);

?>
		<h3 class="heading">Partner Listing</h3>
		<p><?php echo $error;?></p>
		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">
			<form method="post" action="">
				<div class="control-group formSep">
							<label for="description" class="control-label">Partner</label>
							<div class="controls">
							
				<select name='partner_id'>
					<?php
					// iterate services
					$i=0;
					foreach ($list AS $key => $value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
						if ($value['uber_client_id']>0)
						{
							$class	.= ' registered';
							$status	 = 'Registered';
						}
						elseif($value['active']==1)
						{
							$class	.= ' active';
							$status	 = 'Activated';
						}
						else
						{
							$class	.= ' queued';
							$status	 = 'Queued';
						}
						echo "";
						if($status == 'Registered') :
						echo "<option value='{$value['id']}'>{$value['first_name']} {$value['last_name']}</option>";
						endif;
					endforeach;
				?>
					</select>
							</div>
						</div>
				<div class="control-group formSep">
							<label for="description" class="control-label">Order ID</label>
							<div class="controls">
					<input type="text" name="orderid">
					</div>
						</div>
				<div class="control-group formSep">
							<label for="description" class="control-label"></label>
							<div class="controls">
					<button type="submit" id="save_menus" class="btn btn-inverse">Assign</button>
					</div>
						</div>
			</form>
		</div>

