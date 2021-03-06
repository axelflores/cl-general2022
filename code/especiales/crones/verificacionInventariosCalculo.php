<?php
//consulta todos los almacenes
	$sql = "SELECT id_almacen, nombre FROM ec_almacen WHERE id_almacen > 0";
	$alm = $link->query( $sql )or die( "Error al consultar los almacenes : " . $link->error );
	$tabla_creada = 0;
	$diferencias_encontradas = array();
	while( $almacen = $alm->fetch_assoc() ){
/*Verificacion de inventario correcto por sucursal y almacen*/
		$sql = "SELECT
				    ax2.id_productos,
				    ax2.nombre,
				    ax2.nomAlmacen,
				    ax2.inventario,
				    ax2.InvCalculo,
				    (SELECT IF( log.id_log IS NULL,
				    			0,
				    			log.cantidad_inventario_despues
				    		) as invLog
					FROM Log_almacen_producto log
					WHERE log.id_almacen = ax2.id_almacen
					AND log.id_producto = ax2.id_productos
					ORDER BY log.id_log DESC
					LIMIT 1
					) AS registrosLog
				FROM(
				    SELECT
				        ax1.id_productos,
				        ax1.nombre,
				        ax1.inventario,
				        ax1.nomAlmacen,
		            	ax1.id_almacen,
				        IF(ax1.InvCalculo IS NULL, 0, ax1.InvCalculo) AS InvCalculo
				    FROM(
				        SELECT 
				            ax.id_productos,
				            ax.nombre,
				            ax.inventario,
				            ax.nomAlmacen,
			                ax.id_almacen,
				            SUM(
				                IF(
				                    ma.id_movimiento_almacen IS NULL,
				                    0,
				                    md.cantidad * tm.afecta
				                )
				            ) as InvCalculo
				        FROM(
				            SELECT
				                p.id_productos,
				                ap.inventario,
				                p.nombre,
				                alm.nombre as nomAlmacen,
				                ap.id_almacen
				            FROM ec_almacen_producto ap
				            LEFT JOIN ec_productos p On p.id_productos = ap.id_producto
				            LEFT JOIN ec_almacen alm ON alm.id_almacen = ap.id_almacen
				            WHERE ap.id_almacen = '{$almacen['id_almacen']}'
				            GROUP BY p.id_productos
				        )ax
				        LEFT JOIN ec_movimiento_detalle md ON md.id_producto = ax.id_productos
				        LEFT JOIN ec_movimiento_almacen ma ON ma.id_movimiento_almacen = md.id_movimiento
				        LEFT JOIN ec_tipos_movimiento tm ON tm.id_tipo_movimiento = ma.id_tipo_movimiento
				        AND ma.id_almacen = '{$almacen['id_almacen']}'
				        GROUP BY ax.id_productos
				    )ax1
				    GROUP BY ax1.id_productos
				)ax2
				WHERE ax2.inventario != ax2.InvCalculo
				GROUP BY ax2.id_productos";
				//die($sql);
		$diferencias = $link->query( $sql ) or die("Error al consultar las diferencias entre almacen producto (tabla de magento) y el inventario calculado : "
			. $link->error);

		$num_dif = $diferencias->num_rows;
	
	//si encuentra diferencias en los inventarios
		if( $num_dif > 0 ){
		//creaci??n del encabezado de la tabla
			if( $tabla_creada == 0 ){
				$mensaje .= '<h2>Fueron encontradas las siguientes diferencias entre almacen - producto e inventario calculado</h2>';
				$encabezado = array('#','Id Producto', 'Almac??n', 'Producto','Inventario (Almac??n Producto)',
					'Inventario (Calculado)', 'Inventario (Tabla de Respaldo)');

				$mensaje .= "<br/>";
				$mensaje .= ( $es_navegador == 1 ? 
							$report->csv_header_generator( $encabezado ) : 
							$report->crea_tabla_log( $encabezado ) 
				);//crea encabezado de la tabla	
				$tabla_creada = 1;
			}
			while( $dif = $diferencias->fetch_assoc() ){
				array_push($diferencias_encontradas, $dif);//agrega el registro al arreglo global
			}
		}
	}//fin de iteraci??n de almacenes
	
	$diferencias_calculadas = '';
	if( sizeof( $diferencias_encontradas ) > 0 ){
		foreach ($diferencias_encontradas as $key => $diferencia) {
			$diferencias_calculadas .= ( $es_navegador == 1 ? 
								$report->csv_row_generator( $diferencia ) : 
								$report->crea_fila_tabla_log( $diferencia ) 
			);
		}
	}else{		
		$mensaje .= '<h2>No Fueron encontradas diferencias entre almacen - producto e inventario calculado</h2>';
	}
	if( $es_navegador == 0){
		$mensaje = str_replace('|table_content|', $diferencias_calculadas, $mensaje);
	}else{
		$mensaje .= $diferencias_calculadas;
	}

	$mesaje .= '<h3>Diferencias por Proveedor - Producto</h3>';
