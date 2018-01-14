/* Script JavaScript */
window.addEvent('domready',function(){	
/*
var info = $('info');
	info.tween('height', 600);
	
	$('info').addEvent('click', function(){
		$('txt_username').focus();
		info.tween('height', 1);
		$('showinfo').innerHTML = 'Tampilkan Informasi';
		$('showinfo').set('title','Klik untuk Menampilkan Informasi');
		return false;
	}); 
	$('showinfo').addEvent('click', function(){
		if	($('showinfo').innerHTML == 'Sembunyikan Informasi') {
			$('txt_username').focus();
			info.tween('height', 1);
			$('showinfo').innerHTML = 'Tampilkan Informasi';
			$('showinfo').set('title','Klik untuk Menampilkan Informasi');
			return false;
		}
		else{
			info.tween('height', 600);
			$('showinfo').innerHTML = 'Sembunyikan Informasi';
			$('showinfo').set('title','Klik untuk Sembunyikan Informasi');
			return false;
		}
	}); 
*/	
	$('formLogin').addEvent('submit', function(e) {	
		if (($('txt_username').value != '')&&($('txt_password').value != '')){
			$('txt_password').value = MD5($('txt_password').value+$('postForm').value);
		}
	});
	$('btnReset').addEvent('click', function(e) {	
		$('txt_username').focus();
	});
});