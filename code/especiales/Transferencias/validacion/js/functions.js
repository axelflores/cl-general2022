	var current_transfers = new Array();
	var global_pieces_quantity = 0;
	var audio_is_playing = false;
	var global_is_box_barcode = 0;
//mostrar / ocultar vistas del menú
	function show_view( obj, view ){
		if( current_transfers.length <= 0 && ( view == '.transfers_products' || view == '.resume' ) ){
			alert( "Seleccione las transferencia a Validar desde el Listado!" );
			close_emergent();
			return false;
		}
		$('.mnu_item.active').removeClass('active');
		$( obj ).addClass('active');
		$( '.content_item' ).css( 'display', 'none' );
		$( view ).css( 'display', 'block' );
		close_emergent();
		$( '#btn_finish_validation' ).css( 'display', ( view == '.resume' ? 'inline-block' : 'none' ) );
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
	function close_emergent( obj_to_clean = null, obj_to_focus = null ){
	//cierra emergente
		$( '.emergent_content' ).html( '' );
		$( '.emergent' ).css( 'display', 'none' );
		
		global_pieces_quantity = 0;
		
		if( obj_to_clean != null ){
			$( obj_to_clean ).val('');
		}
		if( obj_to_focus != null ){
			$( obj_to_focus ).focus();
		}
	}

	function set_transfers(){
		if( current_transfers.length > 0 ){
			if( !confirm( "Ya hay transferencias validandose, Realmente desea validar nuevas transferencias?" ) ){
				return false;
			}
			current_transfers = new Array();
		}
		var transfer_to_show = '<h5>Las siguientes transferencias serán verificadas :</h5>';
			var transfer_to_set = '<table class="table table-striped table-bordered">';
		transfer_to_show += '<table class="table table-striped table-bordered">';
			transfer_to_set += '<thead><tr><th>Folio</th><th>Destino</th></tr></thead><tbody>';
		transfer_to_show += '<thead><tr><th>Folio</th><th>Destino</th><th>Status</th></tr></thead><tbody>';
		$( '#transfers_list_content tr' ).each( function(index){
			if( $( '#validate_' + index ).prop( 'checked' ) == true ){
				$( this ).children( 'td' ).each( function (index2){
					if( index2 == 0 ){
						current_transfers.push( parseInt( $( this ).html().trim() ) );
					}else if( index2 == 1 ){
						transfer_to_show += '<tr><td>' + $( this ).html() + '</td>' ;
							transfer_to_set += '<tr><td>' + $( this ).html() + '</td>' ;
					}else if( index2 == 3 ){
						transfer_to_show += '<td>' + $( this ).html() + '</td>';
							transfer_to_set += '<td>' + $( this ).html() + '</td></tr>';
					}else if( index2 == 4 ){
						transfer_to_show += '<td>' + $( this ).html() + '</td></tr>';
					}
				});
			}
		});
		transfer_to_show += '</tbody></table><br />';
			transfer_to_set += '</tbody></table><br />';
		build_transfers_to_validate( transfer_to_set );
		transfer_to_show += '<div class="row">';
		transfer_to_show += '<div class="col-2"></div>';
		transfer_to_show += '<div class="col-8">';
			transfer_to_show += '<button onclick="show_view( \'.mnu_item.source\', \'.transfers_products\' );" class="btn btn-success form-control">';
				transfer_to_show += 'Aceptar';
			transfer_to_show += '</button>';
			transfer_to_show += '</div>'; 
		transfer_to_show += '</div>'; 
		$( '.emergent_content' ).html( transfer_to_show );
		$( '.emergent' ).css( 'display', 'block' );
		loadLastValidations();
		load_resumen();
	}

	function load_resumen(){
		var response = ajaxR( 'ajax/db.php?fl=getResumeHeader&transfers=' + current_transfers + '&type=1'  );
		$( '.group_card.adjustments.differences' ).html( response );

		response = ajaxR( 'ajax/db.php?fl=getResumeHeader&transfers=' + current_transfers + '&type=2'  );
		$( '.group_card.adjustments.aggregates' ).html( response );
	}
	function build_transfers_to_validate( content ){
		$( '.accordion-body.transfers' ).html( content );
	}
//var global_permission_box = 0;
var global_tmp_barcode = '';
//validacion de códigos de barras
	function validateBarcode( obj, e, permission = null, pieces = null, permission_box = null ){
		var key = e.keyCode;
		if( key != 13 && e != 'enter' )
			return false;
		alert_scann();

		if( $( obj ).val().length <= 0 && global_tmp_barcode == '' ){
			alert( "El código de barras no puede ir vacío!" );
			$( obj ).focus();
			return false;
		}

		var txt = $( obj ).val().trim();
		global_tmp_barcode = ( global_tmp_barcode == '' && permission_box != null && txt != '' ? txt : global_tmp_barcode );
		var url = "ajax/db.php?fl=validateBarcode";
		url += "&transfers=" + current_transfers;

		url += "&barcode=" + txt/*( global_permission_box != 0 ? global_tmp_barcode : txt )*/;
		
		if( global_pieces_quantity != 0){
			url += "&pieces_quantity=" + global_pieces_quantity;
		}else if( pieces != null ){
			url += "&pieces_quantity=" + pieces;
		}
		if( permission != null ){
			url += "&manager_permission=1";
		}

		if( permission_box != null ){//|| global_permission_box == 1 
			//global_permission_box = 1;
			url += "&permission_box=" + permission_box;
			//global_permission_box = 0;//oscar
		}else{
			//global_permission_box = 0;
		}

//	alert( url );
		var response =  ajaxR( url );
		//alert( response );
		var ax = response.split( '|' );
//		$( obj ).val( '' );
		/*global_permission_box = 0;
		global_tmp_barcode = '';*/
		if( ax[0] == 'seeker' ){
			$( '#seeker_response' ).html( ax[1] );
			$( '#seeker_response' ).css( 'display' , 'block' );
			return false;
		}
		$( '#seeker_response' ).css( 'display' , 'block' );//oculta resultado de búsqueda

		if( ax[0] == 'emergent' && ax[1] != 'is_box_code'){
			//alert( 'is_not_box_code' + ax[1] );
		//muestra contenido
			$( '.emergent_content' ).html( ax[1] );
			$( '.emergent' ).css( 'display', 'block' );
			return false;
		}
		if( ax[1] == 'is_box_code' ){//&& permission_box == null
			//alert( 'is_box_code' );
		//código de barras de caja
			//global_permission_box = 0;
			$( '.emergent_content' ).html( ax[2] );
			$( '.emergent' ).css( 'display', 'block' );
			$( '#tmp_sell_barcode' ).focus();
			//$( '#barcode_seeker' ).val( '' );
			return false;
		}
		
		//alert( 'pasa' );
		$( '.emergent_content' ).html( ax[1] );
		$( '.emergent' ).css( 'display', 'block' );	
		
		if( ax[0] == 'ok' ){
			loadLastValidations();
			load_resumen();
			$( obj ).val( '' );
			global_pieces_quantity = 0;
			//global_permission_box = 0;
			global_tmp_barcode = '';
			$( '#barcode_seeker' ).val( '' );
			$( '#barcode_seeker' ).focus();
		}
	}

	function setPiecesQuantity(  ){
		global_pieces_quantity = $( '#pieces_quantity_emergent' ).val();
		/*if( barcode == '' || barcode == null ){
			alert( "El código de barras no puede ir vacío" );
		}*/		
		if( global_pieces_quantity <= 0 ){
			alert( "El número de piezas debe ser mayor a Cero!" );
			$( '#pieces_quantity_emergent' ).val( 1 );
			$( '#pieces_quantity_emergent' ).select();
			return false;
		}
		validateBarcode( '#barcode_seeker', 'enter', null, global_pieces_quantity );
	}

	function setProductByName( product_id ){
		$( '#seeker_response' ).html( '' );
		$( '#seeker_response' ).css( 'display' , 'none' );//oculta resultado de búsqueda
		var url = "ajax/db.php?fl=getOptionsByProductId&product_id=" + product_id;
		var response = ajaxR( url );
		$( '.emergent_content' ).html( response );
		$( '.emergent' ).css( 'display', 'block' );
	}
	
	function setProductModel(){
		var model_selected = 0;
		$( '#model_by_name_list tr' ).each( function ( index ){
			if( $( '#p_m_5_' + index ).prop( 'checked' ) ){
			//	alert( index );
				model_selected = $( '#p_m_5_' + index ).val();
			}
		});
		if( model_selected == 0 ){
			alert( "Debe de seleccionar un modelo para continuar!" );
			return false;
		}else{
			$( '.emergent_content' ).html( '' );
			$( '.emergent' ).css( 'display', 'none' );
			$( '#barcode_seeker' ).val( model_selected.trim() );
			validateBarcode( '#barcode_seeker', 'enter' );
		}
	}

	function loadLastValidations(){
		var url = "ajax/db.php?fl=loadLastValidations&transfers=" + current_transfers;
		var response =  ajaxR( url );
	//alert( response );
		$( '#last_validations' ).html( response );
	}
//confirma envio de excedente
	function confirm_exceeds(){
	//valida el password del encargado
		var pss = $( '#manager_password' ).val();
		if( pss.length <= 0 ){
			alert( "La contraseña del encargado no puede ir vacía!" );
			$( '#manager_password' ).focus();
			return false;
		}
		var url = 'ajax/db.php?fl=validateManagerPassword&pass=' + pss;
		var response = ajaxR( url );
		if( response != 'ok' ){
			alert( response );
			$( '#response_password' ).html( response );
			$( '#response_password' ).css( 'display', 'block' );
		 	$( '#manager_password' ).select();
			return true;
		}
		validateBarcode( '#barcode_seeker', 'enter', 1 );
	}
//regresar el excedente
	function return_exceeds(){
		var return_instructions = '<h5>Aparte este producto de la transferencia para que no sea enviado a la Sucursal!</h5>';
		return_instructions += '<div class="row">';
			return_instructions += '<div class="col-2"></div>';
			return_instructions += '<div class="col-8">';
				return_instructions += '<button class="btn btn-warning form-control" onclick="close_emergent( \'#barcode_seeker\', \'#barcode_seeker\' );">';
					return_instructions += 'Aceptar';
				return_instructions += '</button>';
			'</div>';
		return_instructions += '<div>';
		$( '.emergent_content' ).html( return_instructions );
	}
//agregar proveedor-producto en transferencias
	function save_new_supply( product_id, product_provider, box, pack, piece ){
	//obtiene el valor de la contraseña
		var pss = $( '#manager_password' ).val();
		if( pss.length <= 0 ){
			alert( "La contraseña del encargado es obligatoria!" );
			$( '#manager_password' ).focus();
			return false;
		}
	
		var url = 'ajax/db.php?fl=validateManagerPassword&pass=' + pss;
		var response = ajaxR( url );
		if( response != 'ok' ){
			alert( response );
			$( '#response_password' ).html( response );
			$( '#response_password' ).css( 'display', 'block' );
		 	$( '#manager_password' ).select();
			return true;
		}
	//agrega el registro en la base de datos
		url = "ajax/db.php?fl=insertNewProductValidation&p_id=" + product_id;
		url += "&p_p_id=" + product_provider + "&box=" + box;
		url += "&pack=" + pack + "&piece=" + piece + "&transfers=" + current_transfers;
		//alert( url );
		response = ajaxR( url );
		//alert( response );
	}
//finaliza la validacion de la trsnferencia
	function finish_validation(){
		if( $( '#validation_resume_1 tr' ).length > 0 ){
			alert( "No se puede terminar la validación de las Transferencias.\nAún hay registros pendientes de validar! " );
			$( '.group_card.adjustments.differences' ).css( 'border', '1px solid red' );
			$( '.group_card.adjustments.differences' ).css( 'background-color', 'orange' );
			setTimeout( function(){
				$( '.group_card.adjustments.differences' ).css( 'border', 'none' );	
				$( '.group_card.adjustments.differences' ).css( 'background-color', 'white' );
			}, 3000 );
			return false;
		}
		var response = ajaxR( 'ajax/db.php?fl=saveValidation&transfers=' + current_transfers );
		var ax = response.split( '|' );
		if( ax[0] == 'ok' ){
			alert( ax[1] );
			location.reload();
		}else{
			alert( "Error : \n" + response );
		}
	}

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
//guardar ajuste de inventario
	function save_adjustment(){
		var data_request_substraction = '', data_request_addition = '', 
			data_request_ok = '';
		var validation_failed = false;
		$( '#inventoryAdjudments tr' ).each( function( index ){
			if( $( '#adjustment_7_' + index ).val().trim() < 0
			|| $( '#adjustment_7_' + index ).val().trim() == '' ){
				validation_failed = '#adjustment_7_' + index;
				return false;
			}
			if( parseInt( $( '#adjustment_8_' + index ).html().trim() ) < 0 ){
				data_request_substraction += ( data_request_substraction != '' ? '|' : '' );
				data_request_substraction += $( '#adjustment_1_' + index ).html().trim();//id de registro
				data_request_substraction += '~' + $( '#adjustment_2_' + index ).html().trim();//id de producto
				data_request_substraction += '~' + $( '#adjustment_3_' + index ).html().trim();//id de proveedor producto
				data_request_substraction += '~' + ( parseInt( $( '#adjustment_8_' + index ).html().trim() ) * -1 );//cantidad para ajustar
			}else if( parseInt( $( '#adjustment_8_' + index ).html().trim() ) > 0 ){
				data_request_addition += ( data_request_addition != '' ? '|' : '' );
				data_request_addition += $( '#adjustment_1_' + index ).html().trim();//id de registro
				data_request_addition += '~' + $( '#adjustment_2_' + index ).html().trim();//id de producto
				data_request_addition += '~' + $( '#adjustment_3_' + index ).html().trim();//id de proveedor producto
				data_request_addition += '~' + parseInt( $( '#adjustment_8_' + index ).html().trim() );//cantidad para ajustar
			}else{
				data_request_ok += ( data_request_ok != '' ? '|' : '' );
				data_request_ok += $( '#adjustment_1_' + index ).html().trim();//id de registro
			}
		});
		if( validation_failed != false ){
			alert( "Aún hay inventarios sin ajustar\nVerifique y vuelva a intentar!" );
			$( validation_failed ).focus();
			return false;
		}
		/*alert( data_request_rest );
		alert( data_request_sum );
		alert( data_request_ok );*/
		var url = 'ajax/db.php?fl=inventoryAdjustment';
		url += '&addition=' + data_request_addition;
		url += '&substraction=' + data_request_substraction;
		url += '&data_ok=' + data_request_ok;
		var response = ajaxR( url );
		$( '.emergent_content' ).html( response ); 
		$( '.emergent' ).css( 'display', 'block' ); 
	}

	function sow_adjustemt_locations( counter ){
		var resp = '<table class="table table-striped table-bordered">';
			resp += '<thead>';
				resp += '<tr>';
					resp += '<th width="20%">#</th>';
					resp += '<th width="80%">Ubicación</th>';
				resp += '</tr>';
			resp += '</thead>';
			resp += '<tbody>';
		var array = $( '#adjustment_9_' + counter ).html().trim().split('~');
			for (var i = 0; i < array.length; i++) {
				resp += '<tr>';
					resp += '<td>' + ( array[i] != 'No hay ubicaciones registradas' ? ( i + 1 ) : '' ) + '</td>';
					resp += '<td>' + array[i] + '</td>';
				resp += '</tr>';
			}
			resp += '</tbody>';
		resp += '</table>';

		resp += '<p align="center">';
			resp += '<button class="btn btn-success" onclick="close_emergent();">';
				resp += '<i class="icon-ok-cirlce">Aceptar</i>';
			resp += '</button>';
		resp += '</p>';

		$( '.emergent_content' ).html( resp );
		$( '.emergent' ).css( 'display', 'block' );
		
	}
	
	function build_adjustemnts_locations(){

	}

	function calculate_adjustment_differece( counter ){
		var virtual_inventory = parseInt( $( '#adjustment_6_' + counter ).html().trim() );
		var physical_inventory = parseInt( $( '#adjustment_7_' + counter ).val().trim() );
		if ( physical_inventory < 0 ){
			alert( "El inventario físico no puede ser menor a cero!" );
			$( '#adjustment_7_' + counter ).val( 0 );
			$( '#adjustment_7_' + counter ).select();
			return false;
		}
		var differece = parseInt( physical_inventory - virtual_inventory );

		$( '#adjustment_8_' + counter ).html( differece );
	}	

//var getFormAssignTransfer = ajaxR( "php/formAssignTransfer.php?p_k=" + transfer_id );
	//llamadas asincronas
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

