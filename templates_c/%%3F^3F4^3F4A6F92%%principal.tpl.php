<?php /* Smarty version 2.6.13, created on 2021-10-08 19:24:00
         compiled from general/principal.tpl */ ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<div id="bg_seccion">    
		<p align="center">
			<img src="img/img_casadelasluces/Logo.png" width="50%" style="position:relative; top : -130px; z-index : -1;">
		<p/>
		<!--<div id="textobienvenida">
			<div class="texto-bien">   
			
			
				<div class="uno">
					<p>El Panel de Administraci&oacute;n de T&eacute;rminus10 permite disponer de de diversas herramientas que lo hacen intuitivo y amigable a la experiencia del usuario. Con la calidad y exigencia de un desarrollo vanguardista y original, nuestro panel esta basado en la m&aacute;s alta tecnolog&iacute;a. Para m&aacute;s informaci&oacute;n sobre nuestros productos visite <a href="http://terminus10.com" target="_blank">Terminus10.com</a></p>
				</div>
			</div>
			<br>
			<br>
			<table width="100%">
				<tr>
					<td width="100%" align="center">
						<img src="<?php echo $this->_tpl_vars['rooturl']; ?>
img/pate_icons.png" border="0">
					</td>
				</tr>
			</table>

	 <div id="tel"><i class="fon"></i><p>Tel&eacute;fono:</p> 3687.7104 opci&oacute;n 3</div>
	 <div id="mail"><i class="sobre"></i><p>Soporte en l&iacute;nea:</p></div>
     <div class="contactar"><a  style="text-decoration:none;color:#FFF;"href="http://terminus10.com/#box11" target="_blank">CONTACTAR</a></div>
    
   
   
             </div>-->
			
			
			</div>
	 		
		</div>           
	</div>
</div>


<?php echo '<?php'; ?>
 echo"cosas";<?php echo '?>'; ?>




<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php echo '
	<!--<script type="text/javascript"> SE CANCELA IMPLEMETANCIÓN PORQUE SALE CONFIRMACIÓN CADA QUE SALE O RECARGA PÁGINA 

	window.addEventListener("beforeunload", function (e) {
  	var confirmationMessage = "\\o/";

  //(e || window.event).returnValue = confirmationMessage; //Gecko + IE
  	 window.open(\'templates/general/cierra_prueba.php\',\'auto_blank\');
  	 /*var conf=confirm("Desea cerrar sesión???");
  		if(conf==true){
  			alert("cerrar sesion");
  		}else{
  			alert("No cerrar sesión");
  		}
 // return confirmationMessage;                            //Webkit, Safari, Chrome
*/
});

	/*window.onbeforeunload=preguntaSalida;
	function preguntaSalida() {
  		alert("entra función");
  		var conf=confirm("Desea cerrar sesión???");
  		if(conf==true){
  			alert("cerrar sesion");
  		}else{
  			alert("No cerrar sesión");
  		}	
  	}
            var bPreguntar = true;

            window.onbeforeunload = preguntarAntesDeSalir;

            function preguntarAntesDeSalir() {
                
                var conf=confirm("Desea cerrar sesión???");
  		if(conf==true){
  			alert("cerrar sesion");
  		}else{
  			alert("No cerrar sesión");
  		}	
                if (bPreguntar)
                    return "¿Seguro que quieres salir?";
            }*/

	</script>-->
'; ?>