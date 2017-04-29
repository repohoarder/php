<?php

$total = 0;


$form_submission = '';
$attributes      = array(
	'method' => 'POST'
);

echo form_open(
	$form_submission,
	$attributes,
	array(
		'modify_packs' => '1'
	)	
);
?>
<script>
$(document).ready(function(){
	$(".editplan").click(function(){
		var id = $(this).attr('id').replace('edit','');
		$('#update'+id).toggle();
	});
	$("#updatehosting").click(function(){
		var slug = $("#new_plan").val();
		var price = $("#" +slug+"price").val();
		var num_months = $("#" +slug+"num_months").val();
		var setup_fee = $("#" +slug+"setup_fee").val();
		var trial_discount = $("#" +slug+"trial_discount").val();
		var plan_id = $("#" +slug+"plan_id").val();
		var current_slug = $("#current_slug").val();
		var total = $("#orgintotal").val();
		var pack = $("#" +slug+"pack").val();
		// if slug is not the same
		///if( current_slug != slug) {
			$.post('/confirmation/updatehosting', 
			{
				slug : slug,
				price : price,
				num_months : num_months,
				setup_fee : setup_fee,
				trial_discount : trial_discount,
				plan_id : plan_id,
				pack : pack,
				total: total
				},
			function(data) {
				//if no error update prices on page
				if (!data.error) {
					$("#current_slug").val(slug);
					$("#slug" + plan_id).html('('+slug+")");
					$("#price" + plan_id).html('$' + data.price);
					$("#setup" + plan_id).html('$' + data.setup);
					$("#totalamount").html('$' + data.total);
				}
			}, 'json'
			);
		//}
	});
});
function update_prices(slug){

}
</script>
<style type="text/css">

#remove_chk:hover{text-decoration:underline;}

