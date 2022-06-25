<?php
//incluimos la libreria de configuraciones generales
	include('config.inc.php');//incluimos la libreria general de funciones
	include($codepath."/general/funciones.php");
//definimos zona horaria
	date_default_timezone_set('America/Mexico_City');
	$hostLocal='localhost';
	$userLocal='wwcasa_desarrollo2022';
	$passLocal='desarrollo2022';
	$nombreLocal='wwcasa_general_2022';
	$local=@mysql_connect($hostLocal, $userLocal, $passLocal);
	//comprobamos conexion local
	if(!$local){	//si no hay conexion
		echo 'no hay conexion local';//finaliza programa
	}else{
	//echo'conexion local'.$nombreLocal;
	}
	$dblocal=@mysql_select_db($nombreLocal);
	if(!$dblocal){
		echo 'BD local no encontrada';
	}else{
	}
/***********************************CONEXIONES BD FORANEA*******************************************/
	$hostLinea='localhost';
	$userLinea='root';
	$passLinea='';
	$nombreLinea='cluces_2022';
	$linea=@mysql_connect($hostLinea,$userLinea,$passLinea);
	$indicador="";	if(!$linea){
die('Sin conexión a Línea');
}
	$dblinea=@mysql_select_db($nombreLinea);	if(!$dblinea){
		echo('BD en linea no encontrada');
	}else{
		//echo '<br>bd en linea encontrada';
	}
	require('include/sesiones.php');

	header('Content-Type: text/html; charset=utf-8');
?>