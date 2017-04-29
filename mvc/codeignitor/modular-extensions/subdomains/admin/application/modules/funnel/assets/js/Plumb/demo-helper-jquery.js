/*
 *  This file contains the JS that handles the first init of each jQuery demonstration, and also switching
 *  between render modes.
 */
jsPlumb.bind("ready", function() {

	
	jsPlumb.DemoList.init();

	jsPlumb.setRenderMode(jsPlumb.SVG);
	
	jsPlumbDemo.init();
	
	// chrome fix.
	document.onselectstart = function () { return false; };				       
});