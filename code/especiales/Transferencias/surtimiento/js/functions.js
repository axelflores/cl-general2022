//declaracion de variables
	var assignment = 0;
	var current_transfer = 0;
	var current_product_id;
	var boxes = 0;
	var packs = 0;
	var pieces = 0;
	var manager_password = 0;
	var p_p_in_edition = 0;
	var audio_is_playing = false;
	var global_is_edition = 0;
	var global_destination_txt = '';
	var global_level = '';
//mostrar / ocultar vistas del menú
	function show_view( obj, view ){
		if( assignment == 0 && ( view == '.supply' || view == '.list_supplied' ) ){
			alert( "Primero selecciona la transferencia a Surtir!" );
			return false;
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
					location.href="../../../../index.php";
				}
			break;
		}
	}

	function pack_off( id, transfer_id, detail_id = null, destination = null, level = null ){
		if( destination != null ){
			global_destination_txt = destination;
			global_level = level;
		}
			$( '#transfer_destination' ).html( global_destination_txt );
			$( '#transfer_level' ).html( global_level );
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { 
					fl : 'getAssignmentDetail', 
					p_k : id,
					supply_detail_id : ( detail_id == null ? '' : detail_id )
			},
			success : function ( dat ){
				if( dat == 'no_rows' ){
					alert( "La Transferencia ya se terminó de surtir, elija otra Transferencia" );
					$( '.mnu_item.invoices' ).click();
					location.reload();
					return true;
				}
			//transferencia en reasignación
				var aux = dat.split( '|' );
				if( aux[0] == 'exception' ){
					$( '.emergent_content' ).html( aux[1] );
					$( '.emergent' ).css( 'display', 'block' );
					setTimeout( function (){
						close_emergent();
						pack_off( id, transfer_id );
					}, 10000);
					return true;
				}
				assignment = id;
				current_transfer = transfer_id;
				//console.log(dat);
			//decodifica JSON	
				var response = JSON.parse( dat );
					$( '#current_transfer_product_id' ).val( response.row_id ); 
					$( '#product_name' ).html(response.name );
					$( '#product_model' ).html( response.provider_model );
					$( '#product_location' ).val( response.product_location );
					$( '.product_boxes_quantity_txt' ).html( response.boxes );
					boxes = parseInt( response.boxes );
					//$( '#product_boxes_quantity' ).removeAttr( 'readonly' );
					$( '.product_packs_quantity_txt' ).html( response.packs );
					packs = parseInt( response.packs );
					//$( '#product_packs_quantity' ).removeAttr( 'readonly' );
					$( '.product_pieces_quantity_txt' ).html( response.pieces );
					pieces = parseInt( response.pieces );
					//$( '#product_pieces_quantity' ).removeAttr( 'readonly' );
					$( '#current_product_id' ).val( response.product_id );
					current_product_id = response.product_id;

					$( '#current_product_provider' ).val( response.product_provider_id );

				//total de piezas
					$( '#product_pieces_total' ).val( response.total_pieces_quantity );
					$( '#current_user_tracking_id' ).val( 0 );

					$( '#current_supply_id' ).val( response.supply_id );
				
				if( detail_id == null ){
					global_is_edition = 0;
					$( '#current_supply_detail_id' ).val( response.supply_detail_id );
					
					$( '#supply_btn_continue' ).css('display', 'block');
					$( '#supply_btn_other_presentation' ).css('display', 'block');
					$( '#supply_btn_delete' ).css('display', 'none');
				}else{
					global_is_edition = 1;
					$( '#product_boxes_quantity' ).val( response.boxes );
					$( '#product_packs_quantity' ).val( response.packs );
					$( '#product_pieces_quantity' ).val( response.pieces );
					//$( '#product_pieces_total' ).val( response.total );

					$( '#current_supply_detail_id' ).val( response.supply_detail_id );
					$( '#supply_btn_delete' ).css( 'display', 'block' );
					$( '#current_user_tracking_id' ).val( response.user_transfer_tracking_id );
					
					$( '#supply_btn_continue' ).css('display', 'block');
					$( '#supply_btn_other_presentation' ).css('display', 'block');
					$( '#supply_btn_delete' ).css('display', 'block');

				//	alert( 'here' );
				}

				$( '#complete' ).removeAttr( 'checked' );
				$( '#incomplete' ).removeAttr( 'checked' );
				build_list_supplied();
			//muestra la vista para surtir mercancía
				$( '.mnu_item.source' ).click();
				$( '#product_seeker' ).focus();
			//oculta radios
				$( '.supply_instructions' ).css( 'display', 'none' );
			}
		});
	}
	function edit_specific_detail( detail_id ){
//alert( detail_id);
		pack_off( assignment, current_transfer, detail_id );
	}

	function build_list_supplied(){
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { 
					fl : 'buildListSupplied',
					id : assignment
				},
			success : function ( dat ){
				//alert( dat ); 
				$( '#list_assignmets_supplied' ).html( dat );
			}
		});
	}

	function show_next_steep( obj, type ){
		switch ( type ){
			case 1 : //surtitmiento completo
				$( '#product_boxes_quantity' ).val( boxes );
				$( '#product_packs_quantity' ).val( packs );
				$( '#product_pieces_quantity' ).val( pieces );

				/*$( '#product_boxes_quantity' ).prop( 'readonly', true );
				$( '#product_packs_quantity' ).prop( 'readonly', true );
				$( '#product_pieces_quantity' ).prop( 'readonly', true );*/
				$( '#supply_btn_continue' ).css( 'display', 'block' );
				$( '#supply_btn_other_presentation' ).css( 'display', 'none' );
			break;

			case 2 : //piezas incompletas
				$( '#product_boxes_quantity' ).val( 0 );
				$( '#product_packs_quantity' ).val( 0 );
				$( '#product_pieces_quantity' ).val( 0 );
				/*$( '#product_boxes_quantity' ).removeAttr( 'readonly' );
				$( '#product_packs_quantity' ).removeAttr( 'readonly' );
				$( '#product_pieces_quantity' ).removeAttr( 'readonly' );*/
				$( '#supply_btn_continue' ).css( 'display', 'none' );
				$( '#supply_btn_other_presentation' ).css( 'display', 'block' );
			break;
		}
	}
