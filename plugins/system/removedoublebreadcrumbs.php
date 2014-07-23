<?php
/**
 * @version     $Id: removedoublebreadcrumbs.php 1.0 2010-07-14 16:50:00 WebRaket $
 * @package     Joomla
 * @subpackage  System
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemRemovedoublebreadcrumbs extends JPlugin{

	function plgSystemRemovedoublebreadcrumbs( &$subject, $config ){
		parent::__construct( $subject, $config );
	}

	function onAfterDispatch(){
		
		$app =& JFactory::getApplication();
		if ( $app->getClientId() === 0 ) {

			global $mainframe;
			$pathway =& $mainframe->getPathway();
			$items = $pathway->getPathWay();
			
			$aUniqueItems = array();
			foreach($items as $key=>$value){
				if(in_array($value->name,$aUniqueItems)){
					unset($items[$key]);
				}else{
					$aUniqueItems[] = $value->name;
				}
			}

			$pathway->setPathWay($items);
		}
	}
}
?>