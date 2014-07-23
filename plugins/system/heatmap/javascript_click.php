<?php
/**
* @version   $Id: javascript_click.php 37 2011-09-20 21:53:58Z edo888@gmail.com $
* @package   Heat Map
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

//insert javascript

$base_url = JURI::base();

$heatmap_javascript = <<<EOM
<script type="text/javascript">
//<![CDATA[
function sendData(e) {
    var isIE = document.all?true:false;
    var pos_x, pos_y, screen_w;
    if (!isIE) {
        pos_x = e.pageX;
        pos_y = e.pageY;
    } if (isIE) {
        var left = document.documentElement.scrollLeft ?
                    document.documentElement.scrollLeft :
                        document.body.scrollLeft;
            pos_x = event.clientX + left;

            var top = document.documentElement.scrollTop ?
                      document.documentElement.scrollTop :
                      document.body.scrollTop;
            pos_y = event.clientY + top;
    }

    /*
     *
     * get window width
     *
     */

    var winW = 630, winH = 460;
    if(document.body && document.body.offsetWidth) {
        winW = document.body.offsetWidth;
        winH = document.body.offsetHeight;
    }
    if(document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth) {
        winW = document.documentElement.offsetWidth;
        winH = document.documentElement.offsetHeight;
    }
    if(window.innerWidth && window.innerHeight) {
        winW = window.innerWidth;
        winH = window.innerHeight;
    }
    screen_w = winW;

    /* sending request */
    var xmlhttp=false;
    if(!xmlhttp && typeof XMLHttpRequest!='undefined') {
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }
    if(!xmlhttp && window.createRequest) {
        try {
            xmlhttp = window.createRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }

    xmlhttp.onreadystatechange = processReqChange;
    xmlhttp.open('POST', "$base_url/plugins/system/heatmap/click_process.php", true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    sendString = "x="+pos_x+"&y="+pos_y+"&screen_w="+screen_w;
    xmlhttp.send(sendString);

    function processReqChange() {
        // only if req shows "loaded"
        if(xmlhttp.readyState == 4) {
            // only if "OK"
            if(xmlhttp.status == 200) {
                //var xmlResponse = xmlhttp.responseText;
            } else {
                // TODO: think what can be done here
                //alert("There was a problem retrieving the XML data: " + req.statusText);
            }
        }
    }
}

window.onload = function() {
    document.onclick = function(e) {sendData(e);}
}
//]]>
</script>
EOM;
?>