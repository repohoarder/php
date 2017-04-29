<?php

// grab partner ID (for hacks)
$partner 	= $this->session->userdata('partner');

// partners who are really only affiliates
$affiliate_partners 	= array(
	157,
	173,
	175,
	199,
	206,
	200,
	201,
	207,
	205,
	208,
	223,
	225,
	239,
	251,
	169
);

if ($setup_incomplete && $step_slug != NULL):

	echo Modules::run('steps/signup/index',$step_slug);

else:

	echo Modules::run('statistics/sales/index');

	echo Modules::run('statistics/visitors/index'); 

	// HACK to not show these sections to "affiliates"
	if ( ! in_array($partner['id'],$affiliate_partners)):

		echo Modules::run('statistics/epc/index'); 
	
		echo Modules::run('statistics/estimatedrevenue/index');

	endif;

endif;

// show sales statistics
//Modules::run('manage/website/index');

// show visitor statistics
//Modules::run('support/support/index');

// show last payout
//echo Modules::run('financial/statement/index');

// show estimated recurring revenue
