<?php
// No direct access
	defined('_JEXEC') or die('Restricted Access');

	//import base view class
	jimport( 'joomla.application.component.view' );


	class AdvancedordermanagerViewadvancesearch extends JView

{
		function display($tpl=null)
    {
		$path='administrator/components/com_advancedordermanager/';
		$path1=' . JURI::root() .';
		$doc = &JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().$path.'css'.'/'.'datep.css');
		$doc->addStyleSheet(JURI::root().$path.'css'.'/'.'jquery.autocomplete.css');
	
		
		$doc->addScript(JURI::root().$path.'css'.'/'.'jquery.js');
		$doc->addScript(JURI::root().$path.'css'.'/'.'jquery.autocomplete.js');
		JHTML::_('behavior.tooltip');
		$doc->addScriptDeclaration("window.addEvent('domready', function(){

	$$('input.DatePicker').each( function(el){
		new DatePicker(el);});
});");
		JToolBarHelper::title( JText::_( 'Advanced Order Manager' ), 'calculator' );
		
		JToolBarHelper::back();
		JToolBarHelper::preferences('com_advancedordermanager','250');
		


		$doc->addScriptDeclaration("function tableOrdering( order, dir, task )
		{
        var form = document.adminForm;
		form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );}");
		
		// build list of list
 		$db =& JFactory::getDBO();
		 $query = "SELECT * FROM #__advancedordermanager order by searchname asc";
		 $db->setQuery($query);
		$rl =$db->loadAssocList();
   
		$javascript =' onchange="document.adminform.submit();"';
		$options = array();
		$option1[] = JHTML::_('select.option', '', JText::_( 'Saved Search' ));
	foreach($rl as $key=>$value) :
   
		$options[] = JHTML::_('select.option',$rl[$key]['savequery'],$rl[$key]['searchname']);
	endforeach;
 
      $options= array_merge($option1,$options);
      

	$dropdown = JHTML::_('select.genericlist', $options, 'searchq','class="inputbox"'. $javascript,'value','text','selected_key',false);
	$this->assignRef('lists2', $dropdown);
	
	//parameter
		$params = &JComponentHelper::getParams( 'com_advancedordermanager' );
		 $productskutype=$params->get('productskutype');
		 $producttype=$params->get('producttype');
		 $paymentmethodtype=$params->get('paymentmethodtype');
		 $userlist= $params->get('userlist');
		
		$this->assignRef('productskutype',$productskutype);
				$this->assignRef('producttype',$producttype);
				$this->assignRef('paymentmethodtype',$paymentmethodtype);
				$this->assignRef('userlist',$userlist);
	parent::display($tpl);
    }
	
	
	function Avsearch($tpl=null)
	{
		global $mainframe;
		$mainframe =& JFactory::getApplication();
		$path='administrator/components/com_advancedordermanager/';
		$path1=' . JURI::root() .';
		$doc = &JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().$path.'css'.'/'.'datep.css');

		JToolBarHelper::title( JText::_('Advanced Order Manager'), 'calculator' );
		 //export button
		 $imgPath =(JURI::root().'/administrator/components/com_advancedordermanager/icons/icon-32-new.png');
	$doc->addStyleDeclaration(".icon-32-export { background: url($imgPath); }");
	$bar = & JToolBar::getInstance('toolbar');
	$url = JRoute::_('index.php?option=com_advancedordermanager&task=exportsearch&format=raw');
	$bar->appendButton('Link', 'export', 'Export', $url);
	JToolBarHelper :: custom( 'savesearchform', 'save', 'Save Search', 'Save search', false, false);
