//declaracion de variables
	var current_transfers = new Array();
//mostrar / ocultar vistas del menú
	function show_view( obj, view ){
		if( current_transfers.length == 0 && view == '.receive_transfers' ){
			alert( "Seleccione la(s) transferencia(s) a Recibir desde el Listado!" );
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
					location.href="../../../../index.php?";
				}
			break;

		}
	}

	function close_emergent(){
		$( '.emergent_content' ).html( '' );
		$( '.emergent' ).css( 'display', 'none' );
	}
//lanza emergente para confirmar transferencias por recibir
	function setTransferToReceive(){
		transfers_to_receive_info = '<div class="transfer_to_receive_container"><div class="row header_transfer_to_receive">'
			+ '<div class="col-6 text-center">Folio</div>'
			+ '<div class="col-6 text-center">Fecha</div>'
		+ '</div>';

		$(".transfers_list_content tr").each(function ( index ) {
			if( $( '#receive_' + index ).prop( 'checked' ) ){
				transfers_to_receive_info += '<div class="row">';
				$(this).children("td").each(function ( index2 ) {
					if( index2 == 0 ){
						current_transfers.push( $( this ).html() );
						transfers_to_receive_info += '<div class="no_visible">' + $( this ).html() + '</div>';
					}else if( index2 <= 2 ){
						transfers_to_receive_info += '<div class="col-6">' + $( this ).html() + '</div>';
					}	
				});
				transfers_to_receive_info += '</div>';
				transfers_to_receive_info += '</div>';
			}
		});
		
		$( '.emergent_content' ).html( 
			'<br /><br />'
			+ '<div style="min-height: 350px;"><p align="center">Las siguentes transferencias serán recibidas :<p>' 
				+ transfers_to_receive_info
				+ '<br />'
				+ '<div class="row">'
					+ '<div class="col-2"></div>'
					+ '<div class="col-8">'
						+ '<button onclick="show_view( \'.mnu_item.source\', \'.receive_transfers\' );close_emergent();" class="btn btn-success form-control">'
							+ 'Confirmar y continuar'
						+ '</button>'
					+'</div>'
					+ '<div class="col-2"></div>'
				+ '</div>'
			+ '</div>' );

		$( '.emergent' ).css( 'display', 'block' );	
		loadLastReceptions();
		receptionResumen( 1 );
		receptionResumen( 2 );
		receptionResumen( 3 );
	}

//validación de códigos de barras
	function validateBarcode( obj, e, permission = null, pieces = null ){
		var key = e.keyCode;
		if( key != 13 && e != 'enter' )
			return false;

		if( $( obj ).val().length <= 0 ){
			alert( "El código de barras no puede ir vacío!" );
			return false;
		}

		var txt = $( obj ).val().trim();
		var url = "ajax/db.php?fl=validateBarcode";
		url += "&transfers=" + current_transfers;
		url += "&barcode=" + txt;
		if( pieces != null ){
			url += "&pieces_quantity=" + pieces;
		}
		if( permission != null ){
			url += "&manager_permission=1";
		}
		//alert( url ); return false;
 		var response =  ajaxR( url );
		//alert( response );
		var ax = response.split( '|' );
		if( ax[0] == 'emergent' ){
		//formulario de piezas
			$( '.emergent_content' ).html( ax[1] );
			$( '.emergent' ).css( 'display', 'block' );
			return false;
		}
		//if( ax[0] == 'ok' ){//recarga los úlmtimos productos recibidos
			$( '.emergent_content' ).html( ax[1] );
			$( '.emergent' ).css( 'display', 'block' );	
			loadLastReceptions();
			receptionResumen( 1 );
			receptionResumen( 2 );
			receptionResumen( 3 );
	}
	function setPiecesQuantity(  ){
		var pieces = $( '#pieces_quantity_emergent' ).val();
		/*if( barcode == '' || barcode == null ){
			alert( "El código de barras no puede ir vacío" );
		}*/		
		if( pieces <= 0 ){
			alert( "El número de piezas debe ser mayor a Cero!" );
			$( '#pieces_quantity_emergent' ).val( 1 );
			$( '#pieces_quantity_emergent' ).select();
			return false;
		}
		validateBarcode( '#barcode_seeker', 'enter', null, pieces );
	}

	function save_new_reception_detail( product_id, product_provider_id, box, pack, piece ){
		var url = "ajax/db.php?fl=insertNewProductReception";
		url += "&transfers=" + current_transfers;
		url += "&p_id=" + product_id + "&p_p_id=" + product_provider_id;
		url += "&box=" + box + "&pack=" + pack + "&piece=" + piece;
		var response = ajaxR( url );
		alert( url );
	}
//
	function loadLastReceptions(){
		var url = "ajax/db.php?fl=loadLastReceptions&transfers=" + current_transfers;
		var response = ajaxR( url );
		$( '#last_received_products' ).html( response );
	}

	function getReceptionProductDetail( product_id, product_provider_id ){
		var url = 'ajax/db.php?fl=getReceptionProductDetail';
		url += '&p_id=' + product_id + "&p_p_id=" + product_provider_id;
		url += '&transfers=' + current_transfers;
		var response = ajaxR( url );
		$( '.emergent_content' ).html( response );
		$( '.emergent' ).css( 'display', 'block' );
	}

	function receptionResumen( type ){		
		var response_obj = "", counter_obj = "";
		var url = "ajax/db.php?fl=getReceptionResumen&transfers=" + current_transfers;
		url += "&type=" + type;
		var response = ajaxR( url );
		switch ( type ){
			case 1 : 
				response_obj = '#transfer_difference';
				counter_obj = '#transfer_difference_counter';
			break;
			case 2 :
				response_obj = '#transfer_excedent';
				counter_obj = '#transfer_excedent_counter';
			break;
			case 3 : 
				response_obj = '#transfer_return';
				counter_obj = '#transfer_return_counter';
			break;
		}
		var aux = response.split( '|' );
		$( counter_obj ).html( aux[0] );
		$( response_obj ).html( aux[1] );
		//$( '#last_received_products' ).html( response );
	}
//resumen del detalle ( resolucion )
	function show_resumen_detail( transfer_id, transfer_product_id, product_id, type ){
		var url = 'ajax/db.php?fl=getProductResolution&t_id=' + transfer_id;
		url += '&t_p=' + transfer_product_id + '&p_id=' + product_id + '&type=' + type;
		var response = ajaxR( url );
		$( '.emergent_content' ).html( response );
		$( '.emergent' ).css( 'display', 'block' );
	}
//inserta registros de resolución de transferencias
	function save_resolution ( type ){
		switch( type ){
			case 'missing' : 

			break;

		}
		var url = 'ajax/db.php?fl=saveResolutionRow';
		url += '&product_id=' + $( '#resolution_5_0' ).val();
		url += '&transfer_product_id=' + $( '#resolution_6_0' ).val();
		url += '&quantity=' + $( '#resolution_4_0' ).val();
		url += '&type=' + type;
//alert( url );
		var response = ajaxR( url );
		$( '.emergent_content' ).html( response );
		$( '.emergent' ).css( 'display', 'block' );
	}
	
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

