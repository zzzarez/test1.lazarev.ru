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

class p2dxtViewAjaxitem extends JView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$db=Jfactory::getDBO();
		$id = JRequest::getVar('id', 0);
		$row = new p2dxt_files($db);
		$row->load($id);
		$this->assignRef('row', $row);
		
		$comp = JRequest::getVar('comp', 0);
		
		$document =& JFactory::getDocument();
		 
		// Set the MIME type for JSON output.
		$document->setMimeEncoding( 'application/json' );
		 
		// Change the suggested filename.
		JResponse::setHeader( 'Content-Disposition', 'attachment; filename="test.json"' );

		
		switch (JRequest::getVar($comp, 0)){
			case "1":
				$c = $comp."on";
				break;
			case "0":
				$c = $comp."off";
				break;
			case "2":
				$c = $comp."2";
				break;
		}
		// Output the JSON data.
		echo json_encode($this->loadTemplate($c));
	}
}
?>