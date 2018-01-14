<?php
switch($_GET[act]){
    default:
        echo "<h2>Data Kemacetan</h2>";
        include "menu_data_kemacetan.php";
        include "form_data_kemacetan.php";
        echo " <p>Opsi: <a href=./aksi.php?module=data_kemacetan&act=hapus_semua_data_kemacetan>Hapus Semua Data</a></p>";
        include "upload_csv.php";

        $per_page = 100;         // number of results to show per page
    if ($_SESSION[leveluser]=='admin'){
        $result=mysql_query("SELECT * FROM data_kemacetan");
    }
        $total_results = mysql_num_rows($result);
        $total_pages = ceil($total_results / $per_page);//total pages we going to have
        $warna1 = '#FFFFFF';
        $warna2 = '#CCFFFF';
        $warna  = $warna1; 

        //-------------if page is setcheck------------------//
        if (isset($_GET['page'])) {
            $show_page = $_GET['page'];             //it will telles the current page
            if ($show_page > 0 && $show_page <= $total_pages) {
                $start = ($show_page - 1) * $per_page;
                $end = $start + $per_page;
            } else {
                // error - show first set of results
                $start = 0;              
                $end = $per_page;
            }
        } else {
            // if page isn't set, show first set of results
            $start = 0;
            $end = $per_page;
        }
        // display pagination
        $page = intval($_GET['page']);

        $tpages=$total_pages;
        if ($page <= 0)
            $page = 1;

        $reload = $_SERVER['PHP_SELF'] . "?module=data_kemacetan&act=data_kemacetan&tpages=" . $tpages;
        if ($total_pages > 1) {
            echo paginate($reload, $show_page, $total_pages);
        }


        echo "<table bgcolor='#00CCFF' border='1' cellspacing='0' cellspading='0'>
            <tr>
                <th>No</th>
                <th>id data</th>
                <th>hari</th>
                <th>jam</th>
                <th>lokasi</th>
                <th>kondisi</th>
                <th>Opsi</th>
           </tr>";

         for ($i = $start; $i < $end; $i++) {
                                // make sure that PHP doesn't try to show results that don't exist
                                if ($i == $total_results) {
                                    break;
                                }
                $num = $i +1;
        echo '<tr bgcolor=' . $warna . '>
              <td>'.$num.'</td>
              <td>' . mysql_result($result, $i, 'id_data') . '</td>
              <td>' . mysql_result($result, $i, 'hari') . '</td>
              <td>' . mysql_result($result, $i, 'jam') . '</td>
              <td>' . mysql_result($result, $i, 'lokasi') . '</td>
              <td>' . mysql_result($result, $i, 'kondisi') . '</td>
              <td><a href=?module=data_kemacetan&act=edit_data_kemacetan&id=' . mysql_result($result, $i, 'id').'>Edit</a> |
                       <a href=./aksi.php?module=data_kemacetan&act=hapus_data_kemacetan&id=' . mysql_result($result, $i, 'id').'>Hapus</a>
                       </td>
                   </tr>';
        $no++;
        }
        echo"</table>";
    break;
    
    case "edit_data_kemacetan";
        echo "<h2>Data Kemacetan &#187; Edit Data Kemacetan</h2>";
        include "menu_data_kemacetan.php";
        include "form_edit_data_kemacetan.php";
    break;
    
	case "partisi_data";
		include "partisi_data.php";
    break;
}