<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 

$button_lbl = $VM_LANG->_('PHPSHOP_CART_ADD_TO');
$button_cls = 'addtocart_button';
if( CHECK_STOCK == '1' && !$product_in_stock ) {
	$button_lbl = $VM_LANG->_('VM_CART_NOTIFY');
	$button_cls = 'notify_button';
}
?>
<!--
<form action="<?php echo $mm_action_url ?>index.php" method="post" name="addtocart" id="addtocart<?php echo $i ?>" class="addtocart_form"<?php if( $this->get_cfg( 'useAjaxCartActions', 1 ) && !$notify ) { echo 'onsubmit="handleAddToCart( this.id );return false;"'; } ?>
    <?php echo $ps_product_attribute->show_quantity_box($product_id,$product_id); ?><br />
	<input type="submit" class="addtocart_button_module" value="В корзину" title="<?php echo $button_lbl ?>" />
    <input type="hidden" name="category_id" value="<?php echo  @$_REQUEST['category_id'] ?>" />
    <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
    <input type="hidden" name="prod_id[]" value="<?php echo $product_id ?>" />
    <input type="hidden" name="page" value="shop.cart" />
    <input type="hidden" name="func" value="cartadd" />
    <input type="hidden" name="Itemid" value="<?php echo $sess->getShopItemid() ?>" />
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="set_price[]" value="" />
    <input type="hidden" name="adjust_price[]" value="" />
    <input type="hidden" name="master_product[]" value="" />
</form>
-->

    	<form action="<?php echo  $mm_action_url ?>index.php" method="post" name="addtocart" id="addtocart<?php echo $i ?>" onsubmit="handleAddToCart( this.id, '<?php echo $product_name; ?>','<?php echo str_replace(" ", "",preg_replace("/\D/","",strip_tags($product_price))) ; ?>' ); var sm=document.getElementById('sbm_btn<?php echo $product_id; ?>');sm.value='В корзине';sm.disabled=true;return false;">
            <input type="hidden" name="option" value="com_virtuemart" />
            <input type="hidden" name="page" value="shop.cart" />
            <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
            <input type="hidden" name="func" value="cartAdd" />
            <input type="hidden" name="prod_id" value="<?php echo $product_id; ?>" />
            <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
            <input type="hidden" name="quantity" value="1" />
            <input type="hidden" name="set_price[]" value="" />
            <input type="hidden" name="adjust_price[]" value="" />
            <input type="hidden" name="master_product[]" value="" />
            <?php echo $ps_product_attribute->show_quantity_box($product_id,$product_id); ?><br />
            <input  id='sbm_btn<?php echo $product_id; ?>' type="submit" class="addtocart_button_module" value="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>" title="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>" />
        </form>
