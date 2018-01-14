<?php require_once('Connections/koneksi.php'); ?>
<?php
echo "";

function countData($atribut, $kondisi){
    $sql = mysql_query("SELECT count(id) as id FROM hasil_prediksi where $atribut AND class_asli = '$kondisi'");
    while($row = mysql_fetch_array($sql)) {
        $count = "$row[id]";
    }
    return $count;
}
function countPersentase($banyak, $atribut)
{
$sqlTotal = mysql_query("SELECT count(id) as id FROM hasil_prediksi where $atribut");
$rowTotal = mysql_fetch_array($sqlTotal);
$persen = round((($banyak * $rowTotal['id']) / 100), 0);
return $persen;
}

$status_data_lancar = countData("status_data is not null", "Lancar");
$status_data_padat = countData("status_data is not null", "Padat");
$status_data_macet = countData("status_data is not null", "Macet");
$total_data = $status_data_lancar + $status_data_padat + $status_data_macet;

$status_data_training_lancar = countData("status_data = 'Data Training'", "Lancar");
$status_data_training_padat = countData("status_data = 'Data Training'", "Padat");
$status_data_training_macet = countData("status_data = 'Data Training'", "Macet");
$data_training_total = $status_data_training_lancar + $status_data_training_padat + $status_data_training_macet;


$status_data_testing_lancar = countData("status_data = 'Data Testing'", "Lancar");
$status_data_testing_padat = countData("status_data = 'Data Testing'", "Padat");
$status_data_testing_macet = countData("status_data = 'Data Testing'", "Macet");
$data_testing_total = $status_data_testing_lancar + $status_data_testing_padat + $status_data_testing_macet;

// <?php
//  public function counttest()
//     {
//       $LL = $this->twitterModel->countHSc45('lancar', 'lancar');
//       $LP = $this->twitterModel->countHSc45('lancar', 'padat');
//       $LM = $this->twitterModel->countHSc45('lancar', 'macet');
      
//       $PL = $this->twitterModel->countHSc45('padat', 'lancar');
//       $PP = $this->twitterModel->countHSc45('padat', 'padat');
//       $PM = $this->twitterModel->countHSc45('padat', 'macet');
      
//       $ML = $this->twitterModel->countHSc45('macet', 'lancar');
//       $MP = $this->twitterModel->countHSc45('macet', 'padat');
//       $MM = $this->twitterModel->countHSc45('macet', 'macet');
      
//       //menghitung nilai presisi tiap kondisi
//       $AL = (($LL + $PL + $ML)/3)*100;
//       $AP = (($LP + $PP + $MP)/3)*100;
//       $AM = (($LM + $PM + $MM)/3)*100;

//        //menghitung nilai recall tiap kondisi
//       $JL = (($LL + $LP + $LM)/3)*100;
//       $JP = (($PL + $PP + $PM)/3)*100;
//       $JM = (($ML + $MP + $MM)/3)*100;
      
//       $precision = ($AL + ($AP + $AM)) * 100;
//       $recall = ($JL / ($JP + $JM)) * 100;
//       $accuracy = (($TP + $TN) / ($TP + $TN + $FP + $FN)) * 100;
      
//       // echo $SL;
//     echo "<table class='table table-bordered'>
//         <thead>
//           <tr>
//             <th class='center'> </th>
//             <th>Lancar</th>
//             <th>Padat</th>
//             <th>Macet</th>
//           </tr>
//         </thead>
//         <tbody>";
//     echo "<tr><td>Lancar</td>";
//     echo "<td>" . $LL ."</td>";
//     echo "<td>" . $PL ."</td>";
//     echo "<td>" . $ML ."</td>";
//     echo "</tr>";
//     echo "<tr><td>Padat</td>";
//     echo "<td>" . $LP ."</td>";
//     echo "<td>" . $PP ."</td>";
//     echo "<td>" . $MP ."</td>";
//     echo "</tr>";
//     echo "<tr><td>Macet</td>";
//     echo "<td>" . $LM ."</td>";
//     echo "<td>" . $PM ."</td>";
//     echo "<td>" . $MM ."</td>";
//     echo "</tr>";
//     echo "
//         </tbody>
//       </table>";
//       echo "<h3>Precision : " . number_format($precision, 2) . "%";
//       echo "<h3>Recall : " . number_format($recall ,2) . "%";
//       echo "<h3>Accuracy : " . number_format($accuracy, 2) . "%";
//     }



if (isset($_POST['submit'])) {
    if ($_POST['data'] > 100) {
        echo "<p>Data Kurusng dimasukkan harus lebih kecil dari 100!<p>";
    } else {
        $Macet = countPersentase($_POST['data'], "class_asli = 'Macet'");
        $Padat = countPersentase($_POST['data'], "class_asli = 'Padat'");
        $Lancar = countPersentase($_POST['data'], "class_asli = 'Lancar'");

        mysql_query("TRUNCATE data_keputusan");
        mysql_query("TRUNCATE data_keputusan_kinerja");
        mysql_query("TRUNCATE iterasi_id3");
        mysql_query("TRUNCATE pohon_keputusan_id3");
        mysql_query("TRUNCATE rule_id3");
        
        mysql_query("UPDATE hasil_prediksi SET status_data = ''");
        mysql_query("UPDATE hasil_prediksi SET status_data = 'Data Training' WHERE class_asli = 'Macet' LIMIT $Macet");
        mysql_query("UPDATE hasil_prediksi SET status_data = 'Data Training' WHERE class_asli = 'Padat' LIMIT $Padat");
        mysql_query("UPDATE hasil_prediksi SET status_data = 'Data Training' WHERE class_asli = 'Lancar' LIMIT $Lancar");
        mysql_query("UPDATE hasil_prediksi SET status_data = 'Data Testing' WHERE status_data = ''");
        echo "<script>alert('Data Training berhasil diupdate!'); document.location.href='partisi.php';</script>\n";
    }
} ?>
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
                    <h2>Data Partisi</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    
                   <?php echo "<form method=POST action=''>
                      <table>
                          <tr>
                              <td>Set Data Training (Semua Data)</td>        
                              <td> : <input name='data' type='text' style='width:30px'> %</td>
                              <td colspan=2>
                                  <input type=submit class='btn btn-success' name=submit value=Proses>
                              </td>
                          </tr>

                      </table>
                      </form>"; ?>
                      </br>
                     <?php echo"
                     <div class='table-responsive'>
                      <table class='table table-striped jambo_table bulk_action'>
                      <thead>
                        <tr class='headings'>
                          <th>Status Data  ($total_data Data)</th>
                          <th>Data Training ($data_training_total Data)</th>
                          <th>Data Testing ($data_testing_total Data)</th>
                        </tr>
                      </thead>
                        
                        
                      <tbody>
                     
                        <tr class='even pointer'>
                          <td><b>Lancar ($status_data_lancar Data)</b></td>
                          <td><b>$status_data_training_lancar</b></td>
                          <td><b>$status_data_testing_lancar</b></td>
                        </tr>

                        <tr class='odd pointer'>
                          <td><b>Padat ($status_data_padat Data)</b></td>
                          <td><b>$status_data_training_padat</b></td>
                          <td><b>$status_data_testing_padat</b></td>
                        </tr>

                       <tr class='even pointer'>
                          <td><b>Macet ($status_data_macet Data)</b></td>
                          <td><b>$status_data_training_macet</b></td>
                          <td><b>$status_data_testing_macet</b></td>
                        </tr>
                         
                      </tbody>
                    </table>
                    " ?>
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

