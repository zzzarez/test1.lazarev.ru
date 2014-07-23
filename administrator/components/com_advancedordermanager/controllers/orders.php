<?php
/**
 * @version		1.0.0 order $
 * @package		order
 * @copyright	Copyright © 2011 - All rights reserved.
 * @license		GNU/GPL
 * @author		braj
 * @author mail	brajkishor15@gmail.com
 *
 *
 * @
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class AdvancedordermanagerControllerorders extends JController
{



	/**
 * constructor (registers additional tasks to methods)
 * @return void
 */
	function __construct()
	{
		parent::__construct();
	 
		// Register Extra tasks
		$this->registerTask( 'add'  ,     'edit' );
	}
 
	function edit()
	{
		JRequest::setVar( 'view', 'orders' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
	 
		parent::display();
	}

	function save()
{
    $model = $this->getModel('orders');
 
    if ($model->store()) {
        $msg = JText::_( 'Greeting Saved!' );
    } else {
        $msg = JText::_( 'Error Saving Greeting' );
    }
 
    // Check the table in so it can be edited.... we are done with it anyway
    $link = 'index.php?option=com_advancedordermanager';
    $this->setRedirect($link, $msg);
}
 
function remove()
{
    $model = $this->getModel('Advancedordermanager');
    if(!$model->delete()) {
        $msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
    } else {
        $msg = JText::_( 'Greeting(s) Deleted' );
    }
 
    $this->setRedirect( 'index.php?option=com_advancedordermanager', $msg );
}


function cancel()
{
    $msg = JText::_( 'Operation Cancelled' );
    $this->setRedirect( 'index.php?option=com_advancedordermanager', $msg );
}

}
?>