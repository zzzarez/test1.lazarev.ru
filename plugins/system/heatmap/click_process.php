<?php
/**
* @version   $Id: click_process.php 13 2011-08-12 21:20:35Z simonpoghosyan@gmail.com $
* @package   Heat Map
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/


$click_x = (int)$_POST['x'];
$click_y = (int)$_POST['y'];
$screen_w = (int)$_POST['screen_w'];

if($click_x != 0 && $click_y != 0 && $screen_w != 0) {

	//includes configuration file
	include '../../../configuration.php';
	
	$config = new JConfig;
	
	//conects to datababse
	mysql_connect($config->host, $config->user, $config->password);
	mysql_select_db($config->db);
	mysql_query("SET NAMES utf8");
	
    // Create clicks data table
    $query_create_table = "CREATE TABLE IF NOT EXISTS `".$config->dbprefix."heatmap` (
        `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `x` MEDIUMINT UNSIGNED NOT NULL ,
        `y` MEDIUMINT UNSIGNED NOT NULL ,
        `screen` MEDIUMINT UNSIGNED NOT NULL ,
        `date` DATE NOT NULL ,
        `url` TEXT NOT NULL
        ) ENGINE = MYISAM";
   mysql_query($query_create_table);

    $click_url = $_SERVER['HTTP_REFERER'];

    // Insert click data
    $query_insert = "INSERT INTO ".$config->dbprefix."heatmap (`x`, `y`, `screen`, `date`, `url`) VALUES ('$click_x', '$click_y', '$screen_w', NOW(), '$click_url')";
	mysql_query($query_insert);
}
?>