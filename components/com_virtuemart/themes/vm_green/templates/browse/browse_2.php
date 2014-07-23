<div class="product_cont">
    <div class="fly_bg_img">

        <img src="/images/<?php
        $img_name="bg_disk.png";
        if($format=="pdf, doc, fb2")
            $img_name="bg_book.png";
        echo $img_name;?>">
    </div>
    <div class="prod_img">
        <a href="<?php echo $product_flypage ?>"><img src="<?php echo $product_full_image; ?>" ></a>
    </div>

    <div class="prod_name">
        <a href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a>
        <div class="prod_s_desc">
            <?php
            if (!function_exists('toCut')) {
                function toCut($str, $len = 920, $div = " ")
                {
                    if (strlen($str) <= $len) {
                        return $str;
                    } else {
                        $str = substr($str, 0, $len);
                        $pos = strrpos($str, $div);
                        $str = substr($str, 0, $pos);
                        return $str;
                    }
                }

                ;
            }



            echo toCut($product_s_desc,580);?>
            <?php
            if ($product_s_desc)
                echo " &nbsp;&nbsp;&nbsp;<a  href='".$product_flypage."'>подробнее&raquo;</a>";
            ?>
        </div>

    </div>
    <div class="prod_price">
        <?php echo $product_price; ?>
    </div>
    <div class="prod_format">
        <b>Формат:</b><br>
        <?php echo $format?>
    </div>

    <div class="buy_button">
        <?php echo $form_addtocart ?>
    </div>



</div>
<div class="prod_rate">
    <div style="position: absolute;width: 111px;height: 23px;margin-top: -1px;z-index: 33;">
    </div>

    <?php
$product_rating = JHTML::_('content.prepare', '{extravote '.$prod_id.'}');
echo $product_rating;
//echo $format;
?>
</div>
