
<!-- sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" oldtitle="Hide Sidebar">Sidebar switch</a>

<div class="sidebar">
	
	<div class="antiScroll">
		<div class="antiscroll-inner" style="width: 240px; height: 567px;">
			<div class="antiscroll-content" style="height: 567px;">
		
				<div class="sidebar_inner" style="min-height: 100%;">

						<div class="sidebar_info">
							<ul class="unstyled">
								<li>
									<span class="act act-success"><?php echo $this->partners->activated(); ?></span>
									<strong>Partners Activated</strong>
								</li>
								<li>
									<span class="act act-warning"><?php echo $this->partners->registered(); ?></span>
									<strong>Partners Registered</strong>
								</li>
								<!--
								<li>
									<span class="act act-danger">85</span>
									<strong>New registrations</strong>
								</li>
								-->
							</ul>
						</div>

						<br>


						<div class="accordion-group">
							<div class="accordion-heading">
								<a href="#collapse7" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
								   <i class="icon-th"></i> Calculator
								</a>
							</div>
							<div class="accordion-body collapse" id="collapse7">
								<div class="accordion-inner">
									<form name="Calc" id="calc">
										<div class="formSep control-group input-append">
											<input type="text" style="width:142px" name="Input"><button type="button" class="btn" name="clear" value="c" onclick="Calc.Input.value = ''"><i class="icon-remove"></i></button>
										</div>
										<div class="control-group">
											<input type="button" class="btn btn-large" name="seven" value="7" onclick="Calc.Input.value += '7'">
											<input type="button" class="btn btn-large" name="eight" value="8" onclick="Calc.Input.value += '8'">
											<input type="button" class="btn btn-large" name="nine" value="9" onclick="Calc.Input.value += '9'">
											<input type="button" class="btn btn-large" name="div" value="/" onclick="Calc.Input.value += ' / '">
										</div>
										<div class="control-group">
											<input type="button" class="btn btn-large" name="four" value="4" onclick="Calc.Input.value += '4'">
											<input type="button" class="btn btn-large" name="five" value="5" onclick="Calc.Input.value += '5'">
											<input type="button" class="btn btn-large" name="six" value="6" onclick="Calc.Input.value += '6'">
											<input type="button" class="btn btn-large" name="times" value="x" onclick="Calc.Input.value += ' * '">
										</div>
										<div class="control-group">
											<input type="button" class="btn btn-large" name="one" value="1" onclick="Calc.Input.value += '1'">
											<input type="button" class="btn btn-large" name="two" value="2" onclick="Calc.Input.value += '2'">
											<input type="button" class="btn btn-large" name="three" value="3" onclick="Calc.Input.value += '3'">
											<input type="button" class="btn btn-large" name="minus" value="-" onclick="Calc.Input.value += ' - '">
										</div>
										<div class="formSep control-group">
											<input type="button" class="btn btn-large" name="dot" value="." onclick="Calc.Input.value += '.'">
											<input type="button" class="btn btn-large" name="zero" value="0" onclick="Calc.Input.value += '0'">
											<input type="button" class="btn btn-large" name="DoIt" value="=" onclick="Calc.Input.value = Math.round( eval(Calc.Input.value) * 1000)/1000">
											<input type="button" class="btn btn-large" name="plus" value="+" onclick="Calc.Input.value += ' + '">
										</div>
									</form>
								</div>
							 </div>
						</div>

						<!--
						<div class="accordion-group">
							<div class="accordion-heading">
								<a href="#collapseFour" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
									<i class="icon-folder-close"></i> Statistics
								</a>
							</div>
							<div class="accordion-body collapse" id="collapseFour">
								<div class="accordion-inner">
									<ul class="nav nav-list">
										<li class="nav-header">Visits</li>
										<li><a href="javascript:void(0)">Unique Visitors</a></li>
										<li><a href="javascript:void(0)">Page Views</a></li>
										<li class="nav-header">Sales</li>
										<li><a href="javascript:void(0)">Sale Counts</a></li>
										<li><a href="javascript:void(0)">Gross Revenue</a></li>
										<li><a href="javascript:void(0)">Net Revenue</a></li>
										<li class="nav-header">Conversion</li>
										<li><a href="javascript:void(0)">EPC</a></li>
										<li><a href="javascript:void(0)">Percentage</a></li>
										<li class="divider"></li>
										<li><a href="javascript:void(0)">Help</a></li>
									</ul>
								</div>
							</div>
						</div>
						-->
						
					</div>
					
					<div class="push"></div>
				</div>
				<!--   
				<div class="sidebar_info">
					<ul class="unstyled">
						<li>
							<span class="act act-warning">65</span>
							<strong>New comments</strong>
						</li>
						<li>
							<span class="act act-success">10</span>
							<strong>New articles</strong>
						</li>
						<li>
							<span class="act act-danger">85</span>
							<strong>New registrations</strong>
						</li>
					</ul>
				</div> 
			-->
			</div>
		</div>
	</div>