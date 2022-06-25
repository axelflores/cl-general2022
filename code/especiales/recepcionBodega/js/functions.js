//declaracion de variables
	var provider = 0;
	var invoices_number = 0;
	var invoices = new Array();
	var invoices_detail = new Array();
	var parts = new Array();
	var series = new Array();
	var first_steep_validate = false;
	
//mostrar / ocultar vistas del menú
	function show_view( obj, view ){
		if( first_steep_validate == false && view == '.invoices_products' ){
			alert( "Primero capture las remisiones!" );
			return false;
		}
		if( series.length == 0 && view == '.invoices_lists' ){
			alert( "Primero especifique las remisiones en la sección de remisiones para continuar!" );
			return false;
		}
	//limpia formulario de producto
		if( view == '.invoices_products' ){
			clean_product_form();
			desactivate_product_form();
		}
		$('.mnu_item.active').removeClass('active');
		$( obj ).addClass('active');
		$( '.content_item' ).css( 'display', 'none' );
		$( view ).css( 'display', 'block' );
	}
//redireccionamientos
	function redirect( type ){
		switch ( type ){
			case 'home' : 
				if( confirm( "Salir sin Guardar?" ) ){
					location.href="../../../";
				}
			break;

		}
	}

	function seeker_provider( show_all = 0 ){
		if( show_all == 1 ){
			$( '#invoice_provider' ).val( '' );
			$( '#invoice_provider_seeker' ).val( '' );
			$( '#invoice_provider_seeker' ).removeAttr( 'disabled' );
		}
		var url = 'ajax/db.php?fl=seekProvider&txt=' + $( '#invoice_provider_seeker' ).val();
		//alert( url );
		var response = ajaxR( url );
		$( '#provider_seeker_response' ).html( response );
		$( '#provider_seeker_response' ).css( 'display', 'block' );

	}

	function setProvider( provider_id, provider_name ){
		$( '#invoice_provider' ).val( provider_id );
		$( '#invoice_provider_seeker' ).val( provider_name );
		$( '#invoice_provider_seeker' ).attr( 'disabled' , true );
		$( '#provider_seeker_response' ).css( 'display', 'none' );
		$( '#btn_show_all_providers' ).attr( 'disabled', true );
		provider = provider_id;
	}

//establecer proveedor / numero de remisiones por recibir
	function setInitialConfig(){
		provider = $( '#invoice_provider' ).val();
		if( provider == 0 ){
			alert( "Escoja un proveedor para continuar");
			$( '#invoice_provider' ).focus();
			return false;
		}
		invoices_number = $( '#invoices_initial_counter' ).val();
		if( invoices_number <= 0 ){
			alert( "Ingrese el numero de remisiones a recibir para continuar");
			$( '#invoices_initial_counter' ).select();
			return false;
		}
		$( '#invoice_provider' ).attr( 'disabled', 'true' );
		$( '#invoices_initial_counter' ).attr( 'disabled', 'true' );
		$( '#invoices_initial_config_confirm' ).css( 'display', 'none' );
		$( '#invoices_initial_config_edit' ).css( 'display', 'block' );
	//habilita campos de busqueda para agregar pedido
		$( '#invoices_seeker' ).removeAttr( 'disabled' );
		$( '#invoice_folio' ).removeAttr( 'disabled' );
		$( '#invoice_parts' ).removeAttr( 'disabled' );
		$( '#invoice_button_add' ).removeAttr( 'disabled' );

		$( '#btn_show_all_providers' ).attr( 'disabled', true );

		validate_invoices_number();
	}

//editar proveedor / numero de remisiones por recibir
	function editInitialConfig(){
		$( '#invoice_provider' ).removeAttr( 'disabled' );
		$( '#invoices_initial_counter' ).removeAttr( 'disabled' );
		$( '#invoices_initial_config_confirm' ).css( 'display', 'block' );
		$( '#invoices_initial_config_edit' ).css( 'display', 'none' );
	//habilita campos de busqueda para agregar pedido
		$( '#invoices_seeker' ).attr( 'disabled', 'true' );
		$( '#invoice_folio' ).attr( 'disabled', 'true' );
		$( '#invoice_parts' ).attr( 'disabled', 'true' );
		$( '#invoice_button_add' ).attr( 'disabled', 'true' );

		$( '#btn_show_all_providers' ).removeAttr( 'disabled' );

	}
//validar que el folio de la remisión no exista
	function validateInvoiceNoExists( obj ){
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'validateInvoiceNoExists', to_check : $( obj ).val().trim() },
			success : function ( dat ){
				if ( dat != 'ok' ){
					alert( dat );
					$( '#invoice_button_add' ).attr( 'disabled', 'true' );
					$( obj ).select();
					$( obj ).val( '' );
					return false;
				}
				$( '#invoice_button_add' ).removeAttr( 'disabled' );
			}
		});
		
	}

