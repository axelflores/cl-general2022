<link href="estilo_final.css" rel="stylesheet" type="text/css" />
<link href="css/demos.css" rel="stylesheet" type="text/css" />
<!--Modificacaión Oscar 11.11.2018 para implementar librería para convertir entrada de texto en password-->
	<script type="text/javascript" src="../../js/passteriscoByNeutrix.js"></script>
<!--Fin de cambio Oscar 11.11.2018-->
{include file="_header.tpl" pagetitle="$contentheader"}

<div id="campos">  
<div id="titulo">4.1 Control de asistencia</div>
<br><br>
<div id="filtros">
	<!--<form style="width:300px; margin:0 auto; display:block;" class="buscars" >actions= "asistencias.php" methods"post"names"form" se quita formulario cambio Oscar 24.05.2018-->
	<center>
		<table border="0" style="position:absolute;right:38%;top:400px;width:40%;left:30%;">
         	<tr style="background:green;">
<!--<td class="motivo"><p style="font-size:15px;>"><b>Ingrese su Login<b></p></td>-->
			<!---->
       			<!--<td>
       				<button id="entrada" onclick="marca_tipo(1);"
       				style="position:absolute;top:-54px;width:80px;height:80px;left:-30%;padding:10px;background:gray;color:white;
     -moz-border-radius: 100px 100px 0 0;
     -webkit-border-radius: 100px 100px 0 0;">Entrada
       					<!--<img src="../../img/especiales/asistencia_entrada.jpg" width="50px">--
       				</button>
       				<button id="salida" onclick="marca_tipo(2);"
       				style="position:absolute;top:24px;width:80px;height:80px;left:-30%;background:gray;color:white;
     -moz-border-radius: 0px 0px 100px 100px;
     -webkit-border-radius: 0px 0px 100px 100px;">Salida 
       					<!--<img src="../../img/especiales/asistencia_salida.jpg" width="50px">-
       				</button>class="boton" value="{$clave_acceso}"  value="{$clave_acceso}"
       			</td>
	        ---->
	        	<td style="padding:20px;"><b style="font-size:40px;color:white;">Entrada:</b></td>
          		<td>
          			<input type="text" name="sucur" id="clave_as" placeholder="Usuario" onkeyup="buscaEmpleado(this.form,1,event,1);">	
	          	</td>				
	          	<td align="center">
					<input name="button" type="button" id="button" style="padding:10px;border-radius:10px;" value="Aceptar" onclick="buscaEmpleado(this.form,2)"/>
				</td>				
          	</tr>
          	<tr style="background:red;">
	        	<td style="padding:20px;"><b style="font-size:40px;color:white;">Salida:</b></td>
          		<td "padding:20px;">
          			<input type="text" name="sucur" id="clave_as_1" placeholder="Usuario" onkeyup="buscaEmpleado(this.form,1,event,3);">	
	          	</td>				
	          	<td "padding:20px;">
					<input name="button" type="button" style="padding:10px;border-radius:10px;" id="button" value="Aceptar" onclick="buscaEmpleado(this.form,4)"/>
				</td>				
          	</tr>
          	</tr>
        {if $ver_log_login eq '1'}  	
          	<tr>
          		<td colspan="4" align="right"><br><input type="checkbox" id="incluye_login" checked>  Incluir busqueda por Login</td>
          	</tr>
        {/if}
    	</table>
    </center>      	
	<!--</form>-->
</div>

                	<p style="color:#c1272d;font-weight:bold;top:75%;position:absolute;left:40%;width:20%" align="center" id="textoErr"></p>
<div id="bg_seccion">
	<!--<div class="name_module" align="center">
		<p>Control de asistencia</p>		    
	</div>-->
		
</div>
<!--implementación de Oscar 27.02.2018-->
	<div style="width:100%;height:130%;position:absolute;background:rgba(0,0,0,.8);top:0;display:none;z-index:1500;" id="emer_asist">
	<center>
		<p style="top:150px;position:relative;font-size:25px;color:white;" id="instr_asist"></p>
		<table width="40%" border="1" style="position:relative;top:170px;background:white;" id="contenido_horas">
			<tr>
				<th colspan="3"><p id="nom_em">erlge</p></th>
			</tr>
			<tr>
				<td align="center">
					Fecha 
				</td>
				<td>
					Hora Entrada
				</td>
				<td>
					Hora Salida
				</td>
			</tr>
			<tr>
				<td align="center">
					<input type="text" id="fec_as" disabled>
					<input type="hidden" id="id_reg" value="0">
				</td>
				<td>
					<input type="text" id="hor_ent_as" disabled>
				</td>
				<td>
					<input type="text" id="hor_sal_as">
				</td>
			</tr>
			<tr>
				<td colspan="3" width="100%">
					<p align="center">
					<br><br>
						<input type="text" id="pss_encar_suc1" onkeydown="cambiar(this,event,'pss_encar_suc');" style="width:50%;">
						<input type="hidden" id="pss_encar_suc" value="">
						<br><br>
						<input type="button" value="Cambiar Salida" onclick="cambia_salida();" style="padding:10px;"><br>
					</p>
				</td>
			</tr>
		</table>
	</center>
	</div>