//
	function getProductModels(){
		var product_id = current_product_id;
		if( product_id <= 0 ){
			alert( "No hay producto por recibir!" );
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { 
					fl : 'getProductModels',
					p_k : product_id,
					p_p : $( '#current_product_provider' ).val(),
					b : $( '#product_boxes_quantity' ).val(),
					pa : $( '#product_packs_quantity' ).val(),
					pi : $( '#product_pieces_quantity' ).val(),
					is_edition : global_is_edition,
					transfer_id : current_transfer
				},
			success : function ( dat ){
				$( '.emergent_content' ).html( '<br/><div class="row">' 
					+ '<p align="center">Total de piezas : <b class="orange">' + $( '#product_pieces_total' ).val() + '</b><p>'
					+ $( '.supply_instructions_information' ).html() + '</div>' + dat );
				$( '.emergent' ).css( 'display', 'block' );
			}
		});
	}
	
	function edit_p_p_ceil( number, counter ){
		if( p_p_in_edition != 0 ){
			desedit_p_p_ceil();
			return false;
		}
		p_p_in_edition = '#p_p_' + number + '_' + counter;
		var p_p_tmp = $( p_p_in_edition ).html();
		$( p_p_in_edition ).html('<input type="number" class="form-control" id="p_p_tmp" onblur="desedit_p_p_ceil();">');
		$( '#p_p_tmp' ).val( p_p_tmp );
		$( '#p_p_tmp' ).focus();
	}

	function desedit_p_p_ceil(){
		var new_value = ( $( '#p_p_tmp' ).val() == '' ? 0 : $( '#p_p_tmp' ).val() ); 
		$( p_p_in_edition ).html( new_value );
		recalculate_supply_subtotal(  );
		p_p_in_edition = 0;
	}

	function recalculate_supply_subtotal(  ){
			var total = 0;
			var subtotal = 0;
			$( '#product_provider_models tr' ).each( function (index) {
				//var aux_1 = $( '#p_p_1_' + index ).html().trim();
				var aux_boxes = parseInt( $( '#p_p_2_' + index ).html().trim() * $( '#p_p_6_' + index ).html().trim() );
				var aux_packs = parseInt( $( '#p_p_3_' + index ).html().trim() * $( '#p_p_7_' + index ).html().trim() );
				var aux_pieces = parseInt( $( '#p_p_4_' + index ).html().trim() );
				var aux_subtotal = aux_boxes + aux_packs + aux_pieces;
				$( '#p_p_5_' + index ).html( aux_subtotal );
				//alert( 'validation :' + $( '#p_p_9_' + index ).attr('barcode_validated') );
				if( aux_subtotal > 0 && $( '#p_p_9_' + index ).attr( 'barcode_validated' ) == 0 ){
					$( '#p_p_9_' + index ).attr( 'need_validation' , '1' );
					$( '#p_p_9_icon_' + index ).css( 'display', 'block' );
					$( '#p_p_9_icon_' + index ).css( 'color', 'gray' );
				}else if( aux_subtotal <= 0 ){
					$( '#p_p_9_' + index ).removeAttr( 'need_validation' );
					$( '#p_p_9_icon_' + index ).css( 'display', 'none' );
					$( '#p_p_9_icon_' + index ).css( 'color', 'black' );
				}
				subtotal += aux_subtotal;
			});
			$( '#product_supply_total' ).html( subtotal );
	}

	function seek_barcode_form_multiple( e ){
		if( e != 'enter' && e.keyCode != 13){
			return false;
		}
		var txt = $( '#tmp_pp_barcodes' ).val();
		if( txt == '' ){
			alert( "El código de barras no puede ir vacío!" );
			$( '#tmp_pp_barcodes' ).focus();
			return false;
		}
	//recorre la tabla para buscar concidencias
		var aux;
		var is_in_list = 0;
		$( '#product_provider_models tr' ).each( function (index) {
			aux = $( '#p_p_9_' + index ).html().trim().split('|');
			for( var i = 0; i < aux.length; i++ ){
				if( aux[i] != '' && aux[i] == txt ){
					$( '#p_p_9_icon_' + index ).css( 'display', 'block' );
					$( '#p_p_9_icon_' + index ).css( 'color', 'green' );
					$( '#p_p_9_' + index ).attr( 'barcode_validated', '1' );
					is_in_list = 1;
					$( '#tmp_pp_barcodes' ).val( '' );
					//alert( "Validado exitosamente!" );
					$( '#tmp_pp_barcodes' ).focus();
				}
			}
		});
		if( is_in_list == 0 ){
			alert( "El código de barras no corresponde a ninguno de los modelos surtidos.\nVerifique y vuelva a intentar!" );
			$( '#tmp_pp_barcodes' ).focus();
		}
	}

	function saveProductSupplie( is_multiple = null ){
		var product_id, product_provider_id;
		var request_data = ''; 
		if( is_multiple == null ){
			boxes = $( '#product_boxes_quantity' ).val();
			packs = $( '#product_packs_quantity' ).val();
			pieces = $( '#product_pieces_quantity' ).val();
			request_data += $( '#current_transfer_product_id' ).val();//transfer_product
			request_data += '~' + current_product_id;
			request_data += '~' + $( '#current_product_provider' ).val();//p_p_id
			request_data += '~' + boxes + '~' + packs + '~' + pieces ;//is complete
			request_data += '~' + $( '#current_supply_id' ).val();
			request_data += '~' + $( '#current_supply_detail_id' ).val();
		}else{
			var finish = false;
			$( '#product_provider_models tr' ).each( function (index) {
				var aux_1 = $( '#p_p_1_' + index ).html().trim();
				var aux_2 = $( '#p_p_2_' + index ).html().trim();
				var aux_3 = $( '#p_p_3_' + index ).html().trim();
				var aux_4 = $( '#p_p_4_' + index ).html().trim();
				if( aux_2 > 0 || aux_3 > 0 || aux_4 > 0 ){
				//valida que no haya códigos de barras por validar
					if( $( '#p_p_9_' + index ).attr( 'barcode_validated') != 1
					&& $( '#p_p_9_' + index ).attr( 'need_validation' ) == 1 ){
						finish = true;
						//return false;
					}
					if( request_data != '' ){
						request_data += '|~|';
					}

					request_data += $( '#current_transfer_product_id' ).val();//transfer_product	
					request_data += '~' + current_product_id;
					request_data += '~' + aux_1;//p_p_id
					request_data += '~' + aux_2 + '~' + aux_3 + '~' + aux_4 ;//is complete
					request_data += '~' + $( '#current_supply_id' ).val();
					request_data += '~' + $( '#current_supply_detail_id' ).val();
				}
			});
			if( finish ){
				alert( "Hay códigos de barras pendientes de validar, escaneé los faltantes y de click en aceptar" );
				$( '#tmp_pp_barcodes' ).focus();				
				return false;
			}

			if( $( '#manager_password' ).val().length <= 0 ){
				alert( "El password del encargado no puede ir vacío.\nPida al encargado que ingrese su password" );
				$( '#manager_password' ).focus();
				return false;
			}
		}
		var flag = ( global_is_edition == 0 ? 'saveProductSupplie' : 'saveProductSupplyEdition' )
		//alert( $( '#current_transfer_product_id' ).val() );// return false;
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { 
					fl : flag,
					request : request_data,
					request_password : $( '#manager_password' ).val(),
					transfer_id : current_transfer,
					is_edition : global_is_edition,
					edition_row_id : $( '#current_user_tracking_id' ).val(),
					original_product : ( is_multiple != null ? $( '#current_transfer_product_id' ).val() : 'no' )
			},
			success : function ( dat ){
				alert( dat );
				pack_off( assignment, current_transfer );
				if( is_multiple != null ){
					$( '.emergent_content' ).html();
					$( '.emergent' ).css( 'display', 'none' );
				}
			//limpia formulario
				cleanProductTransferForm();
			}
		});
	}

	function cleanProductTransferForm(){
		$( '#product_boxes_quantity' ).val( '' );
		$( '#product_packs_quantity' ).val( '' );
		$( '#product_pieces_quantity' ).val( '' );

		$( '#complete' ).removeAttr( 'checked' );
		$( '#incomplete' ).removeAttr( 'checked' );
	//oculta radios
		$( '.form_continue' ).css( 'display', 'none' );

	}


	function close_emergent(){
		$( '.emergent_content' ).html( '' );
		$( '.emergent' ).css( 'display', 'none' );
	}

