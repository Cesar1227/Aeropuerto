<?php 

	include ("../otros/validacion.php");
	include_once ("../controlador/controller.php");
	$objControl = new Controller();
	$alert="";

		//PAGINADOR
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
		$total_registros=$objControl->cantidad_aviones();
		$total_paginas=ceil($total_registros/$por_pagina);
		//TERMINA PAGINADOR

		$datos=$objControl->listar_aviones($desde,$por_pagina);

		if(isset($_POST['btn_agregar'])){
			$mensaje=$objControl->agregar_avion(strtoupper($_POST['matricula']),$_POST['capacidad'],strtoupper($_POST['aerolinea']));
			$alert=$mensaje;

		}elseif(isset($_POST['btn_buscar'])){
			
			$result=$objControl->filtrar_avion($_POST['f_matricula']);
			
			$result=pg_fetch_array($result);
		}elseif(isset($_POST['btn_actualizar'])){
			
			$result=$objControl->actualizar_avion($_POST['f_matricula'],strtoupper($_POST['upd_matricula']),$_POST['upd_capacidad'],strtoupper($_POST['upd_aerolinea']));
			$alert=$result;
			$datos=$objControl->listar_aviones($desde,$por_pagina);
		}elseif(isset($_POST['btn_eliminar'])){
			
			$result=$objControl->eliminar_avion($_POST['f_matricula']);
			$alert=$result;
			$datos=$objControl->listar_aviones($desde,$por_pagina);
		}

		if (isset($_GET['matricula'])) {

			if (isset($_POST['f_matricula'])) {
				
			}else{
				if (!empty($_GET['matricula']) ) {
					$result=$objControl->filtrar_avion($_GET['matricula']);
					$result=pg_fetch_array($result);
				}
			}
			
		}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	<?php  include "plantillas/scripts.php" ?>
	<link rel="stylesheet" type="text/css" href="css/style_destinos.css">
	
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	
	
	

	</script>
	<title>AEROPUERTO EL DORADO</title>
</head>
<body>
	<?php include "plantillas/header.php" ?>

	
		
	<section id="containerPasajeros">
		<?php include "plantillas/menu.php" ?>

		<div class="fondo">
			<div class="content">
				<nav class="navbar navbar-light bg-light">
			<a class="navbar-brand" > <img src="img/icons/avion.png" width="30" height="30" class="d-inline-block align-top" alt="image">AVIONES</a>
			</nav>
				
			<div class="form_registro">
				
				<div class="row">

					<div class="col-12">

						<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>

						<?php if ($_SESSION['id_rol']=="1") { ?>

						<form class="" method="POST" action="">
							<h5>AGREAR AVION</h5>
							<div  class="row">
								<div class="col-3">
									<label for="matricula" class="col-form-label">MATRICULA</label>
									<input type="text" name="matricula" id="matricula" required>
								</div>
								<div class="col-3">
									<label for="capacidad" class="col-form-label">CAPACIDAD</label>
									<input type="text" name="capacidad" id="capacidad" required>
								</div>
								<div class="col-3">
									<label for="aerolinea" class="col-form-label">AEROLINEA</label>
									<input type="text" name="aerolinea" id="aerolinea" required>
								</div>
								
							</div>		
							<div class="row">
								<div class="col-form-label col-2" style="position:relative; float: right;">
									<button class="btn btn-outline-dark" type="submit" name="btn_agregar">AGREGAR</button>
								</div>
							</div>
						</form>

						<?php } ?>

						<div class="col-12">

							<form class="" method="POST" action="">
								<h5>ACTUALIZAR | <?php if ($_SESSION['id_rol']=="1") { ?> ELIMINAR AVION <?php } ?> </h5>
								<div class="form-group row">
									<div class="col-3">
										<label for="act_matricula" class="col-form-label">MATRICULA</label>
										<input type="text" name="f_matricula" id="act_matricula" required value='<?php echo isset($result['matricula'])? $result['matricula'] : ""; ?>'> 
										
									</div>
									<div class="col-6">
											<button class="btn btn-primary" name="btn_buscar">BUSCAR</button>
											
									</div>

								</div>
								
								<div class="row">
									<div class="col-3">
										<label for="upd_matricula" class="col-form-label">MATRICULA</label>
										<input type="text" name="upd_matricula" id="upd_matricula" value='<?php echo isset($result['matricula'])? $result['matricula'] : ""; ?>'>
									</div>
									<div class="col-3">
										<label for="upd_capacidad" class="col-form-label">CAPACIDAD</label>
										<input type="text" name="upd_capacidad" id="upd_capacidad" value='<?php echo isset($result['capacidad'])? $result['capacidad'] : ""; ?>'>
									</div>
									<div class="col-3">
										<label for="upd_aerolinea" class="col-form-label">AEROLINEA</label>
										<input type="text" name="upd_aerolinea" id="upd_aerolinea" value='<?php echo isset($result['aerolinea'])? $result['aerolinea'] : ""; ?>'>
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
					</div>
				</div>
			</div>

						<!--INICIA TABLA-->
						<div class="table_pasajeros">
							<h5>DISPONIBILIDAD</h5>
							<br>
							<table class="table table-hover table-dark">
								<thead>
									<tr>
										<th>MATRICULA</th>
										<th>CAPACIDAD</th>
										<th>AEROLINEA</th>
										<th>ACCIONES</th>

									</tr>
								</thead>
								<tbody>
									<?php while($row = pg_fetch_array($datos)){ ?>
											<tr>
												<td><?php echo $row['matricula'] ?></th>
												<td><?php echo $row['capacidad'] ?></th>
												<td><?php echo $row['aerolinea'] ?></th>
												<td><a class="link_editar" href="View_aviones.php?matricula=<?php echo $row['matricula'] ?>">EDITAR</a></td>
												<td><a class="link_eliminar" href="View_aviones.php?matricula=<?php echo $row['matricula'] ?>">ELIMINAR</a></td>
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
						<!--TERMINA TABLA-->
				
			</div>
		</div>
		
	</section>
	
	<footer>
		
	</footer>
	
</body>
</html>