<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<?php
if( sizeof($VM_BROWSE_ORDERBY_FIELDS) < 2 ) {
	return;
}
?>
<?php echo $VM_LANG->_('PHPSHOP_ORDERBY') ?>: 
<select class="inputbox" name="orderby" onchange="order.submit()">
<option value="product_name" ><?php echo $VM_LANG->_('PHPSHOP_SELECT') ?></option>
<!--<option value="rate">Популярность</option>-->
<?php
// SORT BY PRODUCT RATE
    if( in_array( 'rate', $VM_BROWSE_ORDERBY_FIELDS)) { ?>
        <option value="rate" <?php echo $orderby=="rate" ? "selected=\"selected\"" : "";?>>
            Популярность</option>
        <?php }?>

<?php
// SORT BY PRODUCT NAME
if( in_array( 'product_name', $VM_BROWSE_ORDERBY_FIELDS)) { ?>
        <option value="product_name" <?php echo $orderby=="product_name" ? "selected=\"selected\"" : "";?>>
        <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_NAME_TITLE') ?></option>
<?php
}
// SORT BY PRODUCT SKU
if( in_array( 'product_sku', $VM_BROWSE_ORDERBY_FIELDS)) { ?>
        <option value="product_sku" <?php echo $orderby=="product_sku" ? "selected=\"selected\"" : "";?>>
        <?php echo "Дата"//$VM_LANG->_('PHPSHOP_CART_SKU') ?></option>
        <?php
}
// SORT BY PRODUCT PRICE
  if (_SHOW_PRICES == '1' && $auth['show_prices'] && in_array( 'product_price', $VM_BROWSE_ORDERBY_FIELDS)) { ?>
                <option value="product_price" <?php echo $orderby=="product_price" ? "selected=\"selected\"" : "";?>>
        <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_PRICE_TITLE') ?></option><?php 
  } 
  // SORT BY PRODUCT CREATION DATE
if( in_array( 'product_cdate', $VM_BROWSE_ORDERBY_FIELDS)) { ?>
        <option value="product_cdate" <?php echo $orderby=="product_cdate" ? "selected=\"selected\"" : "";?>>
        <?php echo $VM_LANG->_('PHPSHOP_LATEST') ?></option>
        <?php
}
?>
</select>