<?php

require_once(dirname(__FILE__). '/../configuration.php');

init_db();

$stat = 'stat_info';

$time = gmdate('Y-m-d H:i:s', time());
$query = "SELECT downloadid, file_name FROM current_downloadid, jos_vm_product_download WHERE download_id = downloadid AND file_name REGEXP '_view_1'";
$users_d1 = rows(query($query));

$query = "SELECT downloadid, file_name FROM current_downloadid, jos_vm_product_download WHERE download_id = downloadid AND file_name REGEXP '_view_2'";
$users_d2 = rows(query($query));

$query = "SELECT * FROM $stat";
$res = query($query);
$max_d1 = fetch($res)->max_users_d1;
$max_d2 = fetch($res)->max_users_d2;

$query = "UPDATE $stat SET current_users_d1=$users_d1, current_users_d2=$users_d2";
if ($max_d1 < $users_d1) $query .= ", max_users_d1=$users_d1";
if ($max_d2 < $users_d2) $query .= ", max_users_d2=$users_d2";
query($query);

close_db();

function init_db()
{
  global $mysql;
  $config = new JConfig();

  $host = $config->host;
  $db   = $config->db;
  $user = $config->user;
  $pass = $config->password;

  $mysql = mysql_connect($host, $user, $pass)
          or die("Could not connect: " . mysql_error());
  mysql_select_db($db, $mysql)
          or die("Can\'t select database: " . mysql_error());
  query("START TRANSACTION");
}
function close_db()
{
  global $mysql;
  query("COMMIT");
  mysql_close($mysql);
}
function query($query)
{
  global $mysql;
  $resource = mysql_query($query, $mysql) or die("Error: " . mysql_error());
  return $resource;
}
function fetch($resource)
{
  return mysql_fetch_object($resource);
}

function rows($resource)
{
  return mysql_num_rows($resource);
}
?>
