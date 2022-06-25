var antes="",temporal="",input_tmp="",foco=0,id_orden_compra,id_proveedor="";

/*Implemntación Oscar 11.02.2019 para buscar folios existentes*/
//función que busca el folio
	function seek_invoice( e, obj, type ){
		if( $( '#id_prov' ).val() == 0 ){
			alert( "Primero seleccione un proveedor" );
			$( '#id_prov' ).focus();
			return false;
		}
		var response_obj = '#' + type + '_response';
		if(e.keyCode==13 || e.keyCode==40){

		}
	//obtenemos el valor de la caja de texto del folio
		var busca_txt=$(obj).val().trim();
		if(busca_txt.length<=2){
			$( response_obj ).html("");
			$( response_obj ).css("display","none");
			return false;
		} 
		if(id_proveedor==""){
			id_proveedor=$("#id_prov").val();
		}
	//enviamos datos por ajax
		$.ajax({
			type:'post',
			url:'recPedBD.php',
			cache:false,
			data:{	
					flag :'busca_folios',
					txt : busca_txt,
					id_pro : id_proveedor,
					seeker_type : type
			},
			success: function(dat){
				//alert(dat + '\n' + response_obj);
				var aux=dat.split("|");
				//cargamos lo valores en el resultado de búsqueda
					$( response_obj ).html(aux[1]);
					$( response_obj ).css("display","block");
				/*if(aux[0]!='ok'){
					return false;
				}else{*/
				//cargamos lo valores en el resultado de búsqueda
					$( response_obj ).html(aux[1]);
					if(aux[1]!='sin coincidencias'){
						$( response_obj ).css("display","block");
					}else{
						$( response_obj ).css("display","none");
					}
				//}global_type
			}
		});
	}
var global_id_rec = '', global_folio = '', global_monto = '', global_pzas_rem = '';
var global_pzas_rec = '', global_status = '', global_type = '';
//función que carga el folio de la recepción
	function carga_folio_recepcion( id_rec, folio, monto, pzas_rem, pzas_rec, status, type, verification = 0 ){
		if( type == "receptions" && status == 3 && verification == 0 ){
		 	global_id_rec = id_rec; 
		 	global_folio = folio;
		 	global_monto =  monto;
		 	global_pzas_rem = pzas_rem;
		 	global_pzas_rec = pzas_rec;
			global_status = status;
			global_type = type;
			var label = 'La ' + (type == 'receptions' ? 'recepción' : 'remisión') + ' ya está finalizada, si deseas modificar ingresa tu contraseña : ';
			var accept_click = 'carga_folio_recepcion( \'' + global_id_rec + '\'';
			accept_click += ', \'' + global_folio + '\'';
			accept_click += ', \'' + global_monto + '\'';
			accept_click += ', \'' + global_pzas_rem + '\'';
			accept_click += ', \'' + global_pzas_rec + '\'';
			accept_click += ', \'' + global_status + '\'';
			accept_click += ', \'' + global_type + '\', 1 )';
			build_manager_password( label, accept_click );
			return false;
		}else if( type == "receptions" && status == 3 && verification == 1 ){
			var password = $( '#manager_password' ).val();
			if( password.length <= 0 ){
				alert( "La contraseña no puede ir vacía!" );
				$( '#manager_password' ).focus();
				return false;
			}
			var url = 'ajax/db.php?fl=checkUserPassword&pass=' + password;
			var response = ajaxR( url );
			if( response != 'ok' ){
				alert( response );
				$( '#manager_password' ).select();
				return false;
			}else{
				close_emergent();
			}
		}
		var response_obj = '#' + type + '_response';
		$( response_obj ).css("display","none");//ocultamos los resultados del folio
		
		if ( type != 'receptions' ){
			$("#ref_nota_1").val(folio);//asignamos el folio
			$("#monto_nota").val(monto);//asignamos el monto de la nota del proveedor
			$("#id_recepcion").val(id_rec);//asignamos el id de recpción en el campo oculto
			$("#pzas_remision").val(pzas_rem);//asignamos las piezas en remision
		//deshabilita campos
			$( '#ref_nota_1' ).attr( 'disabled', 'true' );
			$( '.add_remission' ).css( 'display', 'none' );
		//habilita campos
			$( '#ref_nota_2' ).removeAttr( 'disabled' );
			$( '.clean_remission' ).css( 'display', 'block' );
			
		}else{
			if( folio.toUpperCase() != $( '#ref_nota_1' ).val().toUpperCase() ){
				alert( "El folio de remisión y la Recepción deben de ser iguales, verifique y vuelva a intentar!" );
				$( '#ref_nota_2' ).val( '' );
				$( '#ref_nota_2' ).focus();
				return false;
			}
			$( '#ref_nota_2' ).val( folio );
			$( '#ref_nota_2' ).attr( 'disabled', 'true' );
			$( '#pzas_recibidas' ).val( pzas_rec );
			$( '#warehose_reception_id' ).val( id_rec );
			load_reception( id_rec );
			//$("#pzas_recibidas").val(pzas_rec);//asignamos las piezas recibidas
		}
		global_id_rec = ''; 
	 	global_folio = '';
	 	global_monto =  '';
	 	global_pzas_rem = '';
	 	global_pzas_rec = '';
		global_status = '';
		global_type = '';
	}

	function build_manager_password( label, accept_click = '' ){
		var resp = '<br><br><h3>' + label + '</h3>';
		resp += '<div class="row">';
		resp += '<div class="col-4"></div>';
			resp += '<div class="col-4"><br><br>';
				resp += '<input type="password" id="manager_password" class="form-control"><br>';
				resp += '<button class="btn btn-success form-control"';
					resp += ' onclick="' + accept_click +  '"';
				resp += '><i class="icon-ok-circle">Aceptar</i></button><br><br>';
				resp += '<button class="btn btn-danger form-control"';
				resp += ' onclick="close_emergent();"';
				resp += '><i class="icon-cancel-circled">Cancelar</i></button>';
			resp += '</div>';
		resp += '</div>';
		$( '.emergent_content' ).html( resp );
		$( '.emergente' ).css( 'display', 'block' );
		$( '#manager_password' ).focus(); 
	}
