<?php

/**
 * Componet for dispaying statistics about VirtueMart trades.
 * Can display many types of statistics and customized them by
 * number of parameters.  
 *
 * @version		$Id$
 * @package     ArtioVMReports
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved. 
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Model class of statistic. Contais all SQL queries for all stistic types.
 * 
 * @author pedu
 *
 */
class VmreportsModelVmreports extends JModel
{
	/**
	 * stores data loaded from database
	 * 
	 * @var array
	 */
	public $_data = null;
	
	/**
	 * start date of search
	 * 
	 * @var string
	 */
	public $start_date = null;
	
	/**
	 * end date of search
	 * 
	 * @var string
	 */
	public $end_date = null;
	
	/**
	 * time interval which sets the GOUP BY clause of SQL query
	 * 
	 * @var string
	 */
	public $time_interval = 'day';
	
	/**
	 * stores generated time intervals between start_date and end_date 
	 * 
	 * @var array
	 */
	public $time_intervals;
	
	/**
	 * string, containing ids of selected products separated by ','
	 * 
	 * @var string
	 */
	public $selected_products;
	
	/**
	 * recursive tree of arrays,
	 *  which represents the tree structure of VirteMart product categories
	 * 
	 * @var array
	 */
	public $categories_tree = null;
	
	/**
	 * string, containing ids of selected categories separated by ','
	 * 
	 * @var string
	 */
	public $selected_categories = "";
	
	/**
	 * flag attribute. Change counting start and end date. 
	 * 
	 * @var boolean
	 */
	public $last_day = false;
	
	/**
	 * number of first selected record by SQL query
	 * 
	 * @var int
	 */
	public $limit_start = 0;
	
	/**
	 * number of amount recors returned by SQL query
	 * 
	 * @var int
	 */
	public $limit_count = 0;	
	
	function getUserGroupsData()
	{
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
 
   		$query = "SELECT g.group_name AS user_group, count(*) as count, sum(o.order_subtotal) as revenue "
				."FROM #__vm_orders o "
				."LEFT JOIN #__vm_auth_user_group ug ON (ug.user_id = o.user_id) "
				."LEFT JOIN #__vm_auth_group g ON (g.group_id = ug.group_id) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.")  "
   			  	."GROUP BY g.group_name ";

   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	function getOrdersData(){
		$db =& JFactory::getDBO();
		
		$this->_data = $this->getTimeIntervals();
		$interval = $this->getTimeIntervalSQL();
		
   		$query = "SELECT $interval[select] AS time, count(*) as count, sum(order_subtotal) as revenue "
   			  	."FROM #__vm_orders o "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.") "
   			  	."GROUP BY $interval[select]";

   		$db->setQuery( $query );

   		
   		foreach ($db->loadObjectList() as $set){
   			$this->_data[$set->time]['count'] = $set->count;
   			$this->_data[$set->time]['revenue'] = $set->revenue;
   		}
   		
		return $this->_data;
	}
	
	function getProductsSaleability(){
		$db =& JFactory::getDBO();
		$intervals = $this->getTimeIntervals();
		$interval = $this->getTimeIntervalSQL();

		$in = array();
		if ($this->selected_products){
			$in = explode (',', $this->selected_products);
		}
		
		foreach ($in as $product){
			$product_id = (int)trim($product);
			
			if ($product_id) {
				$_in[] = $product_id;
			}
		}
		
		if (is_array($_in)){
			foreach ($_in as $id){
				$query = "SELECT product_name FROM #__vm_product WHERE product_id = $id";
				$db->setQuery( $query );
				$this->_data[$id]['name'] = $db->loadObject()->product_name;
			
				$query = "SELECT $interval[select] AS time, p.product_id, sum(o.product_quantity) as count, sum(o.product_item_price * o.product_quantity) as revenue "
	   				  	."FROM #__vm_product p "
   			  			."JOIN #__vm_order_item o ON (o.product_id = p.product_id) "
   					  	."WHERE (p.product_id = $id OR p.product_parent_id = $id) AND $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.")  "
   					  	."GROUP BY $interval[select]";

	   			$db->setQuery( $query );

	   		
	   		
   				$this->_data[$id]['data'] = $intervals; 
	   			foreach ($db->loadObjectList() as $set){
   					$this->_data[$id]['data'][$set->time]['count'] = $set->count;
   					$this->_data[$id]['data'][$set->time]['revenue'] = $set->revenue;
   				}
			}
		}
	
