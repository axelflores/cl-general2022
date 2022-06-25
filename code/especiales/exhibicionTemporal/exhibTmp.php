<?php
	include("../../../conectMin.php");//incluimos el archivo de conexión
?>
<style type="text/css">
	*{
		padding: 0;margin:0;
	}
	.global{
		padding: 0px;
		background-image: url(../../../img/img_casadelasluces/bg8.jpg);
	}
	.enc{
		position:absolute;width: 98.5%;top:0;padding:10px;height:40px;background:#83B141;
	}
	.contenido{
		position:absolute;width: 100%;top:60px;height: 85%;
		background-image: url(../../../img/img_casadelasluces/bg8.jpg);
	}
	.footer{
		position:absolute;width: 98.5%;bottom:0;padding:10px;height:40px;background:#83B141;	
	}
	.btn_footer{
		padding: 10px;border:1px solid white;background: gray;border-radius:5px;width:130px;
		color:white;text-align: center;position: absolute;top:8px;text-decoration: none;width:10%;right:45%;
	}
	.btn_footer:hover{
		padding: 15px;border:1px solid white;background:rgba(0,0,0,.8);top:5px;
	}
	th{
		background: rgba(225,0,0,.6);color:white;padding: 10px;
	}
	#contenidoTabla{
		height:430px;overflow: scroll;
	}
	.btn_save{
		border-radius: 5px;color:black;background:transparent;padding: 5px;
	}
	.btn_save:hover{
		color:blue;background: rgba(0,225,0,.4);
	}
	#res_busc{
		width:39%;height:250px;overflow:auto;background: white;position:fixed;z-index: 2;display:none;
	}
</style>
<!DOCTYPE html>
<html>
<head>
	<title>Ajustes en Almácen exhibición</title>
<script type="text/javascript" src="../../../js/jquery-1.10.2.min.js"></script>
</head>
<body>
	<div class="global">
		<div class="enc">
			<table width="100%">
				<tr>
					<td width="40%">
						<input type="text" id="buscador_exh" style="width:100%;padding:10px;border-radius:8px;" onkeyup="validaBusc(event);">
						<div id="res_busc"></div>
					</td>
					<td><img src="../../../img/especiales/buscar.png" width="40px"></td>
					<td></td>
				</tr>
			</table>
		</div>
		<div class="contenido"><br>
			<table width="80%" style="position:relative;left:10%">
				<tr>
					<th width="10%">Orden Lista</th>
					<th width="45%">Producto</th>
					<th width="15%">Piezas tomadas de Exhibición</th>
					<th width="15%">Piezas exhibidas</th>
					<th width="15%">No se exhiben</th>
				</tr>	
				<tr>
					<td colspan="5" width="101%">
						<div id="contenidoTabla">
							<?php include('cargaTmpExhib.php'); ?>
						</div>
					</td>
				</tr>
			</table>
			<p align="right" style="width:90%">
				<button class="btn_save"><img src="../../../img/especiales/save.png" width="40px" onclick="guardar();"><br><b>Guardar</b></button>
			</p>
		</div>
		<div class="footer">
			<a href="=../../../../../index.php" class="btn_footer"> Regresar al Panel</a>
		</div>
	</div>
</body>
</html>

<!--JS-->
<script type="text/javascript">
	var en_edicion=0;//variable que indica si una celda está en edición
	var valor_antes="";//variable que guarda el valor antes de editar una celda
	var fila_resaltada=0;//variable que guarda que fila está resaltada
	var opc_resaltada=0;
/*función que edita celdas*/
	function editaCelda(flag,num){
	//validamos que la celda no se encuentre en edición
		if(en_edicion==1){
			return false;//si la celda ya está en edición no hacemos nada
		}
	//ponemos la celda en edición
		en_edicion=1;
	//extraemos el valor de la celda y lo guardamos en variable global
		valor_antes=$("#"+flag+"_"+num).html();
		var caja_txt='<input type="text" id="tmp_txt" style="width:99%;height:35px;text-align:right;" value="'+valor_antes+'"" '+
		'onblur="desEditaCelda('+flag+','+num+');" onkeyup="validarTeclaCelda(event,'+flag+','+num+');">';
		$("#"+flag+"_"+num).html(caja_txt);
		$("#tmp_txt").select();
	}