//agregar remisiones
	function addInvoice(){
	//valida que el numero de partidas de la remisión sea valido
		if ( $( '#invoice_parts' ).val() <= 0 ){
			alert( "El nuimero de partidas tiene que ser mayor a 0" );
			$( '#invoice_parts' ).select();
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'insertInvoice', 
					invoice_folio : $( '#invoice_folio' ).val(),
					parts_number : $( '#invoice_parts' ).val(),
					provider_id : provider 
			},
			success : function ( dat ){
			//limipa los inputs
				$( '#invoice_folio' ).val( '' );
				$( '#invoice_parts' ).val( '' );
			//agrega el renglon al arreglo
				build_invoice_row( dat.split( '~' ) );
			}
		});
	}

	function seek_invoices( obj ){
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'seekInvoices', 
					provider_id : provider,
					key : $( obj ).val(),
					current_series : series 
			},
			success : function ( dat ){
				$( '.search_results_invoice' ).html( dat );
				$( '.search_results_invoice' ).css( 'display', 'block');
			}
		});

	}
	function setInvoiceExistent( dat ){
		var row = dat.split( '~' );
		$( '.search_results_invoice' ).html( '' );
		$( '.search_results_invoice' ).css( 'display', 'none');
		$( '#invoices_seeker' ).val( '' );
		build_invoice_row( row );
		build_invoices_lists();
		build_invoices_lists_finish();
	}

	function build_invoice_row ( row ){
		var resp = '';
		resp += '<tr>'
				+ '<td class="no_visible">' + row[0] + '</td>'
				+ '<td>' + row[2] + '</td>'
				+ '<td>' + row[1] + '</td>'
				+ '<td id="' + row[2] + '_parts_number">' + row[3] + '</td>'
			+ '</tr>'; 
		$( '#invoice_to_receive' ).append( resp );
		invoices[ row[2] ] = row ;
		invoices[ row[2] ]['invoice_detail'] = new Array();
		series.push( row[2] );
		validate_invoices_number();
		build_series_combo();
		first_steep_validate = true;
		//console.log( invoices );
	}

	function build_series_combo(){
		var combo = '<select id="product_serie" onchange="build_series_parts_combo( this )" class="form-control" disabled>';
		combo += '<option value="0">Seleccionar</option>';
		for( var i = 0; i < series.length; i++ ){
			combo += '<option value="' + series[i] + '">' + series[i] + '</option>';
		}
		combo += '</select>';
		$( '.product_serie' ).html( combo );
	}

	function build_series_parts_combo( serie ){
		$( '#product_part_number' ).empty();
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'getInvoiceParts',
					reference : $( serie ).val()
			},
			success : function ( dat ){
				$( '#product_part_number' ).append( dat );
				$( '#product_part_number' ).removeAttr( 'disabled' );
				$( '#btn_parts_edition' ).removeAttr( 'disabled' );
			}
		});
	}	

	function validate_first_part(){
		var current_invoices_number = $( '#invoice_to_receive tr').length;
		//alert( current_invoices_number );
		if( current_invoices_number <= 0 ){
			alert( "Primero capture remisiones por recibir!" );
			return false;
		}
		if( current_invoices_number != invoices_number ){
			alert( "Hay diferencias entre el numero de remisiones a recibir y las recepciones capturadas" );
			return false;
		}
		first_steep_validate = true;
		$( '.source' ).click();
	}

	function validate_invoices_number(){
		if( $( '#invoice_to_receive tr' ).length == invoices_number ){
			$( '#invoices_seeker' ).attr( 'disabled', 'true' );
			$( '#invoice_folio' ).attr( 'disabled', 'true' );
			$( '#invoice_parts' ).attr( 'disabled', 'true' );
			$( '#invoice_button_add' ).attr( 'disabled', 'true' );
		}else{
			$( '#invoices_seeker' ).removeAttr( 'disabled' );
			$( '#invoice_folio' ).removeAttr( 'disabled' );
			$( '#invoice_parts' ).removeAttr( 'disabled' );
			$( '#invoice_button_add' ).removeAttr( 'disabled' );
		}
	}

