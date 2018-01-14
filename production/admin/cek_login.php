<?php
include "../config/koneksi.php";
$login=mysql_query("SELECT * FROM user WHERE id_user='$_POST[username]' AND password='$_POST[password]'");
$ketemu=mysql_num_rows($login);
$r=mysql_fetch_array($login);

// Apabila username dan password ditemukan
if ($ketemu > 0) {
    session_start();
    $_SESSION[namauser] = $r[id_user];
    $_SESSION[passuser] = $r[password];
    $_SESSION[leveluser]= $r[level];
    // echo $_SESSION['leveluser'];
    header('location:media.php?module=data_kemacetan');
} else {
    echo "<script>alert('Login gagal! Username & Password tidak benar!'); document.location.href='index.php';</script>\n";
}
