<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// funnel type (order or client)
$config['_type']	= 'order';

$config['services'] = array(
		'lightning_fast_servers' => array(
			'img' => 'img/icon-lightning.jpg',
			'content' => "Lightning Fast Servers mean a faster website, faster processors, more RAM and less customers per server.  Experience improved reliability and greater uptime. Go faster!",
			'lang_line_content' => 'lightning_fast_content'
		),
	'private_ssl' => array(
			'img' => 'img/icon-ssl.jpg',
			'content' => "Protect your website and your visitor's sensitive personal information with a secure data transmission, allowing you to operate an online store and ensuring higher search engine ranking. Provide customers with a safer online experience with our exclusive RapidSSL Certificate. (To use a Private SSL you must purchase a Dedicated IP.)",
			'lang_line_content' => 'private_ssl'
		),
	'dedicated_ip' => array(
			'img' => 'img/icon-dedicated.jpg',
			'content' => "A Dedicated IP means that you are the only one on the Internet using that unique IP number and is necessary for most traffic heavy or ecommerce enabled sites. We highly recommend that any business oriented website purchase a Dedicated IP.",
			'lang_line_content' => 'dedicated_ip'
		),
	'spam_assassin' => array(
			'img' => 'img/icon-assassin.jpg',
			'content' => "Spam Assassin is the top-rated spam protection service. It is an automated email management filter used to identify unsolicited bulk emails and either file them as spam or delete them for you. It is the easiest way to stay spam and virus free.",
			'lang_line_content' => 'spam_assassin'
		),
	'google_local_listing' => array(
			'img' => 'img/img-se-local.jpg',
			'content' => "Reach desktop and mobile search users quickly and easily",
			'lang_line_content' => 'directory_listing'
		),
	'search_engine_submission' => array(
			'img' => 'img/img-se-submitter.jpg',
			'content' => "Make each major search engine aware of your web presence",
			'lang_line_content' => 'search_engine_submission'
		),
	'directory_listing' => array(
			'img' => 'img/img-se-directory.jpg',
			'content' => "Increase your reach with a listing on our site directory",
			'lang_line_content' => 'google_local_listing'
		)
);