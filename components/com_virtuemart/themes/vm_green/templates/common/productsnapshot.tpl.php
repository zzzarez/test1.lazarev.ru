<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>
 

<div class="prod_latest_img">
<a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>">
<!--fix !!!    <a title="--><?php //echo $product_name ?><!--" href="--><?php //echo $product_link ?><!--"><img src="--><?php //echo $mm_action_url?><!--images/stories/--><?php //echo $product_thumb_image; ?><!--" ></a>-->
	<?php
		// Print the product image or the "no image available" image
    if(($_GET['layout']=='blog')&&($_GET['id']==6))
    {//fix!!! components/com_virtuemart/shop_image/product
        echo "<img src='".$mm_action_url."/components/com_virtuemart/shop_image/product/".$product_thumb_image."' alt='".$product_name."'>";
		//echo ps_product::image_tag( $product_thumb_image, "alt=\"".$product_name."\"");
    }
    else
    {
        ?>
        <a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>"><img src="<?php echo $mm_action_url?>images/stories/<?php echo $product_thumb_image; ?>" ></a>
        <?php
    }
	?>
</a>
</div>
<div class="prod_latest_title">
    <a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>"><span style="font-weight:bold;"><?php echo $product_name ?></span></a>
</div>

<div class="wrap_price_form">
    <div class="prod_latest_price">
        <?php
        if (!empty($price)) {
            echo $price;
        }
        ?>
    </div>
    <div class="prod_latest_submit">
        <?php
        //var sm=document.getElementById('sbm_btn');sm.value='В корзине';sm.disabled=true;
        if (!empty($addtocart_link)) {
            ?>

            <form action="<?php echo  $mm_action_url ?>index.php" method="post" name="addtocart"
                  id="addtocart<?php echo $product_id; ?>" onsubmit="handleAddToCart( this.id, '<?php echo $product_name; ?>','<?php echo str_replace(" ", "",preg_replace("/\D/","",strip_tags($price))) ; ?>');document.getElementsById('sbm_btn<?php echo $product_id; ?>').forEach(function(sm) { sm.value='В корзине';sm.disabled=true; }); return false;">
                <input type="hidden" name="option" value="com_virtuemart"/>
                <input type="hidden" name="page" value="shop.cart"/>
                <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>"/>
                <input type="hidden" name="func" value="cartAdd"/>
                <input type="hidden" name="prod_id" value="<?php echo $product_id; ?>"/>
                <input type="hidden" name="product_id" value="<?php echo $product_id ?>"/>
                <input type="hidden" name="quantity" value="1"/>
                <input type="hidden" name="set_price[]" value=""/>
                <input type="hidden" name="adjust_price[]" value=""/>
                <input type="hidden" name="master_product[]" value=""/>
                <input id='sbm_btn<?php echo $product_id; ?>' type="submit" class="addtocart_button_module"
                       value="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>"
                       title="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>"/>
            </form>

            <?php
        }
        ?>
    </div>
</div>