<?php
	include("../../conectMin.php");
	extract($_POST);
	extract($_GET);	

if(isset($_POST['autoriza_transferencia'])){
	$sql="	SELECT
			administrador
			FROM sys_users
			WHERE id_usuario=$user_id";
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	$row=mysql_fetch_row($res);
	if($row[0] == '0'){
		die("No cuenta con los permisos para realizar esta acción");
	}	
	$sql="SELECT
			id_estado
			FROM ec_transferencias
			WHERE id_transferencia=$id_transferencia";			
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	$row=mysql_fetch_row($res);
	
	if($row[0] != '1'){
		die("La transferencia ya ha sido autorizada");
	}

	if($user_tipo_sistema=='local'){
		$sql="SELECT permite_transferencias FROM sys_sucursales WHERE id_sucursal=$user_sucursal";
		$eje=mysql_query($sql)or die("Error al consultar si la transferencia se puede hacer localmente!!!");
		$r=mysql_fetch_row($eje);
		if($r[0]==0){
			die("No es posible continuar con el proceso de transferencia localmente.\nContacte al administrador para continuar!!!");
		}
	}
	//die('aqui');	
		
	//MAMG	
	//$sql="UPDATE ec_transferencias SET id_estado=$nval, ultima_actualizacion=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') WHERE id_transferencia=$id_transferencia";	

	$sql="UPDATE ec_transferencias SET id_estado=2, ultima_actualizacion=DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') WHERE id_transferencia=$id_transferencia";	
	mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	$sqlSal = "SELECT id_transferencia_producto, id_transferencia,cantidad, cantidad_presentacion FROM ec_transferencia_productos WHERE id_transferencia = $id_transferencia";
	$resSal=mysql_query($sqlSal) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	$numTrans = mysql_num_rows($resSal);
	for ($iSal = 0; $iSal < $numTrans; $iSal++){
		$rowSal=mysql_fetch_row($resSal);
		$sqlU = "UPDATE ec_transferencia_productos SET cantidad_salida =" . $rowSal[2] . ", cantidad_salida_pres =" .$rowSal[3] . " where id_transferencia=" .$rowSal[1] . " and id_transferencia_producto=".$rowSal[0] ;
		mysql_query($sqlU) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());
	}
	$id=$id_transferencia;
/*implementación Oscar 18.04.2019 para meter la transferencia directamente salida de transferecnia si la sucursal origen es diferente de Matriz*/
	$sql="SELECT id_sucursal_origen FROM ec_transferencias WHERE id_transferencia=$id_transferencia";
	$eje=mysql_query($sql)or die("Error al consultar dato de sucursal origen de la transferencia!!!");
	$r=mysql_fetch_row($eje);
	//die("4r[0]: ".$r[0]);
	if($r[0]>1){//si el origen no es Matriz

	//cambiamos la transferencia a status de salida de transferencia directamente
		$sql="UPDATE ec_transferencias SET id_estado=4 WHERE id_transferencia=$id_transferencia";
		$eje=mysql_query($sql)or die("Error al actualizar status de la transferencia directamente a salida por ser de sucursal a sucursal!!!");
	}
/*Fin de cambio Oscar 18.04.2019*/
//aqui imprime el documento
	require("imprimeDocTrans.php");
//aqui envia el email	
	/*
		comentado por Oscar 2021 porque mandaba muchos correos y ya no se usa
		require("enviaMailTrans.php");
	*/

	die(" Se ha cambiado el estatus de la transferencia exitosamente");
}

/**/
	$sql="SELECT id_estado,id_sucursal_destino,id_sucursal_origen FROM ec_transferencias WHERE id_transferencia=$id_transferencia";
	$eje=mysql_query($sql)or die("Eror al consultar el status de la transferencia!!!\n\n".$sql."\n\n".mysql_error());
	$row=mysql_fetch_row($eje);

//si la transferencia no ah sido autorizada
	if($row[0]==1){
		die('ok|0');
	}
//si es para poner en proceso de surtimiento y el origen es matriz
	if($row[0]==2 && $row[2]==1){
		if($autorizacion==''||$autorizacion==null){
			if($user_sucursal==$row[1]){
				die("La transferencia solo puede ser puesta en proceso de surtimiento desde Matriz!!!");
			}
			die('ok|pedir_pass|Ingrese el nombre de quien surtirá la Transferencia para continuar con el proceso|white');
		}else{
			$sql="UPDATE ec_transferencias SET id_estado=3,observaciones=CONCAT(observaciones,'\n-Surtida por: ','$autorizacion',' a las ',(SELECT NOW())) WHERE id_transferencia=$id_transferencia";
			$eje=mysql_query($sql)or die("Error al poner transferencia en Surtimiento\n".mysql_error());
			die("ok|1|La transferencia fue puesta en status de Surtimiento!!!");
		}
	}
//si es para poner en salida de transferencia y el origen es matriz
	if($row[0]==3){
		if($autorizacion==''||$autorizacion==null){
			if($user_sucursal==$row[1]){
				die("La transferencia solo puede ser puesta en Salida de Transferencia desde Matriz!!!");
			}
			die('ok|pedir_pass|Ingrese el nombre de quién revisa y pone en salida la Transferencia |yellow');
		}else{
			$sql="UPDATE ec_transferencias SET id_estado=4,observaciones=CONCAT(observaciones,'\n-Puesta en salida por: ','$autorizacion',' a las ',(SELECT NOW())) WHERE id_transferencia=$id_transferencia";
			$eje=mysql_query($sql)or die("Error al poner transferencia en Salida");
			die("ok|1|La transferencia fue puesta en status de Salida!!!");	
		}
	}

//si es recepción
	if($row[0]==4){
		if($row[1]!=$user_sucursal  && $user_sucursal!=-1){
			die("Las transferencias solo pueden ser recibidas desde la sucursal de destino");
		}
		$url_respuesta="code/general/contenido.php?aab9e1de16f38176f86d7a92ba337a8d=ZWNfdHJhbnNmZXJlbmNpYXM=&a1de185b82326ad96dec8ced6dad5fbbd=MQ==&a01773a8a11c5f7314901bdae5825a190=";
		$url_respuesta.=base64_encode($id_transferencia);
		$url_respuesta.="&bnVtZXJvX3RhYmxh=Mg==";
		die('ok|2|'.$url_respuesta);
	}
//si etá en resolución de transferencias
	if($row[0]==5){
		if($row[1]!=$user_sucursal && $user_sucursal!=-1){
			die("Las transferencias solo pueden ser recibidas desde la sucursal de destino");
		}
		$url_respuesta="code/especiales/resolucionTransferencias.php?a1de185b82326ad96dec8ced6dad5fbbd=MQ==&a01773a8a11c5f7314901bdae5825a190=";
		$url_respuesta.=base64_encode($id_transferencia);
		die('ok|2|'.$url_respuesta);
	}
	
//actualizar a salida de Transferencia
	if($row[0]==7){
		$sql = "UPDATE ec_transferencias SET id_estado = 8 WHERE id_transferencia = '{$id_transferencia}'";
		$eje=mysql_query($sql)or die("Error al poner transferencia en Salida");
		die( 'ok|7|Transferencia actualizada a Salida exitosamente!' );
	}

?>