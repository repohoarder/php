<?php 

$this->lang->load('header'); 

$first_name = 'Travis';

?>

<!--Header-->
<div class="header"> 
	<!--User Profile-->
	<div class="profile">
		 <div class="avatar"><img src="/resources/whitedream/assets/images/avatar.jpg" alt=""></div>
		 <div class="info-prof">
			<?php echo $this->lang->line('header_welcome', '<strong>'.$first_name.'</strong>'); ?>
		 </div>
		 
		 <?php /*
		 <div class="profile_link"><a href="#"><span class="setting-icon"></span></a></div>
		 */ ?>
		 
	</div>
	<!--User Profile END-->
	
   <?php /*
   <div class="search">
	 <form>
	  <input type="text" id="testinput" placeholder="Search">
	 </form>   
   </div>
   */ ?>
   
   <div class="buttons-head">
	  <?php /*
	  <div class="button-profile-2 view_menu"><a href="#menu"><span class="menu-icon"></span></a></div>
	  <div class="button-profile"><a href="#" data-reveal-id="myModal-new"><span class="new-icon"></span></a></div>
	  <div class="button-profile"><a href="#" data-reveal-id="myModal"><span class="chat-icon" ></span><span class="notice">3</span></a></div>
	  <div class="button-profile"><a href="#"><span class="event-icon"></span><span class="notice">3</span></a></div>
	  */ ?>
	  <div class="button-profile-2"><a href="/index.html"><span class="exit-icon"></span><span class="desc">Logout</span></a></div>
   </div>
   
</div>
<!--Header END-->

<!--SpeedBar-->
<div class="speedbar">

	<div class="bar-online">
	
		<?php /* ?>
		<strong>Online:</strong> June 13, 2012 - 25 User
		<?php */ ?>
		
	</div>
		
	<ul class="speed-info">
	
		<?php /* ?> 
		<li>Order: <span>23</span></li>
		<li>New customer: <span>117</span></li>
		<li>Repeat customer:  <span>15</span></li>
		<?php */ ?>
		
	</ul>
	
	<div class="speed-info-2">
		
		<?php /* 
		Total: <span>$ 15,705</span>
		*/ ?>
		
	</div>
	
	
</div>
<div class="speedbar-shadow"></div>
<!--SpeedBar END-->