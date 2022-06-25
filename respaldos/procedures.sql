/*

NOTA: no quitar el palito que aparece despues de eliminar cada procedure y despues de insertarlo ya que el el separador de las instrucciones

**/
DROP PROCEDURE IF EXISTS agrupaVentas|
DELIMITER $$
CREATE PROCEDURE agrupaVentas(IN id_tipo_agrupacion INTEGER(11),IN fecha_agrupacion VARCHAR(10))
BEGIN
	DECLARE cont_sucursales INTEGER(11);/*contador de sucursales*/
	DECLARE tope_sucursales INTEGER(11);/*tope de sucursales*/
	DECLARE verifica_sucursal INTEGER(11);/*tope de sucursales*/
	DECLARE id_cabecera_pedido INTEGER(11);/*auxiliar para id de cabecera de nueva venta agrupada*/
	DECLARE id_cabecera_devolucion_interna INTEGER(11);/*auxiliar para id de cabecera de nueva devolucion interna agrupada*/
	DECLARE id_cabecera_devolucion_externa INTEGER(11);/*auxiliar para id de cabecera de nueva devolucion externa agrupada*/
	DECLARE verif_ventas INTEGER(11);/*variable para verificar que haya ventas*/
	DECLARE verif_dev_int INTEGER(11);/*variable para verficar que haya devoluciones internas*/
	DECLARE verif_dev_ext INTEGER(11);/*variable para verficar que haya devoluciones externas*/
	DECLARE contador_detalles_dev_int INTEGER(11);
	DECLARE contador_detalles_dev_ext INTEGER(11);
	DECLARE contador_pagos_dev_int INTEGER(11);
	DECLARE contador_pagos_dev_ext INTEGER(11);
	DECLARE fecha_agrupacion_auxiliar VARCHAR(10);

START TRANSACTION;
	IF(id_tipo_agrupacion=3)/*por ano*/
	THEN
		SELECT DATE_FORMAT(max(fecha_alta),'%Y-%m-%d') INTO fecha_agrupacion_auxiliar FROM ec_pedidos WHERE fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%');
	END IF;

	IF(id_tipo_agrupacion=4)/*por todos los anteriores*/
	THEN
		SELECT date_add(CURRENT_DATE(), INTERVAL (fecha_agrupacion*-1) DAY) INTO fecha_agrupacion;
	END IF;

	SELECT MAX(id_sucursal) INTO tope_sucursales FROM sys_sucursales WHERE id_sucursal>0;
	SET cont_sucursales=1;

/*recorremos con while*/
	WHILE cont_sucursales<=tope_sucursales DO
		
		IF(id_tipo_agrupacion=2)/*por día*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_pedidos SET id_status_agrupacion=1 WHERE id_sucursal=cont_sucursales AND id_pedido!=-1 AND id_equivalente!=0
			AND id_status_agrupacion=-1 AND pagado=1 AND fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%');
		END IF;

		IF(id_tipo_agrupacion=3)/*por ano*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_pedidos SET id_status_agrupacion=1 WHERE id_sucursal=cont_sucursales AND id_pedido!=-1 AND id_equivalente!=0
			AND id_status_agrupacion=2 AND pagado=1 AND fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%');
		END IF;

		IF(id_tipo_agrupacion=4)/*por historico*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_pedidos SET id_status_agrupacion=1 WHERE id_sucursal=cont_sucursales AND id_pedido!=-1 AND id_equivalente!=0
			AND id_status_agrupacion IN(3,4) AND pagado=1 AND fecha_alta<=CONCAT(fecha_agrupacion,' 23:59:59');
		END IF;	
	/*reseteamos variables de ids nuevos*/
		SET id_cabecera_pedido=0,id_cabecera_devolucion_interna=0,id_cabecera_devolucion_externa=0,verif_dev_int=0,verif_dev_ext=0,contador_detalles_dev_int=0,
		contador_detalles_dev_ext=0,contador_pagos_dev_int=0,contador_pagos_dev_ext=0;
	/*verificamos que la sucursal exista*/
		SELECT COUNT(id_sucursal) into verifica_sucursal FROM sys_sucursales WHERE id_sucursal=cont_sucursales;
	/*verificamos que haya ventas para agrupar*/
		SELECT COUNT(id_pedido) INTO verif_ventas 
		FROM ec_pedidos 
		WHERE id_sucursal=cont_sucursales 
		AND id_status_agrupacion=1;

	/*comenzamos el proceso de agrupacion*/
		IF(verifica_sucursal=1 AND verif_ventas>0)
		THEN
		/*ponemos en status de agrupacion temporal las ventas que pertenencen al día y sucursal*
			UPDATE ec_pedidos SET id_status_agrupacion=1 WHERE fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%') AND id_sucursal=cont_sucursales AND id_equivalente!=0;
*/
			INSERT INTO ec_pedidos 
				SELECT
				/*1*/null,
				/*2*/'agrupacion',
				/*3*/'agrupacion',
				/*4*/'agrupacion',
				/*5*/'agrupacion',
				/*6*/1,
				/*7*/2,
				/*8*/1,
				/*9*/IF(id_tipo_agrupacion=3,CONCAT(fecha_agrupacion_auxiliar,' ',current_time),CONCAT(fecha_agrupacion,' ',current_time)),
				/*10*/null,
				/*11*/-1,
				/*12*/null,
				/*13*/-1,
				/*14*/SUM(subtotal),
				/*15*/0,
				/*16*/0,
				/*17*/SUM(total),
				/*18*/null,
				/*19*/1,
				/*20*/0,
				/*21*/0,
				/*22*/cont_sucursales,
				/*23*/1,
				/*24*/0,
				/*25*/0,
				/*26*/1,
				/*27*/SUM(descuento),
				/*28*/null,
				/*29*/null,
				/*30*/'-',
				/*31*/'-',
				/*32*/-1,
				/*33*/0,
				/*34*/'0000-00-00 00:00:00',
				/*35*/NOW(),
				/*36*/0,
				/*37*/id_tipo_agrupacion,
				/*38*/id_cajero,
				/*39*/id_devoluciones
				FROM ec_pedidos
				WHERE id_sucursal=cont_sucursales
				/*AND fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')*/
				AND id_status_agrupacion=1
