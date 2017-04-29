$(document).ready(function(){

	$('.box .more').show();
	$('.box .view').show();

	$('.pop_preview').click(function(){

		var $preview = $(this).parent().find('.funnel_preview');

		$.fancybox({content: $preview});

		return false;

	});

	$('.pop_upsell').click(function(){

		var 
			$elem = $(this).parent().find('.funnel_popup'),
			$copier = $elem.find('.copier'),
			$link_input = $elem.find('.funnel-link');

		$link_input.data(
			'originalbg',
			$link_input.css('backgroundColor')
		);

		$.fancybox(
			{
				content: $elem,
				afterShow: function() {

					if ( ! $copier.data('zclip_init')) {

						$copier.data('zclip_init', 1);

						$copier.zclip({
							path : '/resources/allphase/js/zclip/ZeroClipboard.swf',
							copy : function(){
								return $link_input.val();
							},
							afterCopy: function() {

								$link_input.animate({

									backgroundColor: '#99ccff'

								}, 'fast', function(){

									$(this).animate({
										backgroundColor: $(this).data('originalbg')
									}, 'fast');

								});
							}
						});

					}

				},
				afterClose: function()
				{
					$copier.zclip('remove');
					$copier.data('zclip_init', 0);

				}

			}
		);
		return false;

	});

	$('.funnel_popup .delete_funnel').submit(function(){

		var funnel_name = $(this).parent().find('input[name="funnel_name"]').val();
		return confirm('Are you sure you want to delete funnel: '+funnel_name+'?');

	});

	$('input.funnel-link').focus(function(){
		this.select();
	});
	
});