<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<!--<style type="text/css">-->
<!--    body{-->
<!--        font-family: Arial;-->
<!--        font-size:10pt;}-->
<!--    .opis{display:none;-->
<!--        text-align:justify;}-->
<!--    #opislink1{-->
<!--        cursor: pointer;-->
<!--        font-size: 14px !important;-->
<!--        color: #ffffff;-->
<!--        padding: 5px;-->
<!--    }-->
<!--</style>-->

<script language="javascript" type="text/javascript">

    function showHide(shID) {
        if (document.getElementById(shID)) {
            if (document.getElementById(shID+'-show').style.display != 'none') {
                document.getElementById(shID+'-show').style.display = 'none';
                document.getElementById(shID).style.display = 'block';
            }
            else {
                document.getElementById(shID+'-show').style.display = 'inline';
                document.getElementById(shID).style.display = 'none';
            }
        }
    }

</script>
<style type="text/css">
        /* This CSS is just for presentational purposes. */
    #wrap {

    }
    .more {
        display: none;
    }
    .showLink
    {
        color: #006400;
        font-size: 14px !important;
    }
</style>

<?php

$text_desc="<p style='text-align: justify; width: 545px;'><span style='font-family: arial,helvetica,sans-serif;'>Первое, что должны понять новички: это не эзотерика в привычном понимании этого слова, это не сказки, это не фантазии. Это реальная практическая информация, с которой нужно работать. Она требует серьезного отношения и готовности человека меняться.
<a href='#' id='opis1-show' onclick='showHide(\"opis1\");return false;' class='showLink'>Подробнее...</a></span></p>
<div class='opis' id='opis1' style='display: none;'>
<p style='text-align: justify; width: 545px;'><span style='font-family: arial,helvetica,sans-serif;'>Еще в первой книге я писал: если прощать не научились, лучше эту книгу не читать. Парадоксально, но когда человек начинает знакомиться с этой информацией, у него происходят непроизвольные изменения, которые можно назвать чисткой. Это и неприятности, это и проблемы по судьбе, это и проблемы со здоровьем и характером. «Грязь» начинает выходить из души, – люди, которые только начинают знакомиться с информацией, должны знать об этом. Если вы рассчитываете, прочитав книжку, стать здоровым и богатым, ничего не делая, – закрывайте ее сразу. Реальные изменения достигаются тяжелым трудом. Изменение и очищение души – процесс довольно мучительный, но если человек реально идет в этом направлении, тогда он может и судьбу свою изменить, и характер, и помочь своим детям, внукам и даже родственникам.</span></p>
<p style='text-align: justify; width: 545px;'><span style='font-family: arial,helvetica,sans-serif;'>Многие говорят: «Зачем смотреть давние кассеты? Посмотрим нынешние – и сразу выздоровеем». Но дело в том, что это эволюция. Конечно, первокласснику хочется попасть в университет, но если он попадет туда сразу же после первого класса, тогда у него не будет ни школьного образования, ни высшего. Любая серьезная информация воспринимается постепенно, часто мучительно, тяжело. Человек должен меняться с усвоением информации. Поэтому я всем советую: начинайте постепенно, понемногу, пусть придет понимание. Потому что уже само понимание лечит, уже понимание меняет человека, его судьбу и характер.</span></p>
<p style='text-align: justify; width: 545px;'><span style='font-family: arial,helvetica,sans-serif;'>Итак, сначала нужны элементарные базовые знания, которые должны отлежаться в голове, устояться внутри и дать первый импульс, а потом надо постепенно увеличивать объем информации, развивать свое понимание, свою готовность меняться, готовность становиться другим человеком, с другим характером, с другой судьбой. Повторяю: процесс очень серьезный.
 <a href='#' onclick='showHide(\"opis1\");return false;'  class='showLink' id='opis1-hide'>Скрыть...</a></span></p>
</div>";
//<p class='hdr_cat_btn radgrad' style='float: right; margin-right: 10px; padding: 3px; width: 100px;'><a href='#' id='opis1-show' onclick='showHide(\"opis1\");return false;' class='showLink'>Подробнее...</a></p>";
$cat_id=$_GET['category_id'];


if($cat_id==33)
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Ответы на вопросы - Видео</h1>";
}
if($cat_id==34)
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Ответы на вопросы - Аудио</h1>";
}
if($cat_id==32)
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Аудио книги</h1>";
}
if($cat_id==16)
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Выздоровление души</h1>";
}
if($cat_id==12)
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Ритмы Освобождения Сознания</h1>";
}