/*Verificacion por proveedor producto*/
	/*Verificacion de inventario correcto por sucursal y almacen*/	
	$sql = "SELECT id_almacen, nombre FROM ec_almacen WHERE id_almacen > 0";
	$alm = $link->query( $sql )or die( "Error al consultar los almacenes : " . $link->error );
	$tabla_creada = 0;
	$diferencias_encontradas = array();
	while( $almacen = $alm->fetch_assoc() ){
		$sql = "SELECT
				    ax2.id_productos,
				    ax2.nombre,
				    ax2.nomAlmacen,
				    ax2.inventario,
				    ax2.InvCalculo,
				    (SELECT IF( log.id_log IS NULL,
				    			0,
				    			log.cantidad_inventario_despues
				    		) as invLog
					FROM Log_almacen_producto log
					WHERE log.id_almacen = ax2.id_almacen
					AND log.id_producto = ax2.id_productos
					ORDER BY log.id_log DESC
					LIMIT 1
					) AS registrosLog
				FROM(
				    SELECT
				        ax1.id_productos,
				        ax1.nombre,
				        ax1.inventario,
				        ax1.nomAlmacen,
		            	ax1.id_almacen,
				        IF(ax1.InvCalculo IS NULL, 0, ax1.InvCalculo) AS InvCalculo
				    FROM(
				        SELECT 
				            ax.id_productos,
				            ax.nombre,
				            ax.inventario,
				            ax.nomAlmacen,
			                ax.id_almacen,
				            SUM(
				                IF(
				                    ma.id_movimiento_almacen IS NULL,
				                    0,
				                    md.cantidad * tm.afecta
				                )
				            ) AS InvCalculo
				        FROM(
				            SELECT
				                p.id_productos,
				                SUM( ipp.inventario) AS inventario,
				               	CONCAT( p.nombre, ' ( MODELO : ', IF( pp.clave_proveedor IS NULL, '', pp.clave_proveedor ) , ' ) ' ) AS nombre,
				                alm.nombre AS nomAlmacen,
				                ipp.id_almacen
				            FROM ec_inventario_proveedor_producto ipp
				            LEFT JOIN ec_productos p On p.id_productos = ipp.id_producto
				            LEFT JOIN ec_almacen alm ON alm.id_almacen = ipp.id_almacen
				            LEFT JOIN ec_proveedor_producto pp 
				            ON ipp.id_proveedor_producto = pp.id_proveedor_producto
				            WHERE ipp.id_almacen = '{$almacen['id_almacen']}'
				            GROUP BY p.id_productos, alm.id_almacen/*, pp.id_proveedor_producto*/
				        )ax
				        LEFT JOIN ec_movimiento_detalle md ON md.id_producto = ax.id_productos
				        LEFT JOIN ec_movimiento_almacen ma ON ma.id_movimiento_almacen = md.id_movimiento
				        LEFT JOIN ec_tipos_movimiento tm ON tm.id_tipo_movimiento = ma.id_tipo_movimiento
				        AND ma.id_almacen = '{$almacen['id_almacen']}'
				        GROUP BY ax.id_productos
				    )ax1
				    GROUP BY ax1.id_productos
				)ax2
				WHERE ax2.inventario != ax2.InvCalculo
				GROUP BY ax2.id_productos";
				//die($sql);
		$diferencias = $link->query( $sql ) or die("Error al consultar las diferencias entre almacen producto (tabla de magento) y el inventario calculado : "
			. $link->error);

		$num_dif = $diferencias->num_rows;
	
	//si encuentra diferencias en los inventarios
		if( $num_dif > 0 ){
		//creaci??n del encabezado de la tabla
			if( $tabla_creada == 0 ){
				$mensaje .= '<h2>Fueron encontradas las siguientes diferencias entre almacen - proveedor producto e inventario calculado</h2>';
				$encabezado = array('#','Id Producto', 'Almac??n', 'Producto','Inventario (Proveedor Producto)',
					'Inventario (Calculado)', 'Inventario (Tabla de Respaldo)');

				$mensaje .= "<br/>";
				$mensaje .= ( $es_navegador == 1 ? 
							$report->csv_header_generator( $encabezado ) : 
							$report->crea_tabla_log( $encabezado ) 
				);//crea encabezado de la tabla	
				$tabla_creada = 1;
			}
			while( $dif = $diferencias->fetch_assoc() ){
				array_push($diferencias_encontradas, $dif);//agrega el registro al arreglo global
			}
		}
	}//fin de foreach

	$diferencias_calculadas = '';
	if( sizeof( $diferencias_encontradas ) > 0 ){
		foreach ($diferencias_encontradas as $key => $diferencia) {
			$diferencias_calculadas .= ( $es_navegador == 1 ? 
								$report->csv_row_generator( $diferencia ) : 
								$report->crea_fila_tabla_log( $diferencia ) 
			);
		}
	}else{		
		$mensaje .= '<h2>No Fueron encontradas diferencias entre almacen - proveedor producto e inventario calculado</h2>';
	}
	if( $es_navegador == 0){
		$mensaje = str_replace('|table_content|', $diferencias_calculadas, $mensaje);
	}else{
		$mensaje .= $diferencias_calculadas;
	}

?>