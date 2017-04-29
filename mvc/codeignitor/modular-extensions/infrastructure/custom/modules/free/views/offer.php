<?php
// custom youtube video
$youtube 	= array(
	'103288'	=> 'pU-GsiZLBTE'
);
?>


	<h1><?php echo $header; ?></h1>

	<!-- THIS IS THE OLD WAY TO DISPLAY THE VIDEO
	
	<a id="player" style="display:block;margin:0 auto;width:588px;height:335px" href="<?php echo $video; ?>">
	
	<?php /*
		<!--[if IE]>
        <object
                type="application/x-shockwave-flash"
                data="/resources/brainhost/js/flowplayer/flowplayer-3.2.12.swf"
                codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
                pluginspage="http://www.macromedia.com/go/getflashplayer"
                classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                width="100%" 
                height="100%">

			<param value="true" name="allowfullscreen"><param value="always" name="allowscriptaccess">
			<param value="high" name="quality"><param value="#000000" name="bgcolor">
			<param value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;<?php echo $video; ?>&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;<?php echo $video; ?>&quot;}]}" name="flashvars">
        </object>
        <![endif]-->
        
        <!--[if !IE]><!-->
		<object width="100%" height="100%" type="application/x-shockwave-flash" data="/resources/brainhost/js/flowplayer/flowplayer-3.2.12.swf" name="player_api" id="player_api">
			<param value="true" name="allowfullscreen"><param value="always" name="allowscriptaccess">
			<param value="high" name="quality"><param value="#000000" name="bgcolor">
			<param value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;<?php echo $video; ?>&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;<?php echo $video; ?>&quot;}]}" name="flashvars">
		</object>
		<!--<![endif]-->
		*/ ?>
	</a>
	<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.11.min.js"></script>
	<script type="text/javascript">
		flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
			clip: {
				// these two configuration variables does the trick
				autoPlay: true,
				autoBuffering: true // <- do not place a comma here
			},
			plugins: {
				controls: null
			}
		});
	</script>
	-->

	<!-- Flowplayer for video -->
	<link rel="stylesheet" type="text/css" href="http://releases.flowplayer.org/5.3.1/skin/minimalist.css" />
	<script src="http://releases.flowplayer.org/5.3.1/flowplayer.min.js"></script>

	<?php
	
	// custom youtube code
	if (array_key_exists($affiliate_id,$youtube)):
	?>
	
		<center><iframe id="ytplayer" type="text/html" width="500" height="281.25" src="https://www.youtube.com/embed/<?php echo $youtube[$affiliate_id]; ?>?autoplay=1&controls=0&rel=0&showinfo=0&autohide=1" frameborder="0" allowfullscreen></iframe></center>

	<?php
	else: // show original video
	?>

		<div class="flowplayer" style="display:block;margin:0 auto;width:588px;height:335px">
			<video preload autoplay>
				<source  type="video/flash" src="<?php echo $video; ?>"/>
			</video>
		</div>

	<?php
	endif;
	?>


	<a class="btn-sign-up" href="<?php echo $redirect; ?>"><strong>CLICK HERE</strong> To Sign Up For Your Domain Name &amp; Hosting</a>
	<aside id="steps">
		<h2>How It Works</h2>
		<ol>
			<li class="step1"><strong>Watch</strong> The Video Above.</li>
			<li class="step2"><strong>Sign Up</strong> for Domain Name &amp; Hosting.</li>
			<li class="step3"><strong>We Build Your <?php echo $item; ?></strong> $1995 Value <strong>Yours FREE</strong>!</li>
			<li class="step4"><strong>Make Money</strong> With Your New <?php echo $item; ?>.</li>
		</ol>
	</aside>

	<?php
	// show custom pixel if there is one
	if ($pixel)
		echo $pixel;
	?>