/*fin de función que edita celdas*/

/*función que desEdita celdas*/
	function desEditaCelda(flag,num){
	//extraemos el valor de la caja de texto
		var nvo_valor=$("#tmp_txt").val();
		if(nvo_valor==''||nvo_valor==null){
			nvo_valor=0;
		}
		$("#"+flag+"_"+num).html(nvo_valor);
		var pendientes=parseInt($("#7"+"_"+num).html());
		if((parseInt($("#4"+"_"+num).html())+parseInt($("#5"+"_"+num).html()))>pendientes){
			$("#"+flag+"_"+num).html("0");
			alert("no se puede regresar o exhibir más de lo que se tomó!!!");
			en_edicion=0;
			setTimeout(editaCelda(flag,num),500);
			return false;
		}

	//hacemos las sumas de las columnas
		var sumatoria=pendientes-(parseInt($("#4"+"_"+num).html())+parseInt($("#5"+"_"+num).html()));
		$("#3"+"_"+num).html(sumatoria);
		en_edicion=0;//liberamos celda en edición
	}

/*fin de función que desEdita celdas*/

/*función que valida las teclas en caja de texto temporal*/
	function validarTeclaCelda(e,flag,num){
		var tca=e.keyCode;
		//alert(tca);
	//tecla izquierda
		if(tca==37){
			if(flag==4){
				return false;
			}
			$("#buscador_exh").focus();
			$("#"+parseInt(flag-1)+"_"+num).click();
		}
	//tecla arrriba
		if(tca==38){
			if(num==1){
				return false;
			}
			$("#fila_"+parseInt(num-1)).focus();
			$("#"+flag+"_"+parseInt(num-1)).click();
		}
	//tecla derecha
		if(tca==39){
			if(flag==5){
				return false;
			}
			$("#buscador_exh").focus();
			$("#"+parseInt(flag+1)+"_"+num).click();

		}
	//tecla abajo o intro
		if(tca==40||tca==13){
			if(num>=$("#total_filas").val()){
				return false;
			}
			$("#fila_"+parseInt(num+1)).focus();
			$("#"+flag+"_"+parseInt(num+1)).click();
		}
	}
/*fin de función que valida las teclas en caja de texto temporal*/

/*funcion que resalta/regresa color de filas del grid de datos*/
	function resalta_fila(num){
		var color="#BAD8E6";
		if(num%2==0){
			color="#E6E8AB";
		}
	//verificamos si ya hay una fila resaltada
		if(fila_resaltada!=0){
		//regresamos el color original a la celda resaltada
			$("#fila_"+fila_resaltada).css("background",color);	
		}
	//resaltamos la fila donde se dió click
		fila_resaltada=num;//asignamos la nueva fila resltada
			//$("#fila_"+fila_resaltada).focus();
			$("#fila_"+fila_resaltada).css("background","rgba(0,225,0,0.5)");	
	}
/*fin de funcion que resalta/regresa color de filas del grid de datos*/

/*función que guarda los datos en la BD*/
	function guardar(){
	//sacamos el tamaño de la tabla
		var tam=$("#total_filas").val();
		var datos="";//declaramos la variable que guardará los datos
		var agotados=0;//declaramos contador de productos agotados
	//recorremos la tabla en busca de valores
		for(var i=1;i<=tam;i++){
		//comprobamos si existe la fila
			if(document.getElementById("fila_"+i)){

			//verificamos si hay datos válidos
				if(($("#4_"+i).html()!=0 && $("#4_"+i).html()!='')||($("#5_"+i).html()!=0 && $("#5_"+i).html!='')){
				//guardamos los datos en la variable datos
					datos+=$("#1_"+i).html()+"~";//id del registro
					datos+=$("#4_"+i).html()+"~";//piezas exhibidas
					datos+=$("#5_"+i).html()+"~";//piezas agotadas
					datos+=$("#6_"+i).html()+"|";//id del producto
				//checamos si se trata de un producto agotado
					if($("#5_"+i).html()!=0 && $("#5_"+i).html!=''){
						agotados++;
					}
				}//fin de si es registro válido
			}//fin de si existe la fila
		}//fin de for i	
		/*alert(datos);
		return false;*/
	//enviamos los datos por ajax
		$.ajax({
			type:'post',
			url:'procesosBD.php',
			cache:false,
			data:{fl:'guarda',arr:datos,mov_alm:agotados},
			success:function(dat){
				var aux=dat.split("|");
				if(aux[0]!='ok'){
					alert("Error!!!\n"+dat);
				}else{
				//recargamos la página
					location.reload();
				}
			}

		});
	}
