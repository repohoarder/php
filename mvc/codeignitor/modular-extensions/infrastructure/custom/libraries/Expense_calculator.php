<?php

class Expense_calculator {

	protected $_ci;

	function __construct()
	{

		$this->_ci = &get_instance();

	}
	
	public function calculatetype($type,$params,$amount,$countitems) {
		
		// determine what to calculate
		
		switch($type) :
			case 1 : case 5 : case 6 : case 7 :
			return $this->_multiPly($params,$amount,$countitems);
			break;
			case 2 :case 3 :
				return $this->_multiAdd($params,$amount,$countitems);
			break;
			case 4 :
				// need to calculate the support cost
		endswitch;
	}
	private function _multiPly($params,$amount,$countitems){
		
		$type = $params['type'];
		if($type === "%"):
			return   round(($params['amount'] * .01 ) * $amount, 2 );
		else:
			return $params['amount'] * $countitems;	
		endif;	
		
		
	}
	private function _multiAdd($params,$amount){
		$type = $params['type'];
		if($type === "%"):
			return   round(($params['amount'] * .01 ) * $amount, 2 ) + $amount;
		else:
			return ($params['amount'] * $countitems ) + $amount;	
		endif;	
	}
}
