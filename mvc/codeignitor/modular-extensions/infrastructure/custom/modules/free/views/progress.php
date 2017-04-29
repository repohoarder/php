<style>

	.ui-progressbar .ui-progressbar-value { background-image: url("../../resources/modules/free/assets/css/ui-lightness/images/pbar-ani.gif"); }
	
	.submitClick {
		border-color:#F93;
		background-color:#FFF;
	}


</style>
<script>

$(function() {
	
	$("#submitClick").click(function() {
		
		if($("#formInput").val().length < 1) {
			
			$("#notifyDomain").html("You must enter your domain.");
					
		} else {
			
			 $('#progressBarDiv').fadeIn('slow', function() {
				// Animation complete
			});
			
		}
		
	});
	
	$( "#progressbar" ).progressbar({
		value: 75
	});
	
});
</script>

<div style="margin-left:24px; margin-top:10px;">

<h2>Get website progress report</h2>

<div style="margin-left:24px; margin-top:10px;">

	Your website address: <input type="text" id="formInput" name="formInput" /> <input id="submitClick" name="submitClick" class="submitClick" type="submit" value="Get report" />
    
    <span style="color:red;" id="notifyDomain" name="notifyDomain"></span>
     
</div>

<div align="center">
    
    <div id="progressBarDiv" name="progressBarDiv" class="demo" style="display:none; margin-top:24px; width:550px;">
        
        <h3 align="left">In progress...</h3>
        
        <div id="progressbar" style="height:22px; width:550px;"></div><div align="right">75%</div>
        
    </div>

</div>

<table cellpadding="0" cellspacing="0" border="0" id="progressList" style="display:none;">
    <tbody id="progressListContent">
    
    </tbody>

</table>

</div>

<br /><br />