
<?php

$host = 'localhost';
$user = 'root';
$pswd = '';
$db = 'miner';

$koneksi = new mysqli($host,$user,$pswd,$db);

$sql = "SELECT lokasi,COUNT(*) AS jumlah
FROM zminer  
GROUP BY lokasi
ORDER BY jumlah DESC 
";
$result = mysqli_query($koneksi, $sql);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
    	echo $row['lokasi']." --> ".$row['jumlah']."<br>";
    	
        
    }
} else {
    echo "0 results";
}

// SELECT jalan,COUNT(*) AS jumlah
// FROM arah1     
// GROUP BY jalan
// ORDER BY jumlah DESC 
?>