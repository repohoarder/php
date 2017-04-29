<?php

// 60,000 centiseconds in 10 minutes

$sess = $this->session->all_userdata();

$now  = microtime(true);

if ( ! isset($sess['timer_start'])):
	$this->session->set_userdata('timer_start', $now);
endif;

$timer_start = $this->session->userdata('timer_start');

$total_secs  = ($timer_start + (10 * 60)) - $now;

$total_centiseconds_remaining = round($total_secs * 100);


if ($total_centiseconds_remaining <= 0 && $_SERVER['REMOTE_ADDR'] == '127.0.0.1'):

	$total_centiseconds_remaining = rand(0,60000);

endif;


if ($total_centiseconds_remaining > 0): 
	
	$mins_no_pad           = floor($total_centiseconds_remaining / 6000);
	$minutes_remaining     = str_pad($mins_no_pad,2,'0',STR_PAD_LEFT);

	$seconds_remaining     = str_pad(floor(($total_centiseconds_remaining%6000)/100),2,'0',STR_PAD_LEFT);

	$deciseconds_remaining = str_pad(($total_centiseconds_remaining%6000)%100,2,'0',STR_PAD_LEFT);

	# Container for Javascript domain reservation countdown ?>

	<div id="the_final_countdown">

		<h3>Your domain <?php echo $domain; ?> is being reserved at <?php echo $partner_data['website']['company_name']; ?> for the next <span class="remaining_minutes"><?php echo $mins_no_pad; ?></span> minutes.</h3>
		<div class="time" id="time_remaining">
			<span class="remaining_minutes"><?php echo $minutes_remaining; ?></span>:<span class="remaining_seconds"><?php echo $seconds_remaining; ?></span>:<span class="remaining_deciseconds"><?php echo $deciseconds_remaining; ?></span>
		</div>
		<p>If you don't complete your order within the next <span class="remaining_minutes"><?php echo $mins_no_pad; ?></span> minutes, <br/> your domain will be released at <?php echo $partner_data['website']['company_name']; ?> and could be registered by someone else.  </p>

	</div>

<?php

$total_deciseconds_remaining = floor($total_centiseconds_remaining / 10);

endif; ?>

<input id="total_deciseconds" type="hidden" value="<?php echo $total_deciseconds_remaining;?>" name="total_deciseconds"/>