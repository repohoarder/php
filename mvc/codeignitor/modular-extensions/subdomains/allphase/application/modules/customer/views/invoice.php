<style type="text/css">
#t-main     { 
    border-bottom: 0px solid #FFF; 
    box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.4);
}
#boldme     { 
    font-weight:bold; 
}
</style>


<h1>Invoice</h1>
<table width='100%' align='center'>
    
     <tr>
        <td id="boldme">Invoice # </td>
        <td><?php echo $customer['invid'];?></td>
    </tr>
    <tr>
        <td id="boldme">Invoice Date: </td>
        <td> <?php echo date("y/m/d",strtotime($customer['invoice_date']));?></td>
    </tr>
    <tr>
        <td id="boldme">Amount </td>
        <td>$<?php echo $customer['amount'];?></td>
    </tr>
</table>
        <h1>Customer Details</h1>
<table width='100%' align='center'>
    <tr>
        <td id="boldme">Name </td>
        <td><?php echo $customer['first'].' '.$customer['last']; ?></a> </td>
    </tr>
    <tr>
        <td id="boldme">Address </td>
       	<td><?php echo $customer['address'].'<br>'.$customer['city'].','.$customer['state'].' '.$customer['zip']; ?></td>

    </tr>
    <tr>
        <td id="boldme">Email </td>
        <td><?php echo $customer['email'];?></td>
    </tr>
    <tr>
        <td id="boldme">Phone </td>
        <td><?php echo $customer['phone'];?></td>
    </tr>
    <?php 
     if( ! empty($customer['company'])) : ?>
        <tr>
        <td id="boldme">Company</td>
        <td><?php echo $customer['company'];?></td>
    </tr> 
     <?php endif; ?>
    
</table>
         <h1>Package Details</h1>
<table width='100%' align='center'>
	<tr id="boldme">
						<th align="left">Package</th>
						<th align="left">Price</th>
						<th align="left">Term</th>
						<th align="left">Next Rebill</th>
	</tr>

	<?php
	foreach ($lineitems AS $key => $value):

		switch($value['period']){
		case '0' :
			$term = "One-Time Fee";
		break;
		case '1' :
			$term = 'Monthly';
		  break;
		case '6' :
			$term    = 'Biannually';
		  break;
		case '12' :
			$term    = 'Yearly';
            break;
        case '24' :
            $term   = 'Biennial';
		break;
		default : 
		 $term ='&nbsp;';
	 }
	?>
		<tr>
			<td><?php echo $value['desserv'];?></td>
			<td><?php echo $value['cost'];?></td>
			<td><?php echo $term;?> </td>
			<td><?php echo ($value['period'] == 0)? '': date("m/d/y",strtotime($value['renewal'])); ?></td>

		</tr>
	<?php
	endforeach;
	?>

</table>
