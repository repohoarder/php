

		<?php if( ! isset($breadcrumb)):
			
			?>
	
			<nav>
                <div id="jCrumbs" class="breadCrumb module">
                    <div style="overflow:hidden; position:relative; width: 1366px;">
                    	<div>

		                    <ul style="width: 5000px;">
		                    	<li class="first">
		                    		<a href="/home"><i class="icon-home"></i></a>
		                		</li> 

								<?php
								// initialize variables
								$url	= '';	// this variable will hold the URL value of the breadcrumb

								// grab URI string to build breadcrumb
								$uri 	= $this->uri->segment_array();

								foreach ($uri AS $key => $value):

									// add this to the URL
									$url .= '/'.$value;

									// if this is the last item in the array, then we need to use different LI
									if (count($uri) == $key):
								?>
									<li class="last" style="background: none repeat scroll 0% 0% transparent;"><?php echo ucwords($value); ?></li>
								<?php
									else:	// this isn't the last item in the array
								?>
									<li style="background: none repeat scroll 0% 0% transparent;">

										<span style="overflow: hidden;">
											<a href="<?php echo $url; ?>"><?php echo ucwords($value); ?></a>
										</span>
										
										<div class="chevronOverlay" style="display: block;"></div>

									</li>

								<?php 
									endif;	// end checking to see if this is the last item in the array

								endforeach;
								?>
                 
		                    </ul>
                		</div>
           			</div>
                </div>
            </nav>
<?php 
else:
	echo $breadcrumb;
endif; ?>
			
            