/*Sección de busqueda de productos*/
	function seek_product( e, obj ){
		var key = e.keyCode;
		if( key != 13 ){
			return false;
		}
		alert_scann();
		if( $( obj ).val().trim().length <=0  ){
			alert( "El código de Barras no puede ir vacío!" );
			$( obj ).focus();
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'seekProduct', 
					key : $( obj ).val().trim(),
					p_id : $( '#current_product_id' ).val().trim(),
					p_p_id : $( '#current_product_provider' ).val().trim(),
					loc : $( '#product_location' ).val().trim(),
					model : $( '#product_model' ).html().trim()
			},
			success : function ( dat ){
				//alert( 'here' );
				$( '.supply_instructions' ).css( 'display', 'block' );
				if( dat == 'ok' ){
					setProduct();
					//$( '#complete' ).click();
				}else{
					$( '.emergent_content' ).html( dat );
					$( '.emergent' ).css( 'display', 'block');
					$( '.emergent_content' ).focus();
					//$( '.form_continue' ).css( 'display', 'flex' );
				}
				$( obj ).val( '' );
				$( '.form_continue' ).css( 'display', 'flex');
			}
		});
	}

	function setProduct(){
	//habilita boton para continuar
		$( '#supply_btn_continue' ).css( 'display', 'block' );
	}