<!--Fin de implementacion 27.02.2018-->
<script>
	{literal}
	function guarda_cambios_salidas(){
		var password=0;
		var pass_enc="";
		var tope=$("#total_salidas_pendientes").val();
		var por_guardar="";
	//recorremos las salidas
		if($("#col_sal_pend_4_1").html()=='00:00:00'){
			alert("No puede registrar una entrada si tiene salidas pendientes!!!");
			setTimeout(function(){$("#col_sal_pend_4_1").click();},'300');
			//$("#input_temporal").select();
			return false;
		}
		for(var i=1;i<=tope;i++){
		//comprobamos que existe la fila
			if($("#col_sal_pend_4_"+i).html()!='' && $("#col_sal_pend_4_"+i).html()!='00:00:00'){
				por_guardar+=$("#col_sal_pend_1_"+i).html()+"~";//id de registro de nomina
				por_guardar+=$("#col_sal_pend_4_"+i).html()+'~';//hora de salida
				por_guardar+=$("#col_sal_pend_2_"+i).html()+"|";//fecha
			}
		}
		//alert(por_guardar);
	//enviamos datos por ajax
		if(por_guardar!=""){//si hay registros		
	//vemos si es necesario el password
			if(document.getElementById('password_assistencia_1')){
				password=1;
				pass_enc=$("#password_assistencia_1").val();//obtenemos el valor del password
				if(pass_enc.length<=0){
					alert("La contraseña de encargado no puede ir vacía!");
					$("#password_assistencia_1").focus();
					return false;
				}
			}
		//enviamos datos por ajax
			$.ajax({
				type:'post',
				url:'../ajax/especiales/asistenciass.php',
				cache:false,
				data:{fl:'actualiza_salidas',verifica_pass:password,password_encargado:pass_enc,salidas:por_guardar},
				success:function(dat){
					if(dat!='ok'){
						alert("Error:\n"+dat);
						return false;
					}else{
						var login=0;
						if(document.getElementById('incluye_login')){
							if(document.getElementById('incluye_login').checked==true){
								login=1;
							}
						}
						var aux=ajaxR("../ajax/especiales/asistenciass.php?tipo=3&clave="+$("#clave_as").val()+
							"&tipo_asistencia=1&incluir_login="+login);//modificación de Oscar 24.04.2018
						var ax=aux.split('|');
						$("#emer_asist").html(ax);
						$("#emer_asist").css("display","block");
						$("#btn_ok").focus();					
					}
				}
			});
		}else{
			location.reload();//recargamos la página
		}
	}

var valor_antes="",editando_celda=0;
	function edita_celda(num){
		if(editando_celda!=0){return false;}else{editando_celda=1;}
		valor_antes=$("#col_sal_pend_4_"+num).html();
		var input_tmp='<p style="width:90%;"><input type="time" min="07:00" max="23:59" id="input_temporal" style="padding:10px;width:90%;" value="'+valor_antes+'"'+
		' onblur="desedita_celda('+num+');"></p>';
		$("#col_sal_pend_4_"+num).html(input_tmp);
		$("#input_temporal").select();	
		$("#input_tmp").select();	
	}

	function desedita_celda(num){
		$("#col_sal_pend_4_"+num).html($("#input_temporal").val());
		editando_celda=0;
	}

var tipo_registro=0;
	function marca_tipo(flag){
		if(flag==1){
			$("#entrada").css("background","rgba(0,225,0,0.6)");
			$("#entrada").css("border","3px solid gray");
			$("#salida").css("background","gray");
			$("#salida").css("border","0");
		}
		if(flag==2){
			$("#salida").css("background","rgba(225,0,0,0.6)");
			$("#salida").css("border","3px solid gray");
			$("#entrada").css("background","gray");
			$("#entrada").css("border","0");
		}
		tipo_registro=flag;
		$("#clave_as").focus();
	}
