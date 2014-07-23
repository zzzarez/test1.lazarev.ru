<?php
 include_once ('configuration.php');
 $jconfig = new JConfig();
 $db_config = mysql_connect( $jconfig->host, $jconfig->user, $jconfig->password ) or die( mysql_error() );
 mysql_select_db( $jconfig->db, $db_config ) or die( mysql_error() );
 mysql_query("SET CHARACTER SET 'utf8'",$db_config);
$dn="aga_view1.htm";
$dnum="16 ноября";
if($_GET['d']=='2'){
	$dn="aga_view2.htm";
	$dnum="17 ноября";
}

$q="SELECT FROM_UNIXTIME(ord.cdate) as orddate, us.name as name, us.email as mail, ord.user_id as uid, ord.order_id as oid ".
" FROM  `jos_vm_product_download` pd,  `jos_users` us, jos_vm_orders ord".
" WHERE pd.user_id = us.id".
" AND pd.user_id = ord.user_id".
" AND pd.order_id = ord.order_id".
" AND ord.order_status =  'C'".
" AND (".
" pd.file_name =  '".
$dn.
"')".
" AND ord.cdate > UNIX_TIMESTAMP(  '2013-10-20 00:00:00' ) ".
" AND ord.cdate < UNIX_TIMESTAMP(  '2013-11-30 00:00:00' ) ".
" LIMIT 0 , 99999";


$result = mysql_query ($q,$db_config);
if (!$result) {
    $message  = 'Неверный запрос: ' . mysql_error() . "\n";
    $message .= 'Запрос целиком: ' . $q;
    die($message);
}
$itogo=mysql_num_rows($result);

echo "<style>";
echo ".cell{width:110px;background:rgb(200, 255, 233);;display:inline-block;}";
echo ".cell2{width:110px;background:white;display:inline-block;}";
echo "</style>";
$i=0;
echo "<b>На ".$dnum."   всего куплено билетов:".$itogo."</b>";
while ($row = mysql_fetch_assoc($result)) 
{
	$i++;
	$is="";
	if($i%2==0){
		$is="2";
	}
echo "<div style='width:1000px;'>";
    echo "<div class='cell".$is."' style='width:150px;'>".$row['orddate']." </div> ";
    echo "<div class='cell".$is."' style='width:350px;'>".$row['name']." </div> ";
    echo "<div class='cell".$is."' style='width:250px;'>".$row['mail']." </div> ";
    echo "<div class='cell".$is."' style='width:80px;'>".$row['uid']." </div> ";
    echo "<div class='cell".$is."' style='width:80px;'>".$row['oid']." </div> ";
echo "</div>";

}

mysql_free_result($result);

?>
