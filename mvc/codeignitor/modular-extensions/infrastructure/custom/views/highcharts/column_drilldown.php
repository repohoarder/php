
<?php
// HACK, Pass other charts by reference to dynamic container
// Do not generated rand() ID - column_drilldowns don't get along with the proper method
$id = 'column_drilldown_'.rand(0,99999);

?>

<script type="text/javascript">

    $(document).ready(function() {

        var colors = Highcharts.getOptions().colors,
            categories = <?php echo json_encode($categories_x); ?>,
            name = "<?php echo (isset($label_x) ? $label_x : '');?>",
            data = [<?php echo implode(',',$test_series);?>];
    
        function setChart(name, categories, data, color) {
            chart.xAxis[0].setCategories(categories, false);
            chart.series[0].remove(false);
            chart.addSeries({
                name: name,
                data: data,
                color: color || 'white'
            }, false);
            chart.redraw();
        }
        
        var $container = $('<div id="<?php echo $id;?>">').appendTo('#s-reports .pad');
        $container.addClass('highchart column_drilldown');

        chart = new Highcharts.Chart({
            chart: {
                renderTo: '<?php echo $id; ?>',
                type: 'column'
            },
            title: {
                text: '<?php echo $title; ?>'
            },
            subtitle: {
                text: "<?php echo (isset($subtitle) ? $subtitle : 'Click the columns to drill down. Click again to return.');?>"
            },
            xAxis: {
                categories: categories,
                labels: {
                    formatter: function() {
                        return <?php echo (isset($label_x_format) ? $label_x_format : 'this.value'); ?>;
                    }
                }
            },
            yAxis: {
                title: {
                    text: '<?php echo $label_y; ?>'
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else { // restore
                                    setChart(name, categories, data);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return <?php echo (isset($label_column_format) ? $label_column_format : 'this.y'); ?>;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.x +':<b>'+<?php echo (isset($label_column_format) ? $label_column_format : 'this.y'); ?> +'</b><br/>';
                    if (point.drilldown) {
                        s += 'Click to view '+ point.category;
                    } else {
                        s += 'Click to return';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: data,
                color: 'white'
            }]
        });
    });

</script>

<?php

/*

[
                {
                    y: 500, // total of the "data" array in drilldown below
                    color: colors[0],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [100,200,100,100],
                        color: colors[0]
                    }
                },
                {
                    y: 540,
                    color: colors[1],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [100,210,100,130],
                        color: colors[1]
                    }
                },
                {
                    y: 810,
                    color: colors[2],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [150,240,300,120],
                        color: colors[2]
                    }
                },
                {
                    y: 1060,
                    color: colors[3],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [140,250,170,500],
                        color: colors[3]
                    }
                },
                {
                    y: 950,
                    color: colors[4],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [170,280,300,200],
                        color: colors[4]
                    }
                },
                {
                    y: 1000,
                    color: colors[5],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [110,210,230,450],
                        color: colors[5]
                    }
                },
                {
                    y: 1280,
                    color: colors[6],
                    drilldown: {
                        name: 'Sales Reps',
                        categories: ['John', 'Jennifer', 'Gerald', 'Jane'],
                        data: [170,800,200,110],
                        color: colors[6]
                    }
                }
            ]
 */