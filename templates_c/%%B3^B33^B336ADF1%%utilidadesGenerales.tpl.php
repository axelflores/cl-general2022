<?php /* Smarty version 2.6.13, created on 2022-06-09 13:49:45
         compiled from general/utilidadesGenerales.tpl */ ?>

<?php echo '
	<script type="text/javascript">
		function show_list_order_msg(){
			var message = \'<div id="l_o_emergent" style="position:relative; background-color : white;" tabindex="1"><p align="center" style="color:red; font-size:300%;">Importante :</p>\';
			message += \'<p style="color:black; font-size:200%;">Verifique los códigos de barras.</p>\';
			message += \'<p align="center"><button onclick="close_emergent();">Aceptar</button></p></div>\';
			$( \'#contenido_emergente_global\' ).html( message );
			$( \'#ventana_emergente_global\' ).css( \'display\', \'block\' );
			$( \'#l_o_emergent\' ).focus();
		}

		function close_emergent(){
			$( \'#contenido_emergente_global\' ).html( \'\' );
			$( \'#ventana_emergente_global\' ).css( \'display\', \'none\' );
		}
	/*oscar 20202 ( desactivar caja, paquete )*/
		function just_piece( obj, pos ){
			return false;
			//alert(  );
			if( $( \'#proveedorProducto_18_\' + pos ).attr( \'valor\' ) == \'1\' ){
				if( confirm( "Si el tratamiento es por pieza se desactivará CAJA y PAQUETE\\nDesea continuar?" ) == true ){
					$( \'#proveedorProducto_10_\' + pos ).attr( \'valor\', \'0\' );
					$( \'#proveedorProducto_10_\' + pos ).html( \'0\' );
					$( \'#proveedorProducto_11_\' + pos ).attr( \'valor\', \'\' );
					$( \'#proveedorProducto_11_\' + pos ).html( \'\' );
					$( \'#proveedorProducto_14_\' + pos ).attr( \'valor\', \'0\' );
					$( \'#proveedorProducto_14_\' + pos ).html( \'0\' );
					$( \'#proveedorProducto_15_\' + pos ).attr( \'valor\', \'\' );
					$( \'#proveedorProducto_15_\' + pos ).html( \'\' );
				}else{
					$( \'#proveedorProducto_18_\' + pos ).attr( \'valor\', \'0\' );
					$( \'#cproveedorProducto_18_\' + pos ).removeAttr( \'checked\' );
				}
			}/*else{
				alert( \'no checked\' );
			}*/
		}

	/*implementación Oscar 12.02.2019 para calcular precio por caja en grid de proveedor producto*/
		function cambia_precio_caja_proveedor(pos,grid){
			//alert(pos);
			var prc_pza=parseFloat($("#"+grid+"_5_"+pos).html().trim());
			if(isNaN(prc_pza)){
				alert("El precio por pieza no puede ir vacío!!!");
				$("#"+grid+"_5_"+pos).html(\'0\');
				$("#"+grid+"_5_"+pos).attr("valor",\'0\');
				return false;
			}

			var pza_caja=parseFloat($("#"+grid+"_14_"+pos).html().trim());
			var precio_caja=Math.round(parseFloat(pza_caja*prc_pza),2);
			$("#"+grid+"_17_"+pos).html(precio_caja);
			$("#"+grid+"_17_"+pos).attr("valor",precio_caja);

			//valorXY(grid, 6, pos, 0);

		}
	/*Fin de cambio Oscar 12.02.2019*/

	/*Implementación Oscar 2022 para limpieza de productos*/
		function reset_product(){
			if( !confirm( "¿Realmente desea resetaer el producto?\\nEsta acción ELIMINARÁ toda la INFORMACIÓN del producto" ) ){
				return false;
			}
			product_id = $( \'#id_productos\' ).val();
			$.ajax({
				type : \'post\',
				url : \'../especiales/reset_product.php\',
				data : { id : product_id },
				success : function ( dat ){
					if( dat == \'ok\' ){
						alert( \'El producto fue reseteado exitosamente\' );
						location.reload();
					}else{
						alert( dat );
						return false;
					}
				}
			});	
		}
	/*implementacion de Oscar 2021 para evitar que se capturen caracteres especiales*/
		function evitar_simbolos_especiales(e, obj){
			$(obj).val($(obj).val().split(\',\').join(\'\'));
			$(obj).val($(obj).val().split(\'/\').join(\'\'));
			$(obj).val($(obj).val().split(\'\\\\\').join(\'\'));
			$(obj).val($(obj).val().split(\'|\').join(\'\'));
			$(obj).val($(obj).val().split(\' \').join(\'\'));
		}
	/*implementacion de Oscar 2021 para enviar mensaje en cambio de estacionalidades*/
		function lanza_aviso_cambio_estacionalidad(){
			var txt_description = "<h1 style=\\"color:white; top :800px; position : relative; margin:30px;\\"><b>"
			+ "Antes de generar la estacionalidad inicial de la temporada debe de estar en estacionalidad alta y antes de seleccionar la estacionalidad final debe de generarse la estacionalidad final de cada sucursal"
			+ "</b><br /><br /><br />"
			+ "<center><button type=\\"button\\" onclick=\\"document.getElementById(\'btn_cerrar_emergente_global\').click();\\""
			+ " style=\\"padding : 20px; font-size : 20px; border-radius : 15px;\\">Aceptar</button></center>"
			+ "</h1>";
			$( \'#contenido_emergente_global\' ).html( txt_description );
			$( \'#ventana_emergente_global\' ).css( \'display\', \'block\' );
		}

		function valida_codigo_barras ( obj, pos, cell, grid ){
		//valida que el código de barras no esté en el grid
			/*if( !search_in_grid_x( $( \'#\' + grid + \'_\' + cell +\'_\' + pos ).html(), pos, cell ) ){*/
			if ( ! check_codigo_barras_final() ){
				alert( "El código de barras ya existe para este producto");
				setTimeout( function (){ 
					$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).html(\'\');
					$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).attr(\'valor\', \'\');
					$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).val(\'\');
					//$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).click();
					$( \'#c\' + grid + \'_\' + cell +\'_\' + pos ).val(\'\');
					}, 300);
				return false;
			}

			$.ajax({
				type : \'post\',
				url : \'../especiales/validacion_codigo_barras.php\',
				data : { 
					barcode : $( \'#\' + grid + \'_\' + cell +\'_\' + pos ).html(),
					key : $(\'#\' + grid + \'_1_\' + pos ).html(),
					type : cell 
				},
				success : function ( dat ){
					if ( dat != \'ok\' ){
						alert( dat );						
						$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).html(\'\');
						$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).attr(\'valor\', \'\');
						$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).val(\'\');
						//$( \'#\' + grid + \'_\' + cell +\'_\' + pos ).click();
						$( \'#c\' + grid + \'_\' + cell +\'_\' + pos ).val(\'\');
						return false;
					}
				}
			});
		}
//verifica registro en la misma fila
		/*function search_in_grid_x( new_barcode_value, pos, cell ){
			var num=NumFilas(\'proveedorProducto\');//numero de filas en el grid
			for ( var i = 0; i < num; i++ ){
				if( ($( \'#proveedorProducto_6_\' + i ).html().trim() == new_barcode_value.trim() && pos != i) ){
					return false;
				}
				if( ($( \'#proveedorProducto_7_\' + i ).html().trim() == new_barcode_value.trim() && pos != i) ){
					return false;
				}
				if( ($( \'#proveedorProducto_8_\' + i ).html().trim() == new_barcode_value.trim() && pos != i) ){
					return false;
				}
				if( ($( \'#proveedorProducto_11_\' + i ).html().trim() == new_barcode_value.trim() && pos != i) ){
					return false;
				}
				if( ($( \'#proveedorProducto_12_\' + i ).html().trim() == new_barcode_value.trim() && pos != i) ){
					return false;
				}
			}
			return true;
		}*/
//verificacion final del código de barras
		function check_codigo_barras_final(){
			var num=NumFilas(\'proveedorProducto\');//numero de filas en el grid
			var existentes = new Array();
		//recolecta todos los datos
			for ( var i = 0; i < num; i++ ){
				existentes.push( $( \'#proveedorProducto_6_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_7_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_8_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_11_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_12_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_15_\' + i ).html().trim() );
				existentes.push( $( \'#proveedorProducto_16_\' + i ).html().trim() );
			}
			for (var i = 0; i < existentes.length; i++) {
		        for (var j = 0; j < existentes.length; j++) {
		            if ( existentes[i] == existentes[j] 
		            	&& i != j 
		            	&& existentes[i].trim() != \'\'
		            	&& existentes[j].trim() != \'\'
		            	&& existentes[i].trim() != \'&nbsp;\'
		            	&& existentes[j].trim() != \'&nbsp;\' ) {
		                return false;
		            }
	         	}
		   	}
			return true;
		}

/*funciones para ocultar / mostrar columnas del grid*/
		function hide_grid_accordion( column_number, grid_name ){
			var num=NumFilas(\'proveedorProducto\');//numero de filas en el grid

			$( \'#H\' + grid_name + column_number ).css(\'width\', \'35px\');
			/*$( \'#HproveedorProducto\' + column_number ).val(\'CB1>\');*/
			$( \'#H\' + grid_name + column_number ).attr(\'onclick\', \'show_grid_accordion(\' + column_number + \', \\\'\' + grid_name + \'\\\')\');
			$( \'#H\' + grid_name + column_number ).attr(\'title\', \'Código de Barras\');
			for ( var i = 0; i <= num; i++ ){
				$( \'#\' + grid_name + \'_\' + column_number + \'_\' + i ).css(\'width\', \'35px\');
				$( \'#\' + grid_name + \'_\' +  column_number + \'_\' + i ).css(\'color\', \'#f1f1f1\');
			}
		} 
		function show_grid_accordion( column_number, grid_name ){
			var num=NumFilas(\'proveedorProducto\');//numero de filas en el grid
			$( \'#H\' + grid_name + column_number ).css(\'width\', \'120px\');
			$( \'#H\' + grid_name + column_number ).attr(\'onclick\', \'hide_grid_accordion(\' + column_number + \', \\\'\' + grid_name + \'\\\')\');
			for ( var i = 0; i <= num; i++ ){
				$( \'#\' + grid_name + \'_\' + column_number + \'_\' + i ).css(\'width\', \'120px\');
				$( \'#\' + grid_name + \'_\'  + column_number + \'_\' + i ).css(\'color\', \'#333\');
			}
		} 

	</script>
'; ?>