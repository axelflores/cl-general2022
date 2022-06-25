<?php
/*implementación Oscar 2021 para ejecutar consultas con MYSQLI*/
	include('../../../../config.inc.php');
	$link = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
	$link->set_charset("utf8");
//descarga de csv
	if(isset($_POST['fl']) && $_POST['fl']==1){
			//recibimos datos
		$info=$_POST['datos'];
	//creamos el nombre del archivo
		$nombre="exportacion_tabla.csv";
	//generamos descarga
		header('Content-Type: aplication/octect-stream');
		header('Content-Transfer-Encoding: Binary');
		header('Content-Disposition: attachment; filename="'.$nombre.'"');
		echo(utf8_decode($info));
		die('');
	}
	$id_herr=$_POST['id'];
	$filtros=explode("°",$_POST['arr']);
	//sacamos la consulta
		$sql = "SELECT consulta FROM sys_herramientas WHERE id_herramienta='$id_herr'";
		$eje = $link->query($sql)or die("Error al consultar la base de la herramienta!!!<br>".$link->error."<br>".$sql);
		$r = $eje->fetch_row();
		$sql = $r[0];
	//sacamos los filtros
		for($i=0;$i<sizeof($filtros);$i++){
			if($filtros[$i]!='' && $filtros[$i]!=null){
				$campos_filtro=explode("~", $filtros[$i]);
				if($id_herr==1 && ($campos_filtro[1]=='$FECHA_1' || $campos_filtro[1]=='$FECHA_2') ){//si es verificacion de pedidos
					$sql_sub="SELECT DATE_FORMAT('$campos_filtro[2]','%Y')";
					$eje_sub = $link->query($sql_sub)or die("Error al formatear la fecha!!!!<br>".$sql_sub);
					$r_sub = $eje_sub->fetch_row();
					$campos_filtro[2] = $r_sub[0];
				}
			//reemplazamos filtros
				if($campos_filtro[2]==0){
					$campos_filtro[0]='';
					$campos_filtro[2]='';
				}
				$sql=str_replace($campos_filtro[1], $campos_filtro[0]."".$campos_filtro[2], $sql);
			}
		}
		echo 'ok|'.$sql.'|';
		$is_update_or_insert = 0;
	//ejecutamos la consulta
		if( ! $eje = $link->query($sql) ){
			$is_update_or_insert = 1;
			$eje = $link->multi_query($sql) or die("Error al ejecutar la consulta!!!<br>". $link->error ."<br>".$sql);	
			die("La herramienta fue ejecutada exitosamente!");
		}
		
//		$field = mysqli_num_fields($eje);
    	$names;

		$info_campo = mysqli_fetch_fields($eje);

        foreach ($info_campo as $key => $valor) {
            $names[$key] = $valor->name;
        }
		echo '<table class="result" id="grid_resultado" width="100%">';
		/*Oscar 2021 para sumar montos de ventas*/
			$suma_montos = 0;
		/*fin de cambio Oscar 2021*/
		$c=0;
		while( $r = $eje->fetch_row() ){
		if($c==0){
			echo '<tr>';
			for($i=0;$i<sizeof($names);$i++){
				echo '<th>'.$names[$i].'</th>';
			}
			echo '</tr>';
		}
			echo '<tr>';
			for($i=0;$i<sizeof($r);$i++){
				echo '<td>'.$r[$i].'</td>';
			/*Oscar 2021 para sumar montos de ventas*/
				if ( $id_herr == 17 && $i == 1){
					$suma_montos += $r[$i];
				}
			/*fin de cambio Oscar 2021*/
			}
			echo '</tr>';
			$c++;
		}
	/*Oscar 2021 para sumar montos de ventas*/
		if ( $id_herr == 17 ){
			echo "<tr><td style=\"text-align : right;\">Total : </td><td>{$suma_montos}</td><td></td></tr>";
		}
	/*fin de cambio Oscar 2021*/

		echo '</table>';
		/*<html>
	<link rel="stylesheet" href="css/estilos.css">
</html>*/
?>