//carga el detalle de la recepción
	function load_reception( reception_id ){
		$.ajax({
			type:'post',
			url:'ajax/db.php',
			cache:false,
			data:{ fl: 'getReceptionDetail', 
				id : reception_id,
				provider : $( '#id_prov' ).val()
			},
			success:function(dat){
				$( '#table_body' ).html( dat );
				$( '.delete_enc' ).css( 'display', 'none' );
				$( '.delete_row_container' ).css( 'display', 'none' );
				$( '.product_description' ).attr( 'width', '16%' );

			}
		});
	}

	function cleanRemission(){
		if( !confirm( "Revisar otra recepción si guardar los cambios de la actual?" ) ){
			return false;
		}
		$("#ref_nota_1").val( '' );//asignamos el folio
		$("#monto_nota").val( '' );//asignamos el monto de la nota del proveedor
		$("#id_recepcion").val( '' );//asignamos el id de recpción en el campo oculto
		$("#pzas_remision").val( '' );//asignamos las piezas en remision
	//habilita campos
		$( '#ref_nota_1' ).removeAttr( 'disabled' );
		$( '.add_remission' ).css( 'display', 'block' );
	//deshabilita campos
		$( '#ref_nota_2' ).attr( 'disabled', 'true' );
		$( '.clean_remission' ).css( 'display', 'none' );
		getProviderInvoices( null );
	}
/*fin de cambio oscar 11.02.2019*/

