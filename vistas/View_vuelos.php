<?php 
	include ("../controlador/controller.php");
	include ("../otros/validacion.php");
	

		$objControl = new Controller();
		$ciudades =$objControl->llenar_ciudades();
		$fecha =$objControl->fechas_salidas();

		if (isset($_GET['cantidad'])){
			echo $_GET['cantidad'];
			$por_pagina=$_GET['cantidad'];	
		}else{
			$por_pagina=5;
		}
				if(empty($_GET['pagina'])){
					$pagina=1;
				}else{
					$pagina=$_GET['pagina'];
				}

		$desde = ($pagina-1)*$por_pagina;
		$total_registros=$objControl->cantidad_registros();
		$total_paginas=ceil($total_registros/$por_pagina);

		$datos = $objControl->llenar_vuelos_disponibles($desde,$por_pagina);

		if (isset($_GET['filtro_destino']) && isset($_GET['filtro_fecha'])) {
			if ($_GET['filtro_destino']=="" and $_GET['filtro_fecha']=="") {
				
			}else{
				$datos=$objControl->filtro_vuelos($_GET['filtro_destino'],$_GET['filtro_fecha'],100,0);
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
	<link rel="stylesheet" type="text/css" href="css/style_vuelos.css">
	
	<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	

	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>
	<!--
	<script >
				$(document).ready( function () {
		    $('#table_viewPasajeros').DataTable();
		} );
	</script>
	-->
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
    				VUELOS</a>
				</nav>
				<div class="form_registro">
					
					<div class="row">

						<div class="col-12">
							<form class="" method="GET" action="">
								<h5>SALIDAS</h5>
								<div  class="row">
									<div class="col-3">
										<label for="f_identificacion" class="col-form-label">DESTINO</label>
										<select class="custom-select col-sm-10" id="filtro_destino" aria-label="Example select with button addon" name="filtro_destino">
											 <option value="" selected>Seleccione una opción</option>
										    <?php while ($row = pg_fetch_array($ciudades)) {?>
										    	<option value='<?php echo $row['cod_ciu'] ?>'><?php echo $row['nombre'] ?></option>
										    <?php } ?> 
										    

										</select>
									</div>
									
									<div class="col-3">
										<label for="f_nombre" class="col-form-label">FECHA</label>
										<select class="custom-select col-sm-10" id="filtro_fecha" aria-label="Example select with button addon" name="filtro_fecha">
											<option value="">seleccione una fecha</option>

										<?php while ($row=pg_fetch_array($fecha)) { ?>			
											<option value='<?php echo $row['fecha'] ?>'><?php echo $row['fecha'] ?></option>
										<?php } ?>
										</select>

									</div>
									
									<div class="row">
										<label class="sr-onlyr col-form-label" for="btnFiltrar"></label>
										<div class="col-form-label col-2" style="position:relative; float: right; padding-top: 35px;">
											<button type="submit" class="btn btn-outline-dark" >FILTRAR</button>
										</div>
									</div>

								</div>
								
							</form>
						</div>
					</div>
				</div>

							
				<h5>SALIDAS</h5>
				
				<table class="table table-hover table-dark">
						<thead>
							<tr>
								<th>AEROLINEA</th>
								<th>VUELO</th>
								<th>DESTINO</th>
								<th>FECHA</th>
								<th>NOTA</th>

											
							</tr>
						</thead>
						<tbody>
							<?php while($row = pg_fetch_array($datos)){ ?>
									<tr>
										<td><?php echo $row['aerolinea'] ?></th>
										<td><?php echo $row['cod_vuelo'] ?></th>
										<td><?php echo $row['destino'] ?></th>
										<td><?php echo $row['fecha_salida'] ?></th>
										<td><?php echo $row['orden'] ?></th>	
													
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
							
						
				<div class="alert"></div>

						
			</div>


		</div>
		
		
	</section>
	
</body>
</html>

<script type="text/javascript">
	
$(document).ready(function(){

		$("#cmb_por_pagina").change(function(){
			
			$("#cmb_por_pagina").each(function(){
				cantidad=$(this).val();

				
				$.get("GET","?cantidad='$('#cmb_por_pagina').val()'",true);

				var url=window.location;
				var datos="?cantidad=$('#cmb_por_pagina').val()";
				
				$.get('url','datos',function($respuesta){

				});
		
		/*
				var miAjax = new Request({
				   "url":jQuery(location).attr('href'),
				   "method": "get",
				   "data": "cantidad=$('#cmb_por_pagina').val()",
				   "onSuccess": function($respuesta){
				      
				   }
				}).send(); 


				/*
				$.get('')("../controlador/controller.php",{ciudad:$('#selector_ciudades').val()},function($data){
					$("#selector_fecha").html($data);
				});*/
			});
			
		});



	});

</script>