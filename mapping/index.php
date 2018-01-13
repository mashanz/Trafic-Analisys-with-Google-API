<?php
	include '0dbconnect.php'; //koneksi database
	// mysql select query
	$query = "SELECT * FROM tablehari";
	$query2 = "SELECT * FROM tablewaktu";
	$result1 = mysqli_query($koneksi, $query);
	$result2 = mysqli_query($koneksi, $query2);
  $kondisi = "macet";
?>



<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
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
    
    
    <form class="form-vertical" role="form" action="kira.php" method="post">
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
				      <select name="hari">
			            <?php while($row1 = mysqli_fetch_array($result1)):;?>
			            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option> <!--show column hari, value column id-->
			            <?php endwhile;?>
			        </select>
				    <label class="control-label col-sm-4">Waktu</label>				    
				      <select name="jam">
			            <?php while($row1 = mysqli_fetch_array($result2)):;?>
			            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option> <!--show column hari, value column id-->
			            <?php endwhile;?>
			        </select>
				    
			  	</div>
			  	<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				     <button type="submit" class="btn btn-default btn-block">proses</button>
				    </div>
			  	</div>
			</form>
    
    <div id="map"></div>
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        if (kondisi == "macet"){
        	var directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            polylineOptions: { strokeColor: "#8b0013" }
        	});
        } else {
        	var directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            polylineOptions: { strokeColor: "blue" }
        	});
        }
        
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: -6.207885, lng: 106.846671}
        });
        directionsDisplay.setMap(map);

        var onChangeHandler = function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
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

