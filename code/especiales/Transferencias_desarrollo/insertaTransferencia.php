<?php
//1. Hace extract de variables POST
	extract($_POST);
//2. Declara arreglos de los detalles
	$id_producto=explode("~",$idPro);
	$presentacion=explode("~",$pres);
	$pedir=explode("~",$ped);
	$total=explode("~",$tot);
//racionar
	$raciona=explode("~",$racionar);
//3. incluye archivo %/conectMin.php%%
	require('../../../conectMin.php');//INCLUIMOS CLASE DE CONEXION

	mysql_query("BEGIN");//marcamos el inicio de la transacción
//extraemos el prefijo de la sucursal
	$sql="SELECT prefijo FROM sys_sucursales WHERE id_sucursal=$user_sucursal";
	$res=mysql_query($sql);//EJECUTAMOS CONSULTA
	if(!$res){
		$error=mysql_error();
		mysql_query("ROLLBACK");
		die("Error al consultar el prefijo de la sucursal!!!\n\n".$sql."\n\n".$error);
	}
	$row=mysql_fetch_row($res);

/*implementación de Oscar 21.08.2018*/
	$sql="SELECT id_sucursal FROM sys_sucursales WHERE acceso=1";
	$eje=mysql_query($sql)or die("Error al consultar el tipo de sistema!!!\n\n".$sql."\n\n".mysql_error());
	$tipo_sis=mysql_fetch_row($eje);
	if($tipo_sis[0]==-1){//si es un sistema en línea
		$status_transferencia=3;
	}else{
		$status_transferencia=1;//no autorizado
	}
/*fin de cambio*/	
	//4. Inserta la cabecera de la transferencia (titulo_transferencia agregado por Oscar 2021)		
		$sql="INSERT INTO ec_transferencias (id_usuario,folio,fecha,hora,id_sucursal_origen,id_sucursal_destino,observaciones,
			id_razon_social_venta,id_razon_social_compra,facturable,porc_ganancia,id_almacen_origen,id_almacen_destino,id_tipo,
			id_estado,id_sucursal, titulo_transferencia)
			VALUES('$user_id','',NOW(),NOW(),'$adic[1]','$adic[2]','$nota_transfer','-1','1','0','0','$adic[3]','$adic[4]','$adic[5]','1',
			'$sucursal_id', '{$_POST['titulo']}')";
		$eje=mysql_query($sql);
		if(!$eje){
			$error=mysql_error();
			mysql_query("ROLLBACK");
			die("Error al insertar la cabecera de la Transferencias!!!\n\n".$sql."\n\n".$error);
		}

	//captura el id de la nueva transferencia
		$nuevo=mysql_insert_id();
		
	//arma el folio
		$folio="TR".$row[0].date('Ymd').$nuevo;
		//die( 'detalle : ' . $_POST['detail'] );
//5. Inserta el detalle de la transferencia
	$details = explode( '|~|', $_POST['detail'] );
	foreach ( $details as $key => $detail ) {
		$det = explode( '~', $detail );
		$product_id = $det[0];
		$product_providers_detail = explode( '||', $det[1] );
		foreach ( $product_providers_detail as $key2 => $pp ) {
			$pp_detail = explode( '', $pp );
			if( $pp_detail[7] > 0 || $pp_detail[8] > 0 || $pp_detail[9] > 0){
				$sql = "INSERT INTO ec_transferencia_productos( id_transferencia, id_producto_or, 
					id_presentacion, cantidad_presentacion,cantidad, id_producto_de, 
					referencia_resolucion, cantidad_cajas, cantidad_paquetes, cantidad_piezas, id_proveedor_producto )
				VALUES('{$nuevo}','{$product_id}','-1','{$pp_detail[10]}','{$pp_detail[10]}',
					'{$product_id}','{$pp_detail[10]}', '{$pp_detail[7]}', '{$pp_detail[8]}', '{$pp_detail[9]}', '{$pp_detail[2]}')";
				$eje=mysql_query($sql);//EJECUTA CONSULTA
				if(!$eje){
					$error=mysql_error();
					mysql_query("ROLLBACK");
					die("Error al insertar detalle de la Transferencia!!!\n\n".$sql."\n\n".$error);
				}
			}
		}
	}

		
		//}//fin de for i

	//7. Actualiza el folio de la transferencia
		$sql="UPDATE ec_transferencias SET folio='$folio' WHERE id_transferencia=$nuevo";
		$eje=mysql_query($sql);//EJECUTAMOS CONSULTA
		if(!$eje){
			$error=mysql_error();
			mysql_query("ROLLBACK");
			die("Error al actualizar folio de Transferencia!!!\n\n".$sql."\n\n".$error);
		}

/*
Deshabilitado por Oscar 01.11.2019 para que no actualice en automático a actualizada la transferencia libre
		if($adic[5]==5){//si es transferencia de tipo libre
		//actualizamos el movimiento de detalle
			$sql="UPDATE ec_transferencia_productos SET cantidad_salida=cantidad, cantidad_salida_pres=cantidad_presentacion WHERE id_transferencia=$nuevo";	
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");
				die("Error al actualizar la salida de transferencia en el detalle!!!\n\n".$sql."\n\n".$error);
			}
		//actualizamos la transferencia al status 3 para activar el trigger
			$sql="UPDATE ec_transferencias SET id_estado=2/*$status_transferencia* WHERE id_transferencia=$nuevo";
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");
				die("Error al actualizar la salida de transferencia en el detalle!!!\n\n".$sql."\n\n".$error);
			}
		//actualizamos la transferencia al status 3 para activar el trigger
			$sql="UPDATE ec_transferencias SET id_estado=4 WHERE id_transferencia=$nuevo";
			$eje=mysql_query($sql);
			if(!$eje){
				$error=mysql_error();
				mysql_query("ROLLBACK");
				die("Error al actualizar la salida de transferencia a salida de Transferencia!!!\n\n".$sql."\n\n".$error);
			}
			mysql_query("COMMIT");//autorizamos la transacción			
		}else{//de lo contrario (si es cualquier otro tipo de transferencia)
		//regresamos ok
			mysql_query("COMMIT");//autorizamos la transacción
		}*/
		mysql_query("COMMIT");//autorizamos la transacción
		echo 'ok';
?>