/*Sección de busqueda de productos*/
	function seek_product( e, obj ){
		var key = e.keyCode;
		var is_scanner = ( key == 13 ? 1 : 0 );
		if ( $( obj ).val().trim().length <= 2 ){
			$( '.productResBusc' ).html( '' );
			$( '.productResBusc' ).css( 'display', 'none');
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'seekProduct', 
					provider_id : provider,
					key : $( obj ).val().trim(),
					scanner : is_scanner  
			},
			success : function ( dat ){
				$( '.productResBusc' ).html( dat );
				$( '.productResBusc' ).css( 'display', 'block');
			}
		});
	}

	function setProduct( product_id, product_name, product_model, product_provider_id, product_barcode,
				product_pack_cluces, product_pack_cluces_barcode, product_box, product_box_barcode, 
				product_location){
	//si solo es cambio de producto
		if( is_row_edition == 1 ){
			$( '#product_id' ).val( product_id );
			$( '.product_name' ).html( product_name );
			$( '#product_provider' ).val( product_provider_id );
			/*$( '.product_model' ).html( product_model );*/
			$( '#product_seeker' ).attr( 'disabled', 'true' );
			is_row_edition = 1;//marca edicion de registro
			
			$( '#product_seeker' ).val( '' );
			$( '.productResBusc' ).html( '' );
			$( '.productResBusc' ).css( 'display', 'none' );
			return true;
		}
	//
		$( '#product_reset_btn' ).attr( 'onclick', 'desactivate_product_form();clean_product_form();' );
	//asigna los valores en pantalla / ocultos
		$( '#product_id' ).val( product_id );
		$( '.product_name' ).html( product_name );
		$( '.product_model' ).html( product_model );
		$( '#product_model' ).val( product_model );
		$( '#db_product_model' ).val( product_model );
		$( '#product_provider' ).val( product_provider_id );
		$( '#db_piece_barcode' ).val( product_barcode );
		$( '#db_pieces_per_pack' ).val( product_pack_cluces );
		$( '#db_pack_barcode' ).val( product_pack_cluces_barcode );
		$( '#db_pieces_per_box' ).val( product_box );
		$( '#db_box_barcode' ).val( product_box_barcode );
	//oculta opciones del buscador
		$( '.productResBusc' ).html( '' );
		$( '.productResBusc' ).css( 'display', 'none');
	//habilita el formulario
		activate_product_form();
		$( '#product_seeker' ).val( '' );
		//alert( product_location );
		if( product_location != '' ){
			$( "#location_status_source option[value=2]" ).text( "Ubicación actual : " + product_location );
		}else{
			$( "#location_status_source option[value=2]" ).text( "No tiene ubicación" );
		}
	}

	function validateNoRepeatBarcode( obj ){
		var id = $( obj ).attr( 'id' );
		var value = $( obj ).val().trim();
		if( value.length <= 0 ){
			return false;
		}
		if( id != 'piece_barcode' && value == $( '#piece_barcode' ).val()  && value != '' ){
			alert( "El código de barras ya existe en la pieza" );
			$( obj ).val('');
			return false;
		}

		if( id != 'pack_barcode' && value == $( '#pack_barcode' ).val() && value != '' ){
			alert( "El código de barras ya existe en el código de paquete" );
			$( obj ).val('');
			return false;
		}

		if( id != 'box_barcode' && value == $( '#box_barcode' ).val() && value != '' ){
			alert( "El código de barras ya existe en el código de caja" );
			$( obj ).val('');
			return false;
		}
		if( $( '#' + id ).val() == $( '#db_' + id ).val() ){//si es codigo de barras que ya esta registrado
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'seekBarcode',
					code : value,
					p_p : $( '#product_provider' ).val()
			},
			success : function ( dat ){
				if( dat != 'ok' ){
					alert( dat );
					$( obj ).val('');
					return false;	
				}
			}
		});
	}

	function saveInvoiceDetail() {
		var is_new_row = 0;
		var product_model, piece_barcode, pieces_per_pack, pack_barcode, pieces_per_box, box_barcode;
		var box_recived, pieces_recived, product_part_number, product_serie, product_location_status, 
		product_location, row_detail_id;
  //valida que los campos no esten vacios y que los datos sean los mismos	
	//modelo del producto
		product_model = $( '#product_model' ).val().trim();
		if( product_model == '' && ! $( '#product_model_null' ).prop( 'checked' ) 
			&& $( '#db_product_model' ).val().trim() != '' 
		){
			alert( "El modelo del producto no puede ir vacío, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#product_model' ).focus();
			return false;
		}else if( $( '#db_product_model' ).val().trim() != product_model ){
			is_new_row = 1;
		}
	//codigo de barras pieza
		piece_barcode = $( '#piece_barcode' ).val().trim();
		if( piece_barcode == '' && ! $( '#piece_barcode_null' ).prop( 'checked' )
			&& $( '#db_piece_barcode' ).val().trim() != ''  ){
			alert( "El código de barras no puede ir vacío, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#piece_barcode' ).focus();
			return false;
		}else if( $( '#db_piece_barcode' ).val().trim() != piece_barcode ){
			is_new_row = 1;
		}
	//piezas por paquete
		pieces_per_pack = $( '#pieces_per_pack' ).val().trim();
		if( pieces_per_pack == '' && ! $( '#pieces_per_pack_null' ).prop( 'checked' )
			&& $( '#db_pieces_per_pack' ).val().trim() != '' ){
			alert( "Las piezas por paquete no pueden ir vacías, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#pieces_per_pack' ).focus();
			return false;
		}else if( $( '#db_pieces_per_pack' ).val().trim() != pieces_per_pack ){
			is_new_row = 1;
		}
	//codigo de barras paquete
		pack_barcode = $( '#pack_barcode' ).val().trim();
		if( pack_barcode == '' && ! $( '#pack_barcode_null' ).prop( 'checked' )
			&& $( '#db_pack_barcode' ).val().trim() != '' ){
			alert( "El código de barras del paquete no puede ir vacío, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#pack_barcode' ).focus();
			return false;
		}else if( $( '#db_pack_barcode' ).val().trim() != pack_barcode ){
			is_new_row = true;
		}
	//piezas por caja
		pieces_per_box = $( '#pieces_per_box' ).val().trim();
		if( pieces_per_box == '' && ! $( '#pieces_per_box_null' ).prop( 'checked' )
			&& $( '#db_pieces_per_box' ).val().trim() != '' ){
			alert( "Las piezas por caja no pueden ir vacías, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#pieces_per_box' ).focus();
			return false;
		}else if( $( '#db_pieces_per_box' ).val().trim() != pieces_per_box ){
			is_new_row = true;
		}
	//codigo de barras caja
		box_barcode = $( '#box_barcode' ).val().trim();
		if( box_barcode == '' && ! $( '#box_barcode_null' ).prop( 'checked' )
			&& $( '#db_box_barcode' ).val().trim() != '' ){
			alert( "El código de barras de la caja no puede ir vacío, si no tiene este dato marque la casilla 'No tiene'" );
			$( '#box_barcode' ).focus();
			return false;
		}else if( $( '#db_box_barcode' ).val().trim() != box_barcode ){
			is_new_row = true;
		}
	//cajas recibidas
		box_recived = $( '#received_packs' ).val().trim();
		if( box_recived < 0 || box_recived == '' ){
			alert( "Las cajas recibidas no pueden ser menor a cero" );
			$( '#received_packs' ).val( 0 );
			$( '#received_packs' ).select();
			return false;
		}
	//piezas sueltas recibidas
		pieces_recived = $( '#received_pieces' ).val().trim();
		if( pieces_recived < 0 || pieces_recived == '' ){
			alert( "Las piezas recibidas no pueden ser menor a cero" );
			$( '#received_pieces' ).val( 0 );
			$( '#received_pieces' ).select();
			return false;
		}
		if( box_recived <= 0 && pieces_recived <= 0 ){
			alert( "No se puede recibir el producto en ceros, ponga una cantidad válida de cajas y/o piezas sueltas" );
			if( box_recived <= 0 ){
				$( '#received_packs' ).focus();
				$( '#received_packs' ).select();
			}else if( pieces_recived <= 0 ){
				$( '#received_pieces' ).focus();
				$( '#received_pieces' ).select();
			}
			return false;
		}
		//valida que las piezas por caja sean mayor a cero
		if( ( pieces_per_box <= 0 || pieces_per_box == '' ) && box_recived > 0 ){
			alert( "Si va a recibir cajas primero ingrese el número de Piezas por caja!" );
			$( '#pieces_per_box' ).focus();
			return false;
		}

	//partida del producto
		product_part_number = $( '#product_part_number' ).val().trim();
		if( product_part_number <= 0 || product_part_number == '' ){
			alert( "La partida del producto no pueden ser menor a uno" );
			$( '#product_part_number' ).select();
			return false;
		}
	//serie del producto
		product_serie = $( '#product_serie' ).val();
		if( product_serie == 0 ){
			alert( "La serie del producto no puede ir vacía!" );
			$( '#product_serie' ).select();
			return false;
		}
	//estatus de ubicación del producto
		product_location_status = $( '#location_status_source' ).val();
		if( product_location_status == 0 ){
			alert( "El estatus de ubicación del producto no puede ir vacío!" );
			$( '#location_status_source' ).focus();
			return false;
		}
	//ubicacion del producto
		product_location = $( '#product_location_source' ).val();
		if( product_location == 0 && product_location_status > 1 ){
			alert( "La ubicación del producto no puede ir vacía!" );
			$( '#product_location_source' ).focus();
			return false;
		}
	//id del detalle
		row_detail_id = $( '#reception_detail_id' ).val();
		//alert( 'is_new_row : ' + is_new_row + ' , pp : ' + $( '#product_provider' ).val() );
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'saveInvoiceDetail',
				pk : $( '#product_id' ).val(),
				pp : (is_new_row == 1 ? '' : $( '#product_provider' ).val() ),
				model : product_model,
				pz_bc : piece_barcode,
				pzs_x_pack : pieces_per_pack,
				pack_bc : pack_barcode,
				pzs_x_box : pieces_per_box,
				box_bc : box_barcode,
				box_rec : box_recived,
				pieces_rec : pieces_recived,
				product_p_num : product_part_number,
				product_serie : product_serie,
				is_new : is_new_row,
				location_status : product_location_status,
				location : product_location,
				detail_id : row_detail_id
			},
			success : function ( dat ){
				var response = dat.split( '|' );
				if( response[0] == 'ok' ){
					alert( 'Producto registrado exitosamente, puede verfificarlo en la Sección "Listado"' );
					clean_product_form();
					desactivate_product_form();
					$( '#product_seeker' ).focus();
					var new_detail = JSON.parse( response[1] );
					//console.log( new_detail[0].id_recepcion_bodega_detalle );
					invoices[ product_serie ]['invoice_detail'].push( new_detail[0] );
					build_invoices_lists();
					build_invoices_lists_finish();
				}else{
					alert( "Error : " + dat );
				}
				is_row_edition = 0;//resetea indicador de edición
			}
		});
	}

//limpia el formulario de recepcion de productos
	function clean_product_form(){
		$( '#product_id' ).val('');
		$( '#product_provider' ).val('');
		$( '#product_model' ).val('');
		$( '#product_model_null' ).val('');
		$( '#piece_barcode' ).val('');
		$( '#db_piece_barcode' ).val('');
		$( '#piece_barcode_null' ).removeAttr('checked');
		$( '#pieces_per_pack' ).val('');
		$( '#db_pieces_per_pack' ).val('');
		$( '#pieces_per_pack_null' ).removeAttr('checked');
		$( '#pack_barcode' ).val('');
		$( '#db_pack_barcode' ).val('');
		$( '#pack_barcode_null' ).removeAttr('checked');
		$( '#pieces_per_box' ).val('');
		$( '#db_pieces_per_box' ).val('');
		$( '#pieces_per_box_null' ).removeAttr('checked');
		$( '#box_barcode' ).val('');
		$( '#db_box_barcode' ).val('');
		$( '#box_barcode_null' ).removeAttr('checked');
		$( '#received_packs' ).val('');
		$( '#received_pieces' ).val('');
		$( '#product_part_number' ).val('');
		$( '#product_serie' ).val('');
		$( '.product_name' ).html('');
		$( '.product_model' ).html('');
	//limpia formulario de ubicaciones	
		$( "#location_status_source option[value='2']" ).text( "Ubicación actual : " );
		$( "#product_location_source" ).val('');
		$("#location_status_source option[value='0']").attr("selected", 'true');

		$( '#product_part_number' ).empty();
		$( '#reception_detail_id' ).val( '' );
		$( '#btn_parts_edition' ).attr( 'disabled', 'true' );
	}

	function activate_product_form(){
		$( '#piece_barcode' ).removeAttr('disabled');
		$( '#product_model' ).removeAttr('disabled');
		$( '#product_model_null' ).removeAttr('disabled');
		$( '#db_piece_barcode' ).removeAttr('disabled');
		$( '#piece_barcode_null' ).removeAttr('disabled');
		$( '#pieces_per_pack' ).removeAttr('disabled');
		$( '#db_pieces_per_pack' ).removeAttr('disabled');
		$( '#pieces_per_pack_null' ).removeAttr('disabled');
		$( '#pack_barcode' ).removeAttr('disabled');
		$( '#db_pack_barcode' ).removeAttr('disabled');
		$( '#pack_barcode_null' ).removeAttr('disabled');
		$( '#pieces_per_box' ).removeAttr('disabled');
		$( '#db_pieces_per_box' ).removeAttr('disabled');
		$( '#pieces_per_box_null' ).removeAttr('disabled');
		$( '#box_barcode' ).removeAttr('disabled');
		$( '#db_box_barcode' ).removeAttr('disabled');
		$( '#box_barcode_null' ).removeAttr('disabled');
		$( '#received_packs' ).removeAttr('disabled');
		$( '#received_pieces' ).removeAttr('disabled');
		$( '#product_part_number' ).removeAttr('disabled');
		$( '#product_serie' ).removeAttr('disabled');

		$( '#product_seeker_btn' ).css( 'display', 'none' );
		$( '#product_reset_btn' ).css( 'display', 'block' );
		$( '#product_seeker' ).attr( 'disabled', 'true' );

		$( "#location_status_source" ).removeAttr( 'disabled' );

	}

	function desactivate_product_form(){
		$( '#piece_barcode' ).attr('disabled', 'true');
		$( '#product_model' ).attr('disabled', 'true');
		$( '#product_model_null' ).attr('disabled', 'true');
		$( '#product_model_null' ).removeAttr('checked');
		$( '#db_piece_barcode' ).attr('disabled', 'true');
		$( '#piece_barcode_null' ).attr('disabled', 'true');
		$( '#pieces_per_pack' ).attr('disabled', 'true');
		$( '#db_pieces_per_pack' ).attr('disabled', 'true');
		$( '#pieces_per_pack_null' ).attr('disabled', 'true');
		$( '#pack_barcode' ).attr('disabled', 'true');
		$( '#db_pack_barcode' ).attr('disabled', 'true');
		$( '#pack_barcode_null' ).attr('disabled', 'true');
		$( '#pieces_per_box' ).attr('disabled', 'true');
		$( '#db_pieces_per_box' ).attr('disabled', 'true');
		$( '#pieces_per_box_null' ).attr('disabled', 'true');
		$( '#box_barcode' ).attr('disabled', 'true');
		$( '#db_box_barcode' ).attr('disabled', 'true');
		$( '#box_barcode_null' ).attr('disabled', 'true');
		$( '#received_packs' ).attr('disabled', 'true');
		$( '#received_pieces' ).attr('disabled', 'true');
		$( '#product_part_number' ).attr('disabled', 'true');
		$( '#product_serie' ).attr('disabled', 'true');

		$( '#product_seeker_btn' ).css( 'display', 'block' );
		$( '#product_reset_btn' ).css( 'display', 'none' );
		$( '#product_seeker' ).removeAttr( 'disabled' );

		$( "#location_status_source" ).attr( 'disabled', 'true' );
		$( '#reception_detail_id' ).val( '' );
		$( '#btn_parts_edition' ).attr( 'disabled', 'true' );
	}

	function build_invoices_lists(){//obj_destinity
		//var dats;
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/builder.php',
			cache : false,
			data : { fl : 'buildInvoiceList',
					series : series
			},
			success : function ( dat ){
				$('#invoices_lists_container').html( dat );
				//console.log(dat);
			}
		});
	}
	function build_invoices_lists_finish(){//obj_destinity
		//var dats;
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/builder.php',
			cache : false,
			data : { fl : 'buildInvoiceListFinish',
					series : series
			},
			success : function ( dat ){
				$('#finish_invoices_container').html( dat );
				//console.log(dat);
			}
		});
	}
	
	function editDetail( detail_id ){
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'getRecepcionDetail',
					id : detail_id
			},
			success : function ( dat ){
				var new_detail = JSON.parse(dat)
				//console.log( new_detail, new_detail[0].c_b_pieza);	
				//$( '.source' ).click();	

				$('.mnu_item.active').removeClass('active');
				$( '.source' ).addClass('active');
				$( '.content_item' ).css( 'display', 'none' );
				$( '.invoices_products' ).css( 'display', 'block' );	
			
			//setea los valores de los campos

				$( '#product_id' ).val( new_detail[0].id_producto );
				$( '.product_name' ).html( new_detail[0].nombre );
				$( '.product_model' ).html( new_detail[0].modelo );

				$( '#piece_barcode' ).val( new_detail[0].c_b_pieza );
				$( '#db_piece_barcode' ).val( new_detail[0].c_b_pieza );
				if( new_detail[0].c_b_pieza == '' || new_detail[0].c_b_pieza == null ){
					$( '#piece_barcode_null' ).prop( 'checked', 'true' );				
				}
				
				$( '#product_model' ).val( new_detail[0].modelo );
				$( '#db_product_model' ).val( new_detail[0].modelo );
				if( new_detail[0].modelo == '' || new_detail[0].modelo == null ){
					$( '#product_model_null' ).prop( 'checked', 'true' );				
				}

				$( '#pieces_per_pack' ).val( new_detail[0].piezas_por_paquete );
				$( '#db_pieces_per_pack' ).val( new_detail[0].piezas_por_paquete );
				if( new_detail[0].piezas_por_paquete == '' || new_detail[0].piezas_por_paquete == null ){
					$( '#pieces_per_pack_null' ).prop( 'checked', 'true' );				
				}

				$( '#pack_barcode' ).val( new_detail[0].c_b_paquete );
				$( '#db_pack_barcode' ).val( new_detail[0].c_b_paquete );
				if( new_detail[0].c_b_paquete == '' || new_detail[0].c_b_paquete == null ){
					$( '#pack_barcode_null' ).prop( 'checked', 'true' );				
				}

				$( '#pieces_per_box' ).val( new_detail[0].piezas_por_caja );
				$( '#db_pieces_per_box' ).val( new_detail[0].piezas_por_caja );
				if( new_detail[0].piezas_por_caja == '' || new_detail[0].piezas_por_caja == null ){
					$( '#pieces_per_box_null' ).prop( 'checked', 'true' );				
				}

				$( '#box_barcode' ).val( new_detail[0].c_b_caja );
				$( '#db_box_barcode' ).val( new_detail[0].c_b_caja );
				if( new_detail[0].c_b_caja == '' || new_detail[0].c_b_caja == null ){
					$( '#box_barcode_null' ).prop( 'checked', 'true' );				
				}

				$( '#received_packs' ).val( new_detail[0].cajas_recibidas );
				$( '#received_pieces' ).val( new_detail[0].piezas_sueltas_recibidas );
				$( '#product_part_number' ).empty();
				$( '#product_part_number' ).append( '<option value="' + new_detail[0].numero_partida + '">' + new_detail[0].numero_partida + '</option>' );
				$( '#product_serie' ).val( new_detail[0].serie );
			
			//ubicacion del producto
				if( new_detail[0].ubicacion_almacen != '' ){
					$( "#location_status_source option[value=2]" ).text( "Ubicación actual : " + new_detail[0].ubicacion_almacen );
				}else{
					$( "#location_status_source option[value=2]" ).text( "No tiene ubicación" );
				}
				$("#location_status_source option[value='" + new_detail[0].id_status_ubicacion + "']").attr("selected", 'true');
				$( '#product_location_source' ).val( new_detail[0].ubicacion_almacen );
			//id del detalle
				$( '#reception_detail_id' ).val( new_detail[0].id_recepcion_bodega_detalle );
				//alert( 'val : ' + $( '#reception_detail_id' ).val() );
			//activa el formulario
				activate_product_form();
			//cambia funcion del botón de cambiar producto
				$( '#product_reset_btn' ).attr( 'onclick', 'activate_change_product();' );
				/*$( '#product_seeker_btn' ).css( 'display', 'block' );
				$( '#product_reset_btn' ).css( 'display', 'none' );
				$( '#product_seeker' ).removeAttr( 'disabled' );*/

			}
		});
	}
