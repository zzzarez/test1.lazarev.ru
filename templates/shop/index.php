<?php
defined('_JEXEC') or die('Restricted access');
define('YOURBASEPATH', dirname(__FILE__));

?>

<?php echo '<?xml version="1.0" encoding="utf-8">'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{LANG_TAG}" lang="{LANG_TAG}" dir="{LANG_DIR}" >
<script type="text/javascript" src="/components/com_virtuemart/js/mootools/mootools-release-1.11.js"></script>


<head>
    <jdoc:include type="head" />



    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/shop/css/main.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/shop/css/main.css" type="text/css"/>
    <!--[if IE]>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/shop/css/ie.css" type="text/css"/>
    <![endif]-->
    <!--[if IE 9]>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/shop/css/ie9.css" type="text/css"/>
    <meta content="IE=8" http-equiv="X-UA-Compatible"/>
    <![endif]-->



    <script type="text/javascript" src="/components/com_virtuemart/fetchscript.php?gzip=0&amp;subdir[0]=/themes/vm_green/js&amp;file[0]=mooPrompt.js&amp;subdir[1]=/themes/vm_green&amp;file[1]=theme.js" type="text/javascript"></script>
    <script type="text/javascript" src="/components/com_jcomments/libraries/joomlatune/ajax.js"></script>
    <script type="text/javascript" src="/components/com_jcomments/js/jcomments-v2.3.js"></script>

    <script type="text/javascript" src="/plugins/content/extravote/extravote.js"></script>
    <link rel="stylesheet" href="/plugins/content/extravote/extravote.css" type="text/css"/>


    <meta name='yandex-verification' content='75854833ff62c647' />
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-31933385-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

            </script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter19724677 = new Ya.Metrika({id:19724677,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true});
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
<noscript><div><img src="//mc.yandex.ru/watch/19724677" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script type="text/javascript">
document.getElementsById = function(id) {
    var el = document.getElementById(id);
    if (!el) {
        res=[];
        return res;
    }

    var els = document.getElementsByTagName(el.tagName);
	i = 0;
	res = [];
	
    while (els[i]) {
	if (els[i].id == id)
	    res.push(els[i]);
	i++;
    }

    return res;


};
</script>
</head>

<body align="center">
<?php
//if (file_exists(JPATH_SITE.DS.'components'.DS.'com_joomlastats'.DS.'joomlastats.inc.php'))
//    include_once(JPATH_SITE.DS.'components'.DS.'com_joomlastats'.DS.'joomlastats.inc.php');



?>
<jdoc:include type="modules" name="left" style="none">


<div class="top">
    <div class="top_img"><a href="http://shop.lazarev.ru"><img src="images/shop_addr.png"></a></div>
    <div class="shop_name">Интернет-магазин Лазарева С.Н.</div>
    <div class="top_img"><a href="http://shop.lazarev.ru"><img src="images/shop_addr.png"></a></div>
</div>

<div class="main rad">
<div class="leftWrapper">
    <div class="logo radgrad">
        <a href=""> <img src="images/logo.png" alt="Интернет-магазин  Лазарева С.Н."></a>
    </div>
    <div class="menu">
        <jdoc:include type="modules" name="left" style="none"/>
    </div>
</div>
<div class="contentWrapper">
<div class="centerWrapper">
    <jdoc:include type="modules" name="user6" style="afterburner" />
<?php
        if( (JRequest::getCmd( 'flypage' ) != 'flypage.tpl') && (JRequest::getCmd( 'page' )!='checkout.index'))
        {
?>

    <div class="top_catalog">
        <div class="books radgrad">
            <div class="header_books"><a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=6">Книги</a></div>
            <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=6"><img class="books_pic" src="/images/cat_book.png"></a>
        </div>
        <div class="video radgrad">
            <div class="header_video"><a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=1">Видео</a></div>
            <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=1"><img class="video_pic" src="/images/cat_video.png"></a>
        </div>
        <div class="audio radgrad">
            <div class="header_audio"><a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=8">Аудио</a></div>
            <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=8"><img class="audio_pic" src="/images/cat_audio.png"></a>
        </div>
    </div>
<br>
<!--    <div class="JuniorsVideo radgrad">-->
<!--        <a href="/index.php?option=com_virtuemart&category_id=13&page=shop.browse&keyword=555&Itemid=1&limitstart=0&limit=300">Для начинающих &raquo;</a>-->
<!--    </div>-->
<!--    <div class="JuniorsAudio radgrad">-->
<!--        <a href="/index.php?option=com_virtuemart&category_id=15&page=shop.browse&keyword=555&Itemid=1&limitstart=0&limit=300">Для начинающих &raquo;</a>-->
<!--    </div>-->
<?php
        }
