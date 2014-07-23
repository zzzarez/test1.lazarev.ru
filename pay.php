<?php
if($_GET['pass']=='tranceport77')
{

$order_id = $_GET['oid'];
$amount = 14000;
//$host = 'lazarev.excluzive.ws';
$host = 'shop.lazarev.ru';


$key = 'Fzrqa0DrmCu';

$base = 'http://'.$host;
$path = 'administrator/components/com_virtuemart';
$script = 'onpay_notify.php';

$url = $base.'/'.$path.'/'.$script;



$arg = array(
    'type' => 'pay',
    'onpay_id' => 1,
    'pay_for' => $order_id,
    'order_amount' => $amount,
    'balance_amount' => 1,
    'order_currency' => 'RU',
    'balance_currency' => 'RU',
    'exchange_rate' => 1,
);

$md5 = strtoupper(md5($arg['type'].';'.$arg['pay_for'].';'.$arg['onpay_id'].';'.$arg['order_amount'].';'.$arg['order_currency'].';'.$key));
echo 'Generate MD5: '.$md5."\n";

$arg['md5'] = $md5;

foreach ($arg as $parameter => $value)
{
	if (!isset($first))
	{
		$url .= '?';
		$first = true;
	}
	else $url .= '&';

	$url .= $parameter.'='.$value;
}
echo 'open url: '.$url."\n";
echo file_get_contents($url);
}
else
{
echo "не надо баловаться!";
}
?>
