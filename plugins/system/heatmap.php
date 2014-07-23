<?php
/**
* @version   $Id: heatmap.php 13 2011-08-12 21:20:35Z simonpoghosyan@gmail.com $
* @package   Heat Map
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Heat Map plugin
 *
 */
class  plgSystemHeatmap extends JPlugin {
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @access protected
     * @param  object $subject The object to observe
     * @param  array  $config  An array that holds the plugin configuration
     * @since  1.0
     */
    function plgSystemHeatmap(& $subject, $config) {
        parent::__construct($subject, $config);
    }

    /**
     * Add mootools
     *
     * @access public
     */ 
    function onBeforeCompileHead() {
    	global $mainframe;
    	// Enable only on frontend
        if($mainframe->isAdmin())
            return;

        // Enable only on the default page
        $menu = JSite::getMenu();
        if($menu->getActive() != $menu->getDefault())
            return;
		JHTML::_('behavior.mootools');
    }
    
    /**
     * Add JavaScript reloader
     *
     * @access public
     */ 
    function onAfterRender() {
        global $mainframe;
        // Enable only on frontend
        if($mainframe->isAdmin())
            return;

        // Enable only on the default page
        $menu = JSite::getMenu();
        if($menu->getActive() != $menu->getDefault())
            return;

        // TODO: add feature to disable self clicks

        // Creates heatmap wrapper and sends request to generate image, defines the $heatmap_stat variable
        include 'plugins/system/heatmap/create_request.php';

        // Cncludes javascript to send clicks data, defines the $heatmap_javascript variable
        include 'plugins/system/heatmap/javascript_click.php';

        // Makes appropriate changes to the HTML
        $content = JResponse::getBody();
        $content = str_replace('</body>', $heatmap_stat . $heatmap_javascript . '</body>', $content);
        JResponse::setBody($content);
    }

}