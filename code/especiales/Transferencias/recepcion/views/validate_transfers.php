<div class="accordion group_card" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
      	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        	<div class="row">
        		<div class="col-9">
        			Productos pendientes de recibir
      			</div>
      			<div class="col-3" style="text-align:right !important;">
      				<i class="icon-pin"><b style="font-size:7px;" id="transfer_difference_counter">0</b></i>
      			</div>
      		</div>
  		</button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
    	<div class="accordion-body">
	<!-- Faltante -->
			<div class="transfers_validation_container">
				<h5 class="title_sticky"></h5>
				<table class="table table-striped table_80">
					<thead class="header_sticky" style="top : -10px;">
						<tr>
							<th>Producto</th>
							<th>Faltante</th>
							<th>Ver</th>
						</tr>
					</thead>
					<tbody class="transfers_list_content" id="transfer_difference">
						<?php
							//echo receptionResumen( 1, $link );
						?>
					</tbody>
					<tfoot></tfoot>
				</table>
			</div>
    	</div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
    	<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
        	<div class="row">
        		<div class="col-9">
        			Productos que llegaron de más o diferente modelo
      			</div>
      			<div class="col-3" style="text-align:right !important;">
      				<i class="icon-pin"><b style="font-size:7px;" id="transfer_excedent_counter">0</b></i>
      			</div>
      		</div>
      	</button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
    	<div class="accordion-body">
	<!-- Productos de más que se quedan en sucursal -->
			<div class="transfers_validation_container">
				<table class="table table-striped table_80">
					<thead class="header_sticky" style="top : -10px;">
						<tr>
							<th>Producto</th>
							<th>Faltante</th>
							<th>Ver</th>
						</tr>
					</thead>
					<tbody class="transfers_list_content"  id="transfer_excedent">
						<?php
						//	echo receptionResumen( 2, $link );
						?>
					</tbody>
					<tfoot></tfoot>
				</table>
			</div>
    	</div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
      	<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        	<div class="row">
        		<div class="col-9">
        			Productos que se devolverán
      			</div>
      			<div class="col-3" style="text-align:right !important;">
      				<i class="icon-pin"><b style="font-size:7px;" id="transfer_return_counter">2</b></i>
      			</div>
      		</div>
        </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
    	<div class="accordion-body">		
	<!-- Productos de más que se regresan -->
			<div class="transfers_validation_container">
				<h5 class="title_sticky"></h5>
				<table class="table table-striped table_80">
					<thead class="header_sticky" style="top : -10px;">
						<tr>
							<th>Producto</th>
							<th>Faltante</th>
							<th>Ver</th>
						</tr>
					</thead>
					<tbody id="transfer_return">
						<?php
							//echo receptionResumen( 3, $link );
						?>
					</tbody>
					<tfoot></tfoot>
				</table>
			</div>
    	</div>
    </div>
  </div>
</div>
<!-- -->

	<div class="" style="font-size : 70%; max-height: 450px; overflow-y : auto; ">
		<br>
	</div>
<hr>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-8">
				<button
					type="button"
					class="btn btn-success form-control"
				>
					Finalizar Recepción<i class="icon-ok-circle"></i>
				</button>
			</div>
			<div class="col-2"></div>
		</div>