function g(id){
	return document.getElementById(id);
}

function get(id){
	return g(id);
}

function toggle_hp_boxes(div){
	var boxes = 4;
	//turn all off
	for(var i=1; i < boxes; i++){
		if(g('hp_featured_block'+i) && g('clicker'+i)){ 

			if(div != i){ 
				g('hp_featured_block'+i).style.display='none';
				g('clicker'+i).className=null;
			}else{
				g('hp_featured_block'+i).style.display='block';
				g('clicker'+i).className = 'on';
			}
		}
	}
}

function limitChars(textid, limit, infodiv){
	var text = $('#'+textid).val();	
	var textlength = text.length;
	if(textlength > limit)
	{
		$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
		$('#'+textid).val(text.substr(0,limit));
		return false;
	}
	else
	{
		$('#' + infodiv).html('You have '+ (limit - textlength) +' characters left.');
		return true;
	}
}

////////////////////
// start email verify...
function echeck(str) {
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if(str.indexOf(at)==-1){
		return false;
	}
	if(str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		return false;
	}
	if(str.indexOf(dot)==-1 || str.indexOf(dot)==0 || 
	str.indexOf(dot)==lstr){
		return false;
	}
	if(str.indexOf(at,(lat+1))!=-1){
		return false;
	}
	if(str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		return false;
	}
	if(str.indexOf(dot,(lat+2))==-1){
		return false;
	}
	if(str.indexOf(" ")!=-1){
		return false;
	}

	return true;
}
// end email verify...

function isUrl(s) {
	return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(s);
}


function validate_website_form(){

	name = g('name_field').value;
	email = g('email_field').value;
	website = g('website_field').value;
	website_title_field = g('website_title_field').value;
	website_slogan_field = g('website_slogan_field').value;

	g('error_output').style.display = 'none';

	error = 0;
	error_msg = '<strong>Please correct the following errors: </strong><br />';

	if(name == ''){
		error = 1;
		error_msg += '&bull; Please enter your name.<br />';

	}

	if(email == '' || !echeck(email)){
		error = 1;
		error_msg += '&bull; Please a valid email address.<br />';

	}

	if(website == '' || website == 'http://'){
		error = 1;
		error_msg += '&bull; Please enter your website address.<br />';

	}

	if(!isUrl(website)){
		error = 1;
		error_msg += '&bull; Please enter a valid website address.<br />';

	}

	if(website_title_field == ''){
		error = 1;
		error_msg += '&bull; Please enter a slogan for your website.<br />';

	}

	if(website_slogan_field == ''){
		error = 1;
		error_msg += '&bull; Please enter a title for your website.<br />';

	}

	if(g('keywords').value == ''){
		error = 1;
		error_msg += '&bull; Please enter your core website keywords.<br />';
	}

	if(error){
		g('error_output').innerHTML = error_msg;
		g('error_output').style.display = 'block';
		return false;
	}else{
		return true;
	}

}

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=470,height=600,left = 439,top = 134');");
}

function setCookie(name, value, expires){
    if (!expires) expires = new Date(); 
	document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() + "; path=/";
}

//used on offer.php to set cookie so users don't get stuck in split test loop
function form_filled_out(){
	var expdate = new Date (); // pre-set to the current time and date
	expdate.setTime(expdate.getTime() + 1000 * 60 * 60 * 24 * 365); // add one year to it 
	setCookie("form_copmlete","complete",expdate);
	return true;
}