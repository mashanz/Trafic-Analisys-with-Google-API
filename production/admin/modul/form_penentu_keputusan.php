<?php
echo "
<form method=POST action='modul/function/penentuKeputusan.php'>
	<table>
        <tr>
        <td colspan=2><b><center>Input Data</center></b></td>
        </tr>
";
include "form_kemacetan.html";
echo "
		<tr>
        <td colspan=2>
		<input type=submit value=Input>
		</td>
        </tr>
    </table>
</form>";