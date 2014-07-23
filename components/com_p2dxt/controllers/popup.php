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
class p2dxtControllerPopup extends JController
{
	/**
	 * Display the view
	 */
	function display()
	{
		JRequest::setVar( 'view', 'popup' );
		JRequest::setVar( 'layout', ''  );
		parent::display();
	}
	function fieldcheck()
	{
		$comp = JRequest::getWord('comp');
		$model = &$this->getModel($comp);	
		$result = $model->check();

		$document	= JFactory::getDocument();
	
		$vName		= JRequest::getWord('view', 'popup');
		$vFormat	= $document->getType();
		if ($view = &$this->getView('popup', $vFormat))
		
		$view->assignRef('data', $result);
		
		$view->display();
	}
	
	function allchecks() 
	{
		$id = JRequest::getVar("id");
		$db = JFactory::getDBO();
		$item = new p2dxt_files($db);
		$item->load($id);
		$conf = new p2dxt($db);
		$conf->load(1);
	 
//		$model = &$this->getModel('popup');			
		$comps = P2Dpro::getComps($conf, $item);
		$final = true;
		$error = "";
		foreach ($comps as $c) {
			$model = &$this->getModel($c);
			$chk = $model->finalCheck();
			if ($chk !="true") {
				$error .= $chk;
				$final = false; 
			}
		}
		
		if ($final) $data = "true";
		else $data = $error;

		$document	= JFactory::getDocument();
		$vFormat	= $document->getType();
		if ($view = &$this->getView('popup', $vFormat))
		
		$view->assignRef('data', $data);
		
		$view->display();
		 
		
		
	}

}
?>