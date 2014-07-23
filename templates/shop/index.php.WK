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
<!--[if IE]>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/lazarev/css/ie.css" type="text/css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/lazarev/css/ie9.css" type="text/css" />
<meta content="IE=8" http-equiv="X-UA-Compatible" /> 
<![endif]-->
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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
<div class="main">
    <div class="top" align="center">
        <jdoc:include type="modules" name="logo" style="none" />
    </div>
<div class="after_main">
<div class="movemenu">
    <div class="leftmenu radgrad" id="lmenu">
            <jdoc:include type="modules" name="left" style="none" />
    </div>
    <div class="specialvideo radgrad">
        <a href="/index.php?option=com_content&view=section&layout=blog&id=12&Itemid=55">
            Рекомендовано к просмотру
        </a>
    </div>
	<div class="voting radgrad">
		<jdoc:include type="modules" name="user4" style="afterburner" />
	</div>
    <div class="specialvideo radgrad">
	<a href="http://www.lazarev.ru" title="Russia">
		<img border="1" src="images/flags/russia.gif">
	</a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.diagnostykakarmy.pl" title="Poland">
		<img border="1" src="images/flags/poland.gif">
	</a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.lazarevsn.com" title="Germany">
		<img border="1" src="images/flags/germany.gif">
	</a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.diagnostika-karmy.cz" title="Czech Republic">
		<img border="1" src="images/flags/czech.gif">
	</a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.lazarev.es" title="Spain">
		<img width="20" height="10" border="1" src="images/flags/spain.gif">
	</a>
    </div>
    <div class="specialvideo radgrad">
        <a href="http://lazarev.org">
            Лазарев.рф
        </a>
    </div>

    <div class="site_map radgrad">
        <a href="/index.php?option=com_xmap&sitemap=1&Itemid=37">
            Карта сайта
        </a>
    </div>
<div class="counters">

<!--Rating@Mail.ru COUNTEr--><script language="JavaScript" type="text/javascript"><!--
d=document;var a='';a+=';r='+escape(d.referrer)
js=10//--></script><script language="JavaScript1.1" type="text/javascript"><!--
a+=';j='+navigator.javaEnabled()
js=11//--></script><script language="JavaScript1.2" type="text/javascript"><!--
s=screen;a+=';s='+s.width+'*'+s.height
a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth)
js=12//--></script><script language="JavaScript1.3" type="text/javascript"><!--
js=13//--></script><script language="JavaScript" type="text/javascript"><!--
d.write('<a href="http://top.mail.ru/jump?from=96784"'+
' target=_top><img src="http://da.c7.b1.a0.top.list.ru/counter'+
'?id=96784;t=138;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru"'+' border=0 height=40 width=88/><\/a>')
if(11<js)d.write('<'+'!-- ')//--></script><noscript>
<a
target=_top href="http://top.mail.ru/jump?from=96784"><img
src="http://da.c7.b1.a0.top.list.ru/counter?js=na;id=96784;t=138"
border=0 height=40 width=88
alt="Рейтинг@Mail.ru"/></a></noscript><script language="JavaScript" type="text/javascript"><!--
if(11<js)d.write('--'+'>')//--></script><!--/COUNTER-->
</div>
</div>
    <div class="center">
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
?>                



<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>
<br><br>
			<div class="green_header">
				<a href="/index.php?option=com_content&view=article&id=65&Itemid=50">Об авторе</a>
			</div>
			<div class="wrapper_about">
				<div class="about" id="aboutid">
					<p>Мало кто из наших современников смог так близко подойти к пониманию психологии человека и причин его болезней и проблем, как это сделал Сергей Николаевич Лазарев. 
					Сергей Лазарев известен миллионам жителей России и ближнего зарубежья, как автор серии книг  'Диагностика кармы' и 'Человек будущего'. На сегодня вышло в свет в сумме двадцать пять книг. Проведено несколько десятков встреч с читателями.
					Книги Лазарева – это изложение его личного, более чем двадцатилетнего опыта исследований в области психологии и экстрасенсорики.
					Но Лазарева нельзя назвать 'психологом' в привычном понимании этого слова. В современной психологии отсутствует такое ключевое понятие, как 'любовь к Богу', а концепция Лазарева без этого понятия просто немыслима.
					Нельзя назвать Лазарева также и 'экстрасенсом' в обыденном понимании, хотя в наличии у него уникальных экстрасенсорных способностей не приходится сомневаться.
					</p>
					<div align="right">
					 <a HREF="/index.php?option=com_content&view=article&id=65&Itemid=50" >Узнать больше</a>
					</div>
				</div>
				<div class="about_pic">
					<img src="/images/stories/fa1.jpg">
				</div>
			</div><!--end wrap-->
			<div class="green_header">
				<a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Информация от С.Н. Лазарева</a>
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
			//$cl_name = "about_back";
		 }
		 ?>
        <div class="content radgradwhite <?php echo $cl_name; ?>">
<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>
            <jdoc:include type="modules" name="user1" style="afterburner" />
			<DIV ALIGN="right">
            	<a href="<?php echo $this->baseurl ?>index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Архив новостей</a>&nbsp;&nbsp;       
			</div>
<?php
}
?>
       <jdoc:include type="component" />    
        </div>



<br>
<?php
if(JRequest::getCmd( 'view' ) == 'frontpage')
{
?>

		<div class="green_header">
			Информация<br/> для всех
		</div>

        <div class="content radgradwhite">
<h5><i>Здравствуйте!</i><br/><br/>

Прошу всех присылать вопросы только по-русски.<br/>
Приём пациентов также производится только на русском языке.<br/>
Приношу свои извинения.<br/>
С уважением, автор.<br/><br/><br/>

<i>Information for all</i><br/><br/>

Good afternoon!<br/>

Please, send your questions in Russian only.<br/>
Private consultation is also offered in Russian only.<br/>
I present my apologies.<br/>
Yours sincerely, Author.<br/><br/><br/>


<i>Информация для тех, кто посылает мне письма с просьбой о помощи:</i><br/><br/>

Еще раз сообщаю: я не провожу запись на прием по Интернету.<br/>
В настоящее время прием практически закрыт, но проводятся семинары.<br/>
Приношу извинения, но моих сил на прием всех желающих не хватает.<br/>
Еще раз подчеркиваю: информация, изложенная в последних DVD-дисках, позволяет реально работать над собой.
</h5>
		</div>
<?php
}
?>



    </div>
    <div class="footer radgrad">
        Все права защищены 2011
    </div>

</div>
</div>





</body>
</html>  