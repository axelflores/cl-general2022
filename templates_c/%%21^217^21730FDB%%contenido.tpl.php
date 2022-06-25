<?php /* Smarty version 2.6.13, created on 2022-06-01 11:10:41
         compiled from general/contenido.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'general/contenido.tpl', 459, false),)), $this); ?>
<!--version 30.10.2019-->
<!-- 1. Incluye archivo /templates/_header.tpl -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- implementacion Oscar 2021 para agregar JS adicional -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/utilidadesGenerales.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- Fin de cambio Oscar 2021 -->

<!-- Excepcion 1: Implementacion para botones de exportacion de ubicaciones desde la configuracion de la sucursal -->
<?php if ($this->_tpl_vars['tabla'] == 'ec_configuracion_sucursal' && $this->_tpl_vars['no_tabla'] == '0'): ?>
	<table style="position:absolute;bottom:30%;">
		<tr>
			<td>
				<button onclick="exporta_ubics_sucs();">
				Exportar<br>Ubicaciones
				</button>
			</td>
		</tr>
		<tr>
			<td>
				<button id="acciona_imp_ubic_sucs" onclick="msg_importa_ubics();">
					Importar<br>Ubicaciones
				</button>
				<button id="acciona_importacion_ubic_sucs" style="display:none;">
					Actualizar<br>Ubicaciones
				</button>
			</td>
	</table>
	<form id="formularioUbicaciones" method="post" action="../ajax/importaExportaUbicacionesSucursales.php" target="TheWindow">
			<input type="hidden" id="fl_ubic" name="fl_ubic" value="1" />
			<input type="hidden" id="sucursal_ubic" name="sucursal_ubic" value="1" />
			<inupt type="hidden" id="datos_ubic_sucs" name="datos_ubic_sucs" value="1">
			<input type="file" id="archivo_ubic" name="archivo_ubic" value="" style="display:none;" onchange="preparar_importacion_ubic();"/>
	</form>
	<?php echo '
		<script type="text/javascript">

			var ventana_abierta_ubics;
			function msg_importa_ubics(){
				if(!confirm("Recuerde que no pueden ir comas en las ubicaciones y las ubicaciones deberán de ser menores a 10 caracteres!!!")){
					return false;
				}
				$(\'#archivo_ubic\').click();
			}

			function exporta_ubics_sucs(){
			//obtenemos el id de la sucursal
				var id_suc_ubic=$("#id_sucursal").val();
				if(id_suc_ubic<1){
					alert("Esta sucursal no puede tener ubicaciones!!!");
					return false;
				}
				$("#fl_ubic").val(\'exporta_ubicaciones\');
				$("#sucursal_ubic").val(id_suc_ubic);
			//enviamos la descarga
				ventana_abierta_ubics=window.open(\'\', \'TheWindow\');
				document.getElementById(\'formularioUbicaciones\').submit();
				setTimeout(cierra_pestana_ubic,10000);
			}

			function cierra_pestana_ubic(){
				ventana_abierta_ubics.close();//cerramos la ventana
			}

			function preparar_importacion_ubic(){
				$("#acciona_imp_ubic_sucs").css("display","none");
				$("#acciona_importacion_ubic_sucs").css("display","block");

			}

			/*function importar_ubic_sucs(){

			}*/
				$(\'#acciona_importacion_ubic_sucs\').on("click",function(e){
  					e.preventDefault();
  					$(\'#archivo_ubic\').parse({
        				config: {
            				delimiter:"auto",
            				complete: importaUbicacionesSucursales,
        				},
       			 		before: function(file, inputElem){
       			 			//$("#espacio_importa").css("display","none");//ocultamos el botón de búsqueda
            			//console.log("Parsing file...", file);
        				},
       					error: function(err, file){
         		   			console.log("ERROR:", err, file);
        					alert("Error!!!:\\n"+err+"\\n"+file);
        				},
       			 		complete: function(){
            				//console.log("Done with all files");
        				}
    				});
				});

			function importaUbicacionesSucursales(results){
				$("#contenido_emergente_global").html(\'<p align="center" style="color:white;font-size:30px;">Cargando datos<br><img src="../../img/img_casadelasluces/load.gif"></p>\');
					$("#ventana_emergente_global").css("display","block");

				var data = results.data;//guardamos en data los valores delarchivo CSV
				var arr="";
				var id_suc_ubic=$("#id_sucursal").val();
				var msg_alrta="El formato del archivo no es valido!!!\\nVerifique que no haya comas en los campos de ubicación y vuelva a intentar!!!\\n";
				msg_alrta+="El encabezado debe de llevar los campos: ID PRODUCTO|ORDEN DE LISTA|ALFANUMERICO|NOMBRE|INVENTARIO EN SUCURSAL(ALMACEN PRIMARIO)";
				msg_alrta+="|UBICACION";
				for(var i=1;i<data.length;i++){
					var row=data[i];
					var cells = row.join(",").split(",");
					if(cells.length>1){
						if(cells.length!=6){
							alert(msg_alrta+"\\n"+cells.length);
							location.reload();
							return false;
						}
						arr+=cells[0]+",";
    					arr+=cells[5];//se cambia la posición  de 6 a 7 por la implementación de la clave de proveedor Oscar 26.02.2019
						if(i<data.length-1){
					 		arr+="|";
						}
					}
				}//fin de for i
				//alert(arr);
			//enviamos la descarga
				$.ajax({
					type:\'post\',
					url:\'../ajax/importaExportaUbicacionesSucursales.php\',
					cache:false,
					data:{fl_ubic:\'importa_ubicaciones\',datos_ubic_sucs:arr,sucursal_ubic:id_suc_ubic},
					success:function(dat){
						alert(dat);
						$("#ventana_emergente_global").css("display","none");
						location.reload();
					}
				});
			}

		</script>
	'; ?>

<?php endif; ?>


<!-- Excecpcion 2: Implementacion para exportar/ importar estacionalidades -->
<?php echo '

<!-- 2. Incluye archivo /js/papaparse.min.js -->
<script language="JavaScript" type="text/javascript" src="../../js/papaparse.min.js"></script>
				<script type="text/JavaScript">
				$(\'#submit-file\').on("click",function(e){
  					e.preventDefault();
  					$(\'#imp_csv_prd\').parse({
        				config: {
            				delimiter:"auto",
            				complete: importaEstac,
        				},
       			 		before: function(file, inputElem){
            			//console.log("Parsing file...", file);
        				},
       					error: function(err, file){
         		   			console.log("ERROR:", err, file);
        					alert("Error!!!:\\n"+err+"\\n"+file);
        				},
       			 		complete: function(){
            				//console.log("Done with all files");
        				}
    				});
				});

		//detectamos archivo cargado
				$("#imp_csv_prd").change(function(){
        			var fichero_seleccionado = $(this).val();
      				var nombre_fichero_seleccionado = fichero_seleccionado.replace(/.*[\\/\\\\]/, \'\');
       				/* if(nombre_fichero_seleccionado===\'\') {
      			    $(\'#delCarta\').addClass(\'invisible\');
        			} else {
        			   $(\'#delCarta\').removeClass(\'invisible\');
       				 }*/
       				if(nombre_fichero_seleccionado!=""){
        				$("#bot_imp_estac").css("display","none");//ocultamos botón de importación
        				$("#submit-file").css("display","block");//mostramos botón de inserción
        				$("#txt_info_csv").val(nombre_fichero_seleccionado);//asignamos nombre del archivo seleccionado
        				$("#txt_info_csv").css("display","block");//volvemos visible el nombre del archivo seleccionado
        				//$("#importa_csv_icon").css("display","none");
        			}else{
        				alert("No se seleccionó ningun Archivo CSV!!!");
        				return false;
        			}
    			});
/*
				function cambiaEstacDependiente(){
					alert("est");
				}
*/
				function importaEstac(results){
					$("#contenido_emergente_global").html(\'<p align="center" style="color:white;font-size:30px;">Cargando datos<br><img src="../../img/img_casadelasluces/load.gif"></p>\');
					$("#ventana_emergente_global").css("display","block");

					var id_estac=$("#id_estacionalidad").val();
	  				var data = results.data;//guardamos en data los valores delarchivo CSV
	    			var tam_grid=$("#estacionalidadProducto tr").length-3;
	    			//alert(data);
	    			//return true;
	    			var arr="";
	   				for(var i=1;i<data.length-1;i++){
	    				//arr+=data[i];
	    				var row=data[i];
	    				var cells = row.join(",").split(",");
	    				/*for(j=0;j<cells.length;j++){*/
	            			arr+=cells[0]+",";
	            			arr+=cells[6];
	        			/*}*/
	        			if(i<data.length-2){
	        			arr+="|";
	        			}
	    			}
	    		//enviamos datos por ajax
	    			$.ajax({
	    				type:\'post\',
	    				url:\'../ajax/importaExportaEstacionalidades.php\',
	    				cache:false,
	    				data:{fl:2,id_estac:id_estac,arreglo:arr},
	    				success:function(dat){
	    					var arr_resp=dat.split("|");
	    					if(arr_resp[0]!=\'ok\'){
	    						alert("Error al recargar el grid de estacionalidad-producto!!!\\n"+dat);
	    					}else{
	    						location.reload();
	    					}
	    					$("#ventana_emergente_global").css("display","none")//ocultamos la emergente;
	    				}
	    			});
	    		}

				function importa_exporta_estacionalidades(flag){
					var est_id=$("#id_estacionalidad").val();//extraemos el id de la estacionalidad
					if(flag==1){
						document.location.href=\'../ajax/importaExportaEstacionalidades.php?fl=\'+flag+"&estacionalidad_id="+est_id;
					}else if(flag==2){
						$("#imp_csv_prd").click();
					}
				}

		/*************************Fin de implementacion Oscar 16.05.2018*******************************/
</script>
'; ?>


    <div id="campos">
<!-- 3. Div de ventana emergente -->
	<div id="emerge" style="position:fixed;top:0;height:100%;width:100%;background:rgba(0,0,0,.6);display:none;z-index:100;"><!--rgba(103, 161,13,.8);-->
		<center>
			<div id="mensajEmerge" style="width:50%;position:absolute;top:200px;left:25%;background:rgba(225,0,0,.5);border-radius:10px;">

			</div>
		</center>
	</div>
<!--Excepcion 3: Implementacion Oscar 19.08.2019 para impresion de credencial de usuario-->
		<?php if ($this->_tpl_vars['tabla'] == 'sys_users' && $this->_tpl_vars['no_tabla'] == '0' && $this->_tpl_vars['tipo'] != '0'): ?>
			<button id="impresion_cred_usuario" onclick="imprimeCredencial();" style="position:absolute;top:300px;left:42%;">
				Imprimir credencial<img src="../../img/especiales/credencial_usuario.png"  width="50px">
			</button>
		<?php endif; ?>
<!--Fin de cambio Oscar 19.08.2019-->

<!--Excepcion 4: Implementacion Oscar 02.08.2019 para importacion del detalle de ordenes de compra-->
		<?php if ($this->_tpl_vars['tabla'] == 'ec_ordenes_compra' && $this->_tpl_vars['no_tabla'] == '1' && $this->_tpl_vars['tipo'] != '0'): ?>
			<button style="position:fixed;right:1.6%;top:72%;border-radius:10px;font-size:10px;" onclick="descarga_formato_detalle_oc();">
				<img src="../../img/especiales/fotmato_en_blanco.png" width="30px"><br>
				Descarga<br>
				Formato
			</button>
		<!--formulario para la exportación del formato en CSV-->
			<form id="detalle_oc" method="post" action="../ajax/importarDetalleOrdenCompra.php?" target="TheWindow">
				<input type="hidden" name="fl" value="formato" />
			</form>
		<!--formulario para a importacion del csv de detalle de orden de cmompra-->
			<form class="">
				<input type="file" id="imp_detalle_oc" style="display:none;">
				<p class="nom_csv" style="position:fixed;right:7.5%;top:84.3%;border-radius:10px;font-size:10px;display:none;width:10%;">
					<input type="text" id="txt_info_detalle_oc_csv" disabled>
				</p>
				<button type="submit" id="submit_file_detalle_oc" style="position:fixed;right:1.6%;top:84%;border-radius:10px;font-size:10px;display:none;" class="bot_imp">
					<img src="../../img/especiales/sube.png" height="30px;">
					<br><b>Cargar<br>Archivo</b>
				</button>
			</form>
			<!--style="position:absolute;top:107%;right:15%;padding:5px;border-radius:10px;"-->
			<button style="position:fixed;right:1.6%;top:84%;border-radius:10px;font-size:11px;"
			onclick="carga_datos_detalle_oc();" id="btn_imp_detalle_oc">
				<img src="../../img/especiales/importaCSV.png" width="30px"><br>
				importar<br>Detalle
			</button>
<!--					<td>
					</form>-->
		<?php endif; ?>
<!--Fin de cambio Oscar 02.08.2019-->

	<!-- Excepcion 5: Implementacion de Oscar 25.07.2018 para exportar/importar lista de estacionalidades-->
		<?php if ($this->_tpl_vars['tabla'] == 'ec_oc_recepcion'): ?>
		<div style="position:fixed;z-index:40;top:15px;right:15px;">
			<button onclick="emerge_pagos();" id="pags_prv" style="background:white;border-radius:15px;">
				<img src="../../img/especiales/pagar.png" width="50px;">
				<br>Registrar Pago
				</button>
		</div>
			<?php echo '
			<script>
			function carga_cajas_sobrante(){
				$("#id_caja_o_cuenta").val();//extraemos el valor de la caja de donde se toma el pago
				var envia=\'../ajax/cargaPagosProveedor.php?caja_pago=\'+$("#id_caja_o_cuenta").val()+\'&fl=carga_caja_sobrante\';
				var env=ajaxR(envia);
               	var tmp=env.split("|");
               	if(tmp[0]!=\'ok\'){
               		alert("Error!!!\\n"+env);
               	}
               	$("#caja_de_sobrante").html(tmp[1]);//cargamos los resultados en el combo de caja sobrante
			}

			function emerge_pagos(filtro){
			//obtenemos el id de la orden de compra
			var id_ord_comp=$("#id_oc_recepcion").val();
				//alert(id_ord_comp);
				var envia=\'../ajax/cargaPagosProveedor.php?oc=\'+id_ord_comp;
               //metemos los filtros
               	if(filtro==1){
               		envia+="&status="+$("#filtro_tipo_rec").val();
               	//extraemos los filtros de fecha
               		if($("#rango_del").val()!="" || $("#rango_al").val()!=""){
               		//validamos que las 2 fechas esten capturadas
               			if($("#rango_del").val()!="" && $("#rango_al").val()==""){
               				alert("El campo de fecha final no puede ir vacío!!!");
               				$("#rango_al").focus();
               				return false;
               			}
               			if($("#rango_al").val()!="" && $("#rango_del").val()==""){
               				alert("El campo de fecha inicial no puede ir vacío!!!");
               				$("#rango_del").focus();
               				return false;
               			}
               		//mandamos la condición de rango de fechas
               			envia+="&periodo=AND ocr.fecha_recepcion BETWEEN \'"+$("#rango_del").val()+" 00:00:00\' AND \'"+$("#rango_al").val()+" 23:59:59\'";
               		}
               		//alert(envia);
               	}
               	var env=ajaxR(envia);
                var auxi=env.split(\'|\');
                if(auxi[0]!=\'ok\'){
                //mostramos el error
                	alert("Error!!!\\n"+env);
                }else{
                //cargamos los datos en la emergente
                	$("#mensajEmerge").html(auxi[1]);
                	$("#emerge").css("display","block");
                }
			}
			</script>
			'; ?>


		<?php endif; ?>
	<!--Fin de cambio-->

<!-- 4. Div de titulo de la pantalla -->
	<div id="titulo"><?php echo $this->_tpl_vars['titulo']; ?>
</div>
<!-- 5. Declaracion del formulario -->
		<form action="" method="post" name="formaGral" enctype="multipart/form-data">
			<script>
				var ejecutar="";
			</script>
<!-- 6. Variables ocultas %tipo, accion, tabla, no_tabla, llave%% -->
				<input type="hidden" name="tipo" value="<?php echo $this->_tpl_vars['tipo']; ?>
" />
				<input type="hidden" name="accion" value="" />
				<input type="hidden" name="tabla" value="<?php echo $this->_tpl_vars['tabla']; ?>
" />
				<input type="hidden" name="no_tabla" value="<?php echo $this->_tpl_vars['no_tabla']; ?>
" />
				<input type="hidden" name="llave" value="<?php echo $this->_tpl_vars['llave']; ?>
" />
		<!--Excepcion : Implementacion de botones especiales de la pantalla de sucursal-->
				<?php if ($this->_tpl_vars['tabla'] == 'sys_sucursales' && $this->_tpl_vars['no_tabla'] == 0 && ( $this->_tpl_vars['tipo_perfil'] == '1' || $this->_tpl_vars['tipo_perfil'] == '5' )): ?>
				<?php echo '
					<style type="text/css">
						.btns_deshabilit{padding:10px;border-radius:5px;}
					.btns_deshabilit:hover{background: rgba(225,0,0,.5);color: white;}
					</style>
				'; ?>

				<?php $this->assign('sucursal_informacion_tooltip', 'en la sucursal'); ?>
				<?php if ($this->_tpl_vars['campos'][0][10] == '-1'): ?>
					<?php $this->assign('sucursal_informacion_tooltip', 'en todas las sucursales'); ?>
				<?php endif; ?>
		<!-- Sección de herramientas por sucursal -->	
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/herramientasSucursales.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<?php endif; ?>
				<?php if ($this->_tpl_vars['no_tabs'] == 1): ?>
					<div class="redondo" align="center" >
						<table width="87%" border="0" class="tabla-inputs" >
	<!-- 7. Iteracion del arreglo %$campos%% para formar campos (catalogos) de la pantalla-->
							<?php unset($this->_sections['indice']);
$this->_sections['indice']['loop'] = is_array($_loop=$this->_tpl_vars['campos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indice']['name'] = 'indice';
$this->_sections['indice']['max'] = (int)$this->_tpl_vars['no_filas'];
$this->_sections['indice']['show'] = true;
if ($this->_sections['indice']['max'] < 0)
    $this->_sections['indice']['max'] = $this->_sections['indice']['loop'];
$this->_sections['indice']['step'] = 1;
$this->_sections['indice']['start'] = $this->_sections['indice']['step'] > 0 ? 0 : $this->_sections['indice']['loop']-1;
if ($this->_sections['indice']['show']) {
    $this->_sections['indice']['total'] = min(ceil(($this->_sections['indice']['step'] > 0 ? $this->_sections['indice']['loop'] - $this->_sections['indice']['start'] : $this->_sections['indice']['start']+1)/abs($this->_sections['indice']['step'])), $this->_sections['indice']['max']);
    if ($this->_sections['indice']['total'] == 0)
        $this->_sections['indice']['show'] = false;
} else
    $this->_sections['indice']['total'] = 0;
if ($this->_sections['indice']['show']):

            for ($this->_sections['indice']['index'] = $this->_sections['indice']['start'], $this->_sections['indice']['iteration'] = 1;
                 $this->_sections['indice']['iteration'] <= $this->_sections['indice']['total'];
                 $this->_sections['indice']['index'] += $this->_sections['indice']['step'], $this->_sections['indice']['iteration']++):
$this->_sections['indice']['rownum'] = $this->_sections['indice']['iteration'];
$this->_sections['indice']['index_prev'] = $this->_sections['indice']['index'] - $this->_sections['indice']['step'];
$this->_sections['indice']['index_next'] = $this->_sections['indice']['index'] + $this->_sections['indice']['step'];
$this->_sections['indice']['first']      = ($this->_sections['indice']['iteration'] == 1);
$this->_sections['indice']['last']       = ($this->_sections['indice']['iteration'] == $this->_sections['indice']['total']);
?>
		<!-- 7.1. Condicion si son tres o menos campos-->
									<?php if ($this->_tpl_vars['no_campos'] <= 3): ?>
										<td width="69" class="texto_form"><?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][3]; ?>
</td>
										<td width="175">
			<!-- 7.1.1. Condicion si el tipo de campo es CHAR -->
										<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'CHAR'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][12] > 60): ?>
												<textarea name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>><?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?></textarea>
											<?php else: ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>/>
											<?php endif; ?>
			<!-- 7.1.2. Condicion si el tipo de campo es DATE -->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'DATE'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="10" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?>onfocus="calendario(this)"<?php endif; ?> />
											<span class="text_legend">yy-mm-dd</span>
			<!-- 7.1.3. Condicion si el tipo de campo es TIME -->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'TIME'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="8" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?> onkeypress="return validaTime(event, this.id)"<?php endif; ?> />
											<span class="text_legend">hh:mm:ss</span>
			<!-- 7.1.4. Condicion si el tipo de campo es INT o FLOAT -->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'INT' || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> onkeypress="return validarNumero(event,<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>1<?php else: ?>0<?php endif; ?>,id);" onblur="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][15]; ?>
"/>
			<!-- 7.1.5. Condicion si el tipo de campo es PASSWORD -->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'PASSWORD'): ?>
                                            <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
                                            <input type="password" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>/>
			<!-- 7.1.6. Condicion si el tipo de campo es BINARY -->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BINARY'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> value="1"<?php else: ?> value="0"<?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> value="1" <?php else: ?> value="0" <?php endif; ?> <?php endif; ?>/>
												<input type="checkbox" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> disabled="disabled"/>
											<?php else: ?>
												<input type="checkbox" value="1" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
