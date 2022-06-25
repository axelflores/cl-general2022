<?php
	function getTransfersToReceive( $sucursal, $link ){
		$resp = '';
		$sql = "SELECT 
					id_transferencia AS transfer_id,
					folio,
					CONCAT( fecha, ' ', hora ) AS date_time
				FROM ec_transferencias
				WHERE id_estado = 8
				AND id_sucursal_destino = '{$sucursal}'";
				//die( $sql );
		$stm = $link->query( $sql ) or die( "Error al consultar las transferencias por recibir : " . $link->error );
		$counter = 0;
		while ( $row = $stm->fetch_assoc() ) {
			$resp .= "<tr>";
				$resp .= "<td id=\"transferGrid_1_{$counter}\" class=\"no_visible\">{$row['transfer_id']}</td>";
				$resp .= "<td id=\"transferGrid_2_{$counter}\">{$row['folio']}</td>";
				$resp .= "<td id=\"transferGrid_3_{$counter}\">{$row['date_time']}</td>";
				$resp .= "<td id=\"transferGrid_4_{$counter}\"><input type=\"checkbox\" id=\"receive_{$counter}\"></td>";
			$resp .= "</tr>";
			$counter ++;
		}
		return $resp;
	}

?>

	<div class="">
		<br>
		<div class="group_card transfer_container">
			<h5 class="title_sticky">Transferencias por recibir</h5>
			<table class="table table-striped table_80">
				<thead class="header_sticky">
					<tr>
						<th>Transferencia</th>
						<th>Fecha</th>
						<th>Recibir</th>
					</tr>
				</thead>
				<tbody class="transfers_list_content">
					<?php
						echo getTransfersToReceive( $sucursal_id ,$link );
					?>
				</tbody>
				<tfoot></tfoot>
			</table>
		</div>
		
		<br>
		
		<div class="row">
			<div class="col-2"></div>
			<div class="col-8">
				<button
					type="button"
					class="btn btn-success form-control"
					onclick="setTransferToReceive();"
				>
					Recibir<i class="icon-truck"></i>
				</button>
			</div>
			<div class="col-2"></div>
		</div>
	</div>