#purchase {

	padding:3px 20px;cursor:pointer;
	border:none;
	font-size:25px;
	font-weight:500;
	text-align:center;
	text-shadow:-1px -1px 1px #555;
	color:#fff;
	-webkit-border-radius:5px;
	border-radius:5px;
	margin:0;

	background: #76b01d; /* Old browsers */
	background: -moz-linear-gradient(top, #76b01d 0%, #548e13 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#76b01d), color-stop(100%,#548e13)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #76b01d 0%,#548e13 100%); /* IE10+ */
	background: linear-gradient(to bottom, #76b01d 0%,#548e13 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#76b01d', endColorstr='#548e13',GradientType=0 ); /* IE6-9 */
}
.hidden { display:none;}
#purchase_form {float:right;margin-top: -13px;}

</style>

<section id="sec-summary" style="width:100%;float:none;margin:0;">
	<h2 style="float:none;display:block;width:100%;">Confirm Your Order</h2>

	<table style="text-align:left;width:100%;font-size:1.1em;line-height:1.4em;">
	
		<thead>
			<tr>
				<th></th>
				<th>Service</th>
				<th>Price</th>
				<th>Setup Fee</th>
			</tr>
		</thead>

		<?php 
		$current_hosting = '';
		// iterate through all items on the invoice
		
		foreach ($items['current_packs'] AS $key => $value): 

			if ( ! in_array($value['plan_id'], array('11','101'))): 
				continue;
			endif; 

			?>
			
			<tr>
				<td></td>
				<td><?php echo preg_replace("/\([^)]+\)/",'',$value['desserv']).' <span id="slug'.$value['plan_id'].'">('.$period[$value['period']].')</span>'; ?> 
				<?php if($value['plan_id'] == 101) :
					echo " <a href='javascript:void(0);' id='edit{$value['plan_id']}' class='editplan'>Update</a>";
					$current_hosting = '';
					$hostingkey= $key;
				endif;
				?>
				</td>
				<td id="price<?php echo $value['plan_id'];?>">$<?php echo number_format($value['cost'],2); ?></td>
				<td  id="setup<?php echo $value['plan_id'];?>">$<?php echo number_format($value['setup'],2); ?></td>
				
				<?php if (FALSE): ?>
					<td>
						<?php if ($value['plan_id'] == '11'): 
							
							?>

							<select>
								<option>One Year</option>
								<option>Two Years</option>
							</select>

						<?php else: ?>
							
							<select>
								<option>Monthly (+setup?)</option>
								<option>Biannually</option>
								<option>Annually</option>
								<option>Biennially</option>
							</select>

						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
			
			<?php 
			// increase total
			$total	+= $value['cost'];
			$total  += $value['setup'];

		endforeach;
		$hiddenfields = '';
?>
		<tr class='hidden' id="update101">
				<td></td>
				<td>
					<select id="new_plan">
						<option value=''> Select Plan</option>
						<?php foreach($hosting_plans as $slug=>$val) :
							$c = $current_hosting == $slug ? ' selected="selected"':'';
							
							echo "<option value='$slug'$c>$". $hosting_plans[$slug]['price']. " " . ucfirst($slug) ."</option>";
							$hiddenfields .= "<input type='hidden' id='".$slug."price' value='{$hosting_plans[$slug]['price']}'>";
							$hiddenfields .= "<input type='hidden' id='".$slug."setup_fee' value='{$hosting_plans[$slug]['setup_fee']}'>";
							$hiddenfields .= "<input type='hidden' id='".$slug."num_months' value='{$hosting_plans[$slug]['num_months']}'>";
							$hiddenfields .= "<input type='hidden' id='".$slug."trial_discount' value='{$hosting_plans[$slug]['trial_discount']}'>";
							$hiddenfields .= "<input type='hidden' id='".$slug."plan_id' value='{$hosting_plans[$slug]['plan_id']}'>";
							$hiddenfields .= "<input type='hidden' id='".$slug."pack' value='{$hostingkey}'>";
						endforeach;
						?>
					</select>
				</td>
				<td colspan="2">
					<input type="hidden" id="current_slug" value="<?php echo $current_hosting;?>">
					<input type="button" id="updatehosting" value='Update'><?php echo $hiddenfields;?></td>
			</tr>
	<?php
		// iterate through all items on the invoice
		foreach ($items['current_packs'] AS $key => $value): 

			if (in_array($value['plan_id'], array('11','101','-1'))): 

				continue;

			endif; ?>
		
			<tr>
				<td style="text-align:center;"><input type="checkbox" name="remove_upsell[]" value="<?php echo $key;?>"/></td>
				<td><?php echo preg_replace("/\([^)]+\)/",'',$value['desserv']).' ('.$period[$value['period']].')'; ?> </td>
				<td>$<?php echo number_format($value['cost'],2); ?></td>
				<td>$<?php echo number_format($value['setup'],2); ?></td>

				<?php if (FALSE): ?>
					<td></td>
				<?php endif; ?>
			</tr>
		
			<?php 
			// increase total
			$total	+= $value['cost'];
			$total  += $value['setup'];
		
		endforeach;


		if (FALSE):
			if (isset($items['credits'])):
				// iterate through all credits on the invoice
				foreach ($items['credits'] AS $key => $value): ?>
				
					<tr>
						<td class="bold-green"><?php echo $value['reason']; ?> </td>
						<td class="bold-green">($<?php echo number_format($value['amount'],2); ?>)</td>
					</tr>
				
					<?php
					// subtract amount from total
					$total	-= number_format($value['amount'],2);
				
				endforeach;
			endif;
		endif;

		?>
		

		<tr>
			<td></td>
			<td></td>

			<?php if (FALSE): ?>
				<td></td>
			<?php endif; ?>

			<td></td>
			<td></td>

		</tr>

		<tr style="font-size:1.3em;">
			<td></td>
			<td></td>

			<?php if (FALSE): ?>
				<td></td>
			<?php endif; ?>

			<td>Total:</td>
			<td id='totalamount'>$<?php echo number_format($total,2); ?></td>

		</tr>
	</table>
	<input type="hidden" id="orgintotal" value="<?php echo $total;?>">
</section>

<button type="submit" style="background:none;border:none;color:#f00;font-size:0.8em;" id="remove_chk">Remove Checked Items</button>

<?php
echo form_close();


$form_submission = '';
$attributes      = array(
	'method' => 'POST',
	'id'     => 'purchase_form'
);

echo form_open(
	$form_submission,
	$attributes,
	array(
		'action_id'      => 83
	)	
); ?>

	<button type="submit" id="purchase">Process My Order</button>

<?php 

echo form_close();

?>

<div style="clear:both"></div>


