<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 * This is the page that is shown when the order has been placed.
 * It is used to thank the customer for her/his order and show a link 
 * to the order details.
*
* @version $Id: checkout.thankyou.tpl.php 1364 2008-04-09 16:44:28Z soeren_nb $
* @package VirtueMart
* @subpackage themes
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.

* http://virtuemart.net
*/

mm_showMyFileName( __FILE__ );

global $VM_LANG;
?>
<div class="thnk_wrap">
<!--<h3>--><?php //echo $VM_LANG->_('PHPSHOP_THANKYOU') ?><!--</h3>-->
<!--<p>-->
<!-- 	--><?php //
// 	//echo vmCommonHTML::imageTag( VM_THEMEURL .'images/button_ok.png', 'Success', 'center', '48', '48' ); ?>
<!--   	--><?php ////echo $VM_LANG->_('PHPSHOP_THANKYOU_SUCCESS')?>
<!--  -->
<!---->
<!--	--><?php ////echo $VM_LANG->_('PHPSHOP_EMAIL_SENDTO') .": <strong>". $user->user_email . '</strong>'; ?><!--<br />-->
<!--</p>-->
<!-- Begin Payment Information -->
<?php
if( empty($auth['user_id'])) {
	return;
}
if ($db->f("order_status") == "P" ) {
	// Copy the db object to prevent it gets altered
	$db_temp = ps_DB::_clone( $db );
 /** Start printing out HTML Form code (Payment Extra Info) **/ ?>
    <div class="cart_bread3"><span>Корзина</span><div class="cart_img"><img src="images/stories/cart_next.png"></div><span>Оформление заказа</span><div class="cart_img"><img src="images/stories/cart_next.png"></div> </div>
    <div class="cart_white3">Оплата</div>
    <!--<div class="cart_header">Корзина</div>-->

    <b >Ваш заказ подтвержден</b>, пожалуйста выберите подходящий способ оплаты на следующей странице. Для перехода на нее нажмите кнопку "оплатить".

<table width="100%">
  <tr>
    <td width="100%" align="center">
    	<?php 
	    /**
	     * PLEASE DON'T CHANGE THIS SECTION UNLESS YOU KNOW WHAT YOU'RE DOING
	     */
	    // Try to get PayPal/PayMate/Worldpay/whatever Configuration File
	    @include( CLASSPATH."payment/".$db->f("payment_class").".cfg.php" );
	    
		$vmLogger->debug('Beginning to parse the payment extra info code...' );
		
	    // Here's the place where the Payment Extra Form Code is included
	    // Thanks to Steve for this solution (why make it complicated...?)
	    if( eval('?>' . $db->f("payment_extrainfo") . '<?php ') === false ) {
	    	$vmLogger->debug( "Error: The code of the payment method ".$db->f( 'payment_method_name').' ('.$db->f('payment_method_code').') '
	    	.'contains a Parse Error!<br />Please correct that first' );
	    }
	    else {
	    	$vmLogger->debug('Successfully parsed the payment extra info code.' );
	    }
	    // END printing out HTML Form code (Payment Extra Info)

      	?>
    </td>
  </tr>
</table>
<br />
<?php
$db = $db_temp;
}
?>
<b>После зачисления денежных средств, в зависимости от способа оплаты, на ваш email адрес будет отправлено письмо, содержащее ссылку на оплаченный заказ (до 3-х банковских дней).  </b>
    <hr>
    Если Вам пришло сообщение о смене статуса заказа на "Оплачено", но при этом Вы не получили письмо содержащее заказ, то Вы можете скачать оплаченные товары через "Личный кабинет".
<p>
	<a href="<?php $sess->purl(SECUREURL.basename($_SERVER['PHP_SELF'])."?page=account.order_details&order_id=". $order_id) ?>" onclick="if( parent.parent.location ) { parent.parent.location = this.href.replace(/index2.php/, 'index.php' ); };">
 		<?php echo $VM_LANG->_('PHPSHOP_ORDER_LINK') ?>
 	</a>
</p>

</div>
<div class="thnk_wrap2">
    <b style="color: red;">Обращаем внимание</b>, что товары в интернет-магазине представлены в электронном формате. После оплаты, их доставка осуществляется на Ваш E-mail адрес. <br />
    Для приобретения DVD дисков и печатных изданий, обращайтесь по контактам, указанным в разделе <a href="/index.php?option=com_content&amp;view=article&amp;id=6&amp;Itemid=39" style="font-size: 14px;">Доставка</a> нашего сайта.<br><br>

</div>


