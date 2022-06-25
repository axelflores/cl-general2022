<?php
	include( '../../../../config.ini.php' );
	include( '../../../../conectMin.php' );
	include( '../../../../conexionMysqli.php' );

	$action = '';
	if( !isset( $_POST['fl'] ) ){
		$action = $_GET['fl'];
	}else{
		$action = $_POST['fl'];
	}

	switch ( $action ) {

	//validacion para que no se repita el folio de remisión
		case 'validateInvoiceNoExists':
			$key = $_POST['to_check'];
			$sql = "SELECT 
						id_recepcion_bodega 
					FROM ec_recepcion_bodega
					WHERE folio_recepcion = '{$key}'";
			$exc = $link->query( $sql ) or die( "Error validateInvoiceNoExists : " . $link->error );
			if( $exc->num_rows > 0 ){
				die('Este folio de Remisión ya esta registrado, verifique antes de continuar!');
			}
			die( 'ok' );
		break;

	//insertar cabecera de recepción
		case 'insertInvoice':
			$folio = $_POST['invoice_folio'];
			$parts = $_POST['parts_number'];
			$provider = $_POST['provider_id'];
			$sql = "SELECT 
						serie 
					FROM ec_series_recepciones_bodega
					WHERE recepcion_actual = 0
					ORDER BY serie ASC
					LIMIT 1";
			$exc = $link->query( $sql ) or die( "Error al consultar Serie disponible : " . $link->error );
			$serie = $exc->fetch_row();
			
			$sql = "INSERT INTO ec_recepcion_bodega ( id_proveedor, id_usuario, folio_recepcion, serie, numero_partidas, fecha_alta )
			VALUES( {$provider}, {$user_id}, '{$folio}', '{$serie[0]}', {$parts},  NOW() )";
			$exc = $link->query( $sql ) or die( "Error al insertar la recepción : " . $link->error );
			
		//recupera registro
			$sql = "SELECT id_recepcion_bodega, folio_recepcion, serie, numero_partidas FROM ec_recepcion_bodega WHERE serie = '{$serie[0]}'"; 
			$exc = $link->query( $sql ) or die( "Error al recuperar registro de recepcion bodega : " . $link->error );
			$r = $exc->fetch_row();

			$sql = "UPDATE ec_series_recepciones_bodega SET recepcion_actual = '{$r[0]}' WHERE serie = '{$serie[0]}'";
			$exc = $link->query( $sql ) or die( "Error al actualizar serie de recepcion : " . $link->error );
			
			echo "{$r[0]}~{$r[1]}~{$r[2]}~{$r[3]}";
		break;

	//busqueda de remisiones
		case 'seekInvoices' : 
			$provider = $_POST['provider_id'];
			$txt = $_POST['key'];
			$series = $_POST['current_series'];
			$sql = "SELECT 
						id_recepcion_bodega, 
						folio_recepcion, 
						serie, 
						numero_partidas 
					FROM ec_recepcion_bodega 
					WHERE id_proveedor = '{$provider}'
					AND ( folio_recepcion LIKE '%{$txt}%' )
					AND id_recepcion_bodega_status IN( 1, 2 )";
			if( sizeof($series) > 0 ){
				$sql .= " AND serie NOT IN(";
				foreach ($series as $key => $serie) {
					$sql .= ( $key > 0 ? " ," : "" ) . "'{$serie}'";
				}
				$sql .= ")";
			}
			//echo $sql;
			$exc = $link->query( $sql ) or die( "Error al buscar coincidencias de transferencias : " . $link->error );
			if( $exc->num_rows <= 0 ){
				die( '<div><p>Sin coincidencias!</p></div>' );
			}
			$resp = "";
			while ( $r = $exc->fetch_row() ) {
				$resp .= "<div class=\"invoice_seeker_options\" onclick=\"setInvoiceExistent( '{$r[0]}~{$r[1]}~{$r[2]}~{$r[3]}' );\">";
					$resp .= "<p>{$r[1]}</p>";
				$resp .= "</div>";
			}
			die( $resp );	
		break;

	//busqueda por producto
		case 'seekProduct' : 
			$provider = $_POST['provider_id'];
			$txt = strtoupper($_POST['key']);
			$is_scanner = $_POST['scanner'];
			$sql = "SELECT
						p.id_productos,
						CONCAT( p.nombre, '<br/>Caja con <b>', pp.presentacion_caja, '</b> pieza', 
							IF( pp.presentacion_caja > 0, 's ', ' ' ), 
							'<br/>MODELO : <b>' , pp.clave_proveedor, '</b>'
						) AS product_name,
						IF(pp.clave_proveedor IS NULL, '', pp.clave_proveedor ),
						IF(pp.id_proveedor_producto IS NULL, '', pp.id_proveedor_producto ),
						IF(pp.codigo_barras_pieza_1 IS NULL, '', pp.codigo_barras_pieza_1 ),
						IF(pp.piezas_presentacion_cluces IS NULL, '', pp.piezas_presentacion_cluces ),
						IF(pp.codigo_barras_presentacion_cluces_1 IS NULL, '', pp.codigo_barras_presentacion_cluces_1 ),
						IF(pp.presentacion_caja IS NULL, '', pp.presentacion_caja ),
						IF(pp.codigo_barras_caja_1 IS NULL, '', pp.codigo_barras_caja_1 ),
						p.ubicacion_almacen
					FROM ec_productos p
					LEFT JOIN ec_proveedor_producto pp 
					ON p.id_productos = pp.id_producto
					AND pp.id_proveedor = '{$provider}'
					WHERE";
			//if( $is_scanner ){
				$sql .= " ( UPPER( pp.codigo_barras_pieza_1 ) = '{$txt}' OR UPPER( pp.codigo_barras_pieza_2 ) = '{$txt}'";
				$sql .= " OR UPPER( pp.codigo_barras_pieza_3 = '{$txt}' ) OR UPPER( pp.codigo_barras_presentacion_cluces_1 ) = '{$txt}'";
				$sql .= " OR UPPER( pp.codigo_barras_presentacion_cluces_2 ) = '{$txt}'";
				$sql .= " OR UPPER( pp.codigo_barras_caja_1 ) = '{$txt}'";
				$sql .= " OR UPPER( pp.codigo_barras_caja_2 ) = '{$txt}' )";
			//}else{
				$aux = explode(' ', $txt);
				$sql .= " OR (";
				foreach ($aux as $key => $value) {
					$sql .= ( $key > 0 ? " AND" : "" ) . " UPPER( p.nombre ) LIKE '%{$value}%'";
				}
				$sql .= " )";
				$sql .= " OR UPPER( p.clave ) LIKE '%{$txt}%'";
				$sql .= " OR UPPER( pp.clave_proveedor ) LIKE '%{$txt}%'";
				$sql .= " OR UPPER( p.orden_lista ) = '{$txt}'";
			//}
			$sql .= " GROUP BY p.id_productos, pp.id_proveedor_producto";
			//echo 'ok';
			//echo $sql;
			$exc = $link->query( $sql ) or die( "Error al buscar prodctos : " . $link->error );
			$resp = "";
			while ( $r = $exc->fetch_row() ) {
				if( $r[1] != '' ){
					$resp .= "<div class=\"group_card\" onclick=\"setProduct( '{$r[0]}', '{$r[1]}', '{$r[2]}', '{$r[3]}',
					'{$r[4]}', '{$r[5]}', '{$r[6]}', '{$r[7]}', '{$r[8]}', '{$r[9]}' );\">";
						$resp .= "<p>{$r[1]}</p>";
					$resp .= "</div>";
				}
			}
			echo $resp;
		break;

		case 'saveInvoiceDetail' :
			$observaciones = "";
			$product_id = $_POST['pk'];
			$product_provider_id = ( $_POST['pp'] == '' ? 'null' : $_POST['pp'] );
			//die( 'p_p : ' . $_POST['pp'] );
			$product_model = $_POST['model'];
			$observaciones .= ( $product_model == '' ? "El producto NO tiene modelo\n" : "" );

			$piece_barcode = $_POST['pz_bc'];
			$observaciones .= ( $piece_barcode == '' ? "El producto NO tiene código de barras de PIEZA\n" : "" );

			$pieces_per_pack = $_POST['pzs_x_pack']; 
			$observaciones .= ( $pieces_per_pack == '' ? "El producto NO tiene piezas por PAQUETE\n" : "" );

			$pack_barcode = $_POST['pack_bc'];
			$observaciones .= ( $pack_barcode == '' ? "El producto NO tiene código de barras de PAQUETE\n" : "" );

			$pieces_per_box = $_POST['pzs_x_box'];
			$observaciones .= ( $pieces_per_box == '' ? "El producto NO tiene piezas por CAJA\n" : "" );

			$box_barcode = $_POST['box_bc'];
			$observaciones .= ( $box_barcode == '' ? "El producto NO tiene código de barras de CAJA\n" : "" );			

			$box_recived = $_POST['box_rec']; 
			$pieces_recived = $_POST['pieces_rec']; 
			$product_part_number = $_POST['product_p_num']; 
			$product_serie = $_POST['product_serie']; 
			$is_new_row = ( $_POST['is_new'] == true ? 1 : 0 );
		//ubicacion del producto
			$product_location_status = $_POST['location_status'];
			$product_location = $_POST['location'];
		//id de detalle
			$detail_id = ( !isset( $_POST['detail_id'] ) ? '' : $_POST['detail_id'] );
			//die( 'e :' . $detail_id );

			/*if( $detail_id == '' ){
			//inserta nuevo registro
				$sql = "INSERT INTO ec_recepcion_bodega_detalle ( id_recepcion_bodega, id_producto, id_proveedor_producto, 
					modelo, piezas_por_caja, piezas_por_paquete, cajas_recibidas, piezas_sueltas_recibidas, c_b_pieza, c_b_paquete,
				c_b_caja, es_nuevo_modelo, serie, numero_partida, observaciones, validado )
				VALUES( (SELECT id_recepcion_bodega FROM ec_recepcion_bodega WHERE serie = '$product_serie' LIMIT 1), '{$product_id}', {$product_provider_id},
				 '{$product_model}', '{$pieces_per_box}', '{$pieces_per_pack}', '{$box_recived}', '{$pieces_recived}', '{$piece_barcode}', '{$pack_barcode}',
				 '{$box_barcode}', '{$is_new_row}', '{$product_serie}', '{$product_part_number}', '{$observaciones}', '0')";
			}else{*/
			//actualiza un registro existente
				if( $detail_id == '' ){
					$sql = "INSERT INTO ";
				}else{
					$sql = "UPDATE ";
				}
				$sql .= "ec_recepcion_bodega_detalle SET 
							id_recepcion_bodega = (SELECT id_recepcion_bodega FROM ec_recepcion_bodega WHERE serie = '$product_serie' LIMIT 1),
							id_producto = '{$product_id}', 
							id_proveedor_producto = {$product_provider_id}, 
							modelo = '{$product_model}', 
							piezas_por_caja = '{$pieces_per_box}',
							piezas_por_paquete = '{$pieces_per_pack}', 
							cajas_recibidas = '{$box_recived}', 
							piezas_sueltas_recibidas = '{$pieces_recived}', 
							c_b_pieza = '{$piece_barcode}', 
							c_b_paquete = '{$pack_barcode}',
							c_b_caja = '{$box_barcode}', 
							es_nuevo_modelo = '{$is_new_row}', 
							serie = '{$product_serie}', 
							numero_partida = '{$product_part_number}', 
							observaciones = '{$observaciones}', 
							validado = '0',
							ubicacion_almacen = '{$product_location}',
							id_status_ubicacion = '{$product_location_status}'";
				if( $detail_id != '' ){
					$sql .= " WHERE id_recepcion_bodega_detalle = '{$detail_id}'"; 
				}
			//die( $sql );
			$exc = $link->query( $sql ) or die( "Error al insertar/actualizar el detalle de recepción : " . $sql . $link->error );
			//recupera el registro que se insertó
			$inserted_id = $link->insert_id;
			$detail = getRecepcionDetail( $link, $inserted_id, null );
			if( $product_location_status == 3 ){
		//actualiza la nueva ubicación del producto en la tabla de productos
				$sql = "UPDATE ec_productos SET ubicacion_almacen = '{$product_location}' 
				WHERE id_productos = '{$product_id}'";
				$exc = $link->query( $sql ) or die( "Error al actualizar la ubicación del almacen : " . $link->error );
			}
		/*actualiza el status de la cabecera de recepcion bodega
			$sql = "SELECT 
						COUNT( id_recepcion_bodega_detalle )
					FROM ec_recepcion_bodega_detalle
					WHERE id_recepcion_bodega IN( 
						SELECT id_recepcion_bodega 
						FROM ec_recepcion_bodega 
						WHERE serie = '$product_serie' 
						LIMIT 1)";
			$sql = "UPDATE ec_recepcion_bodega SET "*/
			die( "ok|{$detail}" );
		break;

		case 'seekBarcode' : 
			$product_provider_id = $_POST['p_p'];
			$barcode = strtoupper( $_POST['code'] );
			$sql = "SELECT 
						CONCAT(p.nombre, ' ( MODELO : ', pp.clave_proveedor,' )')
					FROM ec_productos p
					LEFT JOIN ec_proveedor_producto pp ON p.id_productos = pp.id_producto
					WHERE pp.id_proveedor_producto NOT IN('{$product_provider_id}' )
					AND( UPPER( pp.codigo_barras_pieza_1 ) = '{$barcode}' 
						OR UPPER( pp.codigo_barras_pieza_2 ) = '{$barcode}'
						OR UPPER( pp.codigo_barras_pieza_3 ) = '{$barcode}' 
						OR UPPER( pp.codigo_barras_presentacion_cluces_1 ) = '{$barcode}'
						OR UPPER( pp.codigo_barras_presentacion_cluces_2 ) = '{$barcode}' 
						OR UPPER( pp.codigo_barras_caja_1 ) = '{$barcode}'
						OR UPPER( pp.codigo_barras_caja_2 ) = '{$barcode}'
					)";
			$exc = $link->query( $sql ) or die( "Error al validar código de barras : " . $link->error );
			if( $exc->num_rows > 0 ){
				$r = $exc->fetch_row();
				die( "El código de barras '{$barcode}' ya esta registrado en el producto : {$r[0]}" );
			}
			die('ok');
		break;

		case 'getRecepcionDetail' : 
			echo getRecepcionDetail( $link, $_POST['id'], null );
		break;

		case 'seekProductsLocations' : 
			echo seekProductsLocations( $link, $_POST['key'] );
		break;

		case 'changeInvoicesStatus' : 
			echo changeInvoicesStatus( $_POST['data'], $link );
		break;
		
		case 'changeProductLocation' :
			echo changeProductLocation( $_POST['p_k'], $_POST['new_location'], $_POST['new_status'], $link );
		break;

		default:
			die( 'Permission Denied!' );
		break;

		case 'getInvoiceParts' : 
			echo getInvoiceParts( $_POST['reference'], $link );
		break;

		case 'validateSerie' :
			echo validateSerie( $_GET['serie'], $_GET['serie_number'], $link );
		break;

		case 'seekProvider' : 
			echo seekProvider( $_GET['txt'], $link );
		break;
		
		default : 
			die( 'Permission denied!' );
		break;
	}

	function seekProvider( $txt, $link ){
		$resp = '';
		$sql = "SELECT 
					id_proveedor AS provider_id, 
					nombre_comercial AS name
				FROM ec_proveedor
				WHERE id_proveedor > 1";
		if( $txt != '' ){
			$sql .= ' AND( ';
			$arr_txt = explode( ' ', $txt );
			foreach ($arr_txt as $key => $value) {
				$sql .= ( $key > 0 ? ' AND' : '' );
				$sql .= " nombre_comercial LIKE '%{$value}%'";
			}
			$sql .= " )";
		}
		$stm = $link->query( $sql ) or die( "Error al buscar proveedores : " . $link->error );
		while ( $row = $stm->fetch_assoc() ) {
			$resp .= '<div class="row provider_response" onclick="setProvider( ' . $row['provider_id'] . ', \'' . $row['name'] . '\' )">';
				$resp .= '<b>' . $row['name'] . '</b>'; 
			$resp .= '</div>';
		}
		//return $sql;
		return $resp;
	}

	function getRecepcionDetail( $link, $id = null, $recepcion_id = null ){
		$resp = array();
		$sql = "SELECT
					rd.id_recepcion_bodega_detalle,
					rd.id_recepcion_bodega,
					rd.id_producto,
					rd.id_proveedor_producto,
					rd.piezas_por_caja,
					rd.piezas_por_paquete,
					rd.cajas_recibidas,
					rd.piezas_sueltas_recibidas,
					rd.c_b_pieza,
					rd.c_b_paquete,
					rd.c_b_caja,
					rd.es_nuevo_modelo,
					rd.observaciones,
					rd.serie,
					rd.numero_partida,
					rd.modelo,
					p.nombre,
					rd.id_status_ubicacion,
					rd.ubicacion_almacen
				FROM ec_recepcion_bodega_detalle rd
				LEFT JOIN ec_productos p ON p.id_productos = rd.id_producto
				WHERE 1";
		$sql .= ( $id != null ? " AND rd.id_recepcion_bodega_detalle = '{$id}'" : "" );
		$sql .= ( $recepcion_id != null ? " AND rd.id_recepcion_bodega = '{$recepcion_id}'" : "" );
		$exc = $link->query( $sql ) or die( "Error al obtener datos de detalle de recepcion : " . $link->error );
		//die( $sql );
		while( $r = $exc->fetch_assoc() ){
			array_push($resp, $r);
		}
		return json_encode( $resp );
	}

	function seekProductsLocations( $link, $txt ){
		$resp = '';
		$sql = "SELECT 
					p.id_productos,
					p.nombre,
					ap.inventario,
					SUM( rd.piezas_sueltas_recibidas + ( rd.piezas_por_caja * rd.cajas_recibidas ) ),
					rd.id_status_ubicacion,
					p.ubicacion_almacen
				FROM ec_recepcion_bodega_detalle rd
				LEFT JOIN ec_productos p ON p.id_productos = rd.id_producto
				LEFT JOIN ec_almacen_producto ap ON ap.id_producto = rd.id_producto
				AND ap.id_almacen = 1
				WHERE p.orden_lista LIKE '%{$txt}%'
				OR p.clave LIKE '%{$txt}%'
				OR ( ";
		$words = explode(' ', $txt);
		foreach ($words as $key => $word ) {
			$sql .= ( $key > 0 ? " AND " : "") . " p.nombre LIKE '%{$word}%'";
		}
		$sql .= " )
				GROUP BY rd.id_producto
				ORDER BY p.orden_lista";
		//return $sql;

		$exc = $link->query( $sql ) or die( "Error al consultar productos recibidos : " . $link->error );
		while( $r = $exc->fetch_row() ){
			$resp .= "<div style=\"padding : 10px;\" onclick=\"setProductLocation('{$r['0']}~{$r['1']}~{$r['2']}~{$r['3']}~{$r['4']}~{$r['5']}');\">{$r[1]}</div>";
		}
		return $resp;
	}

	function changeInvoicesStatus( $data, $link ){
		$dat = explode( '|~|', $data );
		foreach ($dat as $key => $value) {
			$val = explode('~', $value );
			$sql = "UPDATE ec_recepcion_bodega SET id_recepcion_bodega_status = '{$val[1]}' WHERE id_recepcion_bodega = '{$val[0]}'";
			$stm = $link->query( $sql ) or die( "Error al actualizar las recepciones de bodega : " . $link->error );
		}
		return 'ok|Los cambios fueron guardados existosamente!';
	} 

	function changeProductLocation( $product_id, $location, $status, $link ){
		$sql = "UPDATE ec_productos SET ubicacion_almacen = '{$location}' WHERE id_productos = '{$product_id}'";
		$stm = $link->query( $sql ) or die( "Error al actualizar la ubicación del producto : " . $link->error );
		$sql = "UPDATE ec_recepcion_bodega_detalle SET ubicacion_almacen = '{$location}', id_status_ubicacion = '{$status}' 
					WHERE id_producto = '{$product_id}'";
		$stm = $link->query( $sql ) or die( "Error al actualizar la ubicación del producto : " . $link->error );
		return 'ok';
	}

	function getInvoiceParts( $serie, $link ){
		$resp ='<option value="">-</option>';
		$sql = "SELECT 
					rb.numero_partidas, 
					GROUP_CONCAT( rbd.numero_partida SEPARATOR ',' )
				FROM ec_recepcion_bodega rb
				LEFT JOIN  ec_recepcion_bodega_detalle rbd 
				ON rb.id_recepcion_bodega = rbd.id_recepcion_bodega
				WHERE rb.serie = '{$serie}'";
		//die($sql);
		$stm = $link->query( $sql ) or die( "Error al consultar partidas utilizadas : " . $link->error );
		$row = $stm->fetch_row();
		$parts_limit = $row[0];
		$parts = explode(',', $row[1] );
		for( $i = 1; $i <= $parts_limit; $i++ ){
			$exists = 0;
			foreach ($parts as $key => $number_part) {
				if( $number_part == $i ){
					$exists = 1;
				}
			}
			if( $exists == 0 ){
				$resp .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		return $resp;
	}

	function validateSerie( $serie, $serie_number, $link ){
		$sql = "SELECT ";
	}

?>