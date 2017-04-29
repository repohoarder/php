
<!-- Sales Statistics view ************************************************************************************* -->
<section id="calculator">

	<h2>Revenue Calculator</h2>
        <form name="revenuecalculator" id="revenuecalculator">
        <p>The revenue calculator shows theoretical data.</p>
        <?php /* keep this hidden field for javascript calculations */?>
            
        <input type="hidden" id="processing_fee" value="<?php echo $processing_fee;?>">
	<div class="module s-calculator">
		<table id="pnl-calculator-basic-stats">
			<thead>
				<tr>
					<th>Service</th>
                                         <th>PP</th>
					<th>#Sales</th>
					<th>Gross</th>
					<th>Expenses</th>
                                        <th>Net</th>
				</tr>
			</thead>
			<tbody>
                        <?php 
                        
                        foreach ($calculatorreport as $report) : 
                            $count      = rand(0,940);
                            $gross      = round($count * $report['price'],2);
                            $expense    = round($count * $report['cost'],2);
                            $pfee       = round(($processing_fee * .01 ) * $gross, 2 );
                            $expense    = round( $expense + $pfee ,2 );
                            $net        = $gross - $expense;
                            //need to make keyup function for the input boxes.
                            ?>
                            <tr>
                                <td><?php echo $report['service_name'];?> </td>
                                <td><input type="hidden" name="expensehidden<?php echo $report['service_id'];?>" id="expensehidden<?php echo $report['service_id'];?>" value="<?php echo empty($report['cost']) ? 0.00 : $report['cost'];?>" />
                                <input type="text" id="pp<?php echo $report['service_id'];?>" name="pp<?php echo $report['service_id'];?>" value="<?php echo $report['price']; ?>" size="7" class="revcalculate"></td>
                                <td><input type="text" id="cnt<?php echo $report['service_id'];?>" name="cnt<?php echo $report['service_id'];?>" value="<?php echo $count; ?>" size="4" class="revcalculate"></td>
                                <td id="gross<?php echo $report['service_id'];?>">$<?php echo number_format($gross,2);?></td>
                                <td id="expenses<?php echo $report['service_id'];?>">$<?php echo number_format($expense,2);?> </td>
                                <td id="net<?php echo $report['service_id'];?>">$<?php echo number_format($net,2);?></td>
			   </tr>
                     <?php endforeach; ?>
			</tbody>
		</table>
	</div>
        </form>
</section>

