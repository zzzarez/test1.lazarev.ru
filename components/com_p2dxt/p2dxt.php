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
global $mainframe;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."main.p2dxt.php");

require_once(JPATH_SITE.DS."/components/com_p2dxt/helper.p2dxt.php");



$task		= JRequest::getVar('task', "init" );

$view		= JRequest::getVar('view', "default" );

$p2did		= JRequest::getVar('p2did', 0 );

$Itemid		= JRequest::getVar('Itemid', 0 );


$uri = JURI::getInstance();

$root = $uri->root();

// Require the base controller
if( $controller = JRequest::getWord('controller'))
{
	jimport('joomla.application.component.controller');
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	} else
	{
		$controller = '';
	}

	$classname    = 'p2dxtController'.$controller;
	$controller   = new $classname( );
	// Perform the Request task
	$controller->execute( JRequest::getVar( 'task' ) );
	// Redirect if set by the controller
	$controller->redirect();
	$task = "done";
}


$database = JFactory::getDBO();



$menu = JSite::getMenu();

$m = $menu->getActive();

if (isset($m)) {

	$params = new JParameter($m->params);

	$id = $params->get("p2did");

	if (!$p2did) $p2did=$id;

}




switch ($view) {

	case "prouser":

		P2DPro::user();

		return;
		break;

	case "proregister":

		P2DPro::register();

		return;
		break;
		
}



switch ($task) {

	case "init":

		initP2D($p2did);

		break;

	case "confirm":

		confirmP2D();

	break;

	case "cancel":

		cancelP2D(); 

	break;

	case "download":

		downloadP2D();

	break;

	case "error":

		errorP2D();

		break;

	case "registerList":

		P2DPro::registerList();

		break;

	case "registerSend":

		P2DPro::registerSend();

		break;

	case "activate":

		P2DPro::activate();

		break;
	break;

}



function errorP2D() {



	$database = JFactory::getDBO();

 	$query = "SELECT errortext, id FROM #__p2dxt WHERE id = '1'";

 	$database->setQuery($query);

	$msg = $database->loadResult(); 	

	echo $msg;

}



function cancelP2D() {

	echo JText::_("Payment was cancelled");

}



function downloadP2D() {

 		global $mainframe;

		$id = JRequest::getVar('p2did', 0 );

		$tx = JRequest::getVar('tx', 0 );



		$database = JFactory::getDBO();

		$txdb = new p2dxt_tx($database);



		$item = new p2dxt_files($database);

		$item->load($id);



		$query = "SELECT id FROM #__p2dxt_tx WHERE txn_id = '$tx' LIMIT 1";

		$database->setQuery($query);

		$txid = $database->loadResult();

		$txdb->load($txid);



		if (!$txdb->id) {
			$msg = JText::_("Transaction could not be found in database");
			$mainframe->redirect("index.php?option=com_p2dxt&task=error", $msg);

			return;	

		}

		

		$txdb->dwnl = $txdb->dwnl + 1;



		if ($txdb->dwnl>$item->maxdown) {
			$msg = JText::_("Maximum no. of downloads exceeded: ").$txdb->dwnl>$item->maxdown;
			$mainframe->redirect("index.php?option=com_p2dxt&task=error", $msg);

			return;	

		}



		$txdb->store();



 		

		$file = P2Dpro::getDataPath().DS.$item->filename;



        if(!file_exists($file)) {

			$file = $item->url;
			header( "Location: " . $item->url );
			return;

		}



		$path_parts = pathinfo($file);

		$file_extension = $path_parts['extension'];

		

        header('Content-Description: File Transfer');

        header('Content-Type: '.setCType($file_extension).'');

        header('Content-Disposition: attachment; filename='.basename($file));

        header('Content-Transfer-Encoding: binary');

        header('Expires: 0');

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    	header('Pragma: public');

        header('Content-Length: ' . filesize($file));

        ob_clean();

        flush();

        ob_end_flush();

        error_reporting(0);
        if (!@readfile_chunked($file)) {
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$file);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($ch);
			if($res === false)
			{
			    echo 'Curl-Error: ' . curl_error($ch);
			}
			curl_close($ch);
			echo $res;
		}

        exit;

			 



}



function initP2D($id) {



  	$my = JFactory::getUser();



	switch (P2DPro::check($id)) {

		case "upgrade":

			return JError::raiseWarning(E_ERROR, JText::_('This item is for upgrades only'));	

			break;

		case "user":

			return JError::raiseWarning(E_ERROR, JText::_('You have to be logged in to use this function'));

			break;

	}

	

	

	$btn = new p2dxtButton($id);

	$btn->display();

}



