<?php 
	include ("../otros/validacion.php");
	include ("../controlador/controller.php");

			
		
		$objControl = new Controller();

		if(empty($_GET['pagina'])){
			$pagina=1;
		}else{
			$pagina=$_GET['pagina'];
		}
		$por_pagina=3;
		$desde = ($pagina-1)*$por_pagina;
		$total_registros=$objControl->cantidad_pasajeros();
		
		$total_paginas=ceil($total_registros/$por_pagina);

		$datos=$objControl->listar_pasajeros($desde,$por_pagina);

		if (isset($_GET['f_identificacion']) && isset($_GET['f_apellidos'])) {
			if ($_GET['f_identificacion']=="" and $_GET['f_apellidos']=="") {
				
			}else{
				$datos=$objControl->filtro_pasajeros(strtoupper($_GET['f_identificacion']),strtoupper($_GET['f_apellidos']),100,0);
				$total_paginas=1;
				$por_pagina=1;
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
	<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	

	
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>

	
	<title>AEROPUERTO EL DORADO</title>
</head>
<body>
	<?php include "./plantillas/header.php" ?>

	
		
	<section id="containerPasajeros">
		<?php include "plantillas/menu.php" ?>
		<div class="fondo">
			<div class="content">
				<nav class="navbar navbar-light bg-light">
				  <a class="navbar-brand" > <img src="img/icons/listar.svg" width="30" height="30" class="d-inline-block align-top" alt="">
    				VISUALIZAR PASAJEROS</a>
				</nav>
				<div class="form_registro">
					
					<div class="row">

						<div class="col-12">
							<form class="" method="GET">
								<h5>FILTRO</h5>
								<div  class="row">
									<div class="col-3">
										<label for="f_identificacion" class="col-form-label">IDENTIFICACION</label>
										<input type="text" name="f_identificacion" id="f_identificacion" value="">
									</div>
									
									<div class="col-3">
										<label for="f_apellido" class="col-form-label">APELLIDOS</label>
										<input type="text" name="f_apellidos" id="f_apellidos" value="">
									</div>
									
									<div class="col-form-label col-2 btn_filtrar_pasajero" style="position:relative; float: right;">
										<button class="btn btn-outline-dark" id="btn_filtrar" type="submit">FILTRAR</button>
									</div>
									
								</div>		
								<div class="row">
									
								</div>
							</form>
							
						<table class="table table-hover table-dark">
							<thead>
								<tr>
									<th>IDENTIFICACION</th>
									<th>NOMBRES</th>
									<th>APELLIDOS</th>
									<th>FECHA NACIMIENTO</th>
									<th>PASAPORTE</th>
									<th>TELEFONO</th>
									<th>ACCIONES</th>
												
								</tr>
							</thead>
							<tbody>
							<?php while($row = pg_fetch_array($datos)){ ?>
									<tr>
										<td><?php echo $row['id_pasajero'] ?></th>
										<td><?php echo $row['nombre'] ?></th>
										<td><?php echo $row['apellidos'] ?></th>
										<td><?php echo $row['fecha_nac'] ?></th>
										<td><?php echo $row['pasaporte'] ?></th>
										<td><?php echo $row['telefono'] ?></th>
										<td><a class="link_editar" href="View_ActualizarPasajero.php?id=<?php echo $row['id_pasajero'] ?>">EDITAR</a></th>
										<td><a class="link_eliminar" href="View_ActualizarPasajero.php?id=<?php echo $row['id_pasajero'] ?>">ELIMINAR</a></th>

									</tr>
							<?php } ?>
										
							</tbody>
									
						</table>
						<div class="paginador">
							<ul>
								<?php
									if ($pagina!=1) {

								  ?>
								<li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
								<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
								<?php
									}
									for ($i=1; $i <=$total_paginas ; $i++) { 
										if ($i==$pagina) {
											echo '<li class="pagiSelected">'.$i.'</li>';
										}else{
											echo '<li><a href="?pagina='.$i.'">'.$i.'</a>';
										}
										
									}
									if ($pagina!=$total_paginas) {
										
									
								?>

								<li><a href="?pagina=<?php echo $pagina+1; ?>">>></a>
								</li><li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
							<?php } ?>
							</ul>
						</div>

							
						</div>
					</div>
					
					<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>
				</div>

			</div>
		</div>
		

		
	</section>
	
	
</body>
</html>