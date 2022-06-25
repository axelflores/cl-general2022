<?php
	include("../../../../conectMin.php");
//extraemos datos por post
	extract($_POST);

	if($flag==2){
		//die('sin_precio='.$precio_ceros);
		$sql="SELECT 
			pr.id_proveedor_producto,
			CONCAT('$',ROUND(pr.precio_pieza,2),':',p.nombre_comercial),
			pr.presentacion_caja,
			p.id_proveedor
			FROM ec_proveedor_producto pr
			LEFT JOIN ec_proveedor p ON p.id_proveedor=pr.id_proveedor
			WHERE pr.id_producto='$id' AND IF($precio_ceros=1,pr.id_proveedor_producto>0,pr.precio>0)
			ORDER BY pr.precio_pieza ASC";
		$eje=mysql_query($sql)or die("Error al buscar proveedores para llenar el combo!!!\n\n".$sql."\n\n".mysql_error());
		echo 'ok|';
	//armamos combo
		echo '<select onchange="muestra_prov(this,'.$c.',2);" class="comb" id="c_p_'.$c.'" style="width:100%;">';//onclick="carga_proveedor_prod('.$c.','.$id.');" 
		echo '<option value="-1">--Seleccionar--</option>';//comobo prueba
	//retornamos valores
		while($r=mysql_fetch_row($eje)){
			echo '<option value="'.$r[3].'">'.$r[1].':'.$r[2].'pzas//'.base64_encode($r[0]).'//</option>';
		}
		echo '<option value="nvo">Administar Proveedores</option>';
		echo '</select>';
		return;//fin
	}
//consultamos datos de combos por tipo de producto
	if($campo==1){
		$t="ec_subcategoria";
		$c_t="id_subcategoria,nombre";
		$comp="id_categoria";
	}
	if($campo==2){
		$t="ec_subtipos";
		$c_t="id_subtipos,nombre";
		$comp="id_tipo";
	}
	$sql="SELECT {$c_t} FROM {$t} WHERE {$comp}='$id'";
	$eje=mysql_query($sql)or die("Error al buscar datos del combo!!!\n\n".$sql."\n\n".mysql_error());
	echo 'ok|';
//enviamos datos
	while($r=mysql_fetch_row($eje)){
		echo $r[0]."~".$r[1]."°";
	}
?>