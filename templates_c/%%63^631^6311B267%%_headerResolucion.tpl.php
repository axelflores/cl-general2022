<?php /* Smarty version 2.6.13, created on 2021-10-08 12:22:34
         compiled from _headerResolucion.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
  <title>Casa de las luces</title>
  <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['rooturl']; ?>
css/estilo_final1.css"/>
   <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['rooturl']; ?>
css/gridSW_l.css"/>
   <!--link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['rooturl']; ?>
css/cssrumi/css/estilosuperiores.css" />
   <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['rooturl']; ?>
css/cssrumi/css/gridsuperiores.css" /-->


  <!-- <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script> -->
  <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script> -->
  	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
  <script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/funciones.js"></script>
  <!-- Librerias para el grid-->
  <script language="javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/grid/RedCatGridResoluciones.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/grid/yahoo.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/grid/event.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/grid/dom.js"></script>
  <script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/grid/fix.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/calendar.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/calendar-es.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/calendar-setup.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/buzz.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/buzz.min.js"></script>
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/menusistema.min.js"></script>
  <script language="javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/presentacion.js"></script>

	<style>
	
	<?php echo '
	@font-face {
 		 font-family: \'Gafata\';
	     font-style: normal;
	  	 font-weight: 400;
		 src: local(\'Gafata\'), local(\'Gafata-Regular\'), url(';  echo $this->_tpl_vars['rooturl'];  echo '/css/fuentegafata.woff) format(\'woff\');
	}
	'; ?>

	
	</style>

  
  <script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="<?php echo $this->_tpl_vars['rooturl']; ?>
/css/jquery-ui.css">
<script src="<?php echo $this->_tpl_vars['rooturl']; ?>
/js/jquery-ui.js"></script>
   
  
  <?php echo '
  
  	<script>
	
	var mySound = new buzz.sound('; ?>
"<?php echo $this->_tpl_vars['rooturl']; ?>
files/caralarm"<?php echo ', {
   	 formats: [ "mp3" ]
	});
	
	//buzz.all().play();
	
	function buscaAlerta(){
		//Buscamos si hay alertas para el usuario
		'; ?>

		url="<?php echo $this->_tpl_vars['rooturl']; ?>
/code/ajax/buscaAlertas.php";
		<?php echo '
		var res=ajaxR(url);
		aux=res.split(\'|\');
		
		if(aux[0] != \'SI\') {
			document.getElementById(\'IDAutorizacion\').value = "";
			var obj=document.getElementById(\'alertaSistema\');
			obj.style.display=\'none\';
		}
		
		if(aux[0] == \'SI\')
		{
			buzz.all().play();
			//mySound.play();
			
			document.getElementById(\'textoAlerta\').innerHTML="<b>Motivo: </b>"+aux[2];
			document.getElementById(\'fechaAlerta\').innerHTML="<b>Fecha: </b>"+aux[3];
			document.getElementById(\'horaAlerta\').innerHTML="<b>Hora: </b>"+aux[4];
			document.getElementById(\'IDAutorizacion\').value=aux[6];
			'; ?>

			document.getElementById('linkAlerta').href='<?php echo $this->_tpl_vars['rooturl']; ?>
'+aux[5];
			document.getElementById('linkAlerta').target="_blank";
			
			<?php echo '
			
			document.getElementById(\'botonCerrarAlerta\').onclick=function(){cierraAlerta(aux[1])};
			
			var obj=document.getElementById(\'alertaSistema\');
			obj.style.display=\'block\';
			
		}
	
		//document.getElementById(\'alertaSistema\').style.display=\'none\';
		
	}
	var contMsg=0;
	/*function buscaTransfer(){
		'; ?>

		url="<?php echo $this->_tpl_vars['rooturl']; ?>
/code/especiales/sincronizacion/buscaTransfer.php?suc=<?php echo $this->_tpl_vars['sucursal_id']; ?>
";
		<?php echo '
		var res=ajaxR(url);
		aux=res.split(\'|\');
		//alert(res);
		if(aux[0] != \'SI\') {
			if(aux[0]==\'Hay productos no existentes, sincroniza manualmente\'){
				
				//window.location.href="{$rooturl}/index.php";
			}
			if(aux[0]!=\'NO\'){
				if(contMsg==1||contMsg==40){
					//alert("Hay productos nuevos pendientes por insertar!!!\\n"+"Pregunte si puede sincronizar...");	
					return false;
				}
				if(aux[0]==\'servidor ocupado\'){
					//alert("El servidor esta en proceso de sincronizacion\\n"+"Intente en 5 minutos!!!");
					return false;
				}
				//alert(aux[0]);
			}
		}
		
		if(aux[0] == \'SI\'){
			//alert(\'here\'+aux[0]+aux[1]+aux[2]);
			//buzz.all().play();
		//	document.getElementById(\'folT\').value=aux[2];
			'; ?>

			
			<?php echo '
			var obj=document.getElementById(\'alertaTrans\');
			/*
		DESHABILITADO TEMPORALMENTE*/
		//	obj.style.display=\'block\';
	/*	}
	}

	function cierraAviso(){
		//alert(\'No olvide que esta transferencia ya quedo registrada y la puede consultar en el módulo de TRANSFERENCIAS\');
		document.getElementById(\'alertaTrans\').style.display=\'none\';
		return false;//finalizamos funcion
	}
	
	function cierraAlerta(val){
		'; ?>

		url="<?php echo $this->_tpl_vars['rooturl']; ?>
/code/ajax/cancelaAlerta.php?id="+val;
		<?php echo '
		
		var res=ajaxR(url);
		if(res == \'exito\'){
			var obj=document.getElementById(\'alertaSistema\');
			obj.style.display=\'none\';
		}
	}
	
	function Parar()
	{
		//document.all.sound.src = ""
	}
	
	//linkAlerta
	
	$(document).ready(function() {
	//metemos busquedad de transferencia
			buscaTransfer();
			setInterval(\'buscaTransfer()\',\'30000\');	
		$("#linkAlerta").on ("click", function () {
			$("#alertaSistema").css ("display", "none");
			var res=ajaxR("';  echo $this->_tpl_vars['rooturl'];  echo 'code/ajax/aunexisteAlerta.php?id_aut=" + $("#IDAutorizacion").val());
			if (res.match (/SI/i)) {
				return true;
			} else {
				alert ("El vendedor en turno ha cancelado la solicitud");
				return false;
			}
		});
		';  if ($this->_tpl_vars['tabla'] != 'ec_autorizacion'): ?>
		buscaAlerta();  
		setInterval('buscaAlerta()', 10000);
		<?php endif;  echo '
	});
*/
//setTimeInterval();
	
	</script>
	
  <script type="text/javascript">

			