/*función que guarda la recepción de OC*/
	function guarda_recepcion( reload = true ){
	//validamos que este lleno el campo de referencia de nota
		var referencia=$("#id_recepcion").val();
		if(referencia==null||referencia==""||referencia==0){
			alert("Debe de escoger una remisión antes de guardar la recepción!!!");
			$("#ref_nota_1").select();
			return false;
		}
	/*implementacion Oscar 11.02.2019 para campo de monito de la nota*/
	//validamos que este lleno el campo de monto de nota
		var monto_nota=$("#monto_nota").val();
		if(referencia==null||referencia==""){
			alert("El campo de referencia de nota no puede ir vacío");
			$("#ref_nota_1").select();
			return false;
		}
		
		if( $( '#table_body tr' ).length <= 0 ){
			alert( "no hay registros para guardar!" );
			return false;
		}
	/*fin de cambio Oscar 11.02.2019*/

		id_orden_compra = $( "#id_oc" ).val();//sacamos el id de la orden de compra

		var tope = $( "#filas_totales" ).val();
		var datos = "";//declaramos la variable que guardará los datos
		var proveedor = $( "#id_prov" ).val();//capturamos el id del proveedor
	//recorremos la tabla
		for( var i = 0; i <= tope; i++ ){
			if( document.getElementById( 'fila_' + i ) ){//si existe la fila
				if( document.getElementById( '10_' + i ).checked == true ){
					datos += "invalida~";
					datos += $( "#1_" + i ).html()+"~";//extraemos el id de producto
					datos += parseInt( $( "#3_" + i ).html().trim() );//extraemos la cantidad  pendiente
				}else{
				//extraemos datos
					datos+=$("#1_"+i).html()+"~";//extraemos el id de producto
				//piezas
					var tmp=0;
					tmp=parseInt($("#5_"+i).html().trim()*$("#4_"+i).html().trim());//extraemos cajas * presentación
					tmp+=parseInt($("#6_"+i).html());//extraemos la cantidad de piezas y le sumamos la cajas
					datos+=tmp+"~";

					datos+=$("#7_"+i).html().trim()+"~";//extraemos precio por pieza
					datos+=$("#8_"+i).html().trim()+"~";//extraemos el monto por producto
					datos+=$("#4_"+i).html().trim()+"~";//extraemos presentación por caja
				}//fin de else

				datos+=$("#11_"+i).html().trim()+"~";//extraemos el descuento
				if( $("#13_"+i).val() == 0 ){
					alert( "Hay productos sin proveedor, verifique y vuelva a intentar" );
					$("#13_"+i).focus();
					return false;
				}
				datos +=$("#13_"+i).val();//proveedor_producto
				datos += '~' + $("#0_"+i).html().trim();//id recepcion detalle
				datos += '~' + $("#-2_"+i).html().trim();//validado / no validado
				datos += '~' + $("#6_"+i).html().trim();//piezas sueltas recibidas
				datos += '~' + $("#5_"+i).html().trim();//cajas recibidas

				if( $( "#9_" + i ).html().trim() <= 0 ){
					alert( "Hay productos en cero, verifique y vuelva a intentar!" );
					$( "#9_" + i ).click();
					return false;
				}

				if(i<tope){
				//concatena el separador
					datos+="|";
				}
			}
		}//fin de for i
		//alert(datos);//return false;
	//extraemos el valor de la recepción de orden de compra
		var id_recepcion_oc = $("#id_recepcion").val();
		var reception_id = $( '#warehose_reception_id' ).val();
	//enviamos datos por ajax
		$.ajax({
			type : 'post',
			url : 'recPedBD.php',
			cache : false,
			data : {flag:2,
				oc : id_orden_compra,
				datos : datos,
				ref : referencia,
				id_prov : proveedor,
				id : id_recepcion_oc,
				mt_nota : monto_nota,
				reference_reception : reception_id
			},
			success: function(dat){
				var aux=dat.split("|");
				if(aux[0]!='ok'){
					alert("Error al guardar la orden de compra!!!\n"+dat);
					return false;
				}else{
					alert("Recepción guardada satisfactoriamente");
					if( reload == true ){
						location.reload();
					}
				}
			}
		});
	}
