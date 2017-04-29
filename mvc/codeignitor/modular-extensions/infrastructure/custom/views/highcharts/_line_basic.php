<script type="text/javascript">

    $(document).ready(function() {

    	var $container = $('<div>').appendTo('#s-reports .pad');
    	
        $container.addClass('highchart line_basic');

        var chart = new Highcharts.Chart({
            chart: {
                renderTo: $container[0],
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: "<?php echo $title; ?>",
                x: -20 //center
            },
            subtitle: {
                text: "<?php echo (isset($subtitle) ? $subtitle : ''); ?>",
                x: -20 //center
            },
            xAxis: {
            	title: {
                    text: "<?php echo (isset($label_x) ? $label_x : ''); ?>"
                },
            	categories: <?php echo json_encode($categories_x); ?>,
                labels: {
                    formatter: function() {

                        return <?php echo (isset($label_x_format) ? $label_x_format : 'this.value'); ?>;

                    }
                }
            },
            yAxis: {
                title: {
                    text: "<?php echo $label_y; ?>"
                },
                labels: {
                    formatter: function() {
                        return <?php echo (isset($label_y_format) ? $label_y_format : 'this.value'); ?>;
                    }
                }
            },
            tooltip: {
                formatter: function() {

                	return <?php echo (isset($tooltip_format) ? $tooltip_format : 'this.x + " - " + this.y');?>;

                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: <?php echo json_encode($series); ?>,

            /*
            exporting: {

                buttons: {
                    csvButton: {
                        symbol: 'diamond',
                        x: -62,
                        symbolFill: '#B5C9DF',
                        hoverSymbolFill: '#779ABF',
                        //_titleKey: 'printButtonTitle',
                        onclick: function() {

                            var chart_series = this.series,
                                csv          = [],
                                series       = [],
                                i            = 0,
                                j            = 0,
                                csv_count    = 0,
                                cat          = '',
                                headers      = [];

                            for (i = 0; i < chart_series.length; i++) {

                                if (chart_series[i].name == 'Navigator') continue;


                                series = chart_series[i];

                                for (j = 0; j < series.data.length; j++) {

                                    cat = series.data[j].category;

                                    if (typeof csv[cat] === 'undefined') {

                                        csv[cat] = [];
                                    }

                                    csv[cat][series.name] = series.data[j].y; 

                                }

                            }

                            $('body #csv_frame').remove();

                            $frame.html('');
                            $form.html('');

                            $form.appendTo($frame);

                            for (cat in csv){

                                $input = $('<input type="text" name="rows['+cat+'][Category]" value="'+cat+'">').appendTo($form);

                                headers['Category'] = 'Category';

                                for (name in csv[cat]){ 

                                    $input = $('<input type="text" name="rows['+cat+']['+name+']" value="'+csv[cat][name]+'">').appendTo($form);

                                    headers[name] = name;

                                    //csv_count++;

                                }
                            }

                            for (i in headers) {

                                $input = $('<input type="text" name="headers['+i+']" value="'+headers[i]+'">').appendTo($form);

                            }

                            $frame.appendTo('body');
                            $form.submit();

                        }
                    }
                }
            }
             */


        });
    });

</script>