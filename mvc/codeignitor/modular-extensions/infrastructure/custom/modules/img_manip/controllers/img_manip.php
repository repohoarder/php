<?php

/****

TODO/WISHLIST

	Need to figure out system() alternative - Imagick funcs not working
	Need to generate GIFs faster
		- Could create a large cache of every possible gif (yech)
		- Combine caching w/ fuzzy countdown - one gif for every ~30 secs
		- Throw on a decent cloud server
	Offer different timers
		- Include years, omit seconds, show only days, etc.
		- Different backgrounds
		- Different fonts & font options
	Create Frontend admin panel for generation
	Option to display message at 00:00:00:00 ("Sorry you missed it..")
	Convert timezone based on GeoIP of viewer (currently dependent on server)

	Perhaps a spoken-word variation based on http://bloople.net:81/num2text/ :)

*****/

date_default_timezone_set('America/New_York');
set_time_limit(900);

class Img_manip extends MX_Controller {

	/**
	 * Create a countdown timer based on a future date or number of seconds
	 * Used in email campaigns where javascript is unavailable
	 * Accepts $_GET and $_POST values:
	 * 	integer 'interval_secs' Number of seconds to count down
	 * 	string 'future' Future date to count toward
	 * 	string 'nocache' If present, will generate new image instead of using cache
	 * @return NULL
	 */
	function countdown()
	{

		$this->load->config('countdown');
		
		$max_frames       = $this->config->item('countdown_max_length_mins') * 60;

		// default countdown to a minute from now
		$now              = date('U');
		$future           = strtotime('+1 minute', $now);

		// overwrite countdown with static number of seconds if provided
		$interval_seconds = intval($this->input->get_post('interval_secs'));
		$future           = ($interval_seconds) ? strtotime('+'.$interval_seconds.' second', $now) : $future;
		
		// overwrite countdown with a date in the future if provided
		$future_date      = $this->input->get_post('future');
		$future           = ($future_date) ? strtotime($future_date) : $future;
		
		// determine number of seconds to count down
		$interval         = $future - $now;
		$interval         = ($interval > 0) ? $interval : 0;

		// filename to create for caching
		$output_file      = $this->config->item('countdown_folder').'/minimal_'.$interval.'.gif';

		$convert_seconds  = array(
			'days'  => 86400,
			'hours' => 3600,
			'mins'  => 60,
			'secs'  => 1
		);

		if ($interval > $this->config->item('countdown_max_future') || $max_frames <= 0):

			// countdown exceeds max time - fail silently with 00:00:00 timer
			$interval = 0;

		endif;

		if ( ! $this->input->get_post('nocache') && file_exists($output_file)):

			// output image if cached version is available
			return $this->_output_image(file_get_contents($output_file));
			
		endif;

		// don't want to load configs within loop below...
		$template   = $this->config->item('countdown_template');
		$margin_btm = $this->config->item('countdown_bottom_margin');
		$margin_lft = $this->config->item('countdown_left_margin');
		$gap        = $this->config->item('countdown_gap_size');
		$chr_space  = $this->config->item('countdown_char_spacing');

		// save some time by creating template object to clone in loop
		$original = new Imagick($template);

		// calculate vertical position of text
		$height   = $original->getimageheight();
		$bottom   = $height - $margin_btm;
 
		// prepare draw object to write on frames
		$draw = new ImagickDraw();
		$draw->setFillColor($this->config->item('countdown_font_color'));
		$draw->setFont($this->config->item('countdown_font_face'));
		$draw->setFontSize($this->config->item('countdown_font_size'));

		// create gif object to hold frames
		$gif = new Imagick();
		$gif->setFormat("gif");

		$frames   = 0;

		// loop and add frame for every second
		while ($interval >= 0 && $frames <= $max_frames):

			$remaining = $interval;
			$loops     = 0;

			$picin     = clone $original;

			// calculate days/hours/mins/seconds and draw in frame
			foreach ($convert_seconds as $metric => $num_secs):
				
				// pad digits to include leading zero
				$digits    = str_pad(intval($remaining / $num_secs), 2, '0', STR_PAD_LEFT);

				// calc remaining seconds for next measurement
				$remaining = ($num_secs > 1) ? $remaining % $num_secs : $remaining;
				
				// determine left margin of digits based on loops
				$left      = $margin_lft + ($gap * $loops);

				// write first digit of this measurement
				$picin->annotateImage($draw, $left, $bottom, 0, substr($digits, 0, 1));

				// write second digit of measurement
				$picin->annotateImage($draw, $left + $chr_space, $bottom, 0, substr($digits, 1, 1));

				// keep track of loops for spacing
				$loops++;

			endforeach;

			// set frame to show for 100 milliseconds and not repeat
			$picin->setImageIterations(1);
			$picin->setImageDelay(100);

			// add frame to gif
			$gif->addImage($picin);

			// memory cleanup of frames
			$picin->destroy();

			$frames++;
			$interval--;

		endwhile;
		
		// memory cleanup, kill imagick objects
		$original->destroy();
		$draw->destroy();

		// get gif contents for output
		$output = $gif->getImagesBlob();

		// save file to cache
		$gif->writeImages($output_file, TRUE);

		// more memory cleanup!
		$gif->destroy();
		
		// check if system() is usable for compression
		if ($this->_is_system_function_usable('system')):

			// compress gif filesize
			system('convert '.$output_file.' -coalesce -layers optimize '.$output_file);

		else:

			/*
			
			// Need to explore this more.. not currently working
			
			$gif = new Imagick($output_file);
			$gif->coalesceImages();
			$gif->optimizeImageLayers();
			$gif->writeImages($output_file, TRUE);

			$output = $gif->getImagesBlob();
			$gif->destroy();
			*/

		endif;

		return $this->_output_image($output);

	}

	/**
	 * Display image & prevent browser caching
	 * @param  mixed $data File contents of image
	 * @param  string $type File type of output ('png','gif','etc')
	 * @return NULL
	 */
	function _output_image($data, $type='gif')
	{

		// don't let user's browser cache image! would defeat the point
		// should move this func to a view
		header('Content-Type: image/'.$type);
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');

		echo $data;

	}

	/**
	 * Checks if system() or exec() is usable
	 * Can't use is_callable like normal because of nature of these funcs
	 * Adapted from http://stackoverflow.com/a/4033920
	 * @param  string  $func Which function to check ('system'|'exec')
	 * @return boolean       Whether given function is callable
	 */
	function _is_system_function_usable($func) {

		if (ini_get('safe_mode')):
			return FALSE;
		endif;

		$disabled = ini_get('disable_functions');

		if ( ! $disabled):
			return TRUE;
		endif;

		$disabled = explode(',', $disabled);
		$disabled = array_map('trim', $disabled);

		return ( ! is_array($disabled) || ! in_array($func, $disabled));

	}


}