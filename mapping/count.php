<?php
	include '0dbconnect.php'; //connect to database
	
//get value(id) from dropdown
	$testhari = $_POST['hari'];
	$testjam = $_POST['jam'];
	$testjalan = $_POST['jalan'];

	//echo "hari test data: ".$testhari."<br>jam test data: ".$testjam."<br>".$testjalan."<br>";
	//get number of row in table
	$sql="SELECT * FROM $testjalan";
	$result = mysqli_query($connect, $sql);
	$rowcount=mysqli_num_rows($result);

	//memberi nilai k
	$k=3; 
	//echo "nilai k adalah $k <br>";

//show data
	// echo "<table border=1>";
	// echo "<tr>
	// 		<th>day</th>
	// 		<th>hour</th>
	// 		<th>daytest - daytraining</th>
	// 		<th>hourtest - hourtraining</th>
	// 		<th>day^2</th>
	// 		<th>hour^2</th>
	// 		<th>(day^2)+(hour^2)</th>
	// 		<th>sqrt(day^2+hour^2)</th>
	// 		<th>kondisi</th>
	// 	 </tr>";
	for($i=1; $i<=$rowcount; $i++){
		//echo "<tr>";
		$sql1=mysqli_query($connect,"SELECT * FROM $testjalan WHERE id=$i");
		while($baris=mysqli_fetch_row($sql1)){
			$hari = $baris[1];
			$waktu = $baris[2];
			$kondisi = $baris[3];
			//echo "data ke-".$i."<br>";
			$query = "SELECT id as nohari from 1tablehari WHERE hari = '$hari'";
			$hasil = mysqli_query($connect, $query);
			$idhari = $hasil->fetch_object()->nohari;
			//echo "hari ke: ".$idhari."<br>";
			$query2 = "SELECT id as nojam from 1tablewaktu WHERE waktu = '$waktu'";
			$hasil2 = mysqli_query($connect, $query2);
			$idjam = $hasil2->fetch_object()->nojam;
			//echo "jam ke: ".$idjam."<br><br>";								


			//pengurangan
			$selisihhari = $testhari - $idhari;
			$selisihjam = $testjam - $idjam;
			//peng-kuadrat-an
			$kuadrat = (pow($selisihhari,2)) + (pow($selisihjam,2));
			//peng-akar-an
			$akar = sqrt($kuadrat);
			//simpan perhitungan di table temp
			$sql2="INSERT INTO 1temp (hari,jam,jarak,status) VALUES ($idhari,$idjam,$akar,'$kondisi')";
			mysqli_query($connect,$sql2);
			// echo "<td>$testhari - ".$idhari."</td>";
			// echo "<td>$testjam - ".$idjam."</td>";
			// echo "<td>".$selisihhari."</td>";
			// echo "<td>".$selisihjam."</td>";
			// echo "<td>".(pow($selisihhari,2))."</td>";
			// echo "<td>".(pow($selisihjam,2))."</td>";
			// echo "<td>".$kuadrat."</td>";
			// echo "<td>".$akar."</td>";
			// echo "<td>".$kondisi."</td>";
		}
		//echo "</tr>";
		
	}

//sortir data
	// echo "<table border=1>";
	// echo "<tr>
	// 		<th>day</th>
	// 		<th>hour</th>
	// 		<th>jarak</th>
	// 		<th>status</th>
	// 	 </tr>";
	$sql3="SELECT * FROM 1temp ORDER BY jarak LIMIT 0,$k";
	$data=mysqli_query($connect,$sql3);
	//echo "<tr>";
	while ($baris=mysqli_fetch_row($data)){
		$sql4="INSERT INTO 1sort (v1,v2,jarak,status) VALUES ($baris[1],$baris[2],$baris[3],'$baris[4]')";
		mysqli_query($connect,$sql4);

		// echo "<td>".$baris[1]."</td>";
		// echo "<td>".$baris[2]."</td>";
		// echo "<td>".$baris[3]."</td>";
		// echo "<td>".$baris[4]."</td>";
		// echo "</tr>";
	}
	//echo "</table>";

 //mengambil status yang banyak muncul
 	$sql5="SELECT id, jarak, COUNT(*) AS jml, STATUS
 			FROM 1sort
 			GROUP BY STATUS
 			HAVING ( COUNT(STATUS) > 1 )";
 	$data=mysqli_query($connect,$sql5);
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
 			//echo "kondisi lalu lintas adalah <b>".$status[0]."</b>";
 			$kondisi = $status[0];
 		}else{
 			//echo "kondisi lalu lintas adalah <b>".$status[1]."</b>";
 			$kondisi = $status[1];

 		}
 	}else{
 		//echo "kondisi lalu lintas adalah <b>".$status[0]."</b>"; 
 		$kondisi = $status[0];

 	}
 	echo $kondisi;
 	mysqli_query($connect,"DELETE FROM 1temp");
 	mysqli_query($connect,"DELETE FROM 1sort");
 ?>



