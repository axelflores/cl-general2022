<?php
	include('../../../conexionDoble.php');

	if(isset($_POST['fl']) && $_POST['fl']=='permiso'){
	//recibimos las variables
		$user=$_POST['usuario'];
		$pass=md5($_POST['clave']);
		$id_sucursal=$_POST['suc'];
		$conexion=$local;
		if($id_sucursal==-1){
			$conexion=$linea;
		}
	//verificamos los permisos
		$sql="SELECT id_usuario FROM sys_users WHERE login='$user' AND contrasena='$pass' AND (tipo_perfil=1 OR tipo_perfil=5)";
		//die($sql);
		$eje=mysql_query($sql,$conexion);
		if(!$eje){
			$error=mysql_error($conexion);		
			die("Error al verificar si el usuario tiene los permisos para restaurar o generar una nueva BD!!!\n\n".$sql."\n\n".$error);
		}
		if(mysql_num_rows($eje)==1){
			die('ok|');
		}else{
			die("El usuario y/o contraseña son Incorrectos o el usuario no tiene los permisos para restaurar la BD, verifique sus datos y vuelva a intentar!!!");
		}
	}//fin de si es permiso

	$id_suc=$_POST['id_suc'];//recibimos la sucursal
	$tipo_bd=$_POST['t_bd'];//tipo de BD
	$tipo_sistema=$_POST['t_sys'];//tipo de sistema
	$fecha_rsp=$_POST['fecha'];

	if($tipo_sistema==1){//si es nueva Base de datos
		mysql_query("BEGIN",$local);
		mysql_query("BEGIN",$linea);

		include("eliminaSobrantesLocal.php");
	//echo "here_1\n";
		include("actualizaEquivalentes.php");
	//echo "here_2\n";
		mysql_query("COMMIT",$local);
		mysql_query("COMMIT",$linea);
	}
	//die('ok');

/**/
	$s=$hostLocal;
	$bd=$nombreLocal;
	$u=$userLocal;
	$p="";

	$conexion_sqli=new mysqli($s,$u,$p,$bd);
	if($conexion_sqli->connect_errno){
		die("sin conexion");
	}else{
		//echo "conectado";
	}

	$cadena_arreglo="";
	$fp = fopen("../../../respaldos/procedures.sql", "r")or die("Error");
	while (!feof($fp)){
	 	$linea = fgets($fp);
	 	$cadena_arreglo.=$linea;
	}
	fclose($fp);
//echo $cadena_arreglo;
	//$cadena_arreglo=str_replace("DELIMITER $$", "", $cadena_arreglo);
	$arreglo_procedure=explode("|", $cadena_arreglo);
	for($i=0;$i<sizeof($arreglo_procedure);$i++){
//		echo "Array: ".$arreglo_procedure[$i]."\n";
		$arreglo_procedure[$i]=str_replace("DELIMITER $$", "", $arreglo_procedure[$i]);
		$arreglo_procedure[$i]=str_replace("$$", "", $arreglo_procedure[$i]);
		$eje=mysqli_multi_query($conexion_sqli,$arreglo_procedure[$i]);
		if(!$eje){
			die("Error con mysqli!!!".mysqli_error($conexion_sqli));
		}
	}
	die('ok|');
	/**/
/*********************************************************Proceso de restauración de BD****************************************/
	/*


	*/
?>