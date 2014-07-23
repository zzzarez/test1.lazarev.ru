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

/**
 * class for rendering AJAX products select dialog
 * 
 * @author pedu
 *
 */
class ProductsSelect
{
	/**
	 * name of input
	 * 
	 * @var string
	 */
	var $name;
	
	/**
	 * URL of script, generating products list
	 * 
	 * @var string
	 */
	var $ajax_url;
	
	function __construct($name)
	{
		$this->name = $name;
		$this->ajax_url = JURI::root() . 'administrator/index.php?option=com_vmreports&page=productsSaleability&task=ajaxProductsList';
	}
	
	/**
	 * Renders dialog HTML code
	 */
	function render()
	{		
		$session_obj =& JFactory::getSession();
		$session = $session_obj->get('vmreports');
		$page = JRequest::getVar('page', 'orders', 'get', 'string');
		
		$selected_prods = JRequest::getVar($this->name, $session[$page]['products_select'], 'post', 'array');
		
		if (!$selected_prods) {
			$no_data_visibility = 'visible';
		} else {
			$no_data_visibility = 'hidden';
		}
		
		$code  = "<div id='".$this->name."NoDataDiv' class='warning' style='visibility: $no_data_visibility'>";
		$code .= Jtext::_('NO_PRODUCTS_SELECTED');
		$code .= "</div>";
		
		$code .= "<table id='".$this->name."SelectedProducts' class='selected_products_table'>";
				if (is_array($selected_prods)) {
					foreach ($selected_prods as $id => $product){
						$div_name = $this->name."_".$product."_".$id;
						$input_name = $this->name."[".$id."]";
						$code .= "<tr id='".$div_name."_tr' >"
						."<td>"
						."$product"
						."<input type='hidden' name='".$input_name."' value='".$product."' />"
						."</td><td class='product_delete_link' align='right'>"
						."<a onClick=\"AJAXupdater.deleteProduct('$id','$product','$this->name')\">".Jtext::_('REMOVE')."</a>"
						."</td>"
						."</tr>";
					}
				}
		$code .= "</table>";
				
		$code .=  "<input type='button' name='$this->name' id='$this->name' value='" . JText::_('SELECT') . "'  onClick='AJAXupdater.showProductsSelectDialog(\"$this->name\")' />";
		$code .= "<div id='".$this->name."Dialog' class='products_select_dialog' align='center'>"
					."<div width='100%' class='products_select_dialog_head'>"
						."<span class='products_select_dialog_close' onClick='AJAXupdater.hideProductsSelectDialog(\"$this->name\")'>Close</span>"
					."</div>"
					."<div class='products_select_dialog_body'>"
						. JText::_('Filter') . ": <input type='text' class='products_select_dialog_fileter_input' id='".$this->name."Filter' onkeyup='AJAXupdater.AJAXfilter(\"$this->name\", \"$this->ajax_url\")' />"
						."<div id='".$this->name."AJAXdiv' class='products_select_dialog_list'>"
						
						."</div>"
					."</div>"
				."</div>"
				."<script type=\"text/javascript\">"
					."AJAXupdater.AJAXfilter(\"$this->name\", \"$this->ajax_url\");"
				."</script>";
		
		return $code;
	}
}