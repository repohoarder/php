<?php

$url = 'http' . ( ! empty($_SERVER['HTTPS']) ? "s" : '') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

echo json_encode(
	array(
		'success' => 0,
		'error'   => array(
			'type'    => 'CI DB Error',
			'heading' => $heading,
			'message' => $message,
			'url'     => $url
		),
		'data'    => array()
	)
);

exit();