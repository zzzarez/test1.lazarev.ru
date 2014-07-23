<?php
/**
 * @copyright   Copyright (C) 2005 - 2009 RocketTheme, LLC - All Rights Reserved.
 * @license     GNU/GPL, see LICENSE.php
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
define( 'YOURBASEPATH', dirname(__FILE__) );


$color_style            = $this->params->get("colorStyle", "dark");
$template_width         = $this->params->get("templateWidth", "962");
$leftcolumn_width       = $this->params->get("leftcolumnWidth", "210");
$rightcolumn_width      = $this->params->get("rightcolumnWidth", "210");
$leftcolumn_color       = $this->params->get("leftcolumnColor", "color2");
$rightcolumn_color      = $this->params->get("rightcolumnColor", "color1");
$mootools_enabled       = ($this->params->get("mootools_enabled", 1)  == 0)?"false":"true";
$caption_enabled        = ($this->params->get("caption_enabled", 1)  == 0)?"false":"true";
$rockettheme_logo       = ($this->params->get("rocketthemeLogo", 1)  == 0)?"false":"true";


?>
<?php echo '<?xml version="1.0" encoding="utf-8"?' .'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{LANG_TAG}" lang="{LANG_TAG}" dir="{LANG_DIR}" >
<head>
<jdoc:include type="head" />
<?php
    require(YOURBASEPATH . DS . "rt_utils.php");
?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/lazarev/css/main.css" type="text/css" />

<!--[if lte IE 6]>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/js/ie_suckerfish.js"></script>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/css/styles.ie.css" type="text/css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/css/styles.ie7.css" type="text/css" />
<![endif]-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22794877-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>  

<div class="main radgrad">
    <div class="top radgrad">
        <jdoc:include type="modules" name="logo" style="none" />
    </div>
    <div class="leftmenu radgrad">
        <div class="movemenu">
            <jdoc:include type="modules" name="left" style="none" />
        </div>
    </div>
    <div class="center radgrad">
<?php
if(JRequest::getCmd( 'view' ) != 'frontpage')
{
?>
        <div class="mid">
            <jdoc:include type="modules" name="breadcrumb" />
        </div>
<?php
}
?>
        <div class="content">
<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>
            <p class="frontheader">Добро пожаловать на официальный сайт Сергея Николаевича Лазарева<br>
            Диагностика кармы</p>
<br><br>
            <b>Новости:</b><br>
            <jdoc:include type="modules" name="user1" style="afterburner" />
            <br><br><b>Встречи:</b><br>
            <jdoc:include type="modules" name="user2" style="afterburner" />
<?php
}
?>
            <jdoc:include type="component" />
        </div><!--end content-->
    </div><!--end center-->
    <div class="footer radgrad">
        Все права защищены 2011
    </div>
</div><!--end main-->

</body>
</html>  