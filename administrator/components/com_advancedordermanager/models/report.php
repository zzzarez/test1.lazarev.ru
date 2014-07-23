<?php
// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted to Aceess');
 
	jimport('joomla.application.component.model');
	


class AdvancedordermanagerModelreport extends JModel
{
			function __construct()
			{
				parent::__construct();
			}
 
		function getreport()
		{
			global $mainframe;
		
		$mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		$categ = array();
		$dstart= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.sdate','sdate',	'',	'' );
	
		$edate= $mainframe->getUserStateFromRequest( 'com_advancedordermanager.edate','edate','','' );
	
		$prd	= JRequest::getVar('prd');
		
		$cat	= JRequest::getVar('cat');
		
		$orderby = ' ORDER BY #__vm_order_item.order_id ASC';
		
		$time2 = "00:00:01";
		$time3 = "24:00:00";
		$timestamp3 = null;
		$timestamp2 = null;
		
		
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

			$edate = date('Y-m-d',time());
			list($year,$month,$day)= explode('-',$edate);
 
			$timestamp3=mktime(23,59,01,$month,$day,$year);
		}	
		
	
	if(empty($prd))
	{
		$andq = '';
	
		}
		else
		{
		$prd = implode(',',$prd);
		$andq = " and product_id IN($prd)";
		}
		
$catar ='';
	if(empty($cat))
	{
		
	}
	else
	{
	$cat = implode(',',$cat);
	$sql = "select DISTINCT(product_id) from #__vm_product_category_xref where category_id IN($cat)";
	$db->setQuery($sql);
	$Record = $db->loadAssocList();
	foreach($Record as $d){
	$categ[] = $d['product_id'];
	
	
	}
	$categ =implode(',',$categ);
	 $catar = " and product_id IN($categ)";
	}
	
	
		  $query = "select * from #__vm_order_item 
		  where #__vm_order_item.cdate between '$timestamp2' and '$timestamp3'
		   $andq  $catar group by order_item_name ";

			
			$db->setQuery($query);
			$data = $db->loadAssocList();
		

			return $data;

}

	
}
	
	
	
	
	