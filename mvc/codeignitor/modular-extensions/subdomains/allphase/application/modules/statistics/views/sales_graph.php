<script type="text/javascript">

	$(document).ready(function(){

		$('#pnl-sales-custom-date .button').live('click',function(e){

			if (typeof drawn_charts === 'undefined' || drawn_charts.length < 1){
				return;
			}

			var 
				from_date = $('#txtCustomFrom').val(),
				to_date   = $('#txtCustomTo').val(),
				ch_series,
				ch_cats,
				ch_num = $(this).closest('.graph_form').find('.highchart').data('hchart');

			if (from_date==''){
				from_date = $('#txtCustomFrom').attr('placeholder');
			}

			if (to_date==''){
				to_date   = $('#txtCustomTo').attr('placeholder');
			}

			if (from_date=='' || to_date=='') {
				return;
			}

			$.ajax({
				url: '/statistics/graphs/ajax_revenue',
				async: false,
				type: 'POST',
				data: {
					start_date: from_date,
					end_date: to_date
				},
				dataType: 'json',
				success: function(response){

					var 
						ch_series = new Array(), 
						ch_cats   = new Array();

					if ( ! response.success){

						return;
					}

					for (i in response.data){

						ch_cats[i]   = response.data[i]['date'];
						ch_series[i] = parseFloat(response.data[i]['revenue']);

					}

					drawn_charts[ch_num].xAxis[0].setCategories(ch_cats, false);
					drawn_charts[ch_num].setTitle(null, {text: 'Custom date range'});
					drawn_charts[ch_num].series[0].setData(ch_series, false);
					drawn_charts[ch_num].redraw();

				}

			});

			e.preventDefault();

		});

	});

</script>