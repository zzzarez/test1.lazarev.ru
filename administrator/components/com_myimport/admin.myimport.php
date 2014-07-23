<?php
////////////////////////////////////////////////////////
// Алгоритм импорта в Virtuemart 1.1.x				//
// 2005 (C) Выскорко М.С. (aspid02@ngs.ru)			//
// Компонент для Joomla 1.5.x Алгоритм экспорта		//
// 2010 (C) Ребров О.В.   (admin@webplaneta.com.ua)	//
////////////////////////////////////////////////////////
defined('_JEXEC') or die('Restricted access');
if ($task == "import") {
    global $mainframe;
    $db =& JFactory::getDBO();
    function del($new) //функция по предварительной обработке данных вводимых пользователем
    {
        $new = trim($new);
        $new = str_replace("\n", " ", $new);
        $new = str_replace("\r", "", $new);
        $new = str_replace("\0", "", $new);
        $new = str_replace("\t", "", $new);
        $new = str_replace("\x0B", "", $new);
        $new = htmlspecialchars(stripslashes($new), ENT_QUOTES);
        return $new;
    }

    function edit_import($str) //редактирование импотированных данных
    {
        $new = str_replace("\n", " ", $str);
        $new = str_replace("\r", "", $new);
        $new = str_replace("\0", "", $new);
        $new = ltrim($new, '"');
        //$new=rtrim($new,'"');
        //$new=trim($new,'"');//удаляет двойнве ковычки в начале и конце строке
        $new = str_replace('""', '"', $new); //заменяет две двойных ковычки на одни двойные ковычки
        //$new = str_replace('""','"',$new);//заменяет две двойных ковычки на одни двойные ковычки
        $new = str_replace('""', '"', $new); //заменяет две двойных ковычки на одни двойные ковычки
        $new = str_replace('" "', '"', $new); //заменяет две двойных ковычки на одни двойные ковычки
        $new = trim($new); //удаляет пробелы в начале и конце строке
        //$new = htmlspecialchars(stripslashes($new), ENT_QUOTES);//заменяет спец символы в их HTML эквивалент
//удаление всех лишних пробелов
        $arr = explode(" ", $new);
        $sss = "";
        for ($j = 0; $j < count($arr); $j++) {
            if ($arr[$j]) {
                $sss .= $arr[$j];
                if ($j != count($arr) - 1) {
                    $sss .= " ";
                }
            }
        }
//------------------------------
        $new = $sss;
        return $new;
    }

//защита от SQL-инъекций
    if (!preg_match("|^[\d]+$|", @$_GET['id']) && !empty($_GET['id'])) exit("Недопустимый формат URL-запроса");
    if (!preg_match("|^[\d]+$|", @$_GET['categ_id']) && !empty($_GET['categ_id'])) exit("Недопустимый формат URL-запроса");
    if (!preg_match("|^[\d]+$|", @$_GET['sub_id']) && !empty($_GET['sub_id'])) exit("Недопустимый формат URL-запроса");
    if (!preg_match("|^[\d]+$|", @$_GET['name_id']) && !empty($_GET['name_id'])) exit("Недопустимый формат URL-запроса");
//**********************
    $path = JPATH_BASE . "/cache/myimport";
    // Проверяем на существование папку $path
    if (!file_exists($path)) {
        /*die("<b>Пожалуйста, создайте папку <font color=red>".$path."</font> и <a href=&#63;>повторите попытку загрузить файл</a>.</b>");*/
        mkdir($path, 0777);
        echo $path . "- Папка создана";
    }
// Выводим форму для загрузки файла.
    if (empty($_FILES['UserFile']['tmp_name']))
        echo
        "<form method=post enctype=multipart/form-data>
	Пожалуйста используйте только файлы в формате CSV, во избежание нежелаемых последствий <br>
	Выберите файл: <input type=file name=UserFile> <br />
	Уничтожить существуюшие товары? <input type=checkbox name=del_product> <br />
<input type=submit value=Отправить>
	</form>";
    // Если файл не загружен по каким-то причинам, выводим ошибку.
    elseif (!is_uploaded_file($_FILES['UserFile']['tmp_name']))
        die("<b><font color=red>123Файл не был загружен! Попробуйте <a href=&#63;>повторить попытку</a>!</font></b>"); // Если файл удачно загружён на сервер, делаем вот что...
    // Переносим загружённый файл в папку $path
    elseif (@!copy($_FILES['UserFile']['tmp_name'], $path . chr(47) . "price.csv")) {
        echo $_FILES['UserFile']['tmp_name'] . "<br>" . $path . chr(47) . "price.csv";
        $name = $_FILES['UserFile']['name']; // Если не удалось перенести файл, выводим ошибку:
        die("<b><font color=red>456Файл не был загружен! Попробуйте <a href=&#63;>повторить попытку</a>!</font></b>");
    } else // Если всё Ok, то выводим инфо. о загружённом файле.
    {
        echo
            "<center><b>Файл <font color=green>" . $path . chr(47) . $_FILES['UserFile']['name'] . "</font> успешно загружён на сервер!</font></b></center>" .
            "<hr>" .
            "Тип файла: <b>" . $_FILES['UserFile']['type'] . "</b><br>" .
            "Размер файла: <b>" . round($_FILES['UserFile']['size'] / 1024, 2) . " кб.</b>" .
            "<hr>";

        //Импорт товара

        $fl = $path . "/" . "price.csv";
        $file = file_get_contents($fl);
        $file = iconv("windows-1251", "utf-8", $file);
        $file = file_put_contents($fl, $file);
        $data = File($fl);
        $data = str_replace("\r", "", $data);
        $data = str_replace("\0", "", $data);
        $database =& JFactory::getDBO();
        $j = 0;
        $list_order1 = 0; //сортировка категорий 1 уровня
        $list_order2 = 0; //сортировка категорий 2 уровня
        $list_order3 = 0; //сортировка категорий 3 уровня
        if (isset($_POST["del_product"])) //if (@$del_product)    //очистка таблиц товаров
        {
            $database->setQuery("DELETE FROM `#__vm_category`");
            $database->query();
            $database->setQuery("DELETE FROM `#__vm_category_xref`");
            $database->query();
            $database->setQuery("DELETE FROM `#__vm_product`");
            $database->query();
            $database->setQuery("DELETE FROM `#__vm_product_category_xref`");
            $database->query();
            $database->setQuery("DELETE FROM `#__vm_product_price`");
            $database->query();
        }

        for ($i = 1; $i < count($data); $i++) {
            $data_array = explode(";", $data[$i]);

            if ($data_array[0]) //запись категории 1 уровня
            {
                $data_array[0] = edit_import($data_array[0]);
                $category_path0 = $data_array[0];

                $list_order1++;
                $list_order2 = 0;

                //запрос существования категории
                $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[0] . "' LIMIT 1");
                $category_id1 = $database->loadResult();
                if (!$category_id1) {
                    //Запись категории
                    $database->setQuery("INSERT INTO #__vm_category ( `vendor_id` , `category_name` , `category_publish` , `category_browsepage` , `products_per_row` , `category_flypage` , `list_order` ) values ( '1' , '" . $data_array[0] . "' , 'Y', 'browse_1' , '1' , 'flypage_images.tpl' , '" . $list_order1 . "' )");
                    $database->query();
                    print "<b>Категории " . $data_array[0] . " нет, создаем. </b><br>";

                    //запрос идентификатора категории
                    $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[0] . "' LIMIT 1");
                    $category_id1 = $database->loadResult();

                    //запись связи с родительской категорией, для первого уровня родительская = 0
                    $database->setQuery("INSERT INTO #__vm_category_xref ( `category_parent_id` , `category_child_id` ) values ( '0' , '" . $category_id1 . "' )");
                    $database->query();
                }
                unset ($category_id2);
            }

            if ($data_array[1] && !$data_array[2]) //запись категории 2 уровня
            {
                $list_order2++;
                $list_order3 = 0;
                $data_array[1] = edit_import($data_array[1]);

                //запрос существования категории
                $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[1] . "' LIMIT 1");
                $category_id2 = $database->loadResult();
                if (!$category_id2) {
                    //Запись категории
                    $database->setQuery("INSERT INTO #__vm_category ( `vendor_id` , `category_name` , `category_publish` , `category_browsepage` , `products_per_row` , `category_flypage` , `list_order` ) values ( '1' , '" . $data_array[1] . "' , 'Y', 'browse_1' , '1' , 'flypage_images.tpl' , '" . $list_order2 . "' )");
                    $database->query();
                    print "<b>Подкатегории " . $data_array[1] . " нет, создаем. </b><br>";
                    $category_path1 = "/" . $data_array[1] . "/";
                    //запрос идентификатора категории
                    $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[1] . "' LIMIT 1");
                    $category_id2 = $database->loadResult();

                    //запись связи с родительской категорией
                    $database->setQuery("INSERT INTO #__vm_category_xref ( `category_parent_id` , `category_child_id` ) values ( '" . $category_id1 . "' , '" . $category_id2 . "' )");
                    $database->query();
                }
                unset ($category_id3);
            }

            if (!$data_array[1] && $data_array[2]) //запись категории 3 уровня
            {
                $data_array[2] = edit_import($data_array[2]);
                $list_order3++;
                $data_array[2] = edit_import($data_array[2]);

                //запрос существования категории
                $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[2] . "' LIMIT 1");
                $category_id3 = $database->loadResult();
                if (!$category_id3) {
                    //Запись категории
                    $database->setQuery("INSERT INTO #__vm_category ( `vendor_id` , `category_name` , `category_publish` , `category_browsepage` , `products_per_row` , `category_flypage` , `list_order` ) values ( '1' , '" . $data_array[2] . "' , 'Y', 'browse_1' , '1' , 'flypage_images.tpl' , '" . $list_order3 . "' )");
                    $database->query();
                    print "<b>Подподкатегории " . $data_array[2] . " нет, создаем. </b><br>";

                    //запрос идентификатора категории
                    $database->setQuery("SELECT * FROM #__vm_category WHERE category_name ='" . $data_array[2] . "' LIMIT 1");
                    $category_id3 = $database->loadResult();

                    //запись связи с родительской категорией
                    $database->setQuery("INSERT INTO #__vm_category_xref ( `category_parent_id` , `category_child_id` ) values ( '" . $category_id2 . "' , '" . $category_id3 . "' )");
                    $database->query();
                }
            }
            //запись товаров
            if (!$data_array[0] && !$data_array[1] && !$data_array[2]) {

//		$data_array[3]=edit_import($data_array[3]);//артикул родительского товара
//		$data_array[4]=edit_import($data_array[4]);//артикул
//		$data_array[5]=edit_import($data_array[5]);//наименование
//		$data_array[6]=edit_import($data_array[6]);//краткое описание
//		$data_array[7]=edit_import($data_array[7]);//описание
//        $data_array[9]=edit_import($data_array[9]);//скачиваемый файл
//        $data_array[10]=edit_import($data_array[10]);//скачиваемый файл2
//        $data_array[11]=edit_import($data_array[11]);//скачиваемый файл3
//        $data_array[12]=edit_import($data_array[12]);//изображение товара
                for ($f = 3; $f <= 66; $f++) {
                    $data_array[$f] = edit_import($data_array[$f]);
                }

                //запрос существования товара в базе
                $database->setQuery("SELECT * FROM #__vm_product WHERE product_sku ='" . $data_array[4] . "' LIMIT 1");
                $product_id = $database->loadResult();
                if (!$product_id) //если товара нет то создаем его
                {
                    print "$product_id = " . $product_id . " товара " . $data_array[5] . " нет, создаем. <br>";
                    //запрос ИД родительского товара
                    //echo "dat3".$data_array[3];
                    if ($data_array[3]) {
                        $database->setQuery("SELECT * FROM #__vm_product WHERE product_sku ='" . $data_array[3] . "' LIMIT 1");
                        $product_parent_id = $database->loadResult();
                        //echo "<br>ppi=".$product_parent_id;
                    } //["product_id"]
                    else  $product_parent_id = 0;
                    //echo "<br>".$product_parent_id["product_parent_id"];

//		 $prod_thumb_img = $category_path0.$category_path1."resized/".$data_array[4].".jpg";
//       $prod_full_img = $category_path0.$category_path1.$data_array[4].".jpg";

                    $prod_thumb_img = "s_" . $data_array[66];
                    $prod_full_img = $data_array[66];

                    //запись товаров                                                                                                                                                                                                                                                                            //!!!["product_id"]
                    $database->setQuery("INSERT INTO #__vm_product ( vendor_id , product_parent_id , product_sku , product_s_desc , product_publish , product_special , product_discount_id , product_name , product_desc , child_options, quantity_options,product_order_levels,product_full_image,product_thumb_image) values ( '1', '" . $product_parent_id . "', '" . $data_array[4] . "', '" . $data_array[6] . "', 'Y', 'N', '0', '" . $data_array[5] . "', '" . $data_array[7] . "','N,N,N,N,N,,,,', 'hide,0,0,1', '0,0','" . $prod_full_img . "','" . $prod_thumb_img . "')");
                    $database->query();
                    // $category_path="";
                    //запрос идентификатора товара
                    $database->setQuery("SELECT product_id FROM #__vm_product WHERE product_sku ='" . $data_array[4] . "' LIMIT 1");
                    $product_id = $database->loadResult();


                    for($n=9;$n<=62;$n++)
                    {
                        if ($data_array[$n] != "") {
                            $download_file = $data_array[$n];
                            $q = "INSERT INTO #__vm_product_files (file_product_id,file_name,file_title,file_description,file_extension,file_mimetype,file_url,file_published) VALUE ($product_id,'$download_file','Файл для скачивания','','','','',1);";
                            $database->setQuery($q);
                            $database->query();

                            $q = "INSERT INTO #__vm_product_attribute (product_id,attribute_name,attribute_value) VALUE ($product_id,'download','$download_file');";
                            echo $q ."--". $n."-----<br>";
                            $database->setQuery($q);
                            $database->query();
                        }

                    }
//                    $download_file = $data_array[9];
//                    $q = "INSERT INTO #__vm_product_files (file_product_id,file_name,file_title,file_description,file_extension,file_mimetype,file_url,file_published) VALUE ($product_id,'$download_file','Файл для скачивания','','','','',1);";
//                    $database->setQuery($q);
//                    $database->query();
//
//                    $q = "INSERT INTO #__vm_product_attribute (product_id,attribute_name,attribute_value) VALUE ($product_id,'download','$download_file');";
//                    echo $q . "-- 9---<br>";
//                    $database->setQuery($q);
//                    $database->query();
//
//                    if ($data_array[10] != "") {
//                        $download_file = $data_array[10];
//                        $q = "INSERT INTO #__vm_product_files (file_product_id,file_name,file_title,file_description,file_extension,file_mimetype,file_url,file_published) VALUE ($product_id,'$download_file','Файл для скачивания','','','','',1);";
//                        $database->setQuery($q);
//                        $database->query();
//
//                        $q = "INSERT INTO #__vm_product_attribute (product_id,attribute_name,attribute_value) VALUE ($product_id,'download','$download_file');";
//                        echo $q . "-- 10---<br>";
//                        $database->setQuery($q);
//                        $database->query();
//                    }
//                    if ($data_array[11] != "") {
//                        $download_file = $data_array[11];
//                        $q = "INSERT INTO #__vm_product_files (file_product_id,file_name,file_title,file_description,file_extension,file_mimetype,file_url,file_published) VALUE ($product_id,'$download_file','Файл для скачивания','','','','',1);";
//                        $database->setQuery($q);
//                        $database->query();
//
//                        $q = "INSERT INTO #__vm_product_attribute (product_id,attribute_name,attribute_value) VALUE ($product_id,'download','$download_file');";
//                        echo $q . "-- 11---<br>";
//                        $database->setQuery($q);
//                        $database->query();
//
//                    }

                    /*
                    $download_id=dechex(rand(1,100))."aa".dechex(rand(1,100));
                    $q="INSERT INTO #__vm_product_download(product_id,user_id,order_id,end_date,download_max,download_id,file_name) VALUE ($product_id,62,0,3,$download_id,'$download_file');";
                    echo "downltabl=".$q."</br>";
                    $database->setQuery($q);
                    $database->query();
                    */
                    /*
                    $q="INSERT INTO #__vm_product_attribute (product_id,attribute_name,attribute_value) VALUE ($product_id,'download','$download_file');";
                    echo "attr=".$q."</br>";
                    $database->setQuery($q);
                    $database->query();
                    */

                    //запись связей товара с карегориями
                    if (@$category_id3) //запрос существования категории 3 уровня
                    {
                        //запись связи товара с категорией 3 уровня
                        $database->setQuery("INSERT INTO #__vm_product_category_xref ( category_id , product_id , product_list ) values ( '" . $category_id3 . "' , '" . $product_id . "', '1' )");
                        $database->query();
                    } elseif (@$category_id2) //запрос существования категории 2 уровня
                    {
                        //запись связи товара с категорией 2 уровня
                        $database->setQuery("INSERT INTO #__vm_product_category_xref ( category_id , product_id , product_list ) values ( '" . $category_id2 . "' , '" . $product_id . "', '1' )");
                        $database->query();
                    } else {
                        //запись связи товара с категорией 1 уровня
                        $database->setQuery("INSERT INTO #__vm_product_category_xref ( category_id , product_id , product_list ) values ( '" . $category_id1 . "' , '" . $product_id . "', '1' )");
                        $database->query();
                    }
                } else //если товар есть, то удаляем его цены
                {
                    //запрос идентификатора товара
                    $database->setQuery("SELECT * FROM jos_vm_product WHERE product_sku ='" . $data_array[4] . "' LIMIT 1");
                    $product_id = $database->loadResult();
                    //удаление цен
                    $database->setQuery("DELETE FROM #__vm_product_price WHERE product_id=" . $product_id . "");
                    $database->query();
                }
                //запись цен для дефолтовой группы покупателей
                $num_col = '8';
                if (@$data_array[$num_col]) { //!!!
                    $price = ltrim($data_array[$num_col], "$");
                    $price = str_replace(",", ".", $price);
                    $price = str_replace(" ", "", $price);
                    //$price= '1';
                    $quantity_start = '0';
                    $quantity_end = '0';
                    $database->setQuery("INSERT INTO #__vm_product_price ( product_id , product_price , product_currency , shopper_group_id , price_quantity_start , price_quantity_end ) values ( '" . $product_id . "', '" . $price . "' , 'RUB' , '5' , '" . $quantity_start . "' , '" . $quantity_end . "' )");
                    $database->query();
                    //echo "INSERT INTO #__vm_product_price ( product_id , product_price , product_currency , shopper_group_id , price_quantity_start , price_quantity_end ) values ( '".$product_id."', '".$price."' , 'RUB' , '5' , '".$quantity_start."' , '".$quantity_end."' )";

                }
            }
        }
        @unlink($path . "price.csv");
        print  "<center><font color='green'><b>Новый каталог заведён!</b></font></center>";
        print $PHP_SELF;
