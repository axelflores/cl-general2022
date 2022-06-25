<?php
	include( '../../../../../config.ini.php' );
	include( '../../../../../conexionMysqli.php' );

	$action = $_POST['fl'];
	switch ( $action ) {
		case 'getProviders':
			$id = $_POST['p_k'];
			$counter = $_POST['c'];
			echo build_table_product_provider( $id , $counter, $_POST['reception_detail_id'], $link );
		break;

		case 'getRow' : 
			$counter = $_POST['current_counter'];
			$id = $_POST['p_k'];
		//consulta indormacion del producto
			$sql  = "SELECT '',id_productos, nombre, '', '', '', '','', '', '', ''  FROM ec_productos
			WHERE id_productos = '{$id}'";
			$exc = $link->query( $sql ) or die( "Error al consultar datos de la nueva fila de proveedor producto : " . $link->error );
			$r = $exc->fetch_row();
			echo getRow( $r, $counter, $link );
		break;
		
		case 'saveProductProviders' : 
			echo saveProductProviders( $_POST['pp'], $_POST['p_k'], $link );
		break;

		case 'omitProductProvider' : 
			echo omitProductProvider( $_POST['p_k'], $link );
		break;

		default:
			die( "Permission Denied!" );
		break;
	}

	function getRow( $r, $counter, $link, $is_temporal = null ){
		$providers_combo = getComboProviders( $r[2], $counter, $link );
		$resp = "<tr id=\"product_provider_{$counter}\" " . ( $is_temporal != null ? "class=\"prod_prov_tmp_row\"" : "" ) . " >";
			$resp .= "<td class=\"no_visible\">{$r[0]}</td>";
			$resp .= "<td class=\"no_visible\" id=\"pp_1_{$counter}\">{$r[1]}</td>";
			$resp .= "<td id=\"p_p_2_1_{$counter}\"value=\"{$r[2]}\">" . $providers_combo . "</td>";
			
			$resp .= "<td id=\"pp_3_{$counter}\" onclick=\"editaCelda(3, {$counter}, 'pp_');\">{$r[3]}</td>";
			$resp .= "<td id=\"pp_4_{$counter}\" onclick=\"editaCelda(4, {$counter}, 'pp_');\">{$r[11]}</td>";
			$resp .= "<td id=\"pp_5_{$counter}\" onclick=\"editaCelda(5, {$counter}, 'pp_');\">{$r[12]}</td>";

			$resp .= "<td id=\"pp_6_{$counter}\" onclick=\"editaCelda(6, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[4]}</td>";
			$resp .= "<td id=\"pp_7_{$counter}\" onclick=\"editaCelda(7, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[5]}</td>";
			$resp .= "<td id=\"pp_8_{$counter}\" onclick=\"editaCelda(8, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[6]}</td>";
			$resp .= "<td id=\"pp_9_{$counter}\" onclick=\"editaCelda(9, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[7]}</td>";
			$resp .= "<td id=\"pp_10_{$counter}\" onclick=\"editaCelda(10, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[8]}</td>";
			$resp .= "<td id=\"pp_11_{$counter}\" onclick=\"editaCelda(11, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[9]}</td>";
			$resp .= "<td id=\"pp_12_{$counter}\" onclick=\"editaCelda(12, {$counter}, 'pp_', 'validateNoRepeatBarcode( this );');\">{$r[10]}</td>";
			$resp .= "<td id=\"pp_13_{$counter}\" class=\"no_visible\">{$r[13]}</td>";
			$resp .= "<td id=\"pp_14_{$counter}\" value=\"$r[14]\"><input type=\"checkbox\"" . ( $r[14] == 1 ? ' checked' : '' ) 
			. " onchange=\"just_piece( this, {$counter} );\"></td>";
			//$resp .= "<td id=\"pp_11_{$counter}\" onclick=\"editaCelda(11, {$counter}, 'pp_')\">{$r[11]}</td>";
			$resp .= "<td id=\"pp_15_{$counter}\">
					<button 
						type=\"button\"
						onclick=\"remove_product_provider( {$counter} );\"
					>
						X
					</td>";
		$resp .= '</tr>';
		return $resp;
	}

	function getComboProviders( $current_provider, $counter, $link ){
		$sql = "SELECT id_proveedor, nombre_comercial FROM ec_proveedor WHERE id_proveedor > 1";
		$exc = $link->query( $sql ) or die( "Error al consultar proveedores : " . $link->error );
		$resp = "<select id=\"pp_2_{$counter}\" onchange=\"changeProvider( this, {$counter} );\" class=\"form-control-select\">";
		while ( $r = $exc->fetch_row() ) {
			$resp .= "<option value=\"{$r[0]}\"";
			$resp .= ( $r[0] == $current_provider ? ' selected' : '' );
			$resp .= ">{$r[1]}</option>";
		}
		$resp .= "</select>";
		return $resp;
	}

	function build_table_product_provider( $id, $current_count, $reception_detail_id, $link ){
		$resp = '';
		$sql = "SELECT 
						pp.id_proveedor_producto,
						p.id_productos,
						pp.id_proveedor,/**/
						pp.clave_proveedor,
						pp.codigo_barras_pieza_1,
						pp.codigo_barras_pieza_2,
						pp.codigo_barras_pieza_3,
						pp.codigo_barras_presentacion_cluces_1,
						pp.codigo_barras_presentacion_cluces_2,
						pp.codigo_barras_caja_1,
						pp.codigo_barras_caja_2, 
						pp.presentacion_caja,
						pp.piezas_presentacion_cluces,
						'',
						pp.solo_pieza
					FROM ec_productos p
					LEFT JOIN ec_proveedor_producto pp ON pp.id_producto = p.id_productos
					WHERE p.id_productos = '{$id}'";

		$exc = $link->query( $sql ) or die( "Error al consultar la lista de proveedores para este producto : " . $link->error );

		$sql = "SELECT nombre FROM ec_productos WHERE id_productos = '{$id}'";
		$stm = $link->query( $sql ) or die( "Error al consultar el nombre del producto : " . $link->error );
		$product_name = $stm->fetch_row();
		$product_name = $product_name[0];
		$resp .= '<table class="table table-striped product_provider_detail">';
		$resp .= '<thead>';
			$resp .= '<tr><th colspan="12" style="font-size : 200%; color : gray;">' . $product_name . '</tr>';
			$resp .= '<tr>';
				$resp .= '<th class="no_visible">Id_p</th>';
				$resp .= '<th class="no_visible">Id_pp</th>';
				/*$resp .= '<th>Nombre</th>';*/
				$resp .= '<th>Proveedor</th>';
				$resp .= '<th>Modelo</th>';
				$resp .= '<th>Pzs x caja</th>';
				$resp .= '<th>Pzs x paquete</th>';
				$resp .= '<th>C_B_PZA_1</th>';
				$resp .= '<th>C_B_PZA_2</th>';
				$resp .= '<th>C_B_PZA_3</th>';
				$resp .= '<th>C_B_PQ_1</th>';
				$resp .= '<th>C_B_PQ_2</th>';
				$resp .= '<th>C_B_CJ_1</th>';
				$resp .= '<th>C_B_CJ_2</th>';
				$resp .= '<th>Solo Pza</th>';
				$resp .= '<th>Quitar</th>';
			$resp .= '</tr>';
		$resp .= '</thead>';
		$counter = 0;
		while ( $r = $exc->fetch_row() ) {
			$counter ++;
			
			$resp .= getRow( $r, $counter, $link );
		}
	//busca si tiene un nuevo proveedor para agregar
		$sql = "SELECT 
					'',
					rbd.id_producto,
					rb.id_proveedor,
					rbd.modelo,
					rbd.c_b_pieza,
					'',
					'',
					rbd.c_b_paquete,
					'',
					rbd.c_b_caja,
					'', 
					rbd.piezas_por_caja,
					rbd.piezas_por_paquete,
					rbd.id_recepcion_bodega_detalle,
					0
				FROM ec_recepcion_bodega_detalle rbd
				LEFT JOIN ec_recepcion_bodega rb ON rb.id_recepcion_bodega = rbd.id_recepcion_bodega
				WHERE rbd.id_proveedor_producto IS NULL
				AND rbd.omitir_p_p = 0
				AND rbd.id_recepcion_bodega_detalle IN( {$reception_detail_id} )
				AND rbd.id_producto = '{$id}'";
		$stm = $link->query( $sql ) or die("Error al consutar proveedores productos capturados en la recepcion : " . $link->error );
		while ( $r = $stm->fetch_row() ) {
			$counter ++;
			$resp .= getRow( $r, $counter, $link, 1 );
		}

		$resp .= '</table>';
	//boton de agregar
		$resp .= "<div class=\"row\">";
			$resp .= "<div class=\"col-6\">";
				$resp .= "<button 
							type=\"button\"
							class=\"btn btn-primary\"
							onclick=\"add_row( 'provider', '.product_provider_detail', {$id} );\"
						>
							<i class=\"icon-plus\">Agregar Fila</i>
						</button>";
			$resp .= "</div>";
			$resp .= "<div class=\"col-6\">";
			//boton de guardar
				$resp .= "<button 
							type=\"button\"
							class=\"btn btn-success\"
							onclick=\"save_product_providers( 'providers', '.product_provider_detail', {$id}, {$current_count} );\"
						>
							<i class=\"icon-floppy\">Guardar Cambios</i>
						</button>";
			$resp .= "</div>";
		$resp .= "</div>";
		
		return $resp;
	}	

	function saveProductProviders( $product_providers, $product_id, $link ){
		$providers = explode( '|', $product_providers);
		//die( $product_providers);
		$sql = array();
		
		$sql[0] = "DELETE FROM ec_proveedor_producto 
		WHERE id_producto = '{$product_id}'";
	//generacion de las consultas
		foreach ($providers as $key => $product_provider) {
			if( $key > 1 ){
				$provider = explode('~', $product_provider);
				$sql[0] .= " AND id_proveedor_producto != '{$provider[0]}'";
				
				$sql_aux = ($provider[0] != '' && $provider[0] != null ? "UPDATE" : "INSERT INTO") . " ec_proveedor_producto SET ";
				
				$sql_aux .= "clave_proveedor = '{$provider[3]}',";
				$sql_aux .= "id_proveedor = '{$provider[2]}',";
				$sql_aux .= "presentacion_caja = '{$provider[4]}',";
				$sql_aux .= "piezas_presentacion_cluces = '{$provider[5]}',";
				$sql_aux .= "codigo_barras_pieza_1 = '{$provider[6]}',";
				$sql_aux .= "codigo_barras_pieza_2 = '{$provider[7]}',";
				$sql_aux .= "codigo_barras_pieza_3 = '{$provider[8]}',";
				$sql_aux .= "codigo_barras_presentacion_cluces_1 = '{$provider[9]}',";
				$sql_aux .= "codigo_barras_presentacion_cluces_2 = '{$provider[10]}',";
				$sql_aux .= "codigo_barras_caja_1 = '{$provider[11]}',";
				$sql_aux .= "codigo_barras_caja_2 = '{$provider[12]}',";
				$sql_aux .= "solo_pieza = '{$provider[14]}'";
				$sql_aux .= ( $provider[0] != '' ? " WHERE id_proveedor_producto = '{$provider[0]}'" : ", id_producto = '{$product_id}'" );
				
				$stm = $link->query( $sql_aux )or die( "{$key} : Error al guardar los proveedores del producto : " . $link->error );
				
				if( $provider[13] != null && $provider[13]!= '' && ( $provider[0] == '' || $provider[0] == null) ){
					$new_poduct_provider_insert = $link->insert_id;
					$sql[0] .= " AND id_proveedor_producto != '{$new_poduct_provider_insert}'";
				//actualiza el proveedor_producto en la recepcion de bodega si aplica
					$sql_aux = "UPDATE ec_recepcion_bodega_detalle SET id_proveedor_producto = '{$new_poduct_provider_insert}' 
						WHERE id_recepcion_bodega_detalle = '{$provider[13]}'";
					//echo $sql_aux;
					//array_push( $sql, $sql_aux);
					$stm = $link->query( $sql_aux ) or die( "{$key} : Error al actualizar los proveedores del producto en relación a la recepción: " . $link->error );
				}
			}
		}
		foreach ($sql as $key2 => $query) {
			$stm = $link->query( $query )or die( "{$key2} : Error al actualizar los proveedores del producto en relación a la recepción: " . $link->error );
		}
	//14. Actualiza los códigos de barras de acuerdo al proveedor-producto ( Oscar 2022 )
		$sql = "UPDATE ec_proveedor_producto pp
				LEFT JOIN ec_productos p ON pp.id_producto = p.id_productos SET 
				pp.codigo_barras_pieza_1 = 
					IF( pp.codigo_barras_pieza_1 = '' OR pp.codigo_barras_pieza_1 IS NULL,
					CONCAT( pp.id_proveedor_producto, ' ', p.orden_lista ), 
					pp.codigo_barras_pieza_1 ),
				pp.codigo_barras_presentacion_cluces_1 = 
					IF( (pp.codigo_barras_presentacion_cluces_1 = '' OR pp.codigo_barras_presentacion_cluces_1 IS NULL ) AND pp.solo_pieza = 0,
						IF( pp.piezas_presentacion_cluces > 1,
							CONCAT( pp.id_proveedor_producto, ' PQ', pp.piezas_presentacion_cluces, ' ', p.orden_lista ),
							''
						), 
						IF( pp.solo_pieza = 0 , pp.codigo_barras_presentacion_cluces_1, '') 
					),
				pp.codigo_barras_caja_1 = 
					IF( (pp.codigo_barras_caja_1 = '' OR pp.codigo_barras_caja_1 IS NULL) AND pp.solo_pieza = 0,
						IF(	pp.presentacion_caja > 1,
							CONCAT( pp.id_proveedor_producto, ' CJ', pp.presentacion_caja, ' ', p.orden_lista),
							''
						),
						IF( pp.solo_pieza = 0, pp.codigo_barras_caja_1, '' )
					)
				WHERE pp.id_producto = '{$product_id}'";
		$stm = $link->query( $sql )or die( "3 : Error al actualizar los proveedores del producto en relación a la recepción: " . $link->error );

		return 'Proveedores del producto guardados correctamente!';
	}
	function omitProductProvider( $id, $link ){
		$sql = "UPDATE ec_recepcion_bodega_detalle SET omitir_p_p = 1 WHERE id_recepcion_bodega_detalle = '{$id}'";
		$stm = $link->query( $sql ) or die( "Error al omitir proveedor producto : " . $link->error );
		return 'Registro omitido exitosamente!';
	}
?>