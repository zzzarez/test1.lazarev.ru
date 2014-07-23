<?php
/**
* @version   $Id: create_request.php 40 2012-03-20 20:35:59Z simonpoghosyan@gmail.com $
* @package   Heat Map
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

$base_url = JURI::base();

$heatmap = JRequest::getVar('heatmap', '', 'get', 'int');

if($heatmap == 1) {

    global $mainframe;

    // Check if the user is admin
    $user =& JFactory::getUser();
    if($user->usertype != "Super Administrator" and $user->usertype != "Administrator") {
        $return = JURI::base() . 'index.php?option=com_user&view=login';
        $return .= '&return=' . base64_encode(JURI::base() . 'index.php?' . JURI::getInstance()->getQuery());
        $mainframe->redirect($return);
    }

    /**
     * Converts date to mysql format, ex. YYYYMMDD -> YYYY-MM-DD
     *
     * @param int $date the date to convert
     * @return string The converted date
    */
    function create_date($date) {
        if(strlen($date) == 8)
            return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
        return '';
    }

    $date1 = create_date(JRequest::getVar('date1', '', 'get', 'int'));
    $date2 = create_date(JRequest::getVar('date2', '', 'get', 'int'));

    // if one of dates is missing, sets them equal
    if($date1 == '' and $date2 != '')
        $date1 = $date2;
    if($date2 == '' and $date1 != '')
        $date2 = $date1;

    // if something wrong, show clicks from last week
    if(($date1 == '' and $date2 == '') or (strtotime($date2) < strtotime($date1))) {
        $date1 = date('Y-m-d',strtotime('-7 days'));
        $date2 = date('Y-m-d',strtotime('now'));
    }

    $date1_pre = date('d.m.Y',strtotime($date1));
    $date2_pre = date('d.m.Y',strtotime($date2));

    //get difference between two dates
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    //define date classes
    $year_class = ($years == 1 && $months == 0 && $days < 5) ? 'selected' : '';
    $month_class = ($years == 0 && $months == 1 && $days < 5) ? 'selected' : '';
    $quarter_class = ($years == 0 && $months == 3 && $days < 5) ? 'selected' : '';
    $week_class = ($years == 0 && $months == 0 && $days == 7) ? 'selected' : '';
    $yesterday_class = ($years == 0 && $months == 0 && $days == 0 && ($date1 != date('Y-m-d',strtotime('now')))) ? 'selected' : '';
    $today_class = ($years == 0 && $months == 0 && $days == 0 && ($date1 == date('Y-m-d',strtotime('now')))) ? 'selected' : '';

    //define dates, to use in the forms latter
    $date_today = date('Ymd',strtotime('now'));
    $date_yesterday = date('Ymd',strtotime('-1 days'));
    $date_week = date('Ymd',strtotime('-1 week'));
    $date_month = date('Ymd',strtotime('-1 months'));
    $date_quarter = date('Ymd',strtotime('-3 months'));
    $date_year = date('Ymd',strtotime('-1 years'));

    $heatmap_stat = <<<EOM
<style type="text/css">#clickmap_container {margin:0;padding:0;position:absolute;left:0;top:0;opacity:0.8;filter:alpha(opacity=80);width:100%;min-height:100%;botom:0;background-color:#ededed;overflow:hidden;z-index:9999;list-style:none;line-height:normal;}</style>
<style type="text/css">#heatmap_hidden{z-index: 10000;width:250px;height:20px;background-color:#A8ABBB;position:fixed;bottom:0;left:0;border:1px solid #424557;opacity:0.9;filter:alpha(opacity=90)}#heatmap_close_hidden{position:absolute;right:0px;top:0px;color:#32395B;cursor:pointer;height:20px;width:35px;font-size:12px;padding:1px 0 0 0;margin:0}#heatmap_show{position:absolute;right:38px;top:0px;color:#32395B;cursor:pointer;height:20px;width:35px;font-size:12px;padding:1px 0 0 0;margin:0}#heatmap_close_hidden:HOVER{text-decoration:underline;font-weight:bold;font-style:italic}#heatmap_show:HOVER{text-decoration:underline;font-weight:bold;font-style:italic}.heatmap_link{position:absolute;display:block;color:#B60000 !important;text-decoration:underline  !important;left:5px  !important;top:0  !important;font-size:14px  !important}.heatmap_link:hover{text-decoration:none  !important;color:#800  !important;font-style:italic}#heatmap_bottom_menu{width:100%;height:85px;background-color:#A8ABBB;position:fixed;bottom:0;left:0;border:1px solid #424557;z-index:10000;overflow:auto;}#heatmap_top_banner{background-color:#CED1E0;height:20px;border-bottom:1px solid #676B7E;position:relative}#heatmap_close{position:absolute;right:10px;top:0px;color:#32395B;cursor:pointer;height:20px;width:35px;font-size:12px;padding:1px 0 0 0;margin:0}#heatmap_hide{position:absolute;right:40px;top:0px;color:#32395B;cursor:pointer;height:20px;width:35px;font-size:12px;padding:1px 0 0 0;margin:0}#heatmap_close:HOVER{text-decoration:underline;font-weight:bold;font-style:italic}#heatmap_hide:HOVER{text-decoration:underline;font-weight:bold;font-style:italic}#heatmap_dates{float:left;margin:5px 0 0 20px;width:380px}#heatmap_dates input[type=text]{width:105px;color:#32395B;background-color:#D6DEED;text-align:center;border:1px solid #23375C}#view_heatmap{cursor:pointer;background-color:#FFE1B7 !important;border:1px solid #23375C !important}#heatmap_dates input[type=text]:hover{background-color:#F7F8DE}#heatmap_dates input[type=text]:focus{background-color:#F7F8DE}#view_heatmap:hover{background-color:#FFBEA7 !important}.heatmap_range.selected{font-weight:bold !important;color:#222222}.heatmap_range{float:left;display:block;margin:5px;font-size:14px;color: #23375C;cursor:pointer}.heatmap_range:HOVER{color:#480000}#heatmap_clicks{font-size:10px;min-width:165px;float:right;margin:7px 25px 0 0;padding:0 5px;background-color:#FCE8E8;border:1px solid #D07878;color:#222222;font-weight:bold;text-align:center;height:48px;}</style>
<div id="clickmap_container"></div>

