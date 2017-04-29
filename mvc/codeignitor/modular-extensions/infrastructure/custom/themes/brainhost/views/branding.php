<?php

// set affiliate id
$affiliate_id 	= $this->session->userdata('affiliate_id');

$style = '';

if (isset($affiliate_id) && $affiliate_id=='102019'):

	$style = 'style="margin-top:0;"';

endif;


$logo = '/resources/brainhost/img/logo.png';

if (isset($image) && $image):

	$logo = $image;

endif;


## Idea Incubator Logo Hack
if (isset($affiliate_id) AND $affiliate_id == 102912):

	$logo 	= '/resources/brainhost/img/roi_logo.png';

endif;
## End Idea Incubator Logo Hack


$company = $this->lang->line('brand_company');

if (isset($brand) && $brand):

	$company = $brand;

endif;



$logo_link = $this->anchors->get_link('homepage');
$logo_style = '';

if (isset($home_link) && $home_link):

	$logo_link = $home_link;

endif;

if ($logo_link == '#'):

	$logo_style = 'cursor:default;';

endif;

?>

<header id="t-branding">
	<div class="wrapper">

		<a href="<?php echo $logo_link; ?>" class="logo" <?php echo $style; ?> style="<?php echo $logo_style; ?>">
			<img src="<?php echo $logo; ?>" alt="<?php echo $company; ?>" />
		</a>
		
		
		<?php 

		if (isset($login) && $login === TRUE):

			echo $this->template->load_view('login_form'); 

		endif;

		?>
		
	</div>


	<?php /*
	<style type="text/css">
		.lpPoweredByDiv {display:none;}
		.lpStaticButtonTR a {cursor:default;}
	</style>
	<div class="button-holder">			
		<div id="lpButDivID-1301580261"></div>			
		<script src="https://server.iad.liveperson.net/hc/47540901/?cmd=mTagRepstate&amp;site=47540901&amp;buttonID=12&amp;divID=lpButDivID-1301580261&amp;bt=1&amp;c=1" charset="UTF-8" type="text/javascript"></script>	
	</div>
	*/ ?>

</header>