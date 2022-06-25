<?php
//1. Abre el archivo /conexion_inicial.txt
	$path = "conexion_inicial.txt";
	if(file_exists($path)){
		$file = fopen($path,"r");
		$line=fgets($file);
		fclose($file);
    	$config=explode("<>",$line);
	}

	$archivo_path = "conexion_inicial.txt";
	if(file_exists($archivo_path)){
		//echo 'si';
		$file = fopen($archivo_path,"r");
		$line=fgets($file);
		fclose($file);	
	    $config=explode("<>",$line);
	    $conf_loc=explode("~",$config[0]);
	    $conf_ext=explode("~",$config[1]);
	    $conf_tk=explode("~",$config[2]);
	    $ruta_jar=$config[3];
	    $ruta_pte_imp = $config[4];
	    $impresora = $config[5];
	    $intervalo_imp = $config[6];
	    $retardo_sync = $config[7];
	    $puerto_sync = $config[8];
	    $puerto_imp = $config[9];
	}
//1.1. Implementacion Oscar 2020 para listar rutas de ticket e impresoras
	$impresoras = '';
	$arr_imp = explode("____", $config[5]); 
	for ($i=0; $i < sizeof($arr_imp)-1; $i++) { 
		$sub_array_imp = explode("~~", $arr_imp[$i]);
		$impresoras .= '<tr id="imp_'.$i.'">';
		$impresoras .= '<td width="50%">'.$sub_array_imp[0].'</td>';
		$impresoras .= '<td width="40%">'.$sub_array_imp[1].'</td>';
		$impresoras .= '<td width="10%" align="center"><button onclick="quita_impresora('.$i.')">X</button></td>';
		$impresoras .= '</tr>';
	}

?>
<!DOCTYPE html>
<!-- 2. Estilos CSS -->
<style type="text/css">
	#global{width:100%;height: 100%;position: absolute; top:0;left:0; background-image: url("img/especiales/fondo_config.jpg");}
	.entrada{padding: 12px;border-radius: 15px;width: 105%;}
	.descripcion{color:white;}
	#impresoras{background: white;}
	th{background: red;padding: 10px; color: white;}
	#emergente{position: fixed; background: rgba(0,0,0,.5);display: none; width: 100%;height: 100%;top:0; left: 0;}
</style>
<html>
<head>
	<title>Configuración Inicial</title>

	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
</head>
<body>
<!-- 3. Formulario de configuracion -->
	<div id="global">
	<center><br><br>
	<table style="position:absolute;top:10%;left:1%;">
		<tr>
			<td align="center"><b class="descripcion">Host Local:</b></td>
			<td>
				<input type="text" id="host_loc" class="entrada" value="<?php echo base64_decode($conf_loc[0]);?>" placeholder="localhost/ www.dominio...">
			</td>
		</tr>
		<tr>
			<td align="center"><b class="descripcion">Ruta Local:</b></td>
			<td>
				<input type="text" id="ruta_loc" class="entrada" value="<?php echo base64_decode($conf_loc[1]);?>" placeholder="carpeta(s) del sistema">
			</td>
		</tr>
		<tr>
			<td align="center"><b class="descripcion">Nombre BD Local:</b></td>
			<td>
				<input type="text" id="nombre_bd_loc" value="<?php echo base64_decode($conf_loc[2]);?>" class="entrada">
			</td>
		</tr>
		<tr>
			<td align="center"><b class="descripcion">Usuario BD Local:</b></td>
			<td>
				<input type="text" id="usuario_bd_loc" value="<?php echo base64_decode($conf_loc[3]);?>" class="entrada">
			</td>
		</tr>
		<tr>
			<td align="center"><b class="descripcion">Password BD Local:</b></td>
			<td>
				<input type="text" id="pass_bd_loc" value="<?php echo base64_decode($conf_loc[4]);?>" class="entrada">
			</td>
		</tr>
	</table>
<br><br>
	<table style="position:absolute;top:10%;left:30%;">
		<tr>
			<td align="center"><b class="descripcion">Host Linea:</td>
			<td>
				<input type="text" id="host_lin" class="entrada" value="<?php echo base64_decode($conf_ext[0]);?>" placeholder="localhost/ www.dominio...">
			</td>
		</tr>
		<tr>
			<td align="center"><b class="descripcion">Ruta Linea:</b></td>
			<td>
				<input type="text" id="ruta_lin" class="entrada" value="<?php echo base64_decode($conf_ext[1]);?>" placeholder="carpeta(s) del sistema">
			</td>
		</tr>
		<tr>
			<td><b class="descripcion">Nombre de la BD Linea: </b></td>
			<td>
				<input type="text" id="nombre_bd_lin" value="<?php echo base64_decode($conf_ext[2]);?>" class="entrada">
			</td>
		</tr>
		<tr>
			<td><b class="descripcion">Usuario BD Linea: </b></td>
			<td>
				<input type="text" id="usuario_bd_lin" value="<?php echo base64_decode($conf_ext[3]);?>" class="entrada">
			</td>
		</tr>
		<tr>
			<td><b class="descripcion">Password BD Linea: </b></td>
			<td>
				<input type="password" id="pass_bd_lin" value="<?php echo base64_decode($conf_ext[4]);?>" class="entrada">
			</td>
		</tr>
	</table>
