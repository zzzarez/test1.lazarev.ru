<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @version $Id: shop.downloads.php 1578 2008-11-29 23:08:19Z soeren_nb $
 * @package VirtueMart
 * @subpackage html
 * @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */


mm_showMyFileName(__FILE__);

$did = vmGet($_GET, 'download_id');



$itd = 36;
$video_url = $mm_action_url . "index.php?option=com_virtuemart&page=shop.downloads&Itemid=" . $itd . "&download_id=" . $did;
//$ajax_url  = $mm_action_url . "index.php?option=com_virtuemart&page=shop.ajax&Itemid=" . $itd . "&download_id=" . $did;
$ajax_url = $mm_action_url . "ajax/tickets.php?download_id=" . $did;

////////////////player id
$q = "SELECT `player_id`  FROM player_ids WHERE downloadid='" . $did . "'";
$db_p = new ps_DB;
$db_p->query($q);
$db_p->next_record();
$player_id = $db_p->f("player_id");

//$hlog=fopen('/var/www/shop.lazarev.ru/fortest.log','a+');
//fwrite($hlog,"\r\n". $q.$player_id);
//fclose($hlog);

////////////////player id




$q = "SELECT `download_id`, `file_name`,`order_id`,`product_id`,`download_max`  FROM #__{vm}_product_download WHERE download_id='" . $did . "'";
//echo $q;
$db_did = new ps_DB;
$db_did->query($q);
//print_r($d/b_did);
$db_did->next_record();

$fname = $db_did->f("file_name");
$d_max = $db_did->f("download_max");

$ordid=$db_did->f("order_id");
$product_id=$db_did->f("product_id");

$db_p=new ps_DB;
$q="SELECT product_name FROM #__{vm}_product  WHERE product_id='".$product_id."'";
$db_p->query($q);
$product_name="";
if ($db_p->next_record())
    $product_name=$db_p->f("product_name");

$isView1 = false;
$isView2 = false;
if (strpos($fname, "_view"))
{
    $isView = true;
}
//echo $fname;
$isView2 = false;
if (strpos($fname, "_view1"))
{
    $isView1 = true;
}
if (strpos($fname, "_view2"))
{
    $isView2 = true;
}
//echo  "qqq==".$fname."- ".$isView;
$isZayac1=false;
$isZayac2=false;

$isOldTicket=false;
//if(intval($ordid.int)<=45452){
  //  echo "<b style='color: #ff0000;'>Ваш билет на старую дату!</b>";
   // $isOldTicket=true;
//}


