<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>



<h3 class="heading">Client Payment Records</h3>

		<div id="service_table_wrapper" class="dataTables_wrapper form-inline" role="grid">
		<form method="post" action="<?php echo $this->config->item('subdir'); ?>/clients/refunds">
			<input type="text" name="search" value="<?php echo $search;?>">
			<input type="submit" name="searchit" value="Search" >
		</form>
			<form method="post" action="<?php echo $this->config->item('subdir'); ?>/clients/refunds">
				<h3><?php echo $error;?></h3>
			<table class="table table-striped table-bordered table-condensed  uafix" id="service_table" aria-describedby="service_table_info">
				

				<tbody role="alert" aria-live="polite" aria-relevant="all">

					<?php
					// iterate services
					$i=0;
					foreach ($list AS $payment_record_id=>$value):

						// set odd/even class variable
						$class		= ($i % 2 == 0)? 'even': 'odd';
					if($i == 0) :
					?>
						<tr><th colspan='2' style="background-color:#e3e3e3;">
								<table class="table table-striped table-bordered table-condensed  uafix">
										<tr>
											<td>Name</td><td><?php echo $value['customerinfo']['first']. " " .$value['customerinfo']['last'];?></td><td>Address #</td><td><?php echo $value['customerinfo']['address'];?></td>
										</tr>
										<tr>
											<td>Phone</td><td><?php echo $value['customerinfo']['phone'];?></td><td>City</td><td><?php echo $value['customerinfo']['city'];?></td>
										</tr>
										<tr>
											<td>Email</td><td><?php echo $value['customerinfo']['email'];?></td><td>State </td><td><?php echo $value['customerinfo']['state'];?></td>
										</tr>
										<tr>
											<td>&nbsp;</td><td>&nbsp;</td><td>Zip </td><td><?php echo $value['customerinfo']['zip'];?></td>
										</tr>
								</table>
							</th>
						</tr>
						<tr id="record_<?php echo $value['pay_record_id']; ?>">
							<td><input type="hidden" name="clientid" value="<?php echo $value['clientid'];?>">Client ID# : <?php echo $value['clientid'];?></td><td><input style='vertical-align:top;' type="checkbox" name="deactivate" value="<?php echo $value['clientid'];?>"> Deactivate Client</td>
						</tr>
					<?php endif; ?>
						<tr><td colspan="2">Payment Records</td></tr>
						<tr><td valign='top'>
								<table class="table table-striped table-bordered table-condensed  uafix">
									<tr>
										<td>Invoice #</td><td><?php echo $value['invid'];?></td>
									</tr>
									<tr>	
										<td>Transaction #</td><td><?php echo $value['transaction_id'];?></td>
									</tr>
									<tr>
										<td>Date </td><td><?php echo date("Y-m-d",$value['time']);?></td>
									</tr>
									<tr>
										<td>Amount </td><td><?php echo $value['amount'];?></td>
									</tr>
									<tr><td>Refunded</td><td><?php echo $value['refunded'];?></td></tr>
									<tr>
										<td colspan='2'><input  style='vertical-align:top;' type="checkbox" value="<?php echo $value['amount'];?>" name="refund_entire[<?php echo $value['pay_record_id'];?>]"  <?php echo $value['refunded'] == $value['amount'] ? " disabled" : '';?>> Refund entire amount</td>
									</tr>
									<tr><td colspan='2'>Add Note when refunding<br><textarea name="note[<?php echo $value['pay_record_id'];?>]"></textarea></td></tr>
								</table>
							</td>
							<td>
								<p> Payment Item Breakdown</p>
								<table  class="table table-striped table-bordered table-condensed  uafix">
									<tr>
										<th>Refund</th>
										<th>Product</th>
										<th>Amount</th>
										<th>Refunded</th>
									</tr>
								<?php foreach ($value['items'] as $item) : ?>
									<tr>
										<td><input  style='vertical-align:top;' type="checkbox" name="individual[<?php echo $value['pay_record_id'];?>][<?php echo $item['payment_id'];?>]" value="<?php echo $item['amount'];?>" <?php echo $item['refunded'] == $item['amount'] ? " disabled" : '';?>>
										<input type="hidden" name="packid[<?php echo $item['payment_id'];?>]" value="<?php echo $item['packid'];?>">
										</td>
										<td><?php echo $item['desserv'];?></td>
										<td><?php echo $item['amount'];?></td>
										<td><?php echo $item['refunded'];?></td>
									</tr>
								<?php
								endforeach; 
								?>
								</table>
							</td>
						</tr>
					<?php
						// increment counter
						$i++;
					endforeach;
					?>

				</tbody>
			</table>
				<button class="btn btn-danger" type="submit">Refund Selected Items</button>
		</form>
		</div>