//finalización de la recepción
	function finish_reception( check_permission = null ){
		if( $( '#table_body tr' ).length <= 0 ){
			alert( "no hay registros para guardar!" );
			return false;
		}
		if( check_permission == null ){
			var label = 'Al finalizar la recepción se cambiarán los estados de la misma y ya no podrá ser';
			label += ' editada desde la pantalla de Recepción de Mercancía. <br>Si deseas continuar <b>Ingresa tu contraseña</b>';
			var accept_click = 'finish_reception( 1 )';
			build_manager_password( label, accept_click );
			return false;
		}else{
			var password = $( '#manager_password' ).val();
			if( password.length <= 0 ){
				alert( "La contraseña no puede ir vacía!" );
				$( '#manager_password' ).focus();
				return false;
			}
			var url = 'ajax/db.php?fl=checkUserPassword&pass=' + password;
			var response = ajaxR( url );
			if( response != 'ok' ){
				alert( response );
				$( '#manager_password' ).select();
				return false;
			}else{
				guarda_recepcion( false );
				close_emergent();
			}
		}

		var referencia=$("#id_recepcion").val();
		if(referencia==null || referencia =="" || referencia==0 ){
			alert("Debe de escoger una remisión antes de finalizar!!!");
			$("#ref_nota_1").select();
			return false;
		}
		var referencia_2 = $( '#warehose_reception_id' ).val();
		if( referencia_2 == null || referencia_2 == "" || referencia_2 == 0 ){
			alert("Debe de escoger una recepción antes de finalizar!!!");
			$("#ref_nota_1").select();
			return false;
		}
	//envia datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache: false,
			data : { fl : 'finishReception', id : referencia_2  },
			success : function ( resp ){
				var aux = resp.split( '|' );
				if( aux[0] != 'ok' ){
					alert( resp );
					return false;
				}
				alert( aux[1] );
				location.reload();
			} 
		});

	}

/*Función que edita celda*/
function editaCelda( flag, num, prefix = null, onblur_function = null ){
	if(foco==1){//validamos que no sea la misma celda
		return false;
	}
//formamos la caja de texto
	input_tmp='<input type="text" class="form-control" id="entrada_temporal" onkeyup="valida_acc(event,'+flag+','+num+');"' 
		+ ' onblur="deseditaCelda('+flag+','+num; 
		if( prefix != null ){
			input_tmp += (',\''+prefix+'\'');
		} 
		input_tmp += ( onblur_function != null ? ' ,\'' + onblur_function + '\'' : '' );
	input_tmp += ');';
input_tmp += '">';
//extraemos el valor de la celda
	antes=$("#"+ ( prefix == null ? '' : prefix ) +flag+"_"+num).html();
	$("#"+ ( prefix == null ? '' : prefix ) +flag+"_"+num).html(input_tmp);
	$("#entrada_temporal").val(antes);
	$("#entrada_temporal").select();
	foco=1;
}

/*Función que desedita celda*/
function deseditaCelda( flag, num, prefix = null ){
	temporal=$("#entrada_temporal").val();
	$("#"+ ( prefix == null ? '' : prefix ) +flag+"_"+num).html(temporal);
	foco=0;
//realizamos acciones dependiendo la caja de texto
	var subtotal=0,porcentaje_desc=0,subtotal_desc=0,total=0;
	if(flag==4||flag==5||flag==6||flag==7||flag==11){
	//cajas recibidas
		porcentaje_desc=$("#11_"+num).html();
		subtotal=parseFloat(($("#5_"+num).html().trim()*$("#4_"+num).html().trim())+parseFloat($("#6_"+num).html().trim()));
		subtotal_desc=subtotal*porcentaje_desc;
		total=subtotal-subtotal_desc;
	
		$("#9_"+num).html(subtotal);
//		$("#8_"+num).html(Math.round(total*parseFloat($("#7_"+num).html().trim()),2) );
		$("#8_"+num).html(Number((total*parseFloat($("#7_"+num).html().trim())).toFixed(2)));		
	}
	
	/*implementacion Oscar 06.09.2019 para no dejar recibir mas piezas de las pendientes spor recibir*/
		//alert($("#3_"+num).html()+"|"+$("#9_"+num).html());
		/*
DESAHBILITADO POR OSCAR 2022
		if( parseFloat($("#3_"+num).html())<parseFloat($("#9_"+num).html()) ){
			alert("No se pueden recibir mas piezas de las pendientes por recibir!!!");//\nPendientes:"+$("#3_"+num).html()+"\nRecibidas"+$("#9_"+num).html()
			$("#"+flag+"_"+num).html(0);
			$("#"+flag+"_"+num).click();
			return false;
		}*/
	/*Fin de cambio Oscar 06.09.2019*/
 
//mandamos el cambio por ajax
	if(flag==-1||flag==11){
		var fl_tmp='';
		
		if(flag==-1){fl_tmp='ubicacion';}
		if(flag==11){fl_tmp='descuento';}

		var val_id=$("#1_"+num).html();
		
		$.ajax({
			type:'post',
			url:'recPedBD.php',
			cache:false,
			data:{flag:fl_tmp,valor:temporal,id:val_id},
			success:function(dat){
				if(dat!='ok'){
					alert("Error al modificar la ubicacion del almacen en Matriz!!!"+dat);
					return false;
				}
			}
		});
	}
}

