<?php

?>
	<div>
		<!--div class="group_card"-->
			<div class="accordion group_card" id="accordionPanelsStayOpenExample">
			  <div class="accordion-item">
			    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
			      	<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
    					Transferencias
			  		</button>
			    </h2>
			    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
			    	<div class="accordion-body transfers">
			    	</div>
			    </div>
			  </div>
			</div>
		<!--/div-->

		<div class="group_card">
			<div class="input-group">
				<input 
					type="text"
					id="barcode_seeker"
					class="form-control"
					placeholder="Escanear código de barras"
					onkeyup="validateBarcode( this, event );"
				>
				<button type="button" class="btn btn-warning">
					<i class="icon-barcode"></i>
				</button>
			</div>
			<div id="seeker_response"></div>
		</div>

		<h5>Últimas revisiones</h5>
		<div class="group_card last_validations_container">
			<table class="table table-bordered table-striped table-90">
				<thead class="last_validations_header_sticky">
					<tr>
						<th>Producto</th>
						<th>Piezas<br>Revisadas</th>
						<th>Transf.</th>
					</tr>
				</thead>
				<tbody id="last_validations">
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		//loadLastValidations();
	</script>