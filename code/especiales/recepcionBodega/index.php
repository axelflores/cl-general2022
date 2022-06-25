<?php
//conexiones a la base de datos
	include( '../../../config.ini.php' );
	include( '../../../conectMin.php' );//sesión
	include( '../../../conexionMysqli.php' );
	//die( $user_id );
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap/css/bootstrap.css">
	<link href="../../../css/icons/css/fontello.css" rel="stylesheet" type="text/css"  media="all" />
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script type="text/javascript" src="../../../js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<title>Recepción Bodega</title>
</head>
<body>
	<div class="global_container">
		<div class="header">
			<!--div class="row"-->
				<div class="mnu_item invoices active" onclick="show_view( this, '.invoices');">
					<i class="icon-pin"></i><br>
					Remisiones
				</div>
				<div class="mnu_item source" onclick="show_view( this, '.invoices_products');">
					<i class="icon-plus-circle"></i><br>
					Entradas
				</div>
				<div class="mnu_item list" onclick="show_view( this, '.invoices_lists');">
					<i class="icon-th-list-outline"></i><br>
					Listado
				</div>
				<div class="mnu_item finish" onclick="show_view( this, '.invoices_finish');">
					<i class="icon-ok-circle"></i><br>
					Finalizar
				</div>
			<!--/div-->
		</div>

		<div class="content_container">
			<div class="content_item invoices">
				<?php 
					include( 'views/invoices.php' );
				?>
			</div>

			<div class="content_item invoices_products hidden">
				<?php 
					include( 'views/invoices_products.php' );
				?>
			</div>

			<div class="content_item invoices_lists hidden">
				<?php 
					include( 'views/invoices_lists.php' );
				?>
			</div>

			<div class="content_item invoices_finish hidden">
				<?php 
					include( 'views/invoices_finish.php' );
				?>
			</div>

		</div>

		<div class="footer">
			<div class="row">
				<div class="col-6 txt_alg_left">
					<button 
						class="btn btn-light"
						onclick="redirect('home');"
					>
						<i class="icon-home-1"></i>
					</button>
				</div>

				<div class="col-6 txt_alg_right">
					<button class="btn btn-light">
						<i class="icon-off"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>