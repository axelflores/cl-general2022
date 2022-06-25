<?php
	include( '../../../../config.ini.php' );
	include( '../../../../conectMin.php' );//sesión
	include( '../../../../conexionMysqli.php' );
	include( 'ajax/db.php' );

?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../../../../css/bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="../../../../css/bootstrap/js/bootstrap.bundle.min.js"></script>
	<link href="../../../../css/icons/css/fontello.css" rel="stylesheet" type="text/css"  media="all" />
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script type="text/javascript" src="../../../../js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<title>Validación de Tansferencias</title>
</head>
<body>
	<audio id="audio" controls style="display:none;">
		<source type="audio/wav" src="../../../../files/scanner.mp3">
	</audio>
	<div class="emergent">
		<div class="btn_close_container">
			<button
				class="btn btn-danger"
				onclick="close_emergent();"
			>
				X
			</button>
		</div>
		<div class="emergent_content"></div>
	</div>
	
	<div class="header">
		<div class="row">
			<div class="mnu_item invoices active" onclick="show_view( this, '.transfers_lists');">
				<i class="icon-menu"></i><br>
				Transferencias por validar
			</div>
			<div class="mnu_item source" onclick="show_view( this, '.transfers_products');">
				<i class="icon-snowflake-o"></i><br>
				Validación de productos
			</div>
			<div class="mnu_item source" onclick="show_view( this, '.resume');">
				<i class="icon-chart-line"></i><br>
				Resumen
			</div>
		</div>
	</div>

	<div class="global_container">
<?php
	$pending_adjustments = getInventoryAdjudments( $user_id, $link );
	if( $pending_adjustments != 'ok' ){
		echo $pending_adjustments;
	}else{
		echo '<div class="content_item transfers_lists">';
				include( 'views/transfers_lists.php' );
		echo '</div>';

		echo '<div class="content_item transfers_products hidden">';
				include( 'views/transfers_products.php' );
		echo '</div>';

		echo '<div class="content_item resume hidden">';
				include( 'views/resume.php' );
		echo '</div>';
	}

?>		
	</div>

	<div class="footer">
		<div class="row">
			<div class="col-1"></div>
			<div class="col-10"><!-- txt_alg_left -->
				<center>
					<button 
						class="btn btn-light"
						onclick="redirect('home');"
					>
						<i class="icon-home-1"></i>
					</button>

				<button
					type="button"
					class="btn btn-success"
					onclick="finish_validation();"
					id="btn_finish_validation"
				>
					Finalizar Revisión
				</button>
				</center>
			</div>
		</div>
	</div>
	<!--/div-->

</body>
</html>
