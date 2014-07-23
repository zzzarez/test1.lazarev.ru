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
class p2dxtControllerAjax extends JController
{
	/**
	 * Display the view
	 */
	function ajaxitem()
	{

		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'ajaxitem');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', false);

		// Get and render the view.
		if ($view = &$this->getView($vName, $vFormat))
		{
			// Get the model for the view.
			$model = &$this->getModel($vName);
			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();

			// Load the submenu.
		}
	}
	function ajaxcp()
	{

		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'ajaxcp');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', false);

		// Get and render the view.
		if ($view = &$this->getView($vName, $vFormat))
		{
			// Get the model for the view.
//			$model = &$this->getModel($vName);
			// Push the model into the view (as default).
//			$view->setModel($model, true);
//			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();

			// Load the submenu.
		}
	}


}
?>