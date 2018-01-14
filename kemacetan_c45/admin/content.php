<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/hitungWaktu.php";
include "../config/paginate.php";

// Bagian Home
if ($_GET[module]=='home'){
    echo "<h2>Identifikasi Jenis Kemacetan Menggunakan Pohon Keputusan C4.5</h2>
    <table width='100%'>
        <tr>
            <td>
                <h2 align='justify'>
                    Selamat Datang.<br>
                </h2>
            </td>
        </tr>
    </table>";
}

// Modul Data Kemacetan
elseif ($_GET[module]=='data_kemacetan'){
    include "modul/data_kemacetan.php";
}

// Modul C4.5
elseif ($_GET[module]=='c45'){
    include "modul/c45.php";
}

// Modul about
elseif ($_GET[module]=='about'){
    include "modul/about.php";
}

// Modul Penentu Keputusan
elseif ($_GET[module]=='penentu_keputusan'){
    include "modul/penentu_keputusan.php";
}

// Modul Kinerja
elseif ($_GET[module]=='kinerja'){
    include "modul/kinerja.php";
}

else{
    echo "<p><b>MENU BELUM ADA</b></p>";
}
?>