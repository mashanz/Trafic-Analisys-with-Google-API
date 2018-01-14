<?php
switch($_GET[act]){
    default:
    echo "<h2>C45 &#187; Perhitungan C4.5</h2>";
    //include "menu_c45.php";
    echo " <p>Opsi: <a href=./aksi.php?module=c45&act=hapus_data_iterasi>Hapus Semua Data</a></p>
           <table bgcolor='#00CCFF' border='1' cellspacing='0' cellspading='0'>
           <tr>
               <th>No</th>
               <th>Atribut Gain Ratio Max</th>
               <th>Atribut</th>
               <th>Nilai Atribut</th>
               <th>Jumlah Kasus Total</th>
               <th>Jumlah Kasus Lancar</th>
               <th>Jumlah Kasus Padat</th>
               <th>Jumlah Kasus Macet</th>
               <th>Entropy</th>
               <th>Information Gain</th>
               <th>Split Info</th>
               <th>Gain Ratio</th>
           </tr>";
    
if ($_SESSION[leveluser]=='admin'){
    $sql=mysql_query('SELECT * FROM iterasi_c45 ORDER BY id');
}
    $warna1 = '#FFFFFF';
    $warna2 = '#CCFFFF'; 
    $warna  = $warna1; 

    while ($data=mysql_fetch_array($sql)){
        if($warna == $warna1){ 
            $warna = $warna2; 
        } else { 
            $warna = $warna1; 
        } 
        echo " <tr bgcolor='$warna'>
                   <td>$data[iterasi]</td>
                   <td>$data[atribut_gain_ratio_max]</td>
                   <td>$data[atribut]</td>
                   <td>$data[nilai_atribut]</td>
                   <td>$data[jml_kasus_total]</td>
                   <td>$data[jml_kasus_lancar]</td>
                   <td>$data[jml_kasus_padat]</td>
                   <td>$data[jml_kasus_macet]</td>
                   <td>$data[entropy]</td>
                   <td>$data[inf_gain]</td>
                   <td>$data[split_info]</td>
                   <td>$data[gain_ratio]</td>
               </tr>";
    }
    echo"</table>";
    break;
    case "mining";
        echo "<h2>C45 &#187; Mining C4.5</h2>";
        // include "menu_c45.php";
        $sql=mysql_query("SELECT COUNT(*) FROM data_kemacetan WHERE kondisi is not null");
        $data=mysql_fetch_array($sql);
        if (empty($data)) {
            echo "<script>alert('Data Training Kosong, Harap Input Data Training!'); document.location.href='media.php?module=c45';</script>\n";
        } else {
            timer_start();
            include "function/miningPrePruningC45.php";
            $timer = timer_stop(3);
            echo "<p>Proses Mining Selesai! waktu yang dibutuhkan $timer detik</p>";
        }
    break;
    case "pohon_keputusan";
        include "pohon_keputusan_c45.php";
    break;
}