<h1>Manage | Pricing</h1>
<section id="pnl-accordion">

	<h2>Manage Pricing</h2>
	<div class="module s-manage-account">
		<div class="pad">
			<p>Control your revenue by determining price points for each product you offer your customers. "Wholesale" is what it costs to fulfill each service (track this in your Operating Costs), so by setting your retail price you choose exactly what margins you want to make.</p>
		</div>
<?php

// services array is formatted as such:
// (slug -> funnel_id -> affiliate_id -> offer_id -> locale_id -> variant -> term)


if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';


$config['log_threshold'] = 0;
?>

<!-- Custom CSS -->
<script type="text/javascript">
 $(document).ready(function(){
     $("#managepricing").validate();
 });
</script>
<?php
// open form

echo form_open('manage/pricing',array('method' => 'POST',"id"=>"managepricing"),array('funnel_id' => $funnel_id));
?>

<table width="100%" id="pricing">
	<thead>
		<tr>
			<th>Service Name</th>
			<th>Wholesale</th>
			<th>Retail</th>
			<th>Trial Discount</th>
			<th>Service Term</th>
		</tr>
	</thead>
	<tbody>
	    <?php
	            
	         foreach($services as $id=>$service):
	             
	            switch($service['num_months'])
	         	{
					case '0' :
						$term 	= "One-Time Fee";
						break;
					case '1' :
						$term 	= 'Monthly';
					  	break;
					case '6' :
						$term 	= 'Biannually';
					  	break;
					case '12' :
						$term 	= 'Yearly';
			            break;
			        case '24' :
			            $term 	= 'Biennial';
						break;
					default : 
						$term 	='&nbsp;';
	            }
	         ?>  
	         <tr>
			<td><?php echo $service['name'];?></td>
			<td><?php echo $service['cost_type'] == "%" ? "" :'$';?><?php echo $service['cost'];?><?php echo $service['cost_type'] == " %" ? "%" :'';?></td>
			<td>$ <?php 
	                if(in_array($service['type'],$disallowed)):
	                    echo "N/A";
	                    else:
	                       echo form_input(array(
							'name'	=> 'services['.$service['prices_id'].']',
							'type'	=> 'text',
	                        'class'	=> 'number',
							'value'	=> $service['price']
					));
	                endif;
	                ?></td>
			<td>$ <?php 
	                if(in_array($service['type'],$disallowed)):
	                    echo "N/A";
	                    else:
							$conf = array(
							'name'	=> 'trial['.$service['prices_id'].']',
							'type'	=> 'text',
	                        'class'	=> 'number',
							'value'	=> $service['trial_discount']
							);
							
	                       echo form_input($conf);
	                endif;
	                ?></td>
			<td>&nbsp;<?php echo $term ; ?></td>
		</tr>
	        <?php
	        endforeach;
	        ?>
		<tr>
			<td colspan='5' align='center'>
				<br />
				<p>What does this add up to?  Click here to use our <a href="/manage/calculator/<?php echo $funnel_id;?>/?frame=1" class="lightbox" data-fancybox-type="iframe">Revenue Calculator</a></p>
				<br />
				<?php 
				echo form_input(array(
					'name'	=> 'submit',
					'type'	=> 'submit',
					'class' => 'btn-contact',
					'value'	=> 'Update Account'
				));
				?>
			</td>
		</tr>
	</tbody>
</table>

<?php
echo form_close();
?>
</div>
</section>