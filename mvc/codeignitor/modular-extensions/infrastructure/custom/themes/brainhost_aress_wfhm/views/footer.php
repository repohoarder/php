  		</section>
  		<section class="whtbox_bot"></section>
    </section>
  </section>
</section>
<footer>
  <section class="copyright">Copyright <span id="copyright">&copy;</span> Brainhost.com. <a target="_blank" href="/pages/privacy">Privacy Statement</a> | <a href="/pages/terms" target="_blank">Terms of Service</a></section>
</footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script type="text/javascript" src="/resources/brainhost/js/loading.js"></script>

<script type="text/javascript">

$(document).ready(function(){

  $('#form-billing').submit(function(){
    show_loading_dialog();
  });

  $('#cvv2_popup_click').click(function(){
    
    $('#cvv2_popup').fadeIn(); 
    
    /* Hide selects so they don't poke through the popup in IE6 */
    $('#cc_exp_mo, #cc_exp_yr').css('visibility','hidden'); 
    
    return false;
    
  });
  
  $('#cvv2_popup').click(function(){
    
    $(this).fadeOut();
    
    /* Bring back any selects that may have been hidden */
    $('#cc_exp_mo, #cc_exp_yr').css('visibility','visible'); 
    
    return false;
    
  });


  state_options = $('#state optgroup');

  update_states();

  $('#country').on("change", function(event){
    update_states()
  });

  var original_zip = $('#zipcode').val();
  $('#zipcode').change(function(){
    if (is_geoiped() && original_zip && $('#zipcode').val() != original_zip){
      unset_geoiped();
    }
  });

  var original_state = $('#state').val();
  $('#state').change(function(){
    if (is_geoiped() && $('#state').val() != original_state){
      $('#zipcode').val('');
      $('#city').val('');
      unset_geoiped();
    }
  });

  var original_city = $('#city').val();
  $('#city').change(function(){
    if (is_geoiped() && original_city && $('#city').val() != original_city){
      $('#zipcode').val('');
      unset_geoiped();
    }
  });

  var original_country = $('#country').val();
  $('#country').change(function(){
    if (is_geoiped() && original_country && $('#country').val() != original_country){
      $('#zipcode').val('');
      $('#city').val('');
      unset_geoiped();
    }
  });

});


function is_geoiped()
{
  return ($('#geoiped').length > 0 && $('#geoiped').val()=='1');
}

function unset_geoiped()
{
  $('#geoiped').val('0');
}

function update_states () {
  var cur_country = $('#country').val();
  var html = [];

  $(state_options).each(function(e) {
    if ($(this).attr('label') == cur_country) {
      html = $(this);
    }
  });

  $('#state').html($(html));

  $('#country option').each(function () {
    if ($(this).val() == $('#country').val()) {
      if ($(this).attr('data-req-zip') == 'no') {
        $('#zipcode').removeClass('required error');
      } else {
        $('#zipcode').addClass('required');
      }

      if ($(this).attr('data-req-state') == 'no') {
        $('#state').removeClass('required error');
      } else {
        $('#state').addClass('required');
      }
    }
  }); 

}
  
</script>


<?php 

if ($this->session->userdata('affiliate_id') == '103275'): ?>

  <script type="text/javascript">

    var 
      user_stayed = false,
      redirected  = false,
      aff_link    = 'http://affiliate.brainhost.com/tracking/index/7b1af572f52baab9dec2ce6be82d4978/03593ce517feac573fdaafa6dcedef61/0/',
      checker     = self.setInterval(function(){redirect_if_stayed()},300);

    window.onbeforeunload = function (e)
    {
        return pop_it(e);
    }

    $('form').submit(function(){
      stop_popping();
    });



    function pop_it(e)
    {
      var 
        e = e || window.event,
        message = [
          '=== STOP! DO NOT LEAVE THIS PAGE ===',
          '',
          'Complete Your Order Within The Next 15 Minutes',
          'And Get 50% OFF.',
          '',
          'Get Your Money Making Website For',
          'Only $4.95/Month.',
          '',
          'This Offer Expires In 15 Minutes...',
          '',
          'To Get 50% Off Right Now',
          'CLICK THE BUTTON YOU SEE BELOW TO STAY:'
        ].join("\n");

      // For IE and Firefox prior to version 4
      if (e)
      {
          e.returnValue = message;
      }

      user_stayed = true;
      window.location.href = aff_link;
      return message;
    }

    function stop_popping() 
    {
        window.onbeforeunload = function(){};
        window.clearInterval(checker);
    }

    function redirect_if_stayed()
    {

      if ( ! user_stayed || redirected){
        return;
      }

      redirected = true;
      stop_popping();
      window.location.href = aff_link;

    }

  </script>
  
  <?php 

endif;

echo $template['footermeta']; ?>




<!-- Global codes start--> 
<!-- Start Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.brainhost.com/" : "http://piwik.brainhost.com/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script>
<noscript>
<p><img src="https://piwik.brainhost.com/piwik.php?idsite=1" style="border:0" alt="" /></p>
</noscript>
<!-- End Piwik Tracking Code --> 



<!--  Start Google Anylytics --> 
<script type="text/javascript">//<![CDATA[
var _gaq = _gaq || [];
_gaq.push(['_setAccount','UA-18304228-1']);
_gaq.push(['_trackPageview'],['_trackPageLoadTime']);
(function() {
   var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
//]]></script>
<!--  End Google Anylytics --> 



<?php 

$this->load->config('debug');

if (in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))): ?>

	<script type="text/javascript" src="/resources/brainhost/js/debugger.js"></script>

	<?php 

endif; ?>


</body>
</html>