<script type='text/javascript'>
// set-up to use onclick 
document.onclick = getClickXY;

var IE = document.all?true:false

function getClickXY(e){
	var tempX = 0;
	var tempY = 0;
	if (document.captureEvents) {
		var loc = window.location.protocol + '//' + window.location.host + window.location.pathname;
		if (IE) { // grab the x-y pos.s if browser is IE
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
			scrollval = $(window).scrollTop();
		} else {  // grab the x-y pos.s if browser is NS
			tempX = e.pageX;
			tempY = e.pageY;
		} 	

		$.post('/clicktale/track', { x: tempX, y: tempY, url: loc}, function(data) {
			//alert(data);		
		});			
	}	
}
</script>


<a href="#">Test Click</a>