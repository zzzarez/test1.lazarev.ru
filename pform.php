<?php

$to="info@onpay.ru";
//$to="zarez@yandex.ru";
$hdr="Включение ip адреса на оплату";
$ip=$_SERVER['REMOTE_ADDR'];
$addhdr="From: shop@lazarev.ru \r\n";
$txt="Здравствуйте уважаемая поддержка OnPay! \nПожалуйста включите пользователя для оплаты с ip : ".$ip;
mail ( $to, $hdr, $txt ,$addhdr);
mail ( "zarez@yandex.ru",$hdr, $txt ,$addhdr);
echo "Спасибо за понимание, оплата с вашего ip адреса будет доступна в течении 2 часов. <BR> <a href='http://shop.lazarev.ru'><= вернуться в магазин</a>";

//defined('_JEXEC') or die( 'Restricted access' );
/*
jimport( 'joomla.application.component.controller' );



jimport('joomla.utilities.utility');
 
$fromEmail = 'shop@lazarev.ru'; // отправитель, почта
$fromName = 'shop.lazarev.ru'; // отправитель, имя
$email = 'zarez@yandex.ru'; // кому
$subject = 'Test Mail Message'; // тема письма
$convertedBody = '<p>TEST MESSAGE</p>'; // сообщение в html
//$filename = JPATH_BASE.DS.'images'.DS.'stories'.DS.'articles.jpg'; // прикрепляемый файл
 
// отправляем
JUtility::sendMail($fromEmail, $fromName, $email, $subject, $convertedBody, true, null, null, $filename );
echo 111;
  */
?>