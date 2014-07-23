<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: account.order_details.tpl.php 1362 2008-04-08 19:56:44Z soeren_nb $
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
mm_showMyFileName( __FILE__ );

if( $db->f('order_number')) {
?>	

	<?php if (empty( $print )) : ?>
<!--	<div class="pathway">--><?php //echo $vmPathway; ?><!--</div>-->
<!--	<div class="buttons_heading">-->
<!--	--><?php //echo vmCommonHTML::PrintIcon(); ?>
<!--	</div>-->
<!--	<br /><br />-->
	 <?php endif; ?>

<!--	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2">-->
<!--	  <tr>-->
<!--	    <td valign="top">-->
<!--	     <h2>--><?php //echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL') ?><!--</h2>-->
<!--	     <p>--><?php ////echo ps_vendor::formatted_store_address(true) ?><!--</p>-->
<!--	    </td>-->
<!--	    <td valign="top" width="50%" align="left">--><?php //echo $vendor_image; ?><!--</td>-->
<!--	  </tr>-->
<!--	</table>-->
	<?php
	if ( $db->f("order_status") == "P" ) {
		// Copy the db object to prevent it gets altered
		$db_temp = ps_DB::_clone( $db );
	 /** Start printing out HTML Form code (Payment Extra Info) **/ ?>

	<?php
		$db = $db_temp;
	}
	// END printing out HTML Form code (Payment Extra Info)
	?>
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
	  <!-- begin customer information --> 
	  <tr class="sectiontableheader"> 
	    <th align="left" colspan="2" class="gray_hdr" style="height: 60px;vertical-align: top;">Информация о заказе</th>
	  </tr>
	  <tr class="gray_tbl">
	    <td style="font-size: 12px;"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_NUMBER')?>:</td>
	    <td style="font-size: 14px;font-weight: bold;"><?php printf("%08d", $db->f("order_id")); ?></td>
	  </tr>
	
	  <tr class="gray_tbl">
		<td style="font-size: 12px;"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_DATE') ?>:</td>
	    <td style="font-size: 14px;font-weight: bold;"><?php echo vmFormatDate($db->f("cdate")+$time_offset); ?></td>
	  </tr>
<!--	  <tr class="gray_tbl">-->
<!--	    <td >--><?php //echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_STATUS') ?><!--:</td>-->
<!--	    <td>--><?php //echo ps_order_status::getOrderStatusName( $db->f("order_status") ); ?><!--</td>-->
<!--	  </tr>-->
	  <!-- End Customer Information --> 
	  <!-- Begin 2 column bill-ship to --> 

	  <tr valign="top">
	    <td width="50%"> <!-- Begin BillTo -->

	      <!-- End BillTo --> </td>
	    <td width="50%"> <!-- Begin ShipTo --> <?php
	    // Get ship_to information
	    $dbbt->next_record();
	    $dbst =& $dbbt;

	  ?>

	      <!-- End ShipTo --> 
	      <!-- End Customer Information --> 
	    </td>
	  </tr>
	  <tr> 
	    <td colspan="2">&nbsp;</td>
	  </tr>
	  <?php if ( $PSHOP_SHIPPING_MODULES[0] != "no_shipping" && $db->f("ship_method_id")) { ?> 
	  <tr> 
	    <td colspan="2"> 
	      <table width="100%" border="0" cellspacing="0" cellpadding="1" >
	        
	        <tr class="sectiontableheader"> 
	          <th align="left"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUST_SHIPPING_LBL') ?></th>
	        </tr>
	        <tr> 
	          <td> 
	            <table width="100%" border="0" cellspacing="0" cellpadding="0">
	              <tr> 
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_CARRIER_LBL') ?></strong></td>
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_MODE_LBL') ?></strong></td>
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PRICE') ?>&nbsp;</strong></td>
	              </tr>
	              <tr> 
	                <td><?php 
	                $details = explode( "|", $db->f("ship_method_id"));
	                echo $details[1];
	                    ?>&nbsp;
	                </td>
	                <td><?php 
	                echo $details[2];
	                    ?>
	                </td>
	                <td><?php 
	                     echo $CURRENCY_DISPLAY->getFullValue($details[3], '', $db->f('order_currency'));
	                    ?>
	                </td>
	              </tr>
	            </table>
	          </td>
	        </tr>
	        
	      </table>
	    </td>
	  </tr><?php
	  }
	
	  ?> 
	  <tr>
	    <td colspan="2">&nbsp;</td>
	  </tr>
	  <!-- Begin Order Items Information --> 
	  <tr class="sectiontableheader"> 
	    <th align="left" colspan="2" class="gray_hdr"><?php echo $VM_LANG->_('PHPSHOP_ORDER_ITEM') ?></th>
	  </tr>
	  <tr>
	    <td colspan="4">
	<?php
	$dbdl = new ps_DB;
    $dbdtest = new ps_DB;
	/* Check if the order has been paid for */
	if ($db->f("order_status") == ENABLE_DOWNLOAD_STATUS && ENABLE_DOWNLOADS) {
	
		$q = "SELECT `download_id` FROM #__{vm}_product_download WHERE";
		$q .= " order_id =" .(int)$vars["order_id"];
		$dbdl->query($q);

        $q = "SELECT `download_id` FROM #__{vm}_product_download WHERE";
        $q .= " order_id =" .(int)$vars["order_id"];
        $dbdtest->query($q);
		// $q = "SELECT * FROM #__{vm}_product_download WHERE order_id ='" . $db->f("order_id")
		// $dbbt->query($q);
	
	
		// check if download records exist for this purchase order
        $isPurchase=false;
		if ($dbdtest->next_record()) {
            $isPurchase=true;
			//echo "<b>" . $VM_LANG->_('PHPSHOP_DOWNLOADS_CLICK') . "</b><br /><br />";
	
			//echo($VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_3').DOWNLOAD_MAX.". <br />");
	
			//$expire = ((DOWNLOAD_EXPIRE / 60) / 60) / 24;
			//echo(str_replace("{expire}", $expire, $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_4')));
			
			//echo "<br /><br />";
		}
		//else {
			//echo "<b>" . $VM_LANG->_('PHPSHOP_DOWNLOADS_EXPIRED') . "</b><br /><br />";
		//}
	}
	?>
	    </td>
	  </tr>
	  <!-- END HACK EUGENE -->
	  <tr> 
	    <td colspan="2">
            <div class="tbl_ord_wrp">
	      <table  class="cart_tbl" style="width: 100%;">
	        <tr align="center" class="crt_tbl_hdr" style="font-size: 14px; padding: 13px;">
	          <td align="left"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_NAME') ?></td>
<!--	          <td>--><?php //echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PRICE') ?><!--</td>-->
                <?php
                if (!$isPurchase){
                ?>
	          <td>Цена</td>
                    <?php
                }else {
                        ?>
                    <td> </td>
                    <?php
                }
        ?>
	        </tr>
	        <?php 
	        $dbcart = new ps_DB;
	        $q  = "SELECT * FROM #__{vm}_order_item ";
	        $q .= "WHERE #__{vm}_order_item.order_id='$order_id' ";
	        $dbcart->query($q);
	        $subtotal = 0;
	        $dbi = new ps_DB;
	        while ($dbcart->next_record()) {
	
	        	/* BEGIN HACK EUGENE */
	        	/*HACK SCOTT had to retest order status else unpaid were able to download*/
	        	if ($db->f("order_status") == ENABLE_DOWNLOAD_STATUS && ENABLE_DOWNLOADS) {
	        		/* search for download record that corresponds to this order item */
	        		$q = "SELECT `download_id` FROM #__{vm}_product_download WHERE";
	        		$q .= " `order_id`=" . intval($vars["order_id"]);
	        		$q .= " AND `product_id`=". intval($dbcart->f("product_id"));
	        		$dbdl->query($q);
	
	        	}
	        	/* END HACK EUGENE */
	
	        	$product_id = null;
	        	$dbi->query( "SELECT product_id FROM #__{vm}_product WHERE product_sku='".$dbcart->f("order_item_sku")."'");
	        	$dbi->next_record();
	        	$product_id = $dbi->f("product_id" );
	?> 
	        <tr align="left"> 
	          <td style="font-size: 12px; text-align: left; " >
                  <?php

                   $i=0;
	               while ($dbdl->next_record()) {
                       $i++;
                       $day_num="(первый день)";
                       if ($i==2)
                           $day_num="(второй день)";
                       if ($i==3)
                           $day_num="(третий день)";
	        			// hyperlink the downloadable order item
	        			$url = $mosConfig_live_site."/index.php?option=com_virtuemart&page=shop.downloads";
	        			echo '<br><a  style="line-height:24px;" href="'."$url&download_id=".$dbdl->f("download_id").'">'
	        					. 'Скачать <img src="'.VM_THEMEURL.'images/download.png" alt="'.$VM_LANG->_('PHPSHOP_DOWNLOADS_CLICK').'" align="left" border="0" /> '.$day_num.' ';

	        					//. $dbcart->f("order_item_name")
	        					//. '</a>';
                      echo $dbcart->f("order_item_name");
                      echo '</a>';
					}
	        		if (!$isPurchase) {
			        	if( !empty( $product_id )) {
			          		echo '<a href="'.$sess->url( $mm_action_url."index.php?page=shop.product_details&product_id=$product_id") .'" title="'.$dbcart->f("order_item_name").'">';
			          	}
			          	$dbcart->p("order_item_name");
			          	echo " <div style=\"font-size:smaller;\">" . $dbcart->f("product_attribute") . "</div>";
			          	if( !empty( $product_id )) {
			          		echo "</a>";
			          	}
	        		}
			?>
	          </td>
<!--	          <td style="width: 50px; text-align: center;">-->
                  <?php /*
			$price = $ps_product->get_price($dbcart->f("product_id"));
			$item_price = $price["product_price"]; */
			if( $auth["show_price_including_tax"] ){
				$item_price = $dbcart->f("product_final_price");
			}
			else {
				$item_price = $dbcart->f("product_item_price");
			}
//			echo " &nbsp;".$CURRENCY_DISPLAY->getFullValue($item_price, '', 'руб.');
            //echo $item_price." руб."
	
	           ?>
<!--              </td>-->
                <?php
                if (!$isPurchase){
                    ?>
	          <td style="width: 100px; text-align: center;"><?php $total = $dbcart->f("product_quantity") * $item_price;
	          $subtotal += $total;
	          echo $CURRENCY_DISPLAY->getFullValue($total, '', 'руб.');
	           ?></td>
                    <?php
                }else{
                        ?>
<td> </td>
                    <?php
                }
                    ?>

	        </tr><?php
	        }
	?> 

<!--	        <tr> -->
<!--	          <td colspan="4" align="right">--><?php //echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SUBTOTAL') ?><!-- :</td>-->
<!--	          <td align="right">--><?php //echo $CURRENCY_DISPLAY->getFullValue($subtotal, '', $db->f('order_currency')) ?><!--&nbsp;&nbsp;&nbsp;</td>-->
<!--	        </tr>-->
	<?php 
	/* COUPON DISCOUNT */
	$coupon_discount = $db->f("coupon_discount");
	$order_discount = $db->f("order_discount");
	
	if( PAYMENT_DISCOUNT_BEFORE == '1') {
		if (($db->f("order_discount") != 0)) {
	?>

	        
	        <?php 
		}
		if( $coupon_discount > 0 ) {
	?>

	<?php
		}
	}
	?>        

	  <?php
	  $tax_total = $db->f("order_tax") + $db->f("order_shipping_tax");
	  if ($auth["show_price_including_tax"] == 0) {
	  ?>

	<?php
	  }
	  if( PAYMENT_DISCOUNT_BEFORE != '1') {
	  	if (($db->f("order_discount") != 0)) {
	?>

	        <?php 
	  	}
	  	if( $coupon_discount > 0 ) {
	?>

	<?php
	  	}
	  }
	?>

    <?php
    if (!$isPurchase){
        ?>

	        <tr style="border-top: 1px solid #d3d3d3">
	          <td  align="right">
                  <strong>Итого :</strong>
	          </td>

	          <td align="center" style="width: 100px;"><strong><?php
	          $total = $db->f("order_total");
	          echo $CURRENCY_DISPLAY->getFullValue($total, '', 'руб.');
	          ?></strong></td>
	        </tr>
        <?php
    }
              ?>


	      </table>
                </div>
	    </td>
	  </tr>
	 </table>
	  <!-- End Order Items Information --> 
	
        <div align="center" style="margin: 20px;">
          <button class="enter_button" onclick="javascript: document.location.href='/index.php?page=account.index&option=com_virtuemart&Itemid=1&typel=orders'; return false;">Вернуться</button>
        </div>

	<?php // }
	    /** Print out the customer note **/
	    if ( $db->f("customer_note") ) {
	    ?>
	    <table width="100%">
	      <tr>
	        <td colspan="2">&nbsp;</td>
	      </tr>
	      <tr class="sectiontableheader">
	        <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUSTOMER_NOTE') ?></th>
	      </tr>
	      <tr>
	        <td colspan="2">
	         <?php echo nl2br($db->f("customer_note"))."<br />"; ?>
	       </td>
	      </tr>
	    </table>
	    <?php
	    }
}
else {
	echo '<h4>'._LOGIN_TEXT .'</h4><br/>';
	include(PAGEPATH.'checkout.login_form.php');
	echo '<br/><br/>';
}