function confirmP2D() {

global $mainframe;



	$tx = JRequest::getVar('tx', 0 );

	$id = JRequest::getVar('item_number', 0 );



	$database = JFactory::getDBO();



	$item = new p2dxt_files($database);
	$item->load($id);

	$val = validateP2D($id);
//	$val->success= "S";	

	if ($val->success == "E") {
	 	$mainframe->redirect("index.php?option=com_p2dxt&task=error", $val->msg);
		return;	
	}

 	$html = "";

	$query = "SELECT * FROM #__p2dxt_tx WHERE txn_id = '".$tx."'";
	$database->setQuery($query);
	$txdb = $database->loadObject();


	if ($val->success != "S") {
	 	$query = "SELECT pendingtext,id FROM #__p2dxt WHERE id = '1'";
	 	$database->setQuery($query);
		$html .= P2Dpro::parseText($database->loadResult(), $item, $txdb);
	}
	else {
		$query = "SELECT thanktext,id FROM #__p2dxt WHERE id = '1'";
	 	$database->setQuery($query);
	 	
		$html .= P2Dpro::parseText($database->loadResult(), $item, $txdb);
		
		$url = JRoute::_(JUri::base().'index.php?option=com_p2dxt&task=download&tmpl=component&tx='.$tx.'&p2did='.$id);

		if (P2DPro::showButton() && (file_exists(P2Dpro::getDataPath().DS.$item->filename) || (!empty($item->url)))) {
			$html .= "<div id='p2dxtbutton'><input class=\"button\" type=\"button\" onclick=\"downloadP2D();document.location.href='".$url."'\" value=\"Download\"></div>";
	
			$html .= "<div id='p2dxtText' style='display:none'><br/>".JText::_("DOWNLOAD INITIATED")."</div>";
	
		}
		if (P2DPro::autoDownload() && (file_exists(P2Dpro::getDataPath().DS.$item->filename)  || (!empty($item->url)))) {
			$script = "window.onload=window.open(\"".$url."\", \"_blank\");";
			$document =& JFactory::getDocument();
			$document->addScriptDeclaration($script);
		}
		
		
		$html .= P2DPro::showArticles($item);
		
	}


	$html .= P2DPro::footer();	
	P2DPro::plgOnAfterPayment($txdb->id);
	echo $html;

	
	
	if ($val->success == "S") P2DPro::sendMail($item, $tx, $url);

}



