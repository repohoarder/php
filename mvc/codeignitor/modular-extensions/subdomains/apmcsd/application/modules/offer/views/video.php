		<div id="t-main" role="main">
			<div class="center-width">
				<div class="content">
					<div class="col-l">
						<h1><?php echo $text; ?></h1>

						
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
							<li class="four"><?php echo $this->lang->line('mcsd_step_4'); ?></li>
						</ol>
						<a href="<?php echo $url; ?>" class="btn-yellow"><?php echo $this->lang->line('mcsd_cta'); ?></a>
					</div>
				</div>
			</div>
		</div> 