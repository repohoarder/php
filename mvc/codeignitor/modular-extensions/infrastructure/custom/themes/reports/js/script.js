/* Globals */
isMenuOpen = false;

$(document).ready(function() {
	// Create console.log if not available
	if (!window.console) console = {log: function() {}};
	
	/* Open and close the nav on hovers */
	$('#pnl-nav').bind({
		mouseenter: function() {
			$(this).find('.toggle').clearQueue().fadeTo(200, 1);
			$(this).clearQueue().animate({marginLeft:0 }, 300).removeClass('closed').addClass('open');
			isMenuOpen = true;
		},
		mouseleave: function() {
			$(this).find('.toggle').clearQueue().fadeTo(200, 0);
			$(this).clearQueue().animate({marginLeft:-226 }, 300).removeClass('open').addClass('closed');
			isMenuOpen = false;
		}
	});
	
	/* Expand/contract navigation */
	$('#cat-1, #cat-2, #cat-3').bind({
		click: function(e) {
			e.preventDefault();
			theList = $(this).parent().find('ol');
			$('.toggle ol').find('ol').not(theList).slideUp(200);
			theList.slideToggle(200);
		}
	});
	
	/* Filters */
	$('#s-filters a').bind({
		click: function(e) {
			e.preventDefault();
			
			var elID = $(this).attr("id");
			
			$(this).parent().parent().find('li').each(function(index, element) {
				$(this).removeClass('active');
			});
			
			$(this).parent().addClass('active');
			
			switch(elID){
				case "filter-day":
					$('#s-filters .pnl-custom').clearQueue().fadeOut(300);
					// Ajax call goes here
					break;
				case "filter-month":
					$('#s-filters .pnl-custom').clearQueue().fadeOut(300);
					// Ajax call goes here
					break;
				case "filter-year":
					$('#s-filters .pnl-custom').clearQueue().fadeOut(300);
					// Ajax call goes here
					break;
				case "filter-custom":
					$('#s-filters .pnl-custom').clearQueue().fadeIn(300);
					break;
			}
		}
	});
});