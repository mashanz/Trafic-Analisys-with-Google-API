<?php
  require_once('../production/Connections/koneksi.php');
  mysql_select_db($database_koneksi, $koneksi);

  $query_Recordset = "SELECT * FROM lokasi";
  $Recordset = mysql_query($query_Recordset, $koneksi) or die(mysql_error());
  $row_Recordset = mysql_fetch_assoc($Recordset);
  $totalRows_Recordset = mysql_num_rows($Recordset);

  // $query_Recordset2 = "SELECT * FROM hasil_prediksi WHERE jam='17:01-18:00' AND hari='Kamis' AND lokasi='tanah abang-jl mas mansyur'";
  // $Recordset2 = mysql_query($query_Recordset2, $koneksi) or die(mysql_error());
  // $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  // $totalRows_Recordset2 = mysql_num_rows($Recordset2);


  // do { 
  // 	echo $row_Recordset['start']."&nbsp;";
  // 	echo $row_Recordset['lokasi']."<br>"; 
  // } while ($row_Recordset = mysql_fetch_assoc($Recordset));

  // echo "<br>";

  // do { 
  // 	echo $row_Recordset2['hari']."&nbsp;";
  // 	echo $row_Recordset2['jam']."&nbsp;";
  // 	echo $row_Recordset2['lokasi']."&nbsp;";
  // 	echo $row_Recordset2['class_prediksi']."<br>"; 
  // } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));

  // $query_Recordset3 = "SELECT * FROM hasil_prediksi";
  // $Recordset3 = mysql_query($query_Recordset3, $koneksi) or die(mysql_error());
  // $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  // $totalRows_Recordset3 = mysql_num_rows($Recordset3);

  // do { 
  // 	echo $row_Recordset3['hari']."&nbsp;";
  // 	echo $row_Recordset3['jam']."&nbsp;";
  // 	echo $row_Recordset3['lokasi']."&nbsp;";
  // 	echo $row_Recordset3['class_prediksi']."<br>"; 
  // } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));

  $LL = 0;
  $LP = 0;
  $LM = 0;

  $PL = 0;
  $PP = 0;
  $PM = 0;

  $ML = 0;
  $MP = 0;
  $MM = 0;

  $total_data = 0;

  $query_Recordset3 = "SELECT * FROM hasil_prediksi WHERE status_data='Data Testing'";
  $Recordset3 = mysql_query($query_Recordset3, $koneksi) or die(mysql_error());
  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($Recordset3);

  do { 
    $banding = $row_Recordset3['class_asli'].$row_Recordset3['class_prediksi'];
    $total_data += 1;
    switch ($banding) {
      case 'lancarlancar':
        $LL += 1;
        break;
     
      case 'lancarpadat':
        $LP += 1;
        break;

      case 'lancarmacet':
        $LM += 1;
        break;

      case 'padatlancar':
        $PL += 1;
        break;
     
      case 'padatpadat':
        $PP += 1;
        break;

      case 'padatmacet':
        $PM += 1;
        break;

      case 'macetlancar':
        $ML += 1;
        break;
     
     case 'macetpadat':
        $MP += 1;
        break;

      case 'macetmacet':
        $MM += 1;
        break;
      
      default:
        break;
    }

    //echo $row_Recordset3['class_asli'].$row_Recordset3['class_prediksi']."&nbsp;";
    //echo $row_Recordset3['status_data']."<br>"; 
  } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));

  echo $LL."<br>";
  echo $LP."<br>";
  echo $LM."<br>";

  echo $PL."<br>";
  echo $PP."<br>";
  echo $PM."<br>";

  echo $ML."<br>";
  echo $MP."<br>";
  echo $MM."<br>";

  echo $total_data."<br>";

  $presisi_lancar = $LL/($LL+$PL+$ML)*100;
  $presisi_padat = $PP/($LP+$PP+$MP)*100;
  $presisi_macet = $MM/($LM+$PM+$MM)*100;

  $recall_lancar = $LL/($LL+$LP+$LM)*100;
  $recall_padat = $PP/($PL+$PP+$PM)*100;
  $recall_macet = $MM/($ML+$MP+$MM)*100;

  $presisi_total = ($presisi_lancar+$presisi_padat+$presisi_macet)/3;
  $recall_total = ($recall_lancar+$recall_padat+$recall_macet)/3;
  $accuracy = ($LL+$PP+$MM)/$total_data*100;

  echo "presisi :".$presisi_total."%<br>";
  echo "recall :".$recall_total."%<br>";
  echo "akurasi :".$accuracy."%<br>";
?>