/*				AND id_equivalente!=0*/
				GROUP BY id_sucursal;
		/*obtenemos el id insertado en la cabecera de pedido*/
			SELECT LAST_INSERT_ID() INTO id_cabecera_pedido;
		/*agrupamos el detalle*/
			INSERT INTO ec_pedidos_detalle
				SELECT
					/*1*/null,
					/*2*/id_cabecera_pedido,
					/*3*/pd.id_producto,
					/*4*/SUM(pd.cantidad),
					/*5*/pd.precio,
					/*6*/SUM(pd.monto),
					/*7*/0,
					/*8*/0,
					/*9*/0,
					/*10*/SUM(pd.descuento),
					/*11*/0,
					/*12*/pd.es_externo,
					/*13*/pd.id_precio
				FROM ec_pedidos_detalle pd
				LEFT JOIN ec_pedidos ped ON pd.id_pedido=ped.id_pedido
				WHERE ped.id_status_agrupacion=1
				AND ped.id_sucursal=cont_sucursales
				/*AND ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')*/
				GROUP BY pd.id_producto,pd.es_externo;

		/*agrupamos los pagos*/
			INSERT INTO ec_pedido_pagos
				SELECT 
					/*1*/NULL,
					/*2*/-1,
					/*3*/id_cabecera_pedido,
					/*4*/1,
					/*5*/IF(id_tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion),
					/*6*/now(),
					/*7*/SUM(pp.monto),
					/*8*/'',
					/*9*/1,
					/*10*/1,
					/*11*/-1,
					/*12*/-1,
					/*13*/0,
					/*14*/pp.es_externo,
					/*15*/pp.id_cajero
				FROM ec_pedido_pagos pp
				LEFT JOIN ec_pedidos ped ON pp.id_pedido=ped.id_pedido
				WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
				AND */ped.id_sucursal=cont_sucursales
				AND ped.id_status_agrupacion=1
				GROUP BY ped.id_sucursal,pp.es_externo;

		/*verificamos si hay devolucones externas*/
					/*verificamos si hay devolucones internas*/
			SELECT COUNT(dev.id_devolucion) INTO verif_dev_ext
			FROM /*ec_devolucion_pagos dp
			LEFT JOIN */ec_devolucion dev /*ON dp.id_devolucion=dev.id_devolucion*/
			LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
			WHERE dev.es_externo=1
			AND ped.id_sucursal=cont_sucursales
			/*AND ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')*/
			AND ped.id_status_agrupacion=1;


			IF(verif_dev_ext>0)
			THEN
			/*agrupamos las devoluciones internas*/
				INSERT INTO ec_devolucion 
					SELECT
						null,/*1*/
						-1,/*2*/
						1,/*3*/
						cont_sucursales,/*4*/
						IF(id_tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion),/*5*/
						now(),/*6*/
						id_cabecera_pedido,/*7*/
						'AGRUP',/*8*/
						dev.es_externo,/*9*/
						dev.status,/*10*/
						'AGRUPACION',/*11*/
						dev.tipo_sistema,/*12*/
						id_tipo_agrupacion/*13*/
					FROM ec_devolucion dev
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=1
					GROUP BY dev.id_sucursal;

			/*obtenemos el id insertado en la cabecera de la devolucion*/
				SELECT LAST_INSERT_ID() INTO id_cabecera_devolucion_externa;

		/*verificamos si hay detalles de devolucion internos*/
			SELECT count(dd.id_devolucion_detalle) INTO contador_detalles_dev_ext
				FROM ec_devolucion_detalle dd
					LEFT JOIN ec_devolucion dev ON dd.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=1;

			IF(contador_detalles_dev_ext>0)
			THEN
			/*agrupamos detalle de las devoluciones internas*/
				INSERT INTO ec_devolucion_detalle 
					SELECT 
						null,
						id_cabecera_devolucion_externa,
						dd.id_producto,
						SUM(dd.cantidad)
					FROM ec_devolucion_detalle dd
					LEFT JOIN ec_devolucion dev ON dd.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=1
					GROUP BY dd.id_producto;
			END IF;

		/*verificamos sy hay pagos de devolucion externos*/
			SELECT COUNT(dp.id_devolucion_pago) INTO contador_pagos_dev_ext
			FROM ec_devolucion_pagos dp
					LEFT JOIN ec_devolucion dev ON dp.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dp.es_externo=1;
			IF(contador_pagos_dev_ext>0)
			THEN
			/*agrupammos  pago de devoluciones externas*/
				INSERT INTO ec_devolucion_pagos
					SELECT 
						null,
						id_cabecera_devolucion_externa,
						1,
						SUM(dp.monto),
						'',
						dp.es_externo,
						IF(id_tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion),
						now(),
						dp.id_cajero
					FROM ec_devolucion_pagos dp
					LEFT JOIN ec_devolucion dev ON dp.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dp.es_externo=1;
			END  IF;
			
		END IF;/*fin de si hay devoluciones internas*/