"/>
											<?php endif; ?>
			<!-- 7.1.7. Condicion si el tipo de campo es COMBO-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'COMBO'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" disabled="disabled">
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>"/>
											<?php else: ?>
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] ) || isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )): ?>onchange="<?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] )): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif;  if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17];  endif; ?>;"<?php endif; ?>>
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>

											<?php endif; ?>
			<!-- 7.1.8. Condicion si el tipo de campo es BUSCADOR-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BUSCADOR'): ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 1 && $this->_tpl_vars['tipo'] != 2 && $this->_tpl_vars['tipo'] != 3): ?>
												<table>
													<tr>
														<td>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" onkeyup="activaBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event);" onclick="ocultaCombobusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" autocomplete="off"  on_change="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17]; ?>
"/>
														</td>
														<td>
												<!--<img onclick="botonBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/flecha_abajo.gif" style="height:12px;" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" />-->
														</td>
													</tr>
												</table>
												<div id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_div" style="visibility:hidden; display:none; position:absolute; z-index:3;">
													<select id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_sel" size="4" onclick="asignavalorbusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
');<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] != ''): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif; ?>" onkeydown="teclaCombo('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event)" datosDB="getBuscador.php?id=<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][0]; ?>
">
														<option>						</option>
													</select>
												</div>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>">
											<?php else: ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" />
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>">
											<?php endif; ?>
			<!-- 7.1.9. Condicion si el tipo de campo es FILE-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FILE'): ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != ''): ?>
												<a href="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" target="_blank" class="texto_form">Ver documento</a>
											<?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												&nbsp;
											<?php else: ?>
												<input type="file" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
"/>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
											    <br>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                            <?php endif; ?>
										<?php endif; ?>
										</td>
		<!-- 7.2. Condicion si son 4 campos-->
									<?php elseif ($this->_tpl_vars['no_campos'] == 4): ?>
										<td width="69"  class="texto_form"><?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][3]; ?>
</td>
										<td width="175">
			<!-- 7.2.1. Condicion si el tipo de campo es CHAR-->
										<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'CHAR'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][12] > 60): ?>
												<textarea name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>><?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?></textarea>
											<?php else: ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>/>
											<?php endif; ?>
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'DATE'): ?>
			<!-- 7.2.2. Condicion si el tipo de campo es DATE-->
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="10" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?>onfocus="calendario(this)"<?php endif; ?> />
											<span class="text_legend">yy-mm-dd</span>
			<!-- 7.2.3. Condicion si el tipo de campo es TIME-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'TIME'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="8" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?> onkeypress="return validaTime(event, this.id)"<?php endif; ?> />
											<span class="text_legend">hh:mm:ss</span>
			<!-- 7.2.4. Condicion si el tipo de campo es INT o FLOAT-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'INT' || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> onkeypress="return validarNumero(event,<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>1<?php else: ?>0<?php endif; ?>,id);" onblur="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][15]; ?>
"/>
			<!-- 7.2.5. Condicion si el tipo de campo es PASSWORD-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'PASSWORD'): ?>
                                            <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
                                            <input type="password" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> />
			<!-- 7.2.6. Condicion si el tipo de campo es BINARY-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BINARY'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> value="1"<?php else: ?> value="0"<?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> value="1" <?php else: ?> value="0" <?php endif; ?> <?php endif; ?>/>
												<input type="checkbox" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> disabled="disabled"/>
											<?php else: ?>
												<input type="checkbox" value="1" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
"/>
											<?php endif; ?>
			<!-- 7.2.7. Condicion si el tipo de campo es COMBO-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'COMBO'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" disabled="disabled">
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>"/>
											<?php else: ?>
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] ) || isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )): ?>onchange="<?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] )): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif;  if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17];  endif; ?>;"<?php endif; ?>>
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>
											<?php endif; ?>
			<!-- 7.2.8. Condicion si el tipo de campo es BUSCADOR-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BUSCADOR'): ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 1 && $this->_tpl_vars['tipo'] != 2 && $this->_tpl_vars['tipo'] != 3): ?>
												<table>
													<tr>
														<td>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" onkeyup="activaBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event);" onclick="ocultaCombobusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" autocomplete="off"  on_change="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17]; ?>
"/>
														</td>
														<td>
												<!--<img onclick="botonBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/flecha_abajo.gif" style="height:12px;" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" />-->
														</td>
													</tr>
												</table>
												<div id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_div" style="visibility:hidden; display:none; position:absolute; z-index:3;">
													<select id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_sel" size="4" onclick="asignavalorbusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
');<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] != ''): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif; ?>" onkeydown="teclaCombo('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event)" datosDB="getBuscador.php?id=<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][0]; ?>
">
														<option>						</option>
													</select>
												</div>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>">
											<?php else: ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" />
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>">
											<?php endif; ?>
			<!-- 7.2.9. Condicion si el tipo de campo es FILE-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FILE'): ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != ''): ?>
												<a href="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" target="_blank" class="texto_form">Ver documento</a>
											<?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												&nbsp;
											<?php else: ?>
												<input type="file" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
"/>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
											    <br>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                            <?php endif; ?>
										<?php endif; ?>
										</td>
		<!-- 7.3. Condicion si existe la variable %$smarty.section.indice.first%%-->
										<?php if ($this->_sections['indice']['first']): ?>
											 <td width="158">&nbsp;</td>
										     <td width="155" class="texto_form"><?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][3]; ?>
</td>
											 <td width="193">
			<!-- 7.3.1. Condicion si el tipo de campo es CHAR-->
											 <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'CHAR'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
											 	<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12] > 60): ?>
													<textarea name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>><?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?></textarea>
												<?php else: ?>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?>/>
												<?php endif; ?>
			<!-- 7.3.2. Condicion si el tipo de campo es DATE-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'DATE'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="10" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?>onfocus="calendario(this)"<?php endif; ?> />
												<span class="text_legend">yy-mm-dd</span>
			<!-- 7.3.3. Condicion si el tipo de campo es TIME-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'TIME'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="8" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?> onkeypress="return validaTime(event, this.id)"<?php endif; ?> />
												<span class="text_legend">hh:mm:ss</span>
			<!-- 7.3.4. Condicion si el tipo de campo es INT O FLOAT-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'INT' || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> onkeypress="return validarNumero(event,<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>1<?php else: ?>0<?php endif; ?>,id);" onblur="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][15]; ?>
"/>
			<!-- 7.3.5. Condicion si el tipo de campo es PASSWORD-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'PASSWORD'): ?>
                                                <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
                                                <input type="password" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> />
			<!-- 7.3.6. Condicion si el tipo de campo es BINARY-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'BINARY'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> value="1"<?php else: ?> value="0"<?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> value="1" <?php else: ?> value="0" <?php endif; ?> <?php endif; ?>/>
													<input type="checkbox" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> disabled="disabled"/>
												<?php else: ?>
													<input type="checkbox" value="1" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> onclick="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][16]; ?>
"/>
												<?php endif; ?>
			<!-- 7.3.7. Condicion si el tipo de campo es COMBO-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'COMBO'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>

													<select  name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" disabled="disabled">
														<?php if ($this->_tpl_vars['tipo'] == 0): ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]), $this);?>

														<?php else: ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]), $this);?>

														<?php endif; ?>
													</select>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>"/>
												<?php else: ?>

													<select  name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" onclick="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][16]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] ) || isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )): ?>onchange="<?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] )): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif;  if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17];  endif; ?>;"<?php endif; ?>>
														<?php if ($this->_tpl_vars['tipo'] == 0): ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]), $this);?>

														<?php else: ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]), $this);?>

														<?php endif; ?>
													</select>
												<?php endif; ?>
			<!-- 7.3.8. Condicion si el tipo de campo es BUSCADOR-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'BUSCADOR'): ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 1 && $this->_tpl_vars['tipo'] != 2 && $this->_tpl_vars['tipo'] != 3): ?>
													<table>
														<tr>
															<td>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25]; ?>
" onkeyup="activaBuscador('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
',event);" onclick="ocultaCombobusc('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
')" autocomplete="off"  on_change="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][17]; ?>
"/>
															</td>
															<td>
													<!--<img onclick="botonBuscador('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
')" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/flecha_abajo.gif" style="height:12px;" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" />-->
															</td>
														</tr>
													</table>
													<div id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_div" style="visibility:hidden; display:none; position:absolute; z-index:3;">
														<select id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_sel" size="4" onclick="asignavalorbusc('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
');<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29] != ''): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif; ?>" onkeydown="teclaCombo('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
',event)" datosDB="getBuscador.php?id=<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0]; ?>
">
															<option>						</option>
														</select>
													</div>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>">
												<?php else: ?>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25]; ?>
" />
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>">
												<?php endif; ?>
			<!-- 7.3.9. Condicion si el tipo de campo es FILE-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FILE'): ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != ''): ?>
													<a href="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" target="_blank" class="texto_form">Ver documento</a>
												<?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>
													&nbsp;
												<?php else: ?>
													<input type="file" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
"/>
												<?php endif; ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
												    <br>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <?php endif; ?>
											<?php endif; ?>
											 </td>
										<?php endif; ?>
		<!-- 7.4. Condicion si son mas de 4 campos-->
									<?php else: ?>
										<td width="69" class="texto_form"><?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][3]; ?>
