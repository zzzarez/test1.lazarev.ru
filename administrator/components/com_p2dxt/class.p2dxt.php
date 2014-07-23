<?php 
/**
* P2DXT for Joomla!
* @Copyright ((c) 2008 - 2010 JoomlaXT
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ http://www.joomlaxt.com
* @version 1.00.14
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
<?php 

class p2dxtstd extends JTable
{
	var $id				= null;
	var $token			= null;
	var $business		= null;
	var $locale			= null;
	var $testtoken		= null;
	var $testbusiness	= null;
	var $errortext		= null;
	var $thanktext		= null;
	var $button			= null;
	var $dbutton		= null;
	var $version		= null;
	var $popupx			= null;
	var $popupy			= null;

	function __construct( &$db )
	{
		parent::__construct( '#__p2dxt', 'id', $db );
	}
}

class p2dxt_filesstd extends JTable
{
	var $id				= null;
	var $introtext		= null;
	var $filename		= null;
	var $itemname		= null;
	var $amount			= null;
	var $tax			= null;
	var $currency		= null;
	var $testmode		= null;
	var $url 			= null;
	var $maxdown		= null;
	var $shipping		= null;
	var $productid		= null;
	var $limitdown		= null;
	var $donation 		= null;
	var $minamount 		= null;
	var $taxcalc		= null;
	var $meta			= null;
	
	function __construct( &$db )
	{
		parent::__construct( '#__p2dxt_files', 'id', $db );
	}
}
class p2dxt_txstd extends JTable
{
	var $id = null;
	var $payment_date = null;
	var $txn_type = null;
	var $last_name = null;
	var $residence_country = null;
	var $business = null;
	var $payment_type = null;
	var $payer_status = null;
	var $tax = null;
	var $payer_email = null;
	var $txn_id = null;
	var $quantity = null;
	var $receiver_email = null;
	var $first_name = null;
	var $payer_id = null;
	var $receiver_id = null;
	var $item_number = null;
	var $payment_status = null;
	var $mc_fee = null;
	var $mc_gross = null;
	var $dwnl = null;
	var $userid = null;
	var $testmode = null;

	function __construct(&$db)
	{
		parent::__construct( '#__p2dxt_tx', 'id', $db );
	}
}



?>