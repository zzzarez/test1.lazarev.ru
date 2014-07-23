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
require_once(JPATH_SITE.DS."/administrator/components/com_p2dxt/inc/tables.p2dxt.php");


class P2DPro {
 	function onlyCompletedStatus($conf) {
		 return 0; 
	}
	function version() {
		return "com_p2dxt";
	}
 	function getComps() {
		$comps[] = "donation";
		return $comps;
	}
	function popupRequired($conf, $item) {
	 	$popup = false;
		if ($item->donation == "1") $popup = true; 
		return $popup;
	}  	
 	function parseText($text, $item, $txdb = false) {
		// Transaction specific variables
		if ($txdb) {
			$text = str_replace("[FIRST]", $txdb->first_name, $text);
			$text = str_replace("[LAST]", $txdb->last_name, $text);
		}
		// item specific
		$text = str_replace("[ITEM]", $item->itemname, $text);
		
			//donation vs. purchase
		if ($item->donation != "0") {
			$text = preg_replace("/(\[DONATION\](.*?)\[\/DONATION\])/is",'$2', $text);
			$text = preg_replace("/(\[PURCHASE\](.*?)\[\/PURCHASE\])/is",'', $text);

		}		
		else {
			$text = preg_replace("/(\[DONATION\](.*?)\[\/DONATION\])/is",'', $text);
			$text = preg_replace("/(\[PURCHASE\](.*?)\[\/PURCHASE\])/is",'$2', $text);
		}
		
		return $text;
		
	}
	
	function itemSettingsArticle($a, $b) {}
	function itemSettingsOverride($a, $b) {}
 	function showArticles($a) {}
	function getDataPath() {
		return JPATH_SITE.DS."components/com_p2dxt/data";
	}
	function powered($c) {}
 	function settings($a, $b) {}
 	function texts($a, $b, $c){}
 	function sendMail($a, $b, $c) {}
 	function showButton() {
		return true;
	}
 	function autoDownload() {
		return false;
	}
	function register() {}
	function registerList() {}
	function footer() {
		return "<br/><br/><div class=\"small\">powered by <a href=\"http://www.joomlaxt.com\">P2DXT</a></div><br/>";
	}
	function itemSettingsUpgrade() {}
	function check($a) {}
	function banner() {
		return "<center><a target=\"_blank\" href=\"http://www.joomlaxt.com\"><img border=\"0\" src=\"http://www.joomlaxt.com/images/stories/joomlaxt/p2dxtpro_banner.gif\"/></a></center>";
	}
	
	function guideLink() {
		return 	 JText::_('Get the  ')."<a target=\"_blank\" href=\"http://www.joomlaxt.com/index.php?option=com_p2dxt&p2did=1\">P2D Quick Guide</a>";

	}
	function forumLink() {
		return JText::_('Find solutions for your problems in the ')."<a target=\"_blank\" href=\"http://www.joomlaxt.com/forum?func=showcat&catid=16\">Support Forum</a>";

	}
	function cPanelTitle() {
		JToolBarHelper::title(  JText::_( 'Pay 2 download XT' ) );
	}
	function plgConfigSettings($a) {}
	function plgConfigItem() {}
	function plgSaveItem() {}
	function plgOnAfterPayment() {}
	function plgOnAfterRegistration() {	}
	function dashboard() {}

}
?>