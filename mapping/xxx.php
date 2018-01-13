<?php //buat fetch data ke dropdown
  include '0dbconnect.php'; //connect database
  // mysql select query
  $queryh = "SELECT * FROM tablehari";
  $queryw = "SELECT * FROM tablewaktu";
  $queryj = "SELECT * FROM tablejalan";
  $resulth = mysqli_query($connect, $queryh);
  $resultw = mysqli_query($connect, $queryw);
  $resultj = mysqli_query($connect, $queryj);
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
    <form class="form-vertical" role="form" action="count.php" method="post">
      <div class="form-group">
        <label class="control-label col-sm-4">Nama Tempat</label>  
        <select id="jalan" name="jalan">
          <?php while($rowj = mysqli_fetch_array($resultj)):;?>
            <option value="<?php echo $rowj[1];?>"><?php echo $rowj[2];?></option> <!--show column hari, value column id-->
          <?php endwhile;?>
          </select>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4">Hari</label>            
        <select id="hari" name="hari">
          <?php while($rowh = mysqli_fetch_array($resulth)):;?>
            <option value="<?php echo $rowh[0];?>"><?php echo $rowh[1];?></option> <!--show column hari, value column id-->
          <?php endwhile;?>
        </select>
        <label class="control-label col-sm-4">Waktu</label>           
        <select id="jam" name="jam">
          <?php while($roww = mysqli_fetch_array($resultw)):;?>
            <option value="<?php echo $roww[0];?>"><?php echo $roww[1];?></option> <!--show column hari, value column id-->
          <?php endwhile;?>
        </select>
      </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" class="btn btn-default btn-block">proses</button>
    </div>
  </div>
</form>

<div id="result"></div>
<!-- <div id="map"></div>
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
    // document.getElementById('start').addEventListener('change', onChangeHandler);
    // document.getElementById('end').addEventListener('change', onChangeHandler);
    //document.getElementById('hari').addEventListener('change', onChangeHandler);
    document.getElementById('jam').addEventListener('change', onChangeHandler);
  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay) {

    var kondisi = "";
    var awal = "";
    var akhir = ""; 
    var jalan = document.getElementById('jalan').value;
    var hari = document.getElementById('hari').value;
    var jam = document.getElementById('jam').value;

          /*
          Script ajax / post
          */var kirim = {
            "jalan" : jalan,
            "hari" : hari,
            "jam" : jam
          };
          
          $.post( "http://localhost/mapping/count.php", kirim, function( data ) {
              kondisi = data; //data -> string
              var input = kondisi;
              var fields = input.split('~');
              var name = fields[0];
              var street = fields[1];
              awal = fields[2];
              akhir = fields[3];
              alert('kondisi: ' + name + ' jam :' + street); 
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
                  polylineOptions: { strokeColor: "green" }
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
</script> -->

  </body>
  </html>





