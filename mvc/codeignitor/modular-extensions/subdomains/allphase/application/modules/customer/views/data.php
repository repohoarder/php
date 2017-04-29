<!-- Section specific Javascript -->

	<script>
		$(function() {
			$( "#start" ).datepicker();
			$( "#end" ).datepicker();
                 $("#export").click(function(){
                     $("#exportid").val('1');
                     $("#submit").click();
                    
                 });
				  $("#exportit").click(function(){
                     $("#exportitfee").val('1');
                     $("#submit").click();
                    
                 });
			
		});
	</script>
<!-- *************************** -->

<h1>Customer Reporting</h1>
<section id="pnl-accordion">
	<h2>Customer Reporting</h2>
	<div class="module s-customer-reporting">
		<div class="pad">
			<p style="padding:0 0 25px 0;margin:0 0 25px 0;border-bottom:1px solid #bfbfbf;">Review your customer data by selecting a date range and accessing our customer reports below.</p>
			<?php

			if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';

			?>

			<?php

			echo form_open('customer/data',array('method' => 'POST',"id"=> "customerreporting","name" =>"customerreporting"));
			echo form_fieldset('Date of Signup');
                        echo "<input type='hidden' name='export' id='exportid' value='0'><input type='hidden' name='exportitfee' id='exportitfee' value='0'>";
			echo '<label for="start">From Date</label>'.form_input(
				array(
					'name'			=> 'start',
					'id'			=> 'start',
					'type'			=> 'text',
					'value'			=> $start,
					'placeholder'	=> 'Subject'
				)
			);

			echo '<label for="end">To Date</label>'.form_input(
				array(
					'name'			=> 'end',
					'id'			=> 'end',
					'type'			=> 'text',
					'value'			=> $end,
					'placeholder'	=> 'Subject'
				)
			);
                        
                    echo form_input(
				array(
					'name'			=> 'submit',
					'id'			=> 'submit',
					'type'			=> 'submit',
					'value'			=> 'Search'
				)
			);
           if(count($customers) > 0) : 
			   echo"<br><br>";
			echo form_input(
				array(
					'name'			=> 'export',
					'id'			=> 'export',
					'type'			=> 'button',
					'value'			=> 'Export CSV'
				)
			);
		   echo form_input(
				array(
					'name'			=> 'exportfee',
					'id'			=> 'exportit',
					'type'			=> 'button',
					'value'			=> 'Export Fee Breakdown'
				)
			);
				   endif;
		   echo '</fieldset>';

			echo form_close();

			?>
			
			<table  align='center'  id="tablesorter">
				<thead>
				<tr>
					<th>Name</th>
					<!--<th>Email</th>
					<th>Address</th> -->
					<th>Packages</th>
					<th>Invoice Amount</th>
					<th>Date Created</th> 
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($customers AS $key => $value):
					/**/
				?>
					<tr href="/customer/invoice/<?php echo $value['invid'];?>/?frame=1" class="lightbox" data-fancybox-type="iframe"  style="cursor:pointer">
						<td><?php echo $value['first'].' '.$value['last']; ?></td>
						<!-- <td><?php echo $value['email']; ?></td>
						<td><?php echo $value['address'].'<br>'.$value['city'].','.$value['state'].' '.$value['zip']; ?></td> -->
						<td><?php echo $value['packages']; ?></td>
						<td><?php echo $value['amount']; ?></td>
						<td><?php echo date("m/d/Y",strtotime($value['invoice_date'])); ?></td> 
					</tr>
				<?php
				endforeach;
				?>
				</tbody>
			</table>
			
		</div>
	</div>
</section>