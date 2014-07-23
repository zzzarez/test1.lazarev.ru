<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @version $Id: get_payment_method.tpl.php 1140 2008-01-09 20:44:35Z soeren_nb $
 * @package VirtueMart
 * @subpackage templates
 * @copyright Copyright (C) 2007 Soeren Eberhardt. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

include_once(CLASSPATH . "payment/ps_paypal_api.cfg.php");
require_once(CLASSPATH . 'payment/ps_paypal_api.php');



ps_checkout::show_checkout_bar();
if ($_GET['redirected'] == 1) {
    echo '<div class="cart_bread"><span style="margin-left: -109px;">Корзина</span><div class="cart_img"><img src="/images/stories/cart_next.png"></div> <div class="cart_img2"><img src="/images/stories/cart_next.png"></div><span>Оплата</span></div><div class="cart_white2">Оформление заказа</div>';
}

echo $basket_html2;


$db = new ps_DB();
$db->query("SELECT * FROM #__{vm}_user_info WHERE user_info_id='" . strip_tags($_REQUEST['ship_to_info_id']) . "'");
$db->next_record();
$user_email = $db->f("user_email");




//$varname = 'PHPSHOP_CHECKOUT_MSG_' . CHECK_OUT_GET_PAYMENT_METHOD;
//echo '<div class="paypal_hdr">' . $VM_LANG->_($varname) . '</div>';

echo '<div class="paypal_hdr">Пожалуйста, выберите удобный для Вас способ оплаты:</div>';

$lang = jfactory::getLanguage();
$lang_iso = str_replace('-', '_', $lang->gettag());
$paypal_buttonurls = array('en_US' => 'https://www.paypal.com/en_US/i/logo/PayPal_mark_60x38.gif',
    'en_GB' => 'https://www.paypal.com/en_GB/i/bnr/horizontal_solution_PP.gif',
    'de_DE' => 'https://www.paypal.com/de_DE/DE/i/logo/lockbox_150x47.gif',
    'es_ES' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
    'pl_PL' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
    'nl_NL' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
    'fr_FR' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
    'it_IT' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/it_IT/IT/i/bnr/bnr_horizontal_solution_PP_178wx80h.gif',
    'zn_CN' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif');
$paypal_infolink = array('en_US' => 'https://www.paypal.com/us/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'en_GB' => 'https://www.paypal.com/uk/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'de_DE' => 'https://www.paypal.com/de/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'es_ES' => 'https://www.paypal.com/es/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'pl_PL' => 'https://www.paypal.com/pl/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'nl_NL' => 'https://www.paypal.com/nl/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'fr_FR' => 'https://www.paypal.com/fr/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'it_IT' => 'https://www.paypal.com/it/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
    'zn_CN' => 'https://www.paypal.com/cn/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside');
if (!isset($paypal_buttonurls[$lang_iso])) {
    $lang_iso = 'en_US';
}

$html = '<img id="paypalLogo" src="' . $paypal_buttonurls[$lang_iso] . '" alt="PayPal Checkout Available" border="0" style="cursor:pointer;" /></a>';
$html .= '<script type="text/javascript">window.addEvent("domready", function() {
	$("paypalLogo").addEvent("click", function() {
		window.open(\'' . $paypal_infolink[$lang_iso] . '\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=500\');
		});
	
		window.addEvent("click", function() {
			if($("paypalExpressID_ecm").checked) 
			{
				$("paypalExpress_ecm").value="2";
			} 
			else 
			{	
				$("paypalExpress_ecm").value="";
			}
		});
	});
	</script>';
?>



<div class="paypal_wrp" id="pdiv1">
    <div class="paypal_radio" >
<!--        <input type="hidden" id="paypalExpress_ecm" name="payment_method_ppex" value="2"/>-->
<!--        <input type="radio" id="paypalExpressID_ecm" name="payment_method_id"-->
<!--               value="--><?php //echo ps_paypal_api::getPaymentMethodId();?><!--"/>-->
        <input type="hidden" id="paypalExpress_ecm" name="payment_method_ppex" value=""/>
        <input type="radio" id="paypalExpressID_ecm" name="payment_method_id" value=""/>

    </div>
    <div class="paypal_sel" id="psdiv1" style="background: none;border: none;">
        <div class="paypal_img">
            <img src="/images/ps2.png">
        </div>
        <div class="paypal_txt">
            <?php //echo $html; ?> PayPal - международная платежная система. Оплата с помощью всех международных карт.
        </div>
    </div>
</div>


<?php


echo ps_checkout::list_payment_methods($payment_method_id);
?>



<!--<form style="text-align:left;" action="http://secure.onpay.ru/pay/lshop?f=10&pay_mode=fix&pay_for=107989&price=300&currency=RUR&convert=yes&md5=f94895542c412a2a46e81c8fd3f1468f&user_email=zarez@yandex.ru&url_success=http://shop.lazarev.ru/" method="post" target="_blank">-->
<!--    <input class="enter_button" style="margin-top: 18px;margin-left: -20px;font-size: 26px;" type="submit" name="submit"  value="для теста" />-->
<!--</form>-->
<?php

echo $basket_html_s;
echo "<div class='addr_wrap'>адрес доставки: <div class='mail_wrap'>" . $user_email . '</div></div>';
?>

<script>

    var div1 = document.getElementById("pdiv1");
    var div2 = document.getElementById("pdiv2");
    var psdiv1 = document.getElementById("psdiv1");
    var psdiv2 = document.getElementById("psdiv2");

    div1.onmouseover = function (e) {
        psdiv1.style.border="2px solid #c0c0c0";
        psdiv1.style.background="white";
    };
    div1.onmouseout = function (e) {
        if(!document.getElementById("pdiv1").getElementsByTagName('input')[1].checked)
        {
            psdiv1.style.border="none";
            psdiv1.style.background="none";
        }
    };
    div1.onclick = function (e) {
        //var psdiv=document.getElementById("pdiv1");
        //var elements = document.getElementById("pdiv1").getElementsByTagName('input').checked=true;

        document.getElementById("pdiv1").getElementsByTagName('input')[1].checked=true;
        psdiv2.style.border="none";
        psdiv2.style.background="none";
        document.getElementById("paypalExpressID_ecm").value="4";
        document.getElementById("paypalExpress_ecm").value="2";

        document.getElementById("TTT").value="";
      //  alert(document.getElementById('TTT').value);
        //document.getElementById("paypalExpressID_ecm").value="20";


        //elements[1].checked=true;
    };


    div2.onmouseover = function (e) {
        psdiv2.style.border="2px solid #c0c0c0";
        psdiv2.style.background="white";
    }
    div2.onmouseout = function (e) {
        if(!document.getElementById("pdiv2").getElementsByTagName('input')[0].checked)
        {
            psdiv2.style.border="none";
            psdiv2.style.background="none";
        }
    };
    div2.onclick = function (e) {
        document.getElementById("pdiv2").getElementsByTagName('input')[0].checked=true;
        psdiv1.style.border="none";
        psdiv1.style.background="none";

        document.getElementById("paypalExpressID_ecm").value="";
        document.getElementById("paypalExpress_ecm").value="";

        document.getElementById("TTT").value="TTT";

    };


    //   var e = e || window.event;
    //     var target = e.target || e.srcElement;
    //     if (this == target) alert("Вместо меня должно стоять модальное окно");

</script>
