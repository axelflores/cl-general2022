<?php /* Smarty version 2.6.13, created on 2021-10-08 12:41:20
         compiled from reportes_mod.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'reportes_mod.tpl', 89, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<style>
	
	.headerReporte{
		font-weight:900;
		background-color: #CD6156;
	}
	
	.datos{
		background-color: #FFFFFF;
		color: #000000;
	}
	.sumatoriasRep{
		font-weight:900;
		background-color: #CD6156;
	}


</style>
'; ?>


 <div id="campos">

     
  <!--Titulo de la sección-->
  <section id="ctn_seccion">
  	<div id="titulo"><?php echo $this->_tpl_vars['titulo']; ?>
 <?php echo $this->_tpl_vars['folio_ajuste']; ?>
</div><!--implementación Oscar 17.08.2018 para concatenar el folio del ajuste de inventario-->
    <br><br>
<!--implementación Oscar 17.08.2018 para crear variable que guardará el folio temporal-->
	<input type="hidden" value="<?php echo $this->_tpl_vars['folio_ajuste']; ?>
" id="folio_ajuste_inv">
<!--Fin de cambio-->
	
  	
      <div>

		<fieldset>
			<legend style="color:#F81E04;"><b>Filtros</b></legend>	

        <table width="100%">
          <tr>
            <td>
            	Reporte:
            	<!--<select id="id_reporte" name="id_reporte" onchange="if(this.value == 4) document.getElementById('periodo').style.display='none'; else document.getElementById('periodo').style.display=''">
            		<option value="1" <?php if ($this->_tpl_vars['id_reporte'] == 1): ?>selected<?php endif; ?>>Reporte de Ventas</option>
            		<option value="2">Reporte de ventas por producto</option>
            		<option value="3">Reporte compras</option>
            		<option value="4">Balance</option>
					<option value="5">Reporte de ventas de servicios</option>
					<option value="6">Reporte de pagos de colegiaturas</option>
					<option value="7">Rreporte de cursos</option>
					<option value="8">Reporte de proveedores de tienda</option>
					<option value="9">Reporte de coreografías</option>
					<option value="10">Reporte de clientes</option>
					<option value="11">Reporte de pendientes</option>
					<option value="12">Reporte de proveedores de servicios</option>
					<option value="13">Reporte de pagos vencidos</option>
					<option value="14">Reporte de promesas de pago</option>
					<option value="15">Reporte de inventario</option>
					<option value="16">Reporte de alumnos</option>
					<option value="17">Reporte de alumnos</option>
            	</select>-->
				<?php if ($this->_tpl_vars['id_reporte'] == '1'): ?>
					Inventario
				<?php elseif ($this->_tpl_vars['id_reporte'] == '2'): ?>
					Reporte de mercancia pendiente de surtir
				<?php elseif ($this->_tpl_vars['id_reporte'] == '3'): ?>
					Reporte de cuentas por pagar
				<?php elseif ($this->_tpl_vars['id_reporte'] == '4'): ?>
					Reporte de diferencia en transferencias
				<?php elseif ($this->_tpl_vars['id_reporte'] == '5'): ?>
					Reporte de requisiciones pendientes
				<?php elseif ($this->_tpl_vars['id_reporte'] == '6'): ?>
					Reporte de productos por surtir
				<?php elseif ($this->_tpl_vars['id_reporte'] == '7'): ?>
					Reporte de din&aacute;mico de productos con stock
				<?php elseif ($this->_tpl_vars['id_reporte'] == '8'): ?>
					Reporte din&aacute;mico de productos
				<?php elseif ($this->_tpl_vars['id_reporte'] == '9'): ?>
					Reporte din&aacute;mico de ubicaciones
				<?php elseif ($this->_tpl_vars['id_reporte'] == '10'): ?>
					Reporte de transferencias de almac&eacute;n
				<?php elseif ($this->_tpl_vars['id_reporte'] == '11'): ?>
					Reporte de din&aacute;mico de productos, ubicaci&oacute;n y stock
				<?php elseif ($this->_tpl_vars['id_reporte'] == '99'): ?>
					Reporte de ventas filtrable</br>					
						<select name="id_reporte_v" id="id_reporte_v" onchange="document.getElementById('id_reporte').value=this.value">
						         <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['vals'],'output' => $this->_tpl_vars['textos']), $this);?>

					    </select>
				<?php elseif ($this->_tpl_vars['id_reporte'] == '-98'): ?>
						Reporte de compras filtrable</br>					
						<select name="id_reporte_c" id="id_reporte_c" onchange="document.getElementById('id_reporte').value=this.value">
						         <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['vals'],'output' => $this->_tpl_vars['textos']), $this);?>

					    </select>
				<?php elseif ($this->_tpl_vars['id_reporte'] == '98'): ?>
					Reporte de compras filtrable	
				<?php elseif ($this->_tpl_vars['id_reporte'] == '20'): ?>
					Reporte de ventas por subtipo
				<?php elseif ($this->_tpl_vars['id_reporte'] == '21'): ?>
					Reporte de ventas por producto
				<?php elseif ($this->_tpl_vars['id_reporte'] == '22'): ?>
					Reporte de ventas por color
				<?php elseif ($this->_tpl_vars['id_reporte'] == '23'): ?>
					Reporte de ventas por vendedor
				<?php elseif ($this->_tpl_vars['id_reporte'] == '24'): ?>
					Reporte de compras por tipo											
				<?php elseif ($this->_tpl_vars['id_reporte'] == '25'): ?>
					Reporte de compras por familia
				<?php elseif ($this->_tpl_vars['id_reporte'] == '26'): ?>
					Reporte de compras por subtipo
				<?php elseif ($this->_tpl_vars['id_reporte'] == '27'): ?>
					Reporte de compras por producto
				<?php elseif ($this->_tpl_vars['id_reporte'] == '28'): ?>
					Reporte de compras por color
				<?php elseif ($this->_tpl_vars['id_reporte'] == '29'): ?>
					Reporte de compras por proveedor														
			<!--Implementación de Oscar por reporte de Promedios 01.06.2018-->
				<?php elseif ($this->_tpl_vars['id_reporte'] == '33'): ?>
					Reporte de Precio Compra y Precio de Venta
			<!--Fin de cambio Oscar 01.06.2018-->							
				<?php endif; ?>

				
				<input type="hidden" name="id_reporte" id="id_reporte" value="<?php echo $this->_tpl_vars['id_reporte']; ?>
">


            </td>
            <div style="display:none">
	            <td  id="periodo" >
				  <?php if ($this->_tpl_vars['id_reporte'] == '31' || $this->_tpl_vars['id_reporte'] == '40'): ?>
					  Periodo:
		              <label class="select">
	    	            <select name="fechas" id="fechas" onchange="cambiaFec(this.value,'muestraFec')">
	                	  <option value="4">Personalizado</option>
		                </select>
	    	          </label>
	        		      
	            	  <div id="muestraFec" style="display:block; margin-left:30px;">
		              	Fecha del:
		              	<input type="text" name="fecdel" id="fecdel" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
		              	al:
		              	<input type="text" name="fecal" id="fecal" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
		              </div>
				  <?php else: ?>	
					  <?php if ($this->_tpl_vars['id_reporte'] == 1 || $this->_tpl_vars['id_reporte'] == 6 || $this->_tpl_vars['id_reporte'] == 37): ?><!--excluimos el filtro de fechas-->
						<div style="display:none">
					  <?php endif; ?>
			<!--Modificaciones Oscar 12.12.2018 para poder filtrar por fecha el reporte 5.11-->
					  Periodo<?php if ($this->_tpl_vars['id_reporte'] == '11'): ?> de entradas<?php endif; ?>:
		              <label class="select">
	    	            <select name="fechas" id="fechas" onchange="cambiaFec(this.value,'muestraFec')">
	        	          <?php if ($this->_tpl_vars['id_reporte'] != '11'): ?><!--Implementacion Oscar 15.10.2019 para no mostrar estos filtros en reporte de nomina-->
	        	          	<option value="1">Hoy</option>
	            	      	<option value="6">Ayer</option>
	                	  	<?php if ($this->_tpl_vars['id_reporte'] != '32'): ?><!--Fin de cambio Oscar 15.10.2019-->
	                	  		<option value="7">&Uacute;ltimos 7 d&iacute;as</option>
		                 		<option value="2">&Uacute;ltima semana(Lun - Dom)</option>
		                 	<?php endif; ?>
		                  	<option value="3">Este mes</option>
	    	              	<option value="9">Los &uacute;ltimos 30 d&iacute;as</option>
	        	          	<option value="10">El mes pasado</option>
	            	      	<option value="11">Los ultimos 90 d&iacute;as</option>
	            	      <?php endif; ?>
		                  <option value="5">Todo el per&iacute;odo</option>
	                	  <option value="4">Personalizado</option>
		                </select>
		    <!--Fin de cambio Oscar 12.12.2018-->
	    	          </label>
	        		  <?php if ($this->_tpl_vars['reporte'] == 1 || $this->_tpl_vars['id_reporte'] == 6 || $this->_tpl_vars['id_reporte'] == 37): ?>
						</div><!--cerramos el div que excluye el filtro de fechas-->
					  <?php endif; ?>    
	            	  <div id="muestraFec" style="display:none; margin-left:30px;">
		              	Fecha del:
		              	<input type="text" name="fecdel" id="fecdel" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
		              	al:
		              	<input type="text" name="fecal" id="fecal" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
		              </div>
					<?php endif; ?>
				</td>
		<!--implememntación Oscar 17.09.2018 para filtros de reporte 37-->
			<?php if ($this->_tpl_vars['id_reporte'] == '37'): ?>
				<td align="center" colspan="2">
					Fechas de venta más alta:<br>
					Fecha del:
					<input type="text" id="fecha_1_del" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
					Fecha al:
					<input type="text" id="fecha_1_al" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
				</td>
				<td align="center" colspan="2">
					Fechas de promedio de venta:<br>
					Fecha del:
					<input type="text" id="fecha_2_del" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
					Fecha al:
					<input type="text" id="fecha_2_al" onfocus="calendario(this)" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
				</td>
			<?php endif; ?>
		<!--fin de cambio Oscar 17.09.2018-->

			</div>
		</tr>
		<tr>
			<td>
				<?php if ($this->_tpl_vars['id_reporte'] == '10' || $this->_tpl_vars['id_reporte'] == '1' || $this->_tpl_vars['id_reporte'] == '6' || $this->_tpl_vars['id_reporte'] == '11'): ?>
					Sucursal: 
					<select name="id_sucursal" id="id_sucursal" onchange="cambiaAlma(this.value)">
						<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['sucval'],'output' => $this->_tpl_vars['suctxt']), $this);?>

					</select>
					<br>
					Almac&eacute;n: 
					<select name="id_almacen" id="id_almacen">
						<option value="-1">-Cualquiera-</option>
					</select>
					<?php if ($this->_tpl_vars['id_reporte'] == '11'): ?><!--or $id_reporte neq '34'-->
						<br>Productos:
						<select name="estado_suc" id="estado_suc">
							<option value="-1">Todos</option>
							<option value="1">Habilitados</option>
						</select>
					<?php endif; ?>
				<?php else: ?>
					<?php if ($this->_tpl_vars['id_reporte'] != '34'): ?><!--Aqui se agrega condición para que no se incluya el reporte de inventario de sucursales-->
						Sucursal: 
						<select name="id_sucursal" id="id_sucursal">
							<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['sucval'],'output' => $this->_tpl_vars['suctxt']), $this);?>

						</select>
					<?php else: ?>
						<?php  
							$sql="SELECT id_sucursal,nombre FROM sys_sucursales WHERE id_sucursal>0";
							$eje=mysql_query($sql)or die("Error!!!\n\n");
							echo "Sucursales:<br><table><tr>";
							$c=0;//inicializamos el contador en 0
							while($r=mysql_fetch_row($eje)){
								$c++;//incrementamos el contador
						 ?>
						<!--listamos las sucursales-->
							<td style="padding:15px;">
								<input type="checkbox" onclick="activa_desactiva_suc(this);" id="<?php echo 'suc_'.$c; ?>" value="<?php echo $r[0]; ?>" checked><?php echo " ".$r[1]; ?>
							</td>
						<?php 
							}
							echo '<td><input type="checkbox" id="-1" onclick="activa_desactiva_suc(this);" value="-1" checked> Todos</td>';
							echo '</tr></table>';
							echo '<input type="hidden" id="total_sucs" value="'.$c.'">';//guardamos el total de sucursales
						 ?>	
					<?php endif; ?>
				<?php endif; ?>

			</td>
			
			<td>
			
				<?php if ($this->_tpl_vars['id_reporte'] == '1' || $this->_tpl_vars['id_reporte'] == '2' || $this->_tpl_vars['id_reporte'] == '7' || $this->_tpl_vars['id_reporte'] == '8' || $this->_tpl_vars['id_reporte'] == '9' || $this->_tpl_vars['id_reporte'] == '10' || $this->_tpl_vars['id_reporte'] == '11' || $this->_tpl_vars['id_reporte'] == '31' || $this->_tpl_vars['id_reporte'] == '98' || $this->_tpl_vars['id_reporte'] == '35' || $this->_tpl_vars['id_reporte'] == '37'): ?><!--aqui se implementa el reporte 35;correspondiente a ventas de toda la sucursal Oscar 20.08.2018-->
					Familia: 
					<select name="id_categoria" id="id_categoria" onchange="cambiaSC(this.value)">
						<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['catval'],'output' => $this->_tpl_vars['cattxt']), $this);?>

					</select>
					<br>
					Tipo: 
					<select name="id_subcategoria" id="id_subcategoria" onchange="cambiaTP(this.value)">
						<option value="-1">-Cualquiera-</option>
					</select>
					<br>
					Subtipo: 
					<select name="id_tipo" id="id_tipo">
						<option value="-1">-Cualquiera-</option>
					</select>
				
				<?php else: ?>
				
					<input type="hidden" id="id_categoria" value="-1">
					<input type="hidden" id="id_subcategoria" value="-1">
					<input type="hidden" id="id_tipo" value="-1">
				
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['id_reporte'] == '98'): ?>
					<br>
					Producto: 
					<select name="id_producto" id="id_producto">
						<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['proval'],'output' => $this->_tpl_vars['protxt']), $this);?>

					</select>
					<br>
					Color: 
					<select name="id_color" id="id_color">
						<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['colval'],'output' => $this->_tpl_vars['coltxt']), $this);?>

					</select>
				<?php else: ?>
					<input type="hidden" id="id_producto" value="-1">
					<input type="hidden" id="id_color" value="-1">		
				<?php endif; ?>
				
				
				
				<?php if ($this->_tpl_vars['id_reporte'] == '6'): ?>
					Ordenar por:
					<select id="orden_rep1">
						<option value="2">Ubicaci&oacute;n de almac&eacute;n</option>
						<option value="3">Prioridad de surtimiento</option>
					</select>
					<br>
					Prioridad:
					<select id="prioridad">
						<option value="-1">-Cualquiera-</option>
						<option value="1">Urgente</option>
						<option value="2">Medio</option>
						<option value="3">Completo</option>
					</select>
					
					
				<?php else: ?>
					<input type="hidden" id="orden_rep1" value="-1">
					<input type="hidden" id="prioridad" value="-1">
				<?php endif; ?>
				
				
				<?php if ($this->_tpl_vars['id_reporte'] == '7' || $this->_tpl_vars['id_reporte'] == '9' || $this->_tpl_vars['id_reporte'] == '11'): ?>
					<br>
					Ordenar por:
					<select id="orden_rep2">
						<option value="1">Orden de lista</option>
						<option value="2">Ubicaci&oacute;n de almac&eacute;n</option>
					</select>
				<?php else: ?>
					<input type="hidden" id="orden_rep2" value="-1">
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['id_reporte'] == '30'): ?>
					Concepto:
					<select name="id_concepto" id="id_concepto">
						<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['conval'],'output' => $this->_tpl_vars['contxt']), $this);?>

					</select>
				<?php endif; ?>
			
			<!--Implementación de Oscar por reporte de Promedios 25.08.2018-->
				<?php if ($this->_tpl_vars['id_reporte'] == '34'): ?>
					Mostrar:
					<select id="fto_inc_ext">
						<option value="1">Solo Casa de las luces</option>
						<option value="2">Solo Externos</option>
						<option value="3">Todos</option>
					</select>
					<br><br>
			<!--Implementación de Oscar 17.05.2019 para filtrar el reporte de todos los inventarios por almacen -->
					Almacenes Por Sucursal: <br>
						<select id="filtro_tipo_alm" style="padding:5px;">
						<option value="-1">Todos</option>
						<option value="1">Solo almacén Principal</option>
					</select>
			<!--Fin de cambio Oscar 17.05.2019-->
				<?php endif; ?>
			<!--Fin de cambio Oscar 25.08.2018-->
			
			</td>
		</tr>
		</table>
		
		</fieldset>
		
		<table width="100%">
		
	
		<tr>
			<td>	              

              
              
              
              <input type="button" onclick="generaReporte(id_reporte.value)" class="btnreportes" value="Generar">
              <input type="button" onclick="generaExcel(id_reporte.value)" class="btnreportes" value="Exportar Excel">
              <!--<input type="button" onclick="generaPDF(id_reporte.value)" class="btnreportes" value="Exportar PDF">-->

			</td>
			<td>
              <!--
              <input type="button" value="Exportar Excel" onclick="generaExcel(id_reporte.value)">
              <input type="button" value="Exportar PDF" onclick="generaPDF(id_reporte.value)">
              <input type="button" value="Enviar por correo" onclick="enviaCorreo(id_reporte.value, campomail.value)">
              -->
              
              <!--<input type="text" value="" name="campomail" id="campomail" style="margin-top:20px; background-color:#E4E1E1; height:10px; width:140px">
              <input type="button" onclick="enviaCorreo(id_reporte.value, campomail.value)" class="btnreportes" value="Enviar por correo">
              -->
              
              
            </td>
          </tr>
        </table>


      </div>  


	
   
       <!--Comienza el bloque de tres que contendra la gráfica y tabla para después ser exportados-->
       
        <!--<section id="grafica">
          <section class="titulo_g">
              <h3><?php echo $this->_tpl_vars['titulo']; ?>
 - Gr&aacute;fica</h3>
            </section>
           <section>
            <iframe style="height=600px" width="900px" height="600px" id="resultadoGrafica"></iframe>
           </section>

         </section>-->
       
       
       
      <section id="grafica">
            <section class="titulo_g">
              <h3>Datos</h3>
            </section>
            <section>
              <!--Aqui va el grid o una tabla que contedra todo hacerca de los reportes-->
            <table id="contenidoRep">
              
             </table>
 	  		    <br>
 	  			<br>
 	  			<br>
 	  			<br>
 	  			<br> 


              <table id="contenidoRep2">
              	
              </table>
            </section>
          </section>
        
      <!--Termina el bloque de sección que sera eliminado-->
    
  	
</section>





<!--Termina la seccion contenido-->
  </div>

<script>
<?php echo '

//implementación de Oscar 06.06.2018 para reporte de inventarios de todas las sucursales
	function activa_desactiva_suc(obj){
		var valor=$(obj).attr(\'id\');
		var tam=$("#total_sucs").val();//sacamos el total de las sucursales
		if(valor==-1){//si es el check que habilita/deshabilita
			var val_asignar=false;
			if(obj.checked==true){
				val_asignar=true;
			}
			for(var i=1;i<=tam;i++){
				document.getElementById("suc_"+i).checked=val_asignar;
			}
			return true;
		}
	//si es el checkbox de alguna sucursal
		valor=valor.split("suc_");//quitamos el prefijo
		//alert(valor[1]);
		var indicador=0;
	//recorremos los checkbox para ver si estan marcados/desmarcados
		for(var i=1;i<=tam;i++){
			if(document.getElementById(\'suc_\'+i).checked==true){
				indicador+=1;//aumentamos el contador de checks marcados
			}
		}
	//marcamos/desmarcamos el check general
		if(indicador==tam){
			document.getElementById(\'-1\').checked=true;
		}else{
			document.getElementById(\'-1\').checked=false;
		}
	}
	
//fin de cambio
	function cambiaAlma(val)
	{
		var url="getAlma.php?id_sucursal="+val;
		var res=ajaxR(url);
		
		var aux=res.split(\'|\');
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}
		
		var obj=document.getElementById("id_almacen");
		obj.options.length=0;
		
		obj.options[0] = new Option(\'-Cualquiera-\', -1);
		
		for(i=1;i<aux.length;i++)
		{
			ax=aux[i].split(\'~\');
			obj.options[i] = new Option(ax[1], ax[0]);	
		}
	}

	function cambiaTP(val)
	{
		var url="getTipo.php?id_subcategoria="+val;
		var res=ajaxR(url);
		
		var aux=res.split(\'|\');
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}
		
		var obj=document.getElementById("id_tipo");
		obj.options.length=0;
		
		obj.options[0] = new Option(\'-Cualquiera-\', -1);
		
		for(i=1;i<aux.length;i++)
		{
			ax=aux[i].split(\'~\');
			obj.options[i] = new Option(ax[1], ax[0]);	
		}
	}


	function cambiaSC(val)
	{
		var url="getSubCat.php?id_categoria="+val;
		var res=ajaxR(url);
		
		var aux=res.split(\'|\');
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}
		
		var obj=document.getElementById("id_subcategoria");
		obj.options.length=0;
		
		obj.options[0] = new Option(\'-Cualquiera-\', -1);
		
		for(i=1;i<aux.length;i++)
		{
			ax=aux[i].split(\'~\');
			obj.options[i] = new Option(ax[1], ax[0]);	
		}
		
	}

	function cambiaFec(val,id)
	{
		var obj=document.getElementById(id);	
	
		if(val == 4  )
		{
			obj.style.display="block";
		}
		else
		{
			obj.style.display="none";
		}
	
	}
	
	function generaExcel(val)
	{
		if(val == -98 || val == 99)
		{
			alert(\'Elige un reporte por favor\');
			return false;
		}
		var url="reporteExcel.php?id_reporte="+val+"&fecdel="+document.getElementById(\'fecdel\').value+"&fecal="+document.getElementById(\'fecal\').value;
		url+="&tipoFec="+document.getElementById(\'fechas\').value;

		/*implementación Oscar 17.09.2018*/
		if(val==37){
			if($("#fecha_1_del").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_1_del").focus();$("#fecha_1_del").click();
				return false;
			}
			url+="&fecha_maxima_del="+$("#fecha_1_del").val();

			if($("#fecha_1_al").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_1_al").focus();$("#fecha_1_al").click();
				return false;
			}
			url+="&fecha_maxima_al="+$("#fecha_1_al").val();

			if($("#fecha_2_del").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_2_del").focus();$("#fecha_2_del").click();
				return false;
			}
			url+="&fecha_promedio_del="+$("#fecha_2_del").val();

			if($("#fecha_2_al").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_2_al").focus();$("#fecha_2_al").click();
				return false;
			}
			url+="&fecha_promedio_al="+$("#fecha_2_al").val();
		}
	/*fin de cambio Oscar 17.09.2018*/

