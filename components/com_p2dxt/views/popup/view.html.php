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

class p2dxtViewPopup extends JView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$id = JRequest::getVar("p2did");
		$db = JFactory::getDBO();
		$item = new p2dxt_files($db);
		$item->load($id);
		$conf = new p2dxt($db);
		$conf->load(1);
		

		$this->assignRef('item',$item);		
		$this->assignRef('conf',$conf);		


		parent::display('master');
	}
}
?>