<br><br>
	<table style="position:absolute;top:10%;left:65%;">
		<tr>
			<td><b class="descripcion">Ruta origen ticket:</b></td>
			<td><input type="text" id="ruta_ticket_origen" value="<?php echo $conf_tk[0];?>" class="entrada"></td>
		</tr>
		<tr>
			<td><b class="descripcion">Ruta destino ticket: </b></td>
			<td><input type="text" id="ruta_ticket_destino" value="<?php echo $conf_tk[1];?>" class="entrada"></td>
		</tr>
		<tr>
			<td><b class="descripcion">Ruta de archivo jar: </b></td>
			<td><input type="text" id="ruta_archivo_jar" value="<?php echo $ruta_jar;?>" class="entrada"></td>
		</tr>

		<tr>
			<td><b class="descripcion">Comando impresion: </b></td>
			<td><textarea id="ruta_puente_impresion" class="entrada"><?php echo $ruta_pte_imp;?></textarea></td>
		</tr>

		<tr>
			<td><b class="descripcion">Intervalo Impresion <br>(milesimas seg): </b></td>
			<td><input type="number" id="intervalo_imp" value="<?php echo $intervalo_imp;?>" class="entrada"></td>
		</tr>	


		<tr>
			<td><b class="descripcion">Retraso sincronizacion <br>(milesimas seg): </b></td>
			<td><input type="number" id="retraso_sinc" value="<?php echo $retardo_sync;?>" class="entrada"></td>
		</tr>	

		<tr>
			<td><b class="descripcion">Puerto Sincronizacion: </b></td>
			<td><input type="number" id="puerto_sinc" value="<?php echo $puerto_sync;?>" class="entrada" placeholder="recomendado => 1335"></td>
		</tr>	

		<tr>
			<td><b class="descripcion">Puerto Impresion: </b></td>
			<td><input type="number" id="puerto_imp" value="<?php echo $puerto_imp;?>" class="entrada" placeholder="recomendado => 1336"></td>
		</tr>	
		<!--<tr>
			<td colspan="2" align="center"><br><br>
				<button onclick="genera_config();">Crear Configuracion</button>
			</td>
		</tr>-->
	</table>
<!-- 3.1. Implementacion de Oscar 2020 para el sistema de multiimpresion -->
	<table id="impresoras" style="position:absolute;top:60%; width: 500px; left:5%;">
		<tr>
			<th>Ruta de Tickets</th><th>Nombre Impresora</td><th></th>
		</tr>
		<?php echo $impresoras;?>

		<tr>
			<td colspan="2" align="center">
				<button onclick="form_impr();">Agregar Impresora</button>
			</td>
		</tr>
	</table>
		<button onclick="genera_config();" style="position:absolute;top:90%;right:20%;padding:10px;">
			<b>
				Crear Configuracion
			</b>
		</button>	
	</center>
	</div>
	<div id="emergente"></div>