/*fin de función que guarda los datos en la BD*/


/*función que realiza busqueda*/
	function validaBusc(e){
		var texto=$("#buscador_exh").val();
		var tca=e.keyCode;
		if(tca==40){
		//enfocamos la primera opción del buscador
			resalta_opc_busc(1);
			return true;
		}
		if(texto.length<=2){
			$("#res_busc").html("");
			$("#res_busc").css("display","none");
			return true;
		}
	//enviamos datos por ajax
		$.ajax({
			type:'post',
			url:'procesosBD.php',
			cache:false,
			data:{fl:'busqueda',txt:texto},
			success:function(dat){
				var aux=dat.split("|");
				if(aux[0]!='ok'){
					alert("Error!!!\n"+dat);
					return false;
				}
			//cargamos los datos en el resultado de la búsqueda
				$("#res_busc").html(aux[1]);
				$("#res_busc").css("display","block");
			}
		});
	}
/*fin de función que realiza busqueda*/

/*función que valida tecla oprimida en opciones de bucasdor*/
	function valida_tca_busc(e,id,num){
		var tca=e.keyCode;
	//tecla intro
		if(tca==13){
			$("#fila_opc_"+num).click();
		}
	//tecla arriba
		if(tca==38){
			resalta_opc_busc(parseInt(num-1));
			//$("#fila_opc_"+parseInt(num-1)).focus();
		}
	//tecla abajo
		if(tca==40){
			resalta_opc_busc(parseInt(num+1));
			//$("#fila_opc_"+parseInt(num+1)).focus();
		}
		return true;
	}
/*fin de función que valida tecla oprimida en opciones de bucasdor*/
	
/*función que enfoca la fila correspodiente al resultado del buscador*/
	function enfoca(id){
	//sacamos el valor de la tabla
		var tam=$("#total_filas").val();
	//recorremos la tabla en búsqueda del id seleccionado
		for(var i=0;i<=tam;i++){
		//comprobamos si existe la fila
			if(document.getElementById('fila_'+i)){
				if($("#1_"+i).html()==id){
					$("#buscador_exh").val("");//limpiamos el buscador
					opc_resaltada=0;//resetamos variable de opción resaltada
					$("#res_busc").html();//limpiamos los resultados de busqueda
					$("#res_busc").css("display","none");//oculatamos resultados de busqueda
					$("#fila_"+i).focus();//enfocamos fila
					$("#4_"+i).click();//activamos edición de piezas exhibidas
					return true;
				}
			}
		}//fin de for i
		alert("El registro no se encuentra en la tabla!!!");
		$("#buscador_exh").select();
		return true;
	}
/*fin de función que enfoca la fila correspodiente al resultado del buscador*/

/*función que enfoca opciones de búsqueda*/
	function resalta_opc_busc(num){
	//comprobamos si ya hay una celda resaltada
		if(opc_resaltada!=0){
			//regresamos el color blanco
			$("#fila_opc_"+opc_resaltada).css("background","white");
		}
	//asignamos la nueva opcion resaltada
		opc_resaltada=num;
		$("#fila_opc_"+opc_resaltada).css("background","rgba(0,225,0,.6)");
		$("#fila_opc_"+opc_resaltada).focus();
		return true;
	}
/*fin de función que enfoca opciones de búsqueda*/
</script>