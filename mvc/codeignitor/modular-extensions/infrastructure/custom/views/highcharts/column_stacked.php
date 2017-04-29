
<script type="text/javascript">

    $(document).ready(function() {

    	var $container = $('<div>').appendTo('#s-reports .pad');
    	
        $container.addClass('highchart column_stacked');


        var chart = new Highcharts.Chart({
            chart: {
                renderTo: $container[0],
                type: 'column'
            },
            title: {
                text: "<?php echo $title; ?>"
            },
            subtitle: {
                text: "<?php echo (isset($subtitle) ? $subtitle : ''); ?>"
            },
            xAxis: {
            	categories: <?php echo json_encode($categories_x); ?>,
                labels: {
                    formatter: function() {
                        return <?php echo (isset($label_x_format) ? $label_x_format : 'this.value'); ?>;
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: "<?php echo $label_y; ?>"
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    },
                    formatter: function() {
                        return <?php echo (isset($label_stack_format) ? $label_stack_format : 'this.total'); ?>;
                    }
                }
            },
            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 20,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        formatter: function() {

                            return <?php echo (isset($label_column_format) ? $label_column_format : "this.y");?>;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {

                    return <?php echo (isset($tooltip_format) ? $tooltip_format : "<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal");?>;

                }
            },
            series: <?php echo json_encode($series); ?>
        });
    });

</script>