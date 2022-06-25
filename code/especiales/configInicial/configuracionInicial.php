<?php
	include("../../../conectMin.php");	
	$sql="SELECT CONCAT(fecha,' ',hora) FROM sys_respaldos WHERE realizado=0 LIMIT 1";
	$eje=mysql_query($sql)or die("Error al consultar fecha del respaldo!!!\n\n".$sql."\n\n".mysql_error());
	$r=mysql_fetch_row($eje);
	$tiempo_respaldo=$r[0];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Configuración inicial del sistema</title>
	<script type="text/javascript" src="../../../js/jquery-1.10.2.min.js"></script>
<!--importaciónd del calendario-->
	<link rel="stylesheet" type="text/css" href="../../../css/gridSW_l.css"/>
	<script type="text/javascript" src="../../../js/calendar.js"></script>
	<script type="text/javascript" src="../../../js/calendar-es.js"></script>
	<script type="text/javascript" src="../../../js/calendar-setup.js"></script>

	<style type="text/css">
		#principal{
			position: absolute;
			background-image: url('../../../img/img_casadelasluces/bg8.jpg');
			top:0;
			width: 100%;
			height:100%;
			left:0;
		}
		.titulo{color:black;padding: 10px;font-size:30px;top:45px;position: relative;left:-20%;}
		.logo{position:absolute;top:0;left:0;z-index: 2;}
		.combo{padding: 10px;font-size:15px;width: 100%;}
		.txt{font-size: 20px;}
		input[type=checkbox]{-ms-transform:scale(2.4);/*IE*/ -moz-transform:scale(2.4);/*FF*/ -webkit-transform: scale(2.4);/*Safari and Chrome*/
		-o-transform: scale(2.4);/*Opera*/}
		td{padding: 3px;}
		.btn{background:transparent;border-radius:10px;}
		.btn:hover{background: rgba(0,0,0,.5);color: white;}
		#emergente{position: absolute;width: 100%;height: 100%;top:0;background: rgba(0,0,0,.8);z-index: 5;display: none;}
		#cont_emergente{position: relative;border:1px solid white; width: 80%;left: 10%;height:40%;top:25%;border-radius: 15px;background: rgba(225,0,0,.5)}
	</style>
