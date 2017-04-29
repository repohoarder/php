$(document).ready(function(){

	var funnel_url = $('#funnel-url').val();

	$('.funnel-link').show();

	$('.funnel-link span').click(function(){

		var funnel_id = $(this).closest('.box-wrap').find('input[name="funnel_id"]').val();

		$('#funnel-hidden input').data('originalbg',$('#funnel-hidden input').css('backgroundColor'));

		$('#funnel-hidden').slideUp(function(){

			$(this).find('input').val(funnel_url + funnel_id);

			$(this).slideDown(function(){

				if ( ! $(this).data('zclip_init')) {

					$('#copy').zclip({
						path : '/resources/allphase/js/zclip/ZeroClipboard.swf',
						copy : function(){
							return $('#funnel-hidden input').val();
						},
						afterCopy: function() {

							$('#funnel-hidden input').animate({

								backgroundColor: '#99ccff'

							}, 'fast', function(){

								$(this).animate({

									backgroundColor: $(this).data('originalbg')

								}, 'fast');

							});
						}
					});

					$(this).data('zclip_init', true);
				}

			});
		});

	});

	$('#funnel-hidden input').focus(function(){

		this.select();
	
	});

	$('#funnel-hidden').hide();
	
});