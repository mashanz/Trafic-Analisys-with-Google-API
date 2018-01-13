    <?php //buat fetch data ke dropdown
  include '0dbconnect.php'; //koneksi database
  // mysql select query
  $query = "SELECT * FROM tablehari";
  $query2 = "SELECT * FROM tablewaktu";
  $result1 = mysqli_query($koneksi, $query);
  $result2 = mysqli_query($koneksi, $query2);
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
          <option value="-6.207733, 106.803253">mpr</option>
          <option value="-6.204064, 106.800645">slpi</option>
        </select>
        <b>End: </b>
        <select id="end" name="akhir">
          <option value="-6.207733, 106.803253">mpr</option>
          <option value="-6.204064, 106.800645">slpi</option>
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
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="submit" class="btn btn-default btn-block">proses</button>
    </div>
  </div>
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
    // document.getElementById('start').addEventListener('change', onChangeHandler);
    // document.getElementById('end').addEventListener('change', onChangeHandler);
    //document.getElementById('hari').addEventListener('change', onChangeHandler);
    document.getElementById('jam').addEventListener('change', onChangeHandler);
  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay) {

    var kondisi = "";
    var name = "";
    var street = "";
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
              var input = kondisi;
              var fields = input.split('~');
              name = fields[0];
              street = fields[1];
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
</script>

  </body>
  </html>