?>


    <!--            first page-->
    <?php
        if(JRequest::getCmd( 'view' ) == 'frontpage')
        {
    ?>
    <div class="main_about">
        <div class="header_txt radgrad_gray" style="color: #ffffff;">
            Главная
        </div>
        <div class="header_line">
        </div>
        <div class="content radgrad" style="font-size: 13px;">
            <h1 style="font-size: 14px;">Интернет-магазин Лазарева Сергея Николаевича</h1>
            Большое количество просьб и пожеланий со стороны наших читателей, которые хотели бы получать информацию более оперативно и в удобном формате,
            подвигло нас к созданию данного ресурса.
            Теперь все материалы собраны воедино и доступны для вас в любое время.<br>
            <b>Обращаем внимание</b>, что товары в интернет-магазине представлены в электронном формате. После оплаты, их доставка осуществляется на Ваш E-mail адрес.<br>
            Для приобретения DVD дисков и печатных изданий, обращайтесь по контактам, указанным в разделе "Доставка" нашего сайта.
            <br><p style="text-align: right;">С уважением, команда Shop.Lazarev.Ru</p>
        </div>

        <div class="read_more radgrad">
            <a href="/index.php?option=com_content&view=article&id=43&Itemid=36">Далее&raquo;</a>
        </div>

    </div>
            <div style="width: 583px;display: inline-block;">

            </div>
        <div id="wrap2div">
        <div class="main_news">
            <div class="header_txt radgrad_gray">
                <a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Новости</a>
            </div>
            <div class="header_line">
            </div>


            <div class="content radgrad" style="font-size: 11px;padding-bottom: 10px;">
                <jdoc:include type="modules" name="user5" style="afterburner" />
            </div>
            <div class="read_more radgrad">
                <a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Все новости&raquo;</a>
            </div>
        </div>
        <div class="main_special">
            <div class="header_txt radgrad_green">
                <a href="/index.php?option=com_content&view=section&layout=blog&id=6&Itemid=44">Новинки</a>
            </div>
            <div class="header_line">
            </div>
            <div class="content radgrad">
<!--                <111jdoc:include type="modules" name="user8" style="afterburner" />-->

                <jdoc:include type="modules" name="user1" />
                <jdoc:include type="modules" name="user9" style="afterburner" />



<!--                <jdoc!!!!:!!!include type="modules" name="user8" style="afterburner" />-->





            </div>
            <div class="read_more radgrad">
                <a href="/index.php?option=com_content&view=section&layout=blog&id=6&Itemid=44">Все новинки &raquo;</a>
            </div>
        </div>
        </div>
            <div class="down_books">
                <div class="header_txt radgrad_blue">
                    <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=6">Книги</a>
                </div>
                <div class="header_line">
                </div>
                <div class="content radgrad">
                    <jdoc:include type="modules" name="user2" style="none"/>
                </div>
                <div class="read_more radgrad_blue">
                    <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=6">Все книги &raquo;</a>
                </div>
            </div>
            <div class="down_video">
                <div class="hdr_wrapper">
                    <div class="header_txt radgrad_orange">
                        <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=1">Видео</a>
                    </div>
<!--                    <div class="header_txt radgrad_green">-->
<!--                        <a href="/index.php?option=com_virtuemart&category_id=30&page=shop.browse&Itemid=1">Для начинающих</a>-->
<!--                    </div>-->
                </div>
                <div class="header_line">
                </div>
                <div class="content radgrad">
                    <jdoc:include type="modules" name="user3" style="none"/>
                </div>
                <div class="read_more radgrad_orange">
                    <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=1">Все видео &raquo;</a>
                </div>
            </div>
            <div class="down_audio">
            <div class="hdr_wrapper">
                <div class="header_txt radgrad_rad">
                    <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=8">Аудио</a>
                </div>
<!--                <div class="header_txt radgrad_green">-->
<!--                    <a href="/index.php?option=com_virtuemart&category_id=29&page=shop.browse&Itemid=1">Для начинающих</a>-->
<!--                </div>-->
            </div>
            <div class="header_line">
            </div>
            <div class="content radgrad">
                <jdoc:include type="modules" name="user4" style="none"/>
            </div>
            <div class="read_more radgrad_rad">
                <a href="/index.php?option=com_virtuemart&page=shop.browse&category_id=8">Все аудио &raquo;</a>
            </div>
</div>


</div>
        <?php }?>
    <!--            first page/\/\/\/\/\/\-->


    <?php
    if(JRequest::getCmd( 'view' ) != 'frontpage')
    {
        ?>
        <div class="main_content">
            <div class="header_txt radgrad_green">
                <jdoc:include type="modules" name="breadcrumb" />
            </div>
<!--            <div class="header_line">-->
<!--            </div>-->
            <div class="content radgrad">
                <div class="content_move">
                    <jdoc:include type="component" />


                    <?php
                    if (JRequest::getCmd('option') == "com_content" && JRequest::getCmd('view') == "section" && JRequest::getCmd('id') == "6"){
                        ?>

                        <div class="hot">
                        <jdoc:include type="modules" name="user1" />
                        <jdoc:include type="modules" name="user9" style="afterburner" />
                        <jdoc:include type="modules" name="user8" style="afterburner" />
                        <jdoc:include type="modules" name="user7" style="afterburner" />

                        </div>
                        <?php }?>


                </div><!--regmap-->
            </div>
        </div>

        <?php }?>


