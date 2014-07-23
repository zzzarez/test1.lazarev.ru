<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<?php //echo $buttons_header // The PDF, Email and Print buttons ?>

<?php
if( $this->get_cfg( 'showPathway' )) {
//	echo "<div class=\"pathway\">$navigation_pathway</div>";
}
if( $this->get_cfg( 'product_navigation', 1 )) {
    if( !empty( $previous_product )) {
//		echo '<a class="previous_page" href="'.$previous_product_url.'">'.shopMakeHtmlSafe($previous_product['product_name']).'</a>';
    }
    if( !empty( $next_product )) {
//		echo '<a class="next_page" href="'.$next_product_url.'">'.shopMakeHtmlSafe($next_product['product_name']).'</a>';
    }
}
?>
<br style="clear:both;" />

22222<div class="fly_hdr_wrp">
    <div class="fly_img">
        <?php echo $product_image ?>
    </div>
    <div class="fly_name">
        <b align="left"><?php echo $product_name ?></b>
        <br><br><br>
        <?php echo $ask_seller ?>
    </div>
    <div class="fly_price">
        <?php echo $product_price?>
    </div>
    <?php echo $addtocart ?>
</div>
<div class="fly_desc_wrp">
    <?php echo $product_description?>
</div>



<!--<table border="0" style="width: 543px;">-->
<!--  <tbody>-->
<!--	<tr>-->
<?php // if( $this->get_cfg('showManufacturerLink') ) { $rowspan = 5; } else { $rowspan = 4; } ?>
<!--	  <td width="110" rowspan="--><?php //echo $rowspan; ?><!--" valign="top" style="padding-right:15px;"><br/>-->
<!--	  	--><?php //echo $product_image ?><!--<br/><br/>--><?php //echo $this->vmlistAdditionalImages( $product_id, $images ) ?><!--</td>-->
<!--	  <td rowspan="1" colspan="2">-->
<!--	  <h2>--><?php //echo $product_name ?><!-- --><?php //echo $edit_link ?><!--</h2>-->
<!--	  </td>-->
<!--	</tr>-->
<!--	--><?php //if( $this->get_cfg('showManufacturerLink')) { ?>
<!--		<tr>-->
<!--		  <td rowspan="1" colspan="2">--><?php //echo $manufacturer_link ?><!--<br /></td>-->
<!--		</tr>-->
<!--	--><?php //} ?>
<!--	<tr>-->
<!--      <td width="33%" valign="top" align="left">-->
<!--      	--><?php //echo $product_price_lbl ?>
<!--      	--><?php //echo $product_price ?><!--<br /></td>-->
<!--      <td valign="top">--><?php//// echo $product_packaging ?><!--<br /></td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td colspan="2">--><?php //echo $ask_seller ?><!--</td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td rowspan="1" colspan="2" ><hr />-->
<!--	  	--><?php //echo $product_description ?><!--<br/>-->
<!--	  </td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td>--><?php
//	  		if( $this->get_cfg( 'showAvailability' )) {
//	  			echo $product_availability;
//	  		}
//	  		?><!--<br />-->
<!--	  </td>-->
<!--	  <td colspan="2"><br />--><?php //echo $addtocart ?><!--</td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td colspan="3">--><?php //echo $product_type ?><!--</td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td colspan="3"><hr />--><?php //echo $product_reviews ?><!--</td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td colspan="3">--><?php //echo $product_reviewform ?><!--<br /></td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--	  <td colspan="3">--><?php //echo $related_products ?><!--<br />-->
<!--	   </td>-->
<!--	</tr>-->
<!--	--><?php //if( $this->get_cfg('showVendorLink')) { ?>
<!--		<tr>-->
<!--		  <td colspan="3"><div style="text-align: center;">--><?php //echo $vendor_link ?><!--<br /></div><br /></td>-->
<!--		</tr>-->
<!--	--><?php // } ?>
<!--  </tbody>-->
<!--</table>-->
<?php
if( !empty( $recent_products )) { ?>
<!--	<div class="vmRecent">-->
<?php //echo $recent_products; ?>
<!--	</div>-->
<?php
}
if( !empty( $navigation_childlist )) { ?>
<?php //echo $VM_LANG->_('PHPSHOP_MORE_CATEGORIES') ?><br />
<?php //echo $navigation_childlist ?><br style="clear:both"/>
<?php
} ?>
