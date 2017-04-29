
			</div>
		</section>
	</div>


	<?php

	$this->load->config('reports');

	$brands  = $this->config->item('reports_brands');
	
	$reports = $this->config->item('reports_reports');
	
	?>

	<aside id="pnl-nav" class="closed">
		<div class="toggle">
			<h2>Reports</h2>
			<div class="totals">
				<span class="left">total categories: <?php echo count($brands); ?></span>
				<span class="right">total reports: <?php echo count($brands) * count($reports); ?></span>
			</div>
			<ol>

				<?php foreach ($brands as $readable => $brand_key): ?>

					<li>
						<a href="#" id="cat-1"><?php echo $readable; ?></a>
						<ol>
							<?php foreach($reports as $name => $report_key): ?>

								<li><a href="/dashboard/report/<?php echo $report_key;?>/<?php echo $brand_key;?>"><?php echo $name; ?></a></li>

							<?php endforeach; ?>

							<li><a href="/dashboard/brand/<?php echo $brand_key; ?>">Show All Reports</a></li>
						</ol>
					</li>

				<?php endforeach; ?>

			</ol>
		</div>
	</aside>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.7.1.min.js"></script>
	<script type="text/javascript">
		if (typeof jQuery == 'undefined') {
			document.write(unescape("%3Cscript src='js/libs/jquery-1.7.1.min.js' type='text/javascript'%3E%3C/script%3E"));
		}
	</script>
	
	<script src="/resources/reports/js/plugins.js"></script>
	<script src="/resources/reports/js/script.js"></script>
	<script src="/resources/reports/highcharts/js/highcharts.js"></script>
	<script src="/resources/reports/highcharts/js/modules/exporting.js"></script>
	<script src="/resources/reports/highcharts/js/themes/gray.js"></script>
	
	<script src="/resources/reports/js/jquery-1.9.1.js"></script>
	<script src="/resources/reports/js/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="/resources/reports/js/ui/jquery.ui.datepicker.js"></script>
		<script>
		$(document).ready(function(){
			$('#start_date').datepicker({ dateFormat: 'mm' })
			$('#end_date').datepicker({ dateFormat: 'yy' })
		});
		</script>

	<!-- This JS auto-refreshes the page if no page activity is shown -->
	<script>
	     var time = new Date().getTime();
	     $(document.body).bind("mousemove keypress", function(e) {
	         time = new Date().getTime();
	     });

	     function refresh() {
	         if(new Date().getTime() - time >= 600000) 
	             window.location.reload(true);
	         else 
	             setTimeout(refresh, 100000);
	     }

	     setTimeout(refresh, 100000);
	</script>

	<?php echo $template['footermeta']; ?>
	
</body>
</html>