if ($isView) {
    /*
    $db_chk = new ps_DB;
    $timeadd = time();//1 минута
    $indate=gmdate("Y-m-d H:i:s",$timeadd );
    $q="delete FROM current_downloadid WHERE intime<'$indate'";
    $db_chk->query($q);
    $q="SELECT downloadid FROM current_downloadid WHERE downloadid='$did'";
    $db_chk->query($q);
    if ($db_chk->next_record())
    {
        if($_COOKIE['f81ef6h']==$did)
        {
            //echo "same";
        }
        else
        {
            //echo "<b style='color: #ff0000;'>Внимание! Сработала система защиты.</b><br>Просмотр трансляции по одному билету на нескольких устройствах одновременно невозможен. Между закрытием страницы с трансляцией и ее повторным открытием должно пройти не менее 1 минуты.<br><br><h4>Почему я не вижу видеоплеер с трансляцией?</h4>1) У вас уже открыта страница с трансляцией на другом компьютере или устройстве.<br>2) Вы только что закрыли страницу с трансляцией и хотите открыть ее вновь, не дождавшись, пока пройдет время (не менее минуты).<br>3) Вы передали свой билет другому лицу и пытаетесь одновременно с ним посмотреть трансляцию.<br><h4>Что делать?</h4>Если Вы являетесь владельцем билета и получили данное сообщение, то Вам необходимо:<br>1) Закрыть в7 и 8 се страницы с трансляцией.<br>2) Подождать не менее 1 минуты.<br>3) Повторно открыть страницу с трансляцией по ссылке в билете.<br><br><br>Если вышеперечисленные пункты не помогли, то обратитесь в службу поддержки.";
            //            echo "...";
            //$isZayac=true;
        }
    }
    else
    {
        if($isView1)
        {
            $q="INSERT INTO current_downloadid (downloadid,day) VALUES('$did',8)";
            $db_chk->query($q);

            $q="INSERT INTO current_down_all (downloadid,day) VALUES('$did',8)";
            $db_chk->query($q);

        }
        if($isView2)
        {
            $q="INSERT INTO current_downloadid (downloadid,day) VALUES('$did',9)";
            $db_chk->query($q);

            $q="INSERT INTO current_down_all (downloadid,day) VALUES('$did',9)";
            $db_chk->query($q);

        }
        setcookie("f81ef6h",$did,time()+60);
    }
    */
////////////check if user already view
    ?>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->

<!--<style type="text/css">@import "jquery.countdown.css";</style>-->
<!--<script type="text/javascript" src="jquery.countdown.js"></script>-->
<!--<script type="text/javascript" src="jquery.countdown-ru.js"></script>-->

<!--                Таймер                  <script type="text/javascript">-->
<!--    $(function () {-->
<!--        var austDay = new Date();-->
<!--        austDay = new Date(2012, 9, 21,14);-->
<!--        $('#defaultCountdown').countdown({until: austDay});-->
<!--        $('#year').text(austDay.getFullYear());-->
<!--    });-->
<!--</script>-->

<!--<script type="text/javascript">-->
<!--//    var flashvars = {"comment":"IT LEADER","st":"http://media.webinar.w2c.ru/video4-1924.txt","file":"rtmp://stream.webinar.ru:80/webinar-live/2","link":"http://uppod.ru/vftawsitr"};var params = {wmode:"transparent", allowFullScreen:"true", allowScriptAccess:"always",id:"videoplayer16743"}; new swfobject.embedSWF("http://media.webinar.w2c.ru/uppod.swf", "videoplayer16743", "700", "560", "9.0.115.0", false, flashvars, params);-->
<!--</script>-->
</head>
<h1 align="center">Семинар Лазарева С.Н. <br> в режиме онлайн-трансляции<br><h1>
<h2 align="center">
    <b>
        <?php
                $isv="17 ноября ";
                $isvy="(второй день)<br> ";
            if($isView1)
            {
                $isv="16 ноября ";
                $isvy="(первый день)<br> ";
            }
                echo $isv.$isvy;
        ?></b>

</h2>

 <b>Семинар Лазарева С.Н.<br>
     г. Москва, <?php  echo $isv;  ?> 2013 года <?php  echo $isvy;  ?>
     11.00 – 15.00 по московскому времени<br>
 </b><br>
<br><br>
    Онлайн-трансляция мероприятия начнется за 30 минут до начала семинара, чтобы вы смогли еще раз проверить и настроить свой компьютер.
<br>

<!--<h1 align="left">Презентация новой книги <br>в режиме онлайн-трансляции </h1><br><br>-->
<!--<h4>Презентация новой книги "Опыт выживания. Часть 6"<br>-->
<!--    г. Москва, 21 октября 2012 года<br>-->
<!--    14.00-16.00<br>-->
<!--        <h4><br><br>-->
<!--<h4>Онлайн-трансляция презентации начнется в 14.00 по московскому времени.</h4>-->
<!--<br>-->
<!--    <b>Внимание!!! <br>Если трансляция не начинается, нажмите на кнопку "Play" (треугольная кнопка слева внизу окна плеера),<br>или обновите окно браузера.</b>-->
<!--<h3>До начала трансляции осталось:</h3>-->
<!--<div id="defaultCountdown"></div>-->
<br>
<!--    <img src="/images/zag.jpg" alt="">-->
<?php
   if(!$isOldTicket){

        if(!$isZayac){//Сам плеер some player :)
            if($isView1)
            {// 8го декабря
                ?>
                <br><br><br><br>
                <iframe  name="prolivestream" src="http://w.prolivestream.ru/20131016-17lazarev/<?php echo $player_id;?>/" scrolling="no" frameborder="no" height="339" width="549"></iframe>
                <?php
            }
            else
            {
?>
                <br><br><br><br>
                <iframe name="prolivestream" src="http://w.prolivestream.ru/20131117lazarev/<?php echo $player_id;?>/" scrolling="no" frameborder="no" height="339" width="550"></iframe>
                <?php
            }
        }
   }
    ?>
<br><br><br>
    Если у вас возникли трудности с просмотром трансляции,  обращаетесь по телефонам:<br>
    1) Тел: <b>+7 968 541 92 46</b>,<br>
    2) Тел: <b>+7 926 510 50 60</b><br>
    или пишите в <a class="blue_link" href="mailto:shop@lazarev.ru">службу поддержки</a><br><br>

    Обращаем ваше внимание на список часто задаваемых вопросов:<br>

    <p><b>Что делать если появляется надпись?:</b> <br><span style="color: red;">Эта ссылка сейчас используется другим пользователем. Попробуйте обновить страницу. Если сообщение повторяется, воспользуйтесь другой ссылкой.
    </span></p>
    <p>Попробуйте обновить страницу несколько раз нажать кнопку F5 на клавиатуре; либо нажать на закругленную стрелочку в строке набора веб-адреса)
    Также возможно, что ваш билет уже используется. Пожалуйста проверьте не предоставляли ли вы доступ к вашему билету другим людям.</p>
    <br>
    <p><b>Что делать если у меня прерывается трансляция?</b></p>
    <p>Попробуйте сменить “Площадку” в плеере трансляции с “Россия” на “Германия” (Она находится в левом нижнем углу плеера) или уменьшить качество трансляции на более низкое (внизу плеера вы можете выбрать различные качества просмотра: HD, оригинал высокое, средние, низкое и для мобильных устройств)
    </p>
    <br>
    <p><b>Что делать если появляться надпись: Черный экран с надписью на англ. we are unable to connect…</b></p>
    <p>Для просмотра трансляции мы рекомендуем браузер <a class="blue_link" href="http://www.google.com/intl/ru/chrome/browser/">google chrome</a>. Пожалуйста перейдите по <a class="blue_link" href="http://www.google.com/intl/ru/chrome/browser/">ссылке</a> и установите его.</p>
    <br>
    <p><b>Как сделать видео во весь экран?</b></p>
    <p>Для того, чтобы сделать видео во весь экран, вам необходимо нажать на плеере с трансляцией кнопку “в виде экрана” расположенную в правом нижнем углу.</p>




    <br>
    <p><b>Как понять, началась ли трансляция?<br> Что делать если трансляция остановилась?</b></p>