/************************Devoluciones inyternas*************************/

		/*verificamos si hay devolucones internas*/
			SELECT COUNT(dev.id_devolucion) INTO verif_dev_int 
			FROM /*ec_devolucion_pagos dp
			LEFT JOIN */ec_devolucion dev /*ON dp.id_devolucion=dev.id_devolucion*/
			LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
			WHERE dev.es_externo=0
			AND ped.id_sucursal=cont_sucursales
			/*AND ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')*/
			AND ped.id_status_agrupacion=1;

			IF(verif_dev_int>0)
			THEN
			/*agrupamos las devoluciones internas*/
				INSERT INTO ec_devolucion 
					SELECT
						null,/*1*/
						-1,/*2*/
						1,/*3*/
						cont_sucursales,/*4*/
						IF(id_tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion),/*5*/
						now(),/*6*/
						id_cabecera_pedido,/*7*/
						'AGRUP',/*8*/
						dev.es_externo,/*9*/
						dev.status,/*10*/
						'AGRUPACION',/*11*/
						dev.tipo_sistema,/*12*/
						id_tipo_agrupacion/*13*/
					FROM ec_devolucion dev
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=0
					GROUP BY dev.id_sucursal;

			/*obtenemos el id insertado en la cabecera de la devolucion*/
				SELECT LAST_INSERT_ID() INTO id_cabecera_devolucion_interna;

		/*verificamos si hay detalles de devolucion internos*/
			SELECT count(dd.id_devolucion_detalle) INTO contador_detalles_dev_int
				FROM ec_devolucion_detalle dd
					LEFT JOIN ec_devolucion dev ON dd.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=0;

			IF(contador_detalles_dev_int>0)
			THEN
			/*agrupamos detalle de las devoluciones internas*/
				INSERT INTO ec_devolucion_detalle 
					SELECT 
						null,
						id_cabecera_devolucion_interna,
						dd.id_producto,
						SUM(dd.cantidad)
					FROM ec_devolucion_detalle dd
					LEFT JOIN ec_devolucion dev ON dd.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dev.es_externo=0
					GROUP BY dd.id_producto;
			END IF;


		/*verificamos si hay pagos de devolucion externos*/
			SELECT COUNT(dp.id_devolucion_pago) INTO contador_pagos_dev_int
			FROM ec_devolucion_pagos dp
					LEFT JOIN ec_devolucion dev ON dp.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dp.es_externo=0;
			IF(contador_pagos_dev_int>0)
			THEN
			/*agrupammos  pago de devoluciones externas*/
				INSERT INTO ec_devolucion_pagos
					SELECT 
						null,
						id_cabecera_devolucion_interna,
						1,
						SUM(dp.monto),
						'',
						dp.es_externo,
						IF(id_tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion),
						now(),
						dp.id_cajero
					FROM ec_devolucion_pagos dp
					LEFT JOIN ec_devolucion dev ON dp.id_devolucion=dev.id_devolucion
					LEFT JOIN ec_pedidos ped ON dev.id_pedido=ped.id_pedido
					WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
					AND */ped.id_sucursal=cont_sucursales
					AND ped.id_status_agrupacion=1
					AND dp.es_externo=0;
			END IF;
		END IF;/*fin de si hay devoluciones internas*/

			IF(id_tipo_agrupacion=4)
			THEN
			/*eliminamos las devoluciones que pertenecen a las ventas agrupadas*/
				DELETE dev.*
				FROM ec_devolucion dev
				LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
				WHERE /*ped.fecha_alta<=CONCAT(fecha_agrupacion,' 23:59:59')
				AND */ped.id_sucursal=cont_sucursales
				AND ped.id_status_agrupacion=1;
			/*eliminamos las ventas agrupadas*/
				DELETE FROM ec_pedidos 
				WHERE /*fecha_alta<=CONCAT(fecha_agrupacion,' 23:59:59')
				AND */id_sucursal=cont_sucursales
				AND id_status_agrupacion=1;
			ELSE
			/*eliminamos las devoluciones que pertenecen a las ventas agrupadas*/
				DELETE dev.*
				FROM ec_devolucion dev
				LEFT JOIN ec_pedidos ped ON ped.id_pedido=dev.id_pedido
				WHERE /*ped.fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
				AND */ped.id_sucursal=cont_sucursales
				AND ped.id_status_agrupacion=1;
			/*eliminamos las ventas agrupadas*/
				DELETE FROM ec_pedidos 
				WHERE /*fecha_alta LIKE CONCAT('%',fecha_agrupacion,'%')
				AND */id_sucursal=cont_sucursales
				AND id_status_agrupacion=1;

			END IF;

		END IF;
		SET cont_sucursales=cont_sucursales+1;
	END WHILE;
