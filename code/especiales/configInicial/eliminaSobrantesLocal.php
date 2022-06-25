<?php
	
/*********************************************************Proceso de BD local****************************************/
		if($id_suc!=-1){
			mysql_query("BEGIN",$local);//declaramos el inicio de transacción
			mysql_query("BEGIN",$linea);//declaramos el inicio de transacción

		//apagamos el acceso de todas las sucursales en la BD
			$sql="UPDATE sys_sucursales set acceso=0,sincronizar=0";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK");//cancelamos la transacción
				die("Error al poner todas las sucursales en 0!!!".$sql."\n\n".$error);
			}

		//asignamos la nueva sucursal 
			$sql="UPDATE sys_sucursales set acceso=1,sincronizar=0 WHERE id_sucursal=$id_suc";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK");//cancelamos la transacción
				die("Error al activar la sucursal!!!".$sql."\n\n".$error);
			}

		//eliminamos los movimientos de almacen que no sean de la sucursal
			$sql="DELETE FROM ec_movimiento_almacen WHERE id_almacen NOT IN(SELECT id_almacen FROM ec_almacen WHERE id_sucursal=$id_suc) 
			AND id_movimiento_almacen!=-1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los movimientos de almacen que no corresponden a la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		//	echo $sql."\n";
		//eliminamos las devoluciones que no son de la sucursal
			$sql="DELETE FROM ec_devolucion WHERE id_sucursal!=$id_suc AND id_devolucion!=-1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar las devoluciones que no corresponden a la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		//	echo $sql."\n";
		//eliminamos las ventas que no son de la sucursal
			$sql="DELETE FROM ec_pedidos WHERE id_sucursal!=$id_suc AND id_pedido!=-1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los pedidos que no corresponden a la sucursal_1!!!\n\n".$sql."\n\n".$error);
			}
		//eliminamos los registros de sincronización
			$sql="DELETE FROM ec_sincronizacion_registros WHERE id_sucursal!=$id_suc";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los registros de sincronización que no corresponden a la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		//eliminamos los registros de gastos
			$sql="DELETE FROM ec_gastos WHERE id_sucursal!=$id_suc";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los gastos que no son de la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		//eliminamos los registros de nomina
			$sql="DELETE FROM ec_registro_nomina WHERE id_sucursal!=$id_suc";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los registros de nomina que no son de la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		/**/
			$sql="UPDATE ec_pedidos SET id_cliente=1 
				WHERE id_pedido 
				IN(SELECT ax.id_pedido FROM(SELECT p.id_pedido from ec_pedidos p LEFT JOIN ec_clientes c ON p.id_cliente=c.id_cliente WHERE c.id_sucursal!=$id_suc
					AND c.id_sucursal!=-1 AND c.id_cliente>1)ax)";
/*
			$sql="UPDATE ec_pedidos ped 
				LEFT JOIN ec_clientes c ON ped.id_cliente=c.id_cliente
				SET ped.id_cliente=1 
				WHERE c.id_sucursal!=4 AND c.id_sucursal!=-1 AND c.id_cliente>1";
*/
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al modificar los ids de clientes que no son de la sucursal y el pedido si es de la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		/*eliminamos los registros de usuarios*/
			$sql="DELETE FROM ec_clientes WHERE id_sucursal!=$id_suc AND id_sucursal!=-1 AND id_cliente>1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los usuarios que no son de la sucursal!!!\n\n".$sql."\n\n".$error);
			}
		/*eliminamos los registros de sincronizacion*/
			$sql="DELETE FROM ec_sincronizacion_registros WHERE 1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los registros de sincronizacion!!!\n\n".$sql."\n\n".$error);
			}

		/*eliminamos los registros de sincronizacion de linea*/
			$sql="DELETE FROM ec_sincronizacion_registros WHERE id_sucursal=$id_suc AND fecha<='$fecha_rsp'";
			$eje=mysql_query($sql,$linea);
			if(!$eje){
				$error=mysql_error($linea);
				mysql_query("ROLLBACK",$linea);//cancelamos la transacción		
				die("Error al eliminar los registros de sincronizacion de linea!!!\n\n".$sql."\n\n".$error);
			}
/*///////////////////////////////
			$sql="UPDATE ec_movimiento_almacen SET id_usuario=-1
					WHERE id_movimiento_almacen
					IN(SELECT ax.id_movimiento_almacen 
						FROM(SELECT ma.id_movimiento_almacen 
							FROM ec_movimiento_almacen ma 
							LEFT JOIN sys_users u ON ma.id_usuario=u.id_usuario 
							WHERE ma.id_sucursal=$id_suc AND u.id_sucursal NOT IN(-1,$id_suc))ax)";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al actualizar el id de los usuarios que no son de la sucursal cuando el movimiento de almacen corresponde a la sucursal!!!\n\n".$sql."\n\n".$error);
			}
			
			$sql="UPDATE ec_transferencias SET id_usuario=-1
					WHERE id_transferencia
					IN(SELECT ax.id_transferencia 
						FROM(SELECT t.id_transferencia 
							FROM ec_transferencias t 
							LEFT JOIN sys_users u ON t.id_usuario=u.id_usuario 
							WHERE (t.id_sucursal=$id_suc OR t.id_sucursal_origen=$id_suc OR t.id_sucursal_destino=$id_suc) 
							AND u.id_sucursal NOT IN(-1,$id_suc))ax)";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al actualizar el id de los usuarios que no son de la sucursal cuando la trasfererencia corresponde a la sucursal!!!\n\n".$sql."\n\n".$error);
			}
	
			$sql="UPDATE sys_sucursales SET id_encargado=-1 WHERE id_sucursal NOT IN(-1,$id_suc)";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al actualizar los ids de la sucursal!!!\n\n".$sql."\n\n".$error);
			}

		/*eliminamos los registros de usuarios*
			$sql="DELETE FROM sys_users WHERE id_sucursal!=$id_suc AND id_sucursal!=-1";
			$eje=mysql_query($sql,$local);
			if(!$eje){
				$error=mysql_error($local);
				mysql_query("ROLLBACK",$local);//cancelamos la transacción		
				die("Error al eliminar los usuarios que no son de la sucursal!!!\n\n".$sql."\n\n".$error);
			}*/
		}
?>