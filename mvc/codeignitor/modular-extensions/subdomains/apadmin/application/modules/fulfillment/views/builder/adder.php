<?php $admin_url = ($_SERVER['SERVER_ADDR'] != '127.0.0.1') ? '/admin' : ''; ?>

<h1 style="margin-bottom:30px">Add Site to Builder Queue</h1>

<?php if ($status != 'unsubmitted' || isset($_GET['status'])): ?>

	<div style="text-align:center;padding-bottom:30px">

		<?php if ($status == 'success' || (isset($_GET['status']) && $_GET['status'] == 'success')): ?>

			<img src="<?php echo $admin_url; ?>/resources/modules/fulfillment/assets/success.jpg" alt="" height="207px" />
			<h3>Success!</h3>

		<?php elseif ($status == 'failed' || (isset($_GET['status']) && $_GET['status'] == 'failed')): ?>

			<img src="<?php echo $admin_url; ?>/resources/modules/fulfillment/assets/failed.gif" alt="" />
			<h3>I don't think so.</h3>

			<ul>
				<?php foreach ($errors as $error): ?>
					<li><?php echo $error; ?></li>
				<?php endforeach;?>
			</ul>

		<?php endif; ?>

	</div>

<?php endif; ?>

<form method="post" action="" style="width:750px;display:block" id="dom_form">

	<div style="width:300px;float:left;">

		<label style="position:relative">
			Client ID: 
			<input type="text" name="client_id" value="" id="client_id"/>

			<div style="width:20px;height:16px;background:#05719B;color:#fff;border-radius:5px;font-size:8px;text-align:center;cursor:pointer;line-height:16px;position:absolute;right:60px;top:20px" id="go_button">
				Go
			</div>

		</label>
		
		<label id="doms" style="display:none">
			Domain: 
			<select id="domains" name="client_domain"></select>
		</label>

	</div>

	<div style="width:400px;float:left;height:200px;overflow:auto;border-left: 4px double #DDDDDD;padding-left:20px;display:none;" id="dom_cats">
		<table>
			<thead>
				<tr>
					<th>
					</th>

					<th style="text-align:left;">
						Build
					</th>

					<th style="text-align:left;">
						Version
					</th>
				</tr>
			</thead>
			<tbody>

			<?php 

			$checked = 'checked="checked"';

			foreach ($builds as $id => $build): ?>

				<tr>
					<td>
						<input type="radio" name="which_build" value="<?php echo $id; ?>" id="build_v_<?php echo $id; ?>_radio" <?php echo $checked; ?>/> 
					</td>
					
					<td style="padding:0 20px 0 10px" class="build_name" id="build_v_<?php echo $id; ?>">
						<?php echo $build['name'];?>
					</td>

					<td>
						<select name="build_<?php echo $id; ?>" style="width:auto">

							<?php foreach ($build['versions'] AS $version_id => $version): ?>

								<option value="<?php echo $version_id;?>">
									<?php echo $version['version']; ?> (<?php echo $version['num_sites'];?> sites)
								</option>

							<?php endforeach; ?>

						</select>
					</td>
				</tr>

				<?php 

				$checked = '';

			endforeach; ?>

			</tbody>
		</table>
	</div>

	<div style="clear:both;"></div>

	<div style="text-align:right;padding-top:25px;padding-right:45px">

		<button id="add_to_queue" type="submit" style="border:none;background:#05719B;color:#fff;border-radius:5px;padding:5px 10px;display:none">
			Add to Q<?php echo str_shuffle('ueue'); ?>
		</button>

	</div>

	<input type="hidden" name="dom_sub" value="1" />

</form>

<script type="text/javascript">

$(document).ready(function(){

	var client_val = $('#client_id').val();

	$('.build_name').click(function(){

		var radio_id = $(this).attr('id') + '_radio';
		$('#'+radio_id).click();

	});

	$('#go_button').click(function(){

		var client_id = $('#client_id').val();

		if (parseInt(client_id) < 1000){
			return;
		}

		$.getJSON('<?php echo $admin_url; ?>/fulfillment/builder/ajax_domains/'+client_id,function(data){

			if ( ! data.success){

				alert('Unable to find domains for client '+client_id);
				return;
			}

			client_val = $('#client_id').val();

			$('#domains').html('');
			$('.domain_inputs').remove();

			$.each(data.data.domains, function(key, value) {   

			    $('#domains')
			    	.append($('<option>', { value : key })
			    	.text(value)); 

			    $('<input>').attr({
				    type: 'hidden',
				    class: 'domain_inputs',
				    name: 'domain_id_'+key,
				    value: value
				}).appendTo('#dom_form');

			});

			$('#doms, #dom_cats, #add_to_queue').slideDown();

		});

	});

	$('#client_id').keypress(function(e){
	    if ( e.which == 13 ){
	    	$('#go_button').click();
	    	e.preventDefault();
	    }
	});

	$('#client_id').change(function(){

		if ($('#client_id').val() == client_val){
			return;
		}

		$('#doms, #dom_cats, #add_to_queue').hide();
	});

});

</script>

<?php //var_dump($builds);