COMMIT;
END $$
|


DROP PROCEDURE IF EXISTS eliminaRegistrosMantenimiento|
DELIMITER $$
CREATE PROCEDURE eliminaRegistrosMantenimiento(IN fecha_eliminar VARCHAR(10))
	
BEGIN

	SELECT date_add(CURRENT_DATE(), INTERVAL (fecha_eliminar*-1) DAY) INTO fecha_eliminar;

/*Eliminamos movimientos_temporales*/
	DELETE FROM ec_movimiento_temporal WHERE fecha<=fecha_eliminar;
/*Eliminamos movimientos_temporales*/
	DELETE FROM ec_pedidos_back WHERE fecha_alta<=CONCAT(fecha_eliminar,' 23:59:59');
/*Eliminamos movimientos_temporales*/
	DELETE FROM ec_registro_nomina WHERE fecha<=fecha_eliminar;
/**/
	DELETE FROM ec_sincronizacion_registros WHERE fecha<=CONCAT(fecha_eliminar,' 23:59:59');
/**/
	DELETE FROM ec_temporal_exhibicion WHERE fecha_alta<=CONCAT(fecha_eliminar,' 23:59:59');
/**/
	DELETE t.* 
	FROM ec_transferencias t 
	LEFT JOIN ec_movimiento_almacen ma ON ma.id_transferencia=t.id_transferencia
	WHERE ma.id_movimiento_almacen IS NULL
	AND t.id_transferencia!=-1
	AND t.fecha<=fecha_eliminar;
/**
	DELETE FROM sys_archivos_descarga WHERE fecha<=fecha_eliminar;
*/

END $$
|

DROP PROCEDURE IF EXISTS parametrosAgrupaMovimientosAlmacen| 
DELIMITER $$
CREATE PROCEDURE parametrosAgrupaMovimientosAlmacen(IN tipo_agrupacion_movimientos INTEGER(1), IN minimo_dias INTEGER(11))
BEGIN
-- Declaramos las variables necesarias
-- La primera para saber cuando se detendra la consulta
	DECLARE done INT DEFAULT FALSE;
-- Esta variable son las que recibiran los elementos necesarios
	DECLARE fecha_tmp DATE;
-- La variable que declararemos para concatenar los resultados
	DECLARE fecha_base VARCHAR(10);
/*sacamos la fecha restando los días*/
/*Recorre se llma la variable CURSOR que recorre en base a la consulta*/
	DECLARE recorre CURSOR FOR
		SELECT DATE_FORMAT(fecha,'%Y-%m-%d') FROM ec_movimiento_almacen 
		WHERE fecha<=(SELECT date_add(CURRENT_DATE(), INTERVAL (minimo_dias*-1) DAY))
		AND id_movimiento_almacen!=-1/*'2019-05-15'*/
		GROUP BY fecha;    
