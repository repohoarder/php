
<div class="maincontainer">	


	<h1><span>A Special Offer: </span>Finally  Available Near - <script language="javascript">

	            var region = geoip_region_name();

	            if ( ! region || 0 === region.length){
	                region = "The Web";
	            }
	            document.write(region);</script></h1>

	<center>
	    <div class="video">
	        <div class="video_bg" style="background: url() no-repeat; height: 275px; margin-top:-50px; ">

				<!-- Flowplayer for video -->
				<link rel="stylesheet" type="text/css" href="http://releases.flowplayer.org/5.3.1/skin/minimalist.css" />
				<script src="http://releases.flowplayer.org/5.3.1/flowplayer.min.js"></script>

				<style type="text/css">
				.fp-waiting{ display:none !important;}
				</style>

				<div class="flowplayer offer_video" style="display:block;width:500px;height:306px" >

					<?php if ( ! isset($youtube_id) || ! $youtube_id): ?>

						<video preload autoplay controls>
					        <source  type="video/flash" src="<?php echo $video; ?>"/>
					    </video>

					<?php else: 

						$data['width']      = 500;
						$data['height']     = 306;
						$data['youtube_id'] = $youtube_id;
						$this->load->view('offer/youtube_embed', $data);

					endif; ?>

				</div>

	        </div>	    
	    </div>
	</center>



    
    <div class="check">
        <input type="checkbox" checked="checked" class="chcekboxx" /> Yes, I want a Free Money Making Website ($1995 Value) 
    </div>
    <div class="sign_up"><a href="<?php echo $url; ?>">Click Here to Sign Up For Your Domain Name &amp; Hosting</a></div>
    <div class="getway">
        <img src="/resources/squishy/img/norton.png" />
        <img src="/resources/squishy/img/payement_2.png" />
        <img src="/resources/squishy/img/payement_3.png" />
        <img src="/resources/squishy/img/payement_4.png" />
    <div class="clear"></div>
    </div>
</div>