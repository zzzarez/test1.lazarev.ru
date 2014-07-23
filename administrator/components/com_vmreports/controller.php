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

jimport( 'joomla.application.component.controller' );
JHTML::_('behavior.formvalidation');

/**
 * Controller class of the component
 * 
 * @author pedu
 *
 */
class VmreportsController extends JController
{
	function __construct($config = array())
	{
		parent::__construct();		
	}

	function display( )
	{
		$page = JRequest::getVar('page', 'orders', 'get', 'string');
		switch ($page)
		{
			case 'settings':
				JRequest::setVar('layout', 'settings');
				JToolBarHelper::save('save');
				break;
			case 'about':
				JRequest::setVar('layout', 'about');
				break;
		}

		require_once (JPATH_ROOT . DS. 'administrator/components/com_vmreports' . DS . 'config.php');
	
		parent::display();
	}
	
	/**
	 * Save statistics settings to the config.php
	 * 
	 * @param $apply
	 */
	function save($apply = false)
    {    	
    	$settings = new settings();
    	$settings->visible_plot = JRequest::getVar('visible_plot', '', 'post', 'string');
    	$settings->counted_status = $_POST['counted_status'];
    	$settings->chart_width = JRequest::getVar('chart_width', '', 'post', 'string');
    	$settings->chart_height = JRequest::getVar('chart_height', '', 'post', 'string');
    	$settings->currency = JRequest::getVar('currency', '', 'post', 'string');
    	$settings->decimal_precision = JRequest::getVar('decimal_precision', '', 'post', 'string');
    	$settings->max_label_length = JRequest::getVar('max_label_length', '', 'post', 'string');
    	if ($settings->isValid()){
    		$settings->saveConfig();
    		$message = Jtext::_('SAVED');
    	} else {
    		JRequest::setVar('errors', true);
    	}
    	
        $mainframe = &JFactory::getApplication();
        $mainframe->enqueueMessage($message);
        $this->display();
    }
    
    /**
     * Returns filtered products list for AJAX products select dialog
     */
    function ajaxProductsList(){
    	$filter = JRequest::getVar('filter', '', 'get', 'string');
    	$name = JRequest::getVar('name', '', 'get', 'string');
    	 		
    	$model = $this->getModel();
   		$products = $model->getAjaxProductsList($filter);
   		
   		$output = "<table width='100%'  >";
   		$output .= "<thead style='background-color: silver;'>"
   				   		."<tr>"
   				   			."<td>".Jtext::_('ID')."</td>"
   				   			."<td align='left'>".Jtext::_('PRODUCT_NAME')."</td>"
   				   			."<td></td>"
   				   		."</tr>"
   				   ."</thead>";
   				   $color_index = 0;
   					foreach ($products as $prod){
   						if (($color_index%2) == 0) {
   							$class = "product_select_dialog_row_one";
   						} else {
   							$class = "product_select_dialog_row_two";
   						}
   						
   						$output .= "<tr class='$class' >"
   								 ."<td align='center'>"
   								 	."<div onClick=\"AJAXupdater.addProduct('$prod->id', '$prod->name' ,'$name', '".Jtext::_('REMOVE')."') \">"
   								 		.$prod->id
   								 	."</div>"
   								 ."</td>"
   								 ."<td align='left' width='100%' >"
   								 	."<div onClick=\"AJAXupdater.addProduct('$prod->id', '$prod->name' ,'$name', '".Jtext::_('REMOVE')."') \">"
   								 		.$prod->name
   								 	."</div>"
   								 ."</td>"
   								 ."<td class='add_button'><a onClick=\"AJAXupdater.addProduct('$prod->id', '$prod->name' ,'$name', '".Jtext::_('REMOVE')."') \">".JText::_('ADD')."</a></td>"
   								 ."</tr>";
   						$color_index++;
   					}
   		$output .= "</table>";
   		
   		echo $output;
    	exit();
    }
}