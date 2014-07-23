<?php

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

class vm_ps_order {

    /**
     * Changes the status of an order
     * @author pablo
     * @author soeren
     * @author Uli
     *
     *
     * @param array $d
     * @return boolean
     */
    function order_status_update(&$d) {
        global $mosConfig_offset;
        global $sess, $VM_LANG, $vmLogger;

        $db = new ps_DB;
        //$timestamp = time() + ($mosConfig_offset*60*60);  //Original
        $timestamp = time();  //Custom
        //$mysqlDatetime = date("Y-m-d G:i:s",$timestamp);  //Original
        $mysqlDatetime = date("Y-m-d G:i:s", $timestamp + ($mosConfig_offset * 60 * 60));  //Custom

        if (empty($_REQUEST['include_comment'])) {
            $include_comment = "N";
        }

        // get the current order status
        $curr_order_status = @$d["current_order_status"];
        $notify_customer = empty($d['notify_customer']) ? "N" : $d['notify_customer'];
        if ($notify_customer == "Y") {
            $notify_customer = 1;
        }
        else {
            $notify_customer = 0;
        }

        $d['order_comment'] = empty($d['order_comment']) ? "" : $d['order_comment'];
        if (empty($d['order_item_id'])) {
            if ($curr_order_status == "P" && $d["order_status"] == "X") {
                $q = "SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ";
                $q .= "#__{vm}_order_payment.order_id='" . $db->getEscaped($d['order_id']) . "' ";
                $q .= "AND #__{vm}_orders.order_id='" . $db->getEscaped($d['order_id']) . "' ";
                $q .= "AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id";
                $db->query($q);
                $db->next_record();
                $payment_class = strtolower(basename($db->f("payment_class")));
                if (file_exists(CLASSPATH . 'payment/' . $payment_class . '.php')) {
                    require_once( CLASSPATH . "payment/$payment_class.php");
                    $payment = new $payment_class();
                    $d["order_number"] = $db->f("order_number");
                    if (is_callable(array($payment, 'void_authorization'))) {
                        if (!$payment->void_authorization($d)) {
                            return false;
                        }
                    }
                }
            }

            // Do a Refund
            if ($d['order_status'] == 'R' && $curr_order_status != 'R') {
                $vmLogger->debug("Initiating Refund");
                $q = 'SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ';
                $q .= '#__{vm}_orders.order_id=\'' . $db->getEscaped($d['order_id']) . '\' ';
                $q .= 'AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id ';
                $q .= 'AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id';
                $db->query($q);
                $db->next_record();
                $payment_class = strtolower(basename($db->f("payment_class")));
                $vmLogger->debug('Payment Class: ' . $payment_class);
                if (file_exists(CLASSPATH . 'payment/' . $payment_class . '.php')) {
                    $vmLogger->debug('Found Payment Module');
                    require_once( CLASSPATH . "payment/$payment_class.php");
                    $payment = new $payment_class();
                    $d["order_number"] = $db->f("order_number");
                    if (is_callable(array($payment, 'do_refund'))) {
                        $vmLogger->debug('Can call do_refund');
                        if (!$payment->do_refund($d)) {
                            $vmLogger->debug('failed to do refund');
                            return false;
                        }
                    }
                }
            }

            $fields = array('order_status' => $d["order_status"],
                'mdate' => $timestamp);
            $db->buildQuery('UPDATE', '#__{vm}_orders', $fields, "WHERE order_id='" . $db->getEscaped($d["order_id"]) . "'");
            $db->query();

            // Update the Order History.
            $fields = array('order_id' => $d["order_id"],
                'order_status_code' => $d["order_status"],
                'date_added' => $mysqlDatetime,
                'customer_notified' => $notify_customer,
                'comments' => $d['order_comment']
            );
            $db->buildQuery('INSERT', '#__{vm}_order_history', $fields);
            $db->query();

            // Do we need to re-update the Stock Level?
            if ((strtoupper($d["order_status"]) == "X" || strtoupper($d["order_status"]) == "R")
                // && CHECK_STOCK == '1'
                && $curr_order_status != $d["order_status"]
            ) {
                // Get the order items and update the stock level
                // to the number before the order was placed
                $q = "SELECT product_id, product_quantity FROM #__{vm}_order_item WHERE order_id='" . $db->getEscaped($d["order_id"]) . "'";
                $db->query($q);
                $dbu = new ps_DB;
                require_once( CLASSPATH . 'ps_product.php');
                // Now update each ordered product
                while ($db->next_record()) {
                    if (ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($db->f("product_id")) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
                        $q = "UPDATE #__{vm}_product  
								SET product_sales=product_sales-" . $db->f("product_quantity") . " 
							WHERE product_id=" . $db->f("product_id");
                        $dbu->query($q);
                    }
                    else {
                        $q = "UPDATE #__{vm}_product 
							SET product_in_stock=product_in_stock+" . $db->f("product_quantity") . ",
								product_sales=product_sales-" . $db->f("product_quantity") . " 
							WHERE product_id=" . $db->f("product_id");
                        $dbu->query($q);
                    }
                }
            }
            // Update the Order Items' status
            $q = "SELECT order_item_id FROM #__{vm}_order_item WHERE order_id=" . $db->getEscaped($d['order_id']);
            $db->query($q);
            $dbu = new ps_DB;
            while ($db->next_record()) {
                $item_id = $db->f("order_item_id");
                $fields = array('order_status' => $d["order_status"],
                    'mdate' => $timestamp);
                $dbu->buildQuery('UPDATE', '#__{vm}_order_item', $fields, "WHERE order_item_id='" . (int) $item_id . "'");
                $dbu->query();
            }

            if (ENABLE_DOWNLOADS == '1') {
                ##################
                ## DOWNLOAD MOD
                //$this->mail_download_id($d);
                $this->mail_download_id_onpay( $d );

            }

            if (!empty($notify_customer)) {
                $this->notify_customer($d);
            }
        }
        elseif (!empty($d['order_item_id'])) {
            // Update the Order Items' status
            $q = "SELECT order_item_id, product_id, product_quantity FROM #__{vm}_order_item 
							WHERE order_id=" . $db->getEscaped($d['order_id'])
                . ' AND order_item_id=' . intval($d['order_item_id']);
            $db->query($q);
            $item_product_id = $db->f('product_id');
            $item_product_quantity = $db->f('product_quantity');
            require_once( CLASSPATH . 'ps_product.php' );
            if (ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($item_product_id) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
                $q = "UPDATE #__{vm}_product  
								SET product_sales=product_sales-" . $item_product_quantity . " 
							WHERE product_id=" . $item_product_id;
                $db->query($q);
            }
            else {
                $q = "UPDATE #__{vm}_product 
							SET product_in_stock=product_in_stock+" . $item_product_quantity . ",
								product_sales=product_sales-" . $item_product_quantity . " 
							WHERE product_id=" . $item_product_id;
                $db->query($q);
            }

            $fields = array('order_status' => $d["order_status"],
                'mdate' => $timestamp);
            $db->buildQuery('UPDATE', '#__{vm}_order_item', $fields, 'WHERE order_item_id=' . intval($d['order_item_id']));
            return $db->query() !== false;
        }
        return true;
    }

