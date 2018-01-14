<?php
include "../../connections/koneksi.php";
echo "<h2>C45 &#187; Pohon Keputusan</h2>";
// include "menu_c45.php";
echo " <p>Opsi: <a href=./aksi.php?module=c45&act=hapus_pohon_keputusan>Hapus Semua Data</a></p>";
echo "<font face='Courier New' size='2'>";
echo "<h3><b>Pohon Keputusan: <br></b></h3>";
function get_subfolder($idparent, $spasi){
    // if ($_SESSION[leveluser]=='admin'){
        $result = mysql_query("select * from pohon_keputusan_c45 where id_parent= '$idparent'");
    // }
    while($row=mysql_fetch_row($result)){
        for($i=1;$i<=$spasi;$i++){
            echo "|&nbsp;&nbsp;";
        }
        if ($row[8] === 'Lancar') {
            $keputusan = "<font color=green>$row[8]</font>";
        } elseif ($row[8] === 'Padat') {
            $keputusan = "<font color=blue>$row[8]</font>";
        } elseif ($row[8] === 'Macet') {
            $keputusan = "<font color=red>$row[8]</font>";
        } elseif ($row[8] === 'Tidak Bisa Lewat') {
            $keputusan = "<font color=purple>$row[8]</font>";
        } elseif ($row[8] === '?') {
            $keputusan = "<font color=black>$row[8]</font>";
        } else {
            $keputusan = "<b>$row[8]</b>";
        }
        echo "<font color=red>$row[1]</font> = $row[2] (Lancar = $row[4], Padat = $row[5], Macet = $row[6], Tidak bisa dilewati = $row[7]) : <b>$keputusan</b><br>";

        /*panggil dirinya sendiri*/
        get_subfolder($row[0], $spasi + 1);
    }
}

    get_subfolder('0', 0);
echo "<hr>";

echo "<h3><b>Chart Pohon Keputusan <br></b></h3>";
include "tree_graph/index.php";
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