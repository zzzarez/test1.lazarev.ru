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

jimport( 'joomla.application.component.view');
jimport( 'joomla.language.language' );
jimport ( 'joomla.html.pane' );

/**
 * view class of the component
 * 
 * @author pedu
 *
 */
class VmreportsViewVmreports extends JView
{	
	/**
	 * Gets and sets attributes for displayed template
	 * 
	 * @param $tpl
	 */
	function display($tpl = null)
	{		
		$model = $this->getModel();		
		
		$page = JRequest::getVar('page', 'orders', 'get', 'string');
		switch ($page)
		{
			case 'settings':
				JToolBarHelper::title(JText::_('SETTINGS'),'vmreports.png');
				$settings = new Settings();
				if (!JRequest::getVar('errors', false, 'post', 'string')){
					$settings->LoadFromConfig();
				} else {
					$settings->visible_plot = JRequest::getVar('visible_plot', '', 'post', 'string');
    				$settings->counted_status = str_replace ("\\", "", $_POST['counted_status']);
    				$settings->chart_width = JRequest::getVar('chart_width', '', 'post', 'string');
    				$settings->chart_height = JRequest::getVar('chart_height', '', 'post', 'string');
    				$settings->currency = JRequest::getVar('currency', '', 'post', 'string');
    				$settings->decimal_precision = JRequest::getVar('decimal_precision', '', 'post', 'string');
    				$settings->max_label_length = JRequest::getVar('max_label_length', '', 'post', 'string');
				}
				
				$this->assignRef('settings', $settings);
				parent::display($tpl);
				return;
				break;
			case 'about':
				$info = VmreportsHelper::getVMReportsInfo();
				$this->assignRef('info', $info);
				 //echo VMREPORTS_COMPONENT_URL . 'assets' . DS . 'images' . DS . 'icon-48-vmreports.png';
				JToolBarHelper::title(JText::_('About'),'vmreports.png');
				break;
			default:
				$class = ucfirst($page)."Statistic";
				$statistic = new $class($model);
				JToolBarHelper::title(JText::_($statistic->title),'vmreports.png');
				
				$this->setStatisticParams($statistic, $page);
				$this->assignRef('statistic', $statistic);
			break;
		}
		
		//submenu definition
		JSubMenuHelper::addEntry(JText::_('STATISTICS'), 'index.php?option=com_vmreports&page=orders', in_array($page, array('orders', 'ordersStatus', 'userGroups', 'productsSaleability', 'paymentMethods', 'categories')));
		JSubMenuHelper::addEntry(JText::_('TOP_LISTS'), 'index.php?option=com_vmreports&page=topProducts', in_array($page, array('topProducts', 'topUsers', 'topCategories', 'ordersByCountry')));
		JSubMenuHelper::addEntry(JText::_('OTHER'), 'index.php?option=com_vmreports&page=settings', in_array($page, array('settings', 'about')));
		
		parent::display($tpl);
	}
	
