<?php
	include("../../../../conectMin.php");
	$fl=$_POST['flag'];
	$id_orden=$_POST['oc'];
/*Implementacion Oscar 23.07.2019 para modificar la ubicacion directo en la tabla de pedidos*/
	if($fl=='ubicacion'){
		$val=$_POST['valor'];
		$id_prod=$_POST['id'];
		$sql="UPDATE ec_productos SET ubicacion_almacen='$val',sincronizar=1 WHERE id_productos=$id_prod";
		$eje=mysql_query($sql)or die("Error al actualizar la ubicacion del almacen!!!\n".mysql_error()."\n".$sql);
		die('ok');
	}

	if($fl=='descuento'){
		$val=$_POST['valor'];
		$id_prod=$_POST['id'];
		$sql="UPDATE ec_productos SET precio_venta_mayoreo='$val',sincronizar=1 WHERE id_productos=$id_prod";
		$eje=mysql_query($sql)or die("Error al actualizar la ubicacion del almacen!!!\n".mysql_error()."\n".$sql);
		die('ok');
	}
/**/
/*implementación Oscar 11.02.2018 para buscar folios de notas de proveedor*/
//buscador de folios
	if($fl=='busca_folios'){
		$id_proveedor=$_POST['id_pro'];
		$type = $_POST['seeker_type'];
		if ( $type == 'remissions' ){
			$sql="SELECT 
					ocr.id_oc_recepcion,/*0*/
					ocr.folio_referencia_proveedor,/*1*/
					ocr.monto_nota_proveedor,/*2*/
					prov.nombre_comercial,/*3*/
					ocr.piezas_remision,/*4*/
					ocr.piezas_recepcion,/*5*/
					ocr.status,/*6*/
					ero.nombre/*7*/
				FROM ec_oc_recepcion ocr
				LEFT JOIN ec_proveedor prov 
				ON ocr.id_proveedor = prov.id_proveedor
				LEFT JOIN ec_estatus_recepcion_oc ero
				ON ero.id_estatus = ocr.status
				WHERE ocr.id_oc_recepcion > 0 AND prov.id_proveedor = '{$id_proveedor}' 
				AND (";
		}else if( $type == 'receptions' ){
			$sql="SELECT 
					rb.id_recepcion_bodega,/*0*/
					rb.folio_recepcion,/*1*/
					0,/*2*/
					prov.nombre_comercial,/*3*/
					rb.numero_partidas,/*4*/
					0,/*5*/
					rb.id_status_validacion,/*6*/
					svrb.nombre_status
				FROM ec_recepcion_bodega rb
				LEFT JOIN ec_proveedor prov 
				ON rb.id_proveedor = prov.id_proveedor
				LEFT JOIN ec_status_validacion_recepcion_bodega svrb
				ON svrb.id_status_validacion = rb.id_status_validacion
				WHERE rb.id_recepcion_bodega > 0 
				AND rb.id_proveedor = '{$id_proveedor}'
				/*AND rb.id_status_validacion < 3*/
				AND (";
		}
	//busqueda por coincidencias
		$clave=explode(" ",$_POST['txt']);
		for($i=0;$i<sizeof($clave);$i++){
			if($i>0){
				$sql.=" AND ";
			}
			$sql.= ($type == 'remissions' ? "ocr.folio_referencia_proveedor" : "rb.folio_recepcion" );
			$sql .= " LIKE '%".$clave[$i]."%'";
		}//fin de for i
		$sql.=")";//ciera el AND de la consulta
//die('ok|'.$sql);
		$eje=mysql_query($sql)or die("error|Error al consultar coincidencias de folio!!!<br>".$sql."<br>".mysql_error());

		echo 'ok|';
		if(mysql_num_rows($eje)<=0){
			die('sin coincidencias');
		}
		//echo '<table width="100%">';
		$c=0;
		while($r=mysql_fetch_row($eje)){
			$c++;
			echo '<div class="remission_option" tabindex="'.$c.'" onclick="carga_folio_recepcion('.$r[0].',\''.$r[1].'\','.$r[2].
				','.$r[4].','.$r[5].',' .$r[6].',\'' . $type . '\');">';
				echo $r[1].' - '.$r[3].' $'.$r[2].'(' . $r[7] . ')';//'<td width="100%" align="left">'.</td>
			echo '</div>';
		}	
		die('');//</table>
	}
/*fin de cambio Oscar 11.2.2018*/

//buscador de productos
	if($fl==1){
	//armamos la consulta
		$sql = "SELECT 
					p.id_productos,
					CONCAT(p.nombre, ' ( MODELO : ', pp.clave_proveedor, ' - ', 
						pp.presentacion_caja, ' )' )
				FROM ec_productos p
				LEFT JOIN ec_proveedor_producto pp ON p.id_productos = pp.id_producto
				WHERE pp.id_proveedor 
				IN( SELECT id_proveedor FROM ec_ordenes_compra WHERE id_orden_compra = '$id_orden')
				AND (";

/*		$sql="SELECT p.id_productos,p.nombre 
		FROM ec_productos p 
		LEFT JOIN ec_oc_detalle ocd ON p.id_productos=ocd.id_producto
		LEFT JOIN ec_ordenes_compra oc ON ocd.id_orden_compra=oc.id_orden_compra
		WHERE oc.id_orden_compra=$id_orden AND (";*/
	//precisamos la búsqueda
		$clave=explode(" ",$_POST['txt']);

		for($i=0;$i<sizeof($clave);$i++){
			if($clave[$i]!='' && $clave[$i]!=null){
				if($i>0){
					$sql.=" AND ";
				}
				$sql.="CONCAT(p.nombre, ' ( MODELO : ', pp.clave_proveedor, ' - ', 
						pp.presentacion_caja, ' )' ) LIKE '%".$clave[$i]."%'";
			}
		}//fin de for i
	//cerramos el parentesis de las condiciones
		$sql.=")";
		//ejecutamos consulta
		$eje=mysql_query($sql)or die("Error al buscar coincidencias!!\n\n".$sql."\n\n".mysql_error());
	//regresamos resultados
		echo 'ok|<table width="100%">';
		$tab=0;
		while($row=mysql_fetch_row($eje)){
			$tab++;
			echo '<tr tabindex="'.$tab.'" id="opc_'.$tab.'" class="opc_busc" onkeyup="valida_opc(event,'.$tab.');" onclick="valida_opc(\'click\','.$tab.');">';
				echo '<td style="display:none;" id="val_opc_'.$tab.'">'.$row[0].'</td>';
				echo '<td>'.$row[1].'</td>';
			echo '</tr>';	
		}	
		echo '</table>';
		echo '<input type="hidden" id="opc_totales" value="'.$tab.'">';
	}//fin de if $fl==1 (si es buscador)

//insertar recepción
	if($fl==2){
		$ref_prov = $_POST['ref'];
		$id_proveedor = $_POST['id_prov'];
		$id_recepcion = $_POST['id'];
		$monto_recepcion = $_POST['mt_nota'];
		$reception_id = $_POST['reference_reception'];
		mysql_query("BEGIN");//marcamos el inicio de la transaccion

	//insertamos el detalle de la Recepción
		$dat=$_POST['datos'];
		$dato=explode("|", $dat);
	//verifica que exista un movimiento relacionado a la cabecera de la remisión
		$sql = "SELECT 
					ma.id_movimiento_almacen
				FROM ec_movimiento_almacen ma
				WHERE ma.id_orden_compra = '{$id_recepcion}' ";
		$stm = mysql_query( $sql ) or die( "Error al consultar el id de movimiento de almacen de la Remisión : " . mysql_error() );
		if( mysql_num_rows( $stm ) <= 0 ){

			$sql = "INSERT INTO ec_movimiento_almacen
					SELECT 
						null, 
						1, 
						id_usuario,
						1, 
						now(), 
						now(), 
						CONCAT('RECEPCIÓN DE NOTA ', folio_referencia_proveedor),
						-1, 
						id_oc_recepcion, 
						null, 
						-1, 
						-1, 
						1, 
						-1, 
						0, 
						'0000-00-00 00:00:00', 
						now()
					FROM ec_oc_recepcion 
					WHERE id_oc_recepcion = '{$id_recepcion}'";	
			$stm = mysql_query( $sql ) or die( "Error al reinsertar movimiento de almacen de la Remisión : " . mysql_error() );
		}

		$orders = array();
		for($i=0;$i<sizeof($dato);$i++){
			$sql="";
			$d=explode("~",$dato[$i]);
		//verificamos si el producto ya existe en la recepcion
			$sql="SELECT id_oc_recepcion_detalle FROM ec_oc_recepcion_detalle 
			WHERE id_oc_recepcion=$id_recepcion AND id_producto=IF('$d[0]'='invalida','$d[1]','$d[0]')";
			//echo $sql;
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");//cancelamos transacción
				die("Error al insertar detalle de Recepción de Órden de Compra!!!\n\n".$sql."\n\n".$error);
			}
			$nvo=1;
			if(mysql_num_rows($eje)==1){
		//echo 'num: '.mysql_num_rows($eje);
				$nvo=0;
				$r=mysql_fetch_row($eje);
				$id_recepcion_detalle=$r[0];
			}

			//if($d[0]=='invalida'){
			//si es invalidar
				/*
DESHABILITADO POR OSCAR 2022
				if($nvo==0){
					$sql="UPDATE ec_oc_recepcion_detalle 
						SET piezas_recibidas=(piezas_recibidas+IF('$d[0]'='invalida',0,'$d[1]')),
						id_proveedor_producto = '{$d[6]}'
					/*,
							monto=(precio_pieza*piezas_recibidas)-((precio_pieza*piezas_recibidas)*porcentaje_descuento)*
						WHERE id_oc_recepcion_detalle=$id_recepcion_detalle";	
						//die($sql);
				}else*/
			if($d[3]!=0||$d[1]!=0||$d[0]!='invalida'){
				$sql = "";
				if( $d[8] == 0 ){
					$sql="INSERT INTO ec_oc_recepcion_detalle 
							SET 
		 					id_oc_recepcion_detalle = null, 
		 					id_oc_recepcion = '{$id_recepcion}', 
		 					id_producto = IF( '{$d[0]}' = 'invalida', '{$d[1]}', '{$d[0]}' ), 
		 					id_proveedor_producto = IF( '{$d[0]}' = 'invalida', -1, '{$d[6]}' ),
							piezas_recibidas = IF( '{$d[0]}' = 'invalida', 0, '{$d[1]}' ), 
							presentacion_caja = IF( '{$d[0]}' = 'invalida', 0, '{$d[4]}' ), 
							precio_pieza = IF( '{$d[0]}' = 'invalida', 0, '{$d[2]}' ), 
							monto = IF( '{$d[0]}' = 'invalida', 0, '{$d[3]}' ), 
							es_valido = IF( '{$d[0]}' = 'invalida', 0, 1 ), 
							observaciones = IF( '{$d[0]}' = 'invalida', 'Se recibió en ceros', '' ),
							porcentaje_descuento = IF( '{$d[0]}' = 'invalida', 0, '{$d[5]}'),
							id_recepcion_bodega_detalle = '{$d[7]}'";
				//echo ( $sql );
				}else if( $d[8] == 1){
					$sql="UPDATE ec_oc_recepcion_detalle 
							SET 
		 					id_oc_recepcion = '{$id_recepcion}', 
		 					id_producto = IF( '{$d[0]}' = 'invalida', '{$d[1]}', '{$d[0]}' ), 
		 					id_proveedor_producto = IF( '{$d[0]}' = 'invalida', -1, '{$d[6]}' ),
							piezas_recibidas = IF( '{$d[0]}' = 'invalida', 0, '{$d[1]}' ), 
							presentacion_caja = IF( '{$d[0]}' = 'invalida', 0, '{$d[4]}' ), 
							precio_pieza = IF( '{$d[0]}' = 'invalida', 0, '{$d[2]}' ), 
							monto = IF( '{$d[0]}' = 'invalida', 0, '{$d[3]}' ), 
							es_valido = IF( '{$d[0]}' = 'invalida', 0, 1 ), 
							observaciones = IF( '{$d[0]}' = 'invalida', 'Se recibió en ceros', '' ),
							porcentaje_descuento = IF( '{$d[0]}' = 'invalida', 0, '{$d[5]}')
						WHERE id_recepcion_bodega_detalle = '{$d[7]}'";
				}	
				
			}

			if($sql!=""){
			//ejecutamos la consulta que inserta el detalle
				$eje=mysql_query($sql);
				if(!$eje){
					$error=mysql_error();
					mysql_query("ROLLBACK");//cancelamos transacción
					die("Error al insertar detalle de Recepción de Órden de Compra!!!\n\n".$sql."\n\n".$error);
				}
			//actualizamos lo recibido a la orden de compra
				$observaciones='se recibio en 0';
				if($d[0]=='invalida'){
					$sql="DELETE FROM ec_oc_detalle WHERE id_producto=$d[1] AND id_orden_compra=$id_orden";
					$eje=mysql_query($sql);
					if(!$eje){
						$error=mysql_error();
						mysql_query("ROLLBACK");//cancelamos transacción
						die("Error al eliminar del detalle de Orden de Compra!!!\n\n".$sql."\n\n".$error);
					}
					$d[0]=$d[1];
					$d[1]=0;
				}

			//consulta los piezas pendientes de recibir
				$sql = "SELECT 
							ocd.id_oc_detalle, 
							( ocd.cantidad - ocd.cantidad_surtido ),
							ocd.id_orden_compra
						FROM ec_oc_detalle ocd
						LEFT JOIN ec_ordenes_compra oc 
						ON oc.id_orden_compra = ocd.id_orden_compra
						WHERE oc.id_estatus_oc <= 3
						AND ocd.cantidad_surtido < ocd.cantidad
						AND ocd.id_producto = '{$d[0]}'
						AND oc.id_proveedor = '{$id_proveedor}'
						GROUP BY ocd.id_oc_detalle
						ORDER BY ocd.id_oc_detalle ASC";
				$exc = mysql_query( $sql ) or die( "Error al consultar ordenes de compra por actualizar : " . mysql_error() );
				while ( $ocd = mysql_fetch_row( $exc ) ) {
					if( $d[1] > 0 ) {
						$aux_r = ( $d[1] >= $ocd[1] ? $ocd[1] : $d[1] );
						$sql="UPDATE ec_oc_detalle SET 
								cantidad_surtido = ( cantidad_surtido + $aux_r ) 
							WHERE id_producto = '{$d[0]}' AND id_oc_detalle = '{$ocd[0]}'";
						$eje=mysql_query($sql);
						if(!$eje){
							$error=mysql_error();
							mysql_query("ROLLBACK");//cancelamos transacción
							die("Error al actualizar piezas recibidas en la Orden de Compra!!!\n\n".$sql."\n\n".$error);
						}
						$d[1] = ( $d[1] - $aux_r );
					//agrega el id de la orden de compra
						if( !in_array( $orders, $ocd[2] ) ){	
							$orders[] = $ocd[2];
						}
					}
			 	} 
				
		/*implementacion Oscar 16.08.2019*/
		//die($d[6]);
				if($d[0]!='invalida' && $d[6]!=''){
				//consultamos la clave de proveedor
					$sql="SELECT clave_proveedor FROM ec_proveedor_producto WHERE id_proveedor_producto=$d[6]";
					//die($sql);
					$eje=mysql_query($sql);
					if(!$eje){
						$error=mysql_error();
						mysql_query("ROLLBACK");//cancelamos transacción
						die("Error al consultar el codigo de proveedor-producto!!!\n\n".$sql."\n\n".$error);
					}
					$r_1=mysql_fetch_row($eje);
			//introducimos el nuevo código de proveedor si no existe
				//corroboramos si esta clave ya existe en la tabla de productos; de lo contrario la insertamos
					$sql="SELECT COUNT(*) FROM ec_productos WHERE id_productos=$d[0] AND clave LIKE '%$r_1[0]%'";
					$eje=mysql_query($sql);
					if(!$eje){
						$error=mysql_error();
						mysql_query("ROLLBACK");//cancelamos transacción
						die("Error al verificar el codigo de proveedor en la tabla de productos!!!\n\n".$sql."\n\n".$error);
					}
					$r_2=mysql_fetch_row($eje);
					if($r_2[0]==0){
					//actualizamos el codigo de proveedor producto en la tabla de productos
						$sql="UPDATE ec_productos SET clave=CONCAT(clave,',','$r_1[0]') WHERE id_productos=$d[0]";
						$eje=mysql_query($sql);
						if(!$eje){
							$error=mysql_error();
							mysql_query("ROLLBACK");//cancelamos transacción
							die("Error al actualizar el codigo alfanumerico en la tabla de productos!!!\n\n".$sql."\n\n".$error);
						}
					}
					//die($sql);
				//actualizamos el proveedor producto, producto
					$precio_caja=$d[2]*$d[4];
					$sql="UPDATE ec_proveedor_producto pp
						LEFT JOIN ec_productos p ON p.id_productos = pp.id_producto
						SET pp.precio_pieza=$d[2],
						pp.presentacion_caja=$d[4],
						pp.precio=$precio_caja,
						p.precio_compra = IF( $d[2] > 0, $d[2], p.precio_compra )
						WHERE pp.id_proveedor_producto=$d[6]";
					$eje_prov=mysql_query($sql);
					if(!$eje_prov){
						$error=mysql_error();
						mysql_query("ROLLBACK");//cancelamos transacción
						die("Error al actualizar los parametros de proveedor producto!!!\n\n".$sql."\n\n".$error);
					}

				}//fin de si el registro es valido
		/*fin de cambio Oscar 16.08.2019*/
			}//fin de if la consulta no esta vacía
		//actualiza el registro de ec_status_validacion_recepcion_bodega
			$sql = "UPDATE ec_recepcion_bodega_detalle
						SET validado = '1',
						id_proveedor_producto = '{$d[6]}',
						piezas_por_caja = '{$d[4]}',
						piezas_sueltas_recibidas = '{$d[9]}',
						cajas_recibidas = '{$d[10]}'
					WHERE id_recepcion_bodega_detalle = '{$d[7]}'";
//die($sql);
			$stm = mysql_query($sql);
			if( !$stm ){				
				mysql_query("ROLLBACK");//cancelamos transacción
				die( "Error al actualizar el detalle a validado : " . mysql_error() . $sql );
			}
		}//fin de for i

//actualizamos las piezas recibidas
		$sql="UPDATE ec_oc_recepcion 
				SET piezas_recepcion=(SELECT SUM( IF(id_oc_recepcion_detalle IS NULL,0,piezas_recibidas) )
				FROM ec_oc_recepcion_detalle WHERE id_oc_recepcion=$id_recepcion ) 
			WHERE id_oc_recepcion=$id_recepcion";
		$eje=mysql_query($sql);
		
		if(!$eje){
			$error=mysql_error();
			mysql_query("ROLLBACK");//cancelamos la transaccion
			die("Error al actualizar las piezas recibidas en la remisión!!!\n".$error."\n".$sql);
		}
//inserta la relacion entre la orden de compra y la recepcion
		foreach ($orders as $key => $id_orden) {
			$sql="INSERT INTO ec_relaciones_oc_recepcion VALUES(null,$id_orden,$id_recepcion,now())";
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");//cancelamos la transaccion
				die("Error al insertar la relación entre la recepcion y la orden de compra!!!\n".$error."\n".$sql);
			}

	//actualiza el status de la orden de compra
			$sql="UPDATE ec_ordenes_compra 
				SET id_estatus_oc=IF( 
							(SELECT SUM(cantidad)-SUM(cantidad_surtido) FROM ec_oc_detalle WHERE id_orden_compra=$id_orden)=0
							OR
							(SELECT SUM(cantidad)-SUM(cantidad_surtido) FROM ec_oc_detalle WHERE id_orden_compra=$id_orden) IS NULL,
							4,
							3
				)
				WHERE id_orden_compra = '{$id_orden}'";
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");//cancelamos la transaccion
				die("Error al actualizar el status de orden de compra!!!\n".$error."\n".$sql);
			}

		}
	//actualiza el status administrativo de la recepcion
		$sql = "UPDATE ec_recepcion_bodega 
					SET id_status_validacion = 2
				WHERE id_recepcion_bodega = '{$reception_id}'";
		$exc = mysql_query( $sql )or die( "Error al actualizar el status de la recepción de Bodega : " . $link->error );
	
	//actualiza el status administrativo de la recepcion
		$sql = "UPDATE ec_series_recepciones_bodega SET recepcion_actual = 0 WHERE recepcion_actual = '{$reception_id}'";
		$exc = mysql_query( $sql )or die( "Error al liberar serie de recepción de Bodega : " . $link->error );
		
		mysql_query("COMMIT");//autorizamos transacción
		die('ok|');
	}//fin de if $fl==2 (Recibir pedido)
?>