//end

		JToolBarHelper::addNewX('display','New Search');
		JToolBarHelper::back();
		$couponcode = $mainframe->getUserStateFromRequest('com_advancedordermanager.coupon', 'coupon', '', '' );
		$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
		$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
			//keyword
			$searchQuery= trim($mainframe->getUserStateFromRequest('com_advancedordermanager.searchQuery',	'searchQuery',	'',		'' ));
			//orderStatus
			$orderStatus= $mainframe->getUserStateFromRequest('com_advancedordermanager.orderStatus',	'orderStatus',	'',		'' );
			//paymentMethod
			$paymentMethod= $mainframe->getUserStateFromRequest('com_advancedordermanager.paymentMethod',	'paymentMethod',	'',		'' );
			//shippingMethod
			//$shippingMethod= $mainframe->getUserStateFromRequest('com_advancedordermanager.shippingMethod',	'shippingMethod',	'',		'' );
			//orderFrom
			// $orderFrom= $mainframe->getUserStateFromRequest('com_advancedordermanager.orderFrom',	'orderFrom',	'',		'' );
		//orderTo
			//$orderTo= $mainframe->getUserStateFromRequest('com_advancedordermanager.orderTo',	'orderTo',	'',		'' );
		//totalFrom
			//$totalFrom= $mainframe->getUserStateFromRequest('com_advancedordermanager.totalFrom',	'totalFrom',	'',		'' );
		//totalTo
			// $totalTo=$mainframe->getUserStateFromRequest('com_advancedordermanager.totalTo',	'totalTo',	'',		'' );
			 //product
		$product=$mainframe->getUserStateFromRequest('com_advancedordermanager.prd_name',	'prd_name',	'',		'' );
		
		
			//orderid
			$orderid=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderid',	'orderid',	'',		'' );
			//orderdate
			//$orderdate=$mainframe->getUserStateFromRequest('com_advancedordermanager.orderdate',	'orderdate',	'',		'' );
			//product SKU
			$prdsku=$mainframe->getUserStateFromRequest('com_advancedordermanager.prdsku',	'prdsku',	'',		'' );
			//Name
			$name=$mainframe->getUserStateFromRequest('com_advancedordermanager.name',	'name',	'',		'' );
			//Email
			$email=$mainframe->getUserStateFromRequest('com_advancedordermanager.email',	'email',	'',		'' );
			
			//last name
			$lastname=$mainframe->getUserStateFromRequest('com_advancedordermanager.lastname',	'lastname',	'',		'' );
			//user name
		  $username=$mainframe->getUserStateFromRequest('com_advancedordermanager.username',	'username',	'',		'' );
			$savesearch=$mainframe->getUserStateFromRequest('com_advancedordermanager.searchq',	'searchq',	'',		'' );
		
		$model= & $this->getModel('advancesearch'); 
		$result=$model->Amsearch();
		
		$total = $model->gettotal();
		$tot = 0;
		$subtot = 0;
		$cdiscount = 0;
	for ($i=0, $n=count($total); $i < $n; $i++)
		{
			
				$row = & $total[$i];
	
					$tot = $tot + $row->order_total;
					$subtot = $subtot +	$row->order_subtotal;
					$cdiscount =	$cdiscount + $row->coupon_discount;
	}
		
		$this->assignRef('tot', $tot);
		$this->assignRef('subtot', $subtot);
		$this->assignRef('cdiscount', $cdiscount);
		/* Call the state object */
                $state =& $this->get( 'state' );
  
            /* Get the values from the state object that were inserted in the model function */
                $lists['order_Dir']=$state->get('filter_order_Dir' );
                $lists['order'] = $state->get('filter_order');
 
                $this->assignRef( 'lists', $lists );
			
			//get pagination 
			$pageNav=$model->getpagination();
			
		// assign reference to templete
			
			$this->assignRef('orderStatus', $orderStatus);
			$this->assignRef('paymentMethod', $paymentMethod);
		//$this->assignRef('shippingMethod', $shippingMethod);
		//$this->assignRef('orderFrom', $orderFrom);
		//$this->assignRef('orderTo', $orderTo);
		//$this->assignRef('totalFrom', $totalFrom);
		//$this->assignRef('totalTo', $totalTo);
			$this->assignRef('product', $product);
		
			$this->assignRef('pagenav', $pageNav);
			$this->assignRef('coupon', $couponcode);
			$this->assignRef('dstart', $dstart);
			$this->assignRef('edate', $edate);
			
			$this->assignRef('name', $name);
			$this->assignRef('email', $email);
			$this->assignRef('username', $username);
			$this->assignRef('lastname', $lastname);
			
				$this->assignRef('savequery', $model->savequery);
				$this->assignRef('orderid', $orderid);
				$this->assignRef('prdsku', $prdsku);
				
				
				$this->assignRef('savesearch', $savesearch);
				$this->assignRef('items',$result);
				
			parent::display($tpl);
	}
	
		function display2($tpl=null)
		{
			parent::display($tpl);
		}
	
		function delview()
		{
			JToolBarHelper::deleteList('Do you really want to:delete');
			$model =& $this->getModel('advancesearch');
			$result=$model->delmsaved();
			$this->assignRef('items',$result);

		}
		
		
		
		
}
















?>