<p>Если трансляция не началась автоматически, Вам следует обновить страницу (для пользователей Windows: нажать кнопку F5 на клавиатуре; либо нажать на закругленную стрелочку в строке набора веб-адреса) или нажать кнопку play в виде треугольника на видео плеере.</p>
    <br>    <p><b>Плеер выдает сообщение "Не удается подключиться к медиа серверу. Проверьте сетевые настройки"</b></p>
    <p>Обновите, пожалуйста, страницу. Рекомендуем самые свежие версии браузеров и Adobe Flash Player.</p>

    <br><p><b>Почему не получается посмотреть трансляцию?</b></p>
<p>Для просмотра трансляций необходимо, чтобы у Вас была установлена версия Adobe Flash Player 11.1.0 или выше. Установить Adobe Flash Player Вы можете по ссылке: http://get.adobe.com/ru/flashplayer/
    Также, проверьте, пожалуйста, проигрывается ли у вас видео на сайтах
    <a class="blue_link" href="http://www.youtube.com">http://www.youtube.com</a> или <a class="blue_link" href="http://vk.com">http://vk.com</a>.
    Если нет, то причина в отсутствии видеокодеков. Вы можете скачать их на сайте:
    <a class="blue_link" href="/files/klitecodecpack.exe">K-Lite Codec pack</a>
    Также причиной может быть медленное интернет соединение.
</p>
    <br><p><bКакая скорость интернет соединения необходима для проведения трансляции?</b></p>
    <p>Желательная скорость для просмотра трансляции от 1 Мбит исходящего трафика.</p>
    <br><p><b>Что делать, если вышеперечисленные пункты не помогли?</b></p>

Напишите в <a class="blue_link" href="mailto:shop@lazarev.ru">службу поддержки</a><br> или звоните по телефону: <b>+7 968 541 92 46, +7 926 510 50 60</b><br><br>


<p align="left"><i>Вы можете заранее провести тест на своем компьютере на наличие последней версии Adobe Flash Player
    Если видео работает, то проблем с просмотром трансляции не должно возникнуть.
</i><br><br>

    <iframe width="565" height="350" src="http://www.youtube.com/embed/dZYKX8DoHl0" frameborder="0" allowfullscreen></iframe>

    <?php
    }
?>

