<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_koneksi = "localhost";
$database_koneksi = "kemacetan_c45";
$username_koneksi = "root";
$password_koneksi = "";

$hostname_koneksi2 = "127.0.0.1";
$database_koneksi2 = "kemacetan_c45";
$username_koneksi2 = "root";
$password_koneksi2 = "op3nk3y";

$koneksi = mysql_pconnect($hostname_koneksi2, $username_koneksi2, $password_koneksi2) or trigger_error(mysql_error(),E_USER_ERROR);
//$koneksi = mysql_pconnect($hostname_koneksi, $username_koneksi, $password_koneksi) or trigger_error(mysql_error(),E_USER_ERROR); 
?>