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


		$document =& JFactory::getDocument();
		 
		// Set the MIME type for JSON output.
		$document->setMimeEncoding( 'application/json' );
		 
		// Change the suggested filename.
		JResponse::setHeader( 'Content-Disposition', 'attachment; filename="test.json"' );
		 
		// Output the JSON data.
		echo json_encode( $this->data );
	}
}
?>