/*función que quita fila*/
function quitar_fila(num){
//marcamos el check correspondiente
	document.getElementById("10_"+num).checked=true;
//ocultamos la fila
	$("#fila_"+num).css("display","none");
	foco=0;//reseteamos el enfoque
	return true;
}

/*Función que valida acción en la celda*/
function valida_acc(e,flag,num){
	var tca=e.keyCode;
	var tope=$("#filas_totales").val();//sacamos el tamaño del grid

//si es tecla abajo o intro
	if(tca==40||tca==13){
		if(num==tope){
			$("#"+flag+"_"+num).select();
			return false;
		}
		$("#input_buscador").focus();
		$("#"+flag+"_"+parseInt(num+1)).click();
	}
//si es tecla arriba 
	if(tca==38){
		if(num==1){
			$("#"+flag+"_"+num).select();
			return false;
		}
		$("#input_buscador").focus();
		$("#"+flag+"_"+parseInt(num-1)).click();
	}
//si es tecla derecha
	if(tca==39){
		if(flag==11){
			$("#"+flag+"_"+num).select();
			return false;
		}
		$("#input_buscador").focus();
		if(flag<7){
			$("#"+parseInt(flag+1)+"_"+num).click();
		}else if(flag==7){
			$("#11_"+num).click();
		}
	}
//si es tecla izquierda 
	if(tca==37){
		if(flag==4){
			$("#"+flag+"_"+num).select();
			return false;
		}
		$("#input_buscador").focus();
		if(flag==11){
			$("#7_"+num).click();
		}else{
			$("#"+parseInt(flag-1)+"_"+num).click();
		}
	}

}


/**********************************************************FUNCIONES DEL BUSCADOR*************************************************************/
var opc_res=0;
/*Función que aciva el buscador*/
function busca_txt(e){
	orden_compra=$("#id_oc").val();
	var texto=$("#input_buscador").val();
	if(texto.length<=2){
		$("#res_busc").css("display","none");
		return false;
	}
	if(e.keyCode==13||e.keyCode==40){
	//enfocamos la primera opción
		resalta_opc(1);
		return false;
	}
//enviamos datos por ajax
	$.ajax({
		type:'post',
		url:'recPedBD.php',
		cache:false,
		data:{flag:1,oc:orden_compra,txt:texto},
		success: function(dat){
			var aux=dat.split("|");
			if(aux[0]!='ok'){
				alert("Error!!\n\n"+dat);
				return false;
			}else{
			//cargamos lo valores en el resultado de búsqueda
				$("#res_busc").html(aux[1]);
				$("#res_busc").css("display","block");
			}
		}
	});
}

/*función que valida tecla de buscador*/
function valida_opc(e,num){
	var tca=e.keyCode;
	if(tca==40){
		if(num<$("#opc_totales").val()){
		//recorremos hacia a abajo
			resalta_opc(parseInt(num+1));
		}
		return false;
	}
	if(tca==38){
		//recorremos hacia arriba
		if(num>1){
			resalta_opc(parseInt(num-1));
		}else{
			$("#input_buscador").select();
		}
		return false;
	}

	if(tca==13||e=='click'){
	//extraaemos el id del productro en la opción
		var valor_opc=$("#val_opc_"+num).html();
	//recorremos la tabla en busca del ´roducto
		var tope=$("#filas_totales").val();
		for(var i=1;i<=tope;i++){
			if($("#1_"+i).html().trim()==valor_opc){
				$("#res_busc").css("display","none");
				$("#input_buscador").val("");
				$("#fila_"+i).focus();
				$("#5_"+i).click();
				return true;
			}
		}
		$("#res_busc").css("display","none");
		$("#input_buscador").select();
		//alert("Este producto ya fue recibido completamente o cancelado!!!");
		if( confirm( "El producto no existe en el pedido con esta presentación; desea agregarlo? ") ){
			//insertProductOrder(  );
		}
	}
}

