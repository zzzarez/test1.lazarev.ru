<?php
// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted to Aceess');
 
	jimport('joomla.application.component.model');
	jimport( 'joomla.database.database.mysqli' );


class AdvancedordermanagerModeladvancesearch extends JModel
{
			var $total;
			var $lists = '';
			var $_data=null;
			var $_pagination=null;
			var $savequery=null;
			function __construct()
			{
				parent::__construct();
			}
 
	function Amsearch()
	{
		global $mainframe;
		
		$mainframe =& JFactory::getApplication();
		
		//database connection
			$db =& JFactory::getDBO();
		
		//for ordering
			$filter_order=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order', 'filter_order', '#__vm_orders.order_id', '');
			$filter_order_Dir=$mainframe->getUserStateFromRequest('com_advancedordermanager.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
			$this->setState('filter_order', $filter_order);
			$this->setState('filter_order_Dir', $filter_order_Dir);
			//Limit start and end
			$limit= $mainframe->getUserStateFromRequest('global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
			$limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
			//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
			//coupon code
			$couponcode	= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon',	'coupon',	'',		'' );
			//Date
			$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
			$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
			
			//orderStatus
			 $orderStatus=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderStatus',	'orderStatus',	'',		'' );
			//paymentMethod
			$paymentMethod= $mainframe->getUserStateFromRequest('com_advancedordermanager.paymentMethod',	'paymentMethod',	'',		'' );
			//shippingMethod
			//$shippingMethod= $mainframe->getUserStateFromRequest('com_advancedordermanager.shippingMethod',	'shippingMethod',	'',		'' );
			//orderFrom
			//$orderFrom= $mainframe->getUserStateFromRequest('com_advancedordermanager.orderFrom',	'orderFrom',	'',		'' );
		//orderTo
			//$orderTo= $mainframe->getUserStateFromRequest('com_advancedordermanager.orderTo',	'orderTo',	'',		'' );
		//totalFrom
			//$totalFrom= $mainframe->getUserStateFromRequest('com_advancedordermanager.totalFrom',	'totalFrom',	'',		'' );
		//totalTo
			// $totalTo= $mainframe->getUserStateFromRequest('com_advancedordermanager.totalTo',	'totalTo',	'',		'' );
		//product
		$product=$mainframe->getUserStateFromRequest('com_advancedordermanager.prd_name',	'prd_name',	'',		'' );
			
		//orderid
		$orderid=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderid',	'orderid',	'',		'' );
		//orderdate
		// $orderdate=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderdate',	'orderdate',	'',		'' );
		//product SKU
		$prdsku=$mainframe->getUserStateFromRequest('com_advancedordermanager.prdsku',	'prdsku',	'',		'' );
		//Name
			$name=$mainframe->getUserStateFromRequest('com_advancedordermanager.name',	'name',	'',	'' );
			//Email
			$email=$mainframe->getUserStateFromRequest('com_advancedordermanager.email',	'email',	'',		'' );
		
			//last name
			$lastname=$mainframe->getUserStateFromRequest('com_advancedordermanager.lastname',	'lastname',	'',		'' );
			//user name
			$username=$mainframe->getUserStateFromRequest('com_advancedordermanager.username',	'username',	'',		'' );
		//searchq
		$savesearch=$mainframe->getUserStateFromRequest('com_advancedordermanager.searchq',	'searchq',	'',		'' );
		
		
		$this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
		
				//orderby
				$orderby = ' ORDER BY #__vm_orders.order_id ASC';
                $filter_order=$this->getState('filter_order');
                $filter_order_Dir = $this->getState('filter_order_Dir');
 
                /* Error handling */
                if(!empty($filter_order) && !empty($filter_order_Dir) )
				{
                         $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

                     	}
						
	$time2="00:00:01";
	$time3="23:59:01";
	$timestamp3=null;
	$timestamp2=null;
	if(!(empty($dstart)) && !(empty($edate)))
	{
		list($year,$month,$day)= explode('-',$dstart);
		list($hour,$minute,$second)= explode(':',$time2);
		 $timestamp2 = mktime($hour,$minute,$second,$month,$day,$year);

		list($year,$month,$day)= explode('-',$edate);
		list($hour,$minute,$second)= explode(':',$time3);
		 $timestamp3 = mktime($hour,$minute,$second,$month,$day,$year);
	}
		//default Date
		if($dstart=='' && $edate=='')
		{
			$timestamp2='15166225';

			$edate=date('Y-m-d',time());
			list($year,$month,$day)=explode('-',$edate);
 
			$timestamp3=mktime(23,59,59,$month,$day,$year);
		}
	//default order id
	/*if($orderFrom=='' && $orderTo =='')
	{
	$query1='select max(order_id) as order1 from #__vm_orders';
						$db->setQuery($query1);
						$rows=&$db->loadAssocList();
						foreach($rows as $value){
	
	
	
	  $orderTo= $value['order1'];
	 }
	 $query='select min(order_id) as order2 from #__vm_orders';
						$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
					 $orderFrom=$value['order2'];
		}
	}*/
	
	//default Total order
	/*if($totalTo =='' && $totalFrom =='')
	{
	 $query='select max(order_total) as T2 from #__vm_orders';
						$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value){
						 $totalTo=$value['T2'];
						}
		
	
		
		$query='select min(order_total) as T1 from #__vm_orders';
						$db->setQuery($query);
						$rows=&$db->loadAssocList();
				
						foreach($rows as $value)
						{
								
					 $totalFrom=$value['T1'];
					 }
}*/
	if(empty($orderid))
	{
	$orderid='%';
	}
	
	if(empty($email))
	{
		$email='%';
	
		}
	if(empty($name))
	{
	$name='%';
	}
	
	if(empty($orderStatus))
	{
	$orderStatus='%';
	}
	if(empty($paymentMethod))
	{
	$paymentMethod='%';
	}
	
	if(empty($couponcode))
	{
	$couponcode='%';
	}
	
	
	if(empty($prdsku))
	{
	$prdsku='%';
	}
	if(empty($product))
	{
	$product='%';
	}
	else
	{
	$product='%' . $product . '%';
	}
	if(empty($lastname))
	{
	$lastname='%';
	}
	if(!(empty($username)))
	{
	$query="select id from #__users where username='".$username."'";
	$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
						$username=$value['id'];
						}
	}
	else
	{
	$username='%';
	}
	
	if(!empty($savesearch))
	{
	$query=$savesearch;
	
	}
	
   else
   {
$query="select DISTINCT (#__vm_orders.order_id),#__vm_orders.order_subtotal,#__vm_orders.cdate,#__vm_orders.coupon_discount,#__vm_orders.coupon_code,#__vm_orders.order_total,#__vm_order_user_info.first_name,#__vm_order_user_info.last_name from #__vm_orders ,#__vm_order_item,#__vm_order_user_info,#__vm_order_payment where #__vm_orders.order_id=#__vm_order_user_info.order_id and #__vm_orders.order_id=#__vm_order_payment.order_id and #__vm_order_item.order_id=#__vm_orders.order_id and #__vm_orders.cdate between '$timestamp2' and '$timestamp3' and #__vm_order_user_info.address_type ='BT' and #__vm_orders.order_id like '$orderid' and #__vm_orders.order_status like '$orderStatus' and #__vm_orders.coupon_code like '$couponcode' and #__vm_order_payment.payment_method_id like '$paymentMethod' and #__vm_order_user_info.first_name like '$name' and #__vm_order_item.order_item_sku like '$prdsku' and #__vm_order_item.order_item_name like '$product' and #__vm_order_user_info.user_email like '$email' and #__vm_order_user_info.last_name like '$lastname' and #__vm_orders.user_id like '$username'$orderby";
}
	
		//code for count total fetched record
  
		$db->setQuery($query );
		$this->total=$this->_getListCount($query);
		//savequery
		$_SESSION['squery']=$this->savequery=$query;
		 
			
			if (empty($this->_data))
			{
			$db->setQuery($query, $limitstart, $limit);
            $this->_data =$db->loadObjectList(); 
			}
        return $this->_data;

}
	//Function for Page navigation
	
		function getPagination()
		{
				global $mainframe;
		
				$mainframe =& JFactory::getApplication();
		
			
			
			$limit= $mainframe->getUserStateFromRequest( 'global.list.limit','limit',$mainframe->getCfg('list_limit'), 'int' );
			$limitstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.limitstart',	'limitstart',	0,	'int' );
			//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
			// Load the content if it doesn't already exist
			if (empty($this->_pagination))
			{
				jimport('joomla.html.pagination');
				$this->_pagination = new JPagination($this->total, $limitstart, $limit);
				}
				return $this->_pagination;
  }
	//Function for save search record
		function store()
		{
			
			$db =& JFactory::getDBO();
		$mainframe =& JFactory::getApplication();
		$data=$_SESSION['squery'];
		$nameq=$mainframe->getUserStateFromRequest('com_advancedordermanager.savename','savename','','');
		$description=$mainframe->getUserStateFromRequest('com_advancedordermanager.description','description','','');
		$query="insert into #__advancedordermanager (id,searchname,description,savequery) values('"."','$nameq','$description',".'"'.$data.'")';

			$db->setQuery($query);
			$db->query();

			return true;

	}
	function delmsaved()
	{
	$db =& JFactory::getDBO();
	$query="select * from #__advancedordermanager";
	$db->setQuery($query);
	$data=$db->loadObjectList(); 
	return $data;
	}
	//Function For Deleting Saved Search Results
		function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable('advancedordermanager');
 
		foreach($cids as $cid) 
		{
			if (!$row->delete( $cid )) {
            $this->setError( $row->getErrorMsg() );
            return false;
        }
    }
 
		return true;
}


