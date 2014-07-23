<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
* This is the Read-Only Basket Template. Its is included
* in the last Step of the Checkout. The difference to the
* normal Basket is: All Update/Delete buttons and the
* quantity Box are missing.
*
* @version $Id: ro_basket_b2c.html.php 1377 2008-04-19 17:54:45Z gregdev $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2004-2005 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
?>
<div class="cart_bread"><span style="margin-left: -109px;">Корзина</span><div class="cart_img"><img src="images/stories/cart_next.png"></div> <div class="cart_img2"><img src="images/stories/cart_next.png"></div><span>Оплата</span></div>
<div class="cart_white2">Оформление заказа</div>
<!--<div class="cart_header">Корзина</div>-->
<div class="tbl_wrp">
    <table class="cart_tbl2">

        <?php

        foreach( $product_rows as $product ) {
//        print_r( $product);
//        echo $product[product_sku];

            $db = new ps_DB;
            $q = "SELECT pr.product_full_image,pr.product_id,attr.product_id, attr.attribute_value FROM #__{vm}_product as pr,#__{vm}_product_attribute as attr " ;
            $q .= "WHERE pr.product_id=attr.product_id and pr.product_sku = '" .$product[product_sku] . "' ";

            $db->query( $q ) ;
            $db->next_record() ;

            $pimg = $db->f("product_full_image");

            $filename = $db->f("attribute_value");
            //echo $filename;
            $format="";
            if(strpos($filename,"avi")!=0)
                $format="(avi)";
            if(strpos($filename,"mp3")!=0)
                $format="(mp3)";
            if((strpos($filename,"doc")!=0)||(strpos($filename,"fb2")!=0)||(strpos($filename,"pdf")!=0))
                $format="(doc, fb2, pdf)";


            ?>
            <tr valign="top" class="<?php echo $product['row_color'] ?>">

                <td ><?php echo "<img class='cart_prod_img' src='/components/com_virtuemart/shop_image/product/$pimg'>";  ?></td>
                <td style="vertical-align: middle;width: 499px;" align='left'>
                    <?php echo $product['product_name']  ?>
                </td>
                <td style="vertical-align: middle;width: 70px;" align='center'>
                    <?php echo "<b style='font-size:11px;'>электронная версия<br>".$format  ."</b>"?>
                </td>

                <td align="right" style="vertical-align: middle;width: 90px;">
                    <?php echo $product['product_price'] ?>
                </td>
<!--                <td>-->
<!--                    --><?php //echo $product['delete_form'] ?>
<!--                </td>-->


            </tr>
            <?php } ?>
        <!--Begin of SubTotal, Tax, Shipping, Coupon Discount and Total listing -->
        <!--  <tr class="sectiontableentry1">-->
        <!--    <td colspan="4" align="right">--><?php //echo $VM_LANG->_('PHPSHOP_CART_SUBTOTAL') ?><!--:</td> -->
        <!--    <td colspan="3" align="right">--><?php //echo $subtotal_display ?><!--</td>-->
        <!--  </tr>-->
        <?php if( $discount_before ) { ?>
        <tr class="sectiontableentry1">
            <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT') ?>:
            </td>
            <td colspan="3" align="right"><?php echo $coupon_display ?></td>
        </tr>
        <?php }
        if( $shipping ) { ?>
            <tr class="sectiontableentry1">
                <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING') ?>: </td>
                <td colspan="3" align="right"><?php echo $shipping_display ?></td>
            </tr>
            <?php }
        if($discount_after) { ?>
            <tr class="sectiontableentry1">
                <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT') ?>:
                </td>
                <td colspan="3" align="right"><?php echo $coupon_display ?></td>
            </tr>
            <?php } ?>

        <tr class="sectiontableentry1" style="border-bottom:0px ;border-top: 2px solid rgb(213, 210, 210);">
            <td colspan="3" align='right' style="font-weight: bold; ;">Итого:</td>
            <td  align="right" ><strong><?php echo $order_total_display ?></strong></td>
            <td></td>

        </tr>
        <?php if ( $show_tax ) { ?>
        <!--  <tr class="sectiontableentry1">-->
        <!--        <td colspan="4" align="right" valign="top">--><?php //echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL_TAX') ?><!--: </td> -->
        <!--        <td colspan="3" align="right"">--><?php //echo $tax_display ?><!--</td>-->
        <!--  </tr>-->
        <?php } ?>

    </table>
</div>
