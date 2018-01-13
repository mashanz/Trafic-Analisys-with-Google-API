<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_koneksi = "127.0.0.1";
$database_koneksi = "kemacetan_c45";
$username_koneksi = "root";
$password_koneksi = "op3nk3y";
$koneksi = mysql_pconnect($hostname_koneksi, $username_koneksi, $password_koneksi) or trigger_error(mysql_error(),E_USER_ERROR); 
?>