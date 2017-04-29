
<h1>Manage | Landing Pages</h1>


<?php 
// show error if one is passed
if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; 
?>


<?php
// open form
echo form_open_multipart('manage/landers',array('method' => 'POST'),array('landing_page_id' => $landing_page_id));
?>


<section id="pnl-accordion">

	<h2>Manage Landing Page Custom Variables</h2>
	<div class="module s-manage-account">
		<div class="pad">
			<?php echo form_fieldset('Landing Page Custom Variables'); ?>
			<div class="row">
				<label for="video">Video URL (.flv, .mp4)</label>
				<?php
				echo form_input(array(
					'name'		=> 'video',
					'id'		=> 'video',
					'type'		=> 'text',
					'value'		=> (isset($landers['video']) AND $landers['video'] != '')? $landers['video']: $video
				));
				?>
			</div>
			<div class="row">
				<label for="text">Header Text</label>
				<?php
				echo form_input(array(
					'name'		=> 'text',
					'id'		=> 'text',
					'type'		=> 'text',
					'value'		=> (isset($landers['text']) AND $landers['text'] != '')? $landers['text']: $text
				));
				?>
			</div>
			
			<div style="text-align:center;margin-top:115px;margin-bottom:10px;"><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></div>
				
			
			<?php echo form_fieldset_close();?>
			
			
			<div class="row s-customer-reporting">
				<?php
				// submit button
				echo form_input(array(
					'name'	=> 'submit',
					'type'	=> 'submit',
					'value'	=> 'Save Custom Variables'
				));
				?>
			</div>
		</div>
	</div>
</section>


<?php
// close form
echo form_close();
?>
