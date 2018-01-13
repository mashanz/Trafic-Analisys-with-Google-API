    <?php //buat fetch data ke dropdown
  include '0dbconnect.php'; //koneksi database
  // mysql select query
  $query = "SELECT * FROM tablehari";
  $query2 = "SELECT * FROM tablewaktu";
  $result1 = mysqli_query($koneksi, $query);
  $result2 = mysqli_query($koneksi, $query2);

  /*if (isset($_POST['submit'])){
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
  echo $awal."<br>".$akhir."<br>";
  echo $kond;

  mysqli_query($koneksi,"DELETE FROM temp");
  mysqli_query($koneksi,"DELETE FROM sort");
}*/
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="http://localhost/mapping/bootstrap/jquery.min.js"></script>

  <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      
    </style>
  </head>
  <body>
    <form class="form-vertical" role="form" action="" method="post">
      <div class="form-group">
        <b>Start: </b>
        <select id="start" name="awal">
          <option value="chicago, il">Chicago</option>
          <option value="st louis, mo">St Louis</option>
          <option value="joplin, mo">Joplin, MO</option>
          <option value="oklahoma city, ok">Oklahoma City</option>
          <option value="amarillo, tx">Amarillo</option>
          <option value="gallup, nm">Gallup, NM</option>
          <option value="flagstaff, az">Flagstaff, AZ</option>
          <option value="winona, az">Winona</option>
          <option value="kingman, az">Kingman</option>
          <option value="barstow, ca">Barstow</option>
          <option value="san bernardino, ca">San Bernardino</option>
          <option value="los angeles, ca">Los Angeles</option>
        </select>
        <b>End: </b>
        <select id="end" name="akhir">
          <option value="chicago, il">Chicago</option>
          <option value="st louis, mo">St Louis</option>
          <option value="joplin, mo">Joplin, MO</option>
          <option value="oklahoma city, ok">Oklahoma City</option>
          <option value="amarillo, tx">Amarillo</option>
          <option value="gallup, nm">Gallup, NM</option>
          <option value="flagstaff, az">Flagstaff, AZ</option>
          <option value="winona, az">Winona</option>
          <option value="kingman, az">Kingman</option>
          <option value="barstow, ca">Barstow</option>
          <option value="san bernardino, ca">San Bernardino</option>
          <option value="los angeles, ca">Los Angeles</option>
        </select>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4">Hari</label>            
        <select id="hari" name="hari">
          <?php while($row1 = mysqli_fetch_array($result1)):;?>
            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option> <!--show column hari, value column id-->
          <?php endwhile;?>
        </select>
        <label class="control-label col-sm-4">Waktu</label>           
        <select id="jam" name="jam">
          <?php while($row1 = mysqli_fetch_array($result2)):;?>
            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option> <!--show column hari, value column id-->
          <?php endwhile;?>
        </select>
      </div>
  <!--div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" class="btn btn-default btn-block">proses</button>
    </div>
  </div-->
</form>

<div id="map"></div>
<script>
  var directionsDisplay;
  var kondisi = "macet";
  var map;

  function initMap() {
    var directionsService = new google.maps.DirectionsService;

    directionsDisplay = new google.maps.DirectionsRenderer({
      map: map
    });

    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 7,
      center: {lat: 41.85, lng: -87.65}
    });
    directionsDisplay.setMap(map);

    var onChangeHandler = function() {
      calculateAndDisplayRoute(directionsService, directionsDisplay);
    };
    document.getElementById('start').addEventListener('change', onChangeHandler);
    document.getElementById('end').addEventListener('change', onChangeHandler);
    document.getElementById('hari').addEventListener('change', onChangeHandler);
    document.getElementById('jam').addEventListener('change', onChangeHandler);
  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay) {

    var kondisi = "";
    var awal = document.getElementById('start').value;
    var akhir = document.getElementById('end').value;
    var hari = document.getElementById('hari').value;
    var jam = document.getElementById('jam').value;

          /*
          Script ajax / post
          */var kirim = {
            "awal" : awal,
            "akhir" : akhir,
            "hari" : hari,
            "jam" : jam
          };
          
          $.post( "http://localhost/mapping/kira.php", kirim, function( data ) {
              kondisi = data; //data -> string
              //alert(kondisi);
            });
          

          directionsService.route({
            origin: awal,
            destination: akhir,
            travelMode: 'DRIVING'
          }, function(response, status) {
            if (status === 'OK') {
              map = new google.maps.Map(document.getElementById('map'), {
      zoom: 7,
      center: {lat: 41.85, lng: -87.65}
    });
              if (kondisi=="macet") {
                directionsDisplay = new google.maps.DirectionsRenderer({
                  map: map,
                  polylineOptions: { strokeColor: "red" }
                });
              } else {
                directionsDisplay = new google.maps.DirectionsRenderer({
                  map: map,
                  polylineOptions: { strokeColor: "blue" }
                });
              }
              directionsDisplay.setDirections(response);
            } else {
              window.alert('Directions request failed due to ' + status);
            }
          });
        }
      </script>
      <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzRLhlQb9YJLyOe1BWSJZuDY4PJBykJEo&callback=initMap">
    </script>

  </body>
  </html>





