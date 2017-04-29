<html>
<head>

<?php 
// load all css files
foreach ($css AS $file):
?>
	<link rel="stylesheet" type="text/css" href="<?php echo $file; ?>" />  
<?php 
endforeach;
?>

<?php 
// load all js files
foreach ($js AS $file):
?>
	<script type="text/javascript" src="<?php echo $file; ?>" /></script>
<?php 
endforeach;
?>

</head>
<body>




<p>This is my test header</p>


</body>
</html>