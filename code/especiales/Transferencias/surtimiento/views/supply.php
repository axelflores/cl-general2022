
	<div class="supply_container">
		<div class="row" style="font-size : 100%; text-align : center;">
			<div class="col-6">
				<span><b>Destino :</b> <b class="orange" id="transfer_destination"></b></span>
			</div>
			<div class="col-6">
				<span><b>Nivel :</b> <b class="orange" id="transfer_level"></b></span>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="input-group">
				<input 
					type="text" 
					class="form-control"
					id="product_seeker"
					placeholder="Escanear código de barras"
					onkeyup="seek_product( event, '#product_seeker' );"
				>
				<button class="btn btn-warning">
					<i class="icon-barcode"></i>
				</button>
			</div>
			<div>
				<p id="product_name" class="product_description"></p>
				<p class="product_description">MODELO : <b id="product_model"></b></p>
			</div>
		<!-- campos ocultos -->
			<input type="hidden" id="current_transfer_product_id" value="0">
			<input type="hidden" id="current_product_id" value="0">
			<input type="hidden" id="current_product_provider" value="0">
			<input type="hidden" id="current_supply_detail_id" value="0">
			<input type="hidden" id="current_supply_id" value="0">
			<input type="hidden" id="current_user_tracking_id" value="0">


			
			<div class="group_card card" id="product_supply_container">
				<div class="row card_item">
					<div class="col-6" style="text-align : center;">
						<label>Ubicación : </label>
						<div>
							<input 
								type="text"
								id="product_location"
								class="form-control readonly"
								readonly
							>
						</div>
					</div>
					<div class="col-6 product_pieces_total_information" style="text-align : center;">
						<label>Total pzs : </label>
							<input 
								type="text"
								id="product_pieces_total"
								class="form-control readonly orange text-center"
								readonly
							>
					</div>
				</div>
				<div class="supply_instructions">
					<div class="row card_item supply_instructions_information">
						<div class="col-4">
							<label for="">Cajas : <b class="product_boxes_quantity_txt"></b></label>
						</div>
						<div class="col-4">
							<label for=""> Paq : <b class="product_packs_quantity_txt"></b></label>
						</div>
						<div class="col-4">
							<label for="">Piezas : <b class="product_pieces_quantity_txt"></b></label>
						</div>
					</div>

					<div class="row card_item pieces_required">
						<div class="col-4 txt-center">
							<input 
								type="text"
								id="product_boxes_quantity"
								class="form-control readonly text-center"
							>
							<input
								type="hidden"
								id="product_boxes_quantity_to_supply"
								value="0"
							>
							<input
								type="hidden"
								id="product_boxes_quantity_supplied"
								value="0"
							>
						</div>
						<div class="col-4 txt-center">
							<input 
								type="text"
								id="product_packs_quantity"
								class="form-control readonly text-center"
							>
							<input
								type="hidden"
								id="product_packs_quantity_to_supply"
								value="0"
							>
							<input
								type="hidden"
								id="product_packs_quantity_supplied"
								value="0"
							>
						</div>
						<div class="col-4 txt-center">
							<input 
								type="text"
								id="product_pieces_quantity"
								class="form-control readonly text-center"
							>
							<input
								type="hidden"
								id="product_pieces_quantity_to_supply"
								value="0"
							>
							<input
								type="hidden"
								id="product_pieces_quantity_supplied"
								value="0"
							>
						</div>
					</div>
				
					<div class="row card_item form_continue">
						<div class="col-6">
							<input type="radio" id="complete" onclick="show_next_steep( this, 1 );" name="supply_error">
							<label for="complete" class="complete_label">Completo</label>
						</div>
						<div class="col-6">
							<input type="radio" id="incomplete" onclick="show_next_steep( this, 2 );" name="supply_error">
							<label for="incomplete" class="incomplete_label">Piezas Incompletas</label>
						</div>
						<!--div class="col-4">
							<input type="radio" onclick="show_next_steep( this, 3 );" name="supply_error">No hay
						</div-->
					</div>
				
				</div>
			</div>


<!-- boton de continuar -->
			<div class="row card_item">
				<button 	
					type="button"
					id="supply_btn_continue"
					class="btn btn-success"
					onclick="saveProductSupplie( null );"
				>
					<i class="icon-right-big">Continuar</i>
				</button>
			</div>
	<!-- boton para acompletar con otra presentación -->
			<div class="row card_item">
				<button 	
					type="button"
					id="supply_btn_other_presentation"
					class="btn btn-warning"
					onclick="getProductModels()"
				>
					<i class="icon-attention-filled">Acompletar con otra presentación</i>
				</button>
			</div>
	<!-- Botón para eliminar un registro de surtimiento de Tansferencias -->
			<div class="row card_item">
				<button
					type="button"
					id="supply_btn_delete"
					class="btn btn-danger"
					onclick="deleteProductSupplie();"
				>
					<i class="icon-cancel-circled">Eliminar este Surtimiento</i>
				</button>
			</div>
		</div>
		
	</div>
