<?php
//consultamos los datos
	$sql="SELECT 
			/*0*/te.id_temporal_exhibicion,
			/*1*/p.orden_lista,
			/*2*/p.nombre,
			/*3*/(te.cantidad-te.piezas_recibidas)-te.piezas_agotadas,
			/*4*/0,
			/*5*/0,
			/*6*/p.id_productos
		FROM ec_temporal_exhibicion te
		LEFT JOIN ec_productos p ON te.id_producto=p.id_productos
		LEFT JOIN sys_sucursales_producto sp ON p.id_productos=sp.id_producto AND sp.id_sucursal IN($user_sucursal)
		WHERE te.id_sucursal=$user_sucursal AND (te.cantidad-te.piezas_recibidas)-te.piezas_agotadas>0 and te.es_valido=1
		ORDER BY p.orden_lista ASC";
	$eje=mysql_query($sql)or die("Error al consultar productos temporales en exhibición!!!\n\n".$sql."\n\n".mysql_error());
	$c=0;//declaramos contador en cero
//creamos el cuerpo de la tabla
	echo '<table width="100%">';
	while($r=mysql_fetch_row($eje)){
		$c++;//incrementamos el contador
	//asignamos el color
		if($c%2==0){
			$color='#E6E8AB';
		}else{
			$color='#BAD8E6';
		}

		echo '<tr id="fila_'.$c.'" style="background:'.$color.';" onclick="resalta_fila('.$c.');" tabindex="'.$c.'">';
			echo '<td style="display:none;" id="1_'.$c.'">'.$r[0].'</td>';//id del registro
			echo '<td id="2_'.$c.'" width="10%" style="padding:10px;" align="right">'.$r[1].'</td>';//orden de lista
			echo '<td width="45%">'.$r[2].'</td>';//nombre del producto
			echo '<td id="3_'.$c.'" align="right" width="15%">'.$r[3].'</td>';//cantidad tomada
			echo '<td id="4_'.$c.'" align="right" width="15%" onclick="editaCelda(4,'.$c.');">'.$r[4].'</td>';//cantidad de piezas regeresadas
			echo '<td id="5_'.$c.'" align="right" width="13%" onclick="editaCelda(5,'.$c.');">'.$r[5].'</td>';//cantidad de piezas agotadas
			echo '<td style="display:none;" id="6_'.$c.'">'.$r[6].'</td>';//id del producto
			echo '<td style="display:none;" id="7_'.$c.'">'.$r[3].'</td>';//cantidad original (OCULTA)
		echo '</tr>';
	}
	echo '</table>';
//creamos variable oculta que contiene total de filtas
	echo '<input type="hidden" value="'.$c.'" id="total_filas">';
?>
