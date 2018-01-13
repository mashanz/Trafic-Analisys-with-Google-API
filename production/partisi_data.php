<?php
echo "<h2>Data Training &#187; Partisi Data</h2>";
// include "menu_data_training.php";

function countData($atribut, $kondisi)
{
    $sql = mysql_query("SELECT count(id) as id FROM data_kemacetan where $atribut AND kondisi = '$kondisi'");
    while($row = mysql_fetch_array($sql)) {
        $count = "$row[id]";
    }
    return $count;
}
function countPersentase($banyak, $atribut)
{
$sqlTotal = mysql_query("SELECT count(id) as id FROM data_kemacetan where $atribut");
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

echo "<form method=POST action=''>
<table>
    <tr>
        <td>Set Data Training (Semua Data)</td>        
        <td> : <input name='data' type='text' style='width:30px'> %</td>
        <td colspan=2>
            <input type=submit name=submit value=Proses>
        </td>
    </tr>

</table>
</form>";

if (isset($_POST['submit'])) {
    if ($_POST['data'] > 100) {
        echo "<p>Data Kurusng dimasukkan harus lebih kecil dari 100!<p>";
    } else {
        $Macet = countPersentase($_POST['data'], "kondisi = 'Macet'");
        $Padat = countPersentase($_POST['data'], "kondisi = 'Padat'");
        $Lancar = countPersentase($_POST['data'], "kondisi = 'Lancar'");

        mysql_query("TRUNCATE data_keputusan");
        mysql_query("TRUNCATE data_keputusan_kinerja");
        mysql_query("TRUNCATE iterasi_id3");
        mysql_query("TRUNCATE pohon_keputusan_id3");
        mysql_query("TRUNCATE rule_id3");
        
        mysql_query("UPDATE data_kemacetan SET status_data = ''");
        mysql_query("UPDATE data_kemacetan SET status_data = 'Data Training' WHERE kondisi = 'Macet' LIMIT $Macet");
        mysql_query("UPDATE data_kemacetan SET status_data = 'Data Training' WHERE kondisi = 'Padat' LIMIT $Padat");
        mysql_query("UPDATE data_kemacetan SET status_data = 'Data Training' WHERE kondisi = 'Lancar' LIMIT $Lancar");
        mysql_query("UPDATE data_kemacetan SET status_data = 'Data Testing' WHERE status_data = ''");
        echo "<script>alert('Data Training berhasil diupdate!'); document.location.href='media.php?module=data_kemacetan&act=partisi_data';</script>\n";
    }
}

echo "<table>
        <tr>
            <th><b>Status Data ($total_data Data)</b></th>
            <th>Data Training ($data_training_total Data)</th>
            <th>Data Testing ($data_testing_total Data)</th>
        </tr>
        

        <tr>
            <td><b>Lancar ($status_data_lancar Data)</b></td>
            <td><b>$status_data_training_lancar</b></td>
            <td><b>$status_data_testing_lancar</b></td>
        </tr>

        <tr>
            <td><b>Padat ($status_data_padat Data)</b></td>
            <td><b>$status_data_training_padat</b></td>
            <td><b>$status_data_testing_padat</b></td>
        </tr>

        <tr>
            <td><b>Macet ($status_data_macet Data)</b></td>
            <td><b>$status_data_training_macet</b></td>
            <td><b>$status_data_testing_macet</b></td>
        </tr>
      </table>";