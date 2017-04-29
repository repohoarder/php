<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upsell Queue</title>

	<style type="text/css">
		body {font-family:arial, helvetica, sans-serif;font-size:14px;}
		table {text-align:left;font-size:0.9em;border-collapse:collapse;width:99%;}
		table td, table th {padding:5px;}
		table .split {border-right:2px solid #000;}
		
		table tr {background:#fff;}
		table tr:hover {background:#e9fffd;}

		table tr.broken {background:#ffdede;}
		table tr.broken:hover {background:#fcc;}

		table tr.completed {background:#e4ffde;}
		table tr.completed:hover {background:#cfc;}

		table tr.inactive {background:#eee;}
		table tr.inactive:hover {background:#dadada;}

		table tr.top {background:#000;color:#fff;}

		.paging {width:99%;margin:20px 0;}

		.paging .previous {float:left;}
		.paging .next {float:right;}

		.breakfree {clear:both;}

	</style>
</head>
<body>

	<div class="paging">
		<span class="previous">
			<a href="<?php echo site_url('upsell_queue'); ?>">All Packs</a> / 
			<a href="<?php echo site_url('upsell_queue/page/fulfill'); ?>">New Packs</a> / 
			<a href="<?php echo site_url('upsell_queue/page/renew'); ?>">Renewed Packs</a> / 
			<a href="<?php echo site_url('upsell_queue/page/revoke'); ?>">Refunded Packs</a>
		</span>
		<span class="next">
			<strong><a href="<?php echo site_url('upsell_queue/process'); ?>">Process Queue</a></strong>
		</span>

		<div class="breakfree"></div>
	</div>

	<?php if (isset($errors) && is_array($errors) && count($errors)): ?>

		<ul>

		<?php foreach($errors as $error): ?>
		
			<li><?php echo $error; ?></li>

		<?php endforeach; ?>

		</ul>

	<?php endif; 


	if (isset($rows) && is_array($rows) && count($rows)): ?>

		<table>

			<thead>
				<tr class="top">
					<th colspan="5" class="split">Pack Info</th>
					<th colspan="8">Queue Info</th>
				</tr>
				<tr>
					<th>Plan</th>
					<th>Pack ID</th>
					<th class="split">Date</th>
					
					<th>Type</th>
					<th>Date</th>
					<th>Current Step</th>
					<th>Retry</th>
					<th>Attempted</th>
					<th>Errors</th>
					<th>Close</th>
				</tr>
			</thead>


			<?php foreach ($rows as $row): 

				foreach ($row['steps'] as $queue_type => $steps): 

					foreach ($steps as $uber_id => $step): 

						$class = '';
						$retry = '';

						if ($step['completed']):

							$class .= ' completed ';

						endif;

						if ($row['closed']):

							$class .= ' inactive ';

						endif;

						if ($step['attempted'] && ! $step['completed']):

							$retry_url = site_url('upsell_queue/step/'.$row['id'].'/'.$step['queue_id'].'/'.$step['current_step']);
							$retry     = '<span style="font-size:0.7em">[<a target="_blank" href="'.$retry_url.'">Retry</a>]</span>';

							$class .= ' broken ';

						endif;

						$errors = json_decode($step['errors'], TRUE);

						if ( ! is_null($errors) && is_array($errors)):

							$errors = implode('<br/>', $errors);

						endif;

						?>

						<tr class="<?php echo $class; ?>">

							<td><?php echo $row['plan'];?></td>
							<td><a target="_blank" href="http://my.brainhost.com/admin/clientmgr/client_service_details.php?packid=<?php echo $row['packid'];?>"><?php echo $row['packid'];?></a></td>
							<td class="split"><?php echo date('M d, Y h:i A',strtotime($row['date_added']));?></td>
							
							<td><?php echo $step['type'];?></td>
							<td><?php echo date('M d, Y h:i A',strtotime($step['queue_date']));?></td>
							<td><?php echo $step['current_step'];?></td>
							<td><?php echo $retry;?></td>
							<td><?php echo $step['attempted'];?></td>
							<td style="max-width:200px"><?php echo $errors;?></td>

							<td><span style="font-size:0.7em">[<a class="closer" target="_blank" href="<?php echo site_url('upsell_queue/close/'.$row['id'].'/'.$step['queue_id']);?>">Close</a>]</span></td>
						</tr>


					<?php endforeach;

				endforeach; 

			endforeach; ?>

		</table>

	<?php endif; ?>


	<div class="paging">

		<?php if ($page > 1): ?>

			<a class="previous" href="<?php echo site_url('upsell_queue/page/'.$type.'/'.($page - 1)); ?>">Previous</a>

		<?php endif; ?>

		<a class="next" href="<?php echo site_url('upsell_queue/page/'.$type.'/'.($page+1)); ?>">Next</a>

		<div class="breakfree"></div>

	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

	<script type="text/javascript">

	$('document').ready(function(){

		$('a.closer').click(function(){

			var 
				sayings = Array('For real?', 'You sure about that?', 'Do you know what you\'re doing?', 'Oh god you did it now', 'Should I... should I delete this?', 'This will be GONE FOREVER if you continue', 'What will be done cannot be undone. Do it?', 'Hmm. Yes. I see you\'re trying to close this item. Is that correct?', 'Well, golly, I wouldn\'t have put this here if I knew you were going to hate it', 'Think of this thing\'s family!', 'Very good, but try to click it a little better next time.'),
				saying  = sayings[Math.floor(Math.random()*sayings.length)];

			return confirm(saying);

		});

	});

	</script>

</body>
</html>