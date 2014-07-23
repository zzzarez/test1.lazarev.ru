<?php if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.'); ?>

<?php //echo $buttons_header // The PDF, Email and Print buttons ?>

<?php
if ($this->get_cfg('showPathway')) {
//	echo "<div class=\"pathway\">$navigation_pathway</div>";
}
if ($this->get_cfg('product_navigation', 1)) {
    if (!empty($previous_product)) {
//		echo '<a class="previous_page" href="'.$previous_product_url.'">'.shopMakeHtmlSafe($previous_product['product_name']).'</a>';
    }
    if (!empty($next_product)) {
//		echo '<a class="next_page" href="'.$next_product_url.'">'.shopMakeHtmlSafe($next_product['product_name']).'</a>';
    }
}




?>
<br style="clear:both;"/>


<div class="fly_hdr_wrp">
    <div class="fly_bg_img">

        <img src="/images/<?php
        $img_name = "bg_disk.png";
        if ($files_format == "pdf, doc, fb2")
            $img_name = "bg_book.png";
        echo $img_name;?>">
    </div>
    <div class="fly_img">
        <?php echo $product_image; ?>
    </div>


    <div class="fly_right_wrapper">
        <div class="fly_name">
            <?php echo $product_name; ?>
        </div>
        <div class="files_details">
            <p><span class="green_hdr">Тип товара: </span><?php echo $prod_type . "<b style='color:white;'>" . $dura_sum . "</b>";?>
            </p>

            <p><span class="green_hdr">Формат: </span><?php echo $files_format; //$sizes_sum?></p>

            <p style="text-align: left;"><span class="green_hdr">Размер: </span>
                <br>
                <?php
                $i = sizeof($files_arr);
                $sta = '';
                $stf = '';

                if (strpos($product_sku, "app") == 0) {
                    for ($f = 0; $f < $i; $f++) {
                        //echo $f."<span class='green_hdr'>Имя файла: </span>".$files_arr[$f]." <br><span class='green_hdr'>Размер:</span> ".$files_sizes[$f]."<br><span class='green_hdr'>Формат: </span>".$files_formats[$f]."<br><br>";
                        if ($files_format != "pdf, doc, fb2") {
                            if ($prod_type == "Семинар")
                                echo "длительность -  4 часа ";
                            else {
                                if ($product_image == "film.jpg")
                                    echo ($f + 1) . " день - " . $files_sizes[$f] . ", длительность:  " . $files_dura[$f] . "<br>";
                                else {

                                    if (strpos($product_image, "audio_") != 0) {

                                        if (strpos($files_arr[$f], "zip") != 0)
                                            echo " Аудио книга по главам в архиве - " . $files_sizes[$f] . "<br>";

                                        if (strpos($files_arr[$f], "zip") == 0)
                                            echo " Аудио книга целиком - " . $files_sizes[$f] . ", длительность:  " . $files_dura[$f] . "<br>";


                                    } else
                                        echo ($f + 1) . " диск - " . $files_sizes[$f] . ", длительность:  " . $files_dura[$f] . "<br>";
                                }
                            }

                        } else {
                            $bf = "pdf";
                            if ($f == 1)
                                $bf = "fb2";
                            if ($f == 2)
                                $bf = "doc";
                            echo $bf . " - " . $files_sizes[$f] . "<br>";


                        }
                        //echo $sta.$stf;

                    }
                } else {
                    echo "Общий размер: " . $sizes_sum . " Mb";
                    echo "<br>Общая длительность: " . $dura_sum . " чч:мм:cc";
                }
                ?>
            </p>

        </div>
        <div class="fly_price">
            <span>Цена:</span><?php echo $product_price?>
            <div class="fly_addtocart">
                <?php echo $addtocart ?>
            </div>
            <hr class="fly_hr">

        </div>

        <div id="vote_my">
            <?php
            $product_rating = JHTML::_('content.prepare', '{extravote ' . $product_id . '}');
            echo $product_rating;
            ?>
        </div>
        <!--        <p id="vote_my"></p>-->

        <div class="fly_desc_wrp">
            <?php echo $product_description?>
        </div>
        <div class="fly_ask">
            <?php echo $ask_seller ?>
        </div>


    </div>
</div>




<?php


echo $product_reviews; ?>

<?php
//echo  $files_arr[0]."-----".$files_sizes[0]."----".$files_formats[0];
?>
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
if (!empty($recent_products)) {
    ?>
<!--	<div class="vmRecent">-->
<?php //echo $recent_products; ?>
<!--	</div>-->
<?php
}
if (!empty($navigation_childlist)) {
    ?>
<?php //echo $VM_LANG->_('PHPSHOP_MORE_CATEGORIES') ?><br/>
<?php //echo $navigation_childlist ?><br style="clear:both"/>
<?php
} ?>


