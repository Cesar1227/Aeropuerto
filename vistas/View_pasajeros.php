<!--VISTA PASAJERO-->

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	<?php  include "plantillas/scripts.php" ?>
	<link rel="stylesheet" type="text/css" href="css/styleRegistrarPasajero.css">
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	<script src="js/bootstrap.min.js"></script>
	<title>AEROPUERTO EL DORADO</title>
</head>
<body>
	<?php include "plantillas/header.php" ?>

	
		
	<section id="containerPasajeros">
		<?php include "plantillas/menu.php" ?>
		<div class="fondo">
			<div class="content">
				<nav class="navbar navbar-light bg-light">
					  <a class="navbar-brand" > <img src="./img/icons/pasajeros.svg" width="30" height="30" class="d-inline-block align-top" alt="">
	    				PASAJEROS</a>
				</nav>
		
				<div class="button_group">
					<div class="row row-cols-1 row-cols-md-3">
						  <div class="col mb-4">
						    <div class="card" href="View_buscarPasajeros.php">
						      <img src="./img/icons/buscarPersona.svg" class="card-img-top" alt="buscar" style="width: 32px; height: 32px !important">
						      <div class="card-body">
						      	<a href="View_buscarPasajero.php"><button type="button" class="btn btn-outline-dark">BUSCAR</button></a>
						        
						      </div>
						    </div>
						</div>
						<div class="col mb-4">
						   <div class="card">
						      <img src="./img/icons/registra.svg" class="card-img-top" alt="vuelo">
						      <div class="card-body">
						        <a href="View_registrarPasajero.php"><button type="button" class="btn btn-outline-dark">REGISTAR</button></a>
						      </div>
						   </div>
						</div>
						<div class="col mb-4">
						   <div class="card">
						      <img src="./img/icons/lapiz.svg" class="card-img-top" alt="vuelo">
						      <div class="card-body">
						        <a href="View_ActualizarPasajero.php"><button type="button" class="btn btn-outline-dark">ACTUALIZAR | ELIMINAR</button></a>
						      </div>
						   </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
	</section>
	
	
</body>
</html>