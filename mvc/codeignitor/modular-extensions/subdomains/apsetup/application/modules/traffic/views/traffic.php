<div>
	<!--<?php if (isset($errors)): ?>
		<?php foreach ($errors as $error): ?>
			<p class="error"><?php echo $error; ?></p>
		<?php endforeach; ?>
	<?php endif; ?>-->
	<form method="post">
		<label>
			Category
			<select name="category">
				<?php foreach($categories as $key=>$category): ?>
					<option value="<?php echo $key; ?>"><?php echo $category; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label>
			Traffic Hits
			<input type="text" disabled="disabled" value="<?php echo $package['meta']['traffic_hits']['value']; ?>" />
		</label>
		<label>
			Domain
			<select name="domain">
				<?php foreach($domains as $key=>$domain): ?>
					<option value="<?php echo $domain['servername']; ?>"><?php echo $domain['servername']; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label>
			Traffic User
			<input type="text" name="traffic_user" />
		</label>
		<label>
			Traffic Password
			<input type="password" name="traffic_pass" />
		</label>
		<!--<label>
			Traffic Password Confirm
			<input type="password" name="traffic_pass_confirm" />
		</label>-->
		<button type="submit">Submit</button>
	</form>
</div>