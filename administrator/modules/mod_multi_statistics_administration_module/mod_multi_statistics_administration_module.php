<?php
/**
* iMaQma Joomla Statistics Administration Module
* www.imaqma.com
*
* @package iMaQma Joomla Statistics Administration Module
* @copyright (C) 2006-2010 Components Lab, Lda.
* @license GNU/GPL
*
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.pagination');

$dimension = $params->get('dimension', 'all');

$db	=& JFactory::getDBO();

// get registered users (total)
$query = "SELECT COUNT(*) AS total FROM `#__users`";
$db->setQuery( $query );
$registered_users = $db->loadResult();

// get current visitors
$query = "SELECT COUNT(*) AS total FROM `#__session` WHERE `client_id`=0";
$db->setQuery( $query );
$current_visitors = $db->loadResult();

// get registered users (current month)
$query = "SELECT COUNT(*) AS total FROM `#__users` WHERE YEAR(`registerDate`)='".date("Y")."' AND MONTH(`registerDate`)='".date("m")."'";
$db->setQuery( $query );
$reg_this_month = $db->loadResult();

// get registered users (current year)
$query = "SELECT COUNT(*) AS total FROM `#__users` WHERE YEAR(`registerDate`)='".date("Y")."'";
$db->setQuery( $query );
$reg_this_year = $db->loadResult();

// get registered users (current month)
$query = "SELECT COUNT(*) AS total FROM `#__users` WHERE MONTH(`registerDate`)='".(date("m")-1)."' AND YEAR(`registerDate`)='".date("Y")."'";
$db->setQuery( $query );
$reg_last_month = $db->loadResult();

// get registered users (current year)
$query = "SELECT COUNT(*) AS total FROM `#__users` WHERE YEAR(`registerDate`)='".(date("Y")-1)."'";
$db->setQuery( $query );
$reg_last_year = $db->loadResult();

// get the existing store (digistore)
$query = "SELECT COUNT(*) FROM `#__components` WHERE `option`='com_digistore'";
$db->setQuery( $query );
$digistore = $db->loadResult();

// get the existing store (redshop)
$query = "SELECT COUNT(*) FROM `#__components` WHERE `option`='com_redshop'";
$db->setQuery( $query );
$redshop = $db->loadResult();

// get the existing store (virtuemart)
$query = "SELECT COUNT(*) FROM `#__components` WHERE `option`='com_virtuemart'";
$db->setQuery( $query );
$virtuemart = $db->loadResult();

$store = ( $digistore ? 'digistore' : ( $virtuemart ? 'virtuemart' : 'redshop' ) );

$orders_today_digistore = 0;
$orders_this_month_digistore = 0;
$orders_last_month_digistore = 0;
$orders_this_year_digistore = 0;
$orders_last_year_digistore = 0;
$orders_today_redstore = 0;
$orders_this_month_redstore = 0;
$orders_last_month_redstore = 0;
$orders_this_year_redstore = 0;
$orders_last_year_redstore = 0;
$orders_today_vm = 0;
$orders_this_month_vm = 0;
$orders_last_month_vm = 0;
$orders_this_year_vm = 0;
$orders_last_year_vm = 0;

// get orders (today) - digistore
if( $digistore ) {
	$query = "SELECT COUNT(*) AS total FROM `#__digistore_orders` WHERE YEAR(FROM_UNIXTIME(`order_date`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`order_date`))='".date("m")."' AND DAY(FROM_UNIXTIME(`order_date`))='".date("d")."' AND `status`='Active'";
	$db->setQuery( $query );
	$orders_today_digistore = $db->loadResult();
	
	// get orders (current month) - digistore
	$query = "SELECT COUNT(*) AS total FROM `#__digistore_orders` WHERE YEAR(FROM_UNIXTIME(`order_date`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`order_date`))='".date("m")."' AND `status`='Active'";
	$db->setQuery( $query );
	$orders_this_month_digistore = $db->loadResult();
	
	// get orders (last month) - digistore
	$query = "SELECT COUNT(*) AS total FROM `#__digistore_orders` WHERE YEAR(FROM_UNIXTIME(`order_date`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`order_date`))='".(date("m")-1)."' AND `status`='Active'";
	$db->setQuery( $query );
	$orders_last_month_digistore = $db->loadResult();
	
	// get orders (current year) - digistore
	$query = "SELECT COUNT(*) AS total FROM `#__digistore_orders` WHERE YEAR(FROM_UNIXTIME(`order_date`))='".date("Y")."' AND `status`='Active'";
	$db->setQuery( $query );
	$orders_this_year_digistore = $db->loadResult();
	
	// get orders (last year) - digistore
	$query = "SELECT COUNT(*) AS total FROM `#__digistore_orders` WHERE YEAR(FROM_UNIXTIME(`order_date`))='".(date("Y")-1)."' AND `status`='Active'";
	$db->setQuery( $query );
	$orders_last_year_digistore = $db->loadResult();
}

// get orders (today) - redstore
if( $redshop ) {
	$query = "SELECT COUNT(*) AS total FROM `#__redshop_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".date("m")."' AND DAY(FROM_UNIXTIME(`cdate`))='".date("d")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_today_redstore = $db->loadResult();
	
	// get orders (current month) - redstore
	$query = "SELECT COUNT(*) AS total FROM `#__redshop_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".date("m")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_this_month_redstore = $db->loadResult();
	
	// get orders (last month) - redstore
	$query = "SELECT COUNT(*) AS total FROM `#__redshop_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".(date("m")-1)."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_last_month_redstore = $db->loadResult();
	
	// get orders (current year) - redstore
	$query = "SELECT COUNT(*) AS total FROM `#__redshop_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_this_year_redstore = $db->loadResult();
	
	// get orders (last year) - redstore
	$query = "SELECT COUNT(*) AS total FROM `#__redshop_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".(date("Y")-1)."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_last_year_redstore = $db->loadResult();
}

// get orders (today) - virtuemart
if( $virtuemart ) {
	$query = "SELECT COUNT(*) AS total FROM `#__vm_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".date("m")."' AND DAY(FROM_UNIXTIME(`cdate`))='".date("d")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_today_vm = $db->loadResult();
	
	// get orders (current month) - virtuemart
	$query = "SELECT COUNT(*) AS total FROM `#__vm_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".date("m")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_this_month_vm = $db->loadResult();
	
	// get orders (last month) - virtuemart
	$query = "SELECT COUNT(*) AS total FROM `#__vm_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND MONTH(FROM_UNIXTIME(`cdate`))='".(date("m")-1)."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_last_month_vm = $db->loadResult();
	
	// get orders (current year) - virtuemart
	$query = "SELECT COUNT(*) AS total FROM `#__vm_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".date("Y")."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_this_year_vm = $db->loadResult();
	
	// get orders (last year) - virtuemart
	$query = "SELECT COUNT(*) AS total FROM `#__vm_orders` WHERE YEAR(FROM_UNIXTIME(`cdate`))='".(date("Y")-1)."' AND `order_status`='C'";
	$db->setQuery( $query );
	$orders_last_year_vm = $db->loadResult();
}

$orders_today = ( $orders_today_digistore + $orders_today_redstore + $orders_today_vm );
$orders_this_month = ( $orders_this_month_digistore + $orders_this_month_redstore + $orders_this_month_vm );
$orders_last_month = ( $orders_last_month_digistore + $orders_last_month_redstore + $orders_last_month_vm );
$orders_this_year = ( $orders_this_year_digistore + $orders_this_year_redstore + $orders_this_year_vm );
$orders_last_year = ( $orders_last_year_digistore + $orders_last_year_redstore + $orders_last_year_vm );

// check if helpdesk is installed
$query = "SELECT COUNT(*) FROM `#__components` WHERE `option`='com_maqmahelpdesk'";
$db->setQuery( $query );
$helpdesk = $db->loadResult();

// get opened tickets
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` AS t INNER JOIN `#__support_status` AS s ON s.`id`=t.`id_status` WHERE s.`status_group`='O'";
$db->setQuery( $query );
$tickets_pending = $db->loadResult();

// get created tickets (today)
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` WHERE YEAR(`date`)='".date("Y")."' AND MONTH(`date`)='".date("m")."' AND DAY(`date`)='".date("d")."'";
$db->setQuery( $query );
$tickets_today = $db->loadResult();

// get created tickets (this month)
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` WHERE YEAR(`date`)='".date("Y")."' AND MONTH(`date`)='".date("m")."'";
$db->setQuery( $query );
$tickets_this_month = $db->loadResult();

// get created tickets (this year)
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` WHERE YEAR(`date`)='".date("Y")."'";
$db->setQuery( $query );
$tickets_this_year = $db->loadResult();

// get registered users (current month)
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` WHERE MONTH(`date`)='".(date("m")-1)."'";
$db->setQuery( $query );
$tickets_last_month = $db->loadResult();

// get registered users (current year)
$query = "SELECT COUNT(*) AS total FROM `#__support_ticket` WHERE YEAR(`date`)='".(date("Y")-1)."'";
$db->setQuery( $query );
$tickets_last_year = $db->loadResult();

require( dirname( __FILE__ ).DS.'tmpl'.DS.'default.php' );