<div id="heatmap_bottom_menu">
    <div id="heatmap_top_banner">
        <div id="heatmap_close" title="close heatmap">close</div>
        <div id="heatmap_hide" title="hide menu">hide</div>
        <a class="heatmap_link" title="Joomla! Heatmap" target="_blank" href="http://2glux.com/projects/heatmap">Joomla! Heatmap</a>
    </div>
    <div>
        <div id="heatmap_dates">
            <input type="text" id="date1_pre" value="$date1_pre" /> -
            <input type="text" id="date2_pre" value="$date2_pre" />
            <input type="text"  id="view_heatmap" value="View" title="View" />
            <br />
            <div class="heatmap_range $today_class" id="heatmap_today">Today</div>
            <div class="heatmap_range $yesterday_class" id="heatmap_yesterday">Yesterday</div>
            <div class="heatmap_range $week_class" id="heatmap_week">Week</div>
            <div class="heatmap_range $month_class" id="heatmap_month">Month</div>
            <div class="heatmap_range $quarter_class" id="heatmap_quarter">Quarter</div>
            <div class="heatmap_range $year_class" id="heatmap_year">Year</div>

            <form action="" method="get" id="heatmap_dates_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="" id="date1" />
                <input type="hidden" name="date2" value="" id="date2" />
            </form>

            <form action="" method="get" id="heatmap_today_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_today"  />
                <input type="hidden" name="date2" value="$date_today"  />
            </form>
            <form action="" method="get" id="heatmap_yesterday_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_yesterday"  />
                <input type="hidden" name="date2" value="$date_yesterday"  />
            </form>
            <form action="" method="get" id="heatmap_week_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_week"  />
                <input type="hidden" name="date2" value="$date_today"  />
            </form>
            <form action="" method="get" id="heatmap_month_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_month"  />
                <input type="hidden" name="date2" value="$date_today"  />
            </form>
            <form action="" method="get" id="heatmap_quarter_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_quarter"  />
                <input type="hidden" name="date2" value="$date_today"  />
            </form>
            <form action="" method="get" id="heatmap_year_form" >
                <input type="hidden" name="heatmap" value="1" />
                <input type="hidden" name="date1" value="$date_year"  />
                <input type="hidden" name="date2" value="$date_today"  />
            </form>
        </div>
        <div id="heatmap_clicks"></div>
    </div>
</div>

<div id="heatmap_hidden" style="display: none;">
    <div id="heatmap_close_hidden" title="close heatmap">close</div>
    <div id="heatmap_show" title="show menu">show</div>
    <a class="heatmap_link" title="Joomla! Heatmap" target="_blank" href="http://edo.webmaster.am/projects">Joomla! Heatmap</a>
