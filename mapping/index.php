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
  //$query_Recordset1 = "SELECT * FROM hari";
  //$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
  //$Recordset1 = mysql_query($query_limit_Recordset1, $koneksi) or die(mysql_error());
  //$row_Recordset1 = mysql_fetch_assoc($Recordset1);

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

  $colname_Recordset4 = "";
  if (isset($_GET['hari'])) $colname_Recordset4 = $_GET['hari'];

  $colname1_Recordset4 = "";
  if (isset($_GET['jam'])) $colname1_Recordset4 = $_GET['jam'];

  $colname2_Recordset4 = "";
  if (isset($_GET['lokasi'])) $colname2_Recordset4 = $_GET['lokasi'];

  $query_Recordset4 = sprintf("SELECT * FROM hasil_prediksi WHERE hari = %s AND jam= %s  AND lokasi=%s", GetSQLValueString($colname_Recordset4, "text"),GetSQLValueString($colname1_Recordset4, "text"),GetSQLValueString($colname2_Recordset4, "text"));
  $Recordset4 = mysql_query($query_Recordset4, $koneksi) or die(mysql_error());
  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  $totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
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
        background-color: #4286f4;
        padding: 5px;
        border: 1px solid #c2c2c2;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <div id="floating-panel">
    <b>Start: </b>
    <select id="start">
      <?php do { ?>
        <option value="<?php echo $row_Recordset3['start']; ?>"><?php echo $row_Recordset3['lokasi']; ?></option>
      <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>

    <?php
      $query_Recordset3 = "SELECT * FROM lokasi";
      $Recordset3 = mysql_query($query_Recordset3, $koneksi) or die(mysql_error());
      $row_Recordset3 = mysql_fetch_assoc($Recordset3);
      $totalRows_Recordset3 = mysql_num_rows($Recordset3);
    ?>

    </select>
    <b>End: </b>
    <select id="end">
      <?php do { ?>
        <option value="<?php echo $row_Recordset3['end']; ?>"><?php echo $row_Recordset3['ruas']; ?></option>
      <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
      
    </select>
    <b>Hari: </b>
    <select id="hari">
      <?php do { ?>
        <option value="<?php echo $row_Recordset1['hari']; ?>"><?php echo $row_Recordset1['hari']; ?></option>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    </select>
    <b>Jam: </b>
    <select id="jam">
      <?php do { ?>
        <option value="<?php echo $row_Recordset2['waktu']; ?>"><?php echo $row_Recordset2['waktu']; ?></option>
      <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
    </select>
    <b>Kondisi: </b>
    <select id="kondisi">
      <option value="green">Lancar</option>
      <option value="yellow">Padat</option>
      <option value="red">Macet</option>
    </select>
    </div>

    <div id="map"></div>
    <script>
      var directionsService;
      var directionsDisplay;
      var map;
      var onChangeHandler;
      var styledMapType

      function initMap() {
        styledMapType = new google.maps.StyledMapType([
              {
            elementType: 'geometry',
            stylers: [{color: '#f5f5f5'}]
          },
          {
            elementType: 'labels.icon',
            stylers: [{visibility: 'off'}]
          },
          {
            elementType: 'labels.text.fill',
            stylers: [{color: '#616161'}]
          },
          {
            elementType: 'labels.text.stroke',
            stylers: [{color: '#f5f5f5'}]
          },
          {
            featureType: 'administrative.land_parcel',
            elementType: 'labels.text.fill',
            stylers: [{color: '#bdbdbd'}]
          },
          {
            featureType: 'poi',
            elementType: 'geometry',
            stylers: [{color: '#eeeeee'}]
          },
          {
            featureType: 'poi',
            elementType: 'labels.text.fill',
            stylers: [{color: '#757575'}]
          },
          {
            featureType: 'poi.park',
            elementType: 'geometry',
            stylers: [{color: '#e5e5e5'}]
          },
          {
            featureType: 'poi.park',
            elementType: 'labels.text.fill',
            stylers: [{color: '#9e9e9e'}]
          },
          {
            featureType: 'road',
            elementType: 'geometry',
            stylers: [{color: '#ffffff'}]
          },
          {
            featureType: 'road.arterial',
            elementType: 'labels.text.fill',
            stylers: [{color: '#757575'}]
          },
          {
            featureType: 'road.highway',
            elementType: 'geometry',
            stylers: [{color: '#dadada'}]
          },
          {
            featureType: 'road.highway',
            elementType: 'labels.text.fill',
            stylers: [{color: '#616161'}]
          },
          {
            featureType: 'road.local',
            elementType: 'labels.text.fill',
            stylers: [{color: '#9e9e9e'}]
          },
          {
            featureType: 'transit.line',
            elementType: 'geometry',
            stylers: [{color: '#e5e5e5'}]
          },
          {
            featureType: 'transit.station',
            elementType: 'geometry',
            stylers: [{color: '#eeeeee'}]
          },
          {
            featureType: 'water',
            elementType: 'geometry',
            stylers: [{color: '#c9c9c9'}]
          },
          {
            featureType: 'water',
            elementType: 'labels.text.fill',
            stylers: [{color: '#9e9e9e'}]
          }
            ],
            {name: 'Styled Map'});


        onChangeHandler = function() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 7,
          center: {lat: 41.85, lng: -87.65},
          mapTypeControlOptions: {
            mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
                    'styled_map']
          }
        });

        map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');

          directionsService = new google.maps.DirectionsService;
          directionsDisplay = new google.maps.DirectionsRenderer({
            polylineOptions: {
              strokeColor: document.getElementById('kondisi').value,
              strokeOpacity: 0.75,
              strokeWeight: 5
          }});
          
          directionsDisplay.setMap(map);
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };

        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
        document.getElementById('hari').addEventListener('change', onChangeHandler);
        document.getElementById('jam').addEventListener('change', onChangeHandler);
        document.getElementById('kondisi').addEventListener('change',onChangeHandler);
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