<html>
<head>
	<title> Input Data</title>
	<style type ="text/css">

	table {
		background-color:  : #FCF;
	}
	th {
		widht :150px;
		text_align : left;
	}
</style>
</head>
<body>
	<h1> Input Data </h1>
	<form method="post" action="prediksi.php">
	<input type="hidden" name="submitted" value="true"/>

	<label> Hari :
	<select name="hari">
		<option value='senin'>senin</option>
    	<option value='selasa'>selasa</option>
    	<option value='rabu'>rabu</option>
    	<option value='kamis'>kamis</option>
    	<option value='jumat'>jumat</option>
    	<option value='sabtu'>sabtu</option>
    	<option value='minggu'>minggu</option>
		</select>
		</label>

	<label> Jam :
	<select name="jam">
		<option value='00:01-01:00'>00:01-01:00</option>
    	<option value='01:01-02:00'>01:01-02:00</option>
    	<option value='02:01-03:00'>02:01-03:00</option>
    	<option value='03:01-04:00'>03:01-04:00</option>
    	<option value='04:01-05:00'>04:01-05:00</option>
    	<option value='05:01-06:00'>05:01-06:00</option>
    	<option value='06:01-07:00'>06:01-07:00</option>
    	<option value='07:01-08:00'>07:01-08:00</option>
    	<option value='08:01-09:00'>08:01-09:00</option>
    	<option value='09:01-10:00'>09:01-10:00</option>
    	<option value='10:01-11:00'>10:01-11:00</option>
    	<option value='11:01-12:00'>11:01-12:00</option>
    	<option value='12:01-13:00'>12:01-13:00</option>
    	<option value='13:01-14:00'>13:01-14:00</option>
    	<option value='14:01-15:00'>14:01-15:00</option>
    	<option value='15:01-16:00'>15:01-16:00</option>
    	<option value='16:01-17:00'>16:01-17:00</option>
    	<option value='17:01-18:00'>17:01-18:00</option>
    	<option value='18:01-19:00'>18:01-19:00</option>
    	<option value='19:01-20:00'>19:01-20:00</option>
    	<option value='20:01-21:00'>20:01-21:00</option>
    	<option value='21:01-22:00'>21:01-22:00</option>
    	<option value='22:01-23:00'>22:01-23:00</option>
    	<option value='23:01-00:00'>20:01-00:00</option>
		</select>
		</label>

	<label> Lokasi :
	<select name="lokasi">
		<option value='tanah abang-jl mas mansyur'>tanah abang-jl mas mansyur</option>
    	<option value='bundaran hi-dukuh atas'>bundaran hi-dukuh atas</option>
    	<option value='casablanca-kp melayu'>casablanca-kp melayu</option>
    	<option value='dukuh atas-semanggi'>dukuh atas-semanggi</option>
    	<option value='jl haryono-pancoran'>jl haryono-pancoran</option>
    	<option value='pondok indah-lebak bulus'>pondok indah-lebak bulus</option>
    	<option value='slipi jaya-tomang'>slipi jaya-tomang</option>
    	<option value='kalibata-pancoran'>kalibata-pancoran</option>
    	<option value='pancoran-kalibata'>pancoran-kalibata</option>
    	<option value='kayu-rawasari'>kayu-rawasari</option>
    	<option value='jl mas mansyur-tanah abang'>jl mas mansyur-tanah abang</option>
    	<option value='mpr-slipi'>mpr-slipi</option>
    	<option value='pancoran-haryono'>pancoran-haryono</option>
    	<option value='mampang-rasuna said'>mampang-rasuna said</option>
    	<option value='rasuna said-mampang'>rasuna said-mampang</option>
    	<option value='senopati-blok s'>senopati-blok s</option>
    	<option value='thamrin-bundaran hi'>thamrin-bundaran hi</option>
		</select>
		</label>

		<input type="submit" name="Submit">
	</form>
<?php
if (isset($_POST['submitted'])) {
	include('koneksi.php');

	$hari = $_POST['hari'];
	$jam = $_POST['jam'];
	$lokasi = $_POST['lokasi'];
	$query = "SELECT * FROM hasil_prediksi WHERE $id='$hari";
	$result = mysql_query($koneksi, $query) or die('error getting data');
	echo "<table>";
	echo "<tr>ID</tr>
			<tr>Hari</tr>
			<tr>Jam</tr>
			<tr>Lokasi</tr>
			<tr>Class Prediksi</tr>";

	while ($row = mysql_fetch_array($result)){
		echo "<tr><td>";
		echo $row['id'];
		echo "<tr><td>";
		echo $row['hari'];
		echo "<tr><td>";
		echo $row['jam'];
		echo "<tr><td>";
		echo $row['lokasi'];
		echo "<tr><td>";
		echo $row['clas_prediksi'];
		echo "<tr><td>";
	}

	echo "</body>";
}
