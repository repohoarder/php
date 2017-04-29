<?php
// don't show exit pop for affiliate id 102912 (Idea Incubator)
if ($this->session->userdata('affiliate_id') == 102912):
	$noexitpop 	= TRUE;
endif;
?>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<script src="/resources/brainhost/js/plugins.js"></script>
<script src="/resources/brainhost/js/script.js"></script>

<script type="text/javascript" src="/resources/brainhost/js/loading.js"></script>
<?php if( ! isset($noexitpop) OR ! $noexitpop) : ?>
<script type="text/javascript" src="/resources/brainhost/js/par.js"></script>
<?php endif; ?>
<div id="legalese" style="text-align:center;width:800px;margin:0px auto;font-size:12px;clear:both;padding-top:15px;">

	Copyright <span id="copyright">&copy;</span> Brainhost.com.
	<a rel="iframe" title="" class="lightview" href="https://orders.brainhost.com/popup/privacy/off">Privacy Statement</a> |
	<a rel="iframe" title="" class="lightview" href="https://orders.brainhost.com/popup/terms/off">Terms of Service</a>

</div>

<?php 

echo $template['footermeta']; 

echo $this->template->load_view('tracking_pixels_global'); 

echo $this->template->load_view('tracking_pixels_retargeting'); 


$this->load->config('debug');

if (in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))): ?>

	<script type="text/javascript" src="/resources/brainhost/js/debugger.js"></script>

	<?php 

endif; ?>

</body>
</html>