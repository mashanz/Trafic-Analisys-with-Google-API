<?php
error_reporting(0);
session_start();
if (empty($_SESSION[namauser]) AND empty($_SESSION[passuser])){
    echo "<link href='../config/adminstyle.css' rel='stylesheet' type='text/css'>
    <center>Untuk mengakses modul, Anda harus login <br>";
    echo "<a href=index.php><b>LOGIN</b></a></center>";
} else {
?>
<html>
<head>
    <title>Identifikasi Jenis Kemacetan Menggunakan Pohon Keputusan C4.5</title>
    <link  href="icon.png" rel="shortcut icon" type="image/png" />
    <link href="../config/adminstyle.css" rel="stylesheet" type="text/css" />
</head>
<body>

    <div id="header">
        <br>
        <div class='menupic'>
            <div class='menuhorisontal'>

            </div>
        </div>

        <div id="content">
            <?php include "content.php"; ?>
        </div>
        
	<div id="menu">
      <br><ul>
            <!-- <li><a href=?module=home>.: Home</a></li> -->
            <li><a href=?module=data_kemacetan>.: Data Kemacetan</a></li>
            <li><a href=?module=c45>.: C.45 &#187; Perhitungan</a></li>
            <li><a href=?module=c45&act=mining onClick=\"return confirm('Anda Yakin? Proses akan membutuhkan waktu yang lama.')\">.: C.45 &#187; Proses Mining</a></li>
            <li><a href=?module=c45&act=pohon_keputusan>.: C.45 &#187; Pohon Keputusan</a></li>
            <li><a href=?module=prediksi>.: C.45 &#187; Penentu Keputusan</a></li>
            <li><a href=?module=data_kemacetan&act=partisi_data>.: Partisi Data</a></li>
            <li><a href=http://localhost/mtjapps>.: kembali [ke MTJAPPS]</a></li>
            <!-- <li><a href=?module=about>.: About</a></li> -->
            <!-- <li><a href=logout.php>.: Logout</a></li> -->
        </ul>
	    <p>&nbsp;</p>
 	</div>

        <div id="footer">
            <p>Copyright&copy; 2017 by:<br />
            <b>Suhartina Hajrahnur || 1103144092</b></p>
        </div>
    </div>
</body>
</html>
<?php
}
?>