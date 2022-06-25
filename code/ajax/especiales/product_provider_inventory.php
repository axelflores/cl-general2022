<?php
	include("../../../conectMin.php");
	
	extract($_GET);
    
    if(!isset($sucur))
        $sucur=-1;

	/*if($tipo==2){
		$sql="SELECT 
				ppi.id_producto,
				p.orden_lista,
				provProd.clave_proveedor,
				ppi.nombre,
				ppi.almacen,
				ppi.inventario,
				'ver'
			FROM ec_productos p
			LEFT JOIN product_provider_inventory ppi ON ppi.id_producto = p.id_productos
			/*LEFT JOIN ec_almacen alm ON ppi.id_almacen = alm.id_almacen*
			LEFT JOIN ec_proveedor_producto provProd ON provProd.id_proveedor_producto = ppi.id_proveedor_producto
			LEFT JOIN sys_sucursales_producto sp ON sp.id_producto = p.id_productos
			WHERE sp.id_sucursal = '{$user_sucursal}'
			AND sp.estado_suc = 1
			AND ppi.id_sucursal = '{$user_sucursal}'";

		//ampliamos las coincidencias de búsqueda
			if( $valor!='' && $valor!=null ){	
				$sql.=" AND(";
				$arr=explode(" ", $valor);
				for( $i=0; $i < sizeof( $arr ); $i++ ){
					if( $arr[$i] != '' && $arr[$i] != null ){
						if( $i > 0 ){
							$sql .= " AND ";
						}
						$sql .= "ppi.nombre LIKE '%".$arr[$i]."%'";
					}
				}//fin de for $i
				$sql.=")";
			}
			$sql .= ( $sucur != -1 ? " AND ppi.id_almacen = '{$sucur}'" : "" ) .
					(isset($cantmayora) && strlen($cantmayora)>0 ? " AND cantidad > '{$cantmayora}' " : "")." ".
		  			(isset($cantmenora) && strlen($cantmenora)>0 ? " AND cantidad < '{$cantmenora}' " : "")." ";
			$sql.=" GROUP BY ppi.id
				ORDER BY p.orden_lista, ppi.nombre ASC";//)axORDER BY {$orderGRC} {$sentidoOr}
//die($sql);

	}else{*/
		$sql="SELECT
				ax.id,
				ax.orden_lista,
				ax.clave_proveedor,
				ax.nombre,
				ax.almacen,
				ax.cantidad,
				'ver'
			FROM(
				SELECT 
					ppi.id_producto AS id,
					ppi.orden_lista,
					provProd.clave_proveedor,
					ppi.nombre,
					ppi.almacen,
					ppi.inventario AS cantidad
				FROM ec_productos p
				LEFT JOIN product_provider_inventory ppi ON ppi.id_producto = p.id_productos
				/*LEFT JOIN ec_almacen alm ON ppi.id_almacen = alm.id_almacen*/
				LEFT JOIN ec_proveedor_producto provProd ON provProd.id_proveedor_producto = ppi.id_proveedor_producto
				LEFT JOIN sys_sucursales_producto sp ON sp.id_producto = p.id_productos
				WHERE sp.id_sucursal = '{$user_sucursal}'
				AND sp.estado_suc = 1
				AND ppi.id_sucursal = '{$user_sucursal}'"
				. ( $sucur != -1 ? " AND ppi.id_almacen = '{$sucur}'" : "" )
				. (isset($nombre) && strlen($nombre)>0 ? " AND p.nombre LIKE '%{$nombre}%' " : "")." ";
			//ampliamos las coincidencias de búsqueda
			if( $valor!='' && $valor!=null ){	
				$sql.=" AND(";
				$arr=explode(" ", $valor);
				for( $i=0; $i < sizeof( $arr ); $i++ ){
					if( $arr[$i] != '' && $arr[$i] != null ){
						if( $i > 0 ){
							$sql .= " AND ";
						}
						$sql .= "ppi.nombre LIKE '%".$arr[$i]."%'";
					}
				}//fin de for $i
				$sql.=")";
			}

			$sql .=	"GROUP BY ppi.id
				ORDER BY p.orden_lista, ppi.nombre ASC
			)ax 
			WHERE 1 ".
		  (isset($cantmayora) && strlen($cantmayora)>0 ? " AND cantidad > '{$cantmayora}' " : "")." ".
		  (isset($cantmenora) && strlen($cantmenora)>0 ? " AND cantidad < '{$cantmenora}' " : "")." 
		  	ORDER BY {$orderGRC} {$sentidoOr}";/*
			ORDER BY ax.orden_lista, ax.nombre ASC*/
		/*$sql="SELECT
		  id,
		  orden_lista,
		  clave,
		  nombre,
		  familia,
		  cantidad,
		  'ver'
		  FROM(
			SELECT
			p.id_productos AS id,
			p.nombre,
			p.orden_lista,
			p.clave,
			a.nombre AS familia,
			SUM(
				IF(
					md.id_movimiento IS NULL,
					0,
					md.cantidad*tm.afecta
				)
			) AS cantidad
			FROM ec_productos p
			JOIN ec_movimiento_detalle md ON p.id_productos = md.id_producto
			JOIN ec_movimiento_almacen ma ON md.id_movimiento = ma.id_movimiento_almacen AND ma.id_sucursal = '{$user_sucursal}'
			JOIN ec_almacen a ON ma.id_almacen = a.id_almacen
			JOIN ec_tipos_movimiento tm ON ma.id_tipo_movimiento = tm.id_tipo_movimiento
			WHERE p.id_productos > 0 ".
			(isset($nombre) && strlen($nombre)>0 ? "AND p.nombre LIKE '%{$nombre}%' " : "")." ".
			"GROUP BY p.id_productos, a.id_almacen
		  )aux
		  WHERE 1	".
		  (isset($cantmayora) && strlen($cantmayora)>0 ? " AND cantidad > '{$cantmayora}' " : "")." ".
		  (isset($cantmenora) && strlen($cantmenora)>0 ? " AND cantidad < '{$cantmenora}' " : "")." 
		  ORDER BY {$orderGRC} {$sentidoOr}";*/
	/*}*/

	if( isset( $ini ) && isset( $fin ) ){
		//Conseguimos el número de datos real
		$resultado=mysql_query($sql) or die(mysql_error() . " Consulta:\n$sql\n\nDescripcion:\n".mysql_error());
		$numtotal=mysql_num_rows($resultado);
		//Añadimos el limit para el paginador
		$sql.=" LIMIT $ini, $fin";
	}	  
	
	//Buscamos los datos de la consulta final
	$res=mysql_query($sql) or die(mysql_error() . " Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	
	$num=mysql_num_rows($res);		
	
	echo "exito";
	for($i=0;$i<$num;$i++){
		$row=mysql_fetch_row($res);
		echo "|";
		for($j=0;$j<sizeof($row);$j++){	
			if($j > 0)
				echo "~";
			if($j == 0)
				echo base64_encode($row[$j]);
			else	
				echo $row[$j];
		}	
	}
	
	
	//Enviamos en el ultimo dato los datos del listado, numero de datos y datos que se muestran
	if(isset($ini) && isset($fin))
		echo "|$numtotal~$num";
	
?>