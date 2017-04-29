



<br><br><br><br><br>
<div style="margin-left: 50px;"><canvas width="1820" height="1080" id="canv"></canvas></div>


<script type='text/javascript'>

// initialize heatmap
heatmap = new HeatCanvas("canv");

<?php
// iterate through all rows and add to map
foreach ($rows AS $key => $value):
?>
	// push data into the map
	heatmap.push(<?php echo $value['CoordX']; ?>, <?php echo $value['CoordY']; ?>, 20);	// .push(X,Y,Pressure)
	document.getElementById("canv").getContext("2d").fillText(<?php echo $value['CoordX']; ?>, <?php echo $value['CoordY']; ?>, 20);
	
<?php
endforeach;
?>

// render the map
heatmap.render(1, null, null);

</script>