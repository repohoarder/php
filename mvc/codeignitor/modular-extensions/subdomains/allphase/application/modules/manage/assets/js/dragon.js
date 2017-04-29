$(document).ready(function(){

	$('.dragon').draggable({
		revert: 'invalid',
		containment: 'document',
		helper: 'clone',
		cursor: 'move',
		cancel: '.zoom',
		start: function( event, ui ) {
			$(".tooltip").hide(200);
		}
	}); 

	$('.lair').droppable({
		accept: '.dragon',
		activeClass: 'dragon-droppable',
		hoverClass: 'dragon-hover',
		drop: function( event, ui ) {

			var dragon = ui.draggable.find('.dragon-label').text(),
				baby_dragon = ui.draggable.data('dragon'),
				$clones = $('.lair').has('input.inp-item[value="'+baby_dragon+'"]');

			if ($clones.length){
				slay_dragon($clones);
			}

			$(this).find('span.lair-label span').text(dragon);
			$(this).find('input.inp-item').val(baby_dragon);

			$(this).removeClass().addClass('lair dropped upsell-'+ui.draggable.data('dragon'));

		}
	});



	$('#container').sortable({
		stop: function(event, ui) {
			$('#container .lair').each(function(){

				var position = $(this).index() + 1;

				$(this).attr('data-lair', position);
				$(this).find('input.inp-pos').val(position);

				$(this).find('.knight').text(position);

			});
		}
    });

	$('#container').disableSelection();
	$('#accordion').accordion();


	$('.lair').click(function(e){

		var $item = $(this),
			$target = $(e.target);

		if ($target.is('.knight')){
			slay_dragon($item);
		}

		return false;

	});

	$('.billing-pages .box a').click(function(e){

		var targ = $(this).attr('href');

		$.fancybox({
			content: '<img src="'+targ+'" alt="" />'				
		});

		e.preventDefault();

	});

	$('.billing-pages').on('click','.bill_btn', function(e){

		if ($(this).hasClass('bill_select')){
			e.preventDefault();
			return;
		}

		$('.bill_select').removeClass('bill_select');
		$(this).addClass('bill_select');

		$('#billing_input').val($(this).data('slug'));

		e.preventDefault();
		return;

	});

	$('.dragon').click(function(e){

		var $item = $(this),
			$target = $(e.target),
			slug = '',
			name = '';

		if ($target.is('.zoom')){
			
			slug = $item.data('dragon');
			name = $item.find('.dragon-label span').text();

			$.fancybox({
				content: '<img src="/resources/modules/manage/assets/images/previews/'+slug+'.png" alt="'+name+'" title="'+name+'" />'				
			});

		}

		return false;

	});

});


function slay_dragon($elem)
{

	var start = 0;

	$elem.each(function(){

		start = $(this).data('lair');

		$(this).find('span.lair-label span').text('');
		$(this).find('input.inp-item').val('');

		$(this).removeClass('dropped');

		var 
			classes = $(this).attr('class').split(/\s+/),
			$that = $(this);

		$.each(classes, function(index, item){

		    if (item.substring(0,7) !== 'upsell-'){
		       return;
		    }

		    $that.removeClass(item);

		});

	});

}