<?php
	function getProductReceived(){
		$resp = '';
		for ($i = 0; $i <= 10 ; $i++ ) {
			$resp .= "<tr>";
				$resp .= "<td>Serie LED 50 Luces Blanca C/Transparente 3.5M</td>";
				$resp .= "<td>10</td>";
			$resp .= "</tr>";
		}
		return $resp;
	}
?>
	<div class="">

		<div class="group_card">
			<label for="barcode_seeker">Escaner código de barras del Producto</label>
			<div class="input-group">
				<input 
					type="text"
					id="barcode_seeker"
					class="form-control"
					placeholder="Escaner código de barras del Producto"
					onkeyup="seekTicketBarcode( event, this, 'seekProductBarcode' );"
				>
				<button class="input-group-text btn btn-warning">
					<i class="icon-barcode"></i>
				</button>
			</div>
		</div>
		<!--h5 style="color : red ;">Productos Pendientes de Revisar</h5-->
		<div class="group_card validation_detail_table_container group_danger">
			<table class="table table-striped table_80">
				<thead class="header_sticky header_sticky_validation">
					<tr>
						<th>Producto</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody id="pending_validation">
				</tbody>
				<tfoot></tfoot>
			</table>
		</div>

		<!--h5 style="color : green ;">Productos Revisados</h5-->
		<div class="group_card validation_detail_table_container group_success">
			<table class="table table-striped table_80">
				<thead class="header_sticky header_sticky_validation">
					<tr>
						<th>Producto</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody id="validated">
				</tbody>
				<tfoot></tfoot>
			</table>
		</div>
<hr>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-8">
				<button
					type="button"
					class="btn btn-success form-control"
					onclick="finish_validation();"
				>
					Finalizar Revisión
				</button>
			</div>
			<div class="col-2"></div>
		</div>
	</div>