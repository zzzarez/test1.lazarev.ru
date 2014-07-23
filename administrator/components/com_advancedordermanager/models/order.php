<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport('joomla.application.component.model');
 jimport( 'joomla.database.database.mysqli' );


class orderModelorder extends JModel
{
var $total;
   		var $lists = '';
			var $_data=null;
			var $_pagination=null;
			function __construct()
			{
				parent::__construct();
				}
 
	function csearch()
	{
		global $mainframe;
		
		$mainframe =& JFactory::getApplication();
		
		//database connection
		$db =& JFactory::getDBO();
			//for ordering
		$filter_order=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order', 'filter_order', 'jos_vm_orders.order_id', '');
        $filter_order_Dir=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		 $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
		
		$limit= $mainframe->getUserStateFromRequest('global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
		 $limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
		  $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$couponcode	= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon',	'coupon',	'',		'' );

		//orderby
		       $orderby = '';
                $filter_order=$this->getState('filter_order');
                $filter_order_Dir = $this->getState('filter_order_Dir');
 
                /* Error handling is never a bad thing*/
                if(!empty($filter_order) && !empty($filter_order_Dir) )
				{
                        $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

                     	}	
		
		
		
$query=" select jos_vm_orders.order_id,jos_vm_orders.order_subtotal,jos_vm_orders.cdate,jos_vm_orders.coupon_discount,jos_vm_orders.coupon_code,jos_vm_orders.order_total,jos_vm_order_user_info.first_name,jos_vm_order_user_info.last_name from jos_vm_orders inner join jos_vm_order_user_info on jos_vm_orders.order_id=jos_vm_order_user_info.order_id and jos_vm_order_user_info.address_type='BT' and jos_vm_orders.coupon_code='$couponcode'".$orderby ;
		// search filter
		$this->lists['coupon']=$couponcode;
		
		//code for count total fetched record
		$db->setQuery( $query );
	$this->total=$this->_getListCount($query);
		
		
		if (empty($this->_data)) {
           $db->setQuery($query, $limitstart, $limit);
            $this->_data =$db->loadObjectList(); 
        }
        return $this->_data;
  
	
		
		
	
		//$post=JRequest::get('POST');
	
		//$couponcode=$post['coupon'];
		//$order=$post['filter_order'];
		//$couponcode=$c_codes->coupon;
 
	
	}


 
		function searchmdate()
		{
			//database connection
			$db =& JFactory::getDBO();
		
			global $mainframe;
		
			$mainframe =& JFactory::getApplication();
		
			
		$filter_order=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order', 'filter_order', 'jos_vm_orders.order_id', '');
        $filter_order_Dir=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
 
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);


		 $limit= $mainframe->getUserStateFromRequest( 'global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
		  $limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
		 $limitstart =($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
		$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );

		
		//orderby
		       $orderby = '';
                $filter_order=$this->getState('filter_order');
                $filter_order_Dir = $this->getState('filter_order_Dir');
 
                /* Error handling is never a bad thing*/
                if(!empty($filter_order) && !empty($filter_order_Dir) )
				{
                        $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

                     	}	
		
		
		//$post=JRequest::get('POST');
	
		//$dstart=$post['dstart'];
		//$edate=$post['edate'];
		//$orderby="jos_vm_orders.order_id";
		$time2="10:05:53";
	if(!empty($dstart) && !empty($dstart))
	{
		list($year,$month,$day)= explode('-',$dstart);
		list($hour,$minute,$second)= explode(':',$time2);
		$timestamp2 = mktime($hour,$minute,$second,$month,$day,$year);

		list($year,$month,$day)= explode('-',$edate);
		list($hour,$minute,$second)= explode(':',$time2);
		$timestamp3 = mktime($hour,$minute,$second,$month,$day,$year);
	}
 $query="select jos_vm_orders.order_id,jos_vm_orders.order_subtotal,jos_vm_orders.cdate,jos_vm_orders.coupon_discount,jos_vm_orders.coupon_code,jos_vm_orders.order_total,jos_vm_order_user_info.first_name,jos_vm_order_user_info.last_name from jos_vm_orders join jos_vm_order_user_info on jos_vm_orders.order_id=jos_vm_order_user_info.order_id and jos_vm_orders.cdate >='".$timestamp2. "' and jos_vm_orders.cdate <='".$timestamp3."' and jos_vm_orders.coupon_code !='' and jos_vm_order_user_info.address_type='BT' ".$orderby;
	
  //code for count total fetched record
  
		$db->setQuery( $query );
		$this->total=$this->_getListCount($query);
  
		 
			
	if (empty($this->_data)) {
           $db->setQuery($query, $limitstart, $limit);
            $this->_data =$db->loadObjectList(); 
        }
        return $this->_data;
  

	}
		
		 
		 function searchcoupondate()
		{

			//database connection
			$db =& JFactory::getDBO();
		
			global $mainframe;
		
			$mainframe =& JFactory::getApplication();
		
			
		$filter_order=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order', 'filter_order', 'jos_vm_orders.order_id', '');
        $filter_order_Dir=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
 
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);


		 $limit= $mainframe->getUserStateFromRequest( 'global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
		  $limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
		 $limitstart =($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
		$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
		$couponcode	= $mainframe->getUserStateFromRequest('com_advancedordermanager.coupon',	'coupon',	'',		'' );
		
		//orderby
		       $orderby = '';
                $filter_order=$this->getState('filter_order');
                $filter_order_Dir = $this->getState('filter_order_Dir');
 
                /* Error handling is never a bad thing*/
                if(!empty($filter_order) && !empty($filter_order_Dir) )
				{
                        $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

                     	}	
		
		//$post=JRequest::get('POST');
	
		//$dstart=$post['dstart'];
		//$edate=$post['edate'];
		//$orderby="jos_vm_orders.order_id";
		$time2="10:05:53";
	if(!empty($dstart) && !empty($dstart))
	{
		list($year,$month,$day)= explode('-',$dstart);
		list($hour,$minute,$second)= explode(':',$time2);
		$timestamp2 = mktime($hour,$minute,$second,$month,$day,$year);

		list($year,$month,$day)= explode('-',$edate);
		list($hour,$minute,$second)= explode(':',$time2);
		$timestamp3 = mktime($hour,$minute,$second,$month,$day,$year);
		}
  $query="select jos_vm_orders.order_id,jos_vm_orders.order_subtotal,jos_vm_orders.cdate,jos_vm_orders.coupon_discount,jos_vm_orders.coupon_code,jos_vm_orders.order_total,jos_vm_order_user_info.first_name,jos_vm_order_user_info.last_name from jos_vm_orders join jos_vm_order_user_info on jos_vm_orders.order_id=jos_vm_order_user_info.order_id and jos_vm_orders.cdate >='".$timestamp2. "' and jos_vm_orders.cdate <='".$timestamp3."' and jos_vm_orders.coupon_code !='' and jos_vm_order_user_info.address_type='BT' and jos_vm_orders.coupon_code LIKE '%".$couponcode."'".$orderby;
	
  //code for count total fetched record
  
		$db->setQuery($query );
		$this->total=$this->_getListCount($query);
  
		 
			
	if (empty($this->_data)) {
           $db->setQuery($query, $limitstart, $limit);
            $this->_data =$db->loadObjectList(); 
        }
        return $this->_data;


	}



		 function getPagination()
		{
			global $mainframe;
		
			$mainframe =& JFactory::getApplication();
		
			
			
			$limit= $mainframe->getUserStateFromRequest( 'global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
			$limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        // Load the content if it doesn't already exist
			if (empty($this->_pagination))
			{
				jimport('joomla.html.pagination');
				$this->_pagination = new JPagination($this->total, $limitstart, $limit);
				}
				return $this->_pagination;
  }

  
  
  
  
  
		
	
}	
?>