    /**
     * mails the Download-ID to the customer
     * or deletes the Download-ID from the product_downloads table
     *
     * @param array $d
     * @return boolean
     */
    function mail_download_id(&$d) {

        global $sess, $VM_LANG, $vmLogger;

        $url = URL . "index.php?option=com_virtuemart&page=shop.downloads&Itemid=" . $sess->getShopItemid();

        $db = new ps_DB();
        $db->query('SELECT order_status FROM #__{vm}_orders WHERE order_id=' . (int) $d['order_id']);
        $db->next_record();

        if (in_array($db->f("order_status"), array(ENABLE_DOWNLOAD_STATUS, 'S'))) {
            $dbw = new ps_DB;

            $q = "SELECT order_id,user_id,download_id,file_name FROM #__{vm}_product_download WHERE";
            $q .= " order_id = '" . (int) $d["order_id"] . "'";
            $dbw->query($q);
            $dbw->next_record();
            $userid = $dbw->f("user_id");
            $download_id = $dbw->f("download_id");
            $datei = $dbw->f("file_name");
            $dbw->reset();

            if ($download_id) {

                $dbv = new ps_DB;
                $q = "SELECT * FROM #__{vm}_vendor WHERE vendor_id='1'";
                $dbv->query($q);
                $dbv->next_record();

                $db = new ps_DB;
                $q = "SELECT first_name,last_name, user_email FROM #__{vm}_user_info WHERE user_id = '$userid' AND address_type='BT'";
                $db->query($q);
                $db->next_record();

                $message = $VM_LANG->_('HI', false) . ' ' . $db->f("first_name") . ($db->f("middle_name") ? ' ' . $db->f("middle_name") : '' ) . ' ' . $db->f("last_name") . ",\n\n";
                $message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_1', false) . ".\n";
                $message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_2', false) . "\n\n";

                while ($dbw->next_record()) {
                    $message .= basename($dbw->f("file_name")) . ": " . $dbw->f("download_id")
                        . "\n$url&download_id=" . $dbw->f("download_id") . "\n\n";
                }

                $message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_3', false) . DOWNLOAD_MAX . "\n";
                $expire = ((DOWNLOAD_EXPIRE / 60) / 60) / 24;
                $message .= str_replace("{expire}", $expire, $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_4', false));
                $message .= "\n\n____________________________________________________________\n";
                $message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_5', false) . "\n";
                $message .= $dbv->f("vendor_name") . " \n" . URL . "\n\n" . $dbv->f("contact_email") . "\n";
                $message .= "____________________________________________________________\n";
                $message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_6', false) . $dbv->f("vendor_name");


                $mail_Body = $message;
                $mail_Subject = $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_SUBJ', false);
                $from = $dbv->f("contact_email") ? $dbv->f("contact_email") : $GLOBALS['mosConfig_mailfrom'];
                $result = vmMail($from, $dbv->f("vendor_name"), $db->f("user_email"), $mail_Subject, $mail_Body, '');

                if ($result) {
                    $vmLogger->info($VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG', false) . " " . $db->f("first_name") . " " . $db->f("last_name") . " " . $db->f("user_email"));
                }
                else {
                    $vmLogger->warning($VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_SEND', false) . " " . $db->f("first_name") . " " . $db->f("last_name") . ", " . $db->f("user_email"));
                }
            }
        }
        elseif (in_array(vmGet($d, 'order_status'), array(DISABLE_DOWNLOAD_STATUS, 'X', 'R'))) {
            $q = "DELETE FROM #__{vm}_product_download WHERE order_id=" . (int) $d["order_id"];
            $db->query($q);
            $db->next_record();
        }

        return true;
    }


    /* For OnPay.ru plug-in - delete session. Corrected by Norgen */
//    function  getProductName($id)
//    {
//        $db = new ps_DB();
//        $db->query("SELECT product_name FROM #__{vm}_product WHERE product_id=".$id);
//        $db->next_record();
//        return $db->f('product_name');
//    }


    function haveProducts($order_id) {
        $dbf = new ps_DB;
        $q = "SELECT file_name FROM #__{vm}_product_download WHERE";
        $q .= " order_id = '" . $order_id . "'";
        $dbf->query($q);

        $ret= false;
        while ($dbf->next_record()) {
            $datei = $dbf->f("file_name");
            if ((!strpos($datei, "film"))&&(!strpos($datei, "_view"))&&(strlen($datei)!=0))
                $ret=true;
        }
//        $result = vmMail( "zarez@yandex.ru", "vendor", 'zarez@yandex.ru', "testhaveprod", "mb", '',true );
        return $ret;
    }
    function getViewType($order_id) {
        $dbf = new ps_DB;
        $q = "SELECT file_name FROM #__{vm}_product_download WHERE";
        $q .= " order_id = '" . $order_id . "'";
        $dbf->query($q);

        $type_view1=false;
        $type_view2=false;
        $isFilmtype=false;

        while ($dbf->next_record()) {
            $datei = $dbf->f("file_name");
            if (strpos($datei, "_view1"))
                $type_view1=true;
            if (strpos($datei, "_view2"))
                $type_view2=true;
            if (strpos($datei, "_film"))
                $isFilmtype=true;
        }
        $ret=0;
        if (($type_view1)&&($type_view2))
            $ret=3;
        if (($type_view1)&&(!$type_view2))
            $ret=1;
        if ((!$type_view1)&&($type_view2))
            $ret=2;
        if ($isFilmtype)
            $ret=4;
        return $ret;
    }
    function insertPlayerIds($order_id)
    {
        $dbf = new ps_DB;
        $q = "SELECT pd.file_name as file,pd.download_id as did,pd.user_id,us.user_email as email,us.user_id FROM #__{vm}_product_download pd, #__{vm}_user_info us WHERE ";
        $q .= " us.user_id=pd.user_id and pd.order_id = '" . $order_id . "' group by did";
        //SELECT pd.file_name as fname,pd.download_id as did,pd.user_id,us.user_email as email,us.user_id FROM jos_vm_product_download pd, jos_vm_user_info us WHERE
        //us.user_id=pd.user_id and  pd.order_id = '903'
        $dbf->query($q);

        $hlog=fopen('/var/www/shop.lazarev.ru/fortest.log','a+');
        fwrite($hlog,"\r\n". $q);
        fclose($hlog);

        $i=0;
        while ($dbf->next_record()) {
            $filename = $dbf->f("file");
            $did = $dbf->f("did");
            $email = $dbf->f("email");

            if (strpos($filename, "_view1"))
            {
                $dbv1 = new ps_DB;
                $q = "update player_ids set email='$email',downloadid='$did'  WHERE trans_date='1' and downloadid is null limit 1";
                //update player_ids set email='aaaaa' ,downloadid='bbb' WHERE trans_date='1' and downloadid is null limit 1
                $dbv1->query($q);
                $i++;
//                $hlog=fopen('/var/www/shop.lazarev.ru/fortest.log','a+');
//                fwrite($hlog,"\r\n". $q);
//                fclose($hlog);

            }
            if (strpos($filename, "_view2"))
            {
                $dbv1 = new ps_DB;
                $q = "update player_ids set email='$email',downloadid='$did'  WHERE trans_date='2' and downloadid is null limit 1";
                $dbv1->query($q);
                $i++;
//                $hlog=fopen('/var/www/shop.lazarev.ru/fortest.log','a+');
//                fwrite($hlog,"\r\n". $q);
//                fclose($hlog);

            }
        }

        return $i;
    }

    function isApp($order_id)
    {
        $ret="";
        $dbw = new ps_DB;
        $dbp = new ps_DB();
        $q = "SELECT order_id,user_id,download_id,file_name,product_id,end_date FROM #__{vm}_product_download WHERE";
        $q .= " order_id = '" . $order_id . "' order by file_name asc";
        $dbw->query($q);
        $dbw->next_record();
        while ($dbw->next_record()) {
            $dbp->query("SELECT product_name,product_sku FROM #__{vm}_product WHERE product_id=".$dbw->f("product_id"));
            $dbp->next_record();
            if(strpos($dbp->f('product_sku'),"_app")!=0)
                $ret = $dbp->f('product_sku');
        }
        return $ret;
    }
    function mail_download_id_onpay(&$d) {



        //global $sess,	$VM_LANG, $vmLogger;
        //$url = URL."index.php?option=com_virtuemart&page=shop.downloads&Itemid=".$sess->getShopItemid();
        $url = URL . "index.php?option=com_virtuemart&page=shop.downloads&Itemid=27";

        $ord_id="".(int) $d['order_id'];

        $db = new ps_DB();
        $db->query('SELECT order_status FROM #__{vm}_orders WHERE order_id=' . $ord_id);
        $db->next_record();

        if ($db->f("order_status") == ENABLE_DOWNLOAD_STATUS) {


            $prod_sku= $this->isApp($ord_id);

            $dbw = new ps_DB;

            $q = "SELECT order_id,user_id,download_id,file_name,product_id,end_date FROM #__{vm}_product_download WHERE";
            $q .= " order_id = '" . $ord_id . "' order by file_name asc";
            $dbw->query($q);
            $dbw->next_record();
            $userid = $dbw->f("user_id");
            $download_id = trim($dbw->f("download_id"));
            $prod_id=$dbw->f("product_id");
            $prod_date=$dbw->f("end_date");
            $datei = $dbw->f("file_name");
            $dbw->reset();


            $view_type=$this->getViewType($ord_id);
            $haveproducts=$this->haveProducts($ord_id);
            $insert_player_ids = $this->insertPlayerIds($ord_id);
            
            $isView=false;
            $isFilm=false;
            if (strpos($datei, "_view"))
            {
                $isView=true;
            }
            if (strpos($datei, "_film"))
            {
                $isFilm=true;
            }

            if ($download_id) {
                $dbv = new ps_DB;
                $q = "SELECT * FROM #__{vm}_vendor WHERE vendor_id='1'";
                $dbv->query($q);
                $dbv->next_record();

                $db = new ps_DB;
                $q = "SELECT first_name,last_name, user_email FROM #__{vm}_user_info WHERE user_id = '$userid' AND address_type='BT'";
                $db->query($q);
                $db->next_record();
                $style = include(CLASSPATH .'mail_css.php');
                $message = '<body style="'. $style['body'] .'"><div style="'. $style['top'] .'"></div><div style="'. $style['content'] .'"><b>Уважаемый(ая) '. $db->f("first_name") . ($db->f("middle_name") ? ' ' .$db->f("middle_name") : '') . '!</b><br>';
                $message .= 'Благодарим Вас за покупку в интернет-магазине <a style="'. $style['link'] .'" href="http://shop.lazarev.ru">shop.lazarev.ru</a><br><br>';
                $message .= 'Вами оплачен заказ <b>№ '. $ord_id .'</b><br>';
                $message2 = $message;
                $message .= 'Скачать его в доступном формате, Вы можете нажав на соответствующую ссылку<br>';

                $message3 ='<body style="'. $style['body2'] .'"><div style="'. $style['content2'] .'"><b>Уважаемый(ая) '. $db->f("first_name") . ($db->f("middle_name") ? ' ' .$db->f("middle_name") : '') . '!</b><br>';
                $message3 .='Благодарим Вас за покупку в интернет-магазине <a style="'. $style['link'] .'" href="http://shop.lazarev.ru">shop.lazarev.ru</a><br><br>';
                $message3 .= 'Вами оплачен заказ <b>№ '. $ord_id .'</b><br> Чтобы скачать его перейдите на ссылку ниже:';
                $message3 .= '<div style="'. $style['filmcontent'] .'">';
                $message3 .= '<div style="'. $style['filmtopic'] .'">&laquo;ПОМНИТЬ О ГЛАВНОМ&raquo;</div>';
                $message3 .= 'специальное видеоиздание, которое содержит 2 диска:<br>';



                //$message2 .= "Здесь Вы можете скачать оплаченный заказ <b>N ".$ord_id."</b> <br><br>";


                //$result = vmMail( "zarez@yandex.ru", "vendor", 'zarez@yandex.ru', "testhaveprod", "mb".$haveproducts, '',true );
                /*
                if ($isView){
                    $ForS="";
                    $ss1="даннной ссылке";
                    if ($view_type==1)
                        $ForS="13 апреля (первый день семинара)";
                    if ($view_type==2)
                        $ForS="14 апреля (второй день семинара)";
                    if ($view_type==3)
                    {
                        $ForS="13 и 14 апреля ";
                        $ss1="данным ссылкам";
                    }

                    $message .= "Благодарим Вас за покупку электронного билета на онлайн-трансляцию семинара !\n\n<br><br><hr><b>Семинар Лазарева С.Н. <br>г. Санкт-Петербург,\n13 и 14 апреля 2013 года<br> 11.00 – 15.00 по московскому времени.</b><hr><br> \n\n";
                    $message .= "Оплаченный заказ <b>N ".$ord_id." </b><br><br>\n";
                    $message .= "Ваш индивидуальный доступ на онлайн-трансляцию расположен ниже.<br>\nПо ".$ss1." Вы сможете перейти на страницу просмотра семинара, который состоится ".$ForS." в 11.00 по московскому времени.\n<br>";
                }
                */


                //" от ".date("m.d.y  H:i:s",time()).
                $i=0;
                $product = '';
                $book = array();
                $video = array();
                $audio = array();
                $lecture = array();
                $old_name='';
                $dnum=1;
                while ($dbw->next_record()) {
                    $i++;
                    $dbp = new ps_DB();
                    $dbp->query("SELECT product_name,product_sku FROM #__{vm}_product WHERE product_id=".$dbw->f("product_id"));
                    $dbp->next_record();
                    $prod_name= $dbp->f('product_name');
                    //$prod_sku= $dbp->f('product_sku');


                    $datei = $dbw->f("file_name");
                    $download_id = $dbw->f("download_id");

                    if (strpos($datei, "_view1"))
                        $format="view1";
                    if (strpos($datei, "_view2"))
                        $format="view2";

                    if(strpos($datei,"avi")!=0)
                        $format="avi";
                    if(strpos($datei,"mp3")!=0)
                        $format="mp3";
                    if(strpos($datei,"doc")!=0)
                        $format="doc";
                    if(strpos($datei,"fb2")!=0)
                        $format="fb2";
                    if(strpos($datei,"pdf")!=0)
                        $format="pdf";
                    if (strpos($datei, "_film1"))
                        $format="film1";
                    if (strpos($datei, "_film2"))
                        $format="film2";
                      
                      switch ($format)
                      {
                        case "doc":
                        case "fb2":
                        case "pdf":
                          $class = 'book';
                          break;
                        case "avi":
                          $format = 'avi'.$i;
                          $class = 'video';
                          break;
                        case "mp3":
                          $format = 'mp3'.$i;
                          $class = 'audio';
                          break;
                        case "view1":
                        case "view2":
                          $class = 'lecture';
                          break;
                        case "film1":
                        case "film2":
                          $class = 'film';
                          break;
                      }
                    $url=str_replace(' ','',$url);
                    $download_id=str_replace(' ','',$download_id);




                      $tmp = array($format => $url .'&download_id='. $download_id);

                      if (is_array(${$class}[$prod_name]))
                        ${$class}[$prod_name] = array_merge(${$class}[$prod_name], $tmp);
                      else
                        ${$class}[$prod_name] = $tmp;
                    
                    //$message2 .= "<br>".$prod_name . ", формат ".$format." , код продукта:".$dbw->f("download_id")." ,имя файла:".$dbw->f("file_name").", <br><b>ссылка для скачивания:</b> <a href='$url&download_id=" . $dbw->f("download_id") . "'>".$prod_name."</a><br><br>";
                    /*
                    $isView1=false;
                    $isView2=false;

                    if (strpos($datei, "_view1"))
                        $isView1=true;
                    if (strpos($datei, "_view2"))
                        $isView2=true;

                    if ($isView){
                        $tmptxt="";
                        if($isView1){
                            $tmptxt= "Ссылка доступа на страницу онлайн-трансляции: 13 апреля";
                        }
                        if($isView2){
                            $tmptxt= "Ссылка доступа на страницу онлайн-трансляции: 14 апреля";
                        }
                        $message .= "<br><a href='$url&download_id=" . $dbw->f("download_id") . "'>$tmptxt </a><br>\n\n";
                    }
                    */
                }
                
                $content_order = array(
                  'book',
                  'video',
                  'audio',
                  'lecture',
                  'film',
                );
                
                $date = array(
                    1 => '16',
                    2 => '17',
                    3 => '16 и 17',
                    'month' => 'ноября',
                    'year' => '2013',
                );
                
                foreach ($content_order as $content)
                {
                  if (!empty(${$content}))
                  {
                    if ($content == 'lecture')
                    {
                      $msg = '<br><div style="'. $style['lecture'] .'">';
                      $msg .= 'Семинар Лазарева С.Н.<br>';
                      $msg .= 'г. Москва, '. $date[$view_type] .' '. $date['month'] .' '. $date['year'] .' года<br>';
                      $msg .= '11.00 - 15.00 по московскому времени.<br>';
                      $msg .= '</div><br>';
                      $msg .= 'Перейти к просмотру Вы можете по индивидуальным ссылкам, указанным ниже.<br><br>';
                    }
                    else
                      $msg = '<br><div style="'. $style[$content.'_h'] .'"></div><div style="'. $style[$content] .'">';
                    $first_pass = true;
                    foreach (${$content} as $item => $formats)
                    {
                      if ($first_pass) $first_pass = false;
                      else if ($content != 'lecture') $msg .= '<hr><br>';
                      else $msg .= '<br>';
                      if ($content != 'lecture' && $content != 'book' && $content !='film' ) $msg .= '<b>'. $item .'</b><br><br>';
                      if ($content == 'book')
                      {
                        $msg .= '<a style="'. $style['book_link'] .'" href="'. $formats['pdf'] .'"><b>'. $item .'</b></a><br><br>';
                        $msg .= 'Формат: ';
                      }
                      $first_pass_format = true;
                      $i = 0;
                      foreach ($formats as $format => $link)
                      {
                        $i++;
                        if ($first_pass_format) $first_pass_format = false;
                        else if ($content == 'book') $msg .= ', ';
                        else $msg .= '<br>';
                        $type = $format;
                        if (strpos('_'.$format, "avi"))
                        {
                          $type = "download".$i;
                          $format = "avi";
                        }
                        if (strpos('_'.$format, "mp3"))
                        {
                          $type = "download".$i;
                          $format = "mp3";
                        }
                        $br_link=false;


                        switch ($type)
                        {
                          case "download1":
                            $txt = "Скачать первый день";
                            break;
                          case "download2":
                            $txt = "Скачать второй день";
                            break;
                          case "download3":
                            $txt = "Скачать третий день";
                            break;
                          case "view1":
                                $txt = "Ссылка доступа на страницу онлайн-трансляции ". $date[1] ." ". $date['month'];
                                $br_link=true;
                                break;
                          case "view2":
                            $txt = "Ссылка доступа на страницу онлайн-трансляции ". $date[2] ." ". $date['month'];
                            break;
                          case "film1":
                                $txt = "Диск 1. Помнить о главном  -  скачать";
                                $br_link=true;
                                break;
                          case "film2":
                                $txt = "Диск 2. Фильм &Prime;Картина жизни&Prime; -  скачать";
                                $br_link=true;
                                break;
                          default:
                            $txt = $type;
                        }
                        $link=str_replace(' ','',$link);

                          if(strpos($prod_sku,"_app")!=0)//for add name of files in pack-mails
                          {
                              $did=substr($link,strpos($link,'download_id=')+12);
$sql="SELECT pf.file_product_id, pf.file_name, pr.product_name as p_name FROM  jos_vm_product_files pf,jos_vm_product pr WHERE pf.file_product_id=pr.product_id   and  pf.file_name = (select file_name FROM jos_vm_product_download where download_id='$did') order by pf.file_product_id limit 0,1";
                              $dbt = new ps_DB();
                              $dbt->query($sql);
                              $dbt->next_record();
                              $p_name= $dbt->f('p_name');

                              if( $old_name == $p_name )
                              {
                                  $dnum++;

                              }
                              else
                              {
                                  $dnum=1;
                              }

                              $old_name=$p_name;

                              $txt=$p_name." (часть ".$dnum.")";
                          }

                        if (count($formats) == 1 && $content != 'lecture') $txt = 'Скачать файл';
                        $msg .= "\r\n<a style='". $style['link'] ."' href='". $link ."'>\r\n". $txt ."\r\n</a>";
                        if($br_link)
                             $msg .='<br>';
                      }
                      if ($format == 'avi' || $format == 'mp3')
                        $msg .= '<div style="'. $style['format'] .'">Формат: '. $format .'</div>';
                    }
                    if ($content != 'lecture')
                      $message .= $msg . '</div>';
                    else
                      $message2 .= $msg .'<br><br>Желаем приятного просмотра!<br><br><br>';

                    if ($content == 'film')
                    {
                        $message3 .= $msg ."</div><br><br><img src='http://shop.lazarev.ru/images/p1.png'><br><br>Желаем приятного просмотра!<br><br><br>";
                    }

                  }
                }

                //$msg = '<br>Данное письмо сформировано автоматически и не предполагает ответа. Пожалуйста, не отвечайте на него.<br><br>';
                $msg = '';
                $msg .= '<br><br> <a style="'. $style['support'] .'" href="http://shop.lazarev.ru/index.php?option=com_contact&view=contact&id=1&Itemid=6">Служба поддержки</a>';
                $msg .= '<br><br><br>С уважением,<br>команда <a style="'. $style['link'] .'" href="http://shop.lazarev.ru">shop.lazarev.ru</a>';
                $msg .= "</div><hr><div style='text-align:center;width:800px;'>	<a href='http://vk.com/diagnostika.karm'> <img src='http://shop.lazarev.ru/images/sn/vk.png'></a> 	<a href='http://www.facebook.com/groups/LazarevSN'> <img src='http://shop.lazarev.ru/images/sn/fa.png'></a>	<a href='http://www.youtube.com/user/rostokunity'> <img src='http://shop.lazarev.ru/images/sn/yo.png'></a></div></body>";
                $message .= $msg;
                $message2 .= $msg;
                $message3 .= $msg;
                /*
                if ($isView){
                    $message .= "<br>Желаем приятного просмотра!<br><br><i>С уважением,<br>команда shop.lazarev.ru\n<br><a href='http://shop.lazarev.ru'>shop.lazarev.ru</a><br><a href='mailto:shop@lazarev.ru'>shop@lazarev.ru</a></i>";
                }
                */
                    //$message .= 'Можно скачать несколько раз: ' . DOWNLOAD_MAX . "\n";
                //$message2 .= "Можно скачать 5 раз<br>\n";

                    //$expire = ((DOWNLOAD_EXPIRE / 60) / 60) / 24;
                    //$message .= 'Доступно в течении ' . $expire . " суток \n";
                //$message2 .= "Доступно в течение 10 суток <br>\n";
                //$message2 .= "<br><br><i>С уважением,<br>команда shop.lazarev.ru\n<br><a href='http://shop.lazarev.ru'>shop.lazarev.ru</a><br><a href='mailto:shop@lazarev.ru'>shop@lazarev.ru</a></i>";

                $mail_Subject = 'Ваш заказ на shop.lazarev.ru';
                $mail_Subject2=$mail_Subject;


                $mail_Subject2 = 'Ваш билет на онлайн-трансляцию '. $date[$view_type] .' '. $date['month'] .' '. $date['year'] .' года';

                $mail_Subject3 = 'Специальное видеоиздание - Помнить о главном ';

                if($haveproducts){



//                    for($f=0;$f<50;$f++)
//                    {
//                        vmMail($dbv->f("contact_email"), $dbv->f("vendor_name"), "zarez@lazarev.ru", $mail_Subject, $message, '',true);
//                    }

                    $result = vmMail($dbv->f("contact_email"), $dbv->f("vendor_name"), $db->f("user_email"), $mail_Subject, $message, '',true);
                }
                if(($view_type!=0)&&($view_type!=4)){

                    $result = vmMail($dbv->f("contact_email"), $dbv->f("vendor_name"), $db->f("user_email"), $mail_Subject2, $message2, '',true);
                }

                if($view_type==4){

                    $hlog=fopen('/var/www/shop.lazarev.ru/orderonpay.log','a+');
                    fwrite($hlog,"\r\n\r\n\r\n ".$message3);
                    fclose($hlog);

                    $result = vmMail($dbv->f("contact_email"), $dbv->f("vendor_name"), $db->f("user_email"), $mail_Subject3, $message3, '',true);
                }


                /*
                  if ($result) {
                  $vmLogger->info( $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG',false). " ". $db->f("first_name") . " " . $db->f("last_name") . " ".$db->f("user_email") );
                  }
                  else {
                  $vmLogger->warning( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_SEND',false)." ". $db->f("first_name") . " " . $db->f("last_name") . ", ".$db->f("user_email") );
                  }
                 */
            }
        }
        elseif ($d["order_status"] == DISABLE_DOWNLOAD_STATUS) {
            $q = "DELETE FROM #__{vm}_product_download WHERE order_id=" . $ord_id;
            $db->query($q);
            $db->next_record();
        }

        return true;
    }


    /**
     * notifies the customer that the Order Status has been changed
     *
     * @param array $d
     */
    function notify_customer(&$d) {

        global $sess, $VM_LANG, $vmLogger;

        $url = SECUREURL . "index.php?option=com_virtuemart&page=account.order_details&order_id=" . urlencode($d["order_id"]) . '&Itemid=' . $sess->getShopItemid();

        $db = new ps_DB;
        $dbv = new ps_DB;
        $q = "SELECT vendor_name,contact_email FROM #__{vm}_vendor ";
        $q .= "WHERE vendor_id='" . $_SESSION['ps_vendor_id'] . "'";
        $dbv->query($q);
        $dbv->next_record();

        $q = "SELECT first_name,last_name,user_email,order_status_name FROM #__{vm}_order_user_info,#__{vm}_orders,#__{vm}_order_status ";
        $q .= "WHERE #__{vm}_orders.order_id = '" . $db->getEscaped($d["order_id"]) . "' ";
        $q .= "AND #__{vm}_orders.user_id = #__{vm}_order_user_info.user_id ";
        $q .= "AND #__{vm}_orders.order_id = #__{vm}_order_user_info.order_id ";
        $q .= "AND order_status = order_status_code ";
        $db->query($q);
        $db->next_record();

        // MAIL BODY
        $message = $VM_LANG->_('HI', false) . ' ' . $db->f("first_name") . ($db->f("middle_name") ? ' ' . $db->f("middle_name") : '' ) . ' ' . $db->f("last_name") . ",\n\n";
        $message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_1', false) . "\n\n";

        if (!empty($d['include_comment']) && !empty($d['order_comment'])) {
            $message .= $VM_LANG->_('PHPSHOP_ORDER_HISTORY_COMMENT_EMAIL', false) . ":\n";
            $message .= $d['order_comment'];
            $message .= "\n____________________________________________________________\n\n";
        }

        $message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_2', false) . "\n";
        $message .= "____________________________________________________________\n\n";
        $message .= $db->f("order_status_name");

        if (VM_REGISTRATION_TYPE != 'NO_REGISTRATION') {
            $message .= "\n____________________________________________________________\n\n";
            $message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_3', false) . "\n";
            $message .= $url;
        }
        $message .= "\n\n____________________________________________________________\n";
        $message .= $dbv->f("vendor_name") . " \n";
        $message .= URL . "\n";
        $message .= $dbv->f("contact_email");

        $message = str_replace("{order_id}", $d["order_id"], $message);

        $mail_Body = html_entity_decode($message);
        $mail_Subject = str_replace("{order_id}", $d["order_id"], $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_SUBJ', false));


        $result = vmMail($dbv->f("contact_email"), $dbv->f("vendor_name"), $db->f("user_email"), $mail_Subject, $mail_Body, '');

        /* Send the email */
        if ($result) {
            $vmLogger->info($VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG', false) . " " . $db->f("first_name") . " " . $db->f("last_name") . ", " . $db->f("user_email"));
        }
        else {
            $vmLogger->warning($VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_SEND', false) . ' ' . $db->f("first_name") . " " . $db->f("last_name") . ", " . $db->f("user_email") . " (" . $result->ErrorInfo . ")");
        }
    }

    /**
     * This function inserts the DOWNLOAD IDs for all files associated with this product
     * so the customer can later download the purchased files
     * @static
     * @since 1.1.0
     * @param int $product_id
     * @param int $order_id
     * @param int $user_id
     */
    function insert_downloads_for_product(&$d) {
        $db = new ps_DB();
        $dbd = new ps_DB();
        if (empty($d['product_id']) || empty($d['order_id'])) {
            return false;
        }

        $dl = "SELECT attribute_name,attribute_value ";
        $dl .= "FROM #__{vm}_product_attribute WHERE product_id='" . $d['product_id'] . "'";
        $dl .= " AND attribute_name='download'";
        $db->query($dl);
        $dlnum = 0;
        while ($db->next_record()) {

            $str = (int) $d['order_id'];
            $str .= $d['product_id'];
            $str .= uniqid('download_');
            $str .= $dlnum++;
            $str .= time();

            $download_id = md5($str);

            $fields = array('product_id' => $d['product_id'],
                'user_id' => (int) $d['user_id'],
                'order_id' => (int) $d['order_id'],
                'end_date' => '0',
                'download_max' => DOWNLOAD_MAX,
                'download_id' => $download_id,
                'file_name' => $db->f("attribute_value")
            );




            $dbd->buildQuery('INSERT', '#__{vm}_product_download', $fields);
            $dbd->query();
        }
    }

    /**
     * Handles a download Request
     *
     * @param array $d
     * @return boolean
     */
    function download_request(&$d) {
        global $download_id, $VM_LANG, $vmLogger;

        $db = new ps_DB;
        $download_id = $db->getEscaped(vmGet($d, "download_id"));

        $q = "SELECT * FROM #__{vm}_product_download WHERE";
        $q .= " download_id = '$download_id'";

        $db->query($q);
        $db->next_record();

        $download_id = $db->f("download_id");
        $file_name = $db->f("file_name");
        if (strncmp($file_name, 'http', 4) !== 0) {
            $datei = DOWNLOADROOT . $file_name;
        }
        else {
            $datei = $file_name;
        }
        $download_max = $db->f("download_max");
        $end_date = $db->f("end_date");
        $zeit = time();

        if (!$download_id) {
            $vmLogger->err($VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_INV', false));
            return false;
            //vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
        }
        elseif ($download_max == "0") {
            $q = "DELETE FROM #__{vm}_product_download";
            $q .=" WHERE download_id = '" . $download_id . "'";
            $db->query($q);
            $db->next_record();
            $vmLogger->err($VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_MAX', false));
            return false;
            //vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
        }
        elseif ($end_date != "0" && $zeit > $end_date) {
            $q = "DELETE FROM #__{vm}_product_download";
            $q .=" WHERE download_id = '" . $download_id . "'";
            $db->query($q);
            $db->next_record();
            $vmLogger->err($VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_EXP', false));
            return false;
            //vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
        }
        require_once(CLASSPATH . 'connectionTools.class.php');

        $download_count = true;

        if (@file_exists($datei)) {
            // Check if this is a request for a special range of the file (=Resume Download)
            $range_request = vmConnector::http_rangeRequest(filesize($datei), false);
            if ($range_request[0] == 0) {
                // this is not a request to resume a download,
                $download_count = true;
            }
            else {
                $download_count = false;
            }
        }
        else {
            $download_count = false;
        }

        // Parameter to check if the file should be removed after download, which is only true,
        // if we have a remote file, which was transferred to this server into a temporary file
        $unlink = false;

        if (strncmp($datei, 'http', 4) === 0) {
            require_once( CLASSPATH . 'ps_product_files.php');
            $datei_local = ps_product_files::getRemoteFile($datei);
            if ($datei_local !== false) {
                $datei = $datei_local;
                $unlink = true;
            }
            else {
                $vmLogger->err($VM_LANG->_('VM_DOWNLOAD_FILE_NOTFOUND', false));
                return false;
            }
        }
        else {
            // Check, if file path is correct
            // and file is
            if (!@file_exists($datei)) {
                $vmLogger->err($VM_LANG->_('VM_DOWNLOAD_FILE_NOTFOUND', false));
                return false;
                //vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
            }
            if (!@is_readable($datei)) {
                $vmLogger->err($VM_LANG->_('VM_DOWNLOAD_FILE_NOTREADABLE', false));
                return false;
                //vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
            }
        }
        if ($download_count) {
            // decrement the download_max to limit the number of downloads
            $q = "UPDATE `#__{vm}_product_download` SET";
            $q .=" `download_max`=`download_max` - 1";
            $q .=" WHERE download_id = '" . $download_id . "'";
            $db->query($q);
            $db->next_record();
        }
        if ($end_date == "0") {
            // Set the Download Expiry Date, so the download can expire after DOWNLOAD_EXPIRE seconds
            $end_date = time('u') + DOWNLOAD_EXPIRE;
            $q = "UPDATE #__{vm}_product_download SET";
            $q .=" end_date=$end_date";
            $q .=" WHERE download_id = '" . $download_id . "'";
            $db->query($q);
            $db->next_record();
        }

        if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
            $UserBrowser = "Opera";
        }
        elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
            $UserBrowser = "IE";
        }
        else {
            $UserBrowser = '';
        }
        $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

        // dump anything in the buffer
        while (@ob_end_clean());

        vmConnector::sendFile($datei, $mime_type, basename($file_name));

        if ($unlink) {
            // remove the temporarily downloaded remote file
            @unlink($datei);
        }
        $GLOBALS['vm_mainframe']->close(true);
    }

    /**
     * Shows the list of the orders of a user in the account mainenance section
     *
     * @param string $order_status Filter by order status (A=all, C=confirmed, P=pending,...)
     * @param int $secure Restrict the order list to a specific user id (=1) or not (=0)?
     */
    function list_order($order_status='A', $secure=0) {
        global $VM_LANG, $CURRENCY_DISPLAY, $sess, $limit, $limitstart, $keyword, $mm_action_url;

        $ps_vendor_id = $_SESSION["ps_vendor_id"];
        $auth = $_SESSION['auth'];
        require_once( CLASSPATH . 'ps_order_status.php');
        require_once( CLASSPATH . 'htmlTools.class.php');
        require_once( CLASSPATH . 'pageNavigation.class.php');
        $db = new ps_DB;
        $dbs = new ps_DB;

        $listfields = 'o.order_id,o.cdate,order_total,order_track,o.order_status,order_currency';
        $countfields = 'count(*) as num_rows';
        $count = "SELECT $countfields FROM #__{vm}_orders o ";
        $list = "SELECT DISTINCT $listfields FROM #__{vm}_orders o ";
        $q = "WHERE o.vendor_id='$ps_vendor_id' ";
        if ($order_status != "A") {
            $q .= "AND order_status='$order_status' ";
        }
        if ($secure) {
            $q .= "AND user_id='" . $auth["user_id"] . "' ";
        }
        if (!empty($keyword)) {
            $count .= ', #__{vm}_order_item oi ';
            $list .= ', #__{vm}_order_item oi ';
            $q .= "AND (order_item_sku LIKE '%" . $keyword . "%' ";
            $q .= "OR order_number LIKE '%" . $keyword . "%' ";
            $q .= "OR o.order_id=" . (int) $keyword . ' ';
            $q .= "OR order_item_name LIKE '%" . $keyword . "%') ";
            $q .= "AND oi.order_id=o.order_id ";
        }
        $q .= "ORDER BY o.cdate DESC";
        $count .= $q;

        $db->query($count);
        $db->next_record();
        $num_rows = $db->f('num_rows');
        if ($num_rows == 0) {
            echo "<span style=\"font-style:italic;\">" . $VM_LANG->_('PHPSHOP_ACC_NO_ORDERS') . "</span>\n";
            return;
        }
        $pageNav = new vmPageNav($num_rows, $limitstart, $limit);

        $list .= $q .= " LIMIT " . $pageNav->limitstart . ", $limit ";
        $db->query($list);

        $listObj = new listFactory($pageNav);

        if ($num_rows > 0) {
            // print out the search field and a list heading
            $listObj->writeSearchHeader('', '', 'account', 'index');
        }
        // start the list table
        $listObj->startTable();

        $listObj->writeTableHeader(3);

        while ($db->next_record()) {

            $order_status = ps_order_status::getOrderStatusName($db->f("order_status"));

            $listObj->newRow();

            $tmp_cell = "<a href=\"" . $sess->url($mm_action_url . "index.php?page=account.order_details&order_id=" . $db->f("order_id")) . "\">\n";
            $tmp_cell .= "<img src=\"" . IMAGEURL . "ps_image/goto.png\" height=\"32\" width=\"32\" align=\"middle\" border=\"0\" alt=\"" . $VM_LANG->_('PHPSHOP_ORDER_LINK') . "\" />&nbsp;" . $VM_LANG->_('PHPSHOP_VIEW') . "</a><br />";
            $listObj->addCell($tmp_cell);

            $tmp_cell = "<strong>" . $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_DATE') . ":</strong> " . vmFormatDate($db->f("cdate"), "%d. %B %Y");
            $tmp_cell .= "<br /><strong>" . $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL') . ":</strong> " . $CURRENCY_DISPLAY->getFullValue($db->f("order_total"), '', $db->f('order_currency'));
            $listObj->addCell($tmp_cell);
            if ($db->f("order_track") != "") {
                $tmp_cell = "<strong>" . $VM_LANG->_('PHPSHOP_TRACK') . ":</strong> " . $db->f("order_track");
                $listObj->addCell($tmp_cell);
            }
            $tmp_cell = "<strong>" . $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_STATUS') . ":</strong> " . $order_status;
            $tmp_cell .= "<br /><strong>" . $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_NUMBER') . ":</strong> " . sprintf("%08d", $db->f("order_id"));
            $listObj->addCell($tmp_cell);
        }
        $listObj->writeTable();
        $listObj->endTable();
        if ($num_rows > 0) {
            $listObj->writeFooter($keyword, '&Itemid=' . $sess->getShopItemid());
        }
    }

    /**
     * Validate form values prior to delete
     *
     * @param int $order_id
     * @return boolean
     */
    function validate_delete($order_id) {
        global $VM_LANG;

        $db = new ps_DB;

        if (empty($order_id)) {
            $GLOBALS['vmLogger']->err($VM_LANG->_('VM_ORDER_DELETE_ERR_ID'));
            return False;
        }

        return True;
    }

    /**
     * Controller for Deleting Records.
     */
    function delete(&$d) {

        $record_id = $d["order_id"];

        if (is_array($record_id)) {
            foreach ($record_id as $record) {
                if (!$this->delete_record($record, $d))
                    return false;
            }
            return true;
        }
        else {
            return $this->delete_record($record_id, $d);
        }
    }

    /**
     * Deletes one Record.
     */
    function delete_record($record_id, &$d) {
        global $db;
        $record_id = intval($record_id);
        if ($this->validate_delete($record_id)) {

            $dbu = new ps_db();
            // 	Get the order items and update the stock level
            // to the number before the order was placed
            $q = "SELECT order_status, product_id, product_quantity FROM #__{vm}_order_item WHERE order_id=$record_id";
            $db->query($q);
            require_once( CLASSPATH . 'ps_product.php' );
            // Now update each ordered product
            while ($db->next_record()) {
                if (in_array($db->f('order_status'), array('P', 'X', 'R')))
                    continue;

                if (ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($db->f("product_id")) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
                    $q = "UPDATE #__{vm}_product  
							SET product_sales=product_sales-" . $db->f("product_quantity") . " 
						WHERE product_id=" . $db->f("product_id");
                    $dbu->query($q);
                }
                else {
                    $q = "UPDATE #__{vm}_product 
						SET product_in_stock=product_in_stock+" . $db->f("product_quantity") . ",
							product_sales=product_sales-" . $db->f("product_quantity") . " 
						WHERE product_id=" . $db->f("product_id");
                    $dbu->query($q);
                }
            }

            $q = "DELETE from #__{vm}_orders where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE from #__{vm}_order_item where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE from #__{vm}_order_payment where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE from #__{vm}_product_download where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE from #__{vm}_order_history where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE from #__{vm}_order_user_info where order_id='$record_id'";
            $db->query($q);

            $q = "DELETE FROM #__{vm}_shipping_label where order_id=$record_id";
            $db->query($q);

            return True;
        }
        else {
            return False;
        }
    }

    /**
     * Creates the order navigation on the order print page
     *
     * @param int $order_id
     * @return boolean
     */
    function order_print_navigation($order_id=1) {
        global $sess, $modulename, $VM_LANG;

        $navi_db = new ps_DB;

        $navigation = "<div align=\"center\">\n<strong>\n";
        $q = "SELECT order_id FROM #__{vm}_orders WHERE ";
        $q .= "order_id < '$order_id' ORDER BY order_id DESC";
        $navi_db->query($q);
        $navi_db->next_record();
        if ($navi_db->f("order_id")) {
            $url = $_SERVER['PHP_SELF'] . "?page=$modulename.order_print&order_id=";
            $url .= $navi_db->f("order_id");
            $navigation .= "<a class=\"pagenav\" href=\"" . $sess->url($url) . "\">&lt; " . $VM_LANG->_('ITEM_PREVIOUS') . "</a> | ";
        } else
            $navigation .= "<span class=\"pagenav\">&lt; " . $VM_LANG->_('ITEM_PREVIOUS') . " | </span>";

        $q = "SELECT order_id FROM #__{vm}_orders WHERE ";
        $q .= "order_id > '$order_id' ORDER BY order_id";
        $navi_db->query($q);
        $navi_db->next_record();
        if ($navi_db->f("order_id")) {
            $url = $_SERVER['PHP_SELF'] . "?page=$modulename.order_print&order_id=";
            $url .= $navi_db->f("order_id");
            $navigation .= "<a class=\"pagenav\" href=\"" . $sess->url($url) . "\">" . $VM_LANG->_('ITEM_NEXT') . "  &gt;</a>";
        }
        else {
            $navigation .= "<span class=\"pagenav\">" . $VM_LANG->_('ITEM_NEXT') . " &gt;</span>";
        }

        $navigation .= "\n<strong>\n</div>\n";

        return $navigation;
    }

}

//echo '875';
// Check if there is an extended class in the Themes and if it is allowed to use them
// If the class is called outside Virtuemart, we have to make sure to load the settings
// Thomas Kahl - Feb. 2009
if (!defined('VM_ALLOW_EXTENDED_CLASSES') && file_exists(dirname(__FILE__) . '/../virtuemart.cfg.php')) {
    include_once(dirname(__FILE__) . '/../virtuemart.cfg.php');
}
// If settings are loaded, extended Classes are allowed and the class exisits...
if (defined('VM_ALLOW_EXTENDED_CLASSES') && defined('VM_THEMEPATH') && VM_ALLOW_EXTENDED_CLASSES && file_exists(VM_THEMEPATH . 'user_class/' . basename(__FILE__))) {
    // Load the theme-user_class as extended
    include_once(VM_THEMEPATH . 'user_class/' . basename(__FILE__));
}
else {

    // Otherwise we have to use the original classname to extend the core-class
    class ps_order extends vm_ps_order {

    }

}

$ps_order = new ps_order;
?>
