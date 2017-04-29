<h1>Manage | Tracking Pixels</h1>

<section id="pnl-accordion">

	<h2>Manage Tracking Pixels</h2>
	<div class="module s-manage-pixels">
		<div class="pad">
			<form method="post" action="">
				<h3>Create new tracking pixel</h3>

				<?php
				if ($error) echo '<center><p style="font-weight:bold;color:red;">'.$error.'</p></center>';
				?>

				<div class="row">
					<label for="txtLabel">Give it a name/label:</label>
					<input type="text" name="name" id="txtLabel" />
				</div>
				<div class="row">
					<label for="txtPixel">Enter the tracking code:</label>
					<textarea name="pixel" id="txtPixel" cols="5" rows="5"></textarea>
				</div>
				<div class="row">
					<label for="selLocation">Place this pixel on:</label>
					<select name="type" id="selLocation">

						<?php foreach ($types as $slug => $name): ?>

							<option value="<?php echo $slug; ?>"><?php echo $name; ?></option>

						<?php endforeach; ?>

					</select>
				</div>
				<div class="row">
					<input type="submit" class="btn-green" value="Add" />
				</div>
				<input type="hidden" name="add_pixel" value="1" />
			</form>

			<div style="background:#ffdcdc;color:#7d1717;clear:both;padding:5px;margin:10px 0;">
				Javascript files, images, and other assets must use <span style="font-weight:bold;">https://</span> and have a valid security certificate. Non-secure items may cause customers' browsers to display security warnings and negatively affect your conversions and/or business reputation.
			</div>

			<p style="margin-bottom:20px;font-size:0.9em">
				<strong>Note: </strong> For safety reasons, all tracking pixels must be approved by an All Phase staff member before they will appear on your site and/or sales funnel. This process may take up to 48 hours.
			</p>

			<?php if (isset($pixels) && count($pixels)): ?>

				<h3>Current pixels</h3>
				<table>
					<thead>
						<tr>
							<th class="one">Page</th>
							<th class="two">Pixel</th>
							<th class="three">Approved?</th>
							<th class="four">Delete</th>
						</tr>
					</thead>
					<tbody>

					<?php foreach ($pixels as $pixel): ?>

						<tr>
							<td><?php echo $types[$pixel['type']]; ?></td>
							<td>
								<strong><?php echo ($pixel['name'] ? $pixel['name'] : 'Custom Tracking Pixel'); ?></strong>

								<textarea readonly="readonly"><?php echo trim(htmlentities($pixel['pixel'], ENT_QUOTES)); ?></textarea>
							</td>
							<td>
								<?php if ($pixel['approved']): ?>

									<img src="/resources/allphase/img/icon-check.png" alt="Yes" />

								<?php else: ?>

									<span style="font-size:0.9em;font-weight:bold;display:block;padding:10px;background:#fcc" title="Please allow up to 48 hrs for an All Phase staff member to approve your pixel">
										Pending
									</span>

								<?php endif; ?>
							</td>
							<td>
								<form method="post" action="">

									<button type="submit" style="background:url(/resources/allphase/img/icon-cancel.png) left top no-repeat;display:block;width:37px;height:33px;cursor:pointer;border:none;margin:0 auto"/>

									<input type="hidden" name="pixel_id" value="<?php echo $pixel['id'];?>" />
									<input type="hidden" name="delete_pixel" value="1" />

								</form>
							</td>
						</tr>

					<?php endforeach; ?>

					</tbody>
				</table>

			<?php endif; ?>

		</div>
	</div>
</section>