<html>
<?php
$query = mysql_query("SELECT * FROM data_kemacetan WHERE id='$_GET[id]'");
$data = mysql_fetch_array($query);

function viewForm($atribut, $atributx) {
    $query = mysql_query("SELECT * FROM data_kemacetan WHERE id='$_GET[id]'");
    $data = mysql_fetch_array($query);
    echo "<tr>
        <td>$atributx</td>        
        <td>: 
            <input name='$atribut' value='$data[$atribut]' type='text'>
        </td>
        </tr>";
}

function viewFormSelect($atribut, $atributx) {
    $query = mysql_query("SELECT * FROM data_kemacetan WHERE id='$_GET[id]'");
    $data = mysql_fetch_array($query);
    echo "<tr>
        <td>$atributx</td>        
        <td>: 
            <select name='$atribut' type='text'>
            <option value='$data[$atribut]' selected='selected'>$data[$atribut]</option>";
            $sqlData1 = mysql_query("SELECT * FROM atribut where atribut = '$atribut'");
            while($rowData1 = mysql_fetch_array($sqlData1)) {
                echo "<option value='$rowData1[nilai_atribut]'>$rowData1[nilai_atribut]"; 
            }
            echo "</option>
            </select>
        </td>
        </tr>";
}

?>
<form method=POST action='./aksi.php?module=data_kemacetan&act=update_data_kemacetan'>
      <input type=hidden name=id value=<?php echo "$data[id]"; ?>>
      <table>
        <tr>
        <td colspan=2><b><center>Edit Data Kemacetan</center></b></td>
        </tr>
        
        <?php
        viewForm('id_data', 'id_data');
        viewFormSelect('lancar', 'lancar');
        viewFormSelect('padat', 'padat');
        viewFormSelect('macet', 'macet');
?>
        
        <tr>
        <td><b>Class<b></td>        
        <td>: 
            <select name='class' type='text'>
            <option value='<?php echo "$data[class]"; ?>' selected='selected'><?php echo "$data[class]"; ?></option>
            <option value='Lancar'>lancar</option>
            <option value='Padat'>padat</option>
            <option value='Macet'>macet</option>
            </select>
        </td>
        </tr>

        <tr>
        <td><b>Status Data<b></td>        
        <td>: 
            <select name='status_data' type='text'>
            <option value='<?php echo "$data[status_data]"; ?>' selected='selected'><?php echo "$data[status_data]"; ?></option>
            <option value='Data Training'>Data Training</option>
            <option value='Data Testing'>Data Testing</option>
            </select>
        </td>
        </tr>

        <tr>
        <td colspan=2>
        <input type='submit' value='Simpan'><input type='button' value='Batal' onclick='self.history.back()'>
        </td>
        </tr>
  </table>
  </form>
</html>