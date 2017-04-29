		<div id="t-main" role="main">
			<div class="center-width">
				<div class="content">
					<div class="col-l">
						<h1><p style="font-size:70%;"><?php echo $text; ?></p></h1>

						
						<div class="flowplayer">
							<?php if ( ! isset($youtube_id) || ! $youtube_id): ?>

								<video preload autoplay>
									<source  type="video/flash" src="<?php echo $video; ?>"/>
								</video>

							<?php else: 

								$data['width']      = 490;
								$data['height']     = 276;
								$data['youtube_id'] = $youtube_id;
								$this->load->view('offer/youtube_embed', $data);

							endif; ?>
						</div>
						

						<span><?php echo $this->lang->line('mcsd_arrow'); ?></span>
					</div>
					<div class="col-r">
						<h2><?php echo $this->lang->line('mcsd_how_it_works'); ?></h2>
						<ol>
							<li class="one"><?php echo $this->lang->line('mcsd_step_1'); ?></li>
							<li class="two"><?php echo $this->lang->line('mcsd_step_2'); ?></li>
							<li class="three"><?php echo $this->lang->line('mcsd_step_3'); ?></li>
							<li class="four">Make Money With Your New Empire Website</li>
						</ol>
						<a href="<?php echo $url; ?>" class="btn-yellow">Activate Your Empire Website Now</a>
					</div>
				</div>
			</div>
		</div> 

		<!-- <iframe src="https://8943.clicksurecpa.com/pixel?transactionRef=<?php echo $this->session->userdata('ip_address'); ?>" width="1px" height="1px" frameborder="0"></iframe> -->

		<!--
		<script type="text/javascript">
			popping = false;
			function exit_popping()
			{
				if (popping)
				{
					popping = false;
					$('#exit_pop').submit();
						var msg = ">>>W A I T  B E F O R E  Y O U  G O!<<<\r\n\r\n********************************************\r\nIMPORTANT!!!!!\r\n********************************************\r\n\r\nWe only have a few FREE Websites left. You must claim your website today in order to get it for FREE!\r\n\r\nWe normally charge $1995 for the exact same website that you are about to receive.\r\n\r\nDon't miss out on this incredible opportunity.Once all the FREE websites are claimed you will have to pay the normal price of $1995 tohave a website built.\r\nClaim your FREE website right now by signing up for your own domain name and web hosting with Brain Host.\r\n\r\nOnce you do this we will build you a custom1 of a kind website worth $1995 for FREE!\r\n\r\n********************************************\r\nIMPORTANT!!!!!\r\n********************************************";
						return msg;
				}
			}

			$(window).load(function () {

				pop_leaving = false;
				popping 	= true;

				$('a').click(function()
				{
					popping = false;
				});

				$('form').submit(function()
				{
					popping = false;
				});
			});

			window.onunload = window.onbeforeunload = exit_popping;	


		</script>

		<form id="exit_pop" action="<?php echo $url; ?>" style="display:hidden;"></form>
		-->

<script>
var exitsplashalertmessage = '***************************************\n\n > > > W A I T < < <\n\n CLICK THE ***CANCEL*** BUTTON\n on the NEXT Window for Something\n VERY Special!\n\n***************************************';
var exitsplashmessage = '***************************************\n\n W A I T B E F O R E Y O U G O !\n\n CLICK THE *CANCEL* BUTTON RIGHT NOW\n TO STAY ON THE CURRENT PAGE.\n\n WE HAVE SOMETHING VERY SPECIAL FOR YOU!\n\n***************************************';
var exitsplashpage = '<?php echo $url; ?>';
</script>
<script language="javascript" src="http://a.hostingaccountsetup.com/exitsplash.html"></script>