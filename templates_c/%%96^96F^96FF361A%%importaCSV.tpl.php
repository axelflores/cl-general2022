<?php /* Smarty version 2.6.13, created on 2021-10-09 17:48:22
         compiled from especiales/importaCSV.tpl */ ?>
<link href="estilo_final.css" rel="stylesheet" type="text/css" />
<link href="css/demos.css" rel="stylesheet" type="text/css" />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div id="campos">  
<div id="titulo">Importa CSV - <?php echo $this->_tpl_vars['nombreLista']; ?>
</div>
<br><br>

<div id="filtros">
	<form id="form1" name="form1" method="post" action="importaCSV.php" enctype="multipart/form-data">
	<input type="hidden" name="procesa" value="SI">
	<input type="hidden" name="id_precio" value="<?php echo $this->_tpl_vars['id_precio']; ?>
">
		<span class="mensaje"><?php echo $this->_tpl_vars['mensaje']; ?>
<span>
		<table border="0">
        	<tr>
          		<td class="motivo">Archivo</td>
          		<td>
          			<input name="archivo" type="file" class="barra2" id="text1"/>
          		</td>
          		
          		
          		<td>&nbsp;</td>
          		<td>
            		<input name="button" type="button" class="boton" id="button" value="Importar" onclick="sube(this.form)"/>
          		</td>	
          	</tr>
    	</table>      	
	</form>
</div>

	
</div>

<script>
	<?php echo '
	
	
	function sube(f)
	{
		if(f.archivo.value == \'\')
		{
			alert("Es necesario que seleccione un archivo");
			return false;
		}
		
		f.submit();
		
	}
	
	
	'; ?>

	
</script>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 