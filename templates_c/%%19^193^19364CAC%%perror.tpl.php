<?php /* Smarty version 2.6.13, created on 2021-10-16 16:22:40
         compiled from general/perror.tpl */ ?>
<table>
	<tr>
		<td colspan="2" align="center">Ha ocurrido un error</td>
	</tr>
	<tr>
		<td><b>Nombre:</b></td>
		<td><?php echo $this->_tpl_vars['nombre']; ?>
</td>
	</tr>
	<tr>
		<td><b>No:</b></td>
		<td><?php echo $this->_tpl_vars['no']; ?>
</td>
	</tr>
	<tr>
		<td><b>Descripci&oacute;n:</b></td>
		<td><?php echo $this->_tpl_vars['descripcion']; ?>
</td>
	</tr>
	<tr>
		<td><b>Consulta que genero el error:</b></td>
		<td><?php echo $this->_tpl_vars['consulta']; ?>
</td>
	</tr>
	<tr>
		<td><b>Archivo que genero el error:</b></td>
		<td><?php echo $this->_tpl_vars['archivo']; ?>
</td>
	</tr>	
</table>