//print ("<meta http-equiv=\"refresh\" content=\"2;URL=/price/admin/\">");

    }
} elseif ($task == "export") {
    global $mainframe;
    $mainframe = & JFactory::getApplication();
    $path = JPATH_BASE . "/cache/myimport/";
    $path1 = JPATH_BASE . "/cache/myimport/";
    /*if(!file_exists($path."export_price.csv"))
    {
            unlink ($path."export_price.csv");
    }*/
    $fp = fopen($path . "export_price.csv", 'w+');
    @chmod($path . "export_price.csv", 0777);

    $fp = fopen($path . "export_price.csv", 'a+');

////
    $datacsv_cat = "Категория";
    $datacsv_subcat = "Подкатегория";
    $datacsv_usubcat = "ПодПодкатегория";
    $datacsv_pid = "Товар-родитель";
    $datacsv_sku = "Артикул";
    $datacsv_name = "Наименование";
    $datacsv_s_desc = "Краткое описание";
    $datacsv_desc = "Описание";
    $datacsv_price = "Цена";
    fwrite($fp, $datacsv_cat . ";" . $datacsv_subcat . ";" . $datacsv_usubcat . ";" . $datacsv_pid . ";" . $datacsv_sku . ";" . $datacsv_name . ";" . $datacsv_s_desc . ";" . $datacsv_desc . ";" . $datacsv_price . "\n");
////
    $database =& JFactory::getDBO();
    $mainframe = & JFactory::getApplication();
    $database->setQuery("SELECT * FROM #__vm_category_xref, #__vm_category  WHERE category_child_id=category_id ORDER BY category_parent_id=0"); //category_child_id
    $res = $database->loadObjectList();
    foreach ($res as $category) {
///////////////////////////////////Выбор  категории//////////////////////////////////////
        if ($category->category_parent_id == 0) {
            $cat = $category->category_name;
            /*echo "Категория -", $category->category_id , ":" ,$cat, "<br>";*/
            $datacsv_cat = $cat . ";;;;;;;;" . "\n";
            fwrite($fp, $datacsv_cat);

///////////////////////////////////Выбор  подкатегории 1 порядка////////////////////////////
            $database->setQuery("SELECT * FROM #__vm_category_xref WHERE category_parent_id='" . $category->category_child_id . "' ");
            $sub = $database->loadObjectList();
            foreach ($sub as $subcategory) {
                $database->setQuery("SELECT category_name FROM #__vm_category WHERE category_id='" . $subcategory->category_child_id . "'");
                $subcategory_name = $database->loadResult();
                $subcat = $subcategory_name;
                /*echo "Подкатегория 1-", ":" ,$subcat, "<br>";*/
                $datacsv_subcat = ";" . $subcat . ";;;;;;;" . "\n";
                fwrite($fp, $datacsv_subcat);
///////////////////////////////////Выбор  подкатегории 3 порядка////////////////////////////
                $database->setQuery("SELECT * FROM #__vm_category_xref WHERE category_parent_id='" . $subcategory->category_child_id . "' ");
                $usub = $database->loadObjectList();
                foreach ($usub as $usubcategory) {
                    $database->setQuery("SELECT category_name FROM #__vm_category WHERE category_id='" . $usubcategory->category_child_id . "'");
                    $usubcategory_name = $database->loadResult();
                    $usubcat = $usubcategory_name;
                    $datacsv_usubcat = ";;" . $usubcat . ";;;;;;" . "\n";
                    fwrite($fp, $datacsv_usubcat);
                    /*echo "Подкатегория 2-",":", $usubcat, "ID", $usubcategory->category_child_id, "<br>";*/
///////////////////////////////////Выбор товара в подкатегории 3 порядка////////////////////////////
                    $database->setQuery("SELECT * FROM #__vm_product_category_xref WHERE category_id='" . $usubcategory->category_child_id . "' ");
                    $prod = $database->loadObjectList();
                    foreach ($prod as $product) {
                        $database->setQuery("SELECT * FROM #__vm_product WHERE product_id='" . $product->product_id . "'");
                        $produc = $database->loadObjectList();
                        foreach ($produc as $pro) {
                            $database->setQuery("SELECT product_price FROM #__vm_product_price WHERE product_id='" . $product->product_id . "'");
                            $product_price3 = $database->loadResult();

                            $datacsv_pid = ";;;" . $pro->product_parent_id;
                            $datacsv_sku = $pro->product_sku;
                            $datacsv_name = $pro->product_name; //Имя продукта
                            $datacsv_s_desc = $pro->product_s_desc;
                            $datacsv_desc = $pro->product_desc;
                            $product_price = str_replace('.', ',', $product_price3);
                            $datacsv_price = $product_price . "\n";
                            fwrite($fp, $datacsv_pid . ";" . $pro->product_sku . ";" . $datacsv_name . ";" . $datacsv_s_desc . ";" . $datacsv_desc . ";" . $datacsv_price);
                            /*echo "Товар 3-",":", $pro->product_parent_id, $pro->product_sku,  $pro->product_name, $pro->product_s_desc, $pro->product_desc, $product_price, "<br>";*/
                        }
                    }
                    //Конец выбора товара
                }
///////////////////////////////////Выбор товара в подкатегории 2 порядка////////////////////////////
                $database->setQuery("SELECT * FROM #__vm_product_category_xref WHERE category_id='" . $subcategory->category_child_id . "' ");
                $prod = $database->loadObjectList();
                foreach ($prod as $product) {
                    $database->setQuery("SELECT * FROM #__vm_product WHERE product_id='" . $product->product_id . "'");
                    $produc = $database->loadObjectList();
                    foreach ($produc as $pro) {
                        $database->setQuery("SELECT product_price FROM #__vm_product_price WHERE product_id='" . $product->product_id . "'");
                        $product_price2 = $database->loadResult();
                        $datacsv_pid = ";;;" . $pro->product_parent_id;
                        $datacsv_sku = $pro->product_sku;
                        $datacsv_name = $pro->product_name; //Имя продукта
                        $datacsv_s_desc = $pro->product_s_desc;
                        $datacsv_desc = $pro->product_desc;
                        $product_price = str_replace('.', ',', $product_price2);
                        $datacsv_price = $product_price . "\n";
                        fwrite($fp, $datacsv_pid . ";" . $pro->product_sku . ";" . $datacsv_name . ";" . $datacsv_s_desc . ";" . $datacsv_desc . ";" . $datacsv_price);
                        /*echo "Товар 2-",":", $pro->product_parent_id, $pro->product_sku,  $pro->product_name, $pro->product_s_desc, $pro->product_desc, $product_price, "<br>";*/
                    }
                }
////////////////////////////////////Конец выбора товара////////////////////////////////////////
            }
////////////////////////////////////Выбор товара в категории////////////////////////////////////////
            $database->setQuery("SELECT * FROM #__vm_product_category_xref WHERE category_id='" . $category->category_child_id . "' ");
            $prod = $database->loadObjectList();
            foreach ($prod as $product) {
                $database->setQuery("SELECT * FROM #__vm_product WHERE product_id='" . $product->product_id . "'");
                $produc = $database->loadObjectList();
                foreach ($produc as $pro) {
                    $database->setQuery("SELECT product_price FROM #__vm_product_price WHERE product_id='" . $product->product_id . "'");
                    $product_price = $database->loadResult();
                    $datacsv_pid = ";;;" . $pro->product_parent_id;
                    $datacsv_sku = $pro->product_sku;
                    $datacsv_name = $pro->product_name; //Имя продукта
                    $datacsv_s_desc = $pro->product_s_desc;
                    $datacsv_desc = $pro->product_desc;
                    $product_price = str_replace('.', ',', $product_price);
                    $datacsv_price = $product_price . "\n";

                    fwrite($fp, $datacsv_pid . ";" . $pro->product_sku . ";" . $datacsv_name . ";" . $datacsv_s_desc . ";" . $datacsv_desc . ";" . $datacsv_price);
                    /*echo "Товар -",":", $pro->product_parent_id, $pro->product_sku,  $pro->product_name, $pro->product_s_desc, $pro->product_desc, $product_price, "<br>";*/
                }
            }
////////////////////////////////////Конец выбора товара////////////////////////////////////////
        }

    }
    fclose($fp);
    $file = file_get_contents("cache/myimport/export_price.csv");
    $file = iconv("utf-8", "windows-1251", $file);
    file_put_contents("cache/myimport/export_price.csv", $file);
    ?>
Екcпорт товара успешно завершен
<a href="<?php echo "cache/myimport/export_price.csv"; ?>">Загрузить прайс</a><?
} elseif ($task == "about") {
    include('about.myimport.php');
} else {
    ?>
<style type="text/css">
    <!--
    .style1 {
        color: #0099FF
    }

    -->
</style>
<table width="100%" class="adminform">
    <tr>

        <td width="20%" valign="top">

            <div id="cpanel">
                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=com_myimport&task=import" style="text-decoration:none;"
                           title="Импорт">
                            <img src="components/com_myimport/images/addedit.png" width="48px" height="48px"
                                 align="middle" border="0"/>
                            <br/>
                            Импорт </a></div>
                </div>

                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=com_myimport&task=export" style="text-decoration:none;"
                           title="Експорт">
                            <img src="components/com_myimport/images/backup.png" width="48px" height="48px"
                                 align="middle" border="0"/>
                            <br/>
                            Експорт </a></div>
                </div>

                <div style="float:left;">
                    <div class="icon">
                        <a href="index2.php?option=com_myimport&task=about" style="text-decoration:none;"
                           title="Справка">
                            <img src="components/com_myimport/images/info.png" width="48px" height="48px" align="middle"
                                 border="0"/>
                            <br/>
                            Справка </a></div>
                </div>
            </div>
            <!-- ICON END --></td>
        <td width="20%" align="center" valign="middle"><p><strong>Компонент импорта/экспорта товаров в Virtuemart 1.1.x
            для Joomla 1.5.x</strong></p>

            <p class="style1">MyImport 1.5.0</p></td>
    </tr>
</table>
<?php
}
?>