//
	var is_row_edition = 0;
	function activate_change_product(){
		$( '#product_seeker' ).removeAttr( 'disabled' );
		is_row_edition = 1;//marca edicion de registro
	}
//buscador de ubicacion de productos
	function seekProductsLocations( obj ){
		var txt = $( obj ).val();
		if( txt.length <= 2 ){
			$( '.product_location_seeker_response' ).html();
			$( '.product_location_seeker_response' ).css( 'display', 'none' );
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'seekProductsLocations',
					key :  txt
			},
			success : function ( dat ){
				$( '.product_location_seeker_response' ).html( dat );
				$( '.product_location_seeker_response' ).css( 'display', 'block' );
			}
		});
	}

	
	function change_location( type ){
		var location = $( '#location_status_' + type + ' option:selected' ).text().split(':');
		location = location[1];
		if( $( '#location_status_' + type ).val() != 2 ){
			location = '';
		}
		if ( type == 'source' ) {
			$( '#product_location_' + type ).val( location );
		}else{
			$( '#product_location_' + type ).val( location )
		}
		if( $( '#location_status_' + type ).val() == 3 ){
			$( '#new_location_form_' + type ).css( 'height', 'auto' );
		}else{
			$( '#new_location_form_' + type ).css( 'height', '0' );
		}
	}
	function make_new_location( type ){
		var letter, location, row_from, row_until, final_location='';
		letter = $( '#aisle_' + type ).val();
		if( letter == '' ){
			alert( "El pasillo no puede ir vacío!" );
			$( '#aisle_' + type ).focus();
			return false;
		}
		final_location += letter;
		location = $( '#location_number_' + type ).val();
		if( location == '' ){
			alert( "La ubicacion no puede ir vacía!" );
			$( '#location_number_' + type ).focus();
			return false;
		}
		final_location += '-' + location;
		row_from = $( '#aisle_from_' + type ).val();
		if( row_from != '' ){
			final_location += ' f/p' + row_from;
			row_until = $( '#aisle_until_' + type ).val();	
			if( row_until != '' ){
				final_location += '-' + row_until;
			}
		}
		$( '#product_location_' + type ).val( final_location );
		$( "#location_status_" + type + " option[value=2]" ).text( "Ubicación actual : " + final_location );
		$( '#new_location_form_' + type ).css( 'height', '0' );
	//limpia los campos
		$( '#aisle_' + type ).val( '' );
		$( '#location_number_' + type ).val( '' );
		$( '#aisle_from_' + type ).val( '' );
		$( '#aisle_until_' + type ).val( '' );
	}

	function saveNewLocation(){
		if( $( "#location_status_seeker" ).val() == 3 ){
			make_new_location( 'seeker' );
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'changeProductLocation',
					p_k : $( '#product_id_location_form_seeker' ).val(),
					new_location : $( '#product_location_seeker' ).val(),
					new_status : $( "#location_status_seeker" ).val()
			},
			success : function ( dat ){
				if( dat != 'ok' ){
					alert( "Error : \n" + dat );
					return false;
				}else{
					//cleanProductLocationForm();//limpia formulario de ubicacion de productos
					alert( "Los cambios fueron guardados exitosamente!" );
				}
			}
		});
	}

	function setProductLocation( location_array ){
	//	alert(location_array);
		var location_data = location_array.split( '~' );
		$( '#product_id_location_form_seeker' ).val( location_data[0] );
		$( '#product_name_location_form_seeker' ).html( location_data[1] );//nombre
		$( '#product_inventory_recived' ).val( location_data[2] );
		$( '#product_inventory_no_ubicated' ).val( location_data[3] );
	//oculta resultados de busqueda
		$( '.product_location_seeker_response' ).html( '' );
		$( '.product_location_seeker_response' ).css( 'display', 'none' );
		$( '#location_status_seeker' ).removeAttr( 'disabled' );
		$( '#seeker_product_location' ).val( '' );
		$( '#product_location_seeker' ).val( location_data[5] );
		if( location_data[4] != '' ){
			$( "#location_status_seeker option[value=2]" ).text( "Ubicación actual : " + location_data[5] );
		}else{
			$( "#location_status_seeker option[value=2]" ).text( "No tiene ubicación" );
		}
		$( "#location_status_seeker").val( ( location_data[5] != '' && location_data[4] == 3 ? 2 : location_data[4] ) );

		$( '#product_seeker_location_form_btn' ).css( 'display', 'none' );
		$( '#product_reset_location_form_btn' ).css( 'display', 'block' );
	}

	function cleanProductLocationForm(){
		$( '#product_id_location_form_seeker' ).val( '' );
		$( '#product_id_location_form_seeker' ).attr( 'disabled', 'true' );
		$( '#product_name_location_form_seeker' ).html( '' );
		$( '#product_name_location_form_seeker' ).attr( 'disabled', 'true' );
		$( '#product_inventory_recived' ).val( '' );
		$( '#product_inventory_recived' ).attr( 'disabled', 'true' );
		$( '#product_inventory_no_ubicated' ).val( '' );
		$( '#product_inventory_no_ubicated' ).attr( 'disabled', 'true' );
		$( '#location_status_seeker' ).val( 0 );
		$( '#location_status_seeker' ).attr( 'disabled', 'true' );
		$( '#product_location_seeker' ).val( '' );
		$( '#product_location_seeker' ).attr( 'disabled', 'true' );

		$( '#product_seeker_location_form_btn' ).css( 'display', 'block' );
		$( '#product_reset_location_form_btn' ).css( 'display', 'none' );
		
	}


	function change_invoices_status(){
		var req = '';
		$( '#tbody_finish tr' ).each( function ( index ){
			if( index > 0 ){
				req += '|~|';
			}
			$(this).children("td").each(function (index2) {
				if( index2 == 0 ){
					req += $( this ).html() + '~';
				}else if( index2 == 5 ){
					req += $( '#status_' + index ).val();
				}
			});
		});
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'changeInvoicesStatus',
					data : req
			},
			success : function ( dat ){
				var aux = dat.split( '|' );
				if( aux[0] != 'ok' ){
					alert( "Error : \n" + dat );
					return false;
				}else{
					alert( aux[1] );
					location.reload();
				}
			}
		});
	}

	function edit_parts_number(){
		$( '.number_part_aux' ).css( 'display', 'flex' );
		$( '#product_part_number_aux' ).focus();

	}

	function save_edition_parts(){
		var new_serie = $( '#product_part_number_aux' ).val().trim();
		if( new_serie.length <= 0 || new_serie <= 0 ){
			alert( "El número de serie no puede ir vacío y debe de ser mayor a cero." );
			$( '#product_part_number_aux' ).focus();
			return false;
		}
		new_serie = parseInt( new_serie );
		var serie = $( '#product_serie' ).val();
		var serie_number = parseInt( $( '#' + serie + '_parts_number' ).html().trim() );
		
		if( new_serie > serie_number ){
			alert( "El número de partida para la serie " + serie + " no puede ser mayor a " + serie_number );//+ ' ( ' + new_serie + ' )'
			$( '#product_part_number_aux' ).select();
			return false;
		}
		$( '#product_part_number_aux' ).val( '' );
		$( '#product_part_number' ).append( '<option value="' + new_serie + '">' + new_serie + '</option>' );
		$( '#product_part_number' ).val( new_serie );
		$( '.number_part_aux' ).css( 'display', 'none' );

		new_serie = '';
	}

	function ajaxR(url){
		if(window.ActiveXObject)
		{		
			var httpObj = new ActiveXObject("Microsoft.XMLHTTP");
		}
		else if (window.XMLHttpRequest)
		{		
			var httpObj = new XMLHttpRequest();	
		}
		httpObj.open("POST", url , false, "", "");
		httpObj.send(null);
		return httpObj.responseText;
	}

