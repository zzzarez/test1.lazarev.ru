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

jimport('joomla.application.component.view');

class p2dxtViewAjaxcp extends JView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$db=Jfactory::getDBO();
		$offset = JRequest::getVar('offset', 0);
		$row = new p2dxt($db);
		$row->load("1");
		if ($offset>0) {
			$row->cp_wizard = 1;
		}
		else {
			$row->cp_wizard = 2;
		}
		$row->store();

	}
}
?>