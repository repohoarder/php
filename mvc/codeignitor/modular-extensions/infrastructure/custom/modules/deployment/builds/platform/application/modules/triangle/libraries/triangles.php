<?php

class Triangles
{
	var $CI;
	var $_api;
	var $_url;
	var $_username;
	var $_password;

	public function __construct($params)
	{
		$this->CI 	= &get_instance();

		// load config
		$this->CI->load->config('triangle');

		// load config vars
		$config 		= $this->CI->config->item('application');

		$params 		= array('application' => 'elevatedigital');

		// set global variables
		$this->_url 		= $config[$params['application']]['url'];
		$this->_username 	= $config[$params['application']]['username'];
		$this->_password 	= $config[$params['application']]['password'];

	}

	public function prospect($data=array())
	{
		// initialize variables
		$this->_api 	= 'billing_ws.asmx/CreateProspect';

		// create POST array
		$post 			= array(
			'productTypeID'				=> @(int)$data['product_type_id'],
			'productTypeIDSpecified'	=> @(string)$data['product_type_id_specified'],
			'firstName'					=> @(string)$data['first'],
			'lastName'					=> @(string)$data['last'],
			'address1'					=> @(string)$data['address'],
			'address2'					=> @(string)$data['address2'],
			'city'						=> @(string)$data['city'],
			'state'						=> @(string)$data['state'],
			'zip'						=> @(string)$data['zip'],
			'country'					=> @(string)$data['country'],
			'phone'						=> @(string)$data['phone'],
			'email'						=> @(string)$data['email'],
			'ip'						=> @(string)$data['ip'],
			'affiliate'					=> @(string)$data['affiliate_id'],
			'subAffiliate'				=> @(string)$data['sub_affiliate_id'],
			'internalID'				=> @(string)$data['internal_id'],
			'customField1'				=> @(string)$data['custom_field_1'],
			'customField2'				=> @(string)$data['custom_field_2'],
			'customField3'				=> @(string)$data['custom_field_3'],
			'customField4'				=> @(string)$data['custom_field_4'],
			'customField5'				=> @(string)$data['custom_field_5']
		);

		// post data
		return $this->_post($post);
	}

	public function subscription($data=array())
	{
		// initialize variables
		$this->_api 	= 'billing_ws.asmx/CreateSubscription';

		// create POST array
		$post 			= array(

		);

		// post data
		return $this->_post($post);
	}

	public function charge($data=array())
	{
		// initialize variables
		$this->_api 	= 'billing_ws.asmx/Charge';

		// create POST array
		$post 			= array(
			'amount'					=> @(float)$data['amount'],
			'shipping'					=> @(float)$data['shipping'],
			'shippingSpecified'			=> @(string)$data['shipping_specified'],
			'productTypeID'				=> @(int)$data['product_type_id'],
			'productTypeIDSpecified'	=> @(string)$data['product_type_id_specified'],
			'productID'					=> @(int)$data['product_id'],
			'productIDSpecified'		=> @(string)$data['product_id_specified'],
			'campaignID'				=> @(int)$data['campaign_id'],
			'campaignIDSpecified'		=> @(string)$data['campaign_id_specified'],
			'firstName'					=> @(string)$data['first'],
			'lastName'					=> @(string)$data['last'],
			'address1' 					=> @(string)$data['address'],
			'address2' 					=> @(string)$data['address2'],
			'city' 						=> @(string)$data['city'],
			'state' 					=> @(string)$data['state'],
			'zip' 						=> @(string)$data['zip'],
			'country' 					=> @(string)$data['country'],
			'phone' 					=> @(string)$data['phone'],
			'email' 					=> @(string)$data['email'],
			'ip' 						=> @(string)$data['ip'],
			'affiliate' 				=> @(string)$data['affiliate_id'],
			'subAffiliate' 				=> @(string)$data['sub_affiliate_id'],
			'internalID' 				=> @(string)$data['internal_id'],
			'prospectID'				=> @(int)$data['prospect_id'],
			'prospectIDSpecified'		=> @(string)$data['prospect_id_specified'],
			'paymentType' 				=> @(int)$data['payment_type'],
			'paymentTypeSpecified' 		=> @(string)$data['payment_type_specified'],
			'creditCard' 				=> @(string)$data['credit_card'],
			'cvv' 						=> @(string)$data['cvv'],
			'expMonth' 					=> @(int)$data['exp_month'],
			'expYear' 					=> @(int)$data['exp_year'],
			'description' 				=> @(string)$data['description'],
			'customField1'				=> @(string)$da 
-r�H ps� zr�H p(�) s?  
z}- (m {, ,{$ -s }$ s }# (n { o% {- *  0 
  � { o� 
;�   {' 8�   o� YE      �   8�   t�  o� 9�   o� +{	oZ o� {) {	 (s  ,Ro� o3  
-!~A  
o� 
-7~A  
o� o� 
+#o� o� 
-o� o� o� 
X	oL ?x���o� +t�  o= +o� :���*  0    B  { o� 
-{' o 
* 0    B  { o� 
-{' o	 
*^{' {) 3{ ** 0 ~       {  { (l s } { {) o ( o { o	 { {& o { o ,{ o *{ o *  0 �  � }( {( o� 	YE   _   f   �   �   D     4  4       D     �   �   8�   t�  o� 
-(rH p�  rHH p(�) �(�) s?  
z(n *o� +(n o� -�*(o *{( u�  { o� o� {" {# o &{, 9�   {) o� o� o� {# o� o! *{ {" o *{ {" o *{ {" o  *rFI p��  {( o� �8  oJ  
�(�) s?  
z*0 �  � { o� {( u�  
o� 8�   oZ o� o� {/ (s  ,1{0 (s  ,
o� +R{1 (s  ,Co� +:{. (s  ,+{ o� o3  
,o� +~A  
o� o� XoL ?Z���{ o� o� {$ 	o (p { {$ o o� +(n o� -�}( { {$ o" &{, ,a{) o� o� o� {$ o� o� {$ o ,+{) {$ o" o� o, o� 		o� &{ o� &* 0 �  � o� 
+{oZ }( o� {. (s  -U{ o