//eliminar surtimiento en edición
	function deleteProductSupplie( permission = 0 ){

		$( '.manager_password_response' ).html( '' );
		$( '.manager_password_response' ).css( 'display', 'none' );
		
		if( permission == 0 ){
			build_manager_password( 'Pida la contraseña del encargado para continuar', 'deleteProductSupplie( 1 );' );
			return false;
		}else if( permission == 1 ){
	//valida la contraseña del encargado
			var pass = $( '#manager_password' ).val().trim();
			if( pass.length <= 0 || pass == '' ){
				alert( 'La contraseña no puede ir vacía!' );
				$( '#manager_password' ).focus();
				return false;
			}
			var url = "ajax/db.php?fl=checkManagerPassword&pss=" + pass;
			var response = ajaxR( url );
			//alert( 'response : ' + response );
			if( response.trim() != 'ok' ){
				$( '.manager_password_response' ).html( response );
				$( '.manager_password_response' ).css( 'display', 'block' );
				$( '#manager_password' ).select();
				return false;
			}else{
		//envia petición para eliminar el detalle del surtimiento
				url = "ajax/db.php?fl=deleteProductSupplie";
				url += "&row_id=" + $( '#current_user_tracking_id' ).val();
				url += "&transfer_detail_id=" + $( '#current_transfer_product_id' ).val();
				//url += "&product_provider_id=" + $( '#current_product_provider' ).val();
				response = ajaxR( url );
				alert( response );
				close_emergent();
				$( '.mnu_item.list.hidden' ).click();
			}
		}else{
			alert( "Permission denied!\nNo action to do..." );
		}
	}

	function build_manager_password( label, accept_click = '' ){
		var resp = '<br><br><h3>' + label + '</h3>';
		resp += '<div class="row">';
		resp += '<div class="col-2"></div>';
			resp += '<div class="col-8"><br><br>';
				resp += '<input type="password" id="manager_password" class="form-control"><br>';
				resp += '<p class="manager_password_response"></p>';
				resp += '<button class="btn btn-success form-control"';
					resp += ' onclick="' + accept_click +  '"';
				resp += '><i class="icon-ok-circle">Aceptar</i></button><br><br>';
				resp += '<button class="btn btn-danger form-control"';
				resp += ' onclick="close_emergent();"';
				resp += '><i class="icon-cancel-circled">Cancelar</i></button>';
			resp += '</div>';
		resp += '</div>';
		$( '.emergent_content' ).html( resp );
		$( '.emergent' ).css( 'display', 'block' );
		$( '#manager_password' ).focus();
	}

