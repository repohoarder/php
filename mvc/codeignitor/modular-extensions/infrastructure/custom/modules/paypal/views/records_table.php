<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<title>PayPal Records</title>

	<link rel="stylesheet" href="https://orders.brainhost.com/assets/v1/new/css/reset.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

	<style type="text/css">
		body
		{
			line-height: 1.6em;

			font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
			font-size: 12px;
		}

		form {
			margin:45px;
		}

		#hor-minimalist-a
		{
			
			background: #fff;
			margin: 45px;
			width: 480px;
			border-collapse: collapse;
			text-align: left;
		}
		#hor-minimalist-a th
		{
			font-size: 14px;
			font-weight: normal;
			color: #039;
			padding: 10px 8px;
			border-bottom: 2px solid #6678b1;
		}
		#hor-minimalist-a td
		{
			color: #669;
			padding: 9px 8px 0px 8px;
		}
		#hor-minimalist-a tbody tr:hover td
		{
			color: #009;
		}

		#hor-minimalist-a td {

			width:130px;
			position:relative;
		}

		#hor-minimalist-a span.hovered_td {
			display:block;
			padding:5px;
			background:#eee;
			position:absolute;
			z-index:2;
		}

		.hidden_search_value {display:none;}

		form div {margin-bottom:8px;}

	</style>

	<script type="text/javascript">

		$(document).ready(function(){

			var timer;
			var elem;

			$('.td_hidden').each(function(){

				$(this).hover(function(){

					if(timer) {
	                	clearTimeout(timer);
	                	timer = null
	                }

	                elem = $(this);

	                timer = setTimeout(function(){

	                	var hid = elem.find('.td_hidden_val').val();
	                	elem.find('.td_text').text(hid).addClass('hovered_td');

	                },500);


				},function(){

					if(timer) {
	                	clearTimeout(timer);
	                	timer = null
	                }

	                elem = $(this);

	                timer = setTimeout(function(){

	                	var vis = elem.find('.td_visible_val').val();
						elem.find('.td_text').text(vis).removeClass('hovered_td');

	                },100);

				});

			});


			if ($('select[name=operation]').val()=='between') {

				$('.hidden_search_value').show();

			}else{

				$('.hidden_search_value').hide();

			}

			$('select[name=operation]').change(function(){


				if ($(this).val()=='between'){

					$('.hidden_search_value').show();

				}else {

					$('.hidden_search_value').hide();
				}


			});


		});

	</script>

</head>

<body>

	<?php if ( ! $response['success']): ?>

		Unable to retrieve rows using the given parameters

	<?php else: ?>

		<table id ="hor-minimalist-a">

			<thead>
				<tr>
					<?php foreach ($all_columns as $col_header): ?>

						<th><?php echo $col_header; ?></th>

					<?php endforeach; ?>
				</tr>
			</thead>

			<?php 

			foreach ($response['data']['records'] as $record): ?>

				<tr>
					<?php 

					$count = 0;

					foreach ($record as $col): 

						$td_class  = '';
						$is_hidden = FALSE;

						if (strlen($col) > 20): 

							$is_hidden = TRUE;
							$td_class = 'td_hidden'; 

						endif; ?>

						<td class="<?php echo $td_class; ?>">

							<?php
							if ($is_hidden) :

								$hidden = $col; 
								$col = substr($col,0,20).'...';

								?>

									<input type="hidden" class="td_hidden_val" value='<?php echo $hidden; ?>'/>
									<input type="hidden" class="td_visible_val" value='<?php echo $col; ?>'/>

								<?php								

							endif; 


							if ($all_columns[$count]=='order_id'):

								$col = '<a href="http://my.brainhost.com/admin/ordermgr/order_view.php?order_id='.$col.'" target="_blank">'.$col.'</a>';

							endif;

							if ($all_columns[$count]=='invoice_id'):

								$col = '<a href="http://my.brainhost.com/admin/clientmgr/popup_viewinv.php?invid='.$col.'" target="_blank">'.$col.'</a>';

							endif;

							?>

							<span class="td_text"><?php echo $col; ?></span>

						</td>

						<?php 

						$count++;

					endforeach; ?>

				</tr>

			<?php endforeach; ?>

		</table>

	<?php endif; ?>

	<form method="post" action="">

		<div>
			Display: 
			<input type="text" name="num_rows" value="<?php echo $params['num_rows']; ?>"/> Rows
		</div>
		<div>
			Starting at Row #
			<input type="text" name="offset" value="<?php echo $params['offset']; ?>"/>
		</div>

		<div>

			Where

			<select name="search">

				<?php foreach ($searchable_columns as $column): 

					$selected = ($column==$params['search'] ? 'selected="selected"' : '');?>

					<option value="<?php echo $column; ?>" <?php echo $selected; ?>><?php echo $column; ?></option>

				<?php endforeach;?>

			</select>



			<select name="operation">

				<?php foreach ($operators as $operator => $info): 

					$selected = ($operator==$params['operation'] ? 'selected="selected"' : '');?>

					<option value="<?php echo $operator; ?>" <?php echo $selected; ?>><?php echo $info['text']; ?></option>

				<?php endforeach;?>

			</select>


			Value: <input type="text" name="search_value" size="30" value="<?php echo $params['search_value']; ?>"/>
			<span class="hidden_search_value">
				AND
				<input type="text" name="search_value2" size="30" value="<?php echo$params['search_value2']; ?>"/>
			</span>

			

		</div>


		<div>
			Sort By:
			<select name="column">

				<?php foreach ($sortable_columns as $column): 

					$selected = ($column==$params['column'] ? 'selected="selected"' : ''); ?>

					<option value='<?php echo $column; ?>' <?php echo $selected; ?>><?php echo $column; ?></option>
				
				<?php endforeach; ?>

			</select>

			<select name="sort">
				<?php

				$options = array('DESC', 'ASC');
				
				foreach ($options as $option): 

					$selected = ($option==$params['sort'] ? 'selected="selected"' : ''); ?>
				
						<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>

				<?php endforeach; ?>

			</select>



		</div>

		<div>

			<input type="radio" name="output" value="display" checked="checked"/>Show Rows
			<input type="radio" name="output" value="csv"/>Export to CSV

			<input type="submit"/>

		</div>

	</form>

</body>

</html>