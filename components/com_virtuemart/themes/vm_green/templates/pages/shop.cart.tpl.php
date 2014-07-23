<?php
//echo VM_THEMEURL."theme.php";
//if( file_exists( VM_THEMEPATH.'theme.php' )) {
  //  include( VM_THEMEPATH.'theme.php' );
//}
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.cart.tpl.php 1345 2008-04-03 20:26:21Z soeren_nb $
* @package VirtueMart
* @subpackage themes
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
mm_showMyFileName( __FILE__ );

//echo '<h2>'. $VM_LANG->_('PHPSHOP_CART_TITLE') .'</h2>
//';
//<!-- Cart Begins here -->

include(PAGEPATH. 'basket.php');
echo $basket_html;
echo '<!-- End Cart --><br />';

if ($cart["idx"]) {
    ?>
    <div align="center" class="continue_link">
    <?php
    if( $continue_link != '') {
		?>
<!--		 <a href="--><?php //echo $continue_link ?><!--" >-->
<!--		 	--><?php //echo $VM_LANG->_('PHPSHOP_CONTINUE_SHOPPING'); ?>
<!--		 </a>-->
		<?php
    }
   if (!defined('_MIN_POV_REACHED')) { ?>

       <span style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV2') . " ".$CURRENCY_DISPLAY->getFullValue($_SESSION['minimum_pov']) ?></span>
       <?php
   }
   else {//fix!!PHP_SELF

   		$href = $sess->url( $_SERVER['PHP_SELF'].'?page=checkout.index&ssl_redirect=1', true);
   		$href2 = $sess->url( $mm_action_url . "/index2.php?page=checkout.index&ssl_redirect=1", true);
   		$class_att = 'class="checkout_link"';
   		$text = "Оформить заказ";

   		if( $this->get_cfg('useGreyBoxOnCheckout', 1)) {
   			echo vmCommonHTML::getGreyBoxPopupLink( $href2, $text, '', $text, $class_att, 500, 600, $href );
              // echo '<input type="button" onclick="javascript:window.location(\'/index.php?page=checkout.index&ssl_redirect=1\',\'ssss\',\'_self\' );" name="aaa" class="enter_button" value="Оформить заказ">';
   		}
   		else {
   			echo vmCommonHTML::hyperlink( $href, $text, '', $text, $class_att );
   		}

//       if(("cartAdd" == $_POST['func'])||($_POST['func']=="cartadd"))

        if($_POST["func"] == "cartAdd")
        {

            header("Location:".$_SERVER['HTTP_REFERER']);
            exit;
        }

 	}
	?>
	</div>
<div class="thnk_wrap2">
    <b style="color: red;">Обращаем внимание</b>, что товары в интернет-магазине представлены в электронном формате. После оплаты, их доставка осуществляется на Ваш E-mail адрес. <br />
    Для приобретения DVD дисков и печатных изданий, обращайтесь по контактам, указанным в разделе <a href="/index.php?option=com_content&amp;view=article&amp;id=6&amp;Itemid=39" style="font-size: 14px;">Доставка</a> нашего сайта.<br><br>
</div>
	<?php
	// End if statement
}

?>