	//Function to Export Search Record
	function exportMysqlToCsv()
	{

		$db =& JFactory::getDBO();
    $mainframe =& JFactory::getApplication();
		$csv_terminated = "\n";
		$csv_separator = ",";
		$csv_enclosed = "'";
		$csv_escaped = "\\";
		//$sql_query =$_SESSION['squery']; 
		$schema_insert = '';
		 
		$out='Order Number'	.$csv_separator.'Name'.$csv_separator.'Address'.$csv_separator.'City'.$csv_separator.'State'.$csv_separator.'Country'.$csv_separator.'Postal'.$csv_separator.'Email'.$csv_separator.'Order Date'.$csv_separator.'Order Coupon'.$csv_separator.'Order Status'.$csv_separator.'Coupon Discount'.$csv_separator.'Order Subtotal'.$csv_separator.'Order Shiping '.$csv_separator.'Product'.$csv_separator.'Product Price'.$csv_separator.'Product SKU'.$csv_separator.'Product Quantity'.$csv_separator.'Order Total';
	   
		$out .= $csv_terminated;
 $couponcode= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon',	'coupon',	'',		'' );
			//Date
			$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
			$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
			
			//orderStatus
			 $orderStatus=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderStatus',	'orderStatus',	'',		'' );
			//paymentMethod
			$paymentMethod=$mainframe->getUserStateFromRequest('com_advancedordermanager.paymentMethod',	'paymentMethod',	'',		'' );
			
		//product
		$product=$mainframe->getUserStateFromRequest('com_advancedordermanager.prd_name',	'prd_name',	'',		'' );
		
		//orderid
		$orderid=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderid',	'orderid',	'',		'' );
		//orderdate
		// $orderdate=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderdate',	'orderdate',	'',		'' );
		//product SKU
		$prdsku=$mainframe->getUserStateFromRequest('com_advancedordermanager.prdsku',	'prdsku',	'',		'' );
		//Name
		$name=$mainframe->getUserStateFromRequest('com_advancedordermanager.name',	'name',	'',	'' );
			//Email
		$email=$mainframe->getUserStateFromRequest('com_advancedordermanager.email',	'email',	'',		'' );
		
			//last name
		$lastname=$mainframe->getUserStateFromRequest('com_advancedordermanager.lastname',	'lastname',	'',		'' );
			//user name
		$username=$mainframe->getUserStateFromRequest('com_advancedordermanager.username',	'username',	'',		'' );
		//searchq
		$savesearch=$mainframe->getUserStateFromRequest('com_advancedordermanager.searchq',	'searchq',	'',		'' );
	
	$time2="00:00:01";
	$time3="23:59:24";
	$timestamp3=null;
	$timestamp2=null;
	if(!(empty($dstart)) && !(empty($edate)))
	{
		list($year,$month,$day)= explode('-',$dstart);
		list($hour,$minute,$second)= explode(':',$time2);
		 $timestamp2 = mktime(0,0,0,$month,$day,$year);

		list($year,$month,$day)= explode('-',$edate);
		list($hour,$minute,$second)= explode(':',$time3);
		 $timestamp3 = mktime($hour,$minute,$second,$month,$day,$year);
	}
		//default Date
		if($dstart=='' && $edate=='')
		{
			$timestamp2='15166225';

			$edate=date('Y-m-d',time());
			list($year,$month,$day)=explode('-',$edate);
 
			$timestamp3=mktime(23,59,01,$month,$day,$year);
		}
		if(empty($orderid))
	{
	$orderid='%';
	}
	
	if(empty($email))
	{
		$email='%';
	
		}
	if(empty($name))
	{
	$name='%';
	}
	
	if(empty($orderStatus))
	{
	$orderStatus='%';
	}
	if(empty($paymentMethod))
	{
	$paymentMethod='%';
	}
	
	if(empty($couponcode))
	{
	$couponcode='%';
	}
	
	
	if(empty($prdsku))
	{
	$prdsku='%';
	}
	if(empty($product))
	{
	$product='%';
	}
	else
	{
	$product= '%' . $product . '%';
	}
	if(empty($lastname))
	{
	$lastname='%';
	}
	if(!(empty($username)))
	{
	$query="select id from #__users where username='".$username."'";
	$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
						$username=$value['id'];
						}
	}
	else
	{
	$username='%';
	}
	