//audio de scanner
	function alert_scann(){
		if( audio_is_playing ){
			audio = null;
		}
		var audio = document.getElementById("audio");
		
		audio_is_playing = true;
		audio.currentTime = 0;
		audio.playbackRate = 1;
		audio.play();
	}

//llamadas asincronas
	function ajaxR( url ){
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

	function filter_list( obj ){
		var type = $( obj ).val();
		switch( type ){
			case '-1' :
				$( '.list_assignmets tr' ).each(function ( index ) {
					$( '#list_assignmet_' + index ).css( 'display', 'table-row' );
				});
			break;
			case '1':
				$( '.list_assignmets tr' ).each(function ( index ) {
					if( $( '#list_assignmet_' + index ).attr( 'priority' ) == 'URGENTE' ){
						$( '#list_assignmet_' + index ).css( 'display', 'table-row' );
					}else{
						$( '#list_assignmet_' + index ).css( 'display', 'none' );
					}
				});

			break;
			case '5':
				$( '.list_assignmets tr' ).each(function ( index ) {
					if( $( '#list_assignmet_' + index ).attr( 'priority' ) == 'NORMAL' ){
						$( '#list_assignmet_' + index ).css( 'display', 'table-row' );
					}else{
						$( '#list_assignmet_' + index ).css( 'display', 'none' );
					}
				});

			break; 
		}
	}

