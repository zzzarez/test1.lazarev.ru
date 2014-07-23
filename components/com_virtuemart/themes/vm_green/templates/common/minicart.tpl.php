<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );


echo "<div class='cart_hdr_img'><a style='font-size:14px;color:white;' href='/index.php?page=shop.cart&option=com_virtuemart&Itemid=1'>Корзина</a></div>";
if($empty_cart) { ?>

    <div style="margin: 0 auto;vertical-align: middle;">
    <?php if(!$vmMinicart) { ?>

    <?php }
    //echo "<br>".$VM_LANG->_('PHPSHOP_EMPTY_CART')."<br><br>" ;
        echo "<br>";?>
        
    </div>
<?php }
else {
    $total_prod=0;
?>

  <script type="text/javascript">
<?php foreach ($minicart as $cart) {
          //echo"<!--".$cart['product_id']."-->";
          if((JRequest::getCmd( 'product_id' ) == $cart['product_id'])||(JRequest::getCmd( 'product_id' ) == '')){
          ?>
        document.getElementsById('sbm_btn<?php echo $cart['product_id']; ?>').forEach(function(sm) {
	    sm.value = 'В корзине';
	    sm.disabled = true;
        });
<?php }} ?>
  </script>
    <?php
    // Loop through each row and build the table
    foreach( $minicart as $cart ) {

		foreach( $cart as $attr => $val ) {
			// Using this we make all the variables available in the template
			// translated example: $this->set( 'product_name', $product_name );
			$this->set( $attr, $val );
		}
        if(!$vmMinicart) { // Build Minicart
            $total_prod++;
            ?>
            <div style="border-bottom: 1px dotted #949292;">
                <div class="vmCartPrice" style="float: left;font-size: 11px;width:137px; text-align: left;padding-left: 7px;padding-bottom: 10px; padding-top: 10px;">
            <?php //fix!!! 2111 echo $cart['quantity'] ?><!--&nbsp;x&nbsp;--> <a href="<?php echo $cart['url'] ?>"><?php echo $cart['product_name'] ?></a>
            </div>
                <div class="vmCartPrice" style="float: right;font-size: 9px;padding-right: 5px;padding-bottom: 10px; padding-top: 10px;">
            <?php echo $cart['price'] ?>
            </div>
            <br style="clear: both;" />
            </div>
            <?php
            echo $cart['attributes'];
        }
    }
}
if(!$vmMinicart) {
    if(!$empty_cart){?>
    <hr style="clear: both;display: none;" />
<?php }} ?>
<div style="float: left;font-size: 11px;padding: 10px;font-weight: bold;color: red;" >
    <input type="hidden" id="quantity_hlp" value="<?php echo $total_prod; ?>"/>
    <input type="hidden" id="totalprice_hlp" value="<?php echo str_replace(" ", "",preg_replace("/\D/","",substr_replace(strip_tags ($total_price),'',0,12))); ?>"/>
<?php echo $total_products ?>
</div>
<div style="float: right;font-size: 11px;padding-right: 10px;padding-top: 10px;font-weight: bold;color: red;">
<?php echo $total_price ?>
</div>
<?php if (!$empty_cart && !$vmMinicart) { ?>
    <br/><br style="clear:both;" /><div align="center" class="show_cart_text">
    <?php echo $show_cart ?>
    </div><br/>

<?php }
echo $saved_cart;
?>