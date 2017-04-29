<?php

// location of countdown template to draw on
$config['countdown_template']        = APPPATH.'modules/img_manip/assets/img/countdown_minimal.png';

// countdown cache folder location
$config['countdown_folder']          = APPPATH.'modules/img_manip/assets/img/countdowns';

// countdown font settings
$config['countdown_font_face']       = APPPATH.'modules/img_manip/assets/fonts/arial.ttf';
$config['countdown_font_size']       = 80;
$config['countdown_font_color']      = '#000000';

// countdown text position
$config['countdown_left_margin']     = 130;
$config['countdown_bottom_margin']   = 190;
$config['countdown_gap_size']        = 140;
$config['countdown_char_spacing']    = 55;

// limiting number of frames and maximum future date
$config['countdown_max_length_mins'] = 5;
$config['countdown_max_future']      = 8639999;