</td>
										<td width="175">
			<!-- 7.4.1. Condicion si el tipo de campo es CHAR-->
										<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'CHAR'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][12] > 60): ?>
												<textarea name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
"><?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?></textarea>
											<?php else: ?>
										<!--implementacion de <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '467'): ?>onkeyup="validaNoLista('this');"<?php endif; ?> Oscar 21.02.2018 para agregar buscador en campos de tipo char-->
												<input type="text" <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '467' && $this->_tpl_vars['tipo'] != '2'): ?>onkeyup="validaNoLista(this,event);" onchange="show_list_order_msg();"<?php endif; ?>
												<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '5' && $this->_tpl_vars['tipo'] != '2'): ?>onkeyup="validaLogin(this,event);"<?php endif; ?> name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
"
											/*Modificacion Oscar 2021 para evitar comas en ubicacion matriz y proveedor*/
												<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][20] != '' && $this->_tpl_vars['tipo'] != '2'): ?>
													onkeyup="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][20]; ?>
"
												<?php endif; ?>
											/*Fin de cambio Oscar 2021*/

												/>
												<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '467' || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '5'): ?>
													<div style="position:relative;top:0px;border:1px solid;width:110%;background:white;
													height:100px;overflow:auto;display:none;" id="res_ord_lis"></div>
												<?php endif; ?>
										<!--Fin de cambio-->
											<?php endif; ?>
			<!-- 7.4.2. Condicion si el tipo de campo es DATE-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'DATE'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="10" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?>onfocus="calendario(this)"<?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
"/>
											<span class="text_legend">yy-mm-dd</span>
			<!-- 7.4.3. Condicion si el tipo de campo es TIME-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'TIME'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="8" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?> onkeypress="return validaTime(event, this.id)"<?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
"/>
											<span class="text_legend">hh:mm:ss</span>
			<!-- 7.4.4. Condicion si el tipo de campo es INT o FLOAT-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'INT' || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> onkeypress="return validarNumero(event,<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>1<?php else: ?>0<?php endif; ?>,id);" tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
" onblur="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][15]; ?>
"/>
			<!-- 7.4.5. Condicion si el tipo de campo es PASSWORD-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'PASSWORD'): ?>
                                            <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
                                            <input type="password" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> />
			<!-- 7.4.6. Condicion si el tipo de campo es BINARY-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BINARY'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> value="1"<?php else: ?> value="0"<?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> value="1" <?php else: ?> value="0" <?php endif; ?> <?php endif; ?>/>
												<input type="checkbox" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> disabled="disabled" />
											<?php else: ?>
												<input type="checkbox" value="1" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
" onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
"/>
											<?php endif; ?>
			<!-- 7.4.7. Condicion si el tipo de campo es COMBO-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'COMBO'): ?>
										    <?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <br />
                                            <?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" disabled="disabled">
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>"/>
											<?php else: ?>
										<!--EXCEPCION : Implementacion Oscar 17.09.2019 para no poder editar el campo de proveedor en OC al ser edicion-->
												<select  name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" onclick="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][16]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" tabindex="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][4]; ?>
" <?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] ) || isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )): ?>onchange="<?php if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] )): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif;  if (isset ( $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17] )):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17];  endif; ?>;"<?php endif; ?>>
													<!--
												<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][0] == '95' && ( $this->_tpl_vars['tipo'] == '1' || $this->_tpl_vars['tipo'] == '2' )): ?> disabled="disabled"<?php endif; ?>-->
													<?php if ($this->_tpl_vars['tipo'] == 0): ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9]), $this);?>

													<?php else: ?>
														<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]), $this);?>

													<?php endif; ?>
												</select>
										<!--Fin de cambio Oscar 17.09.2019-->

											<?php endif; ?>
			<!-- 7.4.8. Condicion si el tipo de campo es BUSCADOR-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'BUSCADOR'): ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 1 && $this->_tpl_vars['tipo'] != 2 && $this->_tpl_vars['tipo'] != 3): ?>
												<table>
													<tr>
														<td>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" onkeyup="activaBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event);" onclick="ocultaCombobusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" autocomplete="off"  on_change="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][17]; ?>
"/>
														</td>
														<td>
												<!--<img onclick="botonBuscador('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
')" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/flecha_abajo.gif" style="height:12px;" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" />-->
														</td>
													</tr>
												</table>
												<div id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_div" style="visibility:hidden; display:none; position:absolute; z-index:3;">
													<select id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_sel" size="4" onclick="asignavalorbusc('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
');<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][29] != ''): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif; ?>" onkeydown="teclaCombo('<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
',event)" datosDB="getBuscador.php?id=<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][0]; ?>
">
														<option>						</option>
													</select>
												</div>
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>" >
											<?php else: ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][25]; ?>
" readonly />
												<input type="hidden" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10];  endif; ?>">
											<?php endif; ?>
			<!-- 7.4.9. Condicion si el tipo de campo es FILE-->
										<?php elseif ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][5] == 'FILE'): ?>

											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][10] != ''): ?>
												<a href="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][10]; ?>
" target="_blank" class="texto_form">Ver documento</a>
											<?php endif; ?>
											<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos'][$this->_sections['indice']['index']][8] == 0): ?>
												&nbsp;
											<?php else: ?>
												<input type="file" id="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][11]; ?>
" name="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][12]; ?>
"/>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['campos'][$this->_sections['indice']['index']][27] != ''): ?>
											     <br>
                                                <span class="<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                            <?php endif; ?>
										<?php endif; ?>
										</td>
		<!-- 7.5 Condiciones si el campo display es !='' -->
										<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][3] != ''): ?>
											<td width="158">&nbsp;</td>
										    <td width="155" class="texto_form"><?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][3]; ?>
</td>
											<td width="193" <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0] == '466'): ?>align="center"<?php endif; ?>>
											 <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'CHAR'): ?>
			<!-- 7.5.1. Condicion si el tipo de campo es CHAR-->
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
											 	<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12] > 60): ?>
													<textarea name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
"><?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?></textarea>
												<?php else: ?>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
" <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0] == '466'): ?>onkeyup="crea_previo_etiqueta();"<?php endif; ?>/>
												<?php endif; ?>
											<!--Excepcion : Implementacion Oscar 19.02.2019 para previo de etiqueta de productos-->
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0] == '466'): ?>
													<div id="previo_etiqueta" style="position:relative;top: 0;border:4px solid blue;width:400px;background:white;
													height:190px;overflow:none;margin:5px;display: none;">
													</div>
													<?php echo '
													<script type="text/javascript">
														function crea_previo_etiqueta(){
															if($("#nombre_etiqueta").val().length<=5){
																$("#previo_etiqueta").css("display","none");
																return false;
															}
															var ajax_previo_etq=ajaxR("../ajax/previoEtiquetaProducto.php?datos_etiqueta="+$("#nombre_etiqueta").val()+
																"&ord_lsta="+$("#orden_lista").val()+"&id_prod="+$("#id_productos").val());
															//var arr_mq=es_pd_mq.split("|");
															$("#previo_etiqueta").html(ajax_previo_etq);
															$("#previo_etiqueta").css("display","block");
														}
													</script>
													'; ?>

												<?php endif; ?>
											<!--Fin de Cambio Oscar 19.02.2018-->
			<!-- 7.5.2. Condicion si el tipo de campo es DATE-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'DATE'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="10" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?>onfocus="calendario(this)"<?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
"/>
												<span class="text_legend">yy-mm-dd</span>
			<!-- 7.5.3. Condicion si el tipo de campo es TIME-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'TIME'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="10" maxlength="8" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>readonly=""<?php else: ?> onkeypress="return validaTime(event, this.id)"<?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
"/>
												<span class="text_legend">hh:mm:ss</span>
			<!-- 7.5.4. Condicion si el tipo de campo es INT o FLOAT-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'INT' || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
                                        <!-- Excepcion : Implementacion Oscar 27.02.2018 se agrega if id eq 615 or 617 para hacer cambio de tipo de pago de usuario-->
												<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> onkeypress="return validarNumero(event,<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FLOAT'): ?>1<?php else: ?>0<?php endif; ?>,id);" tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
" onblur="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][15]; ?>
"
												<?php if (( $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0] == '615' || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0] == '617' )): ?> onclick="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][16]; ?>
" <?php endif; ?>/>
										<!--fin de cambio 27.02.2018-->
			<!-- 7.5.5. Condicion si el tipo de campo es PASSWORD-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'PASSWORD'): ?>
                                                <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
                                                <input type="password" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" maxlength="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][21]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" <?php endif; ?> <?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?> readonly="" <?php endif; ?> />
			<!-- 7.5.6. Condicion si el tipo de campo es BINARY-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'BINARY'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> value="1"<?php else: ?> value="0"<?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> value="1" <?php else: ?> value="0" <?php endif; ?> <?php endif; ?>/>
													<input type="checkbox" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> disabled="disabled" />
												<?php else: ?>
													<input type="checkbox" value="1" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" <?php if ($this->_tpl_vars['tipo'] == 0): ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9] != 0): ?> checked="checked" <?php endif; ?> <?php else: ?> <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != 0): ?> checked="checked" <?php endif; ?> <?php endif; ?> tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
" onclick="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][16]; ?>
"/>
												<?php endif; ?>
			<!-- 7.5.7. Condicion si el tipo de campo es COMBO-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'COMBO'): ?>
											    <?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                    <br />
                                                <?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>
													<select  name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_1" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" disabled="disabled">
														<?php if ($this->_tpl_vars['tipo'] == 0): ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]), $this);?>

														<?php else: ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]), $this);?>

														<?php endif; ?>
													</select>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>"/>
												<?php else: ?>
													<!--valor 29:<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29]; ?>
-->
													<select  name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" onclick="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][16]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" tabindex="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][4]; ?>
" <?php if (isset ( $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29] ) || isset ( $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][17] )): ?>onchange="<?php if (isset ( $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29] )): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif;  if (isset ( $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][17] )):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][17];  endif; ?>;"<?php endif; ?>>
														<?php if ($this->_tpl_vars['tipo'] == 0): ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9]), $this);?>

														<?php else: ?>
															<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][0],'output' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25][1],'selected' => $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]), $this);?>

														<?php endif; ?>
													</select>
												<?php endif; ?>
			<!-- 7.5.8. Condicion si el tipo de campo es BUSCADOR-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'BUSCADOR'): ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 1 && $this->_tpl_vars['tipo'] != 2 && $this->_tpl_vars['tipo'] != 3): ?>
													<table>
														<tr>
															<td>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25]; ?>
" onkeyup="activaBuscador('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
',event);" onclick="ocultaCombobusc('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
')" autocomplete="off" on_change="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][17]; ?>
"/>
															</td>
															<td>
													<!--<img onclick="botonBuscador('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
')" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/flecha_abajo.gif" style="height:12px;" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" />-->
															</td>
														</tr>
													</table>
													<div id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_div" style="visibility:hidden; display:none; position:absolute; z-index:3;">
														<select id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_sel" size="4" onclick="asignavalorbusc('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
');<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29] != ''): ?>actualizaDependiente('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][29]; ?>
', '<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][30]; ?>
', this.value, 'NO');<?php endif; ?>" onkeydown="teclaCombo('<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
',event)" datosDB="getBuscador.php?id=<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][0]; ?>
">
															<option>						</option>
														</select>
													</div>
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>">
												<?php else: ?>
													<input type="text" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
_txt" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" value="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][25]; ?>
" />
													<input type="hidden" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][9];  else:  echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10];  endif; ?>">
												<?php endif; ?>
			<!-- 7.5.9. Condicion si el tipo de campo es FILE-->
											<?php elseif ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][5] == 'FILE'): ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10] != ''): ?>
													<a href="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][10]; ?>
" target="_blank" class="texto_form">Ver documento</a>
												<?php endif; ?>
												<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) || $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][8] == 0): ?>
													&nbsp;
												<?php else: ?>
													<input type="file" id="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][11]; ?>
" name="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][2]; ?>
" size="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][12]; ?>
"/>
												<?php endif; ?>
												<?php if ($this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27] != ''): ?>
												    <br>
                                                    <span class="<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][28]; ?>
">(<?php echo $this->_tpl_vars['campos2'][$this->_sections['indice']['index']][27]; ?>
)</span>
                                                <?php endif; ?>
											<?php endif; ?>
											 </td>
										<?php endif; ?>
									<?php endif; ?>
								</tr>
	<!-- 8. Fin de iteracion de campos visibles -->
							<?php endfor; endif; ?>
						</table>
	<!-- 9. Iteracion de campos no visibles -->
						<?php unset($this->_sections['indice']);
