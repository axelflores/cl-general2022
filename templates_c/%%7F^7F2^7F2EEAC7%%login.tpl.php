<?php /* Smarty version 2.6.13, created on 2021-10-08 12:20:51
         compiled from general/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'general/login.tpl', 57, false),)), $this); ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['rooturl']; ?>
css/estilo_final1.css" />
		<title>Administraci&oacute;n Easy-count</title>
	<!--incluimos la librería para cambiar password-->
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['rooturl']; ?>
js/passteriscoByNeutrix.js"></script>
	</head>
	<body>
   
		<div id= "contenido_login">
        
	<div id="loginP">
    
    </div>
    
			<div class="datos">
			
                 <div class="admin"><!--<img src="img/login.png"/>-->
                         <h1 class="logo-base"><a href="https://www.easycount.com.mx/" title="la casa de las luces"><span>La casa de las luces</span></a></h1>
                </div> 
                	<?php if ($this->_tpl_vars['error_login'] == 'YES'): ?>
                
                 
					<span class="texto_error_uc">Nombre de usuario y contrase&ntilde;a incorrectos</span>
				<?php endif; ?>
                
				<div id="formulario">
                   
                
                <form id="forma1" name="forma1" method="post" action="<?php echo $this->_tpl_vars['rooturl']; ?>
index.php" autocomplete="off">
					<input type="hidden" name="form_login" value="YES" />
					<input type="hidden" name="url_act" value="<?php echo $this->_tpl_vars['url_act']; ?>
" />
					<table width="295" height="171" border="0">
						<tr>
							<td  valign="middle">
								<input name="login_user" class="usuario" placeholder="Usuario" type="text"  id="text1" name="text1" onkeypress="keyEnter(event, this.form)" 
								x-autocompletetype="ninguno-ninguno" autocomplete="false"/>
							</td>
					    </tr>
						<tr>							
							<td valign="middle">
								<input class="contrasena" placeholder="Contraseña" type="text"  id="password1_1" name="password1_1" onkeypress="keyEnter(event, this.form)"
								x-autocompletetype="ninguno-ninguno" autocomplete="false" onkeydown="cambiar(this,event,'password1');"/><!--password onkeyup="cambia(this,event);"-->
							</td>
						<!---->
							<td>
								<input type="hidden" id="password1" name="pass_user" value="">
							</td>
						<!---->
						</tr>
						<tr>
							
							<td valign="middle">
								<select name="sucursal_user" class="barra_tres" onkeypress="keyEnter(event, this.form)">
									<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['arrZonaids'],'output' => $this->_tpl_vars['arrZonanames']), $this);?>

								</select>
							</td>
						</tr>
						<!--<input type="hidden" name="sucursal_user" value="1" />-->
						<tr>
							<td height="29" colspan="2" align="center">
								<input name="button" type="button" class="boton-log" id="button" value="Entrar" onclick="valida(this.form)"/>
							</td>
						</tr>
					</table>
					
				</form>	 </div>
                
                 
                <div id="creditos">
               <!--<p>Una creaci&oacute;n de <a style="text-decoration:none;" href="http://www.terminus.mx/"><strong class="terminux">T&eacute;rminus<img src="¨../../img/numero_terminus.png" width="13" height="18" alt=""/></strong></a></p>-->
               
                </div>
			</div>
			
		</div>
				
		
		<script>
			<?php echo '
			/*window.onload=function(){
				setTimeout(\'limpiar_campos()\',10);
			}

			function limpiar_campos(){
				document.getElementById("text1").value=\'\';
				document.getElementById("password1").value=\'\';
				//alert(\'ya limpió\');
			}*/

			document.forma1.login_user.focus();


			function valida(f)
			{
				//alert(f.pass_user.value);
				if(f.login_user.value.length == 0)
				{
					alert(\'Es necesario que inserte su login\');
					f.login_user.focus();
					return false;
				}
				if(f.pass_user.value.length == 0)
				{
					alert(\'Es necesario que inserte su contraseña\');
					f.pass_user.focus();
					return false;
				}
				if(f.sucursal_user.value==0){
					alert(\'Primero elija una sucursal\');
					f.sucursal_user.focus();
					return false;
				}
				
				f.submit();
				
			}
			
			function keyEnter(eve, f)
			{
				var key=0;	
				key=(eve.which) ? eve.which : eve.keyCode;
				
				if(key == 13)
					valida(f);				
			}
			
			'; ?>

		</script>
		
	<div class="fondo1"></div>	
	</body>
</html>