	if(!empty($savesearch))
	{
	$sql_query=$savesearch;
	
	}
	else
	{
 $sql_query="select * from #__vm_orders ,#__vm_order_item,#__vm_order_user_info,#__vm_order_payment where #__vm_orders.order_id=#__vm_order_user_info.order_id and #__vm_orders.order_id=#__vm_order_payment.order_id and #__vm_order_item.order_id=#__vm_orders.order_id and #__vm_orders.cdate between '$timestamp2' and '$timestamp3' and #__vm_order_user_info.address_type ='BT' and #__vm_orders.order_id like '$orderid' and #__vm_orders.order_status like '$orderStatus' and #__vm_orders.coupon_code like '$couponcode' and #__vm_order_payment.payment_method_id like '$paymentMethod' and #__vm_order_user_info.first_name like '$name' and #__vm_order_item.order_item_sku like '$prdsku' and #__vm_order_item.order_item_name like '$product' and #__vm_order_user_info.user_email like '$email' and #__vm_order_user_info.last_name like '$lastname' and #__vm_orders.user_id like '$username' order by #__vm_orders.order_id ASC";
}


		$db->setQuery($sql_query);
		$data=$db->loadObjectList();
		
		

	for ($i=0, $n=count($data); $i < $n; $i++)
		{
			
				$row = & $data[$i];
	
				$schema_insert = '';
			
				if ($row->order_id != '')
				{
 
					
			$order   = array("\r\n", "\n", "\r",","," ");
              
                    $schema_insert .=$row->order_id ;
			$schema_insert .=$csv_separator. str_replace($order, " ", $row->first_name .' '.$row->last_name);
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->address_1 .' '.$row->address_2 );
			
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->city); 
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->state); 
				$schema_insert .=$csv_separator.str_replace($order, " ", $row->country);   ;
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->zip); 
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->user_email); 
					$schema_insert .=$csv_separator.$cdate=date('M-d-Y',$row->cdate);
					$schema_insert .=$csv_separator.str_replace($order, " ", $row->coupon_code);  
					$schema_insert .=$csv_separator.$row->order_status ;
					$schema_insert .=$csv_separator.$row->coupon_discount;
					$schema_insert .=$csv_separator.$row->order_subtotal ;
				$schema_insert .=$csv_separator. str_replace($order, " ", $row->order_shipping);
			$schema_insert .=$csv_separator.str_replace($order, " ", $row->order_item_name); 
					$schema_insert .=$csv_separator.$row->product_item_price;
					$schema_insert .=$csv_separator.str_replace($order, " ", $row->order_item_sku);
				$schema_insert .=$csv_separator.str_replace($order, " ", $row->product_quantity) ;
				
				if(@$data[$i+1]->order_id != $row->order_id)
					$schema_insert .=$csv_separator.$row->order_total . $csv_separator ;
                }
           
				
        $out .= $schema_insert;
        $out .= $csv_terminated;
     
	 } 
	 $out .= $csv_terminated.$csv_separator.$csv_separator.'Exported using Advanced Order Manager by www.jm-experts.com';
        $out .= $csv_terminated;
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($out));
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=export.csv");
		echo $out;
		exit;
 
	}

	
	function gettotal()
	{
	 $mainframe =& JFactory::getApplication();
	 $couponcode= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon',	'coupon',	'',		'' );
			//Date
			$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
			$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
			
			//orderStatus
			 $orderStatus=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderStatus',	'orderStatus',	'',		'' );
			//paymentMethod
			$paymentMethod=$mainframe->getUserStateFromRequest('com_advancedordermanager.paymentMethod',	'paymentMethod',	'',		'' );
			
		//product
		$product=$mainframe->getUserStateFromRequest('com_advancedordermanager.prd_name',	'prd_name',	'',		'' );
		
		//orderid
		$orderid=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderid',	'orderid',	'',		'' );
		//orderdate
		// $orderdate=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderdate',	'orderdate',	'',		'' );
		//product SKU
		$prdsku=$mainframe->getUserStateFromRequest('com_advancedordermanager.prdsku',	'prdsku',	'',		'' );
		//Name
		$name=$mainframe->getUserStateFromRequest('com_advancedordermanager.name',	'name',	'',	'' );
			//Email
		$email=$mainframe->getUserStateFromRequest('com_advancedordermanager.email',	'email',	'',		'' );
		
			//last name
		$lastname=$mainframe->getUserStateFromRequest('com_advancedordermanager.lastname',	'lastname',	'',		'' );
			//user name
		$username=$mainframe->getUserStateFromRequest('com_advancedordermanager.username',	'username',	'',		'' );
		//searchq
		$savesearch=$mainframe->getUserStateFromRequest('com_advancedordermanager.searchq',	'searchq',	'',		'' );
	
	$time2="00:00:01";
	$time3="23:59:24";
	$timestamp3=null;
	$timestamp2=null;
	if(!(empty($dstart)) && !(empty($edate)))
	{
		list($year,$month,$day)= explode('-',$dstart);
		list($hour,$minute,$second)= explode(':',$time2);
		 $timestamp2 = mktime(0,0,0,$month,$day,$year);

		list($year,$month,$day)= explode('-',$edate);
		list($hour,$minute,$second)= explode(':',$time3);
		 $timestamp3 = mktime($hour,$minute,$second,$month,$day,$year);
	}
		//default Date
		if($dstart=='' && $edate=='')
		{
			$timestamp2='15166225';

			$edate=date('Y-m-d',time());
			list($year,$month,$day)=explode('-',$edate);
 
			$timestamp3=mktime(23,59,01,$month,$day,$year);
		}
		if(empty($orderid))
	{
	$orderid='%';
	}
	
	if(empty($email))
	{
		$email='%';
	
		}
	if(empty($name))
	{
	$name='%';
	}
	
	if(empty($orderStatus))
	{
	$orderStatus='%';
	}
	if(empty($paymentMethod))
	{
	$paymentMethod='%';
	}
	
	if(empty($couponcode))
	{
	$couponcode='%';
	}
	
	if(empty($prdsku))
	{
	$prdsku='%';
	}
	if(empty($product))
	{
	$product='%';
	}
	else
	{
	$product= '%' . $product . '%';
	}
	if(empty($lastname))
	{
	$lastname='%';
	}
	if(!(empty($username)))
	{
	$query="select id from #__users where username='".$username."'";
	$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
						$username=$value['id'];
						}
	}
	else
	{
	$username='%';
	}
	
	if(!empty($savesearch))
	{
	$sql_query=$savesearch;
	
	}
	else
	{
 $sql_query="select DISTINCT (#__vm_orders.order_id),#__vm_orders.order_subtotal,#__vm_orders.cdate,#__vm_orders.coupon_discount,#__vm_orders.coupon_code,#__vm_orders.order_total,#__vm_order_user_info.first_name,#__vm_order_user_info.last_name from #__vm_orders ,#__vm_order_item,#__vm_order_user_info,#__vm_order_payment where #__vm_orders.order_id=#__vm_order_user_info.order_id and #__vm_orders.order_id=#__vm_order_payment.order_id and #__vm_order_item.order_id=#__vm_orders.order_id and #__vm_orders.cdate between '$timestamp2' and '$timestamp3' and #__vm_order_user_info.address_type ='BT' and #__vm_orders.order_id like '$orderid' and #__vm_orders.order_status like '$orderStatus' and #__vm_orders.coupon_code like '$couponcode' and #__vm_order_payment.payment_method_id like '$paymentMethod' and #__vm_order_user_info.first_name like '$name' and #__vm_order_item.order_item_sku like '$prdsku' and #__vm_order_item.order_item_name like '$product' and #__vm_order_user_info.user_email like '$email' and #__vm_order_user_info.last_name like '$lastname' and #__vm_orders.user_id like '$username'";
}

$db =& JFactory::getDBO();
		$db->setQuery($sql_query);
		$data1 = $db->loadObjectList();
		//$tot = '';
	//for ($i=0, $n=count($data1); $i < $n; $i++)
		//{
			
				//$row = & $data1[$i];
	
					//$tot = $tot + $row['order_total'];
	//}

	return $data1 ;


}

}








?>
