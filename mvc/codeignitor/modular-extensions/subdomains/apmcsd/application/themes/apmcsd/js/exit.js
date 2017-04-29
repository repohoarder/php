pop_leaving = false;
function exit_pop()
{
	if (pop_leaving)
	{
		pop_leaving = false;
			var msg = ">>>W A I T  B E F O R E  Y O U  G O!<<<\r\n\r\n********************************************\r\nIMPORTANT!!!!!\r\n********************************************\r\n\r\nWe only have a few FREE Websites left. You must claim your website today in order to get it for FREE!\r\n\r\nWe normally charge $1995 for the exact same website that you are about to receive.\r\n\r\nDon't miss out on this incredible opportunity.Once all the FREE websites are claimed you will have to pay the normal price of $1995 tohave a website built.\r\nClaim your FREE website right now by signing up for your own domain name and web hosting with Brain Host.\r\n\r\nOnce you do this we will build you a custom1 of a kind website worth $1995 for FREE!\r\n\r\n********************************************\r\nIMPORTANT!!!!!\r\n********************************************";
			return msg;
	}
}

$(window).load(function () {

	pop_leaving = true;

	$('a').click(function()
	{
		pop_leaving = false;
	});

	$('form').submit(function()
	{
		pop_leaving = false;
	});
});

window.onunload = window.onbeforeunload = exit_pop;	


