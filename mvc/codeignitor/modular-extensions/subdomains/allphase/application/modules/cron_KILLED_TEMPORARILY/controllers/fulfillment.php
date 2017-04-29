<?php

class Fulfillment extends MX_Controller
{
	/**
	 * The array of partner information
	 * @var int
	 */
	private $_response   = array(
			'success' => 0,
			'errors'  => array(),
			'data'    => array()
		);

	public function __construct()
	{
		parent::__construct();
	}

	public function index($error=FALSE)
	{
		exit();
	}
	/**
	 * this function will get new packages within the last 1 days or whatever the config is set to
	 * @return type
	 */
	public function newpackages(){
		$resp = $this->platform->post(
			'fulfillment/cron/package/get', array()
		);

		$output = json_encode($resp);

		echo $output;

	}
	/**
	 * this function will get paid packages within the last 15 days or whatever teh config is set to
	 * @return type
	 */
	public function paidpackages(){
		
		$resp = $this->platform->post(
			'fulfillment/cron/package/updatepaid', array()
		);

		$output = json_encode($resp);

		echo $output;
	}
	/**
	 * this function will grab all deactivated packages mark them as inactive in allphase and insert into revoke queue
	 * @return type
	 */
	public function deactivatedpackages(){
		
		
		$resp = $this->platform->post(
			'fulfillment/cron/package/deactivatedpackages', array()
		);

		$output = json_encode($resp);

		echo $output;
	}
	/**
	 * this function will revoke packages that have been deactivated
	 */
	public function revokepackages(){
		
		// get deactivated packids needing revoked
		$resp = $this->platform->post(
			'fulfillment/cron/package/revokedpackages', array()
		);
		
		if($resp['success']) :
			
			// loop thru each record to be revoked
			foreach($resp['data'] as $record) :
			
			// revoke call
			$revoke = $this->platform->post('revoke/ended/item/service/'.$record['pack_id']."/", array());
		
			// set record as revoked in database
			if($revoke['success']) :
				$this->platform->post('fulfillment/cron/package/markrevoked', array('pack_id'=>$record['pack_id']));
			endif;
			
			endforeach;
		endif;
	}
	
	/**
	 * This function will fulfill all packages that are paid,active,and not already fulfilled
	 * 
	 */
	public function fulfillorders(){
		// get deactivated packids needing revoked
		$packages = $this->platform->post(
			'fulfillment/cron/package/getpaidpackages', array()
		);
		//var_dump($packages);
		if($packages['success']) :
			
			// loop thru each record to be revoked
			foreach($packages['data'] as $record) :
			
			// revoke call
			$fulfill = $this->platform->post('fulfillment/fulfill/item/service/'.$record['pack_id']."/", array());
			var_dump($fulfill);
			// set record as revoked in database
			if($fulfill['success']) :
				
				$deac = $this->platform->post('fulfillment/cron/package/markfulfilled', array('pack_id'=>$record['pack_id']));
				var_dump($deac);
			else: 
				//var_dump($fulfill);
			endif;
			
			endforeach;
		endif;
	}
}

