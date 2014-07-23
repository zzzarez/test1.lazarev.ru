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
<!--<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/js/ie_suckerfish.js"></script>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/css/styles.ie.css" type="text/css" />-->
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/rt_afterburner_j15/css/styles.ie7.css" type="text/css" />
<![endif]-->

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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

<script>
  $(document).ready(function(){

   $("li:first").css('background',' url("/images/stories/menu_button.png") no-repeat');

	$("li:contains('item')").hover(
	  function () {
	    $(this).fadeTo('normal', 0.5);
	  },
	  function () {
	    $(this).fadeTo('normal', 1);
	  }
	);
  });
</script>

</head>
<body align="center">  

<div class="main radgrad">

    <div class="top" align="center">
        <jdoc:include type="modules" name="logo" style="none" />
    </div>

<div class="after_main">


<div class="movemenu">
    <div class="leftmenu radgrad" id="lmenu">
       <!-- <div class="movemenu">-->
            <jdoc:include type="modules" name="left" style="none" />
        <!--</div>-->

    </div>
    <div class="specialvideo radgrad">
        <a href="/index.php?option=com_content&view=section&layout=blog&id=12&Itemid=55">
            Рекомендованно к просмотру
        </a>
    </div>
    <!--
    <div class="cool_inf radgrad">
        <a href="/index.php?option=com_content&view=section&layout=blog&id=9&Itemid=49">
            Полезная информация
        </a>
    </div>-->
	<div class="voting radgrad">
		<jdoc:include type="modules" name="user4" style="afterburner" />
	</div>
    <div class="site_map radgrad">
        <a href="/index.php?option=com_xmap&sitemap=1&Itemid=37">
            Карта сайта
        </a>
    </div>


</div>
    <div class="center radgrad">

            <?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>
			<jdoc:include type="modules" name="top" style="afterburner" />
<?php }?>

<?php
if(JRequest::getCmd( 'view' ) != 'frontpage')
{
?>
        <div class="mid green_header">
            <jdoc:include type="modules" name="breadcrumb" />
        </div>
<?php
}
?>                <!--
            <p class="frontheader">Добро пожаловать на официальный сайт Сергея Николаевича Лазарева<br>
            Диагностика кармы</p>
<br><br>           -->
		<?php
		//echo "!!!!!!!!!".JRequest::getCmd('view').JRequest::getCmd('id');
		if (JRequest::getCmd('option') == "com_content" && JRequest::getCmd('view') == "section" && JRequest::getCmd('id') == "8"){
		 ?>
        	<div class="content radgradwhite">
            	<jdoc:include type="modules" name="user9" style="afterburner" />
			</div>
			</br>
			<div class="green_header">
				Диагностика кармы
			</div>

        	<div class="content radgradwhite">
            	<jdoc:include type="modules" name="user6" style="afterburner" />
			</div>
			</br>
			<div class="green_header">
				Человек будущего
			</div>

        	<div class="content radgradwhite">
            	<jdoc:include type="modules" name="user7" style="afterburner" />
			</div>
			</br>
			<div class="green_header">
				Диагностика кармы(часть два)
			</div>

        	<div class="content radgradwhite">
            	<jdoc:include type="modules" name="user8" style="afterburner" />
			</div>

		<?php
		}
		?>

<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>

<br><br>

			<div class="green_header">
				<a href="/index.php?option=com_content&view=article&id=65&Itemid=50">Об авторе</a>
			</div>
			<div class="about" id="aboutid">
<p>Мало кто из наших современников смог так близко подойти к пониманию психологии человека и причин его болезней и проблем, как это сделал Сергей Николаевич Лазарев. 

Сергей Лазарев известен миллионам жителей России и ближнего зарубежья, как автор серии книг  'Диагностика кармы' и 'Человек будущего'. На сегодня вышло в свет в сумме двадцать пять книг. Проведено несколько десятков встреч с читателями.

Книги Лазарева – это изложение его личного, более чем двадцатилетнего опыта исследований в области психологии и экстрасенсорики.

Но Лазарева нельзя назвать 'психологом' в привычном понимании этого слова. В современной психологии отсутствует такое ключевое понятие, как 'любовь к Богу', а концепция Лазарева без этого понятия просто немыслима.

Нельзя назвать Лазарева также и 'экстрасенсом' в обыденном понимании, хотя в наличии у него уникальных экстрасенсорных способностей не приходится сомневаться.
</p>				<br>
			<div align="right">
			 <a HREF="/index.php?option=com_content&view=article&id=65&Itemid=50" >Узнать больше</a>
			</div>
			</div>

			<div class="green_header">
				<a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=17">Информация от С.Н. Лазарева</a>
			</div>
        	<div class="content radgradwhite">
            	<jdoc:include type="modules" name="user5" style="afterburner" />
			</div>

<br>
			<div class="green_header">
				<a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Мировые новости</a>
			</div>
<?php
}
?>
		<?php
		if (JRequest::getCmd('option') == "com_content" && JRequest::getCmd('view') == "article" && JRequest::getCmd('id') == "65"){
			$cl_name = "about_back";
		 }
		 ?>


        <div class="content radgradwhite <?php echo $cl_name; ?>">
<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>
            <jdoc:include type="modules" name="user1" style="afterburner" />
			<DIV ALIGN="right">
            	<a href="<?php echo $this->baseurl ?>index.php?option=com_content&view=archive">Архив новостей</a>&nbsp;&nbsp;       
			</div>
<?php
}
?>
       <jdoc:include type="component" />    
        </div><!--end content-->

<br>


    </div><!--end center-->
    <div class="footer radgrad">
        Все права защищены 2011
    </div>

</div>
</div><!--end main-->

</body>
</html>  