
<script type="text/javascript">
    

    $(document).ready(function() {

        var ch_params = {
            container: $('<div>').appendTo('<?php echo (isset($charts_div) ? $charts_div : "#s-reports .pad");?>'),
            height: <?php echo isset($chart_height) ? $chart_height : 'null';?>,
            title: "<?php echo $title; ?>",
            subtitle: "<?php echo (isset($subtitle) ? $subtitle : ''); ?>",
            label_x: "<?php echo (isset($label_x) ? $label_x : ''); ?>",
            categories_x: <?php echo json_encode($categories_x); ?>,
            label_x_format: <?php echo (isset($label_x_format) ? "'".$label_x_format."'" : 'null'); ?>,
            label_y: "<?php echo $label_y; ?>",
            label_y_format: <?php echo (isset($label_y_format) ? "'".$label_y_format."'" : 'null'); ?>,
            tooltip_format: <?php echo (isset($tooltip_format) ? "'".$tooltip_format."'" : 'null');?>,
            series: <?php echo json_encode($series); ?>,
			label_y_decimals : '<?php echo isset($label_y_decimals) ? $label_y_decimals : '';?>'
        };

        draw_linechart(ch_params);
    	
    });

</script>