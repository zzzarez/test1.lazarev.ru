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
$uri = JURI::getInstance();
$root = $uri->root();
$doc =& JFactory::getDocument();
$doc->addScript($root."components/com_p2dxt/p2dxt.js");

class p2dxtButton {
	function p2dxtButton($id, $introtext=true) {
 	global $mainframe;

	$database = JFactory::getDBO();
	$doc =& JFactory::getDocument();

 	$uri = JURI::getInstance();

	$item = new p2dxt_files($database);
	$item->load($id);

// set meta data
	$meta = $doc->getMetaData("keywords").",".$item->meta;
	$doc->setMetaData("keywords", $meta);

	if (!$item->id) {
		$this->html = JText::_("Item ID ").$id.JText::_("does not exist");
		return;
	}
	
	if ($item->limitdown > 0) {
		$query = "SELECT COUNT(id) FROM #__p2dxt_tx WHERE item_number = '$id'";
		$database->setQuery( $query );
		$cnt = $database->loadResult();
		
		if ($cnt >= $item->limitdown) {
			$this->html = JText::_('Maximum no. of downloads for this item exceeded.');
			return;
		}
	}
	
	$conf = new p2dxt($database);
	$conf->load("1");
		 	
	if ($item->testmode == "1") {
	 	$business = $conf->testbusiness;
	 	$paypal = "https://www.sandbox.paypal.com";
	}
	else {
	 	$business = $conf->business;
	 	$paypal = "https://www.paypal.com";
	}
	
	if ($conf->button == "") $conf->button = "http://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif";
	if ($conf->dbutton == "") $conf->dbutton = "https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif";
	
 	$itemName = $item->itemname;
 	$amount = $item->amount;
 	if ($item->donation == "0") {
		if ($item->taxcalc==1) {
			$tax = ($item->tax * $amount) / 100;
		}
		else 
		 	$tax = $item->tax;
	}
	elseif ($item->donation == "2") {
		if ($item->taxcalc==1) {
			$tx = 100 + $item->tax;
			$amount = round(($item->amount*100) / $tx,2);
			$tax = $item->amount - $amount;
		}
		else {
			$amount = $item->amount - $item->tax;
		 	$tax = $item->tax;
		}
	}

 	$returnurl = htmlentities($uri->root()."index.php?option=com_p2dxt&task=confirm");
 	$cancelurl = htmlentities($uri->toString());
 	
	$html = "";
	if ($introtext)
		$html .= $item->introtext;
	
	$popup = P2DPro::popupRequired($conf,$item); 

	if ($popup) {
			 	
		JHTML::_('behavior.modal');

		$link = 'index.php?option=com_p2dxt&amp;controller=popup&task=display&amp;p2did='.$id.'&amp;tmpl=component';
	 
		$plink = JUri::root().$link."\" class=\"modal\" rel=\"{handler: 'iframe', size: {x: $conf->popupx, y: $conf->popupy}}\" method=\"post\"";
	}

	$action = $paypal."/cgi-bin/webscr\" method=\"post\"";
		
 	if ($item->donation == "1")  {
 		$cmd="_donations";
 		$button = $conf->dbutton;
 		$tax= 0;
	}
 	else {
		$cmd="_xclick";
 		$button = $conf->button;
	}

	if (isset($item->button) && $item->button != "") $button = $item->button;
	
	// create the form
	$html .= "<form id=\"p2dform\" action=\"".$action." >";
	$html .= "<input type=\"hidden\" name=\"cmd\" value=\"".$cmd."\"/>";
	$html .= "<input type=\"hidden\" name=\"business\" value=\"".$business."\"/>";
	$html .= "<input type=\"hidden\" name=\"item_name\" value=\"".$itemName."\"/>";
	$html .= "<input type=\"hidden\" name=\"item_number\" value=\"".$id."\"/>";
	$html .= "<input type=\"hidden\" name=\"amount\" value=\"".$amount."\"/>";
	$html .= "<input type=\"hidden\" name=\"tax\" value=\"".$tax."\"/>";
	$html .= "<input type=\"hidden\" name=\"no_shipping\" value=\"".$item->shipping."\"/>";
	$html .= "<input type=\"hidden\" name=\"no_note\" value=\"1\"/>";
	$html .= "<input type=\"hidden\" name=\"return\" value=\"".$returnurl."\"/>";
	$html .= "<input type=\"hidden\" name=\"notify_url\" value=\"".$returnurl."\"/>";
	$html .= "<input type=\"hidden\" name=\"cancel_return\" value=\"".$cancelurl."\"/>";
	$html .= "<input type=\"hidden\" name=\"rm\" value=\"2\"/>";
	$html .= "<input type=\"hidden\" name=\"lc\" value=\"".$conf->locale."\"/>";
	$html .= "<input type=\"hidden\" name=\"pal\" value=\"D6MXR7SEX68LU\"/>";
	$html .= "<input type=\"hidden\" name=\"currency_code\" value=\"".strtoupper($item->currency)."\"/>";

	if (!$popup) {
		$html .= "<input type=\"image\" src=\"".$button."\" alt=\"Make payments with payPal - it's fast, free and secure!\"/>";
	}
	else  {
		$html .= "<a href=\"".$plink."\"><img src=\"".$button."\" alt=\"Make payments with payPal - it's fast, free and secure!\"/></a>";		
	}

	
	
    $html .= "<img alt=\"\" src=\"https://www.paypal.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\"/>";
	$html .= "</form>";
	
	$this->html = $html;
	}
	function display() {
		echo $this->html;
	}
	function getHTML() {
		return $this->html;
	}
}


?>