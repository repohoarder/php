
$(document).ready(function() {


	$('#offer_submit_btn, li.btn a, .red_submit_btn, .sign_up_btn_big, .download_pdf a.click_here_btn, .login_info').hover(function(){
		$(this).animate({opacity: 0.75}, 300);
	}, function () {
		$(this).animate({opacity: 1}, 300);
	});

});