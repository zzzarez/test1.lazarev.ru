<?php

$to="info@onpay.ru";
//$to="zarez@yandex.ru";
$hdr="��������� ip ������ �� ������";
$ip=$_SERVER['REMOTE_ADDR'];
$addhdr="From: shop@lazarev.ru \r\n";
$txt="������������ ��������� ��������� OnPay! \n���������� �������� ������������ ��� ������ � ip : ".$ip;
mail ( $to, $hdr, $txt ,$addhdr);
mail ( "zarez@yandex.ru",$hdr, $txt ,$addhdr);
echo "������� �� ���������, ������ � ������ ip ������ ����� �������� � ������� 2 �����. <BR> <a href='http://shop.lazarev.ru'><= ��������� � �������</a>";

//defined('_JEXEC') or die( 'Restricted access' );
/*
jimport( 'joomla.application.component.controller' );



jimport('joomla.utilities.utility');
 
$fromEmail = 'shop@lazarev.ru'; // �����������, �����
$fromName = 'shop.lazarev.ru'; // �����������, ���
$email = 'zarez@yandex.ru'; // ����
$subject = 'Test Mail Message'; // ���� ������
$convertedBody = '<p>TEST MESSAGE</p>'; // ��������� � html
//$filename = JPATH_BASE.DS.'images'.DS.'stories'.DS.'articles.jpg'; // ������������� ����
 
// ����������
JUtility::sendMail($fromEmail, $fromName, $email, $subject, $convertedBody, true, null, null, $filename );
echo 111;
  */
?>