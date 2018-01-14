<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Login</title>
    <link  href="icon.png" rel="shortcut icon" type="image/png" />
    <link  href="CSS_login.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="mootools-1.2.1-core.js.php"></script>
    <link  href="icon.png" rel="shortcut icon" type="image/png" />
    <script type="text/javascript" src="ardizorro-md5.js.php"></script>
    <script type="text/javascript" src="login.js"></script>
</head>
<body>
    <!--	<div id="info" title="Klik untuk Sembunyikan Informasi"><a id="showinfo" title="Klik untuk Menampilkan Informasi" href="#">Sembunyikan Informasi</a></div> -->
    <form method="POST" action="cek_login.php">
    <table class="tb" cellpadding="2px" cellspacing="2px" >
        <tr>
        <td class="header" colspan="2" bgcolor="#2fa1e5" nowrap="nowrap"><span class="light">Identifikasi Jenis Kemacetan <br> Menggunakan Pohon Keputusan C4.5</span></td>
        </tr>

        <tr>
        <td class="caption" colspan="2" align="center"><span class="th">Login Administrator</span></td>
        </tr>
        
        <tr>
        <td width="125" nowrap="nowrap"><div class="right">Username</div></td>
        <td><input style="cursor:help" title="Contoh: admin" type="text" name="username" id="username" size="25" maxlength="15"/></td>
        </tr>			
        <tr>

        <td nowrap="nowrap"><div class="right">Password</div></td>
        <td><input type="password" name="password" id="password" size="25" maxlength="32"/></td>
        </tr>			

        <tr>
        <td height="50">&nbsp;</td>
        <td>
        <input type="hidden" id="postForm" name="postForm" maxlength="32" value="387bcfe8cb701b58a39196a6abb5f57a"/>									
        <button type="reset" id="btnReset" name="btnReset"><img src="btnReset.png"/><span class="txt">Reset</span></button>
        <button type="submit" id="btnLogin" name="btnLogin" value="Login"><img src="btnLogin.png"/><span class="txt">Login</span></button>
        </td>
        </tr>
        </table>		
    </form>
    <br>
    <center><b>Username: admin, Password: admin1234</b></center>

    <div id="footer">
        <p>Copyright&copy; 2017 by: Suhartina Hajrahnur || 1103144092</p>
        <p><b>---</b> </p>
    </div>
</body>
</html>