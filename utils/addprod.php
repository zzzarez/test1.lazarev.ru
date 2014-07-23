<form action="/utils/addprod.php?act=save" method="post">
Категория: <input id="cat" type="text" name="cat" value="<?php echo $_POST['cat'];?>" ><br>
Артикуль: <input id="plu" type="text" name="plu" value="<?php echo $_POST['plu'];?>"><br>
Название: <input id="name" type="text" name="name" value="<?php echo $_POST['name'];?>"><br>

Описание: <textarea id="desc" type="text" size="9999" name="desc" style="width: 500px;height: 200px;" ><?php echo $_POST['desc'];?></textarea><br>
Файлы: <textarea id="files" type="text" size="9999" name="files" style="width: 500px;height: 200px;"><?php echo $_POST['files'];?></textarea><br>
Цена: <input id="price" type="text" name="price" value="<?php echo $_POST['price'];?>"><br>
Картинка: <input id="img" type="text" name="img" value="<?php echo $_POST['img'];?>"><br>
<input type="submit" id="submit" name="в файл...">
</form>

<?php
$str="Категория;Подкатегория;ПодПодкатегория;Товар-родитель;Артикул;Наименование;Краткое описание;Описание;Цена\n";
//echo $_GET['act'];
if($_GET['act']=="save")
{
echo 111;
    if($_POST['cat']!='')
        $str.=$_POST['cat'].";;;;;;;;\n";
    else
        echo "Error - cat!";

    if($_POST['plu']!='')
        $str.=";;;0;".$_POST['plu'].";";
    else
        echo "Error - plu!";

    if($_POST['name']!='')
        $str.=$_POST['name'].";;";
    else
        echo "Error - name!";


    if($_POST['desc']!=''){
        $links = explode ("\r",$_POST['desc']);
        for ($i=0; $i < count($links); $i++)
        {
            $str.= str_replace("\n", "", $links[$i]."<br>");
        }
        $str.=";";
        $links ='';
    }
    else{
        echo "Error - desc!";
    }

    if($_POST['price']!='')
        $str.=$_POST['price'].";";
    else
        echo "Error - price!";

    if($_POST['files']!=''){
        $links = explode ("\r",$_POST['files']);
        for ($i=0; $i < count($links); $i++)
        {
            $str.=str_replace("\n", "", $links[$i].";");
        }
        $str.=";";
        for ($f=0; $f < (56-$i); $f++)
        {
            $str.=";";
        }
    }
    else{
        echo "Error - files!";
    }

    if($_POST['img']!='')
        $str.=$_POST['img'].";\n";
    else
        echo "Error - img!";


    $hdl=fopen("./prod/".$_POST['plu'],"w");
    print_r("hdl---".$hdl);
    $fi = fwrite($hdl, iconv('UTF-8', 'Windows-1251', $str));
    fclose($hdl);

}



?>