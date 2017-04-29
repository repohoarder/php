<?php

date_default_timezone_set('America/New_York');
set_time_limit(900);

/**
 *
 * Travman's badass email countdown timer generation thing
 * 
 */

class Img_manip_old extends MX_Controller {

	/*
	Need to figure out system() alternative - built-in funcs not working
	Need to generate GIFs faster
		- Could create a large cache of every possible gif (yech)
		- Could put on decently powerful cloud server
		- Could make countdown fuzzy - one gif for every ~30 secs
	Offer different timers
		- Some only years, days, hours, etc.
		- Different backgrounds
		- Different fonts & font options
	Option to display message at 00:00:00:00 ("Sorry you missed it..")
	Should convert timezone based on GeoIP of viewer
	Perhaps a spoken-word variation based on http://bloople.net:81/num2text/ :)
	*/


	function countdown()
	{

		$this->load->config('countdown');
		
		$max_frames       = $this->config->item('countdown_max_length_mins') * 60;
		
		$output_file      = $this->config->item('countdown_folder').'/minimal_'.$interval.'.gif';

		// default countdown to a minute from now
		$now              = date('U');
		$future           = strtotime('+1 minute', $now);

		// overwrite countdown with static number of seconds if provided
		$interval_seconds = intval($this->input->get_post('interval_secs'));
		$future           = ($interval_seconds) ? strtotime('+'.$interval_seconds.' second', $now) : future;
		
		// overwrite countdown with a date in the future if provided
		$future_date      = $this->input->get_post('future');
		$future           = ($future_date) ? strtotime($future_date) : $future;
		
		// determine number of seconds to count down
		$interval         = $future - $now;
		$interval         = ($interval > 0) ? $interval : 0;

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
		$font_color = $this->config->item('countdown_font_color');
		$font_face  = $this->config->item('countdown_font_face');
		$font_size  = $this->config->item('countdown_font_size');
		$gap        = $this->config->item('countdown_gap_size');
		$chr_space  = $this->config->item('countdown_char_spacing');

		
		// save some time by creating template object to clone in loop
		$original = new Imagick($template);
		$height   = $picin->getimageheight();
		$bottom   = $height - $margin_btm;
		
		$gif = new Imagick();
		$gif->setFormat("gif");

		$frames   = 0;

		while ($interval >= 0 && $frames <= $max_frames):

			$remaining = $interval;
			$loops     = 0;

			$picin     = clone $original;
			
			$draw = new ImagickDraw();
			$draw->setFillColor($font_color);
			$draw->setFont($font_face);
			$draw->setFontSize($font_size);

			foreach ($convert_seconds as $metric => $num_secs):
				
				$str       = str_pad(intval($remaining / $num_secs), 2, '0', STR_PAD_LEFT);
				$remaining = ($num_secs > 1) ? $remaining % $num_secs : $remaining;
				
				$left      = $margin_lft + ($gap * $loops);

				$picin->annotateImage($draw, $left, $bottom, 0, substr($str, 0, 1));
				$picin->annotateImage($draw, $left + $chr_space, $bottom, 0, substr($str, 1, 1));

				$loops++;

			endforeach;

			$picin->setImageIterations(1);
			$picin->setImageDelay(100);
			$gif->addImage($picin);

			$picin->destroy();

			$frames++;
			$interval--;

		endwhile;
		
		$original->destroy();

		$output = $gif->getImagesBlob();

		$gif->writeImages($output_file, TRUE);

		$gif->destroy();
	
		system('convert '.$output_file.' -coalesce -layers optimize '.$output_file);

		/*
		$gif = new Imagick($output_file);
		$gif->coalesceImages();
		$gif->optimizeImageLayers();
		$gif->writeImages($output_file, TRUE);

		$output = $gif->getImagesBlob();
		$gif->destroy();
		*/

		return $this->_output_image($output);

	}


	function localized($ip = FALSE)
	{

		$ip          = ($ip) ? $ip : $_SERVER['REMOTE_ADDR'];

		$resp        = $this->platform->post('geoip/get_record',
			array(
				'ip_address' => $ip
			)
		);

		if ( ! $resp['success']):

			echo 'Aborted; could not localize';
			return;

		endif;

		$location    = $resp['data']['record']['city'];
		
		$folder      = CUSTOM_PATH.'modules/img_manip/assets/img/localized';
		$output_file = $folder.'/local.png';
		
		
		$font        = CUSTOM_PATH.'modules/img_manip/assets/fonts/arial.ttf';
		$fsize       = 40;
		$fcolor      = '#000000';


		$image = new Imagick();
		$image->newImage(300, 100, new ImagickPixel('transparent'));
		$image->setImageFormat("png");

		$height = $image->getimageheight();


		$draw  = new ImagickDraw();
		$draw->setFont($font);
		$draw->setFillColor($fcolor);		
		$draw->setFontSize($fsize);

		$image->annotateImage($draw, 10, 60, 0, $location);

		$image->writeImage($output_file);

		$output = $image->getImagesBlob();
		$draw->destroy();
		
		return $this->_output_image($output, 'png');



	}



	function cheque()
	{


		$template     = CUSTOM_PATH.'modules/img_manip/assets/img/check.png';
		$folder       = CUSTOM_PATH.'modules/img_manip/assets/img/cheques';
		
		$font         = CUSTOM_PATH.'modules/img_manip/assets/fonts/5thgradecursive-2-italic.ttf';
		$fsize        = 30;
		$fcolor       = '#FF0000';
		
		$lft_margin   = 80;		
		$btm_margin   = 135;

		$name         = $this->input->get_post('name');





		$output_file  = $folder.'/'.strtolower(str_replace('_','',$name)).'.gif';


		$picin = new Imagick($template);

		$height = $picin->getimageheight();
		$bottom = $height - $btm_margin;

		$draw  = new ImagickDraw();
		$draw->setFont($font);
		



		$draw->setFillColor('#000000');
		$draw->setFontSize(20);
		$picin->annotateImage($draw, 300, $height - 38, 0, $this->input->get_post('signature'));
		$picin->annotateImage($draw, $lft_margin - 50, $bottom + 27, 0, 'Eighteen thousand twenty three');
		$picin->annotateImage($draw, 50, $height - 38, 0, 'Sexual Favors');

		$draw->setFontSize(14);
		$picin->annotateImage($draw, 300, $bottom-38, 0, date('F d'));
		$picin->annotateImage($draw, 390, $bottom-38, 0, date('y'));


		$draw->setFillColor($fcolor);		
		$draw->setFontSize($fsize);
		$picin->annotateImage($draw, $lft_margin, $bottom, 0, $name);

		$draw->setFontSize(14);
		$picin->annotateImage($draw, $lft_margin+350, $bottom - 4, 0, '18,023');


		$picin->writeImage($output_file);

		$output = $picin->getImagesBlob();
		$draw->destroy();
		
		return $this->_output_image($output);



	}

	function _output_image($data, $type='gif')
	{

		header('Content-Type: image/'.$type);
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');

		echo $data;

	}

	/*
	// function can check if exec() or system() is available
	function isAvailable($func) {
	    if (ini_get('safe_mode')) return false;
	    $disabled = ini_get('disable_functions');
	    if ($disabled) {
	        $disabled = explode(',', $disabled);
	        $disabled = array_map('trim', $disabled);
	        return !in_array($func, $disabled);
	    }
	    return true;
	}
	 */

}