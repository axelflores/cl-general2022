<?php /* Smarty version 2.6.13, created on 2021-10-14 16:59:36
         compiled from general/error.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_header.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<div id="titulo">Error <?php echo $this->_tpl_vars['no']; ?>
_<?php echo $this->_tpl_vars['nombre']; ?>
</div>
	
	
    <div id="texto">
		<table width="865" border="0">
			<tr>
    			<td width="200" height="54" valign="top" class="motivo">Descripción:</td>
    			<td width="65" valign="top" class="detalle"><?php echo $this->_tpl_vars['descripcion']; ?>
</td>
  			</tr>
  			<tr>
				<td height="67" valign="top" class="motivo">Consulta:</td>
				<td valign="top"><span class="detalle"><?php echo $this->_tpl_vars['consulta']; ?>
</span></td>
			</tr>
  			<tr>
    			<td height="61" valign="top" class="motivo"> Archivo que generó el error:</td>
    			<td valign="top"><span class="detalle"><?php echo $this->_tpl_vars['archivo']; ?>
</span></td>
  			</tr>
		</table>   
  </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_footer.tpl", 'smarty_include_vars' => array('pagetitle' => ($this->_tpl_vars['contentheader']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>