<?php 
require_once('Connections/koneksi.php'); 
include "../config/hitungWaktu.php";
include "../config/paginate.php";
//  error_reporting(E_ERROR | E_PARSE); 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data kemacetan - C45</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>Kemacetan</span></a>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="index.php"><i class="fa fa-home"></i> Home</a>
                  </li>
                  <li><a><i class="fa fa-desktop"></i> Data Kemacetan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="inputdata.php">Input Data</a></li>
                      <li><a href="lihatdata.php">Lihat Data</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-line-chart"></i> Kinerja <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <li><a href="perhitungan.php">Perhitungan C45</a></li>
                     <li><a href="mining.php">Proses Mining</a></li>
                     <li><a href="keputusan.php">Pohon Keputusan</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa fa-flask"></i>Pengujian <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="partisi.php">Partisi Data</a></li>
                      <li><a href="prediksi.php">Hasil Prediksi</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Suhartina Hajrahnur
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                   
                    <li><a href="login.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Algoritma C45</small></h3>
              </div>

              
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Perhitungn C45</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
<?php
include "../../config/koneksi.php";

// sudah oke
echo "<h2>C45 &#187; Pohon Keputusan</h2>";
echo " <p>Opsi: <a href=./aksi.php?module=c45&act=hapus_pohon_keputusan>Hapus Semua Data</a></p>";
echo "<font face='Courier New' size='2'>";
echo "<h3><b>Pohon Keputusan: <br></b></h3>";
function get_subfolder($idparent, $spasi){
    $result = mysql_query("select * from pohon_keputusan_c45 where id_parent= '$idparent'");
    while($row=mysql_fetch_row($result)){
        for($i=1;$i<=$spasi;$i++){echo "|&nbsp;&nbsp;";}
        if ($row[8] === 'Lancar') {$keputusan = "<font color=green>$row[8]</font>";
        } elseif ($row[8] === 'Padat') {$keputusan = "<font color=blue>$row[8]</font>";
        } elseif ($row[8] === 'Macet') {$keputusan = "<font color=red>$row[8]</font>";
        } elseif ($row[8] === 'Tidak Bisa Lewat') {$keputusan = "<font color=purple>$row[8]</font>";
        } elseif ($row[8] === '?') {$keputusan = "<font color=black>$row[8]</font>";
        } else {$keputusan = "<b>$row[8]</b>";
        }
        echo "<font color=red>$row[1]</font> = $row[2] (Lancar = $row[4], Padat = $row[5], Macet = $row[6], Tidak bisa dilewati = $row[7]) : <b>$keputusan</b><br>";
        get_subfolder($row[0], $spasi + 1);
    }
}

get_subfolder('0', 0);

echo "<hr>";
//echo "<h3><b>Chart Pohon Keputusan <br></b></h3>";
?>

  <link rel="stylesheet" href="admin/modul/tree_graph/css/jquery.jOrgChart.css"/>
  <link rel="stylesheet" href="admin/modul/tree_graph/css/custom.css"/>
  <link href="admin/modul/tree_graph/css/prettify.css" type="text/css" rel="stylesheet" />

  <script type="text/javascript" src="admin/modul/tree_graph/prettify.js"></script>
  <script type="text/javascript" src="admin/modul/tree_graph/jquery-2.0.2.js"></script>
  <script type="text/javascript" src="admin/modul/tree_graph/jquery-ui.min.js"></script>
  <script src="admin/modul/tree_graph/jquery.jOrgChart.js"></script>

  <script>
  jQuery(document).ready(function() {
    $("#org").jOrgChart({
      chartElement : '#chart',
      dragAndDrop  : true
    });
  });
  </script>


<ul id="org" style="display:none"></ul>
<script>
  function draw_tree(id, parent_id, atribut, nilai_atribut, keputusan){
    if(parent_id == 0){
      if($("org li").length){
        if(keputusan == '?') $("#parent ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
        else $("#parent ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
      }else{
        $("#org").append("<li id='parent'>"+atribut+"<ul></ul></li>");
        
        if(keputusan == '?') $("#parent ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
        else $("#parent ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
      }
    }else{
      if($("#"+parent_id+" p").length){
        if(keputusan == "?") $("#"+parent_id+" ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
        else $("#"+parent_id+" ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
      }else{
        $("#"+parent_id).append("<p>"+atribut+"</p> <ul></ul>");
        
        if(keputusan == "?") $("#"+parent_id+" ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
        else $("#"+parent_id+" ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
      }
    }
  }
   
<?php

$query = mysql_query("SELECT * FROM  pohon_keputusan_c45");
while($row = mysql_fetch_assoc($query)){
  echo "draw_tree(".$row['id'].",".$row['id_parent'].",'".str_replace("-"," ",str_replace("_"," ",$row['atribut']))."','".str_replace("-"," ",str_replace("_"," ",$row['nilai_atribut']))."','".str_replace("-"," ",str_replace("_"," ",$row['keputusan']))."');\n";
}
?>
</script>
  
<div id="chart" class="orgChart"></div>

<?php //echo "disini buat nampilin chart";?>
  
<script>
  jQuery(document).ready(function() {    
    /* Custom jQuery for the example */
    $("#show-list").click(function(e){
      e.preventDefault();
      $('#list-html').toggle('fast', function(){
        if($(this).is(':visible')){
          $('#show-list').text('Hide underlying list.');
          $(".topbar").fadeTo('fast',0.9);
        }else{
          $('#show-list').text('Show underlying list.');
          $(".topbar").fadeTo('fast',1);                  
        }
      });
    });
    $('#list-html').text($('#org').html());
    $("#org").bind("DOMSubtreeModified", function() {
      $('#list-html').text('');
      $('#list-html').text($('#org').html());
    });
  });
</script>
<?php

// sudah oke
echo "<hr>";
echo "<h3><b>Rule: <br></b></h3>";
$no = 1;
// if ($_SESSION[leveluser]=='admin'){
    $sqlLihatRule = mysql_query("select * from rule_c45 order by id" );
// }
while($rowLihatRule=mysql_fetch_array($sqlLihatRule)){
    if ($rowLihatRule['keputusan'] === 'Lancar') {
        $keputusan = "<font color=green>$rowLihatRule[keputusan]</font>";
    } elseif ($rowLihatRule['keputusan'] === 'Padat') {
        $keputusan = "<font color=blue>$rowLihatRule[keputusan]</font>";
    } elseif ($rowLihatRule['keputusan'] === 'Macet') {
        $keputusan = "<font color=purple>$rowLihatRule[keputusan]</font>";
    } elseif ($rowLihatRule['keputusan'] === 'Tidak bisa dilewati') {
        $keputusan = "<font color=red>$rowLihatRule[keputusan]</font>";
    } elseif ($rowLihatRule['keputusan'] === '?') {
        $keputusan = "<font color=black>$rowLihatRule[keputusan]</font>";
    } else {
        $keputusan = "<b>$rowLihatRule[keputusan]</b>";
    }
    echo "<b>$no.</b> if <b>(</b>$rowLihatRule[rule]<b>)</b> then <b>$keputusan</b> <font color=blue>(id = $rowLihatRule[id])</font><br>";
$no++;
}
echo "</font><br>";
?>
                  </div>
                </div>
              </div>

          
          
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Suhartina Hajrahnur
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

  </body>
</html>