-- Se declara un manejador para saber cuando se tiene que detener
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
-- Se abre el cursor
		OPEN recorre;
		SET fecha_tmp= "";/*reseteamos la fecha*/
		loop_recorre: LOOP  	
			-- Fetch lo utilizamos para leer cada uno de los registros
				FETCH recorre INTO fecha_tmp; 
				/*SET fecha_tmp=DATE_FORMAT(fecha_tmp,'%Y-%m-%d');*/     
			-- If que permite salir del ciclo
			IF done THEN
				LEAVE loop_recorre;
			END IF;
			CALL agrupaMovimientosAlmacen(2,fecha_tmp);
		/*INSERT INTO prueba_dias_movimientos VALUES(null,fecha_tmp);*
			INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion_movimientos,
				(SELECT COUNT(*) FROM ec_movimiento_almacen WHERE id_movimiento_almacen!=-1 AND id_equivalente!=0 AND fecha=fecha_tmp),
				(SELECT max(fecha) FROM ec_movimiento_almacen WHERE fecha like CONCAT('%',fecha_tmp,'%')),now());*/
		END LOOP;
-- cerramos el cursor
	CLOSE recorre;   
END $$
|


DROP PROCEDURE IF EXISTS parametrosAgrupaMovimientosAlmacenPorAno| 
DELIMITER $$
CREATE PROCEDURE parametrosAgrupaMovimientosAlmacenPorAno(IN tipo_agrupacion_movimientos INTEGER(1), IN minimo_dias INTEGER(11))
BEGIN
-- Declaramos las variables necesarias
-- La primera para saber cuando se detendra la consulta
	DECLARE done INT DEFAULT FALSE;
-- Esta variable son las que recibiran los elementos necesarios
	DECLARE fecha_tmp VARCHAR(10);
-- La variable que declararemos para concatenar los resultados
	DECLARE fecha_base VARCHAR(10);
/*sacamos la fecha restando los días**/
/*Recorre se llma la variable CURSOR que recorre en base a la consulta*/
	DECLARE recorre CURSOR FOR
		SELECT DATE_FORMAT(fecha,'%Y') FROM ec_movimiento_almacen 
		WHERE fecha<=(SELECT date_add(CURRENT_DATE(), INTERVAL (minimo_dias*-1) DAY))
		AND id_movimiento_almacen!=-1/*'2019-05-15'*/
		GROUP BY DATE_FORMAT(fecha,'%Y');    
-- Se declara un manejador para saber cuando se tiene que detener
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
-- Se abre el cursor
		OPEN recorre;
		SET fecha_tmp= "";/*reseteamos la fecha*/
		loop_recorre: LOOP  	
			-- Fetch lo utilizamos para leer cada uno de los registros
				FETCH recorre INTO fecha_tmp;    
			-- If que permite salir del ciclo
			IF done THEN
				LEAVE loop_recorre;
			END IF;
			CALL agrupaMovimientosAlmacen(3,fecha_tmp);
		/*INSERT INTO prueba_dias_movimientos VALUES(null,fecha_tmp);*
			INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion_movimientos,
				(SELECT COUNT(*) FROM ec_movimiento_almacen WHERE id_movimiento_almacen!=-1 AND id_equivalente!=0 AND status_agrupacion=2 AND fecha=fecha_tmp),
				(SELECT max(fecha) FROM ec_movimiento_almacen WHERE fecha like CONCAT('%',fecha_tmp,'%')),now());*/
		END LOOP;
-- cerramos el cursor
	CLOSE recorre;  
END $$
|

DROP PROCEDURE IF EXISTS parametrosAgrupaVentas|
DELIMITER $$
CREATE PROCEDURE parametrosAgrupaVentas(IN tipo_agrupacion_ventas INTEGER(1), IN minimo_dias INTEGER(11))
BEGIN
-- Declaramos las variables necesarias
-- La primera para saber cuando se detendra la consulta
	DECLARE done INT DEFAULT FALSE;
-- Esta variable son las que recibiran los elementos necesarios
	DECLARE fecha_tmp DATE;
-- La variable que declararemos para concatenar los resultados
	DECLARE fecha_base VARCHAR(10);
/*sacamos la fecha restando los días*/
/*Recorre se llma la variable CURSOR que recorre en base a la consulta*/
	DECLARE recorre CURSOR FOR
		SELECT DATE_FORMAT(fecha_alta,'%Y-%m-%d') FROM ec_pedidos
		WHERE fecha_alta<=(SELECT date_add(CURRENT_DATE(), INTERVAL (minimo_dias*-1) DAY))
		AND id_pedido!=-1
		GROUP BY DATE_FORMAT(fecha_alta,'%Y-%m-%d');