if(($cat_id==29)||($cat_id==31))
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Cписок аудиоприложений для начинающих</h1>";
    echo $text_desc;
}
if(($cat_id==28)||($cat_id==30))
{
    echo "<h1 style='color: #4d4848; margin-top: -11px; font-size: 24px;'>Cписок видеоприложений для начинающих</h1>";
    echo $text_desc;
}


if (($cat_id==6)||($cat_id==23)||($cat_id==22)||($cat_id==21)||($cat_id==24)||($cat_id==25)){?>
    <div class="hdr_cat_wrp">
    <div class="hdr_cat_btn radgrad" id="book_opvizh" onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=23'">Опыт выживания</div>
    <div class="radgrad hdr_cat_btn" id="book_futureman" onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=22'">Человек будущего</div>
    <div class="radgrad hdr_cat_btn" id="book_dk" onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=21'">Диагностика кармы</div>
    <div class="radgrad hdr_cat_btn"  onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=6'">Все книги</div>
    <div class="radgrad hdr_cat_btn"  onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=24'">Другие Книги</div>
    <div class="radgrad hdr_cat_btn"  onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=25'">С чего начать?</div>
    </div>
<?php }?>
<?php if (($cat_id==1)||($cat_id==33)||($cat_id==43)){?>
<div class="hdr_cat_wrp">
    <div id="forclose" class="hdr_cat_btn radgrad"  style="width: 120px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=30&page=shop.browse&Itemid=1&limitstart=0&limit=300'">С чего начать?</div>
    <div id="questions" class="hdr_cat_btn radgrad"  style="width: 159px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=33&page=shop.browse&Itemid=1&limitstart=0&limit=300'">Ответы на вопросы</div>
    <div id="foradvanced" class="hdr_cat_btn radgrad"  style="width: 180px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=37&page=shop.browse&Itemid=1&limitstart=0&limit=300'">Для опытных читателей</div>
    <div id="film" class="hdr_cat_btn radgrad"  style="" onclick="location.href='/index.php?page=shop.product_details&flypage=flypage.tpl&product_id=3800&category_id=41&option=com_virtuemart&Itemid=1'">Фильм</div>
    <div id="bookapp" class="hdr_cat_btn radgrad"  style="width:340px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=42&page=shop.browse&Itemid=1&limitstart=0&limit=300'">Видеоприложения к книгам</div>
    <div id="seminri" class="hdr_cat_btn radgrad"  style="width:247px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=43&page=shop.browse&Itemid=1'">Семинары</div>
    <div id="lekcii" class="hdr_cat_btn radgrad"  style="width:246px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=87&page=shop.browse&Itemid=1'">Лекции</div>
</div>
<?php }?>
<?php if (($cat_id==8)||($cat_id==16)||($cat_id==8)||($cat_id==12)||($cat_id==15)||($cat_id==32)||($cat_id==34)||($cat_id==44)){?>
<div class="hdr_cat_wrp">
    <div class="hdr_cat_btn radgrad"  onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=8'">Аудио семинары</div>
    <div class="radgrad hdr_cat_btn" id="aud_books" onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=32'">Аудио книги</div>
    <div class="radgrad hdr_cat_btn" id="aud_vizd" onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=16'">Выздоровление души</div>
    <div class="radgrad hdr_cat_btn"  style="width: 104px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=29&page=shop.browse&Itemid=1'">С чего начать?</div>
    <div id="questions_aud" class="hdr_cat_btn radgrad"  style="width: 138px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=34&page=shop.browse&Itemid=1&limitstart=0&limit=300'">Ответы на вопросы</div>
    <div class="radgrad hdr_cat_btn" id="aud_rithm" style="font-family: Arial;font-size: 15px; width:219px;"  onclick="location.href='/index.php?option=com_virtuemart&page=shop.browse&category_id=12'">Ритмы освобождения сознания</div>
    <div id="foradvanced_aud" class="hdr_cat_btn radgrad"  style="width: 180px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=38&page=shop.browse&Itemid=1&limitstart=0&limit=300'">Для опытных читателей</div>
    <div id="seminri" class="hdr_cat_btn radgrad"  style="width:153px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=44&page=shop.browse&Itemid=1'">Семинары</div>
    <div id="lekcii" class="hdr_cat_btn radgrad"  style="width:128px;" onclick="location.href='/index.php?option=com_virtuemart&category_id=88&page=shop.browse&Itemid=1'">Лекции</div>
</div>
<?php }?>


<?php if( trim(str_replace( "<br />", "" , $desc)) != "" ) { ?>

		<div style="width:100%;float:left;">
			<?php echo $desc; ?>
		</div>
		<br class="clr" /><br />
		<?php
     }
?>