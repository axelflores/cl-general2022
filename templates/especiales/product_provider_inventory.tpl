<!--link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.css"-->
<link href="estilo_final.css" rel="stylesheet" type="text/css" />
<link href="css/demos.css" rel="stylesheet" type="text/css" />

{include file="_header.tpl" pagetitle="$contentheader"}
  <div id="campos">  
<div id="titulo">Inventario ( Proveedor - Producto )</div>
<br><br>

<div id="filtros">
	<form id="form1" name="form1" method="post" action="">
		<!--Implementación de Buscador (Oscar)--> 
	 <div id="filtros">
	 		<p align="left" style=""><b>Buscador:</b>
				<input type="text" style="width:50%;" onkeyup="buscaLista(this,'{$datos[0]}','{$tabla}', this.form);"><!--Cambio de Oscar 24.05.2018 transf impresas en verde; se envía variable de tabla -->
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
          		{if $multi eq 1}
          			<td class="motivo">Almac&eacute;n</td>
          			<td>
          				<select name="sucur">
          					{html_options values=$sucval output=$suctxt}
          				</select>
          			</td>
          		{else}
          			<input type="hidden" name="sucur" value="{$sucursal_id}">	
          		{/if}
          		
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
								<img class="vermini" src="{$rooturl}img/vermini.png" height="22" width="22" border="0"  onclick="verProd('#')" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" alt="Ver" title="Ver Registro"/>
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
	{literal}
	var datos = new Array();
	window.onload = function (e){
		//setState();
	}
//funcion para filtrar resultados
	function busca(f){
		RecargaGrid('productos', '../ajax/especiales/product_provider_inventory.php?tipo=1'+"&sucur="+f.sucur.value+"&nombre="+f.valor.value+"&cantmayora="+f.mayor.value+"&cantmenora="+f.menor.value);
	}
//funcion para ir al producto
	function verProd(pos){
		id=celdaValorXY('productos', 0, pos);
		window.open("../general/contenido.php?aab9e1de16f38176f86d7a92ba337a8d=ZWNfcHJvZHVjdG9z&a1de185b82326ad96dec8ced6dad5fbbd=Mg==&a01773a8a11c5f7314901bdae5825a190="+id+"&bnVtZXJvX3RhYmxh=MA==");
	}
//funcion para buscar
	function buscaLista( obj, gr, tabla_list, f ){
		var obj_b=obj.value;
		//alert(obj_b.length);
		if(obj_b.length<3){
			if(obj_b.length<=1){
		//CargaGrid('listado');
			}
		}
		//{/literal}
		//var url="datosListados.php?id_listado={$datos[0]}";
			var url="../ajax/especiales/product_provider_inventory.php?tipo=2&sucur="+f.sucur.value+"&nombre="+f.valor.value+"&cantmayora="+f.mayor.value+"&cantmenora="+f.menor.value;
		//{literal}

		url+="&valor="+obj_b;//&campo="+f.campo.value+"&operador="+f.operador.value+"
		RecargaGrid('productos',url,tabla_list);//se envía la variable de la tabla de listado para pintar de verde transferencias ya imprimidas Oscar 24.05.2018	
	}	
	
	{/literal}
	
</script>


{include file="_footer.tpl" pagetitle="$contentheader"} 