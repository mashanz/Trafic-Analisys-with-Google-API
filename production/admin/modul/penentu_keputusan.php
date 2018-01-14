<html>
<?php
switch($_GET['act']){
default:
echo "<h2>Penentu Keputusan</h2>";

include "form_penentu_keputusan.php";

echo "<p>Opsi: <a href=./aksi.php?module=penentu_keputusan&act=delete_data_penentu_keputusan>Hapus Semua Data</a>
      </p>";
      
echo "<table bgcolor='#00CCFF' border='1' cellspacing='0' cellspading='0'>
    <tr>
    <th>No</th>
    <th>Data</th>
    <th>Hari</th>
    <th>Jam</th>
    <th>Lokasi</th>
    <th>class</th>
    </tr>";

    $sql=mysql_query('SELECT * FROM hasil_prediksi ORDER BY id');
    $warna1 = '#FFFFFF';
          $warna2 = '#CCFFFF';
          $warna  = $warna1; 
          $no = 1; 
    while ($data=mysql_fetch_array($sql)){
        if($warna == $warna1){ 
            $warna = $warna2; 
        } else { 
            $warna = $warna1; 
        } 
        echo "<tr bgcolor='$warna'>
               <td>$no</td>
                   <td>$data[id]</td>
                   <td>$data[hari]</td>
                   <td>$data[jam]</td>
                   <td>$data[lokasi]</td>
                   <td>$data[class_prediksi]</td>
				   <td>
          ";
          if ($id_data['class'] == 'lancar') {
              echo "<font color=green><b>$data[class]</b></font>";
          } elseif ($id_data['class'] == 'padat') {
              echo "<font color=green><b>$data[class]</b></font>";
          } elseif ($id_data['class'] == 'macet') {
              echo "<font color=green><b>$data[class]</b></font>";
          }
		// echo "</td>
  //         <td>
  //           <a href=./aksi.php?module=penentu_keputusan&act=hapus&id=$data[id_data]>Hapus</a> | <a href=media.php?module=penentu_keputusan&act=detail_perhitungan&id=$data[id]>Detail Perhitungan</a>
  //         </td>
  //             </tr>";
  //       $no++;
    }
    
echo"</table>";
break;
//case "detail_perhitungan";
    //include "detail_perhitungan.php";
//break;
}
?>
</html>