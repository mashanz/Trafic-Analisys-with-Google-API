<?php
function viewForm($atribut, $atributx) {
    echo "<tr>
        <td>$atributx</td>        
        <td>: 
            <input name='$atribut' type='text'>
        </td>
        </tr>";
}

function viewFormSelect($atribut, $atributx) {
    echo "<tr>
        <td>$atributx</td>        
        <td>: 
            <select name='$atribut' type='text'>";
            $sqlData1 = mysql_query("SELECT * FROM atribut where atribut = '$atribut'");
            while($rowData1 = mysql_fetch_array($sqlData1)) {
                echo "<option value='$rowData1[nilai_atribut]'>$rowData1[nilai_atribut]"; 
            }
            echo "</option>
            </select>
        </td>
        </tr>";
}

        viewForm('id_data', 'id_data');
        viewFormSelect('hari', 'hari');
        viewFormSelect('jam', 'jam');
        viewFormSelect('lokasi', 'lokasi');

?>