-- Se declara un manejador para saber cuando se tiene que detener
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
-- Se abre el cursor
		OPEN recorre;
		SET fecha_tmp= "";/*reseteamos la fecha*/
		loop_recorre: LOOP  	
			-- Fetch lo utilizamos para leer cada uno de los registros
				FETCH recorre INTO fecha_tmp; 
				/*SET fecha_tmp=DATE_FORMAT(fecha_tmp,'%Y-%m-%d');*/     
			-- If que permite salir del ciclo
			IF done THEN
				LEAVE loop_recorre;
			END IF;
			CALL agrupaVentas(2,fecha_tmp);
		/*INSERT INTO prueba_dias_movimientos VALUES(null,fecha_tmp);*
			INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion_ventas,
				(SELECT COUNT(*) FROM ec_movimiento_almacen WHERE id_movimiento_almacen!=-1 AND id_equivalente!=0 AND fecha=fecha_tmp),
				(SELECT max(fecha) FROM ec_movimiento_almacen WHERE fecha like CONCAT('%',fecha_tmp,'%')),now());*/
		END LOOP;
-- cerramos el cursor
	CLOSE recorre;   
END $$
|

DROP PROCEDURE IF EXISTS parametrosAgrupaVentasPorAno|
DELIMITER $$
CREATE PROCEDURE parametrosAgrupaVentasPorAno(IN tipo_agrupacion_ventas INTEGER(1), IN minimo_dias INTEGER(11))
BEGIN
-- Declaramos las variables necesarias
-- La primera para saber cuando se detendra la consulta
	DECLARE done INT DEFAULT FALSE;
-- Esta variable son las que recibiran los elementos necesarios
	DECLARE fecha_tmp VARCHAR(10);
-- La variable que declararemos para concatenar los resultados
	DECLARE fecha_base VARCHAR(10);
/*sacamos la fecha restando los días*/
/*Recorre se llma la variable CURSOR que recorre en base a la consulta*/
	DECLARE recorre CURSOR FOR
		SELECT DATE_FORMAT(fecha_alta,'%Y') FROM ec_pedidos
		WHERE fecha_alta<=(SELECT date_add(CURRENT_DATE(), INTERVAL (minimo_dias*-1) DAY))
		AND id_pedido!=-1
		GROUP BY DATE_FORMAT(fecha_alta,'%Y');
-- Se declara un manejador para saber cuando se tiene que detener
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
-- Se abre el cursor
		OPEN recorre;
		SET fecha_tmp= "";/*reseteamos la fecha*/
		loop_recorre: LOOP  	
			-- Fetch lo utilizamos para leer cada uno de los registros
				FETCH recorre INTO fecha_tmp; 
				/*SET fecha_tmp=DATE_FORMAT(fecha_tmp,'%Y-%m-%d');*/     
			-- If que permite salir del ciclo
			IF done THEN
				LEAVE loop_recorre;
			END IF;
			CALL agrupaVentas(3,fecha_tmp);
		/*INSERT INTO prueba_dias_movimientos VALUES(null,fecha_tmp);*/
			INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion_ventas,
				(SELECT COUNT(*) FROM ec_pedidos WHERE id_pedido!=-1 AND id_equivalente!=0 AND fecha_alta=fecha_tmp),
				(SELECT DATE_FORMAT(max(fecha_alta),'%Y-%m-%d') FROM ec_pedidos WHERE fecha_alta like CONCAT('%',fecha_tmp,'%')),now());
		END LOOP;
-- cerramos el cursor
	CLOSE recorre;   
END $$
|

DROP PROCEDURE IF EXISTS agrupaMovimientosAlmacen|
DELIMITER $$
CREATE PROCEDURE agrupaMovimientosAlmacen(IN tipo_agrupacion INTEGER(1),IN fecha_agrupacion VARCHAR(10))
BEGIN
/*declaramos variables*/
	DECLARE contador INTEGER(11);
	DECLARE tope INTEGER(11);
	DECLARE num_almacenes INTEGER(11);
	DECLARE tope_almacenes INTEGER(11);
	DECLARE movimiento_insertado BIGINT;/*id de movimiento_insertado*/
	DECLARE id_sucursal_tmp INTEGER(11);/*id de movimiento_insertado*/
	DECLARE id_almacen_tmp INTEGER(11);/*id de movimiento_insertado*/
	DECLARE verif_almacen INTEGER(11);/*id de movimiento_insertado*/
	DECLARE verif_almacen_detalle INTEGER(11);/*id de movimiento_insertado*/
	DECLARE fecha_agrupacion_auxiliar VARCHAR(10);
