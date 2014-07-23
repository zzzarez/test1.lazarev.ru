<?php
include_once ('../configuration.php');
$jconfig = new JConfig();
$db_config = mysql_connect($jconfig->host, $jconfig->user, $jconfig->password) or die(mysql_error());
mysql_select_db($jconfig->db, $db_config) or die(mysql_error());
mysql_query("SET CHARACTER SET 'utf8'", $db_config);

$Year = $_GET['year'];

$Month_r = array(
    "1" => "январь",
    "2" => "февраль",
    "3" => "март",
    "4" => "апрель",
    "5" => "май",
    "6" => "июнь",
    "7" => "июль",
    "8" => "август",
    "9" => "сентябрь",
    "10" => "октябрь",
    "11" => "ноябрь",
    "12" => "декабрь");


echo "<style>";
echo ".cell{width:110px;background:rgb(200, 255, 233);;display:inline-block;}";
echo ".cell2{width:110px;background:white;display:inline-block;}";
echo "</style>";
$i = 0;

echo "Количество зарегистрированных пользователей по месяцам на " . $_GET['year'] . " г:<br><br>";
$vsego=0;
for ($month_num = 1; $month_num <= 12; $month_num++) {
    $last_day_month = date("t", mktime(0, 0, 0, $month_num, 1, $Year));

    $q = "SELECT COUNT( * ) as c FROM  jos_users WHERE registerDate >=  '" . $Year . "-" . $month_num . "-01 00:00:00' AND registerDate <=  '" . $Year . "-" . $month_num . "-" . $last_day_month . " 23:59:59'";
//echo "<br>".$q;
    $result = mysql_query($q, $db_config);
    if (!$result) {
        $message = 'Неверный запрос: ' . mysql_error() . "\n";
        $message .= 'Запрос целиком: ' . $q;
        die($message);
    }
//$itogo=mysql_num_rows($result);

    $row = mysql_fetch_assoc($result);

    $vsego+=$row['c'];


    $i++;
    $is = "";
    if ($i % 2 == 0) {
        $is = "2";
    }
    echo "<div style='width:1000px;'>";
    echo "<div class='cell" . $is . "' style='width:150px;'>" . $Month_r[$month_num] . " " . $Year . " </div> ";
    echo "<div class='cell" . $is . "' style='width:350px;'>" . $row['c'] . " </div> ";
    echo "</div>";
}

echo "<br><br>Всего в $Year год зарегистрировалось ".$vsego;



$q = "SELECT COUNT( * ) as c FROM  jos_users WHERE registerDate >=  '2009-01-01 00:00:00' AND registerDate <= NOW()";
$result = mysql_query($q, $db_config);
if (!$result) {
    $message = 'Неверный запрос: ' . mysql_error() . "\n";
    $message .= 'Запрос целиком: ' . $q;
    die($message);
}
$row = mysql_fetch_assoc($result);

echo "<br><br>Всего зарегистрировалось ".$row['c'];

mysql_free_result($result);




?>
