<?php
		//separamos hora y fecha
		$aux=explode(" ", $fecha_rsp);
		$fecha=$aux[0];
		$hora=$aux[1];

	//llenamos en -1 los ids equivalentes de movimientos que ya fueron incluidos en la BD
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_movimiento_almacen SET id_equivalente=id_movimiento_almacen WHERE id_sucursal=$id_suc 
			AND id_equivalente!=-1 AND CONCAT(fecha,' ',hora)<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de movimientos de almacen en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//llenamos en -1 los ids equivalentes de devoluciones que ya fueron incluidas en la BD
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_devolucion SET id_equivalente=id_devolucion WHERE id_sucursal=$id_suc AND id_equivalente!=-1 
			AND CONCAT(fecha,' ',hora)<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de las devoluciones en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//llenamos en -1 los ids equivalentes de pedidos que ya fueron incluidos en la BD
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_pedidos SET id_equivalente=id_pedido,modificado=0 WHERE id_sucursal=$id_suc AND id_equivalente!=-1 
			AND fecha_alta<='$fecha 23:59:59'";
			//die($sql);
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de las ventas en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//llenamos en -1 los ids equivalentes de pagos que ya fueron incluidos en la BD
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE 
				ec_pedido_pagos pg
				LEFT JOIN ec_pedidos pe ON pg.id_pedido=pe.id_pedido
				SET pg.id_equivalente=id_pedido_pago
				WHERE pe.id_sucursal=$id_suc AND pg.id_equivalente!=-1 AND CONCAT(pg.fecha,' ',pg.hora)<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($eje,$conexion);
				mysql_query("ROLLBACK",$local);

				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de los pagos en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//llenamos en -1 los ids equivalentes de clientes que ya fueron incluidas en la BD
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_clientes SET id_equivalente=id_cliente WHERE id_sucursal=$id_suc AND fecha_alta<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($eje,$conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de clientes en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//igualamos los ids equivalentes de usuarios
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE sys_users SET id_equivalente=id_usuario,sincronizar=0 WHERE /*id_sucursal=$id_suc AND */fecha_alta<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				echo mysql_error($conexion)."\n";
				$error=mysql_error($eje,$conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de registros de sincronizacion en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//igualamos los ids equivalentes de gastos
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_gastos SET id_equivalente=id_gastos,sincronizar=0 WHERE id_sucursal=$id_suc AND CONCAT(fecha,' ',hora)<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($eje,$conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de registros de sincronizacion en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//igualamos los ids equivalentes de gastos
		for($i=0;$i<=1;$i++){
			$conexion=$local;
			$nom_servidor="servidor local";
			if($i==1){
				$conexion=$linea;
				$nom_servidor="servidor línea";
			}		
			$sql="UPDATE ec_registro_nomina SET id_equivalente=id_registro_nomina, sincronizar=0 WHERE id_sucursal=$id_suc AND fecha_alta<='$fecha_rsp'";
			$eje=mysql_query($sql,$conexion);
			if(!$eje){
				$error=mysql_error($eje,$conexion);
				mysql_query("ROLLBACK",$local);
				mysql_query("ROLLBACK",$linea);
				die("Error al actualizar los id´s equivalentes de registros de sincronizacion en $nom_servidor!!!\n\n".$sql."\n\n".$error);
			}
		}//fin de for $i

	//descartamos las transferencias en la BD local
		$sql="DELETE FROM ec_transferencias WHERE (id_sucursal_origen!=$id_suc AND id_sucursal_destino!=$id_suc) AND id_transferencia!=-1";
		$eje=mysql_query($sql,$local);
		if(!$eje){
			$error=mysql_error($local);
			mysql_query("ROLLBACK",$local);
			mysql_query("ROLLBACK",$linea);
			die("Error al eliminar los registros de transferencias que no corresponden a la sucursal en $nom_servidor!!!\n\n".$sql."\n\n".$error);
		}
	//actualizamos los id´s globales de transferencias de la sucursal cuando no tienen id_equivalente
		$sql="UPDATE ec_transferencias SET id_global=id_transferencia WHERE (id_sucursal_origen=$id_suc OR id_sucursal_destino=$id_suc) AND id_global=0";
		$eje=mysql_query($sql,$local);
		if(!$eje){
			$error=mysql_error($local);
			mysql_query("ROLLBACK",$local);
			mysql_query("ROLLBACK",$linea);
			die("Error al actualizar los id´s equivalentes de registros de transferencias que son de la sucursal en $nom_servidor!!!\n\n".$sql."\n\n".$error);
		}
		
	//actualizamos el id equivalente de las sesiones de caja
		$sql="UPDATE ec_sesion_caja SET id_equivalente=id_sesion_caja,sincronizar=0";
		$eje=mysql_query($sql,$local);
		if(!$eje){
			$error=mysql_error($local);
			mysql_query("ROLLBACK",$local);
			mysql_query("ROLLBACK",$linea);			
		}

	//actualizamos el estatus del respaldo
		$sql="UPDATE sys_respaldos SET realizado=1";
		$eje=mysql_query($sql,$local);
		if(!$eje){
			$error=mysql_error($local);
			mysql_query("ROLLBACK",$local);
			mysql_query("ROLLBACK",$linea);
			die("Error al actualizar el registro de respaldo!!!\n\n".$sql."\n\n".$error);
		}
	
?>