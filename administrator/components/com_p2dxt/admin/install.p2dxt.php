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
require_once(JPATH_SITE.DS."administrator/components/com_p2dxt/installer.class.php");
$inst = new xtInstaller("p2dxt");
$inst->installPlugins($this);
function com_install() {
$success = true;
$database = &JFactory::getDBO();

$output = "";
// currently installed version

$inst = new xtInstaller("p2dxt");

if ($inst->isUpgrade()) {
	 	$database->setQuery("SELECT version"."\n FROM #__p2dxt");
		$version = $database->loadResult();
		if (!$version) $version=0;
		$output .= "<tr><td style=\"color:#000000; font-weight:bold; font-size:12px\">";
        $output .= "Upgrading from ".$version;
}

else {
		$output .= "<tr><td style=\"color:#000000; font-weight:bold; font-size:12px\">";
        $output .= "New Installation";
		$version = 0;
}
		
// include updateinfo
    require_once (dirname(__FILE__)."/../com_p2dxtupd.php");

// update database
	if ($createTable) $success = $inst->createTable($createTable);
	
    if ($updatequery) {
        foreach ($updatequery as $q) {
            if ($q->upversion > $version) {
                $database->setQuery(stripslashes($q->query));
                $database->query();
                if ($database->getErrorNum()) {
                    $output .= "<tr><td style=\"color:#bb0000; font-weight:bold; font-size:12px\">";
                    $output .= $database->getErrorMsg()."</td></tr>";
                    $success = false;
                }
                else {
                    $output .= "<tr><td style=\"color:#009900; font-weight:bold; font-size:12px\">";
                    $output .= $q->text." successful</td></tr>"; }

            }
        }
    }

//add joomfish if installed
if (file_exists(JPATH_SITE . '/administrator/components/com_joomfish/joomfish.php')) {
 	@unlink (JPATH_SITE."/administrator/components/com_joomfish/contentelements/p2dxt.xml");
	@rename( JPATH_SITE."/administrator/components/com_p2dxt/contentelements/p2dxt.xml",
		JPATH_SITE."/administrator/components/com_joomfish/contentelements/p2dxt.xml"); 
	@unlink( JPATH_SITE."/administrator/components/com_joomfish/contentelements/p2dxt_files.xml");
	@rename( JPATH_SITE."/administrator/components/com_p2dxt/contentelements/p2dxt_files.xml",
		JPATH_SITE."/administrator/components/com_joomfish/contentelements/p2dxt_files.xml"); 
	@rmdir (JPATH_SITE."/administrator/components/com_p2dxt/contentelements"); 
	$output .= "<tr><td style=\"color:#009900; font-weight:bold; font-size:12px\">";
    $output .= " Joomfish content files added.</td></tr>"; 
}


echo $output.$inst->output();
return $success;
}
?>
