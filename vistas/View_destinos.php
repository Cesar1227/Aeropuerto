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
		$total_registros=$objControl->cantidad_destinos();
		$total_paginas=ceil($total_registros/$por_pagina);
		//TERMINA PAGINADOR

		$datos2=$objControl->llenar_paises();
		$datos=$objControl->listar_destinos($desde,$por_pagina);


		if(isset($_POST['btn_agregar_pais'])){
			$result=$objControl->agregar_pais(strtoupper($_POST['cod_pais']),strtoupper($_POST['nombre_pais']));
			$alert=$result;
			$datos=$objControl->listar_destinos($desde,$por_pagina);
		}elseif((isset($_POST['btn_agregar_ciu']))){
			$result=$objControl->agregar_ciudad(strtoupper($_POST['cod_ciudad']),strtoupper($_POST['nombre_ciudad']),strtoupper($_POST['selector_pais_2']));
			$alert=$result;
			$datos=$objControl->listar_destinos($desde,$por_pagina);
		}elseif(isset($_POST['btn_actualizar'])){
			$result=$objControl->actualizar_pais(strtoupper($_POST['upd_cod_pais']),strtoupper($_POST['upd_nombre_pais']));
			$alert1=$result;
			$result=$objControl->actualizar_ciudad(strtoupper($_POST['upd_cod_ciu']),strtoupper($_POST['upd_nombre']),strtoupper($_POST['upd_cod_pais']));
			$alert2=$result;

			$alert=$alert1." - ".$alert2;
		}elseif(isset($_POST['btn_eliminar'])){
			$result=$objControl->eliminar_ciudad(strtoupper($_POST['f_cod_ciu']));
			$alert=$result;
		}

		if (isset($_GET['cod_ciu'])) {

			if (isset($_POST['cod_ciu'])) {
				
			}else{
				if (!empty($_GET['cod_ciu']) ) {
					$result=$objControl->filtrar_destino($_GET['cod_ciu']);
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
			<a class="navbar-brand" > <img src="img/icons/avion.png" width="30" height="30" class="d-inline-block align-top" alt="image">DESTINOS</a>
				</nav>
					
				<div class="form_registro">
					
					<div class="row">

						<div class="col-12">
							<div class="alert"><button class="close" data-dismiss="alert" style=""><span>&times;</span></button><?php echo isset($alert) ? $alert : '';  ?></div>
							<div class="row">
								
								
								<div class="col-5.5">

								<form class="" method="POST" action="" id="form_pais">
									<h5>NUEVOS PAISES</h5>
									<div  class="row">
										<div class="col-3.5">
											<label for="cod_pais" class="col-form-label">CODIGO PAIS</label>
											<input type="text" name="cod_pais" id="cod_pais" required>
										</div>
										<div class="col-6">
											<label for="nombre_pais" class="col-form-label">NOMBRE</label>
											<input type="text" name="nombre_pais" id="nombre_pais" required>
										</div>
										
									</div>		
									<div class="row">
										<div class="col-form-label col-2" style="position:relative; float: right;">
											<button class="btn btn-outline-dark" type="submit" name="btn_agregar_pais" style="position: flex-end">AGREGAR</button>
										</div>
									</div>
								</form>
							</div>

							<div class="col-6">
								<form class="" method="POST" action="" id="form_ciudad">
									<h5>NUEVOS CIUDADES</h5>
									<div  class="row">
										<div class="col-4">
											<label for="cod_ciudad" class="col-form-label">CODIGO</label>
											<input type="text" name="cod_ciudad" id="cod_ciudad" required>
										</div>
										<div class="col-4">
											<label for="nombre_ciudad" class="col-form-label">NOMBRE</label>
											<input type="text" name="nombre_ciudad" id="nombre_ciudad" required>
										</div>
										<div class="col-4">
											<label for="nombre_pais" class="col-form-label">PAIS</label>

											<select class="custom-select col-12" id="selector_pais_2" aria-label="Example select with button addon" name="selector_pais_2" required>

												<option selected>Seleccione una opción</option>

											    <?php while ($row = pg_fetch_array($datos2)) { ?>
											    	<option value='<?php echo $row['cod_pais'] ?>'> <?php echo $row['nombre_pais'] ?></option>
											    <?php } ?> 
											    
											</select>
										</div>

									</div>		
									<div class="row">
										<div class="col-form-label col-2" style="position:relative; float: right;">
											<button class="btn btn-outline-dark" type="submit" name="btn_agregar_ciu">AGREGAR</button>
										</div>
									</div>
								</form>
							</div>
							
							</div>

							<div class="col-12">

								<form class="" method="POST" action="">
									<h5>ACTUALIZAR |<?php if ($_SESSION['id_rol']=="1") { ?> ELIMINAR DESTINO <?php } ?> </h5>
									<div class="form-group row">
										<div class="col-3">
											<label for="f_cod_ciu" class="col-form-label">CODIGO CIUADAD</label>
											<input type="text" name="f_cod_ciu" id="f_cod_ciu" required value='<?php echo isset($result['cod_ciu'])? $result['cod_ciu'] : ""; ?>'> 
											
										</div>
										<div class="col-6">
												<button class="btn btn-primary" name="btn_buscar">BUSCAR</button>
												
										</div>

									</div>
									
									<div class="row">
										<div class="col-3">
											<label for="upd_cod_ciu" class="col-form-label">CODIGO CIUDAD</label>
											<input type="text" name="upd_cod_ciu" id="upd_cod_ciu" value='<?php echo isset($result['cod_ciu'])? $result['cod_ciu'] : ""; ?>'>
										</div>
										<div class="col-3">
											<label for="upd_nombre" class="col-form-label">NOMBRE</label>
											<input type="text" name="upd_nombre" id="upd_nombre" value='<?php echo isset($result['nombre'])? $result['nombre'] : ""; ?>'>
										</div>
										<div class="col-3">
											<label for="upd_cod_pais" class="col-form-label">CODIGO PAIS</label>
											<input type="text" name="upd_cod_pais" id="upd_cod_pais" value='<?php echo isset($result['cod_pais'])? $result['cod_pais'] : ""; ?>'>
										</div>
										<div class="col-3">
											<label for="upd_nombre_pais" class="col-form-label">NOMBRE PAIS</label>
											<input type="text" name="upd_nombre_pais" id="upd_nombre_pais" value='<?php echo isset($result['nombre_pais'])? $result['nombre_pais'] : ""; ?>'>
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
											<th>COD PAIS</th>
											<th>PAIS</th>
											<th>COD CIUDAD</th>
											<th>CIUDAD</th>
											<th>ACCIONES</th>
										</tr>

									</thead>
									<tbody>
										<?php while($row = pg_fetch_array($datos)){ ?>
												<tr>
													<td><?php echo $row['cod_pais'] ?></th>
													<td><?php echo $row['nombre_pais'] ?></th>
													<td><?php echo $row['cod_ciu'] ?></th>
														<td><?php echo $row['nombre'] ?></th>
													<td><a class="link_editar" href="View_destinos.php?cod_ciu=<?php echo $row['cod_ciu'] ?>">EDITAR</a></td>
													<td><a class="link_eliminar" href="View_destinos.php?cod_ciu=<?php echo $row['cod_ciu'] ?>">ELIMINAR</a></td>

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
						
					<div class="alert"></div>
		


			</div>
		</div>
		
	</section>
	
	<footer>
		
	</footer>
	
</body>
</html>