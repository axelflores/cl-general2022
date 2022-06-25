<?php /* Smarty version 2.6.13, created on 2021-10-08 13:12:05
         compiled from especiales/Etiquetas/etiquetas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'especiales/Etiquetas/etiquetas.tpl', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div id="campos">  
<div id="titulo">1.3 Etiquetas</div>
<br><br>

<div id="filtros">
<div id='cosa2'>
<form action="">
	<ul class ='filters'>
		<li><label for="">Familias:</label></li>
		<li>
			<select id="categoria" class="filters" name='filtros' onchange="cambiaSC(this.value)">
				<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['vals'],'output' => $this->_tpl_vars['textos']), $this);?>

				<option value="-2">PAQUETES</option>
			</select>
		</li>
	
	
		<li><label>&nbsp;Tipos:</label></li>
		<li>
			<select id="tip" class="filters"  name='filtros' onclick="cambiaTP(this.value)">
				<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['vals2'],'output' => $this->_tpl_vars['textos2']), $this);?>

			</select>
		</li>
		<li><label>&nbsp;Subtipos:</label></li>
		<li>
			<select id="subtip" class="filters"  name='filtros'>
				<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['vals3'],'output' => $this->_tpl_vars['textos3']), $this);?>

			</select>
		</li>
		
	</ul>
	<div id='pli'>
		<li><label>Precio:</label></li>
		<ul class="filters">
		<li><label>De:</label><input type='number'  name='filtros' value="0" min="0"></li>
		<li><label>A:</label><input type='number'  name='filtros' value="0" min="0"></li>
		</ul>
		</div>
	<div id='buscador'>
		
        <ul><label>Producto:</label></ul>
        <ul class='filters'>
			<li><input type='hidden' id='proId' value='0'><input type="text" id='busca' onkeyup="buscaProd()"></li>
			<li> <input type="button" id='ag' value='Agregar' onclick="agregarListado()" disabled="true" style="padding:10px;"></li>
		</ul>	
	</div>
	<div id='listaProd'>
		<ul id='proLi'>
		</ul>
	</div>
	<div id='buscProd' class='ob' >
	
    </div>
    <div id='btn'>
    		<li><input type="button" value='Generar' onclick='getId()'></li>

    </div>
    <div id='tpl'>
    	<ul style='list-style: none;'>
    	<li><label>N&uacute;mero de etiquetas:</label></li>
    	<li><input type="number" name='parsTpl' value="1" min="1"></li>
    	<li><label >Plantilla:</label></li>
    	<li><select name="parsTpl" id="">
    	    <option value="-1">---- Elige plantilla ----</option>
    		<option value="1">Plantilla 1</option>
    		<option value="2">Colgantes</option>
    		<option value="3">Varios precios</option>
    		<option value="4">Precio oferta</option>
    		<option value="5">Colgantes de oferta</option>
    	</select></li>
    	</ul>
    </div>
    <div id="ofertas">
    	<!--<span><center><p><b>ofertas</b></p><input type="checkbox" id="ofe" style="padding:10px;"></span>
	Modificación de Oscar 12/02/2017
    	-->
    	<span>
    		<b>Filtrar por:</b>
    			<p align="center">
    				<select id="ofe" align="center">
<!--    					<option value="-1">Con y sin Oferta</option>-->
    					<option value="1">Sin Oferta</option>
    					<option value="2">Con Oferta</option>
    				</select>			
    			</p>
    		</center>
    	</span>
    </div>
</div>
</form>
</div>


<?php echo '
 <style>
 	ul.filters {
display: inline-flex;
list-style: none;
}
 	ul.filters2 {
display: inline-flex;
list-style: none;

}
 	ul#proLi{
list-style: none;


}
div#pli {
position: absolute;
left: 500px;
list-style: none;
}
input#busca {
width: 600px;
}
div#listaProd {
border: solid;
border-width: 2px;
width: 500px;
height: 300px;
position: relative;
left: 100px;
border-color: #64A512;
overflow: scroll;
}
div#buscProd.ob {
display: none;
width: 500px;
height: 500px;
top: 600px;
overflow: scroll;
left: 100px;
}
div#buscProd.mb {
position: relative;
width: 500px;
height: 300px;
overflow: scroll;
left: 100px;
}
.proLiC {
border: solid;
border-color: #64A512;
height: 30PX;
border-width: 1px;
background: white;

}

a.clsEliminarElemento {
padding: 5px;
border: solid 1px #ccc;
background: #fff url(imagen.png) center no-repeat;
border-radius: 3px;
width: 16px;
display: inline-block;
margin-right: 10px;
cursor: pointer;
float: right;
}
div#tpl {
position: relative;
float: right;
right: 90px;
bottom: 300px;
left: px;
}
div#ofertas {
position: relative;
float: right;
right: -70px;
bottom: 200px;
left: px;
}
#btn{
    position:relative;
    left:650px;
    list-style:none;
    bottom:23px;
}
#cosa2 {
    border-radius: 4px;
    padding: 20px 0px;
    background: none repeat scroll 0% 0% #F7F7F7;
    width: 120%;
    overflow-x: hidden;
    margin: 0px auto;
    display: inline-block;
    position: relative;
    left: 100px;
}
label{
    font-weight:bold
}
 </style>

 <script>
 function cambiaSC(val){
	//implementación de Oscar 22.05.2018 (paquetes)
		if(val==-20){//si es paquetes
			return true;
		}
	//fn de cambio		
		var url="getSubCat.php?id_categoria="+val;
		var res=ajaxR(url);
		
		var aux=res.split(\'|\');
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}
		
		var obj=document.getElementById("tip");
		obj.options.length=0;
		
		obj.options[0] = new Option(\'----- Elige un tipo -----\', -1);
		
		for(i=1;i<aux.length;i++)
		{
			ax=aux[i].split(\'~\');
			obj.options[i] = new Option(ax[1], ax[0]);	
		}
		
	}

function cambiaTP(val)
	{
		var url="getTipo.php?id_subcategoria="+val;
		var res=ajaxR(url);
		
		var aux=res.split(\'|\');
		if(aux[0] != \'exito\')
		{
			alert(res);
			return false;
		}
		
		var obj=document.getElementById("subtip");
		obj.options.length=0;
		
		obj.options[0] = new Option(\'----- Elige un tipo-----\', 0);
		
		for(i=1;i<aux.length;i++)
		{
			ax=aux[i].split(\'~\');
			obj.options[i] = new Option(ax[1], ax[0]);	
		}
	}


 	function buscaProd()
 	{
		var aBusc = document.getElementById(\'busca\').value;
		var url   = "../../../code/ajax/especiales/Etiquetas/etiquetas.php";
		$(\'#buscProd\').html("");
		$.post(
				url,
				{
					texto:aBusc
				},
				function(data)
				{
					var i= 0;
					var datos = jQuery.parseJSON(data);
					document.getElementById(\'listaProd\').style.display=\'none\';
						document.getElementById(\'buscProd\').className=\'mb\';
						jQuery.each(datos, function(i, val) {
							 $(\'#buscProd\').append("<input class=\'result\' type=\'text\'  id=\'"+datos[i].id_pr+"\' value=\'"+datos[i].nombre+"\' onfocus=\'coloca(this.value,this.id)\' readOnly ></input>");
							});
				}
			);

 	}

 	function coloca(valor,id)
 	{
 		document.getElementById(\'busca\').value=valor;
 		document.getElementById(\'proId\').value=id;
 		document.getElementById(\'ag\').disabled=false;
 	}

 	function agregarListado()
 	{
 		var valor = document.getElementById(\'busca\').value;
 		var id = document.getElementById(\'proId\').value;
 		 $(\'#proLi\').append("<li class=\'proLiC\' id=\'li"+id+"\'>"+valor+"<input type=\'text\' name=\'pro\' style=\'display:none\' value=\'"+id+"\'></input><a class=\'clsEliminarElemento\'>&nbsp;</a></li>");
 		 document.getElementById(\'buscProd\').className=\'ob\';
 		 document.getElementById(\'listaProd\').style.display=\'block\';
 		 document.getElementById(\'busca\').value=\'\';
 		 document.getElementById(\'proId\').value=\'\';
 		 document.getElementById(\'ag\').disabled=true;
 	}

 	function getId(){
			var elementos  = document.getElementsByName(\'pro\');
			var elementos2 = document.getElementsByName(\'parsTpl\');
			var filtros1    = document.getElementsByName(\'filtros\');
			var e          = [];
			var e2         = [];
			var filtros    = [];
			var band = 0;

		for(i=0;i<elementos2.length;i++){
        	e2.push(elementos2[i].value);
        }
        for(i=0;i<filtros1.length;i++){
        	filtros.push(filtros1[i].value);
        }

		if(elementos2[0].value == 0){
 			alert(\'Introduce un número de etiquetas mayor a cero!!!\');
 			elementos2[0].focus();
 			return false;
 		}
	
 		if(elementos2[1].value == (-1)){
 			alert(\'Elige una plantilla!!!\');
 			elementos2[1].focus();
 			return false;
 		}
 		//console.log(filtros[3]);
 		if(filtros[2] > 0 )
 		{
 			
 			band = 1;
 	    }

 		if(elementos.length > 0){
 			for(var i=0; i<elementos.length; i++) {
 			e.push(elementos[i].value);

          }

 		}else{
 			if(filtros[0]==(-1) && filtros [1] == (-1) && band == 0)
 			{
 				alert(\'Elige al menos un criterio de b\\u00FAsqueda\');
 				document.getElementById(\'categoria\').focus();
 				return false;
 			}
 			{
 				e.push(null);
 			}
 			
 		}
//aqui condicionamos que filtre por oferta o sin oferta
	//implementación Oscar 12-02-2018
        var oferta="";
        if(document.getElementById(\'ofe\').value==1){
        	oferta=" WHERE ax1.oferta=0";
        }
        if(document.getElementById(\'ofe\').value==2){
        	oferta=" WHERE ax1.oferta=1";
        }
        //fin de cambio
    //implemenatción para impresión de paquetes Oscar 22.05.2018
    	var es_pqte=0;
    	if(document.getElementById(\'categoria\').value==-2){
    		es_pqte=1;
    	}
    //fin de cambio
    	//alert(e2[1]);
//alert(oferta);
         var url= "../../../code/ajax/especiales/Etiquetas/crearEtiquetas.php"; 
         $.post(
         	url,
         	{
         		\'arr[]\' :e,
         		\'arr2[]\':e2,
         		\'fil[]\' :filtros,
         		\'ofert\':oferta,/*implementado pr Oscar 2018 para filtrar productos con/sin oferta*/
         		\'paquete\':es_pqte/*implementado pr Oscar 22.05.2018 para  indicar que se trata de impresión de paquetes*/
         	},
         	function(data){
 //alert(data);
         		ax = data.split(\'|\');
         		if(ax[0] == \'fail\'||ax[0]!=\'fail\' && ax[0]!=\'ok\')
         		{
         			alert("Sin datos!!!\\n");
         			/*alert(ax[1]);
         			$("#listaProd").html(ax[1]);*/
         		}
         		if(ax[0] == \'ok\')
         		{
         			nuevaRuta = ax[1].substring(3,50);
         		    window.open(nuevaRuta);
         		}	
         		
         	}
         	)

 	}
 	
 		 
$(document).ready(function() {

 	$(\'#listaProd\').on(\'click\',\'.clsEliminarElemento\',function(){
 		$liPadre = $($(this).parents().get(0));
 		$liPadre.remove();
 	});
   //Aquí van todas las acciones del documento.
});

 		
 	
 </script>

'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 