/*Implementación de Oscar 27.02.2018*/
		function cambia_salida(){
			var id,h_s,h_e,p_e;
		//obtenemos valores
			id=document.getElementById('id_reg').value;
			h_e=document.getElementById('hor_ent_as').value;
			h_s=document.getElementById('hor_sal_as').value;
			p_e=document.getElementById('pss_encar_suc').value;
		//validamos antes de enviar datos
			if(h_s==''||h_s<=h_e){
				alert("La hora de salida debe ser mayor a la hora de entrada y este valor no puede ir vacío");
				document.getElementById('hor_sal_as').value="00:00:00";
				$("#hor_sal_as").select();
				return false;
			}
			var env=ajaxR("../ajax/especiales/modificaSalida.php?id_registro="+id+"&hora_s="+h_s+"&pss="+p_e);
		//trabajamos respuesta
			res=env.split("|");
			//detectamos error
			if(res[0]!='ok'){
				alert("Error\n"+env);
				return false;
			}
			//respuesta de password
			if(res[1]!='ok'){
				alert(res[1]);
				$("#pss_encar_suc").select();
				return false;
			}else{
				if(!$("#button").click()){
					alert("Error al registrar la nueva entrada");
					return false;
				}else{
					alert("La salida del día anterior, así como la entrada de este día fueron registradas exitosamente!!!");
					$("#emer_asist").css("display","none");
				}
			}
		}
/*fin de implementacion 27.02.2018*/
		
		function buscaEmpleado(f,flag,e,flag2)//implementación Oscar 24.05.2018 para registrar nómina con intro
		{	//flag2=1;entrada,=1;salida
			var tipo_de_reg=1,login=0;//entrada por default
			var clave=$("#clave_as").val();
			if(flag==1||flag==3){
				var tca=e.keyCode;
				if(tca!=13){				
					return true;
				}
			}
			//fin de cambio 24.05.2018

			if(flag2==3||flag==4){
				tipo_de_reg=2;//salida
				clave=$("#clave_as_1").val();
			}
		//vemos si existe y esta habilitado el checkbox de incluir login en la busqueda
			if(document.getElementById('incluye_login')){
				if(document.getElementById('incluye_login').checked==true){
					login=1;
				}
			}

			document.getElementById('textoErr').innerHTML="";
			
			var aux=ajaxR("../ajax/especiales/asistenciass.php?tipo=3&clave="+clave+"&tipo_asistencia="+tipo_de_reg+"&incluir_login="+login);//modificación de Oscar 24.04.2018
			//alert(aux);
			
			var ax=aux.split('|');

			$("#emer_asist").html(ax);
			$("#emer_asist").css("display","block");
			if(flag==2){
				$("#emer_asist").css("background","rgba(0,225,0,.8)");
			}
			if(flag==4){
				$("#emer_asist").css("background","rgba(225,0,0,.8)");
			}
				$("#btn_ok").focus();
			/*if(ax[0] != 'exito')
			{
				alert("Respuesta="+aux);
				//f.sucur.value="";
				return true;
			}
		//implementación Oscar 27.02.2018
			if(ax[1]=="Salida Pendiente"){
				//alert(ax[2]);
				var dat=ax[2].split("~");
				document.getElementById('instr_asist').innerHTML="<b>Pida al encargado que registre su hora de salida del día "+dat[2]+"</b>";
				document.getElementById('id_reg').value=dat[0];
				document.getElementById('nom_em').innerHTML=dat[1];
				document.getElementById('fec_as').value=dat[2];
				document.getElementById('hor_ent_as').value=dat[3];
				document.getElementById('hor_sal_as').value=dat[4];
				$("#contenido_horas").css("display","block");
				$("#emer_asist").css("display","block");
				return false;
			}
			
			if(ax[1] == 'NO')
			{
			//implementación Oscar 24.05.2018 para registrar nómina con intro
				$('#instr_asist').html('<br><br><p style="color:white;font-size:50;" align="center"><b>Código Invá	lido!!!</b></p><input id="bt_cierra" type="button" '+
					'style="padding:15px;border-radius:5px;font-size:25;" value="Aceptar" onclick="document.getElementById(\'emer_asist\').style.display=\'none\';">');
				$("#contenido_horas").css("display","none");
				$("#emer_asist").css("display","block");
				$('#clave_as').val("");
				$("#bt_cierra").focus();
				return false;
			//fin de cambio 24.05.2018
			}
			
			else
			{

				document.getElementById('nombre').innerHTML=ax[2];
				document.getElementById('tipoM').innerHTML=ax[3];
				document.getElementById('hora').innerHTML=ax[4];
				$("#clave_as").val("");
			//implementación de mensaje Oscar 24.04.2018
				$('#instr_asist').html('<br><br><p style="color:white;font-size:50;" align="center"><b>Registro exitoso!!!</b></p><input id="bt_cierra" type="button" '+
					'style="padding:15px;border-radius:5px;font-size:25;" value="Aceptar" onclick="document.getElementById(\'emer_asist\').style.display=\'none\';">');
				$("#contenido_horas").css("display","none");
				$("#emer_asist").css("display","block");
				$("#bt_cierra").focus();
			//fin de cambio 24.05.2018
			}*/			
			
		}
	{/literal}
</script>
</div>

{include file="_footer.tpl" pagetitle="$contentheader"} 