</body>
</html>
<!-- 4. Funciones JavaScript -->
<script type="text/javascript">
 
 //4.1. Funcion para generar los archivos de configuracion por medio del archivo code/ajax/conf_inicial.php
	function genera_config(){
//recolectamos los datos de la configuración local
		var h_l=$("#host_loc").val();
		if(h_l.length<=0){
			alert("El campo de Host no puede ir vacío!!!");
			$("#host_loc").focus();
			return false;
		}
		var r_l=$("#ruta_loc").val();
		if(r_l.length<=0){
			alert("El campo de Ruta Local no puede ir vacío!!!");
			$("#ruta_loc").focus();
			return false;
		}
		var n_bd_l=$("#nombre_bd_loc").val();
		if(n_bd_l.length<=0){
			alert("El campo de Nombre de Base de datos no puede ir vacío!!!");
			$("#nombre_bd_loc").focus();
			return false;
		}
		var u_l=$("#usuario_bd_loc").val();
		if(u_l.length<=0){
			alert("El usuario de Base de Datos no puede ir vacío!!!");
			$("#usuario_bd_loc").focus();
			return false;
		}
		var p_l=$("#pass_bd_loc").val();

//recolectamos los datos de la configuración de bd linea
		var h_lin=$("#host_lin").val();
		if(h_lin.length<=0){
			alert("El campo de Host no puede ir vacío!!!");
			$("#host_lin").focus();
			return false;
		}
		var r_lin=$("#ruta_lin").val();
		if(r_lin.length<=0){
			alert("El campo de Ruta Local no puede ir vacío!!!");
			$("#ruta_lin").focus();
			return false;
		}
		var n_bd_lin=$("#nombre_bd_lin").val();
		if(n_bd_lin.length<=0){
			alert("El campo de Nombre de Base de datos no puede ir vacío!!!");
			$("#nombre_bd_lin").focus();
			return false;
		}
		var u_lin=$("#usuario_bd_lin").val();
		if(u_lin.length<=0){
			alert("El usuario de Base de Datos no puede ir vacío!!!");
			$("#usuario_bd_lin").focus();
			return false;
		}
		var p_lin=$("#pass_bd_lin").val();

		var ru_t_or=$("#ruta_ticket_origen").val();
		if(ru_t_or.length<=0){
			alert("La ruta de origen del ticket no puede ir vacía!!!");
			$("#ruta_ticket_origen").focus();
			return false;
		} 
		var ru_t_des=$("#ruta_ticket_destino").val();
		if(ru_t_des.length<=0){
			alert("La ruta de destino del ticket no puede ir vacía!!!");
			$("#ruta_ticket_destino").focus();
			return false;
		} 
		var ru_jar=$("#ruta_archivo_jar").val();
		if(ru_jar.length<=0){
			alert("La ruta del archivo jar no puede ir vacía!!!");
			$("#ruta_archivo_jar").focus();
			return false;
		} 

		var pte_imp = $("#ruta_puente_impresion").val();
		
		var int_imp = $("#intervalo_imp").val();


		var imp = cadena_impresoras();

		var retraso_sincronizacion = $("#retraso_sinc").val(); 
		if(retraso_sincronizacion.length<=0){
			alert("El retraso de ejecucion del sistema de sincronización no puede ir vacio!!!");
			$("#retraso_sinc").focus();
			return false;
		} 

		var puerto_sincronizacion = $("#puerto_sinc").val(); 
		if(puerto_sincronizacion.length<=0){
			alert("El puerto del sistema de sincronizacion no puede ir vacio!!!");
			$("#puerto_sinc").focus();
			return false;
		} 

		var puerto_impresion = $("#puerto_imp").val(); 
		if(puerto_impresion.length<=0){
			alert("El puerto del sistema de impresion no puede ir vacio!!!");
			$("#puerto_imp").focus();
			return false;
		} 
	//enviamos datos por ajax
		$.ajax({
			type:'post',
			url:'code/ajax/conf_inicial.php',
			cache:false,
			data:{
				host_local:h_l,
				ruta_local:r_l,
				nombre_local:n_bd_l,
				usuario_local:u_l,
				pass_local:p_l,
				host_linea:h_lin,
				ruta_linea:r_lin,
				nombre_linea:n_bd_lin,
				usuario_linea:u_lin,
				pass_linea:p_lin,
				ru_or : ru_t_or,
				ru_des : ru_t_des,
				archivo_jar:ru_jar,
				impresion : pte_imp,
				impresora : imp,
				intervalo_impresion : int_imp,
				retraso_sis_sinc : retraso_sincronizacion,
				puerto_sis_sinc : puerto_sincronizacion,
				puerto_sis_imp : puerto_impresion
			},
			success:function(dat){
				if(dat!='ok'){
					alert("Error, actualice la pantalla y vuelva a intentar!!!"+dat);
					return false;
				}else{
					alert("La configuración fue guardada exitosamente!!!");
					location.href='index.php?';
				}
			}
		});
	}

//4.2. Funcion para eliminar impresora
	function quita_impresora(id){
		if(!confirm("Realmente desea eliminar la impresora?")){
			return false;
		}
		$("#imp_" + id).remove();
	}

//4.3. Funcion para formulario de impresora
	function form_impr(){
		var cont_emerg = '<table style="position:absolute;width:50%;left:25%;top:25%; background:white;"><tr><th> Ruta de ticket</th><th> Nombre de Impresora </th><tr>';
		cont_emerg += '<tr><td><textarea id="valor_ruta" style="width:100%;"></textarea></td>';
		cont_emerg += '<td><textarea id="valor_impresora" style="width:100%;"></textarea></td></tr>';
		cont_emerg += '<tr><td colspan="2" align="center"><button onclick="agrega_impresora();">Agregar</td></tr>';
		cont_emerg += '</table>';
		$("#emergente").html(cont_emerg);
		$("#emergente").css("display", "block");
	}

//4.4. Funcion para agregar impresora
	function agrega_impresora(ruta, impresion){
		var cont = $("#impresoras tr").length - 1;
		var ruta= $("#valor_ruta").val();
		var impresora= $("#valor_impresora").val();
		$('#impresoras tr:last').before('<tr id="imp_' + cont + '"><td>' + ruta + '</td><td>' + impresora + '</td><td width="10%" align="center">' +
			'<button onclick="quita_impresora(' + cont + ')">X</button></td></tr>');
		$("#emergente").html("");
		$("#emergente").css("display", "none");
	}

//4.5. Funcion para recopilar info impresiones
	function cadena_impresoras(){
		var cadena = '';
		var cont =0;
		var tabla=document.getElementById('impresoras');
		trs=tabla.getElementsByTagName('tr');
		for(i=0;i<trs.length-2;i++)
		{
			//if(){
				tds=trs[i+1].getElementsByTagName('td');
            	//var objIn=tds[12].getElementsByTagName('input');
				cadena += $(tds[0]).html()+ "~~" + $(tds[1]).html() + "____";
				//cadena += $(tds[1]).html()+ "||";
			//}
		}
		return cadena;
	}

</script>