$this->_sections['indice']['loop'] = is_array($_loop=$this->_tpl_vars['datosInvisibles']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indice']['name'] = 'indice';
$this->_sections['indice']['show'] = true;
$this->_sections['indice']['max'] = $this->_sections['indice']['loop'];
$this->_sections['indice']['step'] = 1;
$this->_sections['indice']['start'] = $this->_sections['indice']['step'] > 0 ? 0 : $this->_sections['indice']['loop']-1;
if ($this->_sections['indice']['show']) {
    $this->_sections['indice']['total'] = $this->_sections['indice']['loop'];
    if ($this->_sections['indice']['total'] == 0)
        $this->_sections['indice']['show'] = false;
} else
    $this->_sections['indice']['total'] = 0;
if ($this->_sections['indice']['show']):

            for ($this->_sections['indice']['index'] = $this->_sections['indice']['start'], $this->_sections['indice']['iteration'] = 1;
                 $this->_sections['indice']['iteration'] <= $this->_sections['indice']['total'];
                 $this->_sections['indice']['index'] += $this->_sections['indice']['step'], $this->_sections['indice']['iteration']++):
$this->_sections['indice']['rownum'] = $this->_sections['indice']['iteration'];
$this->_sections['indice']['index_prev'] = $this->_sections['indice']['index'] - $this->_sections['indice']['step'];
$this->_sections['indice']['index_next'] = $this->_sections['indice']['index'] + $this->_sections['indice']['step'];
$this->_sections['indice']['first']      = ($this->_sections['indice']['iteration'] == 1);
$this->_sections['indice']['last']       = ($this->_sections['indice']['iteration'] == $this->_sections['indice']['total']);
?>
							<input type="hidden" name="<?php echo $this->_tpl_vars['datosInvisibles'][$this->_sections['indice']['index']][1]; ?>
" id="<?php echo $this->_tpl_vars['datosInvisibles'][$this->_sections['indice']['index']][1]; ?>
" value="<?php if ($this->_tpl_vars['tipo'] == 0):  echo $this->_tpl_vars['datosInvisibles'][$this->_sections['indice']['index']][3];  else:  echo $this->_tpl_vars['datosInvisibles'][$this->_sections['indice']['index']][4];  endif; ?>"/>
						<?php endfor; endif; ?>
                 	</div>
				<?php endif; ?>
	<!-- 10. Seccion de botones de opciones-->
               <div class="Botones">
               	<!-- Excepcion : Implementacion para exportar/importar lista de precios -->
               		<?php if ($this->_tpl_vars['tabla'] == 'ec_precios'): ?>
               			<a href="#" class="fl" title="Exportar" onclick="window.open('../especiales/listaCSV.php?id_precio=<?php echo $this->_tpl_vars['llave']; ?>
')">Exportar </a>
               			<a href="#" class="fr" title="Importar" onclick="location.href='../especiales/importaCSV.php?id_precio=<?php echo $this->_tpl_vars['llave']; ?>
'">Importar </a>
               			<a href="#" class="fl" title="Exportar para mayoreo"
               			onclick='window.open("../especiales/listaCSV.php?id_precio=<?php echo $this->_tpl_vars['llave']; ?>
&#x26para_mayoreo=1")'>Exportar<br>Mayoreo</a>
               		<?php endif; ?>

               	<!-- Excepcion : Implementacion para exportar/importar estacionalidades -->
               		<?php if ($this->_tpl_vars['tabla'] == 'ec_estacionalidad'): ?>
               			<a href="#" class="fl" title="Exportar" onclick="window.open('../especiales/listaEstCSV.php?id_estacionalidad=<?php echo $this->_tpl_vars['llave']; ?>
')">Exportar </a>
               			<a href="#" class="fr" title="Importar" onclick="location.href='../especiales/importaEstCSV.php?id_estacionalidad=<?php echo $this->_tpl_vars['llave']; ?>
'">Importar </a>
               		<?php endif; ?>

               	<!-- No se usa -->
                    <?php if ($this->_tpl_vars['tabla'] == 'ec_autorizacion' && $this->_tpl_vars['tipo'] == 1): ?>

						<a href="#"  class="fr b" title="Rechazar"  onclick="document.getElementById('autorizado').checked=false;valida()">Rechazar</a>
                    	<a href="#"  class="fr b" title="Autorizar"  onclick="document.getElementById('autorizado').checked=true;valida()">Autorizar</a>

                    <?php endif; ?>

               </div>

<!-- 11. Implementacion de ventana emergente para avisos Oscar 11.04.2018-->
	<div id="ventana_emergente_global" style="position:absolute;z-index:1000;width:100%;height:250%;background:rgba(0,0,0,.8);top:0;left:0;display:none;">
		<p align="right"><img src="../../img/especiales/cierra.png" height="50px" onclick="document.getElementById('ventana_emergente_global').style.display='none';" id="btn_cerrar_emergente_global"></p>
		<p id="contenido_emergente_global"></p><!--En este div se cargan los datos o avisos que se quieren mostrar en pantalla-->
	</div>

<!--Fin de implementacion de ventana emergenete-->


<!-- 12. Seccion de grids-->
	<?php unset($this->_sections['x']);
$this->_sections['x']['loop'] = is_array($_loop=$this->_tpl_vars['gridArray']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['x']['name'] = 'x';
$this->_sections['x']['show'] = true;
$this->_sections['x']['max'] = $this->_sections['x']['loop'];
$this->_sections['x']['step'] = 1;
$this->_sections['x']['start'] = $this->_sections['x']['step'] > 0 ? 0 : $this->_sections['x']['loop']-1;
if ($this->_sections['x']['show']) {
    $this->_sections['x']['total'] = $this->_sections['x']['loop'];
    if ($this->_sections['x']['total'] == 0)
        $this->_sections['x']['show'] = false;
} else
    $this->_sections['x']['total'] = 0;
if ($this->_sections['x']['show']):

            for ($this->_sections['x']['index'] = $this->_sections['x']['start'], $this->_sections['x']['iteration'] = 1;
                 $this->_sections['x']['iteration'] <= $this->_sections['x']['total'];
                 $this->_sections['x']['index'] += $this->_sections['x']['step'], $this->_sections['x']['iteration']++):
$this->_sections['x']['rownum'] = $this->_sections['x']['iteration'];
$this->_sections['x']['index_prev'] = $this->_sections['x']['index'] - $this->_sections['x']['step'];
$this->_sections['x']['index_next'] = $this->_sections['x']['index'] + $this->_sections['x']['step'];
$this->_sections['x']['first']      = ($this->_sections['x']['iteration'] == 1);
$this->_sections['x']['last']       = ($this->_sections['x']['iteration'] == $this->_sections['x']['total']);
?>
		<input type="hidden" name="file<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
" value="">
	<!-- Impementacion Oscar 17-09-2020 para insertar separarador de venta en linea productos-->
    		<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '60'): ?>
		<div id="bg_seccion" style="">

    			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/seccionProductosLinea.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   
		</div>
			<?php endif; ?>
    <!-- Fin de cambio Oscar 17-09-2020 -->
		<div id="bg_seccion">
    		
    		<div class="name_module" align="center">
	
    

    			<table>
					<tr valign="middle">
						<td><p class="margen"><?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][2]; ?>
<img src="../../img/especiales/add.png" id="desp_<?php echo $this->_sections['x']['index']; ?>
" width="35px" style="top:20px;position:relative;padding:10px;" onClick="despliega(1,<?php echo $this->_sections['x']['index']; ?>
);"></p></td>

					</tr>
				</table><br>
    		</div>
    <!-- 12.1. Implementacion de Oscar 12/02/2018 Para buscador de grids-->
    	<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][21] == '1'): ?><!--condicionamos que solo muestre buscador si el grid asi lo marca en la BD  && ($tipo eq '0' || $tipo eq '1')-->
    		<br><br>
			<div style="border:0;display:none;" id="div_busc_grid_<?php echo $this->_sections['x']['index']; ?>
">
				<span align="left" style="position:absolute;padding:0; font-size:15px; width : 300px;"><b>Buscador:</b></span>
				<input type="text" id="b_g_<?php echo $this->_sections['x']['index']; ?>
" style="width:50%;" onkeyup="activa_buscador_general(this,'<?php echo $this->_sections['x']['index']; ?>
','<?php echo $this->_tpl_vars['tabla']; ?>
','<?php echo $this->_tpl_vars['no_tabla']; ?>
',event ,'<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
', '<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
');">
				<input type="text" id="cantidad_<?php echo $this->_sections['x']['index']; ?>
" style="width:3%;<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '43' || $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '24'): ?>display:none;<?php endif; ?>" onkeyup="validarEv(event,<?php echo $this->_sections['x']['index']; ?>
);">
				<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '43' || $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '24'): ?>
					<img src="../../img/busca_gral.png" height="50px;" style="top:17px;position:relative;" id="img_add_<?php echo $this->_sections['x']['index']; ?>
">
				<?php else: ?>
					<img src="../../img/icono-agregar.png" height="50px;" style="top:17px;position:relative;" id="img_add_<?php echo $this->_sections['x']['index']; ?>
">
				<?php endif; ?>
				<!--<input type="button" value="agregar" id="img_add_<?php echo $this->_sections['x']['index']; ?>
">-->
				<div style="width:51.5%;height:200px;background:white;left:0px;position:relative;border:1px solid;display:none;overflow:auto;" id="res_bus_glob_<?php echo $this->_sections['x']['index']; ?>
"></div>
				<input type="hidden" value="" id="aux_1_<?php echo $this->_sections['x']['index']; ?>
"><!--id-->
				<input type="hidden" value="" id="aux_2_<?php echo $this->_sections['x']['index']; ?>
