var viewportHeight = $(window).height();
var mainHeight = viewportHeight - 120 - 180;
var nav_open = true;

$(function() {
	if(mainHeight > 500) {
		$('#t-main').css('min-height', mainHeight);
	} else {
		$('#t-main').css('min-height', 500);
	}

	$(window).resize(function() {
		viewportHeight = $(window).height();
		mainHeight = viewportHeight - 120 - 180;
		if(mainHeight > 500) {
			$('#t-main').css('min-height', mainHeight);
		} else {
			$('#t-main').css('min-height', 500);
		}
	});

	$('#nav-main .toggle').click(function(e){
		e.preventDefault();
		$('#col-r').toggleClass('expand');
		$('#col-l').toggleClass('condense');
	});

	// Hide tooltips
	$(document).on("click", "#accordion h3, .pad, .tooltip", function(e) {
		$(".tooltip").hide(200);
	});

	// Expand code that is focussed
	$("textarea[readonly='readonly']").on({
		focus: function(){
			$(this).addClass("expand");
		},
		focusout: function(){
			$(this).removeClass("expand");
		}
	});

	// Remove confirm
	$('.s-manage-pixels table a').on({
		click: function(e){
			e.preventDefault();
			var name=$(this).parents('tr').find('strong').html();
			var delete_confirm=confirm("Do you really want to delete \""+name+"\"?");
			if (delete_confirm==true) {
				x="You pressed OK!";
			} else {
				x="You pressed Cancel!";
			}
		}
	});
});