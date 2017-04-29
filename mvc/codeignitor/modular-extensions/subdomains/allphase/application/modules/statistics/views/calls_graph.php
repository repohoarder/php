<script type="text/javascript">

	$(document).ready(function(){

		$('#calls_statistics .button').live('click',function(e){

			if (typeof drawn_charts === 'undefined' || drawn_charts.length < 1){
				return;
			}

			var 
				from_date = $('#txtCustomFrom').val(),
				to_date   = $('#txtCustomTo').val(),
				ch_series,
				ch_cats,
				chart_div = $(this).closest('.graph_form').find('#calls_graph'),
				ch_num = 0;

			if (chart_div.length < 1){
				chart_div = $(this).closest('.graph_form');
			}
			
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
				url: '/statistics/graphs/ajax_calls',
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
						ch_cats   = new Array(),
						j = 0;

					if ( ! response.success){

						return;
					}

					for (i in response.data){

						ch_cats[j]   = response.data[i]['date'];
						ch_series[j] = parseFloat(response.data[i]['num_calls']);

						j++;

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