"><!--descripcion-->
			</div>
	<!--Implementación de Oscar 16.05.2018 para exportar/importar lista de estacionalidades
	<?php if ($this->_tpl_vars['tabla'] == 'ec_estacionalidad' && $this->_tpl_vars['no_tabla'] == '0'): ?>
		<div style="position:absolute;bottom:-40%;z-index:3;">
			<input type="button" id="bot_imp_estac" onclick="importa_exporta_estacionalidades(2);" value="Importar estacionalidad" style="padding:5px;border-radius:5px;">
			<input type="button"  onclick="importa_exporta_estacionalidades(1);" value="Exportar estacionalidad"style="padding:5px;border-radius:5px;">

			<form class="form-inline">
				<input type="file" id="imp_csv_prd" style="display:none;">
				<p class="nom_csv">
    				<input type="text" id="txt_info_csv" style="display:none;" disabled>
    			</p>
    			<input type="button" id="submit-file" style="display:none;" class="bot_imp" value="Enviar">
			</form>
		</div>
	<?php endif; ?>
	fin de implementación OScar 16.05.2018-->


			<?php echo '
		<!--/*********************Implementación de importar/exportar estacionalidades con excel Oscar 16.05.2018******************************************************/-->
			<!--incluimos libreria para poner csv en temporal-->
				<script type="text/JavaScript">
    // 12.2. Funcion que realiza la busqueda de registros por medio de archivo /code/ajax/buscadorGlobal.php
					var tmp_busc="";
					function activa_buscador_general(t,nu,ta,nt,e, grid_id, grid_nombre){//Se agrega grid_id, grid_nombre Oscar 17-09-2020
						if(e.keyCode==40){
							if($(\'#r_1\')){
								resalta_busc(0,1);
							}
							return false;
						}
					'; ?>

						/*var id_gr='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
';//capturamos el id del grid
						var posic='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
';//capturamos el nombre del grid*/
			/*Implementacion Oscar 2020 para obtener valor de llave primaria de la pantalla*/
						var llave_primaria = '<?php echo $this->_tpl_vars['llave']; ?>
';
						//alert('<?php echo $this->_tpl_vars['tipo']; ?>
');
					<?php echo '
				//Excepcion : Implementacion Oscar 09.09.2019 para mandar el id de proveedor en el buscador de detalle de ordenes de compra
						var id_condicion="";
						if(grid_id == 5){
							id_condicion=$("#id_proveedor").val();
						}
				/**/
					//sacamos las filas existentes en el grid
						var fil_ex=($(\'#\'+ grid_nombre +\' tr\').length-5);
						//alert(t.value+\', \'+ta+\', \'+nt+\', \'+id_gr+posic);
					//sacamos valor del buscador
						var txt_busc=t.value;
						if(txt_busc.length<3){
							$("#res_bus_glob_"+nu).css("display","none");
							return false;
						}
						$.ajax({
							type:\'post\',
							url:\'../ajax/buscadorGlobal.php\',
							cache:false,
							data:{clave:txt_busc,tabla:ta,no_t:nt, id : grid_id, fil_exist:fil_ex,n_d:nu,
								grid_nom : grid_nombre, id_cond:id_condicion,
								llave_principal : llave_primaria/*Implementacion Oscar 2020 para enviar valor de llave primaria de la pantalla*/
							},
							success:function(datos){
								var respuesta=datos.split(\'|\');
								if(respuesta[0]!=\'ok\'){
									alert(\'Error!!!....\\n\'+datos+\'   \'+txt_busc);
									return false;
								}else{
									//alert(\'ok\');
									$("#res_bus_glob_"+nu).html(respuesta[1]);
									$("#res_bus_glob_"+nu).css("display","block");
								}
							}
						});
					}
    // 12.3. Funcion que valida accion de teclas sobre opciones
					function eje(e,num_res,id_opc){
						//alert(e.keyCode+num_res+id_opc);
							//alert(e.keyCode);
							if(e.keyCode==40){
								if($("#r_"+num_res)){
									resalta_busc(num_res,1);
								}
							}
							if(e.keyCode==38){
								if($("#r_"+num_res)){
									resalta_busc(num_res,-1);
								}
							}
							if(e.keyCode==13){
								document.getElementById("r_"+num_res).click();
							}
							return true;
					}

	//12.4 Funcion que resalta hover
					function resalta_busc(actual,flag){
						var nvo=actual+(flag);
					//alert(nvo);
					//
						if(actual>0){
							$("#r_"+actual).css("background","white");
						}
						if(actual==1&&flag==-1){
							$("#b_g_0").select();
						}
						$("#r_"+nvo).css("background","#6BFF33");
						$("#r_"+nvo).focus();
						return false;
					}

	//12.5. Funcion que pone producto en buscador y enfoca a cantidad
					function insertaBuscador(n_b, valores, grid_id, grid_nombre){
						//alert(n_b+"\\n"+valores);
						if(valores=="" || valores==null){
							alert(\'No hay valores válidos\');
							return false;
						}
						$("#res_bus_glob_"+n_b).html(\'\');//limpiamos resultados
						$("#res_bus_glob_"+n_b).css("display","none");//ocultamos div de resultados
						$("#cantidad_"+n_b).val("1");//asignamos uno por default a cantidad
						$("#cantidad_"+n_b).select();
					//preparamos el boton para agregar producto seleccionado
						'; ?>

						/*'<?php $this->assign('count_tmp', "'+n_b+'"); ?>';
						alert('<?php echo $this->_tpl_vars['count_tmp']; ?>
');
						var id_gr='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['count_tmp']['index']][0]; ?>
';//capturamos el id del grid
						var posic='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['count_tmp']['index']][1]; ?>
';//capturamos el nombre del grid
						alert(posic);*/
					<?php echo '
				//validamos que el registro no este en el grid
						var valida_gr=validaRegGrid(grid_id,n_b,grid_nombre);
						if(valida_gr==\'1\'){
//							alert(\'El registro ya se encuentra en el grid...\');
							return false;
						}else{
							//alert(\'no esta\');
						//asignamos valores al buscador
							document.getElementById("b_g_"+n_b).value=valores;
						//descomponemos descripcion de buscador
							var arr=document.getElementById(\'b_g_\'+n_b).value.split("°");
						/***********************************implementación de confirmación de movimiento de almacen prod c/maquila Oscar 11.04.2018*/
							if(grid_id == 9){//si es el grid de movimientos de almacén entra al proceso de validación...
								var es_pd_mq=ajaxR("../ajax/validaMovProdMaq.php?id_pr="+arr[0]);
								var arr_mq=es_pd_mq.split("|");
								if(arr_mq[0]==\'ok\'){
									if(arr_mq[1]==\'maquilado\'){
									//se informa al usuario que el productos es maquilado, y se pregunta si desea agregarlo
										var cf_mq=confirm(arr_mq[2]);
										if(cf_mq==false){
											$("#b_g_0").val("");
											$("#b_g_0").focus();
											return false;
										}
									}
								}else{
									alert("Error:\\n"+es_pd_mq);
									return false;
								}
							}
						//fin de implementación 11.04.2018
					//sacamos las filas existentes en el grid
						var fil_ex=($(\'#\'+grid_nombre+\' tr\').length-5);
						$.ajax({
							type:\'post\',
							url:\'../ajax/buscadorGlobal.php\',
							cache:false,
							data:{flag:1,id : grid_id, fil_exist:fil_ex,clave:arr[1],n_d:n_b},
							success:function(dat){
								var resul=dat.split("|");
								if(resul[0]!=\'ok\'){
									alert("Error!!!\\n\\n"+dat)
								}else{
									$("#img_add_"+n_b).attr("onclick",resul[2]);
								}
							}
							});
						}
						//alert("ya cambió evento");
						//document.getElementById(\'img_add_\'+n_b).style.display="none";
					}

	//12.6. Funcion que valida evento click o intro
					function validarEv(e,nu_bus){
						if(e.keyCode==13||e==\'click\'){
							$("#img_add_"+nu_bus).click();
						}

					}
	//12.7. Funcion que valida si el registro ya existe en en grid
					function validaRegGrid(id_g,n_b,posic){
						//alert(id_g+":"+n_b+":"+posic);
						$.ajax({
							type:\'post\',
							url:\'../ajax/buscadorGlobal.php\',
							cache:false,
							data:{id:id_g,flag:-2},
							success:function(dat){
								//alert(\'dat: \'+dat);
								var arr_re=dat.split("|");
								if(arr_re[0]!=\'ok\'){
									alert("Error al mandar validación de registro");
								}
							//sacamos el numero de registros
								var fil_ex=($(\'#\'+posic+\' tr\').length-4);
								var arr=document.getElementById(\'b_g_\'+n_b).value.split("°");
								for(var i=0;i<=fil_ex;i++){//se cambia menor o igual condicion del ciclo for Oscar 26.03.2018
									//alert(posic+\'_\'+arr_re[1]+"_"+i);
									if( id_g == 9 ){
										arr_re[1] = 8;
										arr[0] = arr[2];
									}

									if(document.getElementById(posic+"_"+arr_re[1]+"_"+i)){//campo a comparar
									var tmp=document.getElementById(posic+"_"+arr_re[1]+"_"+i).innerHTML;
									//alert( tmp );
										if(tmp==arr[0]||tmp==arr[1]){
											$("#"+posic+"_"+arr_re[2]+"_"+i).click();
											//alert("resalta:"+"#"+posic+"_"+arr_re[2]+"_"+i);
											$("#"+posic+"_"+arr_re[2]+"_"+i).select();//#c
											$("#"+posic+"_"+arr_re[2]+"_"+i).focus();//#c
											return \'1\';
										}
									}
								}//fin de for i
							//Excepcion 
								if(id_g==43 || id_g==24){
									alert("El producto no fue encontrado!!!");
									$("#cantidad_0").val(\'\');
									$("#b_g_0").select();
								}
							}
						});
					}
				</script>
	<!-- 12.8. Estilos CSS del buscador de grids -->
				<style type="text/css">
					.opcion{
						height: 30px;
					}
					.opcion:hover{
						background:#6BFF33;
					}
				</style>
			'; ?>

		<?php endif; ?>
	<!--Fin de Cambio 12-02-2017-->

	<!-- 12.9. Div que contiene a cada Grid -->
			<div style="border:0px solid;display:none;height:100px;position:relative;z-index:100;" id="div_grid_<?php echo $this->_sections['x']['index']; ?>
"><!--12.9.1. Implementacion de Oscar 31.07.2018 para no mostrar la informacion de todos los grids   (se quita la clase class="tablas-res_")-->
		<!--Excepcion : para mostrar el buscador por codigo de barras en transferencias -->
				<?php if ($this->_tpl_vars['tabla'] == 'ec_transferencias' && $this->_tpl_vars['no_tabla'] == '3'): ?>

					<?php echo '
					<style>

						.buscaProductoBar{
							position:relative;
							top:-20px;
						}

						.inProductoBar{
							width:200px !important;
						}

					</style>

					<script>


						function validaBar(obj)
						{
							'; ?>

								var llave='<?php echo $this->_tpl_vars['llave']; ?>
';
							<?php echo '


							var url="../ajax/validaProductoVer.php?id_transferencia="+llave+"&code="+obj.value;

							var res=ajaxR(url);

							var aux=res.split(\'|\');

							if(aux[0] != \'exito\')
							{
								alert(res);
								return false;
							}


							var id_prod=aux[1];


							var num=NumFilas(\'transferenciasProductos\');

							for(var i=0;i<num;i++)
							{
								if(celdaValorXY(\'transferenciasProductos\', 2, i) == id_prod)
								{
									aux=celdaValorXY(\'transferenciasProductos\', 7, i);
									aux=parseInt(aux);
									aux++;
									valorXY(\'transferenciasProductos\', 7, i, aux);
									htmlXY(\'transferenciasProductos\', 7, i, aux);
								}
							}


							obj.value="";
							obj.focus();


						}

						function validaEntBar(eve, obj)
						{
							var key=0;
							key=(eve.which) ? eve.which : eve.keyCode;


							if(key == 13)
							{
								validaBar(obj);
							}

						}



					</script>



					'; ?>


					<div class="buscaProductoBar">
						Código de barras:
						<input type="text" name="codigo" class="inProductoBar" onkeyup="validaEntBar(event, this)">
						<input type="button" class="boton" onclick="validaBar(codigo)" value="Validar">
					</div>

				<?php endif; ?>

		<!--Excepcion : para mostrar el buscador por codigo de barras en transferencias -->
				<?php if ($this->_tpl_vars['tabla'] == 'ec_transferencias' && $this->_tpl_vars['no_tabla'] == '0'): ?>


					<?php echo '
					<style>

						.buscaProductoBar{
							position:relative;
							top:-20px;
						}

						.inProductoBar{
							width:200px !important;
						}

					</style>

					<script>


						function validaBar(obj)
						{
							'; ?>

								var llave='<?php echo $this->_tpl_vars['llave']; ?>
';
							<?php echo '


							var url="../ajax/validaProductoTrans.php?code="+obj.value;

							var res=ajaxR(url);

							var aux=res.split(\'|\');

							if(aux[0] != \'exito\')
							{
								alert(res);
								return false;
							}


							//alert(aux[2]);

							var id_prod=aux[1];
							var nver=0;

							var num=NumFilas(\'transferenciasProductos\');

							for(var i=0;i<num;i++)
							{
								if(celdaValorXY(\'transferenciasProductos\', 2, i) == id_prod)
								{
									aux=celdaValorXY(\'transferenciasProductos\', 7, i);
									aux=parseInt(aux);
									aux++;
									valorXY(\'transferenciasProductos\', 7, i, aux);
									htmlXY(\'transferenciasProductos\', 7, i, aux);
									nver++;
								}
							}


							if(nver == 0)
							{
								InsertaFilaNoVal(\'transferenciasProductos\');


								valorXYNoOnChange(\'transferenciasProductos\', 2, num, aux[1]);
								valorXYNoOnChange(\'transferenciasProductos\', 3, num, aux[2]);
								valorXY(\'transferenciasProductos\', 6, num, -1);
								valorXY(\'transferenciasProductos\', 10, num, 1);
								valorXY(\'transferenciasProductos\', 7, num, 1);
								valorXY(\'transferenciasProductos\', 8, num, 1);

								//htmlXY(\'transferenciasProductos\', 3, num, aux[2]);

							}

							obj.value="";
							obj.focus();


						}

						function validaEntBar(eve, obj)
						{
							var key=0;
							key=(eve.which) ? eve.which : eve.keyCode;


							if(key == 13)
							{
								validaBar(obj);
							}

						}



					</script>



					'; ?>



					<div class="buscaProductoBar">
						C&oacute;digo de barras:
						<input type="text" name="codigo" class="inProductoBar" onkeyup="validaEntBar(event, this)">
						<input type="button" class="boton" onclick="validaBar(codigo)" value="Validar">
					</div>

				<?php endif; ?>

				<?php if (( $this->_tpl_vars['tabla64'] == 'ZWNfdHJhbnNmZXJlbmNpYXM=' && $this->_tpl_vars['tipo'] == '0' ) || ( ( $this->_tpl_vars['tipo'] == 0 || $this->_tpl_vars['tipo'] == 1 ) && $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][6] != 'false' )): ?>

            	<div class="submenu">

					<?php if ($this->_tpl_vars['tabla64'] == 'ZWNfdHJhbnNmZXJlbmNpYXM=NO' && $this->_tpl_vars['tipo'] == '0'): ?>
						<div class="productos-ojo" title="Mostrar producto">
							<img src="<?php echo $this->_tpl_vars['rooturl']; ?>
/img/mproducto.png" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" onclick="creaListado()">
            				<p>Productos</p>
            			</div>
            		<?php endif; ?>
	<!-- 12.10. Menu lateral boton para agregar fila en el grid-->
					<?php if (( $this->_tpl_vars['tipo'] == 0 || $this->_tpl_vars['tipo'] == 1 ) && $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][6] != 'false'): ?>
						<div class="Fila" title="clic para agregar un nuevo registro" onclick="InsertaFila('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
')">
							<p>Nueva Fila</p>
						</div>
					<?php endif; ?>
				</div>

				<?php endif; ?>

 				<!--Termina el menu lateral de los conetenidos-->

 			<div id="cosa" align="center"><!--style="display:none;"-->

	<!--Excepcion : Implementacion Oscar 22.11.2019 para mostrar el mensaje en el grid de pagos-->
				<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '7'): ?>
					<p align="center" style="color:red;font-size:25px;">Si modifica uno de estos pagos no se verá reflejado; hay que modificarlo manualmente en los pagos a proveedor por partida</p>
				<?php endif; ?>
	<!--Excepcion : Implementacion Oscar 22.11.2019 para mostrar el mensaje en el grid de pagos-->
	
				<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '65'): ?>
					<p align="left" style="font-size:25px;">Imagen Principal</p>
					<div align="center" style="font-size:20px; position: relative; left:0; width:80%; border: 1px solid; padding: 10px;
					background:gray; color: white;">
						
						<label>Nombre : </label>
						<input type="text" id="nombre_img_principal_editable" style="width : 250px;"
						onchange="cambia_valor_nombre_img_principal();">
						
						<label>Extension : </label>
						<select id="formato_imagen_principal" style="padding:10px;"
						onchange="cambia_valor_nombre_img_principal();">
							<?php unset($this->_sections['x_formatos']);
$this->_sections['x_formatos']['loop'] = is_array($_loop=$this->_tpl_vars['formatos_imagenes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['x_formatos']['name'] = 'x_formatos';
$this->_sections['x_formatos']['show'] = true;
$this->_sections['x_formatos']['max'] = $this->_sections['x_formatos']['loop'];
$this->_sections['x_formatos']['step'] = 1;
$this->_sections['x_formatos']['start'] = $this->_sections['x_formatos']['step'] > 0 ? 0 : $this->_sections['x_formatos']['loop']-1;
if ($this->_sections['x_formatos']['show']) {
    $this->_sections['x_formatos']['total'] = $this->_sections['x_formatos']['loop'];
    if ($this->_sections['x_formatos']['total'] == 0)
        $this->_sections['x_formatos']['show'] = false;
} else
    $this->_sections['x_formatos']['total'] = 0;
if ($this->_sections['x_formatos']['show']):

            for ($this->_sections['x_formatos']['index'] = $this->_sections['x_formatos']['start'], $this->_sections['x_formatos']['iteration'] = 1;
                 $this->_sections['x_formatos']['iteration'] <= $this->_sections['x_formatos']['total'];
                 $this->_sections['x_formatos']['index'] += $this->_sections['x_formatos']['step'], $this->_sections['x_formatos']['iteration']++):
$this->_sections['x_formatos']['rownum'] = $this->_sections['x_formatos']['iteration'];
$this->_sections['x_formatos']['index_prev'] = $this->_sections['x_formatos']['index'] - $this->_sections['x_formatos']['step'];
$this->_sections['x_formatos']['index_next'] = $this->_sections['x_formatos']['index'] + $this->_sections['x_formatos']['step'];
$this->_sections['x_formatos']['first']      = ($this->_sections['x_formatos']['iteration'] == 1);
$this->_sections['x_formatos']['last']       = ($this->_sections['x_formatos']['iteration'] == $this->_sections['x_formatos']['total']);
?>
								<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['formatos_imagenes'][$this->_sections['x_formatos']['index']][0],'output' => $this->_tpl_vars['formatos_imagenes'][$this->_sections['x_formatos']['index']][1]), $this);?>

							<?php endfor; endif; ?>
						</select>
						<label>  </label>
						<input type="text" name="nombre_img_principal" id="nombre_img_principal" value="<?php echo $this->_tpl_vars['precios_especiales'][7]; ?>
"
						style="width : 250px;" readonly placeholder="Nombre Completo">
						<label> <button type="button" onclick="limpia_valor_img_principal();">X</button> </label><br>
					</div>
					<p align="left" style="font-size:25px;">Imagenes Adicionales</p>
					<?php echo '
						<script type="text/javascript">
							function cambia_valor_nombre_img_principal(){
								var nom_img_princ = $("#nombre_img_principal_editable").val().trim();
								var nom_form_img_princ =  $("#formato_imagen_principal option:selected").text().trim();
								$("#nombre_img_principal").val( nom_img_princ + \'.\' + nom_form_img_princ);
							}
							function limpia_valor_img_principal(){
								if(!confirm("Realmente desea eliminar el valor de la imagen principal") ){
									return false;
								}
								$("#nombre_img_principal_editable").val(\'\');
								$("#nombre_img_principal").val(\'\');
							}
						</script>
					'; ?>


				<?php endif; ?>

	<!--Fin de cambio Oscar 22.11.2019-->
 		<!-- 12.11. Tabla del grid (contiene datos de cabecera y configuracion del grid)-->
 			<!--12.11.1. Se implementa <?php echo $this->_tpl_vars['filtro_fechas_1']; ?>
 en el atributo datos para filtrar grid por rango Oscar 14.08.2018-->
 				<table id="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
" cellpadding="0" cellspacing="0" border="1" Alto="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][9]; ?>
"
                   conScroll="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][8]; ?>
" validaNuevo="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][6]; ?>
" despuesInsertar="" AltoCelda="25" auxiliar="0" ruta="../../img/grid/"
                   validaElimina="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][7]; ?>
" Datos="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][10];  echo $this->_tpl_vars['llave']; ?>
&campoid=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][16]; ?>
&id_grid=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
&id_PF=<?php echo $this->_tpl_vars['id_PF']; ?>
&rango_fechas=<?php echo $this->_tpl_vars['filtro_fechas_1']; ?>
"
                   verFooter="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][12]; ?>
" guardaEn="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][11];  echo $this->_tpl_vars['llave']; ?>
&campoid=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][16]; ?>
&id_grid=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
&make=<?php echo $this->_tpl_vars['tipo']; ?>
"
                   listado="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][13]; ?>
" class="tabla_Grid_RC" scrollH="N" despuesEliminar="" >
                	<tr class="HeaderCell">
                <!-- 12.11.2. Modificacion de Oscar 07.06.2019 para mandar la llave al archivo que carga los combos del grid en atributo datosDB-->
                    	<?php unset($this->_sections['y']);
$this->_sections['y']['loop'] = is_array($_loop=$this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['y']['name'] = 'y';
$this->_sections['y']['show'] = true;
$this->_sections['y']['max'] = $this->_sections['y']['loop'];
$this->_sections['y']['step'] = 1;
$this->_sections['y']['start'] = $this->_sections['y']['step'] > 0 ? 0 : $this->_sections['y']['loop']-1;
if ($this->_sections['y']['show']) {
    $this->_sections['y']['total'] = $this->_sections['y']['loop'];
    if ($this->_sections['y']['total'] == 0)
        $this->_sections['y']['show'] = false;
} else
    $this->_sections['y']['total'] = 0;
if ($this->_sections['y']['show']):

            for ($this->_sections['y']['index'] = $this->_sections['y']['start'], $this->_sections['y']['iteration'] = 1;
                 $this->_sections['y']['iteration'] <= $this->_sections['y']['total'];
                 $this->_sections['y']['index'] += $this->_sections['y']['step'], $this->_sections['y']['iteration']++):
$this->_sections['y']['rownum'] = $this->_sections['y']['iteration'];
$this->_sections['y']['index_prev'] = $this->_sections['y']['index'] - $this->_sections['y']['step'];
$this->_sections['y']['index_next'] = $this->_sections['y']['index'] + $this->_sections['y']['step'];
$this->_sections['y']['first']      = ($this->_sections['y']['iteration'] == 1);
$this->_sections['y']['last']       = ($this->_sections['y']['iteration'] == $this->_sections['y']['total']);
?>
                    		<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][3] != 'libre'): ?>
	                        	<td tipo="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][3]; ?>
" modificable="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][4]; ?>
" mascara="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][5]; ?>
" align="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][6]; ?>
" formula="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][7]; ?>
" datosdb="../grid/getCombo.php?id=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][0]; ?>
&llave=<?php echo $this->_tpl_vars['llave']; ?>
" depende="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][9]; ?>
" onChange="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][10]; ?>
" largo_combo="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][11]; ?>
" verSumatoria="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][12]; ?>
" valida="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][13]; ?>
" onkey="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][14]; ?>
" inicial="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][15]; ?>
" width="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][17]; ?>
" offsetwidth="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][17]; ?>
" on_Click="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][19]; ?>
" multiseleccion="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][20]; ?>
" requerido="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][16]; ?>
"><?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][1]; ?>
</td>
	                        <?php else: ?>
	                        	<td tipo="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][3]; ?>
" modificable="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][4]; ?>
" mascara="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][5]; ?>
" align="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][6]; ?>
" formula="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][7]; ?>
" datosdb="../grid/getCombo.php?id=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][0]; ?>
&llave=<?php echo $this->_tpl_vars['llave']; ?>
" depende="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][9]; ?>
" onChange="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][10]; ?>
" largo_combo="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][11]; ?>
" verSumatoria="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][12]; ?>
" valida="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][13]; ?>
" onkey="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][14]; ?>
" inicial="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][15]; ?>
" width="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][17]; ?>
" offsetwidth="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][17]; ?>
" on_Click="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][19]; ?>
" valor="<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][18]; ?>
"><?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][20][$this->_sections['y']['index']][1]; ?>
</td>
	                        <?php endif; ?>
                    	<?php endfor; endif; ?>
                   <!--Fin de cambio Oscar 07.06.2019-->

                   <!--Excepcion : Implementacion Oscar 10.06.2019 para agregar el boton de direccion al listado de detalle de ediciones de movimientos caja-->
                 	<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '54'): ?>
                   		<td width="56" offsetWidth="56" tipo="libre" valor="Ver detalle" align="center" campoBD='<?php echo $this->_tpl_vars['valuesEncGrid'][$this->_sections['x']['index']]; ?>
