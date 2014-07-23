<?php

require_once(dirname(__FILE__).'/../configuration.php');

if (isset($_GET['download_id']))
{
  $did = $_GET['download_id'];
  init_db();
  $date = gmdate("Y-m-d H:i:s", time()+60);
  $q = "UPDATE current_downloadid SET intime='$date' WHERE downloadid='$did'";
  query($q);
  setcookie("f81ef6h", $did, time()+60, "/");
}

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
  mysql_select_db($db,$mysql)
          or die("Can\'t select database: " . mysql_error());
}

function query($query)
{
  global $mysql;
  $resource = mysql_query($query, $mysql) or die("Error: " . mysql_error());
  return $resource;
}

?>
