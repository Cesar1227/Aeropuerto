
<?php 
	include ("../otros/validacion.php");
	include ("../controlador/controller.php");
	$objControl = new Controller();

	$alert='';

	if (isset($_GET['id'])) {
		if (isset($_POST['id'])) {
			
		}else{
			if (!empty($_GET['id'])) {
				$datos=$objControl->buscarPasajero($_GET['id']);	
				$result=pg_fetch_array($datos);
			}else{
				header('location: View_buscarPasajero.php');
			}
		}
	}

	if (isset($_POST['btn_buscar'])) {
		
		if (!empty($_POST['id'])) {
			
			$datos=$objControl->buscarPasajero($_POST['id']);	
			$result=pg_fetch_array($datos);
		}else{
			
			$alert='<p class="msg_error">INGRESE UN NUMERO DE IDENTIFICACIÓN PARA BUSCAR.</p>';
		}	
	}

	if (isset($_POST['btn_actualizar'])) {
		if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['fechan'])) {
			$alert='<p class="msg_error">LOS CAMPOS MARCADOS CON (*) SON OBLIGATORIOS.</p>';
		}else{
			$mensaje=$objControl->actualizar_pasajero($_POST['id'],$_POST['nombre'],$_POST['apellido'],$_POST['fechan'],$_POST['pasaporte'],$_POST['telefono']);

			
			$alert=$mensaje;
			
		}
	}

	if (isset($_POST['btn_eliminar'])) {
		$mensaje=$objControl->eliminar_pasajero($_POST['id']);
		$alert=$mensaje;
	}


	

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	<?php  include "./plantillas/scripts.php" ?>
	<link rel="stylesheet" type="text/css" href="css/styleRegistrarPasajero.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	<script src="js/bootstrap.min.js"></script>
	<title>AEROPUERTO EL DORADO</title>
</head>
<body>
	<?php include "./plantillas/header.php" ?>

	
		
	<section id="containerPasajeros">
		<?php include "plantillas/menu.php" ?>
		<nav class="navbar navbar-light bg-light">
				  <a class="navbar-brand" > <img src="img/icons/editar.png" width="30" height="30" class="d-inline-block align-top" alt="">
    				ACTUALIZAR | ELIMINAR PASAJERO</a>
			</nav>
		<div class="form_registro">
		
			<div class="row">
				<div class="col-12">
					<form action="" method="post">

						<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>
						
						<div class="form-group row">
							<div class="col-6">
								<label for="id" class="col-form-label">IDENTIFICACIÓN (*)</label>
								<input type="text" name="id" id="id" value='<?php echo isset($result['id_pasajero'])? $result['id_pasajero'] : ""; ?>' required> 
								
							</div>
							<div class="col-6">
									<button class="btn btn-primary" type="submit" name="btn_buscar" >BUSCAR</button>
								
							</div>

						</div>
						
						<div class="form-group row">
							
								<div class="col-6"> 
									<label for="nombre" class="col-form-label">NOMBRES (*)</label>
									<input type="text" name="nombre" id="nombre" value='<?php echo isset($result['nombre'])? $result['nombre'] : ""; ?>' >
								</div>
								
								<div class="col-6">
									<label for="apellido" class="col-form-label">APELLIDOS (*)</label>
									<input type="text" name="apellido" id="apellido" value='<?php echo isset($result['apellidos'])? $result['apellidos'] : ""; ?>' >
								</div>
								
							
						</div>
						<div class="form-group row">
							<div class="col-6">
								<label for="fechan">FECHA DE NACIMIENTO (*)</label>
								<input type="date" name="fechan" id="fechan" value='<?php echo isset($result['fecha_nac'])? $result['fecha_nac'] : ""; ?>'>
							</div>
							<div class="col-6">
								<label for="pasaporte">PASAPORTE</label>
								<input type="text" name="pasaporte" id="pasaporte" value='<?php echo isset($result['pasaporte'])? $result['pasaporte'] : ""; ?>'>
							</div>

						</div>
						
						<div class="form-group row">
							<div class="col-6">
								<label for="telefono">TELEFONO</label>
								<input type="text" name="telefono" id="telefono" value='<?php echo isset($result['telefono'])? $result['telefono'] : ""; ?>'>

							</div>
						</div>
						<div class="form-group row">
							<div class="col-6">
								<a href="View_ActualizarPasajero.php?id=00"><input type="submit" value="Actualizar usuario" class="btn btn-success" name="btn_actualizar" id="btn_actualizar" href="View_ActualizarPasajero.php?id=00" ></a>
							</div>
							
							<?php if ($_SESSION['id_rol']=="1") { ?>
									<div class="col-6">
									<input type="submit" value="ELiminar usuario" class="btn btn-danger" name="btn_eliminar" id="btn_eliminar">
									</div>
								<?php } ?> 

							
							
						</div>
						
						
						

				</form>
				</div>
			</div>
			
		
		</div>

		
	</section>
	
	
</body>
</html>