</div>
<script type="text/javascript">
//<![CDATA[
function getPageSize() {

    var xScroll, yScroll;

    if (window.innerHeight && window.scrollMaxY) {
        xScroll = window.innerWidth + window.scrollMaxX;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }

    var windowWidth, windowHeight;

    if (self.innerHeight) { // all except Explorer
        if(document.documentElement.clientWidth){
            windowWidth = document.documentElement.clientWidth;
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }

    // for small pages with total height less then height of the viewport
    if(yScroll < windowHeight){
        pageHeight = windowHeight;
    } else {
        pageHeight = yScroll;
    }

    // for small pages with total width less then width of the viewport
    if(xScroll < windowWidth){
        pageWidth = xScroll;
    } else {
        pageWidth = windowWidth;
    }

    return [pageWidth,pageHeight];
}


window.addEvent('domready', function() {

/* get screen width */
var winW = 630, winH = 460;
if (document.body && document.body.offsetWidth) {
    winW = document.body.offsetWidth;
    winH = document.body.offsetHeight;
}
if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) {
    winW = document.documentElement.offsetWidth;
    winH = document.documentElement.offsetHeight;
}
if (window.innerWidth && window.innerHeight) {
    winW = window.innerWidth;
    winH = window.innerHeight;
}

var screen_w = winW;
var screen_h_ = getPageSize();
var screen_h = screen_h_[1];

/* sending request */
var xmlhttp = false;
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    try {
        xmlhttp = new XMLHttpRequest();
    } catch (e) {
        xmlhttp = false;
    }
}

if (!xmlhttp && window.createRequest) {
    try {
        xmlhttp = window.createRequest();
    } catch (e) {
        xmlhttp = false;
    }
}

/* */

var openUrl = '$base_url/plugins/system/heatmap/generate_image.php';
xmlhttp.onreadystatechange = processReqChange;
xmlhttp.open('POST', openUrl, true);
xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
sendString = "type=create_stat&screen_w="+screen_w+"&screen_h="+screen_h+"&date1=$date1&date2=$date2";
xmlhttp.send(sendString);

function processReqChange() {
    // only if req shows "loaded"
    if(xmlhttp.readyState == 4) {
        // only if "OK"
        if(xmlhttp.status == 200) {
            try {
                var xmlResponse = eval('(' + xmlhttp.responseText + ')');
            } catch(e) {
                return;
            }

            document.getElementById('clickmap_container').innerHTML = xmlResponse[0];
            document.getElementById('heatmap_clicks').innerHTML = xmlResponse[1];
        } else {
            // TODO: think what can be done here
            // alert("There was a problem retrieving the XML data: " + req.statusText);
        }
    }
}

//bottom menu script

    document.getElementById('heatmap_close').onclick = function() {
        document.getElementById('heatmap_bottom_menu').style.display = 'none';
        document.getElementById('clickmap_container').style.display = 'none';
    }
    document.getElementById('heatmap_close_hidden').onclick = function() {
        document.getElementById('heatmap_hidden').style.display = 'none';
        document.getElementById('clickmap_container').style.display = 'none';
    }

    document.getElementById('heatmap_hide').onclick = function() {
        document.getElementById('heatmap_bottom_menu').style.display = 'none';
        document.getElementById('heatmap_hidden').style.display = '';
    }
    document.getElementById('heatmap_show').onclick = function() {
        document.getElementById('heatmap_bottom_menu').style.display = '';
        document.getElementById('heatmap_hidden').style.display = 'none';
    }


    document.getElementById('view_heatmap').onclick = function() {
        var d1 = document.getElementById('date1_pre').value;
        var d2 = document.getElementById('date2_pre').value;

        var reg1=/^\d{2}\.\d{2}\.\d{4}$/;
        var res1 = reg1.test(d1);

        var reg1=/^\d{2}\.\d{2}\.\d{4}$/;
        var res2 = reg1.test(d2);
        if(res1 && res2) {
            var new_d1 = d1.replace(/^(\d{2})\.(\d{2})\.(\d{4})$/, "$3$2$1");
            var new_d2 = d2.replace(/^(\d{2})\.(\d{2})\.(\d{4})$/, "$3$2$1");
            document.getElementById('date1').value = new_d1;
            document.getElementById('date2').value = new_d2;
            document.forms["heatmap_dates_form"].submit();
        }
        else {
            alert('Please specify dates in dd.mm.YYYY format');
            return false;
        }
    }

    document.getElementById('heatmap_today').onclick = function() {
        document.forms["heatmap_today_form"].submit();
    }
    document.getElementById('heatmap_yesterday').onclick = function() {
        document.forms["heatmap_yesterday_form"].submit();
    }
    document.getElementById('heatmap_week').onclick = function() {
        document.forms["heatmap_week_form"].submit();
    }
    document.getElementById('heatmap_month').onclick = function() {
        document.forms["heatmap_month_form"].submit();
    }
    document.getElementById('heatmap_quarter').onclick = function() {
        document.forms["heatmap_quarter_form"].submit();
    }
    document.getElementById('heatmap_year').onclick = function() {
        document.forms["heatmap_year_form"].submit();
    }

})
//]]>
</script>
<noscript>You need to enable JavaScript to see the <a href="http://edo.webmaster.am/projects" title="Joomla heatmap">Joomla heatmap</a></noscript>
EOM;

}
?>