'>
							<img class="autorizarmini" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/autorizarmini.png" width="22" height="22" border="0" onclick="ver_detalle_mov_caja('#')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Autorizar" title="De clic para ver los cambios en el movimiento"/>
						</td>
                   	<?php endif; ?>
                   <!--Fin de cambio Oscar Oscar 10.06.2019-->
    	            </tr>
        	 	</table>
             </div>
             <script>
             // 12.12. Se mandan cargar los datos mediante la funcion CargaGrid(%$nombre,$id_grid%%);
                CargaGrid('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
','<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
');
//implementacion Oscar 2020 acordión horizontal en grid proveedor-producto
				<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0] == '48'): ?>
	                hide_grid_accordion( 6, 'proveedorProducto' );
	                hide_grid_accordion( 7, 'proveedorProducto' );
	                hide_grid_accordion( 8, 'proveedorProducto' );
	                hide_grid_accordion( 11, 'proveedorProducto' );
	                hide_grid_accordion( 12, 'proveedorProducto' );
	                hide_grid_accordion( 15, 'proveedorProducto' );
	                hide_grid_accordion( 16, 'proveedorProducto' );
	            <?php endif; ?>
//fin de cambio Oscar2022
			//Excepcion para insertar fila
				<?php if ($this->_tpl_vars['grids'][$this->_sections['ng']['index']][27] != '0'): ?>
					for(ci=NumFilas('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
');ci<<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][18]; ?>
;ci++)
						InsertaFila('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
');
				<?php endif; ?>
              </script>
		</div>
        </div>
	<?php endfor; endif; ?>
<!-- 12.13. Fin de iteracion de grids-->







	<br />
<!-- 13. Seccion de botones laterales de la pantalla -->
	<div id="accione"s  class="btn-inferio"r align="right">

		<table  border="0" style="position:fixed;z-index:100;top:35%;right:8px;">
	<!--Ecepcion : implementacion de Oscar 21.08.2018 boton para avanzar al producto siguiente-->
		<?php if ($this->_tpl_vars['tabla'] == 'ec_productos'): ?>
		  <tr><td align="center"><a href="#" class="fr" title="siguiente" onclick="getSig()" style="background:green;padding:5px;border-radius:5px;color:white;">Siguiente </a><br><br><br></td></tr>
		<?php endif; ?>
	<!---->
	<!-- 13.1. Boton de guardar-->
       	<?php if ($this->_tpl_vars['tipo'] == 0 || $this->_tpl_vars['tipo'] == 1): ?>
				<tr><td id="guardarlistado" valign="bottom" title="Guardar listado"><table width="60"><tr><td ><img class="botonesacciones guardarbtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/guardar.png" alt="guardar" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para guardar los cambios" onclick="lanza_mensaje();"/><br>
                    <span style="border:0px solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Guardar</b></span></td></tr></table></td><!--valida() deshabilitado por Oscar 08.06.2018 para lanzar emergente--></tr>
			<?php endif; ?>

	<!-- 13.2. Boton de listado-->
			<tr>
		<!--Excepcion : implementacion Oscar 08.05.2019 para redireccionar a Listado de Transferencias desde la Recepcion-->
		<?php if ($this->_tpl_vars['tabla64'] == 'ZWNfdHJhbnNmZXJlbmNpYXM=' && $this->_tpl_vars['no_tabla64'] == 'Mg=='): ?>
			<td id="botonlistado" valign="bottom" title="Botón listado">
              <table>
              	<tr style="">
              	<td valign="top" align="center" style="border:0px solid;height:20px;padding:0px;" height="20px;">
              		<img class="botonesacciones listadobtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/listado.png" alt="listado"  onMouseOver="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para ir al listado" 
              		onClick="
              		<?php if ($this->_tpl_vars['special_transfers'] == 1): ?>
              			if(confirm('<?php echo $this->_tpl_vars['letSalir']; ?>
'))
              				location.href='../especiales/Transferencias/transferencias_multiples/index.php?';
              		<?php else: ?>
              			<?php if ($this->_tpl_vars['tipo'] == 0 || $this->_tpl_vars['tipo'] == 1): ?>
              				if(confirm('<?php echo $this->_tpl_vars['letSalir']; ?>
'))
              					location.href='<?php echo $this->_tpl_vars['rooturl']; ?>
code/general/listados.php?tabla=<?php echo $this->_tpl_vars['tabla64']; ?>
&no_tabla=MA==';
              			<?php else: ?>
              				location.href='<?php echo $this->_tpl_vars['rooturl']; ?>
code/general/listados.php?tabla=<?php echo $this->_tpl_vars['tabla64']; ?>
&no_tabla=MA==';
              			<?php endif; ?>
              		<?php endif; ?>"
              		/><br>
                    <span style="border:0px solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Listado</b></span>
                </td></tr>
              </table>
            </td>
		<!--Fin de cambio Oscar 08.05.2019-->
		<?php else: ?>
			<td id="botonlistado" valign="bottom" title="Botón listado">
              <table>
              	<tr style="">
              	<td valign="top" align="center" style="border:0px solid;height:20px;padding:0px;" height="20px;">
              		<img class="botonesacciones listadobtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/listado.png" alt="listado"  onMouseOver="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para ir al listado" onClick="<?php if ($this->_tpl_vars['tipo'] == 0 || $this->_tpl_vars['tipo'] == 1): ?>if(confirm('<?php echo $this->_tpl_vars['letSalir']; ?>
'))location.href='<?php echo $this->_tpl_vars['rooturl']; ?>
code/general/listados.php?tabla=<?php echo $this->_tpl_vars['tabla64']; ?>
&no_tabla=<?php echo $this->_tpl_vars['no_tabla64']; ?>
'<?php else: ?>location.href='<?php echo $this->_tpl_vars['rooturl']; ?>
code/general/listados.php?tabla=<?php echo $this->_tpl_vars['tabla64']; ?>
&no_tabla=<?php echo $this->_tpl_vars['no_tabla64']; ?>
'<?php endif; ?>"/><br>
                    <span style="border:0px solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Listado</b></span>
                </td></tr>
              </table>
            </td>
        <?php endif; ?>
        </tr>
	<!-- 13.3. Boton de agregar registro-->
			<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) && $this->_tpl_vars['mostrar_nuevo'] == '1'): ?>
				<tr <?php if ($this->_tpl_vars['tipo_sistema'] != 'linea' && ( $this->_tpl_vars['tabla'] == 'ec_productos' || $this->_tpl_vars['tabla'] == 'sys_users' || tabla == 'ec_traspasos_bancos' || tabla == 'ec_afiliaciones_cajero' ) || tabla == 'ec_caja_o_cuenta'): ?>style="display:none;"<?php endif; ?>>
				<td id="botonnuevo" valign="top">
				<table width="60"><tr><td><img class="botonesacciones nuevobtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/nuevo.png" alt="nuevo" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para agregar un nuevo registro" onclick="location.href='contenido.php?aab9e1de16f38176f86d7a92ba337a8d=<?php echo $this->_tpl_vars['tabla64']; ?>
&a1de185b82326ad96dec8ced6dad5fbbd=MA==&bnVtZXJvX3RhYmxh=<?php echo $this->_tpl_vars['no_tabla64']; ?>
'"/><br>
				  <span style="border:opx solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Nuevo</b></span>
				  </td></tr>
				</table>
				</td></tr>
			<?php endif; ?>
	<!--13.4. Boton de editar-->
			<?php if (( $this->_tpl_vars['tipo'] == 2 || $this->_tpl_vars['tipo'] == 3 ) && $this->_tpl_vars['mostrar_mod'] == '1'): ?>
				<tr <?php if ($this->_tpl_vars['tipo_sistema'] != 'linea' && ( $this->_tpl_vars['tabla'] == 'ec_productos' || $this->_tpl_vars['tabla'] == 'sys_users' || tabla == 'ec_traspasos_bancos' || tabla == 'ec_afiliaciones_cajero' ) || tabla == 'ec_caja_o_cuenta'): ?>style="display:none;"<?php endif; ?>>
				<td valign="top" title="Editar">
				<table width="60"><tr><td><img class="botonesacciones editarbtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/editar.png" alt="editar" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para editar este registro" onclick="location.href='contenido.php?aab9e1de16f38176f86d7a92ba337a8d=<?php echo $this->_tpl_vars['tabla64']; ?>
&a1de185b82326ad96dec8ced6dad5fbbd=MQ==&a01773a8a11c5f7314901bdae5825a190=<?php echo $this->_tpl_vars['llave64']; ?>
&bnVtZXJvX3RhYmxh=<?php echo $this->_tpl_vars['no_tabla64']; ?>
'"/><br>
                    <span style="border:opx solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Editar</b></span>
                </td></tr></table></td></tr>
			<?php endif; ?>
	<!--13.5. Boton de eliminar-->
			<?php if ($this->_tpl_vars['tipo'] == 3 && $this->_tpl_vars['mostrar_eli'] == '1'): ?>
				<tr <?php if ($this->_tpl_vars['tipo_sistema'] != 'linea' && ( $this->_tpl_vars['tabla'] == 'ec_productos' || $this->_tpl_vars['tabla'] == 'sys_users' || tabla == 'ec_traspasos_bancos' || tabla == 'ec_afiliaciones_cajero' ) || tabla == 'ec_caja_o_cuenta'): ?>style="display:none;"<?php endif; ?>>
				<td valign="bottom" title="Eliminar">
				<table width="60"><tr><td><img class="botonesacciones eliminarbtn" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/eliminar.png" alt="eliminar" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" title="clic para eliminar el registro" onclick="lanza_mensaje();"/><br>
                    <span style="border:opx solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Eliminar</b></span>
                </td></tr></table></td></tr>
			<?php endif; ?><!--valida() deshabilitado por Oscar 08.06.2018 para lanzar emergente-->
	<!--13.6. Boton de imprimir-->
			<?php if (( $this->_tpl_vars['tipo'] == 1 || $this->_tpl_vars['tipo'] == 2 ) && $this->_tpl_vars['mostrar_imp'] == '1' && ( $this->_tpl_vars['tabla'] == 'ec_ordenes_compraNO' || $this->_tpl_vars['tabla'] == 'ec_pedidos' )): ?>
				<tr><td valign="bottom" title="Imprimir"><table width="60"><tr><td><img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/imprimir.png" alt="imprimir" width="31" class="botonesacciones imprimirbtn" title="clic para imprimir el registro" onclick="imprime()" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';"/><br>
                    <span style="border:opx solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Imprimir</b></span>
                </td></tr></table></td></tr>

			<?php endif; ?>
	<!--13.7. Boton de reseteo de producto Implentación Oscar 2021-->
			<?php if ($this->_tpl_vars['tipo'] == 1 && $this->_tpl_vars['tabla'] == 'ec_productos'): ?>
				<tr><td valign="bottom" title="Resetear Producto"><table width="60"><tr><td><img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/Warning.gif" alt="imprimir" width="31" class="botonesacciones " title="clic para resetear el Producto" onclick="reset_product()" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';"/><br>
                    <span style="border:opx solid;position:relative;bottom:16px;left:0px;width:100%;"><b>Resetear Producto</b></span>
                </td></tr></table></td></tr>

			<?php endif; ?>
	<!--Excepcion : Implementacion Oscar 21.08.2018 boton para retroceder al producto anterior-->
		  		<?php if ($this->_tpl_vars['tabla'] == 'ec_productos'): ?>
		  		<tr><td><a href="#" class="fl" style="background:green;padding:5px;border-radius:5px;color:white;" title="anterior" onclick="getAnt()">Anterior</a></td></tr>
                     	<script>
                     		<?php echo '
                     	//Funcion para retroceder producto a travez del archivo code/ajax/prodAnt.php
                     		function getAnt()
                     		{

                     			id_producto=document.getElementById(\'id_productos\').value;
                     			//alert(id_producto);

                     			res=ajaxR(\'../ajax/prodAnt.php?tipo=1&id_producto=\'+id_producto);

                     			aux=res.split(\'|\');

                     			if(aux[0] == \'exito\')
                     			{

                     				//&a01773a8a11c5f7314901bdae5825a190=NTE2MA==&bnVtZXJvX3RhYmxh=MA==


	                     			var url="contenido.php?aab9e1de16f38176f86d7a92ba337a8d=ZWNfcHJvZHVjdG9z&a1de185b82326ad96dec8ced6dad5fbbd=";
	                     			'; ?>

	                     			url+="<?php echo $this->_tpl_vars['tipo64']; ?>
&a01773a8a11c5f7314901bdae5825a190="+aux[1]+"&bnVtZXJvX3RhYmxh=MA==";
	                     			<?php echo '

	                     			//alert(url);
	                     			location.href=url;
	                     		}
	                     		else if(aux[0] == \'NO\')
	                     		{
	                     			alert(\'No hay un producto anterior\');
	                     			return false;
	                     		}
	                     		else
	                     			alert(res);

                     		}

                     	//Funcion para avanzar producto a travez del archivo code/ajax/prodAnt.php
                     		function getSig()
                     		{

                     			id_producto=document.getElementById(\'id_productos\').value;
                     			//alert(id_producto);

                     			res=ajaxR(\'../ajax/prodAnt.php?tipo=2&id_producto=\'+id_producto);

                     			aux=res.split(\'|\')

                     			if(aux[0] == \'exito\')
                     			{

                     				//&a01773a8a11c5f7314901bdae5825a190=NTE2MA==&bnVtZXJvX3RhYmxh=MA==


	                     			var url="contenido.php?aab9e1de16f38176f86d7a92ba337a8d=ZWNfcHJvZHVjdG9z&a1de185b82326ad96dec8ced6dad5fbbd=";
	                     			'; ?>

	                     			url+="<?php echo $this->_tpl_vars['tipo64']; ?>
&a01773a8a11c5f7314901bdae5825a190="+aux[1]+"&bnVtZXJvX3RhYmxh=MA==";
	                     			<?php echo '

	                     			//alert(url);
	                     			location.href=url;
	                     		}
	                     		else if(aux[0] == \'NO\')
	                     		{
	                     			alert(\'No hay un producto siguiente\');
	                     			return false;
	                     		}
	                     		else
	                     			alert(res);

                     		}

                     		'; ?>

                     	</script>

                    <?php endif; ?>
		  	</td>
		  </tr>
		  <!--fin de cambio-->


		</table>
	</div>
	</form>

	<script>

		<?php echo '
		// 14. Funcion para enviar email atravez del archivo /code/pdf/enviaMail.php 
		    function enviarMail()
		    {
		        '; ?>

                var res=ajaxR('../pdf/enviaMail.php?id=<?php echo $this->_tpl_vars['llave']; ?>
');
                <?php echo '

                if(res == \'exito\')
                    alert(\'Se ha enviado el correo con exito\');
                else
                {
                    //alert(\'No fue posible enviar el correo, verifique su configuracion\');
                    alert(res);
                }
		    }

		// 15. Funcion para imprimir cotizacion atravez del archivo /code/pdf/imprimeDoc.php
		    function imprimirCot()
		    {
		        '; ?>

		        window.open('../pdf/imprimeDoc.php?tdoc=COT&id=<?php echo $this->_tpl_vars['llave']; ?>
');
		        <?php echo '
		    }


			function calculaTotales()
			{
				var num=NumFilas(\'productos\');
				var tot=0;

				for(var i=0;i<num;i++)
				{
					var can=celdaValorXY(\'productos\', 4, i);
					var pre=celdaValorXY(\'productos\', 5, i);

					can=isNaN(parseFloat(can))?0:parseFloat(can);
					pre=isNaN(parseFloat(pre))?0:parseFloat(pre);

					tot+=can*pre;
				}

				var num=NumFilas(\'otros\');
				for(var i=0;i<num;i++)
				{
					var can=celdaValorXY(\'otros\', 6, i);
					var pre=celdaValorXY(\'otros\', 7, i);

					can=isNaN(parseFloat(can))?0:parseFloat(can);
					pre=isNaN(parseFloat(pre))?0:parseFloat(pre);

					tot+=can*pre;
				}

				obj=document.getElementById(\'subtotal\');
				obj.value=tot;

				obj=document.getElementById(\'iva\');
				obj.value=tot*0.16;

				obj=document.getElementById(\'total\');
				obj.value=tot*1.16;

			}


			function test()
			{
				return true;
			}

			function cambiaProds(val)
			{
				id=celdaValorXY(\'productos\', 2, val);
				res=ajaxR(\'../ajax/valProds.php?id=\'+id);

				var aux=res.split(\'|\');
				if(aux[0] == \'exito\')
				{
					valorXY(\'productos\', 3, val, aux[1]);
					valorXY(\'productos\', 5, val, aux[2]);
				}
				else
				{
					valorXY(\'productos\', 2, val, \'\');
				}

			}

	/*implementación de Oscar 08.06.2018 para lanzar emergente al editar*/
		function lanza_mensaje(){
			var cargando=\'<p align="center" style="color:white;font-size:35px;">Guardando</p>\';
			cargando+=\'<br><img src="../../img/img_casadelasluces/load.gif" width="100px;"><br>\';
			$("#mensajEmerge").html(cargando);//cargamos el contenido al div
				$("#emerge").css("display","block");
				setTimeout(valida,100);//retrasamos la entrada de la validación
			//alert(2);
			return true;
		}
	/*fin de cambio 08.06.2018*/

			function valida(){
				var f=document.formaGral;
				//alert(f.tabla.value);
				if(f.tabla.value==\'ec_productos\' 
					&& existe_o_l!=0
					&& $( \'#clave\' ).val() != -1/*oscar 2022*/
					){
					alert("El Orden De Lista que insertó para este producto ya existe!!!\\n\\n"+"Pruebe con uno nuevo e intente nuevamente");
					$("#emerge").css("display","none");
					return false;
				}
			/*implementacion Oscar 24.10.2019 para validacion de login unico*/
				if(f.tabla.value==\'sys_users\' && existe_login!=0){
					alert("El login que tecleo para el usuario ya existe o es invalido!!!\\n\\n"+"Pruebe con uno nuevo e intente nuevamente");
					$("#emerge").css("display","none");
					$("#login").select();
					return false;
				}

			/* implementacion Oscar 23-09-2020 para validar tipo de producto y fechas de seccion venta en linea*/
				if(f.tabla.value==\'ec_productos\'){
					if( !validacion_tipo_producto() ){
						$("#emerge").css("display","none");
						return false;
					}
					if( !valida_fecha_tienda_linea() ){
						$("#emerge").css("display","none");
						return false;
					}
				}

				'; ?>

					//alert(<?php echo $this->_tpl_vars['tipo']; ?>
); edición es tipo=1
					//return true;
					<?php if ($this->_tpl_vars['tipo'] != 3): ?>
						<?php echo $this->_tpl_vars['validacion_form']; ?>

					<?php endif; ?>

				<?php echo '

				if(f.tipo.value == \'0\')
				{
					f.accion.value="insertar";
				}
				if(f.tipo.value == \'1\')
				{
					f.accion.value="actualizar";

				}
				if(f.tipo.value == \'3\')
				{
					f.accion.value="eliminar";
				}

				/*if(f.tabla.value == \'sys_users\')
				{
					var aux=GuardaGrid(\'permisos\', 5);

					var ax=aux.split(\'|\');
					if(ax[0] == \'exito\')
					{
						f.filePermisos.value=ax[1];
					}
					else
					{
						alert(aux);
						return false;
					}


				}*/


				//Guardamos los grids
				'; ?>


					<?php unset($this->_sections['x']);
$this->_sections['x']['loop'] = is_array($_loop=$this->_tpl_vars['gridArray']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['x']['name'] = 'x';
$this->_sections['x']['show'] = true;
$this->_sections['x']['max'] = $this->_sections['x']['loop'];
$this->_sections['x']['step'] = 1;
$this->_sections['x']['start'] = $this->_sections['x']['step'] > 0 ? 0 : $this->_sections['x']['loop']-1;
if ($this->_sections['x']['show']) {
    $this->_sections['x']['total'] = $this->_sections['x']['loop'];
    if ($this->_sections['x']['total'] == 0)
        $this->_sections['x']['show'] = false;
} else
    $this->_sections['x']['total'] = 0;
if ($this->_sections['x']['show']):

            for ($this->_sections['x']['index'] = $this->_sections['x']['start'], $this->_sections['x']['iteration'] = 1;
                 $this->_sections['x']['iteration'] <= $this->_sections['x']['total'];
                 $this->_sections['x']['index'] += $this->_sections['x']['step'], $this->_sections['x']['iteration']++):
$this->_sections['x']['rownum'] = $this->_sections['x']['iteration'];
$this->_sections['x']['index_prev'] = $this->_sections['x']['index'] - $this->_sections['x']['step'];
$this->_sections['x']['index_next'] = $this->_sections['x']['index'] + $this->_sections['x']['step'];
$this->_sections['x']['first']      = ($this->_sections['x']['iteration'] == 1);
$this->_sections['x']['last']       = ($this->_sections['x']['iteration'] == $this->_sections['x']['total']);
?>

						<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][11] != 'false'): ?>

							<?php if ($this->_tpl_vars['gridArray'][$this->_sections['x']['index']][4] != ''): ?>
								var aux=<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][4]; ?>
;
								if(!aux)
								return false;
							<?php endif; ?>




							var num=NumFilas('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
');
							var nomGrid='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
';
							var disGrid='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][2]; ?>
';

							<?php echo '



							if (nomGrid == \'sucursalProducto\')
							{
						 		if(validaSucursalProducto(\'sucursalProducto\') == false)
						 		{
						 			alert("Todas las presentacines deben ser mayor a 0");
						 			$("#emerge").css("display","none");
						 			return false;
						 		}
						 		//if(validaSucrsalStock(\'sucursalProducto\') == false)
						 		//{
						 		//	alert("El stock debe ser mayor a 0");
						 	//		return false;
						 	//	}

						 		//getJsonSucPro();
						 	}

				/*Implementacion Oscar 2020 para validar que no se repitan atributos por producto
	DESHABILITADO POR OSCAR MARZO 29 2020 
							if (nomGrid == \'atributosProducto\'){
								var atributos_existentes = new Array();
								var tmp, tmp_cat;
								var categoria_producto = $("#id_categoria").val();
								var atributos_incorrectos = "";
							//recorre grid
							//alert(NumFilas(nomGrid));
								var tabla=document.getElementById(\'Body_\' + nomGrid);
								trs=tabla.getElementsByTagName(\'tr\');
								for(i_a=0 ; i_a < trs.length; i_a++){
								//
									i_a_a = ($(trs[i_a]).attr("id")).replace(\'atributosProducto_Fila\' , \'\');
									tds=trs[i_a].getElementsByTagName(\'td\');
           							tmp_cat = tds[5].getAttribute(\'valor\');
           							
           							tmp = tds[3].getAttribute(\'valor\');
											
           							if(tmp_cat != categoria_producto && (tmp_cat != null && tmp_cat != \'\') ){
										 atributos_incorrectos += $("#" + nomGrid + "_2_" + i_a_a ).html() + "\\n";
										 //alert(\'$("#"\'+ nomGrid + "_2_" + i_a_a + \')\' );
									}
									for(var a_e = 0; a_e < atributos_existentes.length; a_e++){
										if(tmp == atributos_existentes[a_e] && atributos_existentes[a_e] != null){
											$("#emerge").css("display","none");
											alert("Hay un atributo repetido para el producto y no es posible guardar" +
												"\\nVerifique y vuelva a intentar");
											setTimeout(function(){$("#" + nomGrid + "_2_" + (i_a_a) ).click();} , \'300\'); 
											return false;
										}
									}
									//alert("tmp : " + tmp);
									atributos_existentes.push(tmp);
           						}

								if(atributos_incorrectos != ""){
									$("#emerge").css("display","none");
									alert("Los siguientes atributos no corresponde a la categoria del" +
										" producto, verifique sus datos y vuelva a intentar: \\n" 
										+ atributos_incorrectos);
									return false;
						 		}
						 	}*/
				/**/
							for(ig=0;ig<num;ig++)
							{
								var nc=NumColumnas(nomGrid);

								for(jg=0;jg<nc;jg++)
								{
									req=getValueHeader(nomGrid, jg, \'requerido\');

							//alert(jg+" req:"+req);

									if(req == \'S\' || req == \'s\')
									{
										if(celdaValorXY(nomGrid, jg, ig) == \'\')
										{
											//alert(nomGrid+"_"+jg+"_"+ig);
											alert("Debe llenar los datos del grid "+disGrid);
											$("#emerge").css("display","none");
											return false;
										}
									}
								}
							}
				/**/
							if ( nomGrid == \'proveedorProducto\' ){
								if( ! check_codigo_barras_final() ){
									alert("Hay códigos de barras repetidos en el grid de Proveedor - Producto");
									$("#emerge").css("display","none");
									return false;
								}
							}	
				/**/

						/*Cambio para no permitir recibir transferencia si no hay internet Oscar(09-11-2017)*/

					/*si el grid es de recepcion de transferencia verificamos conexion con el servidor
						'; ?>

						var xD='<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][0]; ?>
';
						<?php echo '
						if(xD==43){
							var confirmacionServ=ajaxR(\'../especiales/sincronizacion/conexionSincronizar.php?verifServ=1\');
							if(confirmacionServ==\'no\'){
								alert("No se pueden dar Recepción a las transferencias debido a que no se tiene conexión con el servidor\\n"+
								"Verifique su conexion a internet y vuelva a intentar!!!");
								return false;
							}
						}
					//finaliza cambio                   deshabilitado por Oscar 08.06.2018*/

						'; ?>

						var aux=GuardaGrid('<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
', 5);
						var ax=aux.split('|');
						<?php echo '

						//alert(aux);

						if(ax[0] == \'exito\')
						{
							'; ?>

							f.file<?php echo $this->_tpl_vars['gridArray'][$this->_sections['x']['index']][1]; ?>
.value=ax[1];
							<?php echo '
						}
						else
						{
							alert("Error al guardar grid: "+aux);
							$("#emerge").css("display","none");

							return false;
						}

						'; ?>


						<?php endif; ?>

					<?php endfor; endif; ?>

				<?php echo '

				/*alert(\'Suspendido por pruebas...Atte Equipo de desarrollo\');
				return false;*/

				f.submit();

			}//fin de función valida


			function actualizaDependiente(id_catalogos, id_objetos, valor, val_pre)
			{

				var ids=id_catalogos.split(\',\');
				var obs=id_objetos.split(\',\');
				var vpres=val_pre.split(\',\');


				//alert(ids.length);

				for(var j=0;j<ids.length;j++)
				{
					//alert(i)
					var res=ajaxR(\'comboDependiente.php?id_catalogo=\'+ids[j]+\'&valor=\'+valor);
					var aux=res.split(\'|\');
					if(aux[0] == \'exito\')
					{
						if(document.getElementById(vpres[j]))
							var vpred=document.getElementById(vpres[j]).value;
						else
							var vpred=vpres[j];

						//alert(vpred);

						var obj=document.getElementById(obs[j]);
						obj.options.length=0;
						for(i=1;i<aux.length;i++)
						{
							var ax=aux[i].split(\'~\');
							obj.options[i-1]=new Option(ax[1], ax[0]);
						}
						if(vpred != \'NO\')
						{
							obj.value=vpred;
						}

						var och=obj.getAttribute("onchange");
						//var och=och.replace("\\n", \'\');
						//alert(och);
						eval(och);
					}
					else
						alert(res);
				}
			}

			function botonBuscador(nomcampo)
			{
				var obj=document.getElementById(nomcampo+"_txt");
				if(obj)
				{
					var evento=new Object();
					evento.keyCode="40";
					activaBuscador(nomcampo, evento)
				}
				else
					alert("Error, objeto no encontrado.\\n\\n"+nomcampo+"_txt");
			}

			function activaBuscador(nomcampo, evento)
			{

				objInput=document.getElementById(nomcampo+"_txt");

				var objdiv=document.getElementById(nomcampo+"_div");
				if(!objdiv)
				{
					alert("Error, objeto no encontrado.\\n\\n"+nomcampo+"_div");
					return false;
				}

				//alert(evento.keyCode);

				if(evento.keyCode==9)
				{
					ocultaCombobusc(nomcampo);
					return false;
				}

				var objh=document.getElementById(nomcampo);
				if(!objh)
				{
					alert("Error, objeto no encontrado.\\n\\n"+nomcampo);
					return false;
				}

				if(objh&&evento.keyCode!=40)
				{
					objh.value="";
					objh.value=objInput.value;
				}

				if(evento.keyCode==40 && objdiv.style.display=="block")
				{
					//alert("??");
					FocoComboBuscador(nomcampo);
					return false;
				}
				if(evento.keyCode==40)
				{
					//alert(\'?\');

					var depende=(objInput.getAttribute("depende"))?objInput.getAttribute("depende"):"";
					var cadbusq="";
					if(depende!=""&&depende!=0)
					{
						var arrdepen=depende.split("|");
						for(var i=0; i<arrdepen.length;i++)
						{
							if(arrdepen[i].indexOf("~")!=-1)
							{
								var arr=arrdepen[i].split("~");
								var dependencia=arr[0];
								var campodepen=arr[1];
							}
							else
							{
								var dependencia=arrdepen[i];
								var campodepen="";
							}
							var arrnomde=objInput.name.split("_");
							nomde=arrnomde[1]+"_"+dependencia;
							var objvaldep=document.getElementsByName(nomde)[0];
							if(objvaldep)
							{
								if(objvaldep.value!="")
								{
									cadbusq=objvaldep.value;
									var numdep=dependencia;
								}
							}
							if(objvaldep.value!="")
								break;
						}
					}
					if(cadbusq!="")
					{
						muestraBuscador(nomcampo);
						ComboBuscador(nomcampo);
					}

					//alert("Fin ?");
				}

				var numAct=0;


				if((evento.keyCode==40& objInput.value.length>=numAct)||objInput.value.length>=numAct)
				{
					//alert("Y");
					muestraBuscador(nomcampo);
					ComboBuscador(nomcampo);
				}
				else if(objdiv.style.display=="block"&&objInput.value.length>=numAct)
				{
					ComboBuscador(nomcampo);
				}
				return true;
			}

			function muestraBuscador(nomcampo)
			{

				var objdiv=document.getElementById(nomcampo+"_div");
				objInput=document.getElementById(nomcampo+"_txt");
				if(objdiv)
				{
					if(objdiv.style.display=="none")
					{
						objdiv.style.display="block";
						objdiv.style.visibility="visible";
						var top=objdiv.offsetTop;
						var altura=objInput.offsetHeight;
						var y=posicionObjeto(objInput)[1];
						//if(navigator.appName=="Microsoft Internet Explorer")
						top+=2;
						//if(top<(y+altura))
						//{
							top+=altura;
							top+="px";
							//objdiv.style.top=top;
						//}
					}
				}
				return true;
			}


			function ComboBuscador(nomcampo)
			{
				/*var nomcampo=objInput.name;
				var arr=nomcampo.split("_");
				nomcampo=arr[1]+"_"+arr[2];*/

				objInput=document.getElementById(nomcampo+"_txt");


				//alert(objInput);

				var objselec=document.getElementById(nomcampo+"_sel");
				if(objselec)
				{
					if(!objselec)
							return false;
					var lon=objselec.length;
					for(var i=0;i<lon;i++)
						objselec.options[0]=null;


					//alert(objselec)

					var url=objselec.getAttribute("datosdb");


					//alert(url);

					if(url.length>0)
					{
						url+="&val="+objInput.value;
						var depende=(objInput.getAttribute("depende"))?objInput.getAttribute("depende"):"";
						if(depende!=""&&depende!=0)
						{
							var arrdepen=depende.split("|");
							var cadbusq="";
							for(var i=0; i<arrdepen.length;i++)
							{
								if(arrdepen[i].indexOf("~")!=-1)
								{
									var arr=arrdepen[i].split("~");
									var dependencia=arr[0];
									var campodepen=arr[1];
								}
								else
								{
									var dependencia=arrdepen[i];
									var campodepen="";
								}
								var arrnomde=objInput.name.split("_");
								nomde=arrnomde[1]+"_"+dependencia;
								var objvaldep=document.getElementsByName(nomde)[0];
								if(objvaldep)
								{
									if(objvaldep.value!="")
									{
										cadbusq=objvaldep.value;
										var numdep=dependencia;
									}
								}
								if(objvaldep.value!="")
									break;
							}
							if(objvaldep)
							{
								url+="&depende="+(parseInt(numdep)+1)+"&valordep="+cadbusq;
								if(campodepen!="")
									url+="&nom_dependencia="+campodepen;
							}
						}

						//alert(url);

						var resp=ajaxR(url);

						//alert(resp);
						var arr=resp.split("|");


						if(arr[0]!="exito")
						{
							alert(resp);
							return false;
						}
						var num=parseInt(arr[1]);
						//alert(num);

						if(num<=0)
						{
							/*var nombre=objInput.name.split("_");
							nombre=nombre[1]+"_"+nombre[2];*/

							//alert(\'malo\');

							ocultaCombobusc(nomcampo);
							return false;
						}
						var objselec=document.getElementById(nomcampo+"_sel");
						objselec.options.length=0;
						//alert(objselec);
						for(var i=2;i<(num+2);i++)
						{
							var arrOpciones=arr[i].split("~");
							objselec.options[i-2]=new Option(arrOpciones[1],arrOpciones[0]);
						}
						if(objselec.options.length == 1 && !isNaN(objInput.value) && 0)
						{

							//alert(\'?\');
							objselec.options[0].selected=true;
							asignavalorbusc(nomcampo);
						}
					}
				}
				return true;
			}

			function asignavalorbusc(nomcampo)
			{
				//alert(\'1\');
				var objsel=document.getElementById(nomcampo+"_sel");
				if(objsel)
				{
					//alert(\'2\');
					var valor=objsel.value;
					var objcampo=document.getElementById(nomcampo+"_txt");
					if(objcampo)
					{
						//alert(\'3\');
						var seleccionado=objsel.selectedIndex;
						var objseleccionado=objsel.options[seleccionado];
						var visible=objseleccionado.text;
						var oculto=objseleccionado.value;
						var objh=document.getElementById(nomcampo);
						if(objh)
						{
							//alert(\'4\');
							objh.value=oculto;
							objcampo.value=visible;
							var funciononchange=(objcampo.getAttribute("on_change"))?objcampo.getAttribute("on_change"):"";
							//alert(funciononchange);
							if(funciononchange.indexOf("#")!=-1)
								funciononchange=funciononchange.replace(\'#\',objh.id);
							//alert(\'p1\');
							ocultaCombobusc(nomcampo);
							//alert(\'p2\');
							eval(funciononchange);
						}
					}
				}
				return true;
			}


			function ocultaCombobusc(campo)
			{
				var objdiv=document.getElementById(campo+"_div");
				if(!objdiv)
				{
					/*var campo=campo.split("_");
					campo=campo[1]+"_"+campo[2];
					var objdiv=document.getElementById("div"+campo);*/
					return false;
				}
				if(objdiv)
				{
					if(objdiv.style.display=="block")
					{
						var objInput=document.getElementById(campo+"_txt");
						if(objInput)
						{
							var top=objdiv.offsetTop;
							top-=2;
							var altura=objInput.offsetHeight;
							top-=altura;
							top+="px";
							//objdiv.style.top=top;
						}
						objdiv.style.display="none";
						objdiv.style.visibility="hidden";
					}
				}
				return true;
			}

			function posicionObjeto(obj)
			{
			    var left = 0;
			      var top = 0;
			      if (obj.offsetParent) {
			            do {
			                  left += obj.offsetLeft;
			                  top += obj.offsetTop;
			            } while (obj = obj.offsetParent);
			      }
			      return [left,top];
			}
		/*implementación de deshabilitar/habilitar sucursales_producto Oscar 08.05.2018*/
			function afectaSucProd(objeto){
				var tope_gr=$("#sucursalProd tr").length-4;
				var check_val,valor;
				if(objeto.checked==true){
					check_val=true;
					valor=1;
				}else{
					check_val=false;
					valor=0;
				}
			//asignamos valores habilitado/deshabilitado a grid a productos por sucursal
				for(var i=0;i<=tope_gr;i++){
					document.getElementById("csucursalProd_4_"+i).checked=check_val;//habilitamos/deshabilitamos checkbox
					$("#sucursalProd_4_"+i).attr("valor",valor);//cambiamos valor del checkbox
					$("#sucursalProd_4_"+i).attr("valor",valor);//cambiamos valor de la celda (este es el que modifica el valor en la BD)

					if(valor==1  && i==0){
						return true;
					}

					//if(valor==0){//quitamos el atributo cheked en grid de sucursal por producto en el check de habilitado/deshabilitado
					//	$("#csucursalProd_4_"+i+" check").removeAttr(\'checked\');
					//}
				}
			}
		/*Fin de cambio*/
		
var existe_o_l=0;
		//implementación de Oscar 21-02-2018
			function validaNoLista(obj,e){
				var tca=e.keyCode;
				var valor=obj.value;
				'; ?>

				var tpo=<?php echo $this->_tpl_vars['tipo']; ?>
;//capturamos tipo de accion
				<?php echo '
			//si es edicion capturamos el id del producto
				var idp=0;
				if(tpo==1){
					idp=document.getElementById(\'id_productos\').value;
				}
				if(tca==27){
					$("#res_ord_lis").css("display","none");
					return false;
				}
				if(valor.length<=2){
					$("#res_ord_lis").css("display","none");
				}else{
					//alert(idp);
					$.ajax({
						type:\'post\',
						url:\'../ajax/validaCodLista.php\',
						cache:false,
						data:{datos:valor,acc:tpo,id:idp},
						success:function(dat){
							var arr_re=dat.split("|");
							if(arr_re[0]!=\'ok\'){
								alert("Error!!!\\n"+dat);
							}
							$("#res_ord_lis").html(arr_re[1]);
							$("#res_ord_lis").css("display","block");
							existe_o_l=arr_re[3];
							if(arr_re[2]<=0){
								$("#res_ord_lis").html(\'\');
								$("#res_ord_lis").css("display","none");
							}

							//alert(existe_o_l);
						}
					});
				}
			}

	/*implementacion Oscar 24.10.2019 para validacion de login unico*/
		var existe_login=0;
			//implementación de Oscar 21-02-2018
			function validaLogin(obj,e){
				var tca=e.keyCode;
				var valor=obj.value;
				'; ?>

				var tpo=<?php echo $this->_tpl_vars['tipo']; ?>
;//capturamos tipo de accion
				<?php echo '
			//si es edicion capturamos el id del producto
				var idp=0;
				if(tpo==1){
					idp=document.getElementById(\'id_usuario\').value;
				}
/*				if(tca==27){
					$("#res_ord_lis").css("display","none");
					return false;
				}*/
				if(valor.length<=2){
					$("#login").css("color","red");
					existe_login=1;
				}else{
					$.ajax({
						type:\'post\',
						url:\'../ajax/validaCodLista.php\',
						cache:false,
						data:{fl:\'login\',datos:valor,acc:tpo,id:idp},
						success:function(dat){
							var arr_re=dat.split("|");
							if(arr_re[0]!=\'ok\'){
								alert("Error!!!\\n"+dat);
							}
							//$("#res_ord_lis").html(arr_re[1]);
							//$("#res_ord_lis").css("display","block");
							existe_login=arr_re[2];
							if(existe_login==0){
							//	$("#res_ord_lis").html(\'\');
								$("#login").css("color","green");
							}else{
								$("#login").css("color","red");
							}
						}
					});
				}
			}
	/*Fin de cambio Oscar 24.10.2019*/

//alert(ejecutar);

/*Deshabilitado por Oscar 08.11.2018 porque ya no se usará
	//implementación Oscar 27.02.2018
		function ch_tip_pgo(flag){
			var campo1,campo2;
		//asignamos campos
			if(flag==1){
				campo1="pago por dia";
				campo2="pago por hora";
			}
			if(flag==2){
				campo1="pago por hora";
				campo2="pago por dia y mínimo de horas";
			}
		//enviamosmensaje
			var conf_c_p=confirm("Al cambiar el "+campo1+" se deshabilitará el "+campo2+"\\nDesea continuar?");
		//si no se decide seguir se enfoca el campo de origen
			if(conf_c_p==false){
				if(flag==1){
					$("#pago_hora").focus();
					return false;
				}
				if(flag==2){
					$("#pago_dia").focus();
					return false;
				}

			}else{
				if(flag==1){
					document.getElementById("pago_hora").value="0.00";
					$("#pago_dia").select();
				}
				if(flag==2){
					document.getElementById("pago_dia").value="0.00";
					document.getElementById("minimo_horas").value="0";
					$("#pago_hora").select();
				}
			}
		}
	//fin de implementación 27.02.2018
Fin de deshabilitar Oscar 08.11.2018*/

	//implementación de Oscar 31.07.2018 para desplegar/ocultar los divs de los grids
		function despliega(acc_gr,num_gr){
			var acc_1,acc_2,icono,sig_acc,index;
			if(acc_gr==1){
				acc_1="200px";
				acc_2="block";
				icono="../../img/especiales/menos.png";
				sig_acc="despliega(2,"+num_gr+");";
				index="2000";
			}
			if(acc_gr==2){
				acc_1="0px";
				acc_2="none";
				icono="../../img/especiales/add.png";
				sig_acc="despliega(1,"+num_gr+");";
				index="5";
			}
			$("#div_grid_"+num_gr).css("height",acc_1);
			$("#div_grid_"+num_gr).css("display",acc_2);
			$("#div_busc_grid_"+num_gr).css("display",acc_2);
			$("#desp_"+num_gr).attr("src",icono);
			$("#desp_"+num_gr).attr("onClick",sig_acc);
			$("#desp_"+num_gr).css("z-index",index);
			//$(obj_1)
		}
			eval(ejecutar);

		'; ?>


	</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/funciones.tpl", 'smarty_include_vars' => array('tabla' => $this->_tpl_vars['tabla'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>