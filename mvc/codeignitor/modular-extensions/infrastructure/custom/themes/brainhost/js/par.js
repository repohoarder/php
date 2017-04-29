pop_leaving = false;

$(document).ready(function(){
	
	pop_leaving = true;

	$('body').append(
		'<form id="exit_pop" action="http://setup.brainhost.com/exit/pop/par" style="display:hidden;"></form>'
	);
		
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
		
		if ($('#exit_pop').length > 0)
		{
			$('#exit_pop').submit();
		}

		return 'You\'re only one step away from receiving our Free, Money-Making Guide! \nThis introduction to online marketing provides you with 100+ pages of tips and tools to help you make money online, including: \n\n - Designing a Great Website \n - Email Marketing Techniques \n - Advertising \n - Search Engine Ranking \n\n..and more!';
	}
}


window.onunload = window.onbeforeunload = exit_pop;