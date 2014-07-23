<?php
/**
* RegEmail 1.0
* Plugin for Joomla 1.5.x
* 
* @version 1.0
* @author Leonid Tushov <gfk@mail.ru>
* @copyright 2010 Leonid Tushov
* @license http://www.opensource.org/licenses/lgpl-license.php LGPL
* @link http://tushov.ru
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgSystemRegEmail extends JPlugin {

    function onAfterInitialise()
    {
        if (JRequest::getVar('task') == 'register_save') {
            JRequest::setVar('username', JRequest::getVar('email'));
        }
    }
}