jQuery(window).load(function() {

    $("#nav > li > a").click(function (e) { // binding onclick
        if ($(this).parent().hasClass(\'selected\')) {
            $("#nav .selected div div").slideUp(100); // hiding popups
            $("#nav .selected").removeClass("selected");
        } else {
            $("#nav .selected div div").slideUp(100); // hiding popups
            $("#nav .selected").removeClass("selected");

            if ($(this).next(".subs").length) {
                $(this).parent().addClass("selected"); // display popup
                $(this).next(".subs").children().slideDown(200);
            }
        }
        e.stopPropagation();
    }); 

    $("body").click(function () { // binding onclick to body
        $("#nav .selected div div").slideUp(100); // hiding popups
        $("#nav .selected").removeClass("selected");
    }); 

});


	</script>
 '; ?>
	
</head>

<body>

<!--Busqueda de transferencia-->
     <div id="alertaTrans" style="padding:5px;border:2px solid;position:fixed;width:35%;left:30%;right:35%;top:0;
     height:200px;background:rgba(220,220,0,0.5);display:none;">
     	<div style="width:100%;text-align:right;">
     	<a href="javascript:cierraAviso();"><b style="border:solid 1px red;text-decoration:none;">X</b></a>
     	</div>
     	<a href="<?php echo $this->_tpl_vars['rooturl']; ?>
/code/general/listados.php?tabla=ZWNfdHJhbnNmZXJlbmNpYXM=&no_tabla=MA==">
     		<div style="width=90%;height:90%;" onclick="cierraAviso();">
     			<p align="center">Nueva transferencia insertada o actualizada</p>
     			<p align="center">Folio:</p>
     			<p align="center"><input type="text" id="folT" style="width:50%;background:transparent;" disabled></p>  			
     		</div>
     	</a>
     </div>
<!---->
<div id="alertaSistema" class="contenedor_alerta" style="display:none">
<input type="button" id="botonCerrarAlerta" name="cerrar" class="cerrarse" onclick="cierraAlerta()" value="X">
<a href="#" id="linkAlerta">
<div  class="alerta"  >
  <div>
	<h3>Hay una nueva alerta que necesita revisar</h3>
	<p id="textoAlerta">Motivo: Puede ver</p>
	<p id="fechaAlerta">Fecha: 2018-03-26</p>
	<p id="horaAlerta">Hora:13:00</p>
	<!--<p id="linkAlerta">Revisar class="linksd">Clic</p>-->
	</div>
	<!--<input type="button" class="acep" value="Aceptar">-->
    <input type="hidden" name="IDAutorizacion" id="IDAutorizacion" value="" />
</div>
</div>
</div>
<div id="pantalla" style="display:none; background:rgba(255,255,255,0.5)"></div>
<div id="contenido">    
 <!--Comienza el header--> 
<header>
     <div class="ctn-header">
 	<div class="logoheader">
		<a href="<?php echo $this->_tpl_vars['rooturl']; ?>
index.php">
			<img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/img_casadelasluces/logocasadelasluces-easy.png"/>
		</a>
	</div>
    <!--Usuarios y sucursal-->
       <div class="datosusuario">
       <div class="close"><img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/close.png" onmouseover="this.style.cursor='hand';this.style.cursor='pointer';" onclick="cierraSesion()"/></div>
       <!--Usuarios comienza lado derecho-->
            <div class="usuario1"><strong>Usuario:</strong> <?php echo $this->_tpl_vars['user_fullname']; ?>
</div>
      <div class="sucursal"><strong>Sucursal:</strong> <?php echo $this->_tpl_vars['sucursal_name']; ?>
</div>
        </div>
        </div>
         <!--Titulo del panel-->
          <div class="h1" style="position:relative;top:-30px;">Panel de administraci&oacute;n</div> 
    	<!--Termina los leemenetos del header-->
         <!--comienza el menu princuipal-->

	<?php if ($this->_tpl_vars['ver_pantalla_ventas'] == 1): ?>

	<a  href="<?php echo $this->_tpl_vars['rooturl']; ?>
touch/index.php">
	<!--class="boton"-->
    	<div id="interfazbtn1" style="font-size: 13px;
												    font-weight: bold;
												    margin-right: 54px;
												    margin-top: -160px;
												    text-align: center;
												    text-decoration: none !important;
												    width: 90px;
												    height:100px;
												    border-radius:5px;
												    border:1px solid green;
												    background:white;
												    float:right;
												    position:relative;">
			<center>
				<img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/puntoVenta.webp" style="width:85px;height:100px;"><!--Punto de Venta-->
			Punto de venta
			</center>
		</div>
	</a>  
	
	<?php endif; ?>
     
		<div id="botones" style="top:240px;position:absolute;">		
			<ul id="nav">
				<?php unset($this->_sections['indice']);
$this->_sections['indice']['loop'] = is_array($_loop=$this->_tpl_vars['menus']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indice']['name'] = 'indice';
$this->_sections['indice']['start'] = (int)0;
$this->_sections['indice']['show'] = true;
$this->_sections['indice']['max'] = $this->_sections['indice']['loop'];
$this->_sections['indice']['step'] = 1;
if ($this->_sections['indice']['start'] < 0)
    $this->_sections['indice']['start'] = max($this->_sections['indice']['step'] > 0 ? 0 : -1, $this->_sections['indice']['loop'] + $this->_sections['indice']['start']);
else
    $this->_sections['indice']['start'] = min($this->_sections['indice']['start'], $this->_sections['indice']['step'] > 0 ? $this->_sections['indice']['loop'] : $this->_sections['indice']['loop']-1);
if ($this->_sections['indice']['show']) {
    $this->_sections['indice']['total'] = min(ceil(($this->_sections['indice']['step'] > 0 ? $this->_sections['indice']['loop'] - $this->_sections['indice']['start'] : $this->_sections['indice']['start']+1)/abs($this->_sections['indice']['step'])), $this->_sections['indice']['max']);
    if ($this->_sections['indice']['total'] == 0)
        $this->_sections['indice']['show'] = false;
} else
    $this->_sections['indice']['total'] = 0;
if ($this->_sections['indice']['show']):

            for ($this->_sections['indice']['index'] = $this->_sections['indice']['start'], $this->_sections['indice']['iteration'] = 1;
                 $this->_sections['indice']['iteration'] <= $this->_sections['indice']['total'];
                 $this->_sections['indice']['index'] += $this->_sections['indice']['step'], $this->_sections['indice']['iteration']++):
$this->_sections['indice']['rownum'] = $this->_sections['indice']['iteration'];
$this->_sections['indice']['index_prev'] = $this->_sections['indice']['index'] - $this->_sections['indice']['step'];
$this->_sections['indice']['index_next'] = $this->_sections['indice']['index'] + $this->_sections['indice']['step'];
$this->_sections['indice']['first']      = ($this->_sections['indice']['iteration'] == 1);
$this->_sections['indice']['last']       = ($this->_sections['indice']['iteration'] == $this->_sections['indice']['total']);
?>
					<li>
					<a href="javascript:void(0)" title="<?php echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][1]; ?>
" class="desplegable"><img src="<?php echo $this->_tpl_vars['rooturl'];  echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][2]; ?>
" width="16" height="16" border="0"/><?php echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][1]; ?>
</a>
					<?php unset($this->_sections['ind']);
$this->_sections['ind']['loop'] = is_array($_loop=$this->_tpl_vars['menus'][$this->_sections['indice']['index']][3]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ind']['name'] = 'ind';
$this->_sections['ind']['start'] = (int)0;
$this->_sections['ind']['show'] = true;
$this->_sections['ind']['max'] = $this->_sections['ind']['loop'];
$this->_sections['ind']['step'] = 1;
if ($this->_sections['ind']['start'] < 0)
    $this->_sections['ind']['start'] = max($this->_sections['ind']['step'] > 0 ? 0 : -1, $this->_sections['ind']['loop'] + $this->_sections['ind']['start']);
else
    $this->_sections['ind']['start'] = min($this->_sections['ind']['start'], $this->_sections['ind']['step'] > 0 ? $this->_sections['ind']['loop'] : $this->_sections['ind']['loop']-1);
if ($this->_sections['ind']['show']) {
    $this->_sections['ind']['total'] = min(ceil(($this->_sections['ind']['step'] > 0 ? $this->_sections['ind']['loop'] - $this->_sections['ind']['start'] : $this->_sections['ind']['start']+1)/abs($this->_sections['ind']['step'])), $this->_sections['ind']['max']);
    if ($this->_sections['ind']['total'] == 0)
        $this->_sections['ind']['show'] = false;
} else
    $this->_sections['ind']['total'] = 0;
if ($this->_sections['ind']['show']):

            for ($this->_sections['ind']['index'] = $this->_sections['ind']['start'], $this->_sections['ind']['iteration'] = 1;
                 $this->_sections['ind']['iteration'] <= $this->_sections['ind']['total'];
                 $this->_sections['ind']['index'] += $this->_sections['ind']['step'], $this->_sections['ind']['iteration']++):
$this->_sections['ind']['rownum'] = $this->_sections['ind']['iteration'];
$this->_sections['ind']['index_prev'] = $this->_sections['ind']['index'] - $this->_sections['ind']['step'];
$this->_sections['ind']['index_next'] = $this->_sections['ind']['index'] + $this->_sections['ind']['step'];
$this->_sections['ind']['first']      = ($this->_sections['ind']['iteration'] == 1);
$this->_sections['ind']['last']       = ($this->_sections['ind']['iteration'] == $this->_sections['ind']['total']);
?>
						<?php if ($this->_sections['ind']['first']): ?>
                            <div class="subs">
                            <div>                    
							<ul>
						<?php endif; ?>
						<li>
							<a href="<?php if ($this->_tpl_vars['menus'][$this->_sections['indice']['index']][3][$this->_sections['ind']['index']][1] == '1'):  echo $this->_tpl_vars['rooturl']; ?>
code/general/listados.php?tabla=<?php echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][3][$this->_sections['ind']['index']][2];  else:  echo $this->_tpl_vars['rooturl'];  echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][3][$this->_sections['ind']['index']][3];  endif; ?>&no_tabla=<?php echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][3][$this->_sections['ind']['index']][4]; ?>
">
								<?php echo $this->_tpl_vars['menus'][$this->_sections['indice']['index']][3][$this->_sections['ind']['index']][0]; ?>

							</a>
						</li>
						<?php if ($this->_sections['ind']['last']): ?>
							</ul>
                            </div>
                            </div>
						<?php endif; ?>
					<?php endfor; endif; ?>				
					</li>
				<?php endfor; endif; ?>	
			</ul>
		
		</div>
        <!--  Final Menu general -->
      
        </header>
   <div style="position:fixed;top:1 0px;font-size:30px;color:red;left:250px;"><b id="nom_sistema"></b></div>
   <?php echo '
   <script>

		window.onload=function carga_informe(){
			'; ?>

				var dir="<?php echo $this->_tpl_vars['rooturl']; ?>
/code/ajax/especiales/Seguimiento.php?fl=1";
			<?php echo '
				var res=ajaxR(dir);
				var ax=res.split(\'|\');
				if(ax[0]!=\'ok\'){
					alert(res);
				}else{
					$("#nom_sistema").html(ax[1]);
				}
				return true;
			}
   </script>
   '; ?>