DROP TRIGGER IF EXISTS insertaRegNomina;
DELIMITER $$
CREATE TRIGGER insertaRegNomina
AFTER INSERT ON ec_registro_nomina
FOR EACH ROW
BEGIN
    DECLARE id_suc INT(11);
/*vemos la sucursal de logueo*/
    SELECT id_sucursal INTO id_suc FROM sys_sucursales WHERE acceso=1;
    IF(id_suc=-1 AND new.sincronizar=1)/*si es línea*/
    THEN
    /*Insertamos el registro en línea*/
        INSERT INTO ec_sincronizacion_registros SELECT null,id_suc,id_sucursal,'ec_registro_nomina',new.id_registro_nomina,1,1,now(),0
        FROM sys_sucursales WHERE id_sucursal>0;
    END IF;
    IF(id_suc>0 AND new.sincronizar=1)
    THEN
	/*Insertamos el registro local*/
        INSERT INTO ec_sincronizacion_registros VALUES(null,id_suc,-1,'ec_registro_nomina',new.id_registro_nomina,1,1,now(),0);
    END IF;
END $$
