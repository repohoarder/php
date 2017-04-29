<nav>
<ul>
	<li><a href="/campaign/">Add</a></li>
	<li><a href="/campaign/view">View</a></li>
</ul>
</nav>

<div id="errors">
	
</div>

<form method="post" id="camp">
	<fieldset>
		<h3>Campaign</h3>
		<ul>
			<li>
				<label>Campaign Name:</label>
				<input type="text" name="campaign_name" class="required" value="" />
			</li>
			<li>
				<label> Brand: </label>
				<select name="brand">
				<?php foreach($brands as $brand) :
				echo '<option value="'.$brand['id'].'">'.$brand['name'].'</option>';
					  endforeach;?>
				</select>
			</li>
		</ul>	
	</fieldset>
	<fieldset>
		<h3>Control  <span class="info">(All Percents must add up to 100%)</span></h3>
		<ul>
			<li>
				<label for="control"> Control: </label>
				<input type="text" name="variations[0][variation]" id="control"  class="url required" value="http://" /> <span class="desc">( http:// required )</span>
				<label for="variations">Percent: </label>
				<input size="5" type="text" name="variations[0][percent]" class="percent"/><span class="desc">%</span>
				<input type="hidden" name="variations[0][control]" value="1" />
			</li>
		</ul>	
	</fieldset>	
	<fieldset>
		<h3>Variations <a class="add_item" href="#">+ Add</a> <span class="info">(All Percents must add up to 100%)</span></h3>
		<ul>
			<li>	
				<label for="variations">Variation: </label>
				<input type="text" name="variations[1][variation]" class="url required" id="variation" value="http://" />  <span class="desc">( http:// required )</span>
				<label for="variations">Percent: </label>
				<input size="5" name="variations[1][percent]" class="percent" /><span class="desc">%</span>
			</li>
		</ul>	
	</fieldset>	
	<fieldset>	
		<h3>Goals <a class="add_item" href="#">+ Add</a></h3>
		<ul>
			<li>
				<label for="goal"> Goal: </label>
				<input type="text" name="goals[0][goal]" id="goal" class="url required" value="http://" />  <span class="desc">( http:// required )</span>
				
			</li>
		</ul>	
	</fieldset>
	
	<ul>
		<li>	
			<button type="button">Creat Campaign</button> 
		</li>
	</ul>	
	
</form>

<div style="clear:both;">&nbsp;</div>