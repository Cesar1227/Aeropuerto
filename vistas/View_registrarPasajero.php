<!--VISTA PASAJERO-->
<?php

	include ("../otros/validacion.php");
	include ("../controlador/controller.php");

	$objControl = new Controller();


	if(!empty($_POST)){
		$alert='';
		$activemsg="false";
		$mensaje="";
		
		/*
			echo "<script type=''>
			alert('funcionando');
			</script>"; */

		if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['fechan'])) {

			
			$alert='<p class="msg_error">LOS CAMPOS MARCADOS CON (*) SON OBLIGATORIOS.</p>';
			
		}else{
			if(!is_string($_POST['nombre']) || !is_string($_POST['apellido'])){
				$alert='<p class="msg_error">EL NOMBRE Y APELLIDO DEBEN SER SOLO TEXTO.</p>';
			}else if ($_POST['fechan']>date("Y/m/d")) {
				$alert='<p class="msg_error">LA FECHA DE NACIMIENTO DEBE SER MENOR A LA FECHA ACTUAL.</p>';
			}else {
				$mensaje=$objControl->ingresar_pasajero($_POST['id'],strtoupper($_POST['nombre']),strtoupper($_POST['apellido']),$_POST['fechan'],$_POST['pasaporte'],$_POST['telefono']);
				
				$alert=$mensaje;

			}



			

		}

	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	<?php  include "plantillas/scripts.php" ?>
	<link rel="stylesheet" type="text/css" href="css/styleRegistrarPasajero.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	
	<title>AEROPUERTO EL DORADO</title>
</head>
<body>
	<?php include "plantillas/header.php" ?>

	
		
	<section id="containerPasajeros">
		<?php include "plantillas/menu.php" ?>
		

		<div class="fondo">
			<div class="content">
				
				<nav class="navbar navbar-light bg-light">
					  <a class="navbar-brand" > <img src="img/icons/pasajeros.svg" width="30" height="30" class="d-inline-block align-top" alt="">
	    				REGISTRO PASAJERO</a>
				</nav>
				<div class="form_registro">
					
					

					<div class="row">
						<div class="col-12">

							<form action="" method="POST" name="nuevo_pasajero">
								<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>

								<div class="form-group row">
									<div class="col-6">
										<label for="id" class="col-form-label">IDENTIFICACIÓN (*)</label>
										<input type="text" name="id" id="id" >
									</div>


								</div>
								
								<div class="form-group row">
									
										<div class="col-6"> 
											<label for="nombre" class="col-form-label">NOMBRES (*)</label>
											<input type="text" name="nombre" id="nombre" >
										</div>
										
										<div class="col-6">
											<label for="apellido" class="col-form-label">APELLIDOS (*)</label>
											<input type="text" name="apellido" id="apellido" >
										</div>
										
									
								</div>
								<div class="form-group row">
									<div class="col-6">
										<label for="fechan">FECHA DE NACIMIENTO (*)</label>
										<input type="date" name="fechan" id="fechan" >
									</div>
									<div class="col-6">
										<label for="pasaporte">PASAPORTE</label>
										<input type="text" name="pasaporte" id="pasaporte" >
									</div>

								</div>
								
								<div class="col-5">
									<label for="telefono">TELEFONO</label>
								<input type="text" name="telefono" id="telefono">

								
								</div>

								<input type="submit" value="crear usuario" class="btn btn-primary btn_save">

							
								
						</form>

						</div>
					</div>
					
					
				</div>
			</div>
			

		</div>
		

		
	</section>
	
	
</body>
</html>

