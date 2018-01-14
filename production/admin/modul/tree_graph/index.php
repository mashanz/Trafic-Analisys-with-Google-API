
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
	<title>jOrgChart - A jQuery OrgChart Plugin</title>
	<link rel="stylesheet" href="modul/tree_graph/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="modul/tree_graph/css/jquery.jOrgChart.css"/>
	<link rel="stylesheet" href="modul/tree_graph/css/custom.css"/>
	<link href="modul/tree_graph/css/prettify.css" type="text/css" rel="stylesheet" />

	<script type="text/javascript" src="modul/tree_graph/prettify.js"></script>
	<script type="text/javascript" src="modul/tree_graph/jquery-2.0.2.js"></script>
	<script type="text/javascript" src="modul/tree_graph/jquery-ui.min.js"></script>
	<script src="modul/tree_graph/jquery.jOrgChart.js"></script>

	<script>
	jQuery(document).ready(function() {
		$("#org").jOrgChart({
			chartElement : '#chart',
			dragAndDrop  : true
		});
	});
	</script>


<ul id="org" style="display:none"></ul>
<script>
	/*
	$("#org").append("<li id='parent'>Hasil Glukosa Sewaktu <ul></ul></li>");
	$("#parent ul").append("<li id='1' class='ya'>Diabetes <p>Ya</p></li>");
	$("#parent ul").append("<li id='2' class='tidak'>Normal <p>Tidak</p></li>");
	$("#parent ul").append("<li id='3'> Pradiabetes <p></p></li>");
	$("#3 p").text("riwayat diabetes keluarga");
	$("#3").append("<ul></ul>");
	$("#3 ul").append("<li id='4'>Ada <p></p></li>");
	$("#3 ul").append("<li id='5' class='tidak'>Tidak Ada <p>Tidak</p></li>");
	$("#4 p").text("riwayat diabetes pasien");
	$("#4").append("<ul></ul>");
	$("#4 ul").append("<li id='6' class='ya'>Diabetes <p>Ya</p></li>");
	$("#4 ul").append("<li id='7' class='tidak'>Nondiabetes <p>Tidak</p></li>");
	
	var id = 8;
	if($("#"+id+" p").length) alert('sudah diisi');
	else alert('belum diisi');
	/**/
	
	function draw_tree(id, parent_id, atribut, nilai_atribut, keputusan){
		if(parent_id == 0){
			if($("org li").length){
				if(keputusan == '?') $("#parent ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
				else $("#parent ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
			}else{
				$("#org").append("<li id='parent'>"+atribut+"<ul></ul></li>");
				
				if(keputusan == '?') $("#parent ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
				else $("#parent ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
			}
		}else{
			if($("#"+parent_id+" p").length){
				if(keputusan == "?") $("#"+parent_id+" ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
				else $("#"+parent_id+" ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
			}else{
				$("#"+parent_id).append("<p>"+atribut+"</p> <ul></ul>");
				
				if(keputusan == "?") $("#"+parent_id+" ul").append("<li id='"+id+"'>"+nilai_atribut+"</li>");
				else $("#"+parent_id+" ul").append("<li id='"+id+"' class='"+keputusan+"'>"+nilai_atribut+" <p>"+keputusan+"</p></li>");
			}
		}
	}
	
	/*
	var ya = "org";
	$("#"+ya+" ul").append("<li>yaaa</li>");
	$("#"+ya+" ul").append("<li>yaaa2</li>");
	*/
	
<?php
mysql_connect("localhost","root","");
mysql_select_db("prediksi-katarak");

$query = mysql_query("SELECT * FROM  pohon_keputusan_c45");
while($row = mysql_fetch_assoc($query)){
	echo "draw_tree(".$row['id'].",".$row['id_parent'].",'".str_replace("-"," ",str_replace("_"," ",$row['atribut']))."','".str_replace("-"," ",str_replace("_"," ",$row['nilai_atribut']))."','".str_replace("-"," ",str_replace("_"," ",$row['keputusan']))."');\n";
}
?>
</script>
	
<div id="chart" class="orgChart"></div>

    
<script>
	jQuery(document).ready(function() {
		
		/* Custom jQuery for the example */
		$("#show-list").click(function(e){
			e.preventDefault();
			
			$('#list-html').toggle('fast', function(){
				if($(this).is(':visible')){
					$('#show-list').text('Hide underlying list.');
					$(".topbar").fadeTo('fast',0.9);
				}else{
					$('#show-list').text('Show underlying list.');
					$(".topbar").fadeTo('fast',1);                  
				}
			});
		});
		
		$('#list-html').text($('#org').html());
		
		$("#org").bind("DOMSubtreeModified", function() {
			$('#list-html').text('');
			
			$('#list-html').text($('#org').html());
		});
	});
</script>

