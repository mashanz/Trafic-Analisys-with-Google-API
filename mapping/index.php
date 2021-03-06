<?php require_once('../production/Connections/koneksi.php'); ?>
<?php
  if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }

      $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;    
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }
      return $theValue;
    }
  }

  mysql_select_db($database_koneksi, $koneksi);

  $query_Recordset1 = "SELECT * FROM hari";
  $Recordset1 = mysql_query($query_Recordset1, $koneksi) or die(mysql_error());
  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($Recordset1);

  $query_Recordset2 = "SELECT * FROM jam";
  $Recordset2 = mysql_query($query_Recordset2, $koneksi) or die(mysql_error());
  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  $totalRows_Recordset2 = mysql_num_rows($Recordset2);

  $query_Recordset3 = "SELECT * FROM lokasi";
  $Recordset3 = mysql_query($query_Recordset3, $koneksi) or die(mysql_error());
  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($Recordset3);

  $colname_Recordset4 = "Sabtu";
  if (isset($_GET['hari'])) $colname_Recordset4 = $_GET['hari'];

  $colname1_Recordset4 = "00:01-01:00";
  if (isset($_GET['jam'])) $colname1_Recordset4 = $_GET['jam'];

  $colname2_Recordset4 = "pondok indah-lebak bulus";
  if (isset($_GET['lokasi'])) $colname2_Recordset4 = $_GET['lokasi'];


  // EXAMPLE DATA GET: index.php?hari=Kamis&jam=17:01-18:00&lokasi=tanah%20abang-jl%20mas%20mansyur
  //$query_Recordset4 = sprintf("SELECT * FROM hasil_prediksi WHERE hari = %s AND jam= %s  AND lokasi=%s", GetSQLValueString($colname_Recordset4, "text"),GetSQLValueString($colname1_Recordset4, "text"),GetSQLValueString($colname2_Recordset4, "text"));
  //$Recordset4 = mysql_query($query_Recordset4, $koneksi) or die(mysql_error());
  //$row_Recordset4 = mysql_fetch_assoc($Recordset4);
  //$totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>KONDISI LALIN</title>
    <style>
      #map {
        height: 100%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #c2c2c2;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      green{
        background-color: #0A0;
      }
      yellow{
        background-color: #FFFF00;
      }
      red{
        background-color: #f00;
      }
    </style>
  </head>
  <body>

    <?php 
      $query_Recordset4 = sprintf("SELECT * FROM hasil_prediksi WHERE hari=%s AND jam=%s AND lokasi=%s", GetSQLValueString($colname_Recordset4, "text"), GetSQLValueString($colname1_Recordset4, "text"), GetSQLValueString($colname2_Recordset4, "text"));
      $Recordset4 = mysql_query($query_Recordset4, $koneksi) or die(mysql_error());
      $row_Recordset4 = mysql_fetch_assoc($Recordset4);
      $totalRows_Recordset4 = mysql_num_rows($Recordset4);
    ?>
    <div id="floating-panel">
    <b>Keterangan: <green>LANCAR</green>, <yellow>PADAT</yellow>, <red>MACET</red></b>
    <br>
    <b>Lokasi: </b>
    <select id="start">
      <option value="0,0"> - </option>
      <?php do { ?>
        <option value="<?php echo $row_Recordset3['ruas']; ?>" <?php if(GetSQLValueString($row_Recordset3['ruas'], "text")==GetSQLValueString($colname2_Recordset4, "text")) echo 'selected'?> ><?php echo $row_Recordset3['lokasi']; ?></option>
      <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
    </select>

    <b>Hari: </b>
    <select id="hari">
      <?php do { ?>
        <option value="<?php echo $row_Recordset1['hari']; ?>" <?php if(GetSQLValueString($row_Recordset1['hari'], "text")==GetSQLValueString($colname_Recordset4, "text")) echo 'selected'?> ><?php echo $row_Recordset1['hari']; ?></option>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    </select>
    <b>Jam: </b>
    <select id="jam">
      <?php do { ?>
        <option value="<?php echo $row_Recordset2['waktu']; ?>" <?php if(GetSQLValueString($row_Recordset2['waktu'], "text")==GetSQLValueString($colname1_Recordset4, "text")) echo 'selected'?> ><?php echo $row_Recordset2['waktu']; ?></option>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
    </select>

    <button id="submisi">submit</button>

    <br>

    <?php 
      echo "<b>".$colname_Recordset4."&nbsp;";
      echo $colname1_Recordset4."&nbsp;";
      echo $colname2_Recordset4."&nbsp;&nbsp;&nbsp;&nbsp;";
      echo " KONDISI: ".$row_Recordset4['class_prediksi']."</b>";
    ?>

    </div>

    <div id="map"></div>
    <script>
      var directionsService;
      var directionsDisplay;
      var map;
      var onChangeHandler;
      var onChangeHandler2;
      var styledMapType

      function initMap() {
        styledMapType = new google.maps.StyledMapType([
              {elementType: 'geometry', stylers: [{color: '#ebe3cd'}]},
          {elementType: 'labels.text.fill', stylers: [{color: '#523735'}]},
          {elementType: 'labels.text.stroke', stylers: [{color: '#f5f1e6'}]},
          {
            featureType: 'administrative',
            elementType: 'geometry.stroke',
            stylers: [{color: '#c9b2a6'}]
          },
          {
            featureType: 'administrative.land_parcel',
            elementType: 'geometry.stroke',
            stylers: [{color: '#dcd2be'}]
          },
          {
            featureType: 'administrative.land_parcel',
            elementType: 'labels.text.fill',
            stylers: [{color: '#ae9e90'}]
          },
          {
            featureType: 'landscape.natural',
            elementType: 'geometry',
            stylers: [{color: '#dfd2ae'}]
          },
          {
            featureType: 'poi',
            elementType: 'geometry',
            stylers: [{color: '#dfd2ae'}]
          },
          {
            featureType: 'poi',
            elementType: 'labels.text.fill',
            stylers: [{color: '#93817c'}]
          },
          {
            featureType: 'poi.park',
            elementType: 'geometry.fill',
            stylers: [{color: '#a5b076'}]
          },
          {
            featureType: 'poi.park',
            elementType: 'labels.text.fill',
            stylers: [{color: '#447530'}]
          },
          {
            featureType: 'road',
            elementType: 'geometry',
            stylers: [{color: '#f5f1e6'}]
          },
          {
            featureType: 'road.arterial',
            elementType: 'geometry',
            stylers: [{color: '#fdfcf8'}]
          },
          {
            featureType: 'road.highway',
            elementType: 'geometry',
            stylers: [{color: '#f8c967'}]
          },
          {
            featureType: 'road.highway',
            elementType: 'geometry.stroke',
            stylers: [{color: '#e9bc62'}]
          },
          {
            featureType: 'road.highway.controlled_access',
            elementType: 'geometry',
            stylers: [{color: '#e98d58'}]
          },
          {
            featureType: 'road.highway.controlled_access',
            elementType: 'geometry.stroke',
            stylers: [{color: '#db8555'}]
          },
          {
            featureType: 'road.local',
            elementType: 'labels.text.fill',
            stylers: [{color: '#806b63'}]
          },
          {
            featureType: 'transit.line',
            elementType: 'geometry',
            stylers: [{color: '#dfd2ae'}]
          },
          {
            featureType: 'transit.line',
            elementType: 'labels.text.fill',
            stylers: [{color: '#8f7d77'}]
          },
          {
            featureType: 'transit.line',
            elementType: 'labels.text.stroke',
            stylers: [{color: '#ebe3cd'}]
          },
          {
            featureType: 'transit.station',
            elementType: 'geometry',
            stylers: [{color: '#dfd2ae'}]
          },
          {
            featureType: 'water',
            elementType: 'geometry.fill',
            stylers: [{color: '#b9d3c2'}]
          },
          {
            featureType: 'water',
            elementType: 'labels.text.fill',
            stylers: [{color: '#92998d'}]
          }
            ],
            {name: 'Suhartina Hajrahnur'});

          map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: {lat: 41.85, lng: -87.65},
            mapTypeControlOptions: {
              mapTypeIds: ['styled_map']
            }
          });

          map.mapTypes.set('styled_map', styledMapType);
          map.setMapTypeId('styled_map');

        onChangeHandler = function() {
          map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: {lat: 41.85, lng: -87.65},
            mapTypeControlOptions: {
              mapTypeIds: ['styled_map']
            }
          });

          map.mapTypes.set('styled_map', styledMapType);
          map.setMapTypeId('styled_map');

          document.location.href='index.php?hari='+document.getElementById('hari').value+'&jam='+document.getElementById('jam').value+'&lokasi='+document.getElementById('start').value;
          
          <?php 
            $kondisi = $row_Recordset4['class_prediksi'];

            $query_Recordset6 = sprintf("SELECT * FROM kondisi WHERE kondsi=%s", GetSQLValueString($kondisi, "text"));
            $Recordset6 = mysql_query($query_Recordset6, $koneksi) or die(mysql_error());
            $row_Recordset6 = mysql_fetch_assoc($Recordset6);
            $totalRows_Recordset6 = mysql_num_rows($Recordset6);
          ?>

          directionsService = new google.maps.DirectionsService;
          directionsDisplay = new google.maps.DirectionsRenderer({
            polylineOptions: {
              strokeColor: '<?php echo $row_Recordset6['warna']; ?>',
              strokeOpacity: 0.75,
              strokeWeight: 5
          }});
          
          directionsDisplay.setMap(map);
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };

        onChangeHandler2 = function() {
          map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: {lat: 41.85, lng: -87.65},
            mapTypeControlOptions: {
              mapTypeIds: ['styled_map']
            }
          });

          map.mapTypes.set('styled_map', styledMapType);
          map.setMapTypeId('styled_map');
          
          <?php 
            $kondisi = $row_Recordset4['class_prediksi'];

            $query_Recordset6 = sprintf("SELECT * FROM kondisi WHERE kondsi=%s", GetSQLValueString($kondisi, "text"));
            $Recordset6 = mysql_query($query_Recordset6, $koneksi) or die(mysql_error());
            $row_Recordset6 = mysql_fetch_assoc($Recordset6);
            $totalRows_Recordset6 = mysql_num_rows($Recordset6);
          ?>

          directionsService = new google.maps.DirectionsService;
          directionsDisplay = new google.maps.DirectionsRenderer({
            polylineOptions: {
              strokeColor: '<?php echo $row_Recordset6['warna']; ?>',
              strokeOpacity: 0.75,
              strokeWeight: 5
          }});
          
          directionsDisplay.setMap(map);
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };

        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('hari').addEventListener('change', onChangeHandler);
        document.getElementById('jam').addEventListener('change', onChangeHandler);
        document.getElementById('submisi').addEventListener('click',onChangeHandler2);
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        // get lokasi end berdasarkan GET start
        <?php 
          $query_Recordset5 = sprintf("SELECT * FROM lokasi WHERE ruas=%s", GetSQLValueString($colname2_Recordset4, "text"));
          $Recordset5 = mysql_query($query_Recordset5, $koneksi) or die(mysql_error());
          $row_Recordset5 = mysql_fetch_assoc($Recordset5);
          $totalRows_Recordset5 = mysql_num_rows($Recordset5);
        ?>
        directionsService.route({
          origin: '<?php echo $row_Recordset5['start']; ?>',
          destination: '<?php echo $row_Recordset5['end']; ?>',
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