/*
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
	    ROLLBACK;
	END;
*/
START TRANSACTION;
	IF(tipo_agrupacion=3)/*por ano*/
	THEN
		SELECT max(fecha) INTO fecha_agrupacion_auxiliar FROM ec_movimiento_almacen WHERE fecha LIKE CONCAT('%',fecha_agrupacion,'%');
	END IF;

	IF(tipo_agrupacion=4)/*por todos los anteriores*/
	THEN
		SELECT date_add(CURRENT_DATE(), INTERVAL (fecha_agrupacion*-1) DAY) INTO fecha_agrupacion;
	/*	SELECT add(fecha) INTO fecha_agrupacion_auxiliar FROM ec_movimiento_almacen WHERE fecha LIKE CONCAT('%',fecha_agrupacion,'%');*/
	END IF;

/*extraemos el numero de tipos de movimiento*/
	SELECT COUNT(*) INTO tope FROM ec_tipos_movimiento;

/*extraemos el id maximo de almacenes*/
	SELECT MAX(id_almacen) INTO tope_almacenes FROM ec_almacen WHERE id_almacen>0;

/*inicializamos el contador en ceros*/
	SET contador=1;

/*corremos while de tipos de movimiento*/
	WHILE contador<=tope DO

		IF(tipo_agrupacion=2)/*por día*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_movimiento_almacen SET status_agrupacion=1 WHERE id_tipo_movimiento=contador AND id_movimiento_almacen!=-1 AND id_equivalente!=0
			AND status_agrupacion=-1 AND fecha LIKE CONCAT('%',fecha_agrupacion,'%');
		END IF;

		IF(tipo_agrupacion=3)/*por ano*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_movimiento_almacen SET status_agrupacion=1 WHERE id_tipo_movimiento=contador AND id_movimiento_almacen!=-1 AND id_equivalente!=0
			AND status_agrupacion=2 AND fecha LIKE CONCAT('%',fecha_agrupacion,'%');
		END IF;

		IF(tipo_agrupacion=4)/*por historico*/
		THEN
		/*Ponemos las cabeceras en status de agrupacion "agrupando"*/
			UPDATE ec_movimiento_almacen SET status_agrupacion=1 WHERE id_tipo_movimiento=contador AND id_movimiento_almacen!=-1 AND id_equivalente!=0
			AND status_agrupacion IN(3,4) AND fecha<=fecha_agrupacion;
		END IF;	

	/*declaramos en 1 id de almacen*/
		SET num_almacenes=1;

	/*corremos while anidado para el contador de almacenes*/
		WHILE num_almacenes<=tope_almacenes DO
			SET verif_almacen_detalle=0;
		/*vemos si el almacen existe y si sacamos su sucursal*/
			SELECT count(*) INTO verif_almacen FROM ec_almacen WHERE id_almacen=num_almacenes;
		/**/
			SELECT COUNT(md.id_movimiento_almacen_detalle) INTO verif_almacen_detalle
				FROM ec_movimiento_detalle md 
				LEFT JOIN ec_movimiento_almacen ma ON md.id_movimiento=ma.id_movimiento_almacen
				WHERE ma.id_tipo_movimiento=contador
				AND ma.status_agrupacion=1
				AND ma.id_almacen=num_almacenes;
	
			IF(verif_almacen=1 AND verif_almacen_detalle>0)/*si el almacen existe*/			
			THEN		
			/*extraemos datos del almacen*/
				SELECT id_almacen,id_sucursal INTO id_almacen_tmp,id_sucursal_tmp FROM ec_almacen WHERE id_almacen IN(num_almacenes);
			/*insertamos la cabecera del movimiento de almacen*/
				INSERT INTO ec_movimiento_almacen VALUES(null,contador,1,id_sucursal_tmp,
					IF(tipo_agrupacion=3,fecha_agrupacion_auxiliar,fecha_agrupacion)/*now()*/,now(),'AGRUPACION DE MOVIMIENTOS DE ALMACEN',-1,-1,'',-1,-1,
					id_almacen_tmp,tipo_agrupacion,-1,'0000-00-00 00:00:00',now());

				SELECT LAST_INSERT_ID() INTO movimiento_insertado;
				/*SET movimiento_insertado=251641;*/
			
			/*insertamos el detalle del movimiento de almacen*/
				INSERT INTO ec_movimiento_detalle
					SELECT
						null,
						movimiento_insertado,
						p.id_productos,
						SUM( IF( ma.id_movimiento_almacen IS NULL,0,md.cantidad ) ),
						SUM( IF( ma.id_movimiento_almacen IS NULL,0,md.cantidad ) ),
						-1,
						-1
					FROM ec_productos p
					LEFT JOIN ec_movimiento_detalle md ON md.id_producto=p.id_productos
					LEFT JOIN ec_movimiento_almacen ma ON md.id_movimiento=ma.id_movimiento_almacen
					LEFT JOIN ec_tipos_movimiento tm ON ma.id_tipo_movimiento=tm.id_tipo_movimiento
					WHERE ma.id_tipo_movimiento=contador
					AND ma.status_agrupacion=1
					AND ma.id_almacen=id_almacen_tmp
					GROUP BY p.id_productos;

			/*eliminamos los movimientos de almacen despues de haberlos agrupado*/
				IF(tipo_agrupacion=4)
				THEN
					DELETE FROM ec_movimiento_almacen WHERE id_almacen=id_almacen_tmp AND status_agrupacion=1 AND id_tipo_movimiento=contador
					AND fecha <=fecha_agrupacion AND id_equivalente!=0;
				ELSE
					DELETE FROM ec_movimiento_almacen WHERE id_almacen=id_almacen_tmp AND status_agrupacion=1 AND id_tipo_movimiento=contador
					AND fecha LIKE CONCAT('%',fecha_agrupacion,'%') AND id_equivalente!=0;
				END IF;
			END IF;
			
			SET num_almacenes=num_almacenes+1;
		
		END WHILE;
	/*aumentamos 1 al contador*/
		SET contador=contador+1;
	END WHILE;
	IF(tipo_agrupacion=3)
	THEN
		INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion,
				(SELECT COUNT(*) FROM ec_movimiento_almacen WHERE id_movimiento_almacen!=-1 AND id_equivalente!=0 AND fecha=fecha_agrupacion_auxiliar),
				(SELECT max(fecha) FROM ec_movimiento_almacen WHERE fecha like CONCAT('%',fecha_agrupacion_auxiliar,'%')),now());
	ELSE
		INSERT INTO sys_prueba_mantenimiento VALUES(null,tipo_agrupacion,
				(SELECT COUNT(*) FROM ec_movimiento_almacen WHERE id_movimiento_almacen!=-1 AND id_equivalente!=0 AND fecha=fecha_agrupacion),
				(SELECT max(fecha) FROM ec_movimiento_almacen WHERE fecha like CONCAT('%',fecha_agrupacion,'%')),now());
	END IF;
