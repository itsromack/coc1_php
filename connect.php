<?php
session_start();

$host = 'localhost';
$database = 'tesda_libsys';
$username = 'root';
$password = '';

$conn = mysql_connect($host, $username, $password) 
            or die('unable to connect');
mysql_select_db($database) or die('unable to open database');
?>