function validateP2D($id) {



	$database = JFactory::getDBO();



	$item = new p2dxt_files($database);

	$item->load($id);

	

	$conf = new p2dxt($database);

	$conf->load("1");

	

	$msg = "";

	$success = "S";

		 	

	if ($item->testmode == "1") {

	 	$auth_token = $conf->testtoken;

	 	$paypal = "www.sandbox.paypal.com";

	}

	else {

	 	$auth_token = $conf->token;

	 	$paypal = "www.paypal.com";

	}



	

	$req = 'cmd=_notify-synch';



	$tx_token = JRequest::getVar('tx', 0 );

	$req .= "&tx=".urlencode($tx_token)."&at=".urlencode($auth_token);



	// post back to PayPal system to validate

	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";

	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Host: " . $paypal . "\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	$fp = fsockopen ($paypal, 80, $errno, $errstr, 30);

	

	

	if (!$fp || $errno) {

 // HTTP ERROR -> try CURL

	$ch = curl_init();



	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

	curl_setopt($ch,CURLOPT_URL,'https://'.$paypal.'/cgi-bin/webscr');

	curl_setopt($ch,CURLOPT_POST,1);

	curl_setopt($ch,CURLOPT_POSTFIELDS,$req);







	ob_start();

	curl_exec($ch);

	$res=ob_get_contents();

	curl_close($ch);

	ob_end_clean();



	} else {

		fputs ($fp, $header . $req);

// read the body data

	$res = '';

	$headerdone = false;

	while (!feof($fp)) {

		$line = fgets ($fp, 1024);

		if (strcmp($line, "\r\n") == 0) {

		// read the header

		$headerdone = true;

		}

		else if ($headerdone)

		{

			// header has been read. now read the contents

			$res .= $line;

		}

	}	

	fclose ($fp);

	}

	// parse the data



	$lines = explode("\n", $res);

	$keyarray = array();

	if (strcmp ($lines[0], "SUCCESS") == 0) {

		for ($i=1; $i<count($lines);$i++){

		 	if ($lines[$i] != "") {

			list($key,$val) = explode("=", $lines[$i]);

			$keyarray[urldecode($key)] = urldecode($val);

			}

		}

	// check that txn_id has not been previously processed

	// check that receiver_email is your Primary PayPal email

	// check that payment_amount/payment_currency are correct
	$cheat = false;
	//calculate tax if required
 	if ($item->taxcalc==1) {
		$tax = ($item->tax * $item->amount) / 100;
	}
	else 
	 	$tax = $item->tax;
	
	
	// fixed amount
	if (($item->donation == "0")&& (($keyarray['mc_gross'] != ($item->amount+$tax) || $keyarray['mc_currency'] != strtoupper($item->currency)))) {
	 $cheat = true;
	  echo $keyarray['mc_gross']." not ".($item->amount+$item->tax);die;
	 }
	//donation
	if (($item->donation == "1")&& (($keyarray['mc_gross'] < $item->amount) || $keyarray['mc_currency'] != strtoupper($item->currency))) $cheat = true;
	// minimum (+tax)
	if (($item->donation == "2")&& (($keyarray['mc_gross'] < ($item->amount) || $keyarray['mc_currency'] != strtoupper($item->currency)))) $cheat = true;



	if ($cheat)
 	{

		$success = "E";

		$msg = JText::_("Payment was not correct (amount/currency)");

		$err->success = $success;

		$err->msg = $msg;

		return $err;

	}

	// update the database

	$tx = new p2dxt_tx($database);

	$query = "SELECT id FROM #__p2dxt_tx WHERE txn_id = '$tx_token' LIMIT 1";

	$database->setQuery($query);

	$txid = $database->loadResult();

	$tx->load($txid);

	if (!$tx->id) {

		$keyarray["dwnl"] = "0";

		$keyarray["testmode"] = intval($item->testmode);

		$keyarray["payment_date"] = strtotime($keyarray["payment_date"]);

		$my = JFactory::getUser();

		if (isset($my->id) && $my->id != 0) {

			$keyarray["userid"] = $my->id;

			$keyarray["actstatus"] = "A";

		}

		if (!$tx->bind($keyarray)) {

			echo $tx->getError();

		}

		$tx->ip = $_SERVER['REMOTE_ADDR'];

		if (!$tx->store()) {

			echo $tx->getError();

		}

	// check the payment_status is Completed
		if (P2Dpro::onlyCompletedStatus($conf) == "1" && $keyarray["payment_status"] != "Completed") {
			$success = "W";
			$msg = JText::_("Payment was not yet completed.");
		}
		else {
			P2Dpro::plgOnAfterRegistration($tx->id);
			$success = "S";
		}
	}

	else {

		$success = "E";

		$msg = JText::_("This transaction was already processed");

	}

	}

	else if (strcmp ($lines[0], "FAIL") == 0) {

		$success = "E";	

		$msg = JText::_("Verification failed");



	}

	else {

		$success = "E";	

		$msg = JText::_("Verification did not return any values");

		

	}



	$err->success = $success;

	$err->msg = $msg;

	return $err;

}



	function setCtype ($file_extension) {

		//This will set the Content-Type to the appropriate setting for the file

		switch( $file_extension ) {

			 case "pdf": $ctype="application/pdf"; break;

		     case "exe": $ctype="application/octet-stream"; break;

		     case "zip": $ctype="application/zip"; break;

		     case "doc": $ctype="application/msword"; break;

		     case "xls": $ctype="application/vnd.ms-excel"; break;

		     case "ppt": $ctype="application/vnd.ms-powerpoint"; break;

		     case "gif": $ctype="image/gif"; break;

		     case "png": $ctype="image/png"; break;

		     case "jpeg":

		     case "jpg": $ctype="image/jpg"; break;

		     case "mp3": $ctype="audio/mpeg"; break;

		     case "wav": $ctype="audio/x-wav"; break;

		     case "mpeg":

		     case "mpg":

		     case "mpe": $ctype="video/mpeg"; break;

		     case "mov": $ctype="video/quicktime"; break;

		     case "avi": $ctype="video/x-msvideo"; break;



		     //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)

		     case "php":

		     case "htm":

		     case "html": if ($downpath) die("<b>Cannot be used for ". $file_extension ." files!</b>");



		     default: $ctype="application/force-download";

		}

		return $ctype;

	}
	
    function readfile_chunked($filename,$retbytes=true)
    {
   		$chunksize = 1*(1024*1024); // how many bytes per chunk
   		$buffer = '';
   		$cnt =0;
   		$handle = fopen($filename, 'rb');
   		if ($handle === false) {
       		return false;
   		}
   		while (!feof($handle)) {
       		$buffer = fread($handle, $chunksize);
       		echo $buffer;
			@ob_flush();
			flush();
       		if ($retbytes) {
           		$cnt += strlen($buffer);
       		}
   		}
       $status = fclose($handle);
   	   if ($retbytes && $status) {
       		return $cnt; // return num. bytes delivered like readfile() does.
   		}
   		return $status;
	}
	