pop_leaving = false;

$(document).ready(function(){
	
	pop_leaving = true;
		
	$('a, button, input[type=submit]').live('click',function()
	{
		pop_leaving = false;
	});

	$('form').live('submit',function()
	{
		pop_leaving = false;
	});

});

function exit_pop()
{
	if (pop_leaving)
	{
		pop_leaving = false;

		return 'You\'re only minutes away from getting your own website up and running online.\r\nRemember, with reliable web hosting you get:\r\n\r\n     	- Free Site-Building Tools\r\n     	- Unlimited Disk Space\r\n     	- Unlimited Email Accounts\r\n     	- Unlimited Domain Hosting\r\n\r\nPlus more than $400 in free bonuses when you sign up today!  These bonuses are only available for a limited time, so sign up today!';
	}
}


window.onunload = window.onbeforeunload = exit_pop;