<?php
//}
//echo "aaa=".$mm_action_url ."index.php?option=com_virtuemart&page=shop.downloads&Itemid=".$itd."&download_id=".$did;
$mainframe->setPageTitle($VM_LANG->_('PHPSHOP_DOWNLOADS_TITLE'));

//&&(!$isView)
if (!$isView) {


//    if ($ps_function->userCanExecuteFunc('downloadRequest')) {
//        ?>

    <?php if($fname){?>
        <h1 style="font-size: 24px;color: #565656;">Раздел закачки файла</h1>
    <br><br><br>
    Если загрузка не началась автоматически, нажмите на ссылку ниже:
    <br><br>
    <a id="dlink" style="font-size: 18px;color: #006400;" href="<?php echo $mm_action_url ?>index.php?func=downloadRequest&option=<?php echo VM_COMPONENT_NAME ?>&page=shop.downloads&download_id=<?php echo htmlspecialchars(vmGet($_GET, 'download_id'), ENT_QUOTES) ?>">
        Скачать&nbsp;&nbsp;&nbsp;&nbsp;"<?php echo $product_name;?>"
    </a>
    <br>
        <?php echo "Имя файла: ".$fname;?>
        <br>
        <?php
        if(intval($d_max)==6)
        {
            echo "<b style='font-size: 16px;color: #ff4500;'>Внимание!!! Вы израсходовали 5 закачек из 10. Пожалуйста, используйте дальнейшие попытки более эффективно. Возможно, необходимо сменить интернет канал.</b>";
        }
        if(intval($d_max)<6)
            echo "Оставшееся количество скачиваний: ".$d_max;

        ?>
        <br> <br><br><br>
    <script>
        window.onload = function(){
            document.getElementById('dlink').click();
        }

    </script>
        <?php } else { ?>
        <?php
    echo "<b style='color: red;'>Извините, превышено количество скачиваний или вышло время для скачивания. Обратитесь в службу тех поддержки shop@lazarev.ru.</b>";
    } ?>

<!---->
<!--    <h3>--><?php //echo $VM_LANG->_('PHPSHOP_DOWNLOADS_TITLE') ?><!--</h3>-->
<!--    <img src="--><?php //echo VM_THEMEURL ?><!--images/downloads.gif" alt="downloads" border="0" align="middle"/>-->
<!--    <br/>-->
<!--    <br/>-->
<!--    --><?php
//
//        if (ENABLE_DOWNLOADS == '1') {
//            ?>
<!--        <form method="get" action="--><?php //echo $mm_action_url ?><!--index.php" name="downloadForm">-->
<!--            <p>Для сохранения файлов, нажмите кнопку 'Скачать'.</p>-->
<!---->
<!--            <div align="left">-->
<!--                <input type="text" class="inputbox"-->
<!--                       value="--><?php //echo htmlspecialchars(vmGet($_GET, 'download_id'), ENT_QUOTES) ?><!--" size="32"-->
<!--                       name="download_id"/>-->
<!--                <br/><br/>-->
<!--                <input type="submit"-->
<!--                       onclick="if( document.downloadForm.download_id.value &lt; 12) { alert('--><?php //echo $VM_LANG->_('CONTACT_FORM_NC', false) ?><!--');return false;} else return true;"-->
<!--                       class="button" value="--><?php //echo $VM_LANG->_('PHPSHOP_DOWNLOADS_START') ?><!--"/>-->
<!--            </div>-->
<!--            <input type="hidden" name="func" value="downloadRequest"/>-->
<!--            <input type="hidden" name="option" value="--><?php //echo VM_COMPONENT_NAME ?><!--"/>-->
<!--            <input type="hidden" name="page" value="shop.downloads"/>-->
<!--        </form>-->
<!--        --><?php
//        }
//    }
//    else {
//        $vmLogger->info($VM_LANG->_('NOT_AUTH', false)
//                . ($auth['user_id'] ? '' : ' ' . $VM_LANG->_('DO_LOGIN', false))
//        );
//    }
}
?>
<?php if (!$isZayac) { ?>
<!--<script text="javascrip/text">-->
<!--var num = 1;-->
<!--setInterval(function() {-->
<!--    $.ajax({-->
<!--	url: '--><?php //echo $ajax_url ?><!--',-->
<!--	type: 'GET'-->
<!--    });-->
<!--}, 30000);-->
<!--</script>-->
<?php } ?>
