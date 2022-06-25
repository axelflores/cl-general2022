<?php
	include( '../../../../../config.ini.php' );
	include( '../../../../../conect.php' );
	include( '../../../../../conexionMysqli.php' );

	$action = '';
	if( isset( $_POST['fl'] ) ){
		$action = $_POST['fl'];
	}else{
		$action = $_GET['fl'];
	}
	switch ( $action ) {
		case 'getAssignmentDetail':
			if( $_POST['supply_detail_id'] == null || $_POST['supply_detail_id'] == '' ){
				 $_POST['supply_detail_id'] = null;
			}
			echo getAssignmentDetail( $_POST['p_k'], $_POST['supply_detail_id'], $link );
		break;

		case 'getProductModels':
			echo getProductModels( $_POST['p_k'], $_POST['p_p'], $_POST['b'], $_POST['pa'], $_POST['pi'], $_POST['transfer_id'], $_POST['is_edition'], $link );
		break;

		case 'seekProduct':
			$bc = strtoupper($_POST['key']);
			echo seekProduct( $bc, $_POST['p_id'], $_POST['p_p_id'], $_POST['model'], $_POST['loc'], $link );
		break;

		case 'saveProductSupplie':
			if( $_POST['request_password'] != '' ){
				if( checkPassword( $_POST['request_password'], $link ) != '' ){
					die( "Contraseña incorrecta." );
				}
			}
			if( $_POST['original_product'] == '' ){//registro original para ajustar inventario
				$_POST['original_product'] = null;
			}
			echo saveProductSupplie( $_POST['request'], $user_id, $_POST['transfer_id'],
				$_POST['is_edition'], $_POST['edition_row_id'], $_POST['original_product'], 
				$link );
		break;

		case 'saveProductSupplyEdition':
			if( $_POST['original_product'] == '' ){//registro original para ajustar inventario
				$_POST['original_product'] = null;
			}
			echo saveProductSupplyEdition(  $_POST['request'], $user_id, $_POST['transfer_id'],
				$_POST['is_edition'], $_POST['edition_row_id'], $_POST['original_product'], $link );
		break;

		case 'buildListSupplied': 
			echo buildListSupplied( $_POST['id'], $link );
		break;
		
		case 'checkManagerPassword':
			echo checkManagerPassword( $_GET['pss'], $link );
		break;

		case 'deleteProductSupplie':
			echo deleteProductSupplie( $_GET['row_id'], $_GET['transfer_detail_id'], $link );
		break;

		default:
			die( "Permission Denied!" );
		break;
	}	


		function getAssignmentDetail( $id, $user_transfer_tracking = null, $link ){
			if( $user_transfer_tracking != null ){
				$sql = "SELECT 
							tsu.id_transferencia_producto AS row_id,
							tsu.id_transferencia_surtimiento AS supply_id,
							tsu.id_surtimiento_detalle AS supply_detail_id,
							p.nombre AS name,
							pp.clave_proveedor AS provider_model,
							tsu.id_proveedor_producto AS product_provider_id,
							p.ubicacion_almacen AS product_location,
							tsu.cantidad_cajas_surtidas AS boxes,
							tsu.cantidad_paquetes_surtidos AS packs,
							tsu.cantidad_piezas_surtidas AS pieces,
							tsu.id_producto AS product_id,
							tsu.total_piezas_surtidas AS total_pieces_quantity,
							tsu.id_surtimiento_usuario AS user_transfer_tracking_id
						FROM ec_transferencias_surtimiento_usuarios tsu
						LEFT JOIN ec_productos p
						ON p.id_productos = tsu.id_producto
						LEFT JOIN ec_proveedor_producto pp
						ON pp.id_proveedor_producto = tsu.id_proveedor_producto
						WHERE id_surtimiento_usuario = '{$user_transfer_tracking}'";
				$stm = $link->query( $sql ) or die( "Error al consultar el detalle de surtimiento : " . $link->error );
				$row = $stm->fetch_assoc();
				return json_encode( $row );
			}
		//verifica que no se este reasignando
			$sql = "SELECT 
						IF( id_status_asignacion = 3, 1, 0 ) AS is_paused
					FROM ec_transferencias_surtimiento
					WHERE id_transferencia_surtimiento = '{$id}'";
			$stm = $link->query( $sql ) or die( "Error al validar que la transferencia no se este reasignando : " . $link->error );
			$r = $stm->fetch_assoc();
			if( $r['is_paused'] == 1 ){
				$resp = 'exception|<h3 class="reasignation_title">El surtimiento está siendo reasignado.</h3>';
				$resp .= '<p class="reasignation_text">Espere un momento!</p>';
				$resp .= '<img class="reasignation_icon" src="../../../../img/img_casadelasluces/load.gif">';
				return $resp;
			}

			$sql = "SELECT 
						tp.id_transferencia_producto AS row_id,
						tsd.id_transferencia_surtimiento AS supply_id,
						tsd.id_surtimiento_detalle AS supply_detail_id,
						p.nombre AS name,
						pp.clave_proveedor AS provider_model,
						pp.id_proveedor_producto AS product_provider_id,
						p.ubicacion_almacen AS product_location,
						tp.cantidad_cajas AS boxes,
						tp.cantidad_paquetes AS packs,
						tp.cantidad_piezas AS pieces,
						p.id_productos AS product_id,
						tp.cantidad AS total_pieces_quantity
					FROM ec_transferencias_surtimiento_detalle tsd
					LEFT JOIN ec_transferencia_productos tp 
					ON tsd.id_transferencia_producto = tp.id_transferencia_producto
					LEFT JOIN ec_transferencias_surtimiento ts 
					ON ts.id_transferencia_surtimiento = tsd.id_transferencia_surtimiento
					LEFT JOIN ec_productos p ON p.id_productos = tp.id_producto_or
					LEFT JOIN ec_proveedor_producto pp 
					ON pp.id_proveedor_producto = tp.id_proveedor_producto
					WHERE ts.id_transferencia_surtimiento = '{$id}'
					AND tsd.id_status_surtimiento IN( 2, 1)
					ORDER BY tsd.id_status_surtimiento DESC
					LIMIT 1";
			$stm = $link->query( $sql ) or die( "Error al consultar el producto a surtir : " . $link->error );
			
			if( $stm->num_rows <= 0 ){//ya no hay registros por surtir
				$sql = "UPDATE ec_transferencias_surtimiento 
							SET id_status_asignacion = '4' 
						WHERE id_transferencia_surtimiento = '{$id}'";
				$stm = $link->query( $sql ) or die( "Error al actualizar surtimiento : " . $link->error );
				return 'no_rows';
			}

			$r = $stm->fetch_assoc();
		//actualiza el detalle a status de surtimiento
			$sql = "UPDATE ec_transferencias_surtimiento_detalle 
						SET id_status_surtimiento = '2'
					WHERE id_surtimiento_detalle = '{$r['supply_detail_id']}'";
			$exc = $link->query( $sql ) or die( "Error al poner el detalle en surtimiento : " . $link->error );
			//echo $sql;
			return json_encode( $r );
		}

		function getProductModels( $product_id, $product_provider, $boxes, $packs, $pieces, $transfer_id, $is_edition, $link ){
			$product_provider = ( $product_provider == '' || $product_provider == null ? 0 : $product_provider );
			$boxes = ( $boxes == '' || $boxes == null ? 0 : $boxes );
			$packs = ( $packs == '' || $packs == null ? 0 : $packs );
			$pieces = ( $pieces == '' || $pieces == null ? 0 : $pieces );
			
			/*if( $is_edition == 0 ){
				//echo 'no-edición';
				$sql = "SELECT 
							p.id_productos AS product_id,
							pp.id_proveedor_producto AS product_provider_id,
							p.nombre AS product_name,
							pp.clave_proveedor AS provider_model,
							IF( {$product_provider} = pp.id_proveedor_producto, {$boxes}, 0 ) AS boxes,
							IF( {$product_provider} = pp.id_proveedor_producto, {$packs}, 0 ) AS packs,
							IF( {$product_provider} = pp.id_proveedor_producto, {$pieces}, 0 ) AS pieces,
							IF( {$product_provider} = pp.id_proveedor_producto, 1, 0 ) AS is_principal,
							pp.presentacion_caja AS pieces_per_box,
							pp.piezas_presentacion_cluces AS pieces_per_pack,
							IF( '{$product_provider}' = pp.id_proveedor_producto, 
								( ( {$boxes} * pp.presentacion_caja ) + ( {$packs} * pp.piezas_presentacion_cluces )
								+ {$pieces} ), 
							0 ) AS total
						FROM ec_proveedor_producto pp
						LEFT JOIN ec_productos p ON p.id_productos = pp.id_producto
						WHERE pp.id_producto = '{$product_id}'";
			}else{
				//return 'edición';*/
			//edición
				$sql = "SELECT 
							p.id_productos AS product_id,
							pp.id_proveedor_producto AS product_provider_id,
							p.nombre AS product_name,
							pp.clave_proveedor AS provider_model,
							IF( {$product_provider} = pp.id_proveedor_producto, 
								{$boxes}, 
								IF( tsu.id_surtimiento_usuario IS NULL, 0, tsu.cantidad_cajas_surtidas ) 
							) AS boxes,

							IF( {$product_provider} = pp.id_proveedor_producto, 
								{$packs},
								IF( tsu.id_surtimiento_usuario IS NULL, 0, tsu.cantidad_paquetes_surtidos ) 
							) AS packs,

							IF( {$product_provider} = pp.id_proveedor_producto, 
								{$pieces},
								IF( tsu.id_surtimiento_usuario IS NULL, 0, tsu.cantidad_piezas_surtidas ) 
							) AS pieces,

							IF( {$product_provider} = pp.id_proveedor_producto, 1, 0 

							) AS is_principal,
							pp.presentacion_caja AS pieces_per_box,
							pp.piezas_presentacion_cluces AS pieces_per_pack,
							IF( '{$product_provider}' = pp.id_proveedor_producto, 
								( ( {$boxes} * pp.presentacion_caja ) + ( {$packs} * pp.piezas_presentacion_cluces )
								+ {$pieces} ), 
								IF( tsu.id_surtimiento_usuario IS NULL, 0, tsu.total_piezas_surtidas )
							) AS total,
							CONCAT(  
								pp.codigo_barras_pieza_1, '|',
								pp.codigo_barras_pieza_2, '|',
								pp.codigo_barras_pieza_3, '|',
								pp.codigo_barras_presentacion_cluces_1, '|',
								pp.codigo_barras_presentacion_cluces_2, '|',
								pp.codigo_barras_caja_1, '|',
								pp.codigo_barras_caja_2
							) AS barcodes_row
						FROM ec_proveedor_producto pp
						LEFT JOIN ec_productos p ON p.id_productos = pp.id_producto
						LEFT JOIN ec_transferencia_productos tp
						ON tp.id_producto_or = p.id_productos
						LEFT JOIN ec_transferencias_surtimiento_usuarios tsu
						ON tsu.id_transferencia_producto = tp.id_transferencia_producto
						AND tsu.id_proveedor_producto = pp.id_proveedor_producto
						WHERE p.id_productos = '{$product_id}'
						AND tp.id_transferencia = '{$transfer_id}'
						GROUP BY pp.id_proveedor_producto";
			//}
			//die( $sql );
			$stm = $link->query( $sql ) or die( "Error al consultar presentaciones del producto : " . $link->error . $sql );
			/*if( $stm->num_rows <= 1 ){
				$resp = '<div class="row">';
					$resp .= '<div class="col-2"></div>';
					$resp .= '<div class="col-2"></div>';
						$resp .= '<br><h5 class="orange">La presentación actual es la única para este producto</h5><br>';
						$resp .= '<button class="btn btn-info form-control" onclick="close_emergent();">';
							$resp .= '<i class="icon-ok-circle">Aceptar</i>';
						$resp .= '</button>';
					$resp .= '</div>';
				$resp .= '</div>';	
				return $resp;
			}*/
			return buildProductPresentations( $stm );
		}	

		function buildProductPresentations( $stm ){
			$resp = '<br/><table class="table table-striped table-bordered txt_70">';
			$resp .= '<thead>';
				$resp .= '<tr>';
					$resp .= '<th>Modelo</th>';
					$resp .= '<th>Cajas</th>';
					$resp .= '<th>Paquetes</th>';
					$resp .= '<th>Piezas</th>';
					$resp .= '<th>Total</th>';
					$resp .= '<th><i class="icon-barcode"></i></th>';
				$resp .= '</tr>';
			$resp .= '</thead>';
			//var_dump( $stm );
				$resp .= '<tbody id="product_provider_models">';
			$counter = 0;
			$product_supply_total = 0;
			while ( $r = $stm->fetch_assoc() ) {
				$resp .= '<tr ' . ( $r['is_principal'] == 1 ? ' style="background-color : yellow;"' : '' ) . '>';
					$resp .= '<td>' . $r['provider_model'] . '</td>';
					$resp .= '<td id="p_p_1_' . $counter . '" class="no_visible">' . $r['product_provider_id'] . '</td>';
					$resp .= '<td id="p_p_2_' . $counter . '" onclick="edit_p_p_ceil( 2, ' . $counter . ' );" align="right">' . $r['boxes'] . '</td>';
					$resp .= '<td id="p_p_3_' . $counter . '" onclick="edit_p_p_ceil( 3, ' . $counter . ' );" align="right">' . $r['packs'] . '</td>';
					$resp .= '<td id="p_p_4_' . $counter . '" onclick="edit_p_p_ceil( 4, ' . $counter . ' );" align="right">' . $r['pieces'] . '</td>';
					$resp .= '<td id="p_p_5_' . $counter . '" align="right">' . $r['total'] . '</td>';
					$resp .= '<td id="p_p_6_' . $counter . '" class="no_visible">' . $r['pieces_per_box'] . '</td>';
					$resp .= '<td id="p_p_7_' . $counter . '" class="no_visible">' . $r['pieces_per_pack'] . '</td>';
					$resp .= '<td id="p_p_8_' . $counter . '" ><i class="icon-ok-circle" id="p_p_9_icon_' . $counter . '" ' . ( $r['total'] <= 0 ? ' style="display:none;"' : '' ) . '></i></td>';
					$resp .= '<td id="p_p_9_' . $counter . '" need_validation="' . ( $r['total'] > 0 ? '1' : '0' ) . '" class="no_visible"';
					$resp .= ' barcode_validated="0">' . $r['barcodes_row'] . '</td>';
				$resp .= '</tr>';				
				$counter ++;
				$product_supply_total += $r['total'];
			}
				$resp .= '</tbody>';
			$resp .= '<tfoot><tr><td colspan="4" align="right"></td>';
			$resp .= '<td colspan="4" align="right" id="product_supply_total">' . $product_supply_total . '</td></tr></tfoot>';
			$resp .= '</table>';
			$resp .= '<br/>';
			$resp .= '<div class="row">';
			
				$resp .= '<div class="col-2"></div>';
			
				$resp .= '<div class="col-8">';
					
					$resp .= '<input type="text" id="tmp_pp_barcodes" onkeyup="seek_barcode_form_multiple( event );"';
					$resp .= ' placeholder="Escanear código de barras" class="form-control"><br>';
					
					/*$resp .= '<button class="btn btn-success form-control" onclick="seek_barcode_form_multiple( \'enter\' );">';
						$resp .= 'Aceptar';
					$resp .= '</button><br><br>';*/
					
					$resp .= '<input type="password" id="manager_password" class="form-control mng_pss no" placeholder="Password del encargado"><br>';
					
					$resp .= '<button class="btn btn-success form-control" onclick="saveProductSupplie( 1 );">';
						$resp .= 'Aceptar';
					$resp .= '</button><br><br>';
					
					$resp .= '<button class="btn btn-danger form-control" onclick="close_emergent();">';
						$resp .= 'Cancelar';
					$resp .= '</button>';
				
				$resp .= '</div>';

				$resp .= '<div class="col-2"></div>';
			$resp .= '</div>';
			$resp .= '<br/>';
			return $resp;
		}

		function seekProduct( $barcode, $product_id, $product_provider_id, $model, $location, $link ){
			$resp = '';
			$sql = "SELECT 
						p.id_productos AS product_id,
						pp.id_proveedor_producto AS provider_product_id,
						pp.clave_proveedor AS provider_clue,
						p.ubicacion_almacen AS location,
						pp.unidad_medida_presentacion_cluces AS pieces_per_pack,
						pp.unidad_medida_caja AS pieces_per_box
					FROM ec_proveedor_producto pp
					LEFT JOIN ec_productos p ON pp.id_producto = p.id_productos
					WHERE UPPER( pp.codigo_barras_pieza_1 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_pieza_2 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_pieza_3 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_presentacion_cluces_1 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_presentacion_cluces_2 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_caja_1 ) = '{$barcode}'
					OR UPPER( pp.codigo_barras_caja_2 ) = '{$barcode}'";
			//die( $sql );
			$stm = $link->query( $sql ) or die( "Error al buscar el producto : " . $link->error );
			$results = $stm->num_rows;
			//die( 'res : ' . $results );
			if( $results > 1 ){
				$resp = '<h5 class="red">Se encontró más de una vez el código de barras, por favor notifique al encargado.</h5>';
				$resp .= "<br><div class=\"row\">";
				$resp .= "<div class=\"col-3\"></div>";
				$resp .= "<div class=\"col-6\">";
					$resp .= "<button class=\"btn btn-success form-control\"";
						$resp .= " onclick=\"close_emergent();\"";
					$resp .= ">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div><br><br>";
				return $resp;
			}else if( $results <= 0 ){
				$resp = '<h5 class="red">El código de barras no fue encontrado, verifique y vuelva a escanear.</h5>';
				$resp .= "<br><div class=\"row\">";
				$resp .= "<div class=\"col-3\"></div>";
				$resp .= "<div class=\"col-6\">";
					$resp .= "<button class=\"btn btn-success form-control\"";
						$resp .= " onclick=\"close_emergent();\"";
					$resp .= ">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div><br><br>";
			return $resp;
			}else if( $results == 1 ){
				$row = $stm->fetch_assoc();
			//producto equivocado
				if( $product_id != $row['product_id'] ){
					$resp .= "<h5 class=\"red\">El producto escaneado no es el que se tiene que surtir!</h5>";
				//separa por coma ( más de una ubicación )
					$arr_location = explode(',', $location );
					$arr_location_query = explode(',', $row['location'] );
	    		//separa por espacios ( ubicación fila/pasillo)
					$arr_location_1 = explode(' ', $arr_location[0] );
					$arr_location_query_1 = explode(' ', $arr_location_query[0] );
				//separa por guión ( Letra-numero )
					$arr_location_2 = explode('-', $arr_location_1[0] );
					$arr_location_query_2 = explode('-', $arr_location_query_1[0] );

				//pasillo correcto
					if( $arr_location_2[0] == $arr_location_query_2[0] ){
							$resp .= "<p align=\"center\"><i class=\"icon-ok-circle\">El pasillo es correcto</i>";

						$resp .= "<h5>Ubicación actual : <b class=\"red\">{$arr_location_query_2[0]}-{$arr_location_query_2[1]}</b></h5>";
						$resp .= "<h5>Dirijete a la Ubicación correcta : <b class=\"green\">{$arr_location_2[0]}-{$arr_location_2[1]}</b></h5>";
					//una sola ubicación
						$pasos = $arr_location_2[1] - $arr_location_query_2[1];
						if( $pasos < 0 ){
							$pasos = $pasos * -1;//convierte a positivo
							$resp .= "<p align=\"center\" style=\"font-size : 120%;\">Retrocede <b class=\"orange\">{$pasos}</b> ubicaciones</p>";
						}else{
							$resp .= "<p align=\"center\" style=\"font-size : 120%;\">Avanza <b class=\"orange\">{$pasos}</b> ubicaciones</p>";
						}

						$resp .= "<br><div class=\"row\">";
						$resp .= "<div class=\"col-3\"></div>";
						$resp .= "<div class=\"col-6\">";
							$resp .= "<button class=\"btn btn-success form-control\"";
								$resp .= " onclick=\"close_emergent();\"";
							$resp .= ">";
								$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
							$resp .= "</button>";
						$resp .= "</div><br><br>";

					}else{
				//pasillo equivocado
					//	$resp .= "<h5>Pasillo correcto : <b class=\"orange\">{$arr_location[0]}</b></h5>";
						$resp .= "<h5>Pasillo actual : <b class=\"red\">{$arr_location_query_2[0]}</b></h5>";
						$resp .= "<h5>Dirijete al Pasillo correcto : <b class=\"green\">{$arr_location_2[0]}</b></h5>";

						$resp .= "<br><br><div class=\"row\">";
						$resp .= "<div class=\"col-3\"></div>";
						$resp .= "<div class=\"col-6\">";
							$resp .= "<button class=\"btn btn-success form-control\"";
								$resp .= " onclick=\"close_emergent();\"";
							$resp .= ">";
								$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
							$resp .= "</button>";
						$resp .= "</div><br><br><br>";
					}
				}else{
				//modelo diferente
					if( $product_provider_id != $row['provider_product_id'] ){
						$resp .= "<h5>El modelo del producto escaneado no es el que se tiene que surtir!</h5>";
						$resp .= "<h5>Modelo solicitado : <b class=\"orange\">{$model}</b></h5>";
						$resp .= "<h5>Modelo escaneado : <b class=\"orange\">{$row['provider_clue']}</b></h5>";
						$resp .= "<br><br><div class=\"row\">";
						$resp .= "<div class=\"col-3\"></div>";
						$resp .= "<div class=\"col-6\">";
							$resp .= "<button class=\"btn btn-success form-control\"";
								$resp .= " onclick=\"close_emergent();\"";
							$resp .= ">";
								$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
							$resp .= "</button>";
						$resp .= "</div><br><br><br>";
					}else if( $product_provider_id == $row['provider_product_id'] ){
						$resp = 'ok';
					}
				}
			}
			return $resp;

		}
		function insert_inventory_adjust( $transfer_product_id, $user, $link ){
		//verifica que no haya registros previos para ajustar el proveedor - producto
			$sql = "SELECT 
						dipp.id_diferencia_inventario
					FROM ec_diferencias_inventario_proveedor_producto dipp
					LEFT JOIN ec_transferencia_productos tp
					ON tp.id_producto_or = dipp.id_producto
					AND tp.id_proveedor_producto = dipp.id_proveedor_producto
					WHERE tp.id_transferencia_producto IN( {$transfer_product_id} )";
					//die( $sql );
			$stm = $link->query( $sql ) or die( "Error al consultar si el producto ya esta registrado para ajuste de inventario : " . $link->error . $sql);
			if( $stm->num_rows > 0 ){
				//die( 'here' );
				return true;
			}else{
				$sql = "INSERT INTO ec_diferencias_inventario_proveedor_producto 
				( /*1*/id_transferencia_producto, /*2*/id_producto, /*3*/id_proveedor_producto, 
				/*4*/id_usuario_detona, /*5*/id_usuario_resuelve, /*6*/fecha_alta, /*7*/ajustado )
				SELECT
					id_transferencia_producto,
					id_producto_or,
					id_proveedor_producto,
					'{$user}',
					NULL,
					NOW(),
					0
				FROM ec_transferencia_productos
				WHERE id_transferencia_producto IN( '{$transfer_product_id}' )";
				$stm = $link->query( $sql ) or die( "Error al insertar el registro de ajuste de inventario : " . $link->error );
				return true;
			}
			return false;
		}
		function saveProductSupplie( $products_array, $user, $transfer_id, $is_edition, 
									$edition_row_id, $original_transfer_product = null, 
									$link ){
			$resp = '';
			$product_providers = explode( '|~|', $products_array );
			$reference_transfer = '';
			$link->autocommit( false );
		//inserta la información temporal para ajuste de inventario
			if( $original_transfer_product != null && $original_transfer_product != 'no' ){
				if( ! insert_inventory_adjust( $original_transfer_product, $user, $link ) ){
					return 'No se pudo insertar el registro de ajuste de inventario.' ;
				}
			}
		//itera los productos por insertar
			foreach ( $product_providers as $key => $product_provider ) {
			//inserta los detalles del surtimiento
				$product = explode( '~', $product_provider );
				$reference_transfer = $product[0];
				$sql = "INSERT INTO ec_transferencias_surtimiento_usuarios ( /*1*/id_surtimiento_usuario, /*2*/id_transferencia_producto,
				/*3*/id_producto, /*4*/id_proveedor_producto, /*5*/cantidad_cajas_surtidas, /*6*/cantidad_paquetes_surtidos, /*7*/cantidad_piezas_surtidas,
				/*8*/total_piezas_surtidas, /*9*/id_usuario_surtimiento, /*10*/id_transferencia_surtimiento, /*11*/id_surtimiento_detalle )
					SELECT
						/*1*/null, 
						/*2*/'{$product[0]}', 
						/*3*/'{$product[1]}', 
						/*4*/'{$product[2]}', 
						/*5*/'{$product[3]}', 
						/*6*/'{$product[4]}', 
						/*7*/'{$product[5]}', 
						/*8*/( ( {$product[3]} * presentacion_caja )
							  + ( {$product[4]} * piezas_presentacion_cluces )
							  + {$product[5]}
							),
						/*9*/'{$user}', 
						/*10*/'{$product[6]}',
						/*11*/'{$product[7]}'
					FROM ec_proveedor_producto
					WHERE id_proveedor_producto = '{$product[2]}'";
//die( $sql );
				$stm = $link->query( $sql ) or die( "Error al guardar el detalle del surtimiento de Transferencia : " . $link->error );
			
			//actualiza el registro de surtimiento
				$sql = "UPDATE ec_transferencias_surtimiento_detalle SET id_status_surtimiento = 5 
				WHERE id_transferencia_producto = '{$product[0]}'";
				$stm = $link->query( $sql ) or die( "Error al actualizar el detalle del surtimiento de Transferencia  a status Surtido : " . $link->error );
			//actualiza el surtimiento de la transferencia
				$sql = "UPDATE ec_transferencias_surtimiento ts SET ts.id_status_asignacion = 
				IF( ts.total_partidas > (SELECT COUNT( id_surtimiento_detalle ) 
										FROM ec_transferencias_surtimiento_detalle 
										WHERE id_transferencia_surtimiento = '{$product[6]}' 
										AND id_status_surtimiento = 2 ), 
				2, 4)
				WHERE ts.id_transferencia_surtimiento = '{$product[6]}'";
				$stm = $link->query( $sql ) or die( "Error al actualizar el detalle del surtimiento de Transferencia  a status Surtido : " . $link->error );
			//actualiza la cantidad surtida en la transferencia producto
				$sql = "SELECT 
							id_transferencia_producto 
					FROM ec_transferencia_productos
					WHERE id_transferencia = '{$transfer_id}'
					AND id_proveedor_producto = '{$product[2]}'";
				//die( $sql );
				$stm = $link->query( $sql ) or die( "Error al consultar el id detalle de Transferencia Producto : " . $link->error );
				$tmp_action = '';
				if( $stm->num_rows <= 0 ){
					$tmp_action = 'insertar';
				
					$sql = "INSERT INTO ec_transferencia_productos( /*1*/id_transferencia, /*2*/id_producto_or, 
						/*3*/id_presentacion, /*4*/cantidad_presentacion, /*5*/cantidad, /*6*/id_producto_de, 
						/*7*/referencia_resolucion, /*8*/cantidad_cajas, /*9*/cantidad_paquetes, 
						/*10*/cantidad_piezas, /*11*/id_proveedor_producto, /*12*/cantidad_cajas_surtidas,
						/*13*/cantidad_paquetes_surtidos, /*14*/cantidad_piezas_surtidas, 
						/*15*/total_piezas_surtimiento, /*16*/agregado_surtimiento_validacion )
						SELECT
						/*1*/'{$transfer_id}',
						/*2*/'{$product[1]}',
						/*3*/-1,
						/*4*/( pp.presentacion_caja * {$product[3]} ) 
								+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
								+ {$product[5]} ,
						/*5*/( pp.presentacion_caja * {$product[3]} ) 
								+ ( pp.piezas_presentacion_cluces * {$product[4] }) 
								+ {$product[5]} ,
						/*6*/'{$product[1]}',
						/*7*/( pp.presentacion_caja * {$product[3]} ) 
								+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
								+ {$product[5]} ,
						/*8*/'{$product[3]}',
						/*9*/'{$product[4]}',
						/*10*/'{$product[5]}',
						/*11*/'{$product[2]}',
						/*12*/'{$product[3]}',
						/*13*/'{$product[4]}',
						/*14*/'{$product[5]}',
						/*15*/( pp.presentacion_caja * {$product[3]} ) 
								+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
								+ {$product[5]} ,
						/*16*/ '1'
						FROM ec_proveedor_producto pp
						WHERE pp.id_proveedor_producto = '{$product[2]}'";
				}else{
					$tmp_action = 'actualizar';
					$sql = "UPDATE ec_transferencia_productos tp 
					LEFT JOIN ec_proveedor_producto pp 
					ON tp.id_proveedor_producto = pp.id_proveedor_producto
					SET tp.cantidad_cajas_surtidas = '{$product[3]}',
					tp.cantidad_paquetes_surtidos = '{$product[4]}', 
					tp.cantidad_piezas_surtidas = '{$product[5]}',
					tp.total_piezas_surtimiento = ( pp.presentacion_caja * {$product[3]} ) 
								+ ( pp.piezas_presentacion_cluces * {$product[4]}) 
								+ {$product[5]}
					WHERE tp.id_transferencia_producto = '{$product[0]}'
					AND pp.id_proveedor_producto = '{$product[2]}'";
				}

				$stm = $link->query( $sql ) or die( "Error al {$tmp_action} surtimiento en Transferencia Producto : " 
					. $link->error  );
			//inserta el detalle del movimiento de almacen agregado
				if( $tmp_action == 'insertar' ){
				//recupera el id del registro insertado
					$new_detail_id = $link->insert_id;
				//obtiene el id de movimiento de almacen
					$sql = "SELECT 
								id_movimiento_almacen 
							FROM ec_movimiento_almacen 
							WHERE id_transferencia IN( $transfer_id )";
					$exc = $link->query( $sql ) or die( "Error al consultar el id de movimiento de almacen : " . $link->error );
					$mov_id = $exc->fetch_row();
//echo 'mov_id : ' . $sql . ' ||| ';
				//inserta el nuevo detalle de movimiento almacen
					$sql = "INSERT INTO ec_movimiento_detalle(id_movimiento, id_producto,cantidad,cantidad_surtida, 
							id_pedido_detalle, id_oc_detalle, id_proveedor_producto )
							SELECT 
								'{$mov_id[0]}',
								tp.id_producto_or,
								tp.cantidad,
								tp.cantidad,
								-1,
								-1, 
								tp.id_proveedor_producto
							FROM ec_transferencia_productos tp
							WHERE tp.id_transferencia_producto = '{$new_detail_id}'";
					$exc = $link->query( $sql )or die( "Error al insertar el detalle del movimiento de almacen : " . $link->error );
				}
			}
		//actualiza el satus de la transferencia
			$sql = "UPDATE ec_transferencias 
						SET id_estado = IF( id_estado = 6, 4 ,3 ) 
					WHERE id_transferencia IN( {$transfer_id} )";
			$stm = $link->query( $sql ) or die( "Error al editar el status de la transferencia : " . $link->error );
			$link->autocommit( true );
			return 'Producto Registrado exitosamente.';
		}

		function saveProductSupplyEdition( $products_array, $user, $transfer_id, $is_edition, 
									$edition_row_id, $original_transfer_product = null, $link ){
			$resp = '';

		//inserta la información temporal para ajuste de inventario
			if( $original_transfer_product != null  && $original_transfer_product != 'no' ){
				if( ! insert_inventory_adjust( $original_transfer_product, $user, $link ) ){
					return 'No se pudo insertar el registro de ajuste de inventario.' ;
				}
			}
		//itera los productos por insertar
			$product_providers = explode( '|~|', $products_array );
			$reference_transfer = '';
			$link->autocommit( false );
			foreach ( $product_providers as $key => $product_provider ) {
				$new_detail_id = null;
				$product = explode( '~', $product_provider );
				$reference_transfer = $product[0];
			//verifica si existe
				$sql = "SELECT 
							id_surtimiento_usuario AS supply_id
						FROM ec_transferencias_surtimiento_usuarios tsu
						LEFT JOIN ec_transferencia_productos tp
						ON tp.id_transferencia_producto = tsu.id_transferencia_producto
						LEFT JOIN ec_transferencias t
						ON t.id_transferencia = tp.id_transferencia
						WHERE t.id_transferencia = '{$transfer_id}'
						AND tsu.id_producto = '{$product[1]}'
						AND tsu.id_proveedor_producto = '{$product[2]}'";
				$stm = $link->query( $sql ) or die( "Error al consultar si existe el registro : " . $link->error );
				$tmp_action = '';
				if( $stm->num_rows <= 0 ){
				//verifica que exista el registro de transferencia producto
					$sql = "SELECT 
								id_transferencia_producto
							FROM ec_transferencia_productos
							WHERE id_transferencia IN( {$transfer_id} )
							AND id_producto_or = '{$product[1]}'
							AND id_proveedor_producto = '{$product[2]}'";
					$stm2 = $link->query( $sql ) or die( "Error al consultar registro de id_transferencia_producto : " . $link->error );
					if( $stm2->num_rows <= 0 ){
					//inserta el detalle de transferencia
						$tmp_action = 'insertar';
					
						$sql = "INSERT INTO ec_transferencia_productos( /*1*/id_transferencia, /*2*/id_producto_or, 
							/*3*/id_presentacion, /*4*/cantidad_presentacion, /*5*/cantidad, /*6*/id_producto_de, 
							/*7*/referencia_resolucion, /*8*/cantidad_cajas, /*9*/cantidad_paquetes, 
							/*10*/cantidad_piezas, /*11*/id_proveedor_producto, /*12*/cantidad_cajas_surtidas,
							/*13*/cantidad_paquetes_surtidos, /*14*/cantidad_piezas_surtidas, 
							/*15*/total_piezas_surtimiento, /*16*/agregado_surtimiento_validacion )
							SELECT
							/*1*/'{$transfer_id}',
							/*2*/'{$product[1]}',
							/*3*/-1,
							/*4*/( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
									+ {$product[5]} ,
							/*5*/( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4] }) 
									+ {$product[5]} ,
							/*6*/'{$product[1]}',
							/*7*/( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
									+ {$product[5]} ,
							/*8*/'{$product[3]}',
							/*9*/'{$product[4]}',
							/*10*/'{$product[5]}',
							/*11*/'{$product[2]}',
							/*12*/'{$product[3]}',
							/*13*/'{$product[4]}',
							/*14*/'{$product[5]}',
							/*15*/( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4]} ) 
									+ {$product[5]} ,
							/*16*/ '1'
							FROM ec_proveedor_producto pp
							WHERE pp.id_proveedor_producto = '{$product[2]}'";
					}else{
					//actualiza el detalle de transferencia
						$tmp_action = 'actualizar';
						$sql = "UPDATE ec_transferencia_productos tp 
						LEFT JOIN ec_proveedor_producto pp 
						ON tp.id_proveedor_producto = pp.id_proveedor_producto
						SET tp.cantidad_cajas_surtidas = '{$product[3]}',
						tp.cantidad_paquetes_surtidos = '{$product[4]}', 
						tp.cantidad_piezas_surtidas = '{$product[5]}',
						tp.total_piezas_surtimiento = ( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4]}) 
									+ {$product[5]}
						WHERE tp.id_transferencia_producto = '{$product[0]}'
						AND pp.id_proveedor_producto = '{$product[2]}'
						AND tp.id_transferencia IN( {$transfer_id} )";
					}

					$stm = $link->query( $sql ) or die( "Error al {$tmp_action} detalle de Transferencia Producto en edición: " 
					. $link->error  );

				//inserta el detalle del movimiento de almacen agregado
					if( $tmp_action == 'insertar' ){
					//recupera el id del registro insertado
						$new_detail_id = $link->insert_id;
					//obtiene el id de movimiento de almacen
						$sql = "SELECT 
									id_movimiento_almacen 
								FROM ec_movimiento_almacen 
								WHERE id_transferencia IN( {$transfer_id} )";
						$exc = $link->query( $sql ) or die( "Error al consultar el id de movimiento de almacen : " . $link->error );
						$mov_id = $exc->fetch_row();
	//echo 'mov_id : ' . $sql . ' ||| ';
					//inserta el nuevo detalle de movimiento almacen
						$sql = "INSERT INTO ec_movimiento_detalle(id_movimiento, id_producto,cantidad,cantidad_surtida, 
								id_pedido_detalle, id_oc_detalle, id_proveedor_producto )
								SELECT 
									'{$mov_id[0]}',
									tp.id_producto_or,
									tp.cantidad,
									tp.cantidad,
									-1,
									-1, 
									tp.id_proveedor_producto
								FROM ec_transferencia_productos tp
								WHERE tp.id_transferencia_producto = '{$new_detail_id}'";
						$exc = $link->query( $sql )or die( "Error al insertar el detalle del movimiento de almacen : " . $link->error .  $sql );
					}

					$sql = "INSERT INTO ec_transferencias_surtimiento_usuarios ( /*1*/id_surtimiento_usuario, /*2*/id_transferencia_producto,
					/*3*/id_producto, /*4*/id_proveedor_producto, /*5*/cantidad_cajas_surtidas, /*6*/cantidad_paquetes_surtidos, /*7*/cantidad_piezas_surtidas,
					/*8*/total_piezas_surtidas, /*9*/id_usuario_surtimiento, /*10*/id_transferencia_surtimiento, /*11*/id_surtimiento_detalle )
						SELECT
							/*1*/null, 
							/*2*/'{new_detail_id}', 
							/*3*/'{$product[1]}', 
							/*4*/'{$product[2]}', 
							/*5*/'{$product[3]}', 
							/*6*/'{$product[4]}', 
							/*7*/'{$product[5]}', 
							/*8*/( ( {$product[3]} * presentacion_caja )
								  + ( {$product[4]} * piezas_presentacion_cluces )
								  + {$product[5]}
								),
							/*9*/'{$user}', 
							/*10*/'{$product[6]}',
							/*11*/'{$product[7]}'
						FROM ec_proveedor_producto
						WHERE id_proveedor_producto = '{$product[2]}'";

				}else{
					$row = $stm->fetch_assoc();
					
					$sql = "UPDATE ec_transferencia_productos tp 
					LEFT JOIN ec_proveedor_producto pp 
					ON tp.id_proveedor_producto = pp.id_proveedor_producto
					SET tp.cantidad_cajas_surtidas = '{$product[3]}',
					tp.cantidad_paquetes_surtidos = '{$product[4]}', 
					tp.cantidad_piezas_surtidas = '{$product[5]}',
					tp.total_piezas_surtimiento = ( pp.presentacion_caja * {$product[3]} ) 
									+ ( pp.piezas_presentacion_cluces * {$product[4]}) 
									+ {$product[5]}
						WHERE tp.id_producto_or = '{$product[1]}'
						AND pp.id_proveedor_producto = '{$product[2]}'
						AND tp.id_transferencia IN( {$transfer_id} )";

					$stm2 = $link->query( $sql ) or die( "Error al actualizar registro de detalle transferencia en edición : " . $link->error . $sql );
					
			//	echo $sql;
					$sql = "UPDATE ec_transferencias_surtimiento_usuarios tsu
							LEFT JOIN ec_proveedor_producto pp
							ON tsu.id_proveedor_producto = pp.id_proveedor_producto
								SET tsu.cantidad_cajas_surtidas = '{$product[3]}',
								tsu.cantidad_paquetes_surtidos = '{$product[4]}',
								tsu.cantidad_piezas_surtidas = '{$product[5]}',
								tsu.total_piezas_surtidas = ( ( {$product[3]} * pp.presentacion_caja ) 
								+ ( {$product[3]} * pp.piezas_presentacion_cluces ) + {$product[5]} )
							WHERE tsu.id_surtimiento_usuario = '{$row['supply_id']}'";

				}
				$stm = $link->query( $sql ) or die( "Error al actualizar / insertar registro de surtimiento en edición : " . $link->error . $sql );
			}
			$link->autocommit( true );
		
			return 'Los cambios fueron guardados exitosamente';
		}

		function checkPassword( $password, $link ){
			$sql = "SELECT id_usuario FROM sys_users WHERE contrasena = md5( '{$password}' )";
			$stm = $link->query( $sql ) or die( "Error al verificar passord de encargado : " . $link->error );
			if( $stm->num_rows <= 0 ){
				die( 'La contraseña del encargado es incorrecta.' );
			}
			return '';
		}

		function buildListSupplied( $assignment_id, $link ){
			$resp = '';
			$sql = "SELECT
						tsu.id_surtimiento_usuario AS detail_id,
						p.nombre AS name,
						pp.clave_proveedor AS model,
						tsu.cantidad_cajas_surtidas AS boxes,
						tsu.cantidad_paquetes_surtidos AS packs,
						tsu.cantidad_piezas_surtidas AS pieces,
						tsu.total_piezas_surtidas AS total
					FROM ec_transferencias_surtimiento_usuarios tsu
					LEFT JOIN ec_productos p 
					ON p.id_productos = tsu.id_producto
					LEFT JOIN ec_transferencia_productos tp
					ON tp.id_transferencia_producto = tsu.id_transferencia_producto
					LEFT JOIN ec_transferencias t 
					ON t.id_transferencia = tp.id_transferencia
					LEFT JOIN ec_proveedor_producto pp
					ON pp.id_proveedor_producto = tsu.id_proveedor_producto
					LEFT JOIN ec_transferencias_surtimiento_detalle tsd
					ON tsd.id_transferencia_producto = tsu.id_transferencia_producto
					LEFT JOIN ec_transferencias_surtimiento ts
					ON ts.id_transferencia_surtimiento = tsd.id_transferencia_surtimiento
					WHERE 1 AND ts.id_transferencia_surtimiento IN( {$assignment_id} )
					GROUP BY tsu.id_surtimiento_usuario";
			//return $sql;
			$stm = $link->query( $sql ) or die( "Error al consultar los productos surtidos : " . $link->error );
			if( $stm->num_rows <= 0 ){
				$resp .= '<tr><td colspan="6" align="center">Sin registros!</td></tr>';
			}
			while ( $row = $stm->fetch_assoc() ) {
				$resp .= '<tr onclick="edit_specific_detail( ' . $row['detail_id'] . ' );">';
					$resp .= "<td>{$row['name']}</td>";
					$resp .= "<td>{$row['model']}</td>";
					$resp .= "<td>{$row['boxes']}</td>";
					$resp .= "<td>{$row['packs']}</td>";
					$resp .= "<td>{$row['pieces']}</td>";
					$resp .= "<td>{$row['total']}</td>";
				$resp .= '</tr>';
			}
			return $resp;
		}

	//consulta la contraseña de encargado
		function checkManagerPassword( $pss, $link ){
			$sql = "SELECT 
						id_usuario 
					FROM sys_sucursales s
					LEFT JOIN sys_users u
					ON s.id_encargado = u.id_usuario
					WHERE s.id_sucursal = 1
					AND u.contrasena = md5( '{$pss}' )"; //return $sql;
			$stm = $link->query( $sql )or die( "Error al validar la contraseña de encaragado : " . $link->error );
			if( $stm->num_rows <= 0 ){
				return 'Contraseña incorrecta, verifiquela y vuelva a intentar!';
			}else{
				return 'ok';
			}
		}

		function deleteProductSupplie( $row_id, $transfer_detail_id, $link ){
			$link->autocommit( false );
		//elimina el detalle del surtimiento
			$sql = "DELETE FROM ec_transferencias_surtimiento_usuarios 
					WHERE id_surtimiento_usuario = '{$row_id}'";
			$stm = $link->query( $sql ) or die( "Error al eliminar el detalle de surtimiento : " . $link->error );
			//echo $sql . "\n";
		//actualiza el detalle del surtimiento
			$sql = "UPDATE ec_transferencia_productos SET
						cantidad_cajas_surtidas = 0,
						cantidad_paquetes_surtidos = 0,
						cantidad_piezas_surtidas = 0,
						total_piezas_surtimiento = 0
					WHERE id_transferencia_producto = '{$transfer_detail_id}'";
			$stm = $link->query( $sql ) or die( "Error al actualizar el detalle transferencia producto : " . $link->error );
			//echo $sql;
			$sql = "UPDATE ec_transferencias_surtimiento_detalle 
						SET id_status_surtimiento = '1'
					WHERE id_transferencia_producto = '{$transfer_detail_id}'";
			$stm = $link->query( $sql ) or die( "Error al actualizar el detalle de asignación : " . $link->error );
			$link->autocommit( true );
			return "Surtimiento eliminado exitosamente!";
		}


?>