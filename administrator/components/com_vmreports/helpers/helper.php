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

defined('_JEXEC') or die('Restricted access');

/**
 * Class with support methods
 * 
 * @author pedu
 *
 */
class VmreportsHelper
{
	/**
	 * Method for dumping data in friendly format
	 * 
	 * @param unknown_type $data
	 */
	function dump ($data)
	{
		echo "<pre>";
		print_r($this->data);
		echo "</pre>";
	}
	
	/**
	 * imports all necessary javascript files
	 */
	function importChartJS()
    {
        VmreportsHelper::importJS('chart.js');
        VmreportsHelper::importJS('FusionCharts.js');
        VmreportsHelper::importJS('vmreports.js');
        VmreportsHelper::importJS('joomla-tools.js');
        VmreportsHelper::importJS('productsSelect.js');
        VmreportsHelper::importJS('autility.js');
    }
    
    /**
     * imports one javascript file
     * 
     * @param $name
     */
	function importJS($name)
    {
        $document = &JFactory::getDocument();
        $document->addScript(VMREPORTS_ASSETS_JS . $name);
    }
    
    /**
     * prints unix timestamp in friendly date formate
     * 
     * @param $time
     */
    function dateFormat ($time = null)
    {
  		if ($time == null){
  			$time = time();
  		}
  		
  		return date("Y-m-d", $time);
    }
	
	/**
	 * loads menu tree array from XML file
	 */
	function getMenuFromXml (){
		//$menu = new DOMDocument();
		
		$xml = VMREPORTS_COMPONENT_ADR . DS . 'menu.xml';
		$menu =& JFactory::getXMLParser('Simple');
		$menu->loadFile($xml);
        
        return $menu;
	}
	
	function getVMReportsInfo()
    {
        static $info;
        
        if( !isset($info) ) {
            $info = array();
            
            $xml = JFactory::getXMLParser('Simple');

            $xmlFile = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_vmreports' . DS . 'vmreports.xml';

            if (file_exists($xmlFile)) {
                if ($xml->loadFile($xmlFile)) {
                    $root = & $xml->document;
                    
                    $element = & $root->getElementByPath('version');
                    $info['version'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('creationdate');
                    $info['creationDate'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('author');
                    $info['author'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('authoremail');
                    $info['authorEmail'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('authorurl');
                    $info['authorUrl'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('copyright');
                    $info['copyright'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('license');
                    $info['license'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('description');
                    $info['description'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('vmreportslink');
                    $info['vmreportslink'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('forum');
                    $info['forum'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('paidsupport');
                    $info['paidsupport'] = $element ? $element->data() : '';
                    
                    $element = & $root->getElementByPath('productpage');
                    $info['productpage'] = $element ? $element->data() : '';
                }
            }
        }
        
        return $info;
    }
	
 	function numberFormat($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $format = number_format($number, $decimals, $decPoint, $thousandsSep);
        if (JString::strlen($decPoint)) {
        	$decPoint = JString::substr($decPoint, 0, 1);
            $pos = JString::strpos($format, $decPoint);
            if ($pos !== false) {
                $int = JString::substr($format, 0, $pos);
                $float = JString::substr($format, $pos + 1);
                while (($length = JString::strlen($float))) {
                    if (JString::substr($float, $length - 1, 1) == 0)
                        $float = JString::substr($float, 0, $length - 1);
                    else
                        break;
                }
                return $float ? ($int . $decPoint . $float) : $int;
            }
        }
        return $format;
    }
}