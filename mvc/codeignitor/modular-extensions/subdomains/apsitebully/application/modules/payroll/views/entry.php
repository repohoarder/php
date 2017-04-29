<script type="text/javascript">
	
	$(document).ready(function () {
		
		// VALIDATION
			$("#expense_form").validate({
				rules: {
					expenseGrossPayroll: {
						number: true	
					},
					expenseCompanyBenefit: {
						number: true	
					},
					expenseCompanyTax: {
						number: true
					}
				}
			});
			
		// DATE PICKERS
			$("#dateStart").datepicker({
                beforeShowDay: disableSpecificWeekDays
            });
			$("#dateEnd").datepicker({
                beforeShowDay: disableSpecificWeekDays2
            });
			
			var daysToDisable = [0, 1, 3, 4, 5, 6];

            function disableSpecificWeekDays(date) {
                var day = date.getDay();
                for (i = 0; i < daysToDisable.length; i++) {
                    if ($.inArray(day, daysToDisable) != -1) {
                        return [false];
                    }
                }
                return [true];
            }
			
			var daysToDisable2 = [0, 2, 3, 4, 5, 6];

            function disableSpecificWeekDays2(date) {
                var day = date.getDay();
                for (i = 0; i < daysToDisable2.length; i++) {
                    if ($.inArray(day, daysToDisable2) != -1) {
                        return [false];
                    }
                }
                return [true];
            }
	});
		
</script>

<?php 

	// initialize form variables
			
		$form_submission	= 'payroll/entry/defaultEntry';
		
		$attributes			= array(
			'id'	=> 'expense_form',
			'name'	=> 'expense_form'
		);
			
		$hidden_fields		= array(
			//'order_id'	=> $order_id
		);
	
	// initialize dropdown variables
	
		$options = array(
			'1' => 'Marketing',
			'2' => 'Development',
			'5' => 'Accounting',
			'6' => 'Customer Service',
			'7' => 'Social Media',
			'8' => 'Affiliate',
		
		);
	
?>

<div style="margin-left:10px;">

<h1>Payroll</h1>

<div style="margin-left:24px;">

	<?php echo form_open($form_submission,$attributes,$hidden_fields); ?>
    
    	<div style="width:550px; margin-top:10px;">
        
        	<div class="form_l">Date Start:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'dateStart',
                    'name'	=> 'dateStart',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
        
        	<div class="form_l">Date End:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'dateEnd',
                    'name'	=> 'dateEnd',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
        
        	<div class="form_l">Department:</div>
            <div class="form_r">
            	<?php
					echo form_dropdown('department', $options,NULL,'id=department');
				?>
            </div>
            <div class="form_space"></div>
        
            <div class="form_l">Gross Payroll Expense:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'expenseGrossPayroll',
                    'name'	=> 'expenseGrossPayroll',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
            
            <div class="form_l">Company Benefit Expense:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'expenseCompanyBenefit',
                    'name'	=> 'expenseCompanyBenefit',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
            
            <div class="form_l">Company Tax Expense:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'expenseCompanyTax',
                    'name'	=> 'expenseCompanyTax',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
            
            <div class="form_l"></div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'submit',
                    'value'	=> 'Submit'));
				?>
            </div>
            <div class="form_space"></div>
        
        </div>
    
    <?php echo form_close(); ?>

</div>

</div>
