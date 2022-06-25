<?php
//conexiones a la base de datos
	include( '../../config.ini.php' );
	include( '../../conect.php' );//sesión
	include( '../../conexionMysqli.php' );
	//die( $user_id );
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="../../css/bootstrap/js/bootstrap.bundle.min.js"></script>
	<link href="../../css/icons/css/fontello.css" rel="stylesheet" type="text/css"  media="all" />
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script type="text/javascript" src="../../js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

	<title>Revisión de Ticket</title>
</head>
<body>
<?php
	echo '<input type="hidden" id="user_id" value="' . $user_id . '" >';
?>
	<audio id="audio" controls>
		<source type="audio/wav" src="../../files/scanner.mp3">
	</audio>

	<div class="emergent" tabindex="1">
		<div style="position: relative; top : 120px; left: 90%; z-index:1; display : none;">
			<button 
				class="btn btn-danger"
				onclick="close_emergent();"
			>X</button>
		</div>
		<div class="emergent_content"></div>
	</div>

	<div class="global_container">
		<div class="header">
			<div class="row">
				<div class="mnu_item invoices active" id="check_ticket" onclick="show_view( this, '.check_ticket');">
					<i class="icon-tag-2"></i><br>
					Seleccionar Ticket
				</div>
				<div class="mnu_item source" id="check_ticket_detail" onclick="show_view( this, '.check_ticket_detail');">
					<i class="icon-ok-circle"></i><br>
					Revisar Ticket
				</div>
				<!--div class="mnu_item source" onclick="show_view( this, '.validate_transfers');">
					<i class="icon-ok-circle"></i><br>
					Verificar
				</div-->
			</div>
		</div>

		<div class="content_container">
			<div class="content_item check_ticket">
				<?php 
					include( 'views/check_ticket.php' );
				?>
			</div>

			<div class="content_item check_ticket_detail hidden">
				<?php 
					include( 'views/check_ticket_detail.php' );
				?>
			</div>

			<!--div class="content_item receive_transfers hidden">
				<?php 
					//include( 'views/receive_transfers.php' );
				?>
			</div>


			<div class="content_item validate_transfers hidden">
				<?php 
					//include( 'views/validate_transfers.php' );
				?>
			</div-->

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

	<script type="text/javascript">
	
		var audio_is_playing = false;
		function alert_scann(){
			if( audio_is_playing ){
				audio = null;
			}
			var audio = document.getElementById("audio");
			
			audio_is_playing = true;
			audio.currentTime = 0;
			audio.playbackRate = 1;
			audio.play();
		}
		
		$( '#barcode_seeker' ).focus();

	</script>