		return $this->_data;
	}
	
	/**
	 * Builds array with names of time interval between start and end date. 
	 * Intervals names are store in array keys
	 */
	private function getTimeIntervals()
	{
		if (!$this->time_interval){
			return;
		}
		
		$_start = explode ('-', $this->start_date);
		$_end = explode ('-', $this->end_date);
		
		$start = mktime (0, 0, 0, $_start[1], $_start[2], $_start[0]);
		$end = mktime (23, 59, 59, $_end[1], $_end[2], $_end[0]);
		$intervals = array();
		
		switch ($this->time_interval){
			case 'hour':
				$date = getdate();
				if ($this->last_day == true){
					$start = mktime ($date['hours'], $date['minutes'], $date['seconds'], $_start[1], $_start[2], $_start[0]);
					$end = mktime ($date['hours'], $date['minutes'], $date['seconds'], $_end[1], $_end[2], $_end[0]);
					$start -= (int)date("i", $start) * 60;
					$start -= (int)date("s", $start);
				}
				$this->start_date = $start;
				$this->end_date = $end;
				$iterator = VMREPORTS_SEC_IN_HOUR;
				break;
			case 'day':
				$iterator = VMREPORTS_SEC_IN_DAY;
				break;
			case 'week':
				$iterator = VMREPORTS_SEC_IN_DAY * 7;
				
				$start_date = getdate($start);
				$date = getdate();
				$start -= ($start_date['wday'] - 1) * VMREPORTS_SEC_IN_DAY;
				$end = mktime ($date['hours'], $date['minutes'], $date['seconds'], $_end[1], $_end[2], $_end[0]);
				break;
			case 'month':
				$start = mktime (0, 0, 0, $_start[1], 1, $_start[0]);
				$iterator = cal_days_in_month(CAL_GREGORIAN, $_start[1], $_start[0]) * VMREPORTS_SEC_IN_DAY;
				break;
		}
		$i = $start;
		for ($i = $start; $i <= $end; $i += $iterator){
			switch ($this->time_interval){
				case 'hour':
					$intervals[date("Y-m-d H", $i)] = null;
					break;
				case 'day':
					$intervals[date("Y-m-d", $i)] = null;
					break;
				case 'week':
					$intervals[date("Y-W", $i)] = null;
					break;
				case 'month':
					$intervals[date("Y-m", $i)] = null;
					$year = date("Y", $i);
					$month = date("m", $i);
					$iterator = strtotime("next month", $i) - $i;
					break;
			}
		}
		$this->time_intervals = $intervals;
		return $intervals;
	}
	
	function getPaymentMethodsData(){
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
 
   		$query = "SELECT p.payment_method_name AS payment, count(*) as count, sum(o.order_subtotal) as revenue  "
				."FROM #__vm_orders o "
				."JOIN #__vm_order_payment x ON (x.order_id = o.order_id) "
				."JOIN  #__vm_payment_method p ON (p.payment_method_id = x.payment_method_id) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.")  "
   			  	."GROUP BY p.payment_method_name ";

   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	function getCategoriesData(){
		if (is_array($this->selected_categories)) {
			foreach ($this->selected_categories as $cat){
				$categories[] = $this->getSubcategories($cat, $this->categories_tree);
			}
		}
		
		$interval = $this->getTimeIntervalSQL();
		
		$intervals = $this->getTimeIntervals();
		
		if (is_array($categories)){
			foreach ($categories as $cat){
				$db =& JFactory::getDBO();

				$query = "SELECT $interval[select] as time, count(o.order_id) as count, sum(o.order_subtotal) as revenue "
						."FROM #__vm_orders o "
						."JOIN #__vm_order_item oi ON (o.order_id = oi.order_id) "
						."JOIN #__vm_product p ON (p.product_id = oi.product_id) "
						."JOIN #__vm_product_category_xref cr ON (cr.product_id = p.product_id OR cr.product_id = p.product_parent_id) "
						."WHERE cr.category_id IN (" . implode(',', $cat['ids']) . ") AND $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.") "
						."GROUP BY $interval[select]";
	   			$db->setQuery( $query );
	   			
		   		$this->_data[$cat['name']] = $intervals;
	   		
		   		foreach ($db->loadObjectList() as $set){
	   				$this->_data[$cat['name']][$set->time]['count']  = $set->count;
	   				$this->_data[$cat['name']][$set->time]['revenue']  = $set->revenue; 
		   		}
   			
			}
		}
		
		return $this->_data;
	}
	
	/**
	 * return all subcategories of category in array
	 * 
	 * @param int $id of iterested category
	 * @param unknown_type $cats categories tree data
	 * @param unknown_type $found flag, 1 if interested category found
	 */
	private function getSubcategories($id, $cats, $found = 0){
		$return = array();
		
		if ($found == 0){
			foreach ($cats as $cat){
				if ($cat->id == $id){
					$found = 1;
					$return = array($id);
				}
				if ($cat->subcats){
					$subcats = $this->getSubcategories($id, $cat->subcats, $found);
					$return = array_merge($return,$subcats); 
				}

				if ($cat->id == $id){
					$ret['name'] = $cat->name;
					$ret['ids'] = $return;
					return $ret;
				}
			}
		} else {
			foreach ($cats as $cat){
				if ($cat->subcats){
					$return = array_merge($this->getSubcategories($id, $cat->subcats, $found), $return);
				}
				 
				$return = array_merge ($return, array($cat->id));	
			}
		}
		return $return;
	}
	
	/**
	 * Recursive method for creating tree array of categories
	 * 
	 * @param $cat_id id of paren category
	 */
	function getCategoryTree($cat_id = 0){
		$db =& JFactory::getDBO();
		
		$query = "SELECT c.category_id AS id, c.category_name AS name "
				."FROM #__vm_category_xref x "
				."JOIN #__vm_category c ON (x.category_child_id = c.category_id) "
   			  	."WHERE x.category_parent_id = $cat_id";

   		$db->setQuery( $query );	
   		$categories = $db->loadObjectList();
   		
   		foreach ($categories as $cat){
   			$subcats = $this->getCategoryTree($cat->id);
   			$cat->subcats = $subcats;
   		}

		return $categories;
	}
	
	/**
	 * returns parts of SQL query, which are different for each time interval
	 */
	private function getTimeIntervalSQL(){
		switch ($this->time_interval){
			case 'hour':
				$interval['select'] = "CONCAT(DATE(FROM_UNIXTIME(o.cdate)), ' ', DATE_FORMAT(FROM_UNIXTIME(o.cdate), '%H'))";
				$interval['where'] = "o.cdate";
				break;
			case 'day':
				$interval['select'] = "DATE(FROM_UNIXTIME(o.cdate))";
				$interval['where'] = "DATE(FROM_UNIXTIME(o.cdate))";
				break;
			case 'week':
				$interval['select'] = "CONCAT(YEAR(FROM_UNIXTIME(o.cdate)), '-',WEEKOFYEAR(FROM_UNIXTIME(o.cdate)))";
				$interval['where'] = "DATE(FROM_UNIXTIME(o.cdate))";
				break;
			case 'month':
				$interval['select'] = "DATE_FORMAT(DATE(FROM_UNIXTIME(o.cdate)), '%Y-%m')";
				$interval['where'] = "DATE(FROM_UNIXTIME(o.cdate))";
				break;
		}
		return $interval;		
	}
	
	function getTopProductsData()
	{
		$db =& JFactory::getDBO();

		$interval = $this->getTimeIntervalSQL();

		$query = "SELECT p.product_id as id, p.product_name as name, sum(oi.product_quantity) as count, sum(oi.product_item_price * oi.product_quantity) as revenue "
			  	."FROM #__vm_product p "
		  		."JOIN #__vm_order_item oi ON (oi.product_id = p.product_id) "
		  		."JOIN #__vm_orders o ON (o.order_id = oi.order_id) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.") AND oi.order_status IN (".VMREPORTS_COUNTED_STATUS.")"
   				."GROUP BY p.product_id "
   				."ORDER BY $this->order_by DESC "
   				."LIMIT 0, $this->limit_count ";

	   	$db->setQuery( $query );

   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	function getTopUsersData()
	{
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
 
   		$query = "SELECT u.name, u.id, count(*) as count, sum(o.order_subtotal) as revenue "
				."FROM #__vm_orders o "
				."JOIN #__users u ON (u.id = o.user_id) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.")  "
   			  	."GROUP BY u.id "
   			  	."ORDER BY $this->order_by DESC "
   			  	."LIMIT 0, $this->limit_count ";

   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	function getTopCategoriesData()
	{		
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
		
   		$query = "SELECT c.category_id as id, c.category_name as name, sum(oi.product_quantity) as count, sum(oi.product_item_price * oi.product_quantity) as revenue "
   				."FROM #__vm_orders o "
   				."JOIN #__vm_order_item oi ON (oi.order_id = o.order_id) "
   				."JOIN #__vm_product p ON (oi.product_id = p.product_id) "
   				."JOIN #__vm_product_category_xref pc ON (p.product_id = pc.product_id OR p.product_parent_id = pc.product_id) "
   				."JOIN #__vm_category c ON (c.category_id = pc.category_id) "
   				."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.") "
   				."GROUP BY pc.category_id "
   				."ORDER BY $this->order_by DESC "
   				."LIMIT 0, $this->limit_count ";
   				
   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();  		

		return $this->_data;
	}
	
	function getOrdersStatusData()
	{
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
 
		$query = "SELECT os.order_status_name as status, count(*) as count, sum(o.order_subtotal) as revenue "
				."FROM #__vm_orders o "
				."JOIN #__vm_order_status os ON (os.order_status_code = o.order_status) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' "
   			  	."GROUP BY o.order_status ";

   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	function getOrdersByCountryData()
	{
		$db =& JFactory::getDBO();
		
		if ($this->last_day == true){
			$this->time_interval = 'hour';
		}
		$interval = $this->getTimeIntervalSQL();
		$this->getTimeIntervals();
 
		$query = "SELECT c.country_id as id, c.country_name as name, sum(o.order_subtotal) as revenue "
				."FROM #__vm_orders o "
				."JOIN #__vm_order_user_info ou ON (ou.order_id = o.order_id) "
				."JOIN #__vm_country c ON (c.country_3_code = ou.country OR c.country_2_code = ou.country) "
   			  	."WHERE $interval[where] BETWEEN '$this->start_date' AND '$this->end_date' AND o.order_status IN (".VMREPORTS_COUNTED_STATUS.") "
   			  	."GROUP BY c.country_id "
   			  	."ORDER BY revenue DESC "
   			  	."LIMIT 0, $this->limit_count ";

   		$db->setQuery( $query );	
   		$this->_data = $db->loadObjectList();

		return $this->_data;
	}
	
	/**
     * Returns filtered products list
     */
	function getAjaxProductsList($filter)
	{
		$db =& JFactory::getDBO();
    	
		$filter = explode (' ', $filter);
		
		foreach ($filter as $key => $item) {
			$where[] = " product_name LIKE '%" . trim($item) . "%' ";
		}
		
		$where = implode (' OR ', $where);
		
    	$query = "SELECT product_id as id, product_name as name "
    			."FROM #__vm_product "
    			."WHERE $where "
    			."ORDER BY product_name ";
    	
    	$db->setQuery( $query );   		
   		$products = $db->loadObjectList();
		
   		return $products;
	}
}