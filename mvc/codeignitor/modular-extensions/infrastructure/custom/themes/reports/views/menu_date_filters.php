<section id="s-filters">
			<div class="pad">
				<ul id="chart_date_filter">
					<li class="first">
						<?php /*
						
							
							<form action="" method="post">
								<button id="filter-day">Day</button>
								<input type="hidden" name="start_date" value="<?php echo date('Y-m-d');?>"/>
								<input type="hidden" name="end_date" value="<?php echo date('Y-m-d');?>"/>
							</form>
						</li>
						 
						<li> // <li class="active"> 
						*/ ?>
						
						<form action="" method="post">
							<button id="filter-month">Week</button>
							<input type="hidden" name="start_date" value="<?php echo date('Y-m-d',strtotime('-1 week'));?>"/>
							<input type="hidden" name="end_date" value="<?php echo date('Y-m-d');?>"/>
						</form>
					</li>
					<li>
						
						<form action="" method="post">
							<button id="filter-month">Month</button>
							<input type="hidden" name="start_date" value="<?php echo date('Y-m-d',strtotime('-1 month'));?>"/>
							<input type="hidden" name="end_date" value="<?php echo date('Y-m-d');?>"/>
						</form>
					</li>
					<li>
						
						<form action="" method="post">
							<button id="filter-year">Year</button>
							<input type="hidden" name="start_date" value="<?php echo date('Y-m-d',strtotime('-1 year'));?>"/>
							<input type="hidden" name="end_date" value="<?php echo date('Y-m-d');?>"/>
						</form>
					</li>


					<li>
						
						<form action="" method="post">
							<button id="filter-year">Month-to-Date</button>
							<input type="hidden" name="start_date" value="<?php echo date('Y-m-01');?>"/>
							<input type="hidden" name="end_date" value="<?php echo date('Y-m-d');?>"/>
						</form>
					</li>

					<li class="last">
						<a href="#" id="filter-custom">Custom</a>
					</li>
				</ul>
				<div class="pnl-custom">
					<form action="" method="post">
						<label for="txtFrom">From:</label>
						<input type="text" id="txtFrom" name="start_date" placeholder="yyyy-mm-dd"/>
						<label for="txtTo">To:</label>
						<input type="text" id="txtTo" name="end_date" placeholder="yyyy-mm-dd"/>
						<button>Go</button>
					</form>
				</div>
			</div>
		</section>