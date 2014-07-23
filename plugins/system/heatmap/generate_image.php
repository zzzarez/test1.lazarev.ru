<?php
/**
* @version   $Id: generate_image.php 23 2011-08-12 22:01:05Z edo888@gmail.com $
* @package   Heat Map
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or define('_JEXEC', 1);

if($_POST['type'] == 'create_stat' and $_POST['date1'] != '' and $_POST['date2'] != '') {
    //includes configuration file
    include '../../../configuration.php';

    $config = new JConfig;

    //conects to datababse
    mysql_connect($config->host, $config->user, $config->password);
    mysql_select_db($config->db);
    mysql_query("SET NAMES utf8");

    $date1 = mysql_real_escape_string($_POST['date1']);
    $date2 = mysql_real_escape_string($_POST['date2']);

    // Including heatmap class
    include 'class/Heatmap.class.php';
    include 'class/HeatmapFromFile.class.php';

    $width = (int) $_POST['screen_w'];
    $height = (int) $_POST['screen_h'];


    $heatmap = new HeatmapFromFile();

    $heatmap->cache = '../../../cache';
    // Generated files
    $heatmap->path = '../../../cache';

    // Final file
    $img_secret = substr(md5($config->secret),5,10);
    $heatmap->file = 'heatmap-'.$img_secret.'-%d.png';

    //get total count of clicks
    $query = "SELECT COUNT(`id`) `count` FROM ".$config->dbprefix."heatmap WHERE `date` >= '$date1' AND `date` <= '$date2'";
    $res = mysql_query($query);
    $row = mysql_fetch_assoc($res);
    $count_total_clicks = $row['count'];

    //get count of visible clicks
    $query = "SELECT COUNT(`id`) `count` FROM ".$config->dbprefix."heatmap WHERE `screen` = '$width' AND  `y` <= '$height' AND `date` >= '$date1' AND `date` <= '$date2'";
    $res = mysql_query($query);
    $res = mysql_query($query);
    $row = mysql_fetch_assoc($res);
    $count_visible_clicks = $row['count'];

    //if total count is 0, set it to 1
    $count_total_clicks_devide = $count_total_clicks == 0 ? '1' : $count_total_clicks;
    $percent_vicible = ceil($count_visible_clicks * 100  / $count_total_clicks_devide);

    // Genetate clicks array
    $query = "SELECT * FROM ".$config->dbprefix."heatmap WHERE `screen` = '$width' AND `date` >= '$date1' AND `date` <= '$date2'";
    $result = mysql_query($query);
    $clicks = array();
    while($ckick_data = mysql_fetch_assoc($result)) {
        $clicks[] = array($ckick_data['x'], $ckick_data['y']);
    }

    $heatmap->clicks = $clicks;
    $image_width = $width;
    $image_height = $height;

    $images = $heatmap->generate($image_width, $image_height);

    $rand = rand(100000,999999);
    if($images === false) {
        $response_image_content = 'error: '.$heatmap->error;
    } else {
        for($i = 0; $i < 1; $i++) {
            $response_image_content = '<img src=\"cache/'. $images['filenames'][$i] . '?rand=' . $rand . '\" width=\"' . $images['width'] . '\" height=\"' . $images['height'] . '\" alt=\"\" />';
        }
    }

    $clicks_count_content = 'Current width : <span style=\"color:#8A0000\">' .$width.'px</span><br />Total clicks : <span title=\"Clicks on all resolutions\" style=\"color:#8A0000\">' . $count_total_clicks . '</span><br />Visible clicks : <span title=\"Clicks on '.$width.' x * resolutions\" style=\"color:#8A0000\">' .$count_visible_clicks. ' ('.$percent_vicible . '%)';

    //prints json object
    echo '["'.$response_image_content.'","'.$clicks_count_content.'"]';
}
?>