	/**
	 * Set statistic parameters from request
	 * 
	 * @param object $statistic
	 */
	private function setStatisticParams ($statistic, $page)
	{
		$session_obj =& JFactory::getSession();
		//$session_obj->set('vmreports', array());
		/*echo "<pre>";
		var_dump($_SESSION);
		echo "</pre>";*/
		$session = $session_obj->get('vmreports');
		
		$statistic->date = getdate();
		$statistic->start_date_default = mktime(0, 0, 0, $statistic->date['mon'], 1, $statistic->date['year']);
		
		if (!$session[$page]) {
			$session[$page] = $statistic->getDefaultSession();
			
			$session[$page]['start_date'] = VmreportsHelper::dateFormat($statistic->start_date_default);
			$session[$page]['end_date'] = VmreportsHelper::dateFormat(time());
			$session[$page]['start_date_hid'] = VmreportsHelper::dateFormat($statistic->start_date_default);
			$session[$page]['end_date_hid'] = VmreportsHelper::dateFormat(time());
			$session[$page]['time_preset'] = 'custom';	
			
		}
		
		$statistic->order_by = JRequest::getVar('order_by', $session[$page]['order_by'] , 'post', 'string');
		$statistic->list_limit = JRequest::getVar('list_limit', $session[$page]['list_limit'] , 'post', 'string');
		//$statistic->selected_categories = JRequest::getVar('selected_categories', $session[$page]['selected_categories'], 'post', 'array');
		$statistic->selected_interval = JRequest::getVar('time_interval', $session[$page]['time_interval'], 'post', 'string');
		$statistic->selected_products = JRequest::getVar('products_select', $session[$page]['products_select'], 'post', 'array');
		$statistic->selected_time_preset = JRequest::getVar('time_preset', $session[$page]['time_preset'], 'post', 'string');
		
		if ($_POST) {
			$statistic->selected_categories = JRequest::getVar('selected_categories', array(), 'post', 'array');
		} else {
			$statistic->selected_categories = JRequest::getVar('selected_categories', $session[$page]['selected_categories'], 'post', 'array');
		}
		
		if ($statistic->selected_time_preset == 'custom') {
			$start_date = JRequest::getVar('start_date', $session[$page]['start_date'], 'post', 'string');
		} else {
			$start_date = JRequest::getVar('start_date_hid', $session[$page]['start_date_hid'], 'post', 'string');
		}
		$strd = explode ("-", $start_date);
		if (checkdate((int)$strd[1], (int)$strd[2], (int)$strd[0])) {
			$statistic->start_date = $start_date; 
		} else {
			$statistic->start_date = date("Y-m-d", time());
		}
		
		if ($statistic->selected_time_preset == 'custom') {
			$end_date = JRequest::getVar('end_date', $session[$page]['end_date'], 'post', 'string');
		} else {
			$end_date = JRequest::getVar('end_date_hid', $session[$page]['end_date_hid'], 'post', 'string');
		}
		$endd = explode ("-", $end_date);
		if (checkdate((int)$endd[1], (int)$endd[2], (int)$endd[0])) {
			$statistic->end_date = $end_date;	
		} else {
			$statistic->end_date = date("Y-m-d", time());
		}
		
		$statistic->setParameters();
		
		if ($statistic->show_total_values){
			$statistic->countTotalValues();
		}
		
		$statistic->countAverageValues();
		
		$statistic_session = $statistic->saveToSession();
		if (is_array($statistic_session)) {
			$session[$page] = array_merge($session[$page], $statistic_session);
		}
		$session_obj->set('vmreports', $session);
		
		if (!$session[$page]['check_count']){
			$statistic->check_count = "";
		}
		if (!$session[$page]['check_revenue']){
			$statistic->check_revenue = "";
		}
	
		if (!$session[$page]['check_avg_count']){
			$statistic->check_avg_count = "";
		}
		if (!$session[$page]['check_avg_revenue']){
			$statistic->check_avg_revenue = "";
		}
		
		/*echo "<pre>";
		var_dump($session[$page]);
		echo "</pre>";*/
	}

	/**
	 * Render HTML code of the menu, thet id displayed on the left side.
	 */
    function renderMenu(){
    	$pane = &JPane::getInstance ( 'sliders', array ('allowAllClose' => false, 'startOffset' => true) );
    	$menuXml = VmreportsHelper::getMenuFromXml();
    	
    	$menu = " <script type=\"text/javascript\" language=\"javascript\">
    				function changeColor(idObj,colorObj)
    				{
        				document.getElementById(idObj.id).style.color = colorObj;
    				}
				 </script>";
    	
    	$page = JRequest::getVar('page', 'orders', 'get', 'string');
    	
    	foreach ($menuXml->document->_children as $group){
    		$menu .= "<div class='vmreports_menu'>";
    		$menu .= "<div class='menu_title'><b>". JText::_ ( $group->_attributes['title'] )."</b></div>";	
    		
    		foreach ($group->_children as $link){
    			if ($page == $link->_attributes['page']) {
    				$class = "menu_item_selected";
    			} else {
    				$class = "menu_item";
    			}
				$menu .= "<a class='menu_link' id=\"menu_".$link->_attributes['title']."\" href='". JURI::root() . 'administrator/index.php?option=com_vmreports&page='.$link->_attributes['page'] . "'>"  		
						."<span class='$class'>" 
						. Jtext::_($link->_attributes['title']) 
						."</span>"
						."</a>";
    		}
    			 
    		$menu .= "</div>";
    	}
    	
    	return $menu;
    }    
}