/*Implementacion de Oscar 06.06.2018 para reporte de inventarios*/
		if(val==34){//si es reporte de inventarios
			var tam=$("#total_sucs").val();
			if(document.getElementById(\'-1\').checked==false){
			//recorremos el arreglo
				var tmp=0;
				url+=url+="&id_sucursal=";
				for(var i=1;i<=tam;i++){
				//concatenamos a la variable de la url las suucursales que se desean consultar
					if(document.getElementById("suc_"+i).checked==true){
						url+=$("#suc_"+i).val()+"~";
						tmp++;
					}			
				}//fin de for i
				if(tmp<=0){
					alert("Debe seleccionar por lo menos una sucursal!!!");
					return false;
				}
			}else{
				url+="&id_sucursal=-1";	
			}
		}else{
			url+="&id_sucursal="+document.getElementById(\'id_sucursal\').value;		
		}
/*implementación Oscar 25.08.2018 para filtro de almacenes*/
		if(val==34){
			url+="&filt_externos="+$("#fto_inc_ext").val();
			url+="&filtro_tipo_almacen="+$("#filtro_tipo_alm").val();
		}
/*filtron de cambio 25.08.2018*/
		//url+="&id_sucursal="+document.getElementById(\'id_sucursal\').value;
/*Fin de cambio 06.06.2018*/		
		var extras="";
		var adicionales="";
		//implementacion de Oscar 22-12-2017
		if(val==11){
			if(document.getElementById(\'id_sucursal\').value!=-1){
				if(document.getElementById(\'estado_suc\').value!=-1){
					extras=" AND sp.estado_suc=1";
				}else{
					//extras=" AND sp.estado_suc<=1";
				}
			}else{
				if(document.getElementById(\'estado_suc\').value!=-1){
					extras=" AND p.habilitado=1";
				}else{
					//extras=" AND p.habilitado<=1";
				}
			}
		}
	//fin de cambio 
		
		if(document.getElementById(\'id_categoria\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29)
				extras+=" AND pr.id_categoria = "+document.getElementById(\'id_categoria\').value;
			else
				extras+=" AND p.id_categoria = "+document.getElementById(\'id_categoria\').value;
		}
		
		if(document.getElementById(\'id_producto\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29)
				extras+=" AND pr.id_productos = "+document.getElementById(\'id_producto\').value;
			else
				extras+=" AND p.id_productos = "+document.getElementById(\'id_producto\').value;	
		}
		
		if(document.getElementById(\'id_color\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29)
				extras+=" AND pr.id_color = "+document.getElementById(\'id_color\').value;
			else
				extras+=" AND p.id_color = "+document.getElementById(\'id_color\').value;
		}
		
		if(document.getElementById(\'prioridad\').value != -1)
		{
			if(document.getElementById(\'prioridad\').value == 1)
				extras+=" AND Inventario <= minimo";
			if(document.getElementById(\'prioridad\').value == 2)
				extras+=" AND Inventario <= medio AND Inventario > minimo";
			if(document.getElementById(\'prioridad\').value == 3)
				extras+=" AND Inventario > medio";	
		}
		
		
		if(document.getElementById(\'id_subcategoria\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29)
				extras+=" AND pr.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
			else
				extras+=" AND p.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
		}
		
		if(document.getElementById(\'id_tipo\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29)
				extras+=" AND pr.id_subtipo = "+document.getElementById(\'id_tipo\').value;
			else
				extras+=" AND p.id_subtipo = "+document.getElementById(\'id_tipo\').value;
		}

		
		if(document.getElementById(\'id_almacen\'))
		{
			if(document.getElementById(\'id_almacen\').value != -1)
			{
				if(val == 10){
					extras+=" AND t.id_almacen_destino = "+document.getElementById(\'id_almacen\').value;
				}
				else if(val == 1){
					extras+=" AND ma.id_almacen = "+document.getElementById(\'id_almacen\').value;	
				}
				else if(val == 6){
					extras+=" AND p.id_almacen = "+document.getElementById(\'id_almacen\').value;	
				}
			/*implementación de Oscar 29.08.2018*/
				else if(val==11){
					url+="&id_de_almacen="+document.getElementById(\'id_almacen\').value;
				}
			/*fin de cambio Oscar 29.09.2018*/
			}	
		}
		
		if(document.getElementById(\'orden_rep1\').value != -1)
		{
			if(document.getElementById(\'orden_rep1\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep1\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
			if(document.getElementById(\'orden_rep1\').value == 3)
				adicionales+=" ORDER BY prioridad";		
		}
		
		if(document.getElementById(\'orden_rep2\').value != -1)
		{
			if(document.getElementById(\'orden_rep2\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep2\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
		}
		
		url+="&extras="+extras+"&adicionales="+adicionales;
		
		
		window.open(url);
	}
	
	function enviaCorreo(rep, mail)
	{
	
		if(mail == \'\')
		{
			alert("Es necesario que proporcione un correo para enviar el reporte");
			return false;
		}
		
		var url="reportePDF.php?id_reporte="+rep+"&fecdel="+document.getElementById(\'fecdel\').value+"&fecal="+document.getElementById(\'fecal\').value;
		url+="&tipoFec="+document.getElementById(\'fechas\').value+"&envio=SI&correo="+mail;
		
		url+="&id_sucursal="+document.getElementById(\'id_sucursal\').value;
		
		
		var extras="";
		var adicionales="";
		
		if(document.getElementById(\'id_categoria\').value != -1)
		{
			extras+=" AND p.id_categoria = "+document.getElementById(\'id_categoria\').value;
		}
		
		if(document.getElementById(\'id_subcategoria\').value != -1)
		{
			extras+=" AND p.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
		}
		
		if(document.getElementById(\'id_tipo\').value != -1)
		{
			extras+=" AND p.id_subtipo = "+document.getElementById(\'id_tipo\').value;
		}
		
		if(document.getElementById(\'id_almacen\'))
		{
			if(document.getElementById(\'id_almacen\').value != -1)
			{
				if(rep == 10)
					extras+=" AND t.id_almacen_destino = "+document.getElementById(\'id_almacen\').value;
				else if(rep == 1)
					extras+=" AND ma.id_almacen = "+document.getElementById(\'id_almacen\').value;	
			}	
		}
		
		if(document.getElementById(\'orden_rep1\').value != -1)
		{
			if(document.getElementById(\'orden_rep1\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep1\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
			if(document.getElementById(\'orden_rep1\').value == 3)
				adicionales+=" ORDER BY prioridad";		
		}
		
		if(document.getElementById(\'orden_rep2\').value != -1)
		{
			if(document.getElementById(\'orden_rep2\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep2\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
		}
		
		
		url+="&extras="+extras+"&adicionales="+adicionales;
		
		var res=ajaxR(url);
		
		alert(res);
	
	}
	
	function generaPDF(val)
	{
		if(val == -98 || val == 99)
		{
			alert(\'Elige un reporte por favor\');
			return false;
		}
		var url="reportePDF.php?id_reporte="+val+"&fecdel="+document.getElementById(\'fecdel\').value+"&fecal="+document.getElementById(\'fecal\').value;
		url+="&tipoFec="+document.getElementById(\'fechas\').value;
		
		url+="&id_sucursal="+document.getElementById(\'id_sucursal\').value;
		
		
		var extras="";
		var adicionales="";
		
		if(document.getElementById(\'id_categoria\').value != -1)
		{
			extras+=" AND p.id_categoria = "+document.getElementById(\'id_categoria\').value;
		}
		
		if(document.getElementById(\'id_subcategoria\').value != -1)
		{
			extras+=" AND p.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
		}
		
		if(document.getElementById(\'id_tipo\').value != -1)
		{
			extras+=" AND p.id_subtipo = "+document.getElementById(\'id_tipo\').value;
		}
		
		if(document.getElementById(\'id_almacen\'))
		{
			if(document.getElementById(\'id_almacen\').value != -1)
			{
				if(val == 10)
					extras+=" AND t.id_almacen_destino = "+document.getElementById(\'id_almacen\').value;
				else if(val == 1)
					extras+=" AND ma.id_almacen = "+document.getElementById(\'id_almacen\').value;	
			}	
		}
		
		if(document.getElementById(\'orden_rep1\').value != -1)
		{
			if(document.getElementById(\'orden_rep1\').value == 1)
				extras+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep1\').value == 2)
				extras+=" ORDER BY p.ubicacion_almacen";
			if(document.getElementById(\'orden_rep1\').value == 3)
				extras+=" ORDER BY prioridad";		
		}
		
		if(document.getElementById(\'orden_rep2\').value != -1)
		{
			if(document.getElementById(\'orden_rep2\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep2\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
		}
		
		
		url+="&extras="+extras+"&adicionales="+adicionales;
		
		
		window.open(url);
	}

	function generaReporte(val)
	{//alert(val);
		if(val == -98 || val == 99)
		{
			alert(\'Elige un reporte por favor\');
			return false;
		}
		var url="getDatosReportes.php?id_reporte="+val+"&fecdel="+document.getElementById(\'fecdel\').value+"&fecal="+document.getElementById(\'fecal\').value;
		url+="&tipoFec="+document.getElementById(\'fechas\').value;

/*Implementación Oscar 17.08.2018 para mandar variable de ajuste de inventario si es el reporte de ajuste y viene desde un listado*/
		if(val==40){
			url+="&folio_ajuste="+$("#folio_ajuste_inv").val();
		}
/*Fin de cambio*/
	
/*implementación Oscar 25.08.2018 para */
	if(val==34){
		url+="&filt_externos="+$("#fto_inc_ext").val();
		url+="&filtro_tipo_almacen="+$("#filtro_tipo_alm").val();
	}
/*fin de cambio 25.08.2018*/
	
	/*implementación Oscar 17.09.2018*/
		if(val==37){
			if($("#fecha_1_del").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_1_del").focus();$("#fecha_1_del").click();
				return false;
			}
			url+="&fecha_maxima_del="+$("#fecha_1_del").val();

			if($("#fecha_1_al").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_1_al").focus();$("#fecha_1_al").click();
				return false;
			}
			url+="&fecha_maxima_al="+$("#fecha_1_al").val();

			if($("#fecha_2_del").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_2_del").focus();$("#fecha_2_del").click();
				return false;
			}
			url+="&fecha_promedio_del="+$("#fecha_2_del").val();

			if($("#fecha_2_al").val()==\'\'){
				alert("Este filtro no puede ir vacío!!!");
				$("#fecha_2_al").focus();$("#fecha_2_al").click();
				return false;
			}
			url+="&fecha_promedio_al="+$("#fecha_2_al").val();
		}
	/*fin de cambio Oscar 17.09.2018*/

	//implementación de Oscar 06.06.2018 para reporte de todos los inventarios
		if(val==34){//si es reporte de inventarios
			var tam=$("#total_sucs").val();
			if(document.getElementById(\'-1\').checked==false){
			//recorremos el arreglo
				var tmp=0;
				url+=url+="&id_sucursal=";
				for(var i=1;i<=tam;i++){
				//concatenamos a la variable de la url las suucursales que se desean consultar
					if(document.getElementById("suc_"+i).checked==true){
						url+=$("#suc_"+i).val()+"~";
						tmp++;
					}			
				}//fin de for i
				if(tmp<=0){
					alert("Debe seleccionar por lo menos una sucursal!!!");
					return false;
				}
			}else{
				url+="&id_sucursal=-1";	
			}
		}else{
			url+="&id_sucursal="+document.getElementById(\'id_sucursal\').value;		
		}
	//fin de cambio
		var extras="";
		var adicionales="";
		
	//implementacion de Oscar 22-12-2017
		if(val==11){
			if(document.getElementById(\'id_sucursal\').value!=-1){
				if(document.getElementById(\'estado_suc\').value!=-1){
					extras=" AND sp.estado_suc=1";
				}else{
					//extras=" AND sp.estado_suc<=1";
				}
			}else{
				if(document.getElementById(\'estado_suc\').value!=-1){
					extras=" AND p.habilitado=1";
				}else{
					//extras=" AND p.habilitado<=1";
				}
			}
		}
	//fin de cambio 
		if(document.getElementById(\'id_categoria\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29 || val==35)
				extras+=" AND pr.id_categoria = "+document.getElementById(\'id_categoria\').value;
			else
				extras+=" AND p.id_categoria = "+document.getElementById(\'id_categoria\').value;
		}
		
		if(document.getElementById(\'id_producto\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29 || val==35)
				extras+=" AND pr.id_productos = "+document.getElementById(\'id_producto\').value;
			else
				extras+=" AND p.id_productos = "+document.getElementById(\'id_producto\').value;	
		}
		
		if(document.getElementById(\'id_color\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29 || val==35)
				extras+=" AND pr.id_color = "+document.getElementById(\'id_color\').value;
			else
				extras+=" AND p.id_color = "+document.getElementById(\'id_color\').value;	
		}
		
		if(document.getElementById(\'prioridad\').value != -1)
		{
			if(document.getElementById(\'prioridad\').value == 1)
				extras+=" AND Inventario <= minimo";
			if(document.getElementById(\'prioridad\').value == 2)
				extras+=" AND Inventario <= medio AND Inventario > minimo";
			if(document.getElementById(\'prioridad\').value == 3)
				extras+=" AND Inventario > medio";	
		}
		
		
		if(document.getElementById(\'id_subcategoria\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29 || val==35)
				extras+=" AND pr.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
			else
				extras+=" AND p.id_subcategoria = "+document.getElementById(\'id_subcategoria\').value;
		}
		
		if(document.getElementById(\'id_tipo\').value != -1)
		{
			if(val == 24 || val == 25 || val == 26 || val == 27 || val == 28 || val == 29 || val==35)
				extras+=" AND pr.id_subtipo = "+document.getElementById(\'id_tipo\').value;
			else
				extras+=" AND p.id_subtipo = "+document.getElementById(\'id_tipo\').value;
		}
		
		if(document.getElementById(\'id_concepto\'))
		{
			if(document.getElementById(\'id_concepto\').value != -1)
				extras+=" AND g.id_concepto = "+document.getElementById(\'id_concepto\').value;
		}
		
		if(document.getElementById(\'id_almacen\'))
		{
			if(document.getElementById(\'id_almacen\').value != -1)
			{
				if(val == 10){
					extras+=" AND t.id_almacen_destino = "+document.getElementById(\'id_almacen\').value;
				}
				else if(val == 1){
					extras+=" AND ma.id_almacen = "+document.getElementById(\'id_almacen\').value;	
				}
				else if(val == 6){
					extras+=" AND p.id_almacen = "+document.getElementById(\'id_almacen\').value;	
				}
			/*implementación de Oscar 29.08.2018*/
				else if(val==11){
					url+="&id_de_almacen="+document.getElementById(\'id_almacen\').value;
				}
			/*fin de cambio Oscar 29.09.2018*/
			}	
		}		
		if(document.getElementById(\'orden_rep1\').value != -1)
		{
			if(document.getElementById(\'orden_rep1\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep1\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
			if(document.getElementById(\'orden_rep1\').value == 3)
				adicionales+=" ORDER BY prioridad";		
		}
		
		if(document.getElementById(\'orden_rep2\').value != -1)
		{
			if(document.getElementById(\'orden_rep2\').value == 1)
				adicionales+=" ORDER BY p.orden_lista";
			if(document.getElementById(\'orden_rep2\').value == 2)
				adicionales+=" ORDER BY p.ubicacion_almacen";
		}
		
		
		url+="&extras="+extras+"&adicionales="+adicionales;
		
		//alert(url);return false;
		res=ajaxR(url);
		
		aux=res.split(\'|\');
		
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}	
		else
		{
			//alert(aux[1]);
			document.getElementById(\'contenidoRep\').innerHTML=aux[1];
			
			//url=url.replace(\'getDatosReportes.php\', \'reporteGrafico.php\');
			
			//alert(url);
			'; ?>

			url+="&titulo=<?php echo $this->_tpl_vars['titulo']; ?>
";
			<?php echo '
			
			//alert(url);
			
			//document.getElementById(\'resultadoGrafica\').src=url;
			
		}	
		
		
	}


	function balance(val)
	{
		var url = "getBalance.php";

		res = ajaxR(url);

		aux=res.split(\'|\');
		
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}	
		else
		{
			//alert(aux[1]);
			document.getElementById(\'contenidoRep\').innerHTML=aux[1];
			
			
		}	
		

	}

	function balanceExcel()
	{
		var url = "repExcel.php";

	 		window.open(url);
	}

	function balancePdf()
	{
		var url = "repPdf.php";

	 		window.open(url);

	}

	function envioPdf(mail)
	{
		
		
		var url="repPdf.php?envio=SI&correo="+mail;
		
		var res=ajaxR(url);
		
		alert(res);
	

	} 

'; ?>

</script>

<!--implementación Oscar 17.08.2018 para cargar en automático los reportes que vienen del listado de Ajustes de Inventario-->
	<?php if ($this->_tpl_vars['folio_ajuste'] != ''): ?>
  		<?php echo '<script>generaReporte(\'40\');</script>'; ?>

 	<?php endif; ?>
<!--Fin de cambio-->


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('aktUser' => $this->_tpl_vars['username'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>