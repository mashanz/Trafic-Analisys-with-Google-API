<?php
  require_once('../production/Connections/koneksi.php');
  mysql_select_db($database_koneksi, $koneksi);

  $query_Recordset = "SELECT * FROM lokasi";
  $Recordset = mysql_query($query_Recordset, $koneksi) or die(mysql_error());
  $row_Recordset = mysql_fetch_assoc($Recordset);
  $totalRows_Recordset = mysql_num_rows($Recordset);

  $query_Recordset2 = "SELECT * FROM hasil_prediksi WHERE jam='17:01-18:00' AND hari='Kamis' AND lokasi='tanah abang-jl mas mansyur'";
  $Recordset2 = mysql_query($query_Recordset2, $koneksi) or die(mysql_error());
  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  $totalRows_Recordset2 = mysql_num_rows($Recordset2);


  do { 
  	echo $row_Recordset['start']."&nbsp;";
  	echo $row_Recordset['lokasi']."<br>"; 
  } while ($row_Recordset = mysql_fetch_assoc($Recordset));

  echo "<br>";

  do { 
  	echo $row_Recordset2['hari']."&nbsp;";
  	echo $row_Recordset2['jam']."&nbsp;";
  	echo $row_Recordset2['lokasi']."&nbsp;";
  	echo $row_Recordset2['class_prediksi']."<br>"; 
  } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));

  $query_Recordset3 = "SELECT * FROM hasil_prediksi";
  $Recordset3 = mysql_query($query_Recordset3, $koneksi) or die(mysql_error());
  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($Recordset3);

  do { 
  	echo $row_Recordset3['hari']."&nbsp;";
  	echo $row_Recordset3['jam']."&nbsp;";
  	echo $row_Recordset3['lokasi']."&nbsp;";
  	echo $row_Recordset3['class_prediksi']."<br>"; 
  } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
?>
