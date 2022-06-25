//var global_current_ticket;
//mostrar / ocultar vistas del menú
	function show_view( obj, view ){
		/*if( assignment == 0 && view == '.supply' ){
			alert( "Seleccione la transferencia a Surtir desde el Listado!" );
			return false;
		}*/
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
					location.href="../../";
				}
			break;
		}
	}

	function close_emergent( obj_clean = null, obj_focus = null){
		$( '.emergent_content' ).html( '' );
		$( '.emergent' ).css( 'display', 'none' );
		if( obj_clean != null ){
			$( obj_clean ).val( '' );
		}
		if( obj_focus != null ){
			$( obj_focus ).focus();
		}
	}

	function seekTicketBarcode( e, obj, type ){
		if( e.keyCode != 13 && e != 'enter' ){
			return false;
		}
		var txt = $( obj ).val();
		if( txt.length == 0 || txt == '' ){
			alert( "El código de barras no puede ir vacío!" );
			$( obj ).focus();
			return false;
		}
		alert_scann(); 
		$( obj ).val( '' );
		$( obj ).focus();
		var url = 'ajax/db.php?fl=' + type + '&barcode=' + txt;		
		if( type == 'seekProductBarcode' ){
			url += '&ticket_id=' + localStorage.getItem( 'current_ticket' );
		}
		//alert( url ); return false;
		var response = ajaxR( url );
		var aux = response.trim().split( '|' );
		if( type == 'seekTicketBarcode' ){
			if( aux[0] == 'ok' ){
				setTicket( aux[1] );
			}else{
				$( '.emergent_content' ).html( response );
				$( '.emergent' ).css( 'display', 'block' );
				$( '.emergent' ).focus();
			}
		}else if( type == 'seekProductBarcode'){
			if( aux[0] == 'ok' ){
				load_ticketDetail();//recarga el detalle
				$( '.emergent_content' ).html( aux[1] );
				$( '.emergent' ).css( 'display', 'block' );
			}else{
				$( '.emergent_content' ).html( response );
				$( '.emergent' ).css( 'display', 'block' );
				$( '.emergent' ).focus();
			}
		}

	}

	function focus_again( obj ){
		//alert();
		setTimeout( function (){
				document.getElementById( 'barcode_seeker' ).focus();
				document.getElementById( 'barcode_seeker' ).select();
			}, 100
		);
		return false;
	}

	function setTicket( ticket_id ){
		localStorage.setItem( 'current_ticket', ticket_id );
	//carga detalle del ticket
		$( '#check_ticket_detail' ).click();
		load_ticketDetail();
	}

	function load_ticketDetail(){
	//piezas por validar
		var url = "ajax/db.php?fl=getTicketDetail&p_k=" + localStorage.getItem( 'current_ticket' );
		url += "&type=pending";
//	alert( url );
		var response = ajaxR( url );
		$( '#pending_validation' ).html( response );
	//piezas validadas
		var url = "ajax/db.php?fl=getTicketDetail&p_k=" + localStorage.getItem( 'current_ticket' );
		url += "&type=validated";
//alert( url );
		var response = ajaxR( url );
		$( '#validated' ).html( response );
	}

	function finish_validation(){
		if( $( '#pending_validation tr' ).length > 0 ){
			alert( "Aún hay productos pendientes de validar!" );
			return false;
		}else{
			var url = "ajax/db.php?fl=finishValidation&p_k=" + localStorage.getItem( 'current_ticket' );
			var response = ajaxR( url );
			$( '.emergent_content' ).html( response );
			$( '.emergent' ).css( 'display', 'block' );

		//libera el id de ticket
			localStorage.setItem( 'current_ticket', null );
		}	
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