<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 //import base view class
jimport( 'joomla.application.component.view' );
 

class orderVieworder extends JView
{
   //default function 
    function display($tpl=null)
    {
		$path='administrator/components/com_advancedordermanager/';
		$doc = &JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().$path.'css'.'/'.'datep.css');
	
		$doc->addScript(JURI::root().$path.'js'.'/'.'DatePicke2r.js');

		$doc->addScriptDeclaration("window.addEvent('domready', function(){

	$$('input.DatePicker').each( function(el){
		new DatePicker(el);
	});

});");
		JToolBarHelper::title( JText::_( 'Search order' ), 'searchtext.png' );
		
		JToolBarHelper::back();
		
		$doc->addScriptDeclaration("function tableOrdering( order, dir, task )
		{
        var form = document.adminForm;
		form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );}");

	parent::display($tpl);
    }
        
		function show($tpl=null)
		{
		
		global $mainframe;
		$mainframe =& JFactory::getApplication();

		JToolBarHelper::title( JText::_('Search Order'), 'searchtext.png' );
		JToolBarHelper::addNewX('display','New Search');
		JToolBarHelper::back();
        
        $couponcode = $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon', 'coupon', '', '' );
		
 
        $model= & $this->getModel('order'); 
		$A=$model->csearch();
		 
		 /* Call the state object */
                $state =& $this->get( 'state' );
 
                /* Get the values from the state object that were inserted in the model function */
                $lists['order_Dir']=$state->get( 'filter_order_Dir' );
                $lists['order'] = $state->get( 'filter_order' );
 
                $this->assignRef( 'lists', $lists );
	//get pagination 
		$pageNav=$model->getpagination();
			
		// assign reference to templete
		$this->assignRef('pagenav', $pageNav);
		$this->assignRef('coupon', $couponcode);
		
        $this->assignRef('items',$A);
		//call template
		parent::display($tpl);
		}
	
	//View Function for search by date
		
		function searchvdate($tpl=null)
		{
			global $mainframe;
			$mainframe =& JFactory::getApplication();
			
			JToolBarHelper::title( JText::_( 'Search Order' ), 'searchtext.png' );
			JToolBarHelper::addNewX('display','New Search');
			JToolBarHelper::back();
			
			$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
			$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
				
				$model= & $this->getModel('order'); 
				$result=$model->searchmdate();
				               
				
 
                /* Call the state object */
                $state =& $this->get( 'state' );
 
                /* Get the values from the state object that were inserted in the model function */
                $lists['order_Dir']=$state->get( 'filter_order_Dir' );
                $lists['order'] = $state->get( 'filter_order' );
 
                $this->assignRef( 'lists', $lists );

				
			//get pagination object	
			$pageNav=$model->getpagination();
			
			// assign reference to templete
			$this->assignRef('pagenav', $pageNav);
			$this->assignRef('dstart', $dstart);
			$this->assignRef('edate', $edate);
			//$this->assignRef('lists',$model->lists );
			$this->assignRef('items',$result);
			
				
				parent::display($tpl);
			
		}
		
		
			function searchcoupondate($tpl=null)
		{
			global $mainframe;
			$mainframe =& JFactory::getApplication();
			
			JToolBarHelper::title( JText::_( 'Search Order' ), 'searchtext.png' );
			JToolBarHelper::addNewX('display','New Search');
			JToolBarHelper::back();
			
			$dstart= $mainframe->getUserStateFromRequest('com_advancedordermanager.dstart',	'dstart',	'',		'' );
			$edate= $mainframe->getUserStateFromRequest('com_advancedordermanager.edate',	'edate',	'',		'' );
			$couponcode = $mainframe->getUserStateFromRequest( 'com_advancedordermanager.coupon', 'coupon', '', '' );
				
				$model= & $this->getModel('order'); 
				$result=$model->searchcoupondate();
				               
				
 
                /* Call the state object */
                $state =& $this->get( 'state' );
 
                /* Get the values from the state object that were inserted in the model function */
                $lists['order_Dir']=$state->get( 'filter_order_Dir' );
                $lists['order'] = $state->get( 'filter_order' );
 
                $this->assignRef( 'lists', $lists );

				
			//get pagination object	
			$pageNav=$model->getpagination();
			
			// assign reference to templete
			$this->assignRef('pagenav', $pageNav);
			$this->assignRef('dstart', $dstart);
			$this->assignRef('edate', $edate);
			$this->assignRef('coupon', $couponcode);
			//$this->assignRef('lists',$model->lists );
			$this->assignRef('items',$result);
			
				
				parent::display($tpl);
			
		}
		
		
}
?>