</head>
<body>
	<div id="principal">
		
		<div id="emergente" style="display:bloc;">
			<p id="cont_emergente" align="center">
				<b style="color:white;font-size:40px;">Esta acción agrupará los movimientos, ventas y devoluciones de 3 años hacia atrás<br>Realmente desea continuar??</b>
				<table>
					<tr>
						<!--<td width="50%">
							<button>Continuar</button>
						</td>
						<td width="50%">
							<button>Cancelar</button>
						</td>-->
					</tr>
				</table>
			</p>
		</div>
		
		<p class="titulo" align="center"><b>Restauración y Configuración inicial del sistema</b></p>
		<a href="">
			<img src="../../../img/img_casadelasluces/logocasadelasluces-easy.png" class="logo" width="150px"><br><br>
		</a>
		<table style="width:100%;height:100%;position:absolute;left:0;background:rgba(225,225,0,.2);color:black;top:0;" border="0">
			<tr>
				<td align="right"><br>
					<b class="txt">Seleccione el tipo de Base de Datos:</b>
				</td>
				<td align="left" width="15%"><br>
					<select class="combo" id="tipo_bd">
						<!--<option value="0">--Seleccionar--</option>
						<option value="1">Nueva BD</option>-->
						<option value="2">Restauración de BD</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">
					<b class="txt">Seleccione el tipo de sistema:</b>
				</td>
				<td align="left">
					<select class="combo"id="tipo_sys" onchange="cambia_combo(this);">
						<option value="0">--Seleccionar--</option>
						<option value="1">Local</option>
						<option value="2">Línea</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">
					<b class="txt">Seleccione la sucursal:</b>
				</td>
				<td align="left" id="combo_sucs">
					<select class="combo" id="id_suc" onchange="prepara_acciones(this);">
						<option value="0">--Seleccionar--</option>
					</select>
				</td>
			</tr>

			<!--<tr>
				<td align="right">
					<b class="txt">Agrupar movimientos de almacen:</b>	
				</td>
				<td align="center">
					<input type="checkbox" id="agrupa_mov" class="check" disabled>
				</td>
			</tr>-->

			<tr>
				<td align="right">
					<b class="txt">Eliminar movimientos de almacen que no sean de la sucursal:</b>	
				</td>
				<td align="center">
					<input type="checkbox" id="elimina_mov" class="check" disabled>
				</td>
			</tr>

			<!--<tr>
				<td align="right">
					<b class="txt">Agrupar Ventas:</b>	
				</td>
				<td align="center">
					<input type="checkbox" id="agrupa_vtas" class="check" disabled>
				</td>
			</tr>-->

			<tr>
				<td align="right">
					<b class="txt">Eliminar Ventas que no sean de la sucursal:</b>	
				</td>
				<td align="center">
					<input type="checkbox" id="elimina_vtas" class="check" disabled>
				</td>
			</tr>

			<tr>
				<td align="right">
					<b class="txt">Fecha de generación del respaldo:</b>	
				</td>
				<td align="center">
					<input type="text" id="fecha_respaldo" class="combo" style="width:80%;" 
					onfocus="" 
					onclick="alert('Se debe de ser precios en la hora y fecha de generación del respaldo');calendario(this);" value="<?php echo $tiempo_respaldo;?>">
					<!--<input type="file" id="archivo_txt" accept="txt">-->					
				</td>
			</tr>

			<tr>
				<td align="center" colspan="2"><br>
					<b class="txt" style="font-size:25px;">Ingrese usuario y contraseña para la restauración:</b><br><br>
						<input type="text" class="combo" style="width:20%;" placeholder="Usuario" id="usuario"><br><br>
						<input type="password" class="combo" style="width:20%;" placeholder="***Password***" id="contrasena"><br><br>
					<button class="btn" onclick="verificar();">
						<img src="../../../img/especiales/continuar.png" width="50px"><br>
						<b style="color:blue;">Restaurar</b>
					</button>
				</td>
			</tr>
			<tr>
				<td align="right">
					<button class="btn" onfocus="carga_archivo();">
						<img src="../../../img/especiales/mant.png" class="" width="50px"><br>
						<b style="color:blue;">Mantenimiento</b>
					</button>
				</td>
			</tr>
		</table>
	</div>	
</body>
</html>

<script type="text/javascript">
//declaramos las variables globales
	var tipo_database,tipo_sistema,id_sucursal,agrupar_movimientos=0,eliminar_movimientos=0,agrupar_ventas=0,eliminar_ventas=0,fec_rsp,user,pass;

//
	function prepara_acciones(obj){
		var t_db=$("#tipo_bd").val(),t_s=$("#tipo_sys").val();
		if(t_db==0){
			alert("Elija el tipo de Base de Datos!!!");
			$('#id_suc option[value="0"]').attr("selected", true);
			$("#tipo_bd").focus();
			return false;
		}

		if(t_s==0){
			alert("Elija el tipo de sistema!!!");
			$('#id_suc option[value="0"]').attr("selected", true);
			$("#tipo_sys").focus();
			return false;
		}
	//
		if($(obj).val()==1){//si es nueva BD

		}
		if($(obj).val()==1){//si es respaldo de BD

		}
	}

