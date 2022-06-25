<?php
	//php_track_vars;
	
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
//seleccionamos permisos de acuerdo al perfil
	$sql="SELECT IF(ver=1 OR modificar=1,1,0) FROM sys_permisos WHERE id_menu=196 AND id_perfil=$perfil_usuario";
	$eje=mysql_query($sql)or die("Error al consultar los permisos del perfil!!<br>".mysql_error()."<br>".$sql);	
	$r=mysql_fetch_row($eje);
	$smarty->assign('ver_log_login',$r[0]);
	$smarty->display("especiales/asistencias.tpl");
	
?>