/*función que resalta opciones del buscador*/
function resalta_opc(num){
	if(opc_res!=0){
	//regresamos las propiedades de la opción resaltada
		$("#opc_"+opc_res).css("background","white");
		$("#opc_"+opc_res).css("color","black");
	}
//resaltamos la nueva opción
	$("#opc_"+num).css("background","rgba(92, 124, 14,.7)");
	$("#opc_"+num).css("color","white");
	$("#opc_"+num).focus();
//marcamos la nueva opción resaltada
	opc_res=num;
}
//cambiar proveedor
	/*function changeProvider( obj ){
		var val 
	}*/
/************************************Funciones de emergente************************************************************************************************************/
	
	function changeProductProvider( obj, product_id, count ){
		if( $( obj ).val() == -1 ){
			$( obj ).val( 0 );
			show_product_providers( product_id, count );
		}
	}

	function changeProvider( obj, counter ){
		$( "#p_p_2_1_" + counter ).attr( "value", $( obj ).val() );
	}

	function show_product_providers( product_id, count ){
		$.ajax({
			type : 'post',
			url : 'ajax/getProductProvider.php',
			cache : false,
			data : { p_k : product_id , 
				fl : 'getProviders',
				c : count,
				reception_detail_id : $( '#0_' + count ).html().trim()
			},
			success : function ( dat ){
				$( '.emergent_content' ).html( dat );
				$( '.emergente' ).css( 'display', 'block' );
			}
		});
	}

	function close_emergent(){
		$( '.emergent_content' ).html( '' );
		$( '.emergente' ).css( 'display', 'none' );

	}
/**/
	function add_row( type, table, product_id ){
		counter = $( table + ' tr' ).length;
	//envio de datos por ajax
		$.ajax({
			type : 'post',
			url : 'ajax/getProductProvider.php',
			cache : false,
			data : { current_counter : (counter - 1), fl : 'getRow', p_k : product_id },
			success : function ( dat ){
				$( table + ' tbody' ).append( dat );
			}
		});
	}
	function remove_product_provider( counter ){
	//omite registro
		if( $( '#pp_13_' + counter ).html().trim() != '' ){
			$.ajax({
				type : 'post',
				url : 'ajax/getProductProvider.php',
					cache : false,
					data : { fl : 'omitProductProvider', 
					p_k : $( '#pp_13_' + counter ).html().trim()
				},
				success : function ( dat ) {
					$( '#product_provider_' + counter ).remove();
					alert( dat );
				}

			});
		}else{
			$( '#product_provider_' + counter ).remove();
		}

	}
/*guardar proveedores - producto */
	function save_product_providers( type, table, product_id, count ){
	//	alert( 'save_product_providers : ' + count );
		var product_providers = '';
	//recorre el grid de datos
		counter = $( table + ' tr' ).length;
		$(table + " tr").each(function (index) {
			if( index > 0 ){
				product_providers += '|';
			}
			$(this).children("td").each(function (index2) {
				if( index2 <= 14 && ( index2 != 2 && index2 != 14 ) ){
					product_providers += ( index2 > 0 ? '~' : '' ) + $(this).html().trim();
					
				}else if( index2 == 2 || index2 == 14 ){
					product_providers += '~' + $( this ).attr( 'value' );
					//alert( .getElementsByTagName('input') );
				}/*else if(  ){
					product_providers += '~' + ( $(  ) )
				}*/
			});
		});
		//alert( product_providers ); return false;
	//	
		$.ajax({
			type : 'post',
			url : 'ajax/getProductProvider.php',
			cache : false,
			data : { fl : 'saveProductProviders', pp : product_providers, p_k : product_id },
			success : function ( dat ){
				//console.log( dat );
				alert( dat );
			//recarga el combo de proveedor-producto del producto
				reload_product_providers( product_id, count );
				//show_product_providers( product_id );
				close_emergent();
			}
		});
	}

	function reload_product_providers( product_id, counter ){
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'getProductProviders', 
					pp : $( '#id_prov' ).val(), 
					p_k : product_id,
					c : counter
			},
			success : function ( dat ){
				$( '#12_' + counter ).html( dat );
			}
		});
	}

	
	function getProviderInvoices( obj ){
		if( obj == null ){
			obj = $( '#id_prov' );
		}
	//
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'getProviderInvoices', provider : $( obj ).val() },
			success : function ( dat ){
				$( '#table_body' ).html( dat );
				if( $( obj ).val()!= 0 ){
					$( '#input_buscador' ).removeAttr( 'disabled' );
				}else{
					$( '#input_buscador' ).attr( 'disabled', 'true' );
				}			}
		});
	}
