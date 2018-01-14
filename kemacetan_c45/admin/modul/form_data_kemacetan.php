<?php
echo "
<form method=POST action='./aksi.php?module=data_kemacetan&act=input'>
	<table>
        <tr>
        <td colspan=2><b><center>Input Data kemacetan</center></b></td>
        </tr>";

        include "form_kemacetan.php";

        echo "<tr>
        <td><b>kondisi<b></td>        
        <td>: 
            <select name='kondisi' type='text'>
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
            <option value='Data Training'>Data Training</option>
            <option value='Data Testing'>Data Testing</option>
            </select>
        </td>
        </tr>

		<tr>
        <td colspan=2>
		<input type=submit value=Input>
		</td>
        </tr>
    </table>
</form>";
?>