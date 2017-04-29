function hc_readable_date(date_str){

    if (typeof date_str == 'undefined'){
        return;
    }

    var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ],
        a = date_str.split("-"),
        d = new Date( a[0], (a[1] - 1), a[2] );
        
    return monthNames[d.getMonth()]+" "+d.getDate();

}

function hc_clean_input_key(key){

    return key.toLowerCase().replace(/\s/g,'');

}


var drawn_charts = new Array();


$(document).ready(function(){

    var defaultOptions = Highcharts.setOptions({
        lang: {
            csvTitle: 'Export CSV'
        },

        credits: {
            enabled: false
        },

        exporting: {

            buttons: {
                csvButton: {
                    symbol: 'diamond',
                    x: -62,
                    symbolFill: '#B5C9DF',
                    hoverSymbolFill: '#779ABF',
                    _titleKey: 'csvTitle',
                    onclick: function() {

                        var chart_series = this.series,
                            csv          = [],
                            series       = [],
                            i            = 0,
                            j            = 0,
                            cat          = '',
                            headers      = [],
                            $frame       = $('<iframe id="csv_frame">'),
                            $form        = $('<form method="post" action="/generate_csv">'),
                            $input       = $();

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

                            $input = $('<input type="text" name="rows['+hc_clean_input_key(cat)+'][category]" value="'+cat+'">').appendTo($form);

                            headers['Category'] = 'Category';

                            for (name in csv[cat]){ 

                                $input = $('<input type="text" name="rows['+hc_clean_input_key(cat)+']['+hc_clean_input_key(name)+']" value="'+csv[cat][name]+'">').appendTo($form);

                                headers[name] = name;

                            }
                        }

                        for (i in headers) {

                            $input = $('<input type="text" name="headers['+hc_clean_input_key(i)+']" value="'+headers[i]+'">').appendTo($form);

                        }

                        $frame.appendTo('body');
                        $form.submit();

                    }
                }
            }
        }


    });

});





function draw_linechart(ch_params)
{

    var chart_num  = drawn_charts.length;
    ch_params.label_y_decimals = ch_params.label_y_decimals != null ? false : true;
    ch_params.container.addClass('highchart line_basic');
    ch_params.container.attr('data-hchart', chart_num);

    drawn_charts[chart_num] = new Highcharts.Chart({
        chart: {
            renderTo: ch_params.container[0],
            type: 'line',
            marginRight: 130,
            marginBottom: 25,
            height: ch_params.height
        },
        title: {
            text: ch_params.title,
            x: -20 //center
        },
        subtitle: {
            text: ch_params.subtitle,
            x: -20 //center
        },
        xAxis: {
            title: {
                text: ch_params.label_x
            },
            categories: ch_params.categories_x,
            labels: {
                formatter: function() {

                    return (ch_params.label_x_format != null ? eval(ch_params.label_x_format) : this.value);

                }
            }
        },
        yAxis: {
            title: {
                text: ch_params.label_y
            },
			allowDecimals : ch_params.label_y_decimals,
            labels: {
                formatter: function() {
                    return (ch_params.label_y_format != null ? eval(ch_params.label_y_format) : this.value);
                }
            }
        },
        tooltip: {
            formatter: function() {

                return (ch_params.tooltip_format != null ? eval(ch_params.tooltip_format) : (this.x + " - " + this.y));

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
        series: ch_params.series,

    });


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
}