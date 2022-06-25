<?php /* Smarty version 2.6.13, created on 2022-02-23 15:43:32
         compiled from especiales/product_provider_inventory.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'especiales/product_provider_inventory.tpl', 37, false),)), $this); ?>
<!--link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.css"-->
<link href="estilo_final.css" rel="stylesheet" type="text/css" />
<link href="css/demos.css" rel="stylesheet" type="text/css" />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div id="campos">  
<div id="titulo">Inventario ( Proveedor - Producto )</div>
<br><br>

<div id="filtros">
	<form id="form1" name="form1" method="post" action="">
		<!--Implementación de Buscador (Oscar)--> 
	 <div id="filtros">
	 		<p align="left" style=""><b>Buscador:</b>
				<input type="text" style="width:50%;" onkeyup="buscaLista(this,'<?php echo $this->_tpl_vars['datos'][0]; ?>
','<?php echo $this->_tpl_vars['tabla']; ?>
', this.form);"><!--Cambio de Oscar 24.05.2018 transf impresas en verde; se envía variable de tabla -->
			</p>
		<table border="0">
        	<tr>
          		<td class="motivo">Nombre</td>
          		<td>
          			<input name="valor" type="text" class="barra2" id="text1"/>
          		</td>
          		<td>&nbsp;</td>
          		<td class="motivo">Cant. mayor a</td>
          		<td>
          			<input name="mayor" type="text" class="barra2" id="text1" size="10"/>
          		</td>
          		<td class="motivo">Cant. menor a</td>
          		<td>
          			<input name="menor" type="text" class="barra2" id="text1" size="10"/>
          		</td>
          		<td>&nbsp;</td>
          		<?php if ($this->_tpl_vars['multi'] == 1): ?>
          			<td class="motivo">Almac&eacute;n</td>
          			<td>
          				<select name="sucur">
          					<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['sucval'],'output' => $this->_tpl_vars['suctxt']), $this);?>

          				</select>
          			</td>
          		<?php else: ?>
          			<input type="hidden" name="sucur" value="<?php echo $this->_tpl_vars['sucursal_id']; ?>
">	
          		<?php endif; ?>
          		
          		<td>&nbsp;</td>
          		<td>
            		<input name="button" type="button" class="boton" id="button" value="Buscar" onclick="busca(this.form)"/>
          		</td>	
          	</tr>
    	</table>      	
	</form>
</div>

<div id="bg_seccion">
	<!--div class="name_module" align="center">
		<p>Productos</p>		    
	</div-->
	<div id="cosa1" style="width:165% !important;">
		<br />
		<table align="center">
	    	<tr>
                <td align="center">
					<table id="productos" cellpadding="0" cellspacing="0" Alto="255" conScroll="S" validaNuevo="false" AltoCelda="25"
					auxiliar="0" ruta="../../img/grid/" validaElimina="false" Datos="../ajax/especiales/product_provider_inventory.php?tipo=1"
					verFooter="N" guardaEn="False" listado="S" class="tabla_Grid_RC" paginador="S" datosxPag="30" pagMetodo='php'
					ordenaPHP="S" title="Listado de Registros">
						<tr class="HeaderCell">
							<td tipo="oculto" width="0" offsetWidth="0" campoBD="id">id_producto</td>
						<!---->
							<td tipo="texto" width="80" offsetWidth="200" modificable="N" align="left" campoBD="nombre">Orden Lista</td>
							<td tipo="texto" width="110" offsetWidth="200" modificable="N" align="left" campoBD="nombre">Clave</td>
						<!---->	
							<td tipo="texto" width="300" offsetWidth="400" modificable="N" align="left" campoBD="nombre">Nombre</td>
							<td tipo="texto" width="150" offsetWidth="150" modificable="N" align="left" campoBD="familia">Almac&eacute;n</td>
							<td tipo="texto" width="80" offsetWidth="120" modificable="N" align="right" campoBD="cantidad">Cantidad</td>
							<td width="60" offsetWidth="60" tipo="libre" valor="Ver" align="center">
								<img class="vermini" src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/vermini.png" height="22" width="22" border="0"  onclick="verProd('#')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Ver" title="Ver Registro"/>
							</td>	
						</tr>
					</table>
					<script>	  	
						CargaGrid('productos');
					</script>
				</td>	
			</tr>
		</table>
	</div>	
</div>	
</div>

<script>
	<?php echo '
	var datos = new Array();
	window.onload = function (e){
		//setState();
	}
//funcion para filtrar resultados
	function busca(f){
		RecargaGrid(\'productos\', \'../ajax/especiales/product_provider_inventory.php?tipo=1\'+"&sucur="+f.sucur.value+"&nombre="+f.valor.value+"&cantmayora="+f.mayor.value+"&cantmenora="+f.menor.value);
	}
//funcion para ir al producto
	function verProd(pos){
		id=celdaValorXY(\'productos\', 0, pos);
		window.open("../general/contenido.php?aab9e1de16f38176f86d7a92ba337a8d=ZWNfcHJvZHVjdG9z&a1de185b82326ad96dec8ced6dad5fbbd=Mg==&a01773a8a11c5f7314901bdae5825a190="+id+"&bnVtZXJvX3RhYmxh=MA==");
	}
//funcion para buscar
	function buscaLista( obj, gr, tabla_list, f ){
		var obj_b=obj.value;
		//alert(obj_b.length);
		if(obj_b.length<3){
			if(obj_b.length<=1){
		//CargaGrid(\'listado\');
			}
		}
		//'; ?>

		//var url="datosListados.php?id_listado=<?php echo $this->_tpl_vars['datos'][0]; ?>
";
			var url="../ajax/especiales/product_provider_inventory.php?tipo=2&sucur="+f.sucur.value+"&nombre="+f.valor.value+"&cantmayora="+f.mayor.value+"&cantmenora="+f.menor.value;
		//<?php echo '

		url+="&valor="+obj_b;//&campo="+f.campo.value+"&operador="+f.operador.value+"
		RecargaGrid(\'productos\',url,tabla_list);//se envía la variable de la tabla de listado para pintar de verde transferencias ya imprimidas Oscar 24.05.2018	
	}	
	
	'; ?>

	
</script>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 