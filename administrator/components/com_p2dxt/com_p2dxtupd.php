<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');


$createTable[0]->tname = "p2dxt";
$createTable[0]->tstruct = "CREATE TABLE IF NOT EXISTS `#__p2dxt` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL DEFAULT '',
  `business` varchar(128) NOT NULL DEFAULT '',
  `locale` varchar(2) NOT NULL DEFAULT '',
  `testtoken` varchar(100) NOT NULL DEFAULT '',
  `testbusiness` varchar(128) NOT NULL DEFAULT '',
  `errortext` text NOT NULL,
  `thanktext` text NOT NULL,
  `version` varchar(7) NOT NULL,
  `button` varchar(255) NOT NULL,
  `dbutton` varchar(255) NOT NULL,
  `popupx` varchar(4) NOT NULL,
  `popupy` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
)";
$updatequery[0]->query = "INSERT INTO `#__p2dxt` (`id`, `token`, `business`, `locale`, `testtoken`, `testbusiness`, `errortext`, `thanktext`,`version`,`button`, `dbutton`,`popupx`, `popupy`) VALUES
(1,
 '', 
'', 
'', 
'', 
'', 
'<h1>Error</h1><p>An error occured while processing your request. This may have various reasons:</p><ul></br><li>The link to this site is not valid</li><li>you are trying to download a file without paying the fee via paypal.</li><li>You are trying to download a file multiple times. To avoid misuse it is only allowed to download files once. </li></ul><p>If you think this is a mistake, please contact us by mail providing us with your paypal transaction id.</p><p> </p>', 
'<p>Thanks [FIRST] for [PURCHASE]buying the[/PURCHASE][DONATION]donating for[/DONATION]  [ITEM]. The transaction was completed and you will receive an email confirming your purchase.</p><p>You can login to your account at <a href=\"https://www.paypal.com/\" target=\"_blank\">https://www.paypal.com/</a> to see transaction details.</p><p>Press the button below to start the download</p>',
'1.00.10', 
'http://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif', 
'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif', '800', '300');";
$updatequery[0]->upversion = "1.00.00";
$updatequery[0]->text = "Insert Config";
$createTable[1]->tname = "p2dxt_files";
$createTable[1]->tstruct = "CREATE TABLE `#__p2dxt_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `introtext` text NOT NULL,
  `filename` varchar(128) NOT NULL DEFAULT '',
  `itemname` varchar(128) NOT NULL DEFAULT '',
  `amount` varchar(10) NOT NULL DEFAULT '',
  `tax` varchar(10) NOT NULL DEFAULT '',
  `currency` varchar(3) NOT NULL DEFAULT '',
  `testmode` char(1) NOT NULL DEFAULT '',
  `url` varchar(1024) NOT NULL,
  `maxdown` varchar(3) NOT NULL,
  `shipping` varchar(1) NOT NULL,
  `productid` varchar(40) NOT NULL,
  `limitdown` varchar(9) NOT NULL,
  `donation` tinyint(1) NOT NULL,
  `minamount` varchar(10) NOT NULL,
  `taxcalc` tinyint(1) NOT NULL,
  `meta` text NOT NULL,
  PRIMARY KEY (`id`)
)";
$createTable[2]->tname = "p2dxt_tx";
$createTable[2]->tstruct = "CREATE TABLE `#__p2dxt_tx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tx` varchar(18) NOT NULL DEFAULT '',
  `payment_date` int(10) NOT NULL,
  `txn_type` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `residence_country` varchar(3) NOT NULL,
  `business` varchar(40) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payer_status` varchar(40) NOT NULL,
  `tax` varchar(10) NOT NULL,
  `payer_email` varchar(40) NOT NULL,
  `txn_id` varchar(40) NOT NULL,
  `quantity` varchar(10) NOT NULL,
  `receiver_email` varchar(40) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `payer_id` varchar(40) NOT NULL,
  `receiver_id` varchar(40) NOT NULL,
  `item_number` int(11) NOT NULL,
  `payment_status` varchar(40) NOT NULL,
  `mc_fee` varchar(10) NOT NULL,
  `mc_gross` varchar(10) NOT NULL,
  `dwnl` tinyint(11) NOT NULL,
  `testmode` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";
$updatequery[1]->query = "UPDATE `#__p2dxt` SET `version` = '1.00.14' WHERE `id` =1;";
$updatequery[1]->upversion = "9";
$updatequery[1]->text = "Set Version";
?>