//agregar nueva remision
	function addRemission(){
		var provider_id = $( '#id_prov' ).val();
		if( provider_id <= 0  ){
			alert( "Primero seleccione un proveedor!" );
			$( '#id_prov' ).focus();
			return false;
		}
	//
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'getRemissionForm', provider : provider_id  },
			success : function ( dat ){
				$( '.emergent_content' ).html( dat );
				$( '.emergente' ).css( 'display', 'block' );
			}
		});
	}
//valida que el folio de remision no existe
	function validateNoRepeatRemission(){
		var invoice_reference = $( '#remission_invoice' ).val().trim();
		if( invoice_reference.length <= 0 ){
			return false;
		}	
	//
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'validateNoRepeatRemission', invoice : invoice_reference },
			success : function ( dat ){
				if( dat != 'ok' ){
					alert( dat );
					$( '#remission_invoice' ).val( '' );
				}
			}
		});
	}
//guardar nueva remision
	function save_remission(){
		var provider, invoice, amount, pieces_total, remision_date;
		provider = $( '#remission_provider_id' ).val();
		if( provider == '' ){
			alert( "El proveedor no puede ir vacío!" );
			$( '#remission_provider_id' ).focus();
			return false;
		}

		invoice = $( '#remission_invoice' ).val();
		if( invoice == '' ){
			alert( "El folio de la remisión no puede ir vacío!" );
			$( '#remission_invoice' ).focus();
			return false;
		}

		amount = $( '#remision_amount' ).val(); 
		if( amount <= 0 || amount == '' ){
			alert( "El monto de la remisión no puede ir vacío!" );
			$( '#remission_amount' ).focus();
			return false;
		}
		pieces_total = $( '#remision_pieces' ).val(); 
		if( pieces_total <= 0 || pieces_total == '' ){
			alert( "El numero de piezas de la remisión no puede ir vacío!" );
			$( '#remision_pieces' ).focus();
			return false;
		}

		date = $( '#remission_date' ).val();
		if( date == '' ){
			alert( "La fecha de la remisión no puede ir vacía!" );
			$( '#remission_date' ).focus();
			return false;
		}
		//alert( date ) ; return false;
		var ax = '';
	//
		$.ajax({
			type : 'post',
			url : 'ajax/db.php',
			cache : false,
			data : { fl : 'saveRemission', 
					provider_id : provider,
					remision_invoice : invoice,
					remission_amount : amount,
					remission_pieces : pieces_total,
					remission_date : date },
			success : function ( dat ){
				alert( dat );
				$( '.emergent_content' ).html( '' );
				$( '.emergente' ).css( 'display', 'none' );
			}
		});
	}

	var helpers = new Array();
	helpers[7] = "Número de piezas que fueron recibidas sueltas ( a granel o numero de piezas en caja que llega abierta )";

	function helper( number ){
		$( '.emergent_content' ).html( "<div class=\"helper_txt\">" + helpers[number] + "</div>" );
		$( '.emergente' ).css( 'display', 'block' );
		
	}

	function just_piece( obj, counter ){
		if( $( obj ).prop( 'checked' ) ){
			if( confirm( "El tratamiento por pieza desactivará el código por paquete, caja\nDesea continuar?" ) ){
				$( '#pp_4_' + counter ).html( '0' );
				$( '#pp_5_' + counter ).html( '0' );
				$( '#pp_9_' + counter ).html( '' );
				$( '#pp_11_' + counter ).html( '' );
				$( '#pp_14_' + counter ).attr( 'value', '1' );
			}else{
				$( obj ).removeAttr( 'checked' );
				$( '#pp_14_' + counter ).attr( 'value', '0' );
			}
		}else{
			$( '#pp_14_' + counter ).attr( 'value', '0' );
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





