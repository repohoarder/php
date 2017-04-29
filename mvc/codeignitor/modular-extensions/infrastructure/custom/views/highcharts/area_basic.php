
<script type="text/javascript">

    $(document).ready(function() {

    	var $container = $('<div>').appendTo('#s-reports .pad');

    	$container.addClass('highchart area_basic');
 
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: $container[0],
                type: 'area'
            },
            title: {
                text: "<?php echo $title; ?>"
            },
            subtitle: {
                text: "<?php echo (isset($subtitle) ? $subtitle : ''); ?>"
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
            plotOptions: {
                area: {
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            series: <?php echo json_encode($series); ?>

        });
    });

</script>