</div>
<div class="rightCWrapper">
    <?php
        //echo trim('<jdoc:include type="modules" name="right"/>');
    ?>
    <jdoc:include type="modules" name="right"/>
    <div class="contact_block radgrad">

        <div class="contact_hdr_img">
            <a href="/index.php?option=com_contact&view=contact&id=1&Itemid=6">Наши контакты</a>
        </div>

        <div class="cont_wrapper">
            <span class='black_txt'>Перед тем как отправить вопрос, пожалуйста, ознакомьтесь с разделом <a style="font-size: 12px;text-decoration: underline;" href="/index.php?option=com_content&view=article&id=59&Itemid=43">Частые вопросы</a>
            и <a style="font-size: 12px;;text-decoration: underline" href="/index.php?option=com_content&view=article&id=10&Itemid=41">Помощь</a></span>
            <br><br>
            Официальный сайт: <a style="font-size: 12px;" href="http://www.lazarev.ru">Lazarev.ru</a><br>
            Официальный сайт2: <a style="font-size: 12px;" href="http://lazarev.org">Lazarev.org</a><br><br>
            <b >Email:</b> <a href="mailto:shop@lazarev.ru">shop@lazarev.ru</a><br><br>
            <b>Skype:</b><span class='black_txt'> shop_lazarev</span><br><br>
            <b>ICQ nick:</b><span class='black_txt'> shop_lazarev</span><br><br>
            <a style="font-size: 11px;" href="/index.php?option=com_contact&view=contact&id=1&Itemid=6">Форма обратной связи</a><br>
        </div>
    </div>

    <div class="social_box radgrad">
        <div class="addthis_toolbox addthis_default_style addthis_24x24_style">
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
        </div>
        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f70ad8a05504d0a"></script>

    </div>
	<div class="instruction radgrad">
        <div class="contact_hdr_img">
            <a href="/index.php?option=com_content&view=article&id=10&Itemid=41#insruction">Как оформить заказ?</a>
        </div>
<br>

        <a href="/index.php?option=com_content&view=article&id=10&Itemid=41#insruction">ПОШАГОВАЯ ИНСТРУКЦИЯ</a>
        <br><br>

	</div>

    <div class="instruction radgrad">
        <div class="contact_hdr_img">
            Мы принимаем
        </div>
        <a href="/index.php?option=com_content&view=article&id=17&Itemid=40">
            <img style="margin-left: 4px;border-radius: 10px 10px 10px 10px;-webkit-border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px;" src="/images/ps.png">
        </a>

        <br>

    </div>

</div>


<!--            first page-->

<div class="footer">
    <div class="page_up"><a class='scrollTop' href='#header' style='display:none;'>Вверх страницы &uarr; </a> <br></div>
    <div class="down_menu">
        <a href="/">Главная</a> | <a href="/index.php?option=com_content&view=article&id=43&Itemid=36">О Магазине</a> | <a href="/index.php?option=com_content&view=article&id=6&Itemid=39">Доставка</a> | <a href="/index.php?option=com_content&view=article&id=17&Itemid=40">Оплата</a> | <a href="/index.php?option=com_content&view=section&layout=blog&id=5&Itemid=35">Новости</a> | <a href="/index.php?option=com_contact&view=contact&id=1&Itemid=6">Контакты</a> | <a href="/index.php?option=com_xmap&sitemap=1&Itemid=37">Карта сайта</a> | <a href="/index.php?option=com_content&view=article&id=10&Itemid=41">Помощь</a>

    </div>
    <div class="copyright">
        Все права защищены, 2012 г.
    </div>
</div>
</div>

</body>
</html>  