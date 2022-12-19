

<?php 
	include ("../otros/validacion.php");
	include_once ("../controlador/controller.php");
	
	$objControl = new Controller();
	$alert="";

		//PAGINADOR TABLA 1
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

		$datos=$objControl->listar_bitacora($desde,$por_pagina);
		//TERMINA PAGINADOR 1

		//PAGINADOR TABLA 2
		if (isset($_GET['pagina2'])) {
			
			
			if(empty($_GET['pagina2'])){
				$pagina2=1;
			}else{
				$pagina2=$_GET['pagina2'];
			}
		}else{
			$pagina2=1;
		}

		$por_pagina2=10;

		$desde2 = ($pagina2-1)*$por_pagina2;
		$total_registros2=$objControl->cantidad_registros_bit();

		$total_paginas2=ceil($total_registros2/$por_pagina2);

		$datos2=$objControl->listar_bitacora($desde2,$por_pagina2);

		//TERMINAE PAGINADOR 2
		$datos=$objControl->llenar_usuarios($desde,$por_pagina);
		
		if (isset($_POST['btn_agregar'])) {
			$mensaje=$objControl->agregar_usuario(strtoupper($_POST['identificacion']),strtoupper($_POST['nombre']." ".$_POST['pass']),strtoupper($_POST['selector_id_rol']));
			$alert=$mensaje;
			$datos=$objControl->llenar_usuarios($desde,$por_pagina);

		}elseif(isset($_POST['btn_buscar'])){
			
			$result=$objControl->filtrar_usuario($_POST['f_id']);
			$result=pg_fetch_array($result);
			
		}elseif(isset($_POST['btn_actualizar'])){
			$mensaje=$objControl->actualizar_usuario(strtoupper($_POST['f_id']),strtoupper($_POST['upd_id']),strtoupper($_POST['upd_nombre']),strtoupper($_POST['upd_selector_id_rol']));
			$alert=$mensaje;
			$datos=$objControl->llenar_usuarios($desde,$por_pagina);

		}elseif(isset($_POST['btn_eliminar'])){
			$mensaje=$objControl->eliminar_usuario($_POST['f_id']);
			$alert=$mensaje;
			$datos=$objControl->llenar_usuarios($desde,$por_pagina);
		}

		if (isset($_GET['id'])) {
			if (isset($_POST['id'])) {
				
			}else{
				if (!empty($_GET['id'])) {
					$result=$objControl->filtrar_usuario($_GET['id']);
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
    				ADMINISTRACION</a>
					</nav>
				<div class="form_registro">

					
					<div class="row">

						<div class="col-12">
							<?php  if ($_SESSION['id_rol']=="1") { ?>

								<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>

							
								<form class="" method="POST" action="">

									<h5>NUEVO USUARIO</h5>
									<div  class="row">
										<div class="col-3">
											<label for="identificacion" class="col-form-label">IDENTIFICACION</label>
											<input type="text" name="identificacion" id="identificacion" required>
										</div>
										<div class="col-3">
											<label for="nombre" class="col-form-label">NOMBRE USUARIO</label>
											<input type="text" name="nombre" id="nombre" required>
										</div>
										<div class="col-3">
											<label for="pass" class="col-form-label">CLAVE</label>
											<input type="password" name="pass" id="pas">
										</div>
										<div class="col-3">
											<label for="rol" class="col-form-label">ROL</label>
											<select class="custom-select col-sm-12" id="selector_id_rol" aria-label="Example select with button addon" name="selector_id_rol">
											 	<option value="1">ADMINISTRADOR</option>
											 	<option value="2">VENDEDOR</option>
											 </select>
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
								<h5>ACTUALIZAR | </h5> <h5> ELIMINAR USUARIO</h5> 
								<div class="form-group row">
									<div class="col-3">
										<label for="f_id" class="col-form-label">IDENTIFICACIÓN ACTUAL</label>
										<input type="text" name="f_id" id="f_id" value='<?php echo isset($result['id_usuario'])? $result['id_usuario'] : ""; ?>' required> 
										
									</div>
									<div class="col-6">
											<button class="btn btn-primary" name="btn_buscar">BUSCAR</button>
											
									</div>

								</div>
								
								<div class="row">
									<div class="col-3">
										<label for="upd_id" class="col-form-label">NUEVA IDENTIFICACIÓN</label>
										<input type="text" name="upd_id" id="upd_id" value='<?php echo isset($result['id_usuario'])? $result['id_usuario'] : ""; ?>' required> 
										
									</div>
									<div class="col-3">
										<label for="upd_nombre" class="col-form-label">NOMBRE</label>
										<input type="text" name="upd_nombre" id="upd_nombre" value='<?php echo isset($result['nomb_usuario'])? $result['nomb_usuario'] : ""; ?>'>
									</div>
									
									<div class="col-3">
										<label for="upd_categoria" class="col-form-label">CLAVE</label>
										<input type="text" name="upd_categoria" id="upd_categoria" value='<?php echo isset($result['categoria'])? $result['categoria'] : ""; ?>'>
									</div>	
									<div class="col-3">
											<label for="rol" class="col-form-label">ROL</label>
											<select class="custom-select col-sm-12" id="upd_selector_id_rol" aria-label="Example select with button addon" name="upd_selector_id_rol">
											 	<option value="1">ADMINISTRADOR</option>
											 	<option value="2">VENDEDOR</option>
											 </select>
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
								<h5>USUARIOS ACTUALES</h5>
								
								<table class="table table-hover table-dark">
										<thead>
											<tr>
												<th>IDENTIFICACIÓN</th>
												<th>NOMBRE</th>
												<th>PASSWORD</th>
												<th>ROL</th>
												<th>ACCIONES</th>
											</tr>
										</thead>
										<tbody>
											<?php while($row = pg_fetch_array($datos)){ ?>
													<tr>
														<td><?php echo $row['id_usuario'] ?></td>
														<td><?php echo $row['nomb_usuario'] ?></td>
														<td><?php echo $row['clave'] ?></td>
														<td><?php echo $row['rol'] ?></td>
														<td><a class="link_editar" href="view_administracion.php?id=<?php echo $row['id_usuario'] ?>">EDITAR</a></td>
														<td><a class="link_eliminar" href="view_administracion.php?id=<?php echo $row['id_usuario'] ?>">ELIMINAR</a></td>	
															
													</tr>
											<?php } ?>				
										</tbody>					
								</table>
								<div class="paginador2">
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

							<!--EMPIEZA TABLA BITACORA-->
							<div class="table_pasajeros">
								<h5>REPORTE BITACORA</h5>
								
								<table class="table table-hover table-dark">
										<thead>
											<tr>
												<th>USUARIO</th>
												<th>TABLA MODIFICADA</th>
												<th>ACCION</th>
												<th>FECHA</th>
												<th>LLAVE ALTERADA</th>
											</tr>
										</thead>
										<tbody>
											<?php while($row = pg_fetch_array($datos2)){ ?>
													<tr>
														<td><?php echo $row['usuario'] ?></td>
														<td><?php echo $row['tabla'] ?></td>
														<td><?php echo $row['accion'] ?></td>
														<td><?php echo $row['fecha'] ?></td>
														<td><?php echo $row['key_modified'] ?></td>
															
													</tr>
											<?php } ?>				
										</tbody>					
								</table>
								<div class="paginador2">
									<ul>
										<?php
											if ($pagina2!=1) {
										  ?>
										<li><a href="?pagina2=<?php echo 1; ?>">|<</a></li>
										<li><a href="?pagina2=<?php echo $pagina2-1; ?>"><<</a></li>
										<?php
											}
											for ($i=1; $i <=$total_paginas2 ; $i++) { 
												if ($i==$pagina2) {
													echo '<li class="pagiSelected2">'.$i.'</li>';
												}else{
													echo '<li><a href="?pagina2='.$i.'">'.$i.'</a>';
												}
											}
											if ($pagina2!=$total_paginas2) {
										?>

										<li><a href="?pagina2=<?php echo $pagina2+1; ?>">>></a>
										</li><li><a href="?pagina2=<?php echo $total_paginas2; ?>">>|</a></li>
									<?php } ?>
									</ul>
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