<?php
	if( isset( $_GET['fl'] ) ){
		include( '../../../config.ini.php' );
		include( '../../../conect.php' );
		include( '../../../conexionMysqli.php' );

		$action = $_GET['fl'];

		switch ( $action ) {
			case 'seekTicketBarcode' :
				/*if( !isset( $_GET['manager_permission'] ) ){
					 $_GET['manager_permission']  = null;
				}
				if( !isset( $_GET['pieces_quantity'] ) ){
					 $_GET['pieces_quantity']  = null;
				}*/

				echo validateTicketBarcode( $_GET['barcode'], $sucursal_id, $link );
			break;

			case 'seekProductBarcode' : 
				echo validateProductBarcode( $_GET['barcode'], $_GET['ticket_id'], $user_id, $sucursal_id, $link );
			break;

			case 'getTicketDetail' :
				echo getTicketDetail( $_GET['p_k'],  $_GET['type'], $link );
			break;

			case 'finishValidation' : 
				echo finishValidation( $_GET['p_k'], $sucursal_id, $link );
			break;
			

			/*case 'getReceptionProductDetail' :
				echo getReceptionProductDetail( $_GET['transfers'], $_GET['p_id'], $_GET['p_p_id'], $user_id, $link );
			break;

			case 'validateManagerPassword' : 
				echo validateManagerPassword( $_GET['pass'], $link );
			break;

			case 'getProductResolution' :
				echo getProductResolution( $_GET['t_id'], $_GET['t_p'], $_GET['p_id'], $_GET['type'], $link );
			break;

			case 'saveResolutionRow' :
				echo saveResolutionRow( $_GET['product_id'], $_GET['transfer_product_id'], $_GET['quantity'], $_GET['type'], $user_id, $link );
			break;*/

			default:
				die( "Permission Denied!" );
			break;
		}
	}

	function validateTicketBarcode( $barcode, $user_sucursal, $link ){
		$resp = "";
		$sql = "SELECT 
					id_pedido AS row_id,
					folio_nv,
					total
				FROM ec_pedidos
				WHERE id_sucursal = '{$user_sucursal}'
				AND folio_nv != 'agrupacion'
				AND folio_nv = '{$barcode}'
				ORDER BY id_pedido DESC
				LIMIT 1";
		$stm = $link->query( $sql ) or die( "Error al consultar código de barras del ticket : {$link->error} {$sql}" );
		if( $stm->num_rows <= 0 ){
			$resp = "<p align=\"center\" style=\"color: red; font-size : 200%;\">La nota de ventas con el folio : <b>{$barcode}</b> no fue encontrada.<br>Verifique y vuelva a intentar!</p>";
			$resp .= "<div class=\"row\">";
				$resp .= "<div class=\"col-2\"></div>";
				$resp .= "<div class=\"col-8\">";
					$resp .= "<button class=\"btn btn-success form-control\"
					 onclick=\"close_emergent( null, '#barcode_seeker' );\">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div>";
			$resp .= "</div><br><br>";
			return $resp;
		}else{
			$row = $stm->fetch_assoc();
			return "ok|{$row['row_id']}|{$row['folio_nv']}|{$row['total']}";
		}
	}

	function validateProductBarcode( $barcode, $ticket_id, $user, $sucursal, $link ){
		$resp = "";
	//verifica que el código de barras exista
		$sql = "SELECT
					id_producto
				FROM ec_proveedor_producto
				WHERE codigo_barras_pieza_1 = '{$barcode}'
				OR codigo_barras_pieza_2 = '{$barcode}'
				OR codigo_barras_pieza_1 = '{$barcode}'";
		$stm = $link->query( $sql ) or die( "Error al consultar que exista el código de barras del producto : {$link->error}");
		if( $stm->num_rows <= 0 ){
			$resp = "<p align=\"center\" style=\"color : red; font-size : 200%;\">
				El código de barras '<b>{$barcode}</b>' no pertenece a ningún producto.<br>Verifica y vuelve a intentar</p>";
			$resp .= "<div class=\"row\">";
				$resp .= "<div class=\"col-2\"></div>";
				$resp .= "<div class=\"col-8\">";
					$resp .= "<button class=\"btn btn-danger form-control\" onclick=\"close_emergent( null, '#barcode_seeker' );\">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div><br><br>";
			$resp .= "</div>";
			return $resp;
		}
		

		$sql = "SELECT
					ax.id_pedido_detalle AS row_id,
					ax.product_id,
					ax.product_provider_id,
					ax.total_quantity,
					SUM( IF( pvu.id_pedido_validacion IS NULL, 0, pvu.piezas_validadas ) ) AS validated_quantity
				FROM(
					SELECT
						pd.id_producto AS product_id,
						pp.id_proveedor_producto AS product_provider_id,
						pd.cantidad AS total_quantity,
						pd.id_pedido_detalle
					FROM ec_pedidos_detalle pd
					LEFT JOIN ec_proveedor_producto pp
					ON pp.id_producto = pd.id_producto
					WHERE pd.id_pedido = {$ticket_id}
					AND ( pp.codigo_barras_pieza_1 = '{$barcode}'
					OR pp.codigo_barras_pieza_2 = '{$barcode}'
					OR pp.codigo_barras_pieza_3 = '{$barcode}' )
					GROUP BY pd.id_pedido_detalle
				)ax
				LEFT JOIN ec_pedidos_validacion_usuarios pvu
				ON pvu.id_pedido_detalle = ax.id_pedido_detalle
				GROUP BY ax.id_pedido_detalle";
		$stm = $link->query( $sql ) or die( "Error al consultar los códigos de barras del producto : {$link->error} {$sql}");
		
		if( $stm->num_rows <= 0 ){
			$resp = "<p align=\"center\" style=\"color : red; font-size : 200%;\">
				El producto no esta en esta nota de venta.<br><b style=\"color : orange;\">Aparta este producto de los que se le van a entregar al cliente.</b></p>";
			$resp .= "<div class=\"row\">";
				$resp .= "<div class=\"col-2\"></div>";
				$resp .= "<div class=\"col-8\">";
					$resp .= "<button class=\"btn btn-danger form-control\" onclick=\"close_emergent( null, '#barcode_seeker' );\">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div><br><br>";
			$resp .= "</div>";
			return $resp;
		}
		$row = $stm->fetch_assoc();
	//verifica que no se pase del numero por validar
		if( $row['validated_quantity'] >= $row['total_quantity'] ){
			$resp = "<p align=\"center\" style=\"color : orange; font-size : 200%;\">
				Este producto ya fue validado completamente en relación a la nota de venta. Vuelve a contar estos productos</p>";
			$resp .= "<p style=\"font-size : 150%;\">Piezas compradas : {$row['total_quantity']}</p>";
			$resp .= "<p style=\"font-size : 150%;\">Piezas validadas : {$row['validated_quantity']}</p>";
			$resp .= "<p style=\"color : red;\">Si el producto está de más apartalo de los que se le van a entregar al cliente.</p>";
			$resp .= "<div class=\"row\">";
				$resp .= "<div class=\"col-2\"></div>";
				$resp .= "<div class=\"col-8\">";
					$resp .= "<button class=\"btn btn-danger form-control\" onclick=\"close_emergent( null, '#barcode_seeker' );\">";
						$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
					$resp .= "</button>";
				$resp .= "</div><br><br>";
			$resp .= "</div>";
			return $resp;
		}
	//inserta el registro de validación
		$sql = "INSERT INTO ec_pedidos_validacion_usuarios ( /*1*/id_pedido_validacion, /*2*/id_pedido_detalle, /*3*/id_producto, 
				/*4*/id_proveedor_producto, /*5*/piezas_validadas, /*6*/id_usuario, /*7*/id_sucursal, /*8*/fecha_alta )
			VALUES( /*1*/NULL, /*2*/{$row['row_id']}, /*3*/{$row['product_id']}, /*4*/{$row['product_provider_id']}, /*5*/1, 
				/*6*/{$user}, /*7*/{$sucursal}, /*8*/NOW() )";
		$stm = $link->query( $sql ) or die( "Error al insertar el registro de validación de la venta : {$link->error}" );
		
		$resp = "ok|<p align=\"center\" style=\"font-size : 200%; color : green;\"></p>";
		$resp .= "<div class=\"row\">";
			$resp .= "<div class=\"col-2\"></div>";
			$resp .= "<div class=\"col-8\">";
				$resp .= "<button class=\"btn btn-success form-control\" onclick=\"close_emergent( null, '#barcode_seeker' );\">";
					$resp .= "<i class=\"icon-ok-circle\">Aceptar</i>";
				$resp .= "</button>";
			$resp .= "</div><br><br>";
		$resp .= "</div>";
		return $resp;
	}

	function getTicketDetail( $ticket_id, $type, $link ){
		$resp = "";
		if( $type == "pending" ){
			$sql = "SELECT
						ax.row_id,
						ax.name,
						ax.quantity
					FROM(
						SELECT
							pd.id_pedido_detalle AS row_id,
							p.nombre AS name,
							pd.cantidad -  SUM( IF( pvu.id_pedido_validacion IS NULL, 0, pvu.piezas_validadas ) ) AS quantity
						FROM ec_pedidos_detalle pd
						LEFT JOIN ec_productos p
						ON p.id_productos = pd.id_producto
						LEFT JOIN ec_pedidos_validacion_usuarios pvu
						ON pvu.id_pedido_detalle = pd.id_pedido_detalle
						WHERE pd.id_pedido = {$ticket_id}
						GROUP BY pd.id_pedido_detalle
					)ax
					WHERE ax.quantity > 0
					GROUP BY ax.row_id";
		}else if( $type == "validated" ){
			$sql = "SELECT 
						pvu.id_pedido_detalle AS row_id,
						p.nombre AS name, 
						IF( pvu.id_pedido_validacion IS NULL, 0, SUM( pvu.piezas_validadas ) ) AS quantity
					FROM ec_pedidos_validacion_usuarios pvu
					LEFT JOIN ec_pedidos_detalle pd 
					ON pd.id_pedido_detalle = pvu.id_pedido_detalle
					LEFT JOIN ec_productos p
					ON p.id_productos = pd.id_producto
					WHERE pd.id_pedido = {$ticket_id}
					GROUP BY pvu.id_pedido_detalle";
		}
		
		$stm = $link->query( $sql ) or die( "Error al consultar el detalle del pedido : {$link->error}" );
		/*if( $stm->num_rows <= 0 ){
			return '<tr><td colspan="3">Sin registros!</td></tr>';
		}*/
		while( $row = $stm->fetch_assoc() ){
			$resp .= "<tr>";
				$resp .= "<td class=\"no_visible\">{$row['row_id']}</td>";
				$resp .= "<td>{$row['name']}</td>";
				$resp .= "<td>{$row['quantity']}</td>";
			$resp .= "</tr>";
		}
		//$resp .= '|';
		return $resp;
	}

	function finishValidation( $ticket_id, $sucursal, $link ){
		$resp = "";
		$link->autocommit( false );
		$sql = "INSERT INTO ec_movimiento_detalle_proveedor_producto ( /*1*/id_movimiento_detalle_proveedor_producto, 
			/*2*/id_movimiento_almacen_detalle, /*3*/id_proveedor_producto, /*4*/cantidad, /*5*/fecha_registro, 
			/*6*/id_sucursal, /*7*/id_equivalente, /*8*/status_agrupacion, /*9*/id_tipo_movimiento, /*10*/id_almacen )
			SELECT
			/*1*/NULL,
			/*2*/ax.id_movimiento_almacen_detalle,
		    /*3*/pvu.id_proveedor_producto,
		    /*4*/IF( pvu.id_pedido_validacion IS NULL, 0, SUM( pvu.piezas_validadas ) ),
		    /*5*/NOW(),
		    /*6*/'{$sucursal}',
		    /*7*/0,
		    /*8*/-1,
		    /*9*/ax.id_tipo_movimiento,
		    /*10*/ax.id_almacen
			FROM
			(
			    SELECT 
					md.id_movimiento_almacen_detalle,
					0,
					-1,
			    	pd.id_pedido_detalle,
			    	ma.id_tipo_movimiento,
			    	ma.id_almacen
				FROM  ec_pedidos_detalle pd
			    LEFT JOIN ec_movimiento_almacen ma
			    ON ma.id_pedido = pd.id_pedido
				LEFT JOIN ec_movimiento_detalle md
				ON md.id_movimiento = ma.id_movimiento_almacen
				WHERE pd.id_pedido = '{$ticket_id}'
				GROUP BY pd.id_pedido_detalle
			)ax
			LEFT JOIN ec_pedidos_validacion_usuarios pvu
			ON pvu.id_pedido_detalle = ax.id_pedido_detalle
			GROUP BY pvu.id_proveedor_producto";
		//die( $sql );
		$stm = $link->query( $sql ) or die( "Error al insertar los detalles de movimiento almacen proveedor producto : {$link->error}" );
	//actualiza el status de la cabecera de pedidos
		$sql = "UPDATE ec_pedidos SET venta_validada = '1' WHERE id_pedido = {$ticket_id}";
		$stm = $link->query( $sql ) or die( "Error al actualizar la venta a validada : {$link->error}" );
		$link->autocommit( true );
		$resp = "ok|<div class=\"row\">
					<p align=\"center\" style=\"color : green;\">Nota de venta validada exitosamente.<br>Por favor entregue los productos al cliente!</p>	
					<div class=\"col-2\"></div>
					<div class=\"col-8\">
						<button class=\"btn btn-success\" onclick=\"location.reload();\">
							<i class=\"icon-ok-circle\">Aceptar</i>
						</button>
					</div>
				</div>";
		return $resp;
	}

?>