//función que verifica los datos
	function verificar(){
	//tipo de base de datos
		tipo_database=$("#tipo_bd").val();
		if(tipo_database==0){
			$("#tipo_bd").focus();
			alert("es necesario elegir el tipo de base de  datos!!!");
			return false;
		}
	//tipo de sistema
		tipo_sistema=$("#tipo_sys").val();
		if(tipo_sistema==0){
			$("#tipo_sys").focus();
			alert("es necesario elegir el tipo de sistema!!!");
			return false;
		}
	//id de nueva sucursal
		id_sucursal=$("#id_suc").val();
		if(id_sucursal==0){
			$("#id_suc").focus();
			alert("es necesario elegir la sucursal!!!");
			return false;
		}
	//agrupar movimientos
		if($("#agrupa_mov").checked==true){
			agrupar_movimientos=1;
		}
	//eliminar movimientos
		if($("#elimina_mov").checked==true){
			eliminar_movimientos=1;
		}
	//agrupar ventas
		if($("#agrupa_vtas").checked==true){
			agrupar_ventas=1;
		}
	//eliminar ventas
		if($("#elimina_vtas").checked==true){
			eliminar_ventas=1;
		}
	//fecha de respaldo
		fec_rsp=$("#fecha_respaldo").val();

	//verificamos la contraseña
		user=$("#usuario").val();
		if(user.length==0){
			alert("Debe ingresar un usario!!!");
			$("#usuario").focus();
			return false;
		}
		pass=$("#contrasena").val();
		if(pass.length==0){
			alert("Debe ingresar un usario!!!");
			$("#contrasena").focus();
			return false;
		}
		//alert(id_sucursal);
	//enviamos validación de contraseña por ajax
		$.ajax({
			type:'POST',
			url:'restauraBase.php',
			cache:false,
			data:{fl:'permiso',usuario:user,clave:pass,suc:id_sucursal},
			success: function(dat){
				var aux=dat.split("|");
				if(aux[0]!='ok'){
					alert(dat);
					$("#usuario").val('');
					$("#contrasena").val('');
					return false;
				}else{
					$("#cont_emergente").html('<br><br><b style="color:white;font-size:40px;">Procesando...</b><br><br><img src="../../../img/img_casadelasluces/load.gif" width="180px">');
					$("#emergente").css("display","block");
					genera_restauracion();
					return true;
				}
			}
		});
	}

//función que respalda la BD
	function genera_restauracion(){
//		alert('restauración: '+id_sucursal+"|"+tipo_sistema);return false;
	//enviamos datos por ajax
		$.ajax({
			type:'POST',
			url:'restauraBase.php',
			cache:false,
			data:{t_bd:tipo_database,
				t_sys:tipo_sistema,
				id_suc:id_sucursal,
				gpo_mov:agrupar_movimientos,
				el_mov:eliminar_movimientos,
				gpo_vta:agrupar_ventas,
				el_vta:eliminar_ventas,
				fecha:fec_rsp},
			success: function(dat){
				var aux=dat.split("|");
				if(aux[0]!='ok'){
					alert("Error al procesar la petición, recargue la pantalla y vuelva a intentar!!!\n"+dat);
					$("#emergente").css("display","none");
				}else{
					alert("La base de datos fue procesada correctamente!!!");
					location.href="../../../index.php";
				}
			}
		});
	}

//funcion que carga archivo	
	function carga_archivo(){
		
	}
//función que carga combo
	function cambia_combo(obj){
		var tipo=$(obj).val();
		if(tipo==0){
			return true;
		}else{
		//enviamos dato por ajax
			$.ajax({
				type:'post',
				url:'getDatosCombo.php',
				cache:false,
				data:{fl:tipo},
				success:function(dat){
					var aux=dat.split("|");
					if(aux[0]!='ok'){
						alert("Error!!!\n"+dat);
					}else{
						$("#combo_sucs").html(aux[1]);
					}
				}
			});
		}

	}

//función del calendario
	function calendario(objeto){
    	Calendar.setup({
        	inputField     :    objeto.id,
        	ifFormat       :    "%Y-%m-%d",
        	align          :    "BR",
        	singleClick    :    true
		});
	}
</script>