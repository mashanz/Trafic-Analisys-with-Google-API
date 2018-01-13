
<?php

$host = 'localhost';
$user = 'root';
$pswd = '';
$db = 'miner';

$koneksi = new mysqli($host,$user,$pswd,$db);


$sql = "SELECT * FROM zminer";
$result = mysqli_query($koneksi, $sql);
$id = 1;


if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $kondisi =  $row['kondisi'];
      if ($kondisi == "lengang" || $kondisi == "lancar" || $kondisi == "cenderung lengang" || $kondisi == "cenderung lancar"){
        $bobot = "Lancar";
      } else 
      if ($kondisi == "padat" || $kondisi == "ramai" || $kondisi == "ramai lancar" || $kondisi == "padat merayap" ){
        $bobot = "Padat";
      } else {
        $bobot = "Macet";
      }
       echo $id. " --> ". $bobot. "<br>";
      $query = "UPDATE bundaran SET kondisi = '$bobot' where id = '$id' ";
      if (!mysqli_query($koneksi, $query)) {
          echo "Error updating record: " . mysqli_error($koneksi);
      } 
      $up =   "UPDATE bundaran SET lokasi = 'dukuh atas - bundaran hi' where id = '$id' ";
      if (!mysqli_query($koneksi, $up)) {
          echo "Error updating record: " . mysqli_error($koneksi);
      }

      }
      $id++;

    }
} else {
  echo "0 results";
  }   

//   $qjalan = "SELECT * FROM bundaran1";
//   $rjalan = mysqli_query($koneksi, $qjalan);
//   $rowcount = mysqli_num_rows($rjalan);

// for($i=1; $i<=$rowcount; $i++){
//     echo "<tr>";
//     $sql1=mysqli_query($koneksi,"SELECT * FROM bundaran1 WHERE id=$i");
//     while($baris=mysqli_fetch_row($sql1)){
//       $hari = $baris[1];
//       $waktu = $baris[3];
//       $query = "SELECT id as nohari from 1tablehari WHERE hari = '$hari'";
//       $hasil = mysqli_query($koneksi, $query);
//       $idhari = $hasil->fetch_object()->nohari;
//       $uph =   "UPDATE bundaran1 SET v1 = $idhari where id = '$i' ";
//       mysqli_query($koneksi, $uph);
//       $query2 = "SELECT id as nojam from 1tablewaktu WHERE waktu = '$waktu'";
//       $hasil2 = mysqli_query($koneksi, $query2);
//       $idjam = $hasil2->fetch_object()->nojam;
//       $uph =   "UPDATE bundaran1 SET v2 = $idjam where id = '$i' ";
//       mysqli_query($koneksi, $uph);
//       echo $idhari." --> ".$idjam."<br>";
//     }
//   }

  // $qjalan = "SELECT * FROM bang_mas";
  // $rjalan = mysqli_query($koneksi, $qjalan);
  // $rowcount = mysqli_num_rows($rjalan);
  // for($i=1; $i<=$rowcount; $i++){
  //   $sql1=mysqli_query($koneksi,"SELECT * FROM bang_mas WHERE id=$i");
  //   while($baris=mysqli_fetch_row($sql1)){
  //     $hari = $baris[1];
  //     $waktu = $baris[2];
  //     $id = $i;
  //     $query = "SELECT id as nohari from 1tablehari WHERE hari = '$hari'";
  //     $hasil = mysqli_query($koneksi, $query);
  //     $idhari = $hasil->fetch_object()->nohari;
  //     $query = "UPDATE bang_mas SET hari = '$idhari' where id = '$id' ";
  //     if (!mysqli_query($koneksi, $query)) {
  //         echo "Error updating record: " . mysqli_error($koneksi);
  //     } 
  //     $query2 = "SELECT id as nojam from 1tablewaktu WHERE waktu = '$waktu'";
  //     $hasil2 = mysqli_query($koneksi, $query2);
  //     $idjam = $hasil2->fetch_object()->nojam;
  //      $query = "UPDATE bang_mas SET jam = '$idjam' where id = '$id' ";
  //     if (!mysqli_query($koneksi, $query)) {
  //         echo "Error updating record: " . mysqli_error($koneksi);
  //     }       
  //   }
  // }






// SELECT jalan,COUNT(*) AS jumlah
// FROM arah1     
// GROUP BY jalan
// ORDER BY jumlah DESC 
?>