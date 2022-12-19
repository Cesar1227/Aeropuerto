

<?php 
	include ("../otros/validacion.php");
	include_once ("../controlador/controller.php");
	
	$objControl = new Controller();
	$alert="";

		if (isset($_GET['pagina'])) {
			
			
			if(empty($_GET['pagina'])){
				$pagina=1;
			}else{
				$pagina=$_GET['pagina'];
			}
		}else{
			$pagina=1;
		}

		$por_pagina=5;

		$desde = ($pagina-1)*$por_pagina;
		$total_registros=$objControl->cantidad_tripulantes();

		$total_paginas=ceil($total_registros/$por_pagina);

		$datos=$objControl->llenar_tabla_tripulantes($desde,$por_pagina);
		
		if (isset($_POST['btn_agregar'])) {
			$mensaje=$objControl->agregar_tripulante(strtoupper($_POST['identificacion']),strtoupper($_POST['nombre']." ".$_POST['apellido']),strtoupper($_POST['categoria']));
			$alert=$mensaje;
			$datos=$objControl->llenar_tabla_tripulantes($desde,$por_pagina);

		}elseif(isset($_POST['btn_buscar'])){
			
			$result=$objControl->filtrar_tripulante($_POST['upd_id']);
			$result=pg_fetch_array($result);
			
		}elseif(isset($_POST['btn_actualizar'])){
			$mensaje=$objControl->actualizar_tripulante(strtoupper($_POST['upd_id']),strtoupper($_POST['upd_nombre']),strtoupper($_POST['upd_categoria']));
			$alert=$mensaje;
			$datos=$objControl->llenar_tabla_tripulantes($desde,$por_pagina);

		}elseif(isset($_POST['btn_eliminar'])){
			$mensaje=$objControl->eliminar_tripulante($_POST['upd_id']);
			$alert=$mensaje;
			$datos=$objControl->llenar_tabla_tripulantes($desde,$por_pagina);
		}

		if (isset($_GET['id'])) {
			if (isset($_POST['id'])) {
				
			}else{
				if (!empty($_GET['id'])) {
					$result=$objControl->filtrar_tripulante($_GET['id']);
					$result=pg_fetch_array($result);
				}else{

				}
			}
			
		}
	

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	
	<?php  include "./plantillas/scripts.php" ?>
	<link rel="stylesheet" type="text/css" href="css/style_tripulacion.css">

	
	<script src="js/jquery-3.4.1.min.js"></script>
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
				  <a class="navbar-brand" > <img src="img/icons/tripulantes.png" width="30" height="30" class="d-inline-block align-top" alt="">
    				TRIPULACIÓN</a>
					</nav>
				<div class="form_registro">

					
					<div class="row">

						<div class="col-12">
							<?php  if ($_SESSION['id_rol']=="1") { ?>

								<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>

							
								<form class="" method="POST" action="">

									<h5>NUEVO TRIPULANTE</h5>
									<div  class="row">
										<div class="col-3">
											<label for="identificacion" class="col-form-label">IDENTIFICACION</label>
											<input type="text" name="identificacion" id="identificacion" required>
										</div>
										<div class="col-3">
											<label for="nombre" class="col-form-label">NOMBRE</label>
											<input type="text" name="nombre" id="nombre" required>
										</div>
										<div class="col-3">
											<label for="apellido" class="col-form-label">APELLIDO</label>
											<input type="text" name="apellido" id="apellido">
										</div>
										<div class="col-3">
											<label for="categoria" class="col-form-label">CATEGORIA</label>
											<input type="text" name="categoria" id="categoria" required>
										</div>
										
										
									</div>		
									<div class="row">
										<div class="col-form-label col-2" style="position:relative; float: right;">
											<button class="btn btn-outline-dark" type="submit" name="btn_agregar">AGREGAR</button>
										</div>
									</div>
								</form>
							<?php }  ?>

							

							<div class="col-12">
							<form class="" method="POST" action="">
								<h5>ACTUALIZAR | </h5> <?php if($_SESSION['id_rol']=="1") { ?> <h5> ELIMINAR TRIPULANTE</h5> <?php  } ?>
								<div class="form-group row">
									<div class="col-3">
										<label for="upd_id" class="col-form-label">IDENTIFICACIÓN</label>
										<input type="text" name="upd_id" id="upd_id" value='<?php echo isset($result['id_tripulacion'])? $result['id_tripulacion'] : ""; ?>' required> 
										
									</div>
									<div class="col-6">
											<button class="btn btn-primary" name="btn_buscar">BUSCAR</button>
											
									</div>

								</div>
								
								<div class="row">
									<div class="col-3">
										<label for="upd_nombre" class="col-form-label">NOMBRE</label>
										<input type="text" name="upd_nombre" id="upd_nombre" value='<?php echo isset($result['nombre'])? $result['nombre'] : ""; ?>'>
									</div>
									
									<div class="col-3">
										<label for="upd_categoria" class="col-form-label">CATEGORIA</label>
										<input type="text" name="upd_categoria" id="upd_categoria" value='<?php echo isset($result['categoria'])? $result['categoria'] : ""; ?>'>
									</div>	
									<div class="col-3">
										<button class="btn btn-success act" name="btn_actualizar">ACTUALIZAR</button>
										<?php if ($_SESSION['id_rol']=="1") { ?>
											<button class="btn btn-danger eli" name="btn_eliminar">ELIMINAR</button>
										<?php } ?>
										
									</div>
								</div>

							</form>
						</div>

						<!--EMPIEZA TABLA-->
							<div class="table_pasajeros">
								<h5>TRIPULACION ACTUAL</h5>
								
								<table class="table table-hover table-dark">
										<thead>
											<tr>
												<th>IDENTIFICACIÓN</th>
												<th>NOMBRE</th>
												<th>CATEGORIA</th>
												<th>ACCIONES</th>
											</tr>
										</thead>
										<tbody>
											<?php while($row = pg_fetch_array($datos)){ ?>
													<tr>
														<td><?php echo $row['id_tripulacion'] ?></td>
														<td><?php echo $row['nombre'] ?></td>
														<td><?php echo $row['categoria'] ?></td>
														<td><a class="link_editar" href="View_tripulacion.php?id=<?php echo $row['id_tripulacion'] ?>">EDITAR</a></td>
														<td><a class="link_eliminar" href="View_tripulacion.php?id=<?php echo $row['id_tripulacion'] ?>">ELIMINAR</a></td>	
															
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
							<!--TERMINA TABLA-->

							
						</div>
					</div>
					
				</div>
			</div>
		</div>
		

	</section>
	
</body>
</html>