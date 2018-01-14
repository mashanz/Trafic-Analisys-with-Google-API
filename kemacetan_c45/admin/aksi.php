<?php
error_reporting(0);
function deleteAllDb()
{
    // mysql_query("TRUNCATE atribut");
    mysql_query("TRUNCATE data_kemacetan");
    mysql_query("TRUNCATE data_keputusan");
    mysql_query("TRUNCATE data_keputusan_kinerja");
    
    mysql_query("TRUNCATE iterasi_c45");
    mysql_query("TRUNCATE mining_c45");
    mysql_query("TRUNCATE pohon_keputusan_c45");
    mysql_query("TRUNCATE rule_c45");

    mysql_query("TRUNCATE rule_penentu_keputusan");
    mysql_query("TRUNCATE data_penentu_keputusan");
}

// session_start();
include "../config/koneksi.php";
include "../config/library.php";

$module=$_GET[module];
$act=$_GET[act];

// hapus Data Kemacetan per item
if ($module=='data_kemacetan' AND $act=='hapus_data_kemacetan'){
    mysql_query("DELETE FROM data_kemacetan WHERE id='$_GET[id]'");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=data_kemacetan';</script>\n";
}

// hapus semua Data Kemacetan
elseif ($module=='data_kemacetan' AND $act=='hapus_semua_data_kemacetan'){
	mysql_query("TRUNCATE `data_kemacetan`");
    deleteAllDb();
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=data_kemacetan';</script>\n";
}

// Input Data Kemacetan
elseif ($module=='data_kemacetan' AND $act=='input'){
    mysql_query("INSERT INTO data_kemacetan VALUES('',
        '$_POST[id_data]',
        '$_POST[lancar]',
        '$_POST[padat]',
        '$_POST[macet]',
        '$_POST[class]',
        '$_POST[status_data]'
        )");
    echo "<script>alert('Data berhasil diinput!'); document.location.href='media.php?module=data_kemacetan';</script>\n";
}

// Update Data Kemacetan
elseif ($module=='data_kemacetan' AND $act=='update_data_kemacetan'){
    mysql_query("UPDATE data_kemacetan SET 
        id_data = '$_POST[id_data]',
        lancar = '$_POST[lancar]',
        padat = '$_POST[padat]',
        macet = '$_POST[macet]',
        class = '$_POST[class]',
        status_data = '$_POST[status_data]'
        WHERE id      = '$_POST[id]'");

    echo "<script>alert('Data berhasil diupdate!'); document.location.href='media.php?module=data_kemacetan';</script>\n";
}

// Hapus Semua Data Perhitungan C45
elseif ($module=='c45' AND $act=='hapus_data_iterasi'){
	mysql_query("TRUNCATE `iterasi_c45`");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=c45';</script>\n";
}

// Hapus Semua Data Pohon Keputusan C45
elseif ($module=='c45' AND $act=='hapus_pohon_keputusan'){
	mysql_query("TRUNCATE `pohon_keputusan_c45`");
	mysql_query("TRUNCATE `rule_c45`");
    mysql_query("DELETE FROM rule_penentu_keputusan where pohon = 'C45'");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=c45&act=pohon_keputusan';</script>\n";
}

// Hapus Semua Data Penentu Keputusan
elseif ($module=='penentu_keputusan' AND $act=='delete_data_penentu_keputusan'){
	mysql_query("TRUNCATE `data_keputusan`");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=penentu_keputusan';</script>\n";
}

// Hapus Semua Data Penentu Keputusan per Item
if ($module=='penentu_keputusan' AND $act=='hapus'){
    mysql_query("DELETE FROM data_keputusan WHERE id='$_GET[id]'");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=penentu_keputusan';</script>\n";
}

// Hapus Semua Data Kinerja
elseif ($module=='kinerja' AND $act=='hapus_data_kinerja'){
	mysql_query("TRUNCATE `data_keputusan_kinerja`");
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=kinerja';</script>\n";
}

// Hapus Seluruh Database
elseif ($module=='lain-lain' AND $act=='delete_all_db'){
    deleteAllDb();
    echo "<script>alert('Data berhasil dihapus!'); document.location.href='media.php?module=lain-lain';</script>\n";
}
