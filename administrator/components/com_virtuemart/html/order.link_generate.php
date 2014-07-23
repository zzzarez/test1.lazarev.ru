<?php
//if (!defined('_VALID_MOS') && !defined('JEXEC')) die('Direct Access to '.basename(__FILE__).' is not allowed.');

require_once(CLASSPATH . 'ps_order.php');

$url = URL."index.php?option=com_virtuemart&page=shop.downloads&Itemid=".$sess->getShopItemid();

$db = new ps_DB;
$q = "SELECT product_id,order_id,user_id,download_id,file_name FROM #__{vm}_product_download WHERE";
$q .= " order_id='".$_GET['order_id']."' order by file_name ASC";
$db->query($q);
$msg = '';
$i=0;
while ($db->next_record())
{

    $product_id  = $db->f('product_id');
    $user_id = $db->f('user_id');
    $download_id = $db->f('download_id');
    $filename = $db->f('file_name');
    $dbp = new ps_DB;
    $q = "SELECT product_name FROM #__{vm}_product WHERE";
    $q .= " product_id='".$product_id."'";
    $dbp->query($q);
    $product_name = $dbp->f('product_name');

    if($old_product_name!=$product_name)
        $msg .= "<br><br>\n$product_name <br>";


    $old_product_name=$product_name;
    $msg .= "<br>\n$filename: ";
    $msg .= "$url&download_id=$download_id";
}
echo $msg;

?>