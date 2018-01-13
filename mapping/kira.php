<?php
session_start(); // this NEEDS TO BE AT THE TOP of the page before any output etc

	include '0dbconnect.php'; //connect to database
	$testhari = $_POST['hari'];
	$testjam = $_POST['jam'];
	$awal = $_POST['awal'];
	$akhir = $_POST['akhir'];
	$sql="SELECT * FROM test";
	$result = mysqli_query($koneksi, $sql);
	$rowcount=mysqli_num_rows($result);

	//memberi nilai k
	$k=3; 
	for($i=1; $i<=$rowcount; $i++){		
		$sql1=mysqli_query($koneksi,"SELECT * FROM test WHERE id=$i");
		while($baris=mysqli_fetch_row($sql1)){
			$hari = $baris[1];
			$waktu = $baris[2];
			$kondisi = $baris[3];
			
			$query = "SELECT id as nohari from tablehari WHERE hari = '$hari'";
			$hasil = mysqli_query($koneksi, $query);
			$idhari = $hasil->fetch_object()->nohari;
			
			$query2 = "SELECT id as nojam from tablewaktu WHERE waktu = '$waktu'";
			$hasil2 = mysqli_query($koneksi, $query2);
			$idjam = $hasil2->fetch_object()->nojam;								

			//pengurangan
			$selisihhari = $testhari - $idhari;
			$selisihjam = $testjam - $idjam;
			//peng-kuadrat-an
			$kuadrat = (pow($selisihhari,2)) + (pow($selisihjam,2));
			//peng-akar-an
			$akar = sqrt($kuadrat);
			//simpan perhitungan di table temp
			$sql2="INSERT INTO temp (hari,jam,jarak,status) VALUES ($idhari,$idjam,$akar,'$kondisi')";
			mysqli_query($koneksi,$sql2);			
		}	
	}

	$sql3="SELECT * FROM temp ORDER BY jarak LIMIT 0,$k";
	$data=mysqli_query($koneksi,$sql3);
	
	while ($baris=mysqli_fetch_row($data)){
		$sql4="INSERT INTO sort (v1,v2,jarak,status) VALUES ($baris[1],$baris[2],$baris[3],'$baris[4]')";
		mysqli_query($koneksi,$sql4);

	}

	$sql5="SELECT id, jarak, COUNT(*) AS jml, STATUS
 			FROM sort
 			GROUP BY STATUS
 			HAVING ( COUNT(STATUS) > 1 )";
 	$data=mysqli_query($koneksi,$sql5);
 	$i=0;
 	while($baris=mysqli_fetch_row($data)){
 		$id[]=$baris[0];
 		$jarak[]=$idhari;
 		$jml[]=$idjam;
 		$status[]=$baris[3];
 		$i++;
 	}
 	//membandingkan banyak data per status
 	if ($i>1){
 		if($jml[0]>$jml[1]){
 			$kond = $status[0];
 		}else{
 			$kond = $status[1];
 		}
 	}else{
 		$kond = $status[0];
 	}

 	$awal = "-6.207733, 106.803253";
 	$akhir = "-6.204064, 106.800645";
 	echo $kond."~".$testjam."~".$awal."~".$akhir;

 	mysqli_query($koneksi,"DELETE FROM temp");
 	mysqli_query($koneksi,"DELETE FROM sort");