COMMIT;
END $$
|
/*Procedure implementado por Oscar 20-09-2020 para recalcular inventarios de almcen producto*/
DROP PROCEDURE IF EXISTS recalculaInventariosAlmacen|
DELIMITER $$
CREATE PROCEDURE recalculaInventariosAlmacen()
BEGIN

START TRANSACTION;

	UPDATE ec_almacen_producto ap
	LEFT JOIN 
	(
	SELECT
		NULL,
		ax.id_almacen,
	    ax.id_productos,
	    SUM( IF(ma.id_movimiento_almacen IS NULL, 0, (md.cantidad*tm.afecta) ) ) as inventario
	FROM
	(
		SELECT
	    	alm.id_almacen,
			p.id_productos,
	    	p.nombre
		FROM ec_productos p
		JOIN ec_almacen alm
		WHERE p.id_productos>0
	    AND alm.id_almacen>0
		GROUP BY alm.id_almacen, p.id_productos  
		ORDER BY alm.id_almacen, p.id_productos
	)ax
	LEFT JOIN ec_movimiento_detalle md 
	ON ax.id_productos = md.id_producto
	LEFT JOIN ec_movimiento_almacen ma 
	ON md.id_movimiento = ma.id_movimiento_almacen
	AND ax.id_almacen = ma.id_almacen 
	LEFT JOIN ec_tipos_movimiento tm 
	ON ma.id_tipo_movimiento = tm.id_tipo_movimiento
	GROUP BY ax.id_almacen, ax.id_productos
	)ax_2
	ON ap.id_producto = ax_2.id_productos
	AND ap.id_almacen = ax_2.id_almacen

	SET ap.inventario = ax_2.inventario;

COMMIT;

END $$
|

/*Procedure implementado por Oscar 2021 para eliminar los registros por separado*/
DROP PROCEDURE IF EXISTS eliminaRegistrosProductosSinInventario|
DELIMITER $$
CREATE PROCEDURE eliminaRegistrosProductosSinInventario(IN fecha_eliminar VARCHAR(10))
BEGIN
START TRANSACTION;
	SELECT date_add(CURRENT_DATE(), INTERVAL (fecha_eliminar*-1) DAY) INTO fecha_eliminar;
/*Eliminamos movimientos_temporales*/
	DELETE FROM ec_productos_sin_inventario WHERE alta<=CONCAT(fecha_eliminar,' 23:59:59');


COMMIT;

END $$
