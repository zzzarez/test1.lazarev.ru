<?php 
/**
* P2DXT for Joomla!
* @Copyright ((c) 2008 - 2010 JoomlaXT
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ http://www.joomlaxt.com
* @version 1.00.14
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
<?php
jimport('joomla.application.component.model');

class p2dxtModelDonation extends JModel
{
	function check($val = false, $id=false) {
		if (!$val) $val = JRequest::getVar('p2dDonationAmount', 0 );
		if (!$id) $id = JRequest::getVar('id', 0 );
		$db = JFactory::getDBO();
		$item = new p2dxt_files($db);
		$item->load($id);

		$minamount = $item->minamount;
		
		if ($val >= $minamount) $data = "true";
		else $data = "<font color=\"red\">".JText::_("Please enter at least the minimum donation amount of")." ".$item->minamount." ".$item->currency."</font>";

		if (preg_match('/[^0-9\.]/',$val)) {
			$data = "<font color=\"red\">".JText::_("Please enter a numeric donation value")."</font>";
		} 
		return $data;		
	}
	
	function finalCheck() {
		$val = JRequest::getVar('p2dDonationAmount', 0 );
		$id = JRequest::getVar('id', 0 );
		$r = JRequest::get();
		return $this->check($val, $id);
	}
}

?>