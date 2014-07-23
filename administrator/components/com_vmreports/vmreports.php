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

$page = JRequest::getVar('page', 'orders', 'get', 'string');

require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'productsSelect.php');
if (file_exists (JPATH_COMPONENT . DS . 'models' . DS . 'statistics' . DS . $page . '.php')){
	require_once (JPATH_COMPONENT . DS . 'models' . DS . 'statistics' . DS . $page . '.php');
}
require_once (JPATH_COMPONENT . DS . 'models' . DS . 'settings.php');

$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_vmreports/assets/css/style.css');

/*
 * set roles access
 */
$acl	= & JFactory::getACL();
$acl->addACL( 'com_vmreports', 'manage', 'users', 'super administrator' );
$acl->addACL( 'com_vmreports', 'manage', 'users', 'administrator' );
$acl->addACL( 'com_vmreports', 'manage', 'users', 'manager' );

/*
 * Make sure the user is authorized to view this page
 */
$user = & JFactory::getUser();
if (!$user->authorize( 'com_vmreports', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

$controller	= new VmreportsController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

