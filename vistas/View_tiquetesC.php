<?php  include  "plantillas/scripts.php" ?>
<?php include "plantillas/header.php" ?>
<?php 
	
	include ("../controlador/controller.php");
	$objControl = new Controller();
	$datos =$objControl->llenar_tabla_tiquetes();

	$ciudades =$objControl->llenar_ciudades();

	$clases = $objControl->llenar_clase();


	if (!empty($_POST)) {
		if (isset($_POST['btnfacturar']))
		{
 			$mensaje=$objControl->facturacion($_POST['id_pasajero'],$_POST['selector_clase'],$_POST['precio'],"1",$_POST['selector_metodo_de_pago'],$_POST['selector_ciudades'],$_POST['selector_fecha'],$_POST['selector_hora']);

 			echo "<script> view_mensaje('$mensaje')</script>";

		}else if(isset($_POST['btnbuscar'])){
			if(!empty($_POST['id_pasajero'])){
				$id_pasajero=$_POST['id_pasajero'];
				$datos2=$objControl->buscarPasajero($id_pasajero);
				$result=pg_fetch_array($datos2);
			}
		}
	}
	
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="Utf-8">
	
	<link rel="stylesheet" type="text/css" href="css/style_tiquetes.css"></link>
	<script src="js/jquery-3.4.1.min.js"></script>
	<!--<script
	src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
	-->
	<link rel="stylesheet"  href="css/bootstrap.min.css">
	<script src="js/bootstrap.min.js"></script>
	


	<title>AEROPUERTO EL DORADO</title>
</head>
<body>

		<section id="container">
			<?php include "plantillas/menu.php" ?>
			
			
				
		<div class="fondo">

			<div class="content">
				<nav class="navbar navbar-light bg-light">
				  <a class="navbar-brand" > <img src="img\icons\ticket_svg.svg" width="30" height="30" class="d-inline-block align-top" alt="">
    				VENTA</a>
				</nav>

			<div class="form_venta">

				<form class="form_venta_tiquetes" method="POST">

					
						<div class="form-group row">
							<div class="form-inline col-10">
								<label class="col-sm-1.8 col-form-label">ID PASAJERO</label>
							    <div class="col-sm-3">
							      <input type="text" name="id_pasajero" class="form-control" placeholder="identificación" value='<?php echo isset($result['id_pasajero'])? $result['id_pasajero'] : ""; ?>' required>
							    </div>
							   
							    <button type="submit" class="btn btn-primary" name="btnbuscar" value="1">BUSCAR</button>

							</div>
						   

						</div>
					

					
					<div class="form-group row">
						<div class="col-4">
							<label class="col-sm-3 col-form-label">NOMBRES</label>
							<div class="col-form-label col-sm-12">
						      <input type="text" name="nombre" class="form-control col-sm-10" value='<?php echo isset($result['nombre'])? $result['nombre'] : ""; ?>' >
						    </div>
							
						    
						</div>
						<div class="col-4">
							 <label class="col-sm-3 col-form-label">APELLIDOS</label>	
						    <div class="col-form-label col-sm-12">
						      <input type="text" name="apellidos" class="form-control" value='<?php echo isset($result['apellidos'])? $result['apellidos'] : ""; ?>' >
						    </div>
			
						</div>
						<div class="col-4">
							<label class="col-sm-3 col-form-label">PASAPORTE</label>
						    <div class="col-form-label col-sm-12">
						      <input type="text" name="pasaporte" class="form-control" value='<?php echo isset($result['pasaporte'])? $result['pasaporte'] : ""; ?>'>
						    </div>	

						</div>

						
					   
			 	 	</div>
			 	 	<div class="form-group row">
			 	 		<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">DESTINO</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_ciudades" aria-label="Example select with button addon" name="selector_ciudades">

								    <option selected>Seleccione una opción</option>

								    <?php while ($row = pg_fetch_array($ciudades)) { ?>
								    	<option value='<?php echo $row['cod_ciu'] ?>'> <?php echo $row['nombre'] ?></option>
								    <?php } ?> 
								    
								   
								    
								  </select>
								</div>
							</div>	
			 	 		</div>
			 	 		<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">FECHA</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_fecha" aria-label="Example select with button addon" name="selector_fecha">
								    
								  </select>
								</div>
							</div>	
			 	 		</div>
			 	 		<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">HORA</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_hora" aria-label="Example select with button addon" name="selector_hora">
								    
								  </select>
								</div>
							</div>	
			 	 		</div>
		 	 		</div>

		 	 		<div class="form-group row">
		 	 			<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">CLASE</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_clase" aria-label="Example select with button addon" name="selector_clase">

								    <option selected>Seleccione una opción</option>

								    <?php while ($row = pg_fetch_array($clases)) {?>
								    	<option value='<?php echo $row['cod_clase'] ?>'><?php echo $row['nombre'] ?></option>
								    <?php } ?> 

								  </select>
								</div>
							</div>	
			 	 		</div>
			 	 		
			 	 		<div class="col-4">
			 	 			<label class="col-sm-12 col-form-label">PUESTO</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_puesto" aria-label="Example select with button addon" name="selector_puesto">
								    <option selected>Seleccione una opción</option>
								   	
								    
								  </select>
								</div>
							</div>	
			 	 		</div>

			 	 		<div class="col-4">
			 	 			<label class="col-sm-12 col-form-label">METODO DE PAGO</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
								  
								  <select class="custom-select col-sm-10" id="selector_metodo_de_pago" aria-label="Example select with button addon" name="selector_metodo_de_pago">
								    <option selected>Seleccione una opción</option>
								    <option value="EFECTIVO">EFECTIVO</option>
								    <option value="TARJETA">TARJETA</option>
								    
								  </select>
								</div>
							</div>	
			 	 		</div>

			 	 		<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">VUELO</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
			 	 					<input type="text" name="cod_vuelo" id="cod_vuelo" value="" class="" readonly="readonly">
			 	 				</div>
			 	 			</div>
			 	 			
			 	 		</div>

			 	 		<div class="col-4">
			 	 			<label class="col-sm-3 col-form-label">PRECIO</label>
			 	 			<div class="col-form-label col-sm-12">
			 	 				<div class="input-group mb-12">
			 	 					<input type="text" name="precio" id="precio" value="" class="" readonly="readonly">
			 	 				</div>
			 	 			</div>
			 	 			
			 	 		</div>

			 	 		

			 	 		<div class="col-4">
			 	 			<label class="sr-onlyr col-form-label" for="btnFacturar"></label>
			 	 			<div class="col-form-label respuest" id="btn_facturar">
			 	 				 <button type="submit" class="btn btn-primary respuest" name="btnfacturar" id="btnFacturar" value="" onclick="">FACTURAR</button>
			 	 			</div>
			 	 			
			 	 		</div>
			 	 		
			 	 		
			 	 	</div>



			 	 	<div class="alert"></div>
			 	 </form>
			</div>

				
			
			<nav class="navbar navbar-light bg-light">
				  <a class="navbar-brand" ><img src="img\icons\carrito.svg" width="30" height="30" class="d-inline-block align-top" alt=""> VUELOS</a>
			</nav>
			
			<table class="table table-hover table-dark">
				<thead>
					<tr>
						<th>VUELO</th>
						<th>FECHA</th>
						<th>DESTINO</th>
						<th>HORA SALIDA</th>
						
					</tr>
				</thead>
				<tbody>
					<?php while($row = pg_fetch_array($datos)){ ?>
							<tr>
								<td><?php echo $row['cod_vuelo'] ?></td>
								<td><?php echo $row['fecha_origen'] ?></td>
								<td><?php echo $row['destino'] ?></td>
								<td><?php echo $row['hora_origen'] ?></td>
								
							</tr>
							<?php } ?>
					
				</tbody>
				
			</table>

			</div>
		</div>	

		</section>

</body>
</html>

<!--FUNCIONES JAVASCRIPT Y JQUERY-->

<script type="text/javascript">
	$(document).ready(function(){

		$("#selector_ciudades").change(function(){
			
			$("#selector_ciudades").each(function(){
				ciudad=$(this).val();
				
				$.post("../controlador/controller.php",{ciudad:$('#selector_ciudades').val()},function($data){
					$("#selector_fecha").html($data);
				});
			});
			
		});
		$("#selector_fecha").change(function(){
			
			$("#selector_fecha").each(function(){
				fecha=$(this).val();
				
				$.post("../controlador/controller.php",{fecha:$('#selector_fecha').val(),ciudadE:$('#selector_ciudades').val()},function($data){
					$("#selector_hora").html($data);
				});
			});
			
		});
		$("#selector_hora").change(function(){
			
			$("#selector_hora").each(function(){
				hora=$(this).val();
				
				$.post("../controlador/controller.php",{fecha:$('#selector_fecha').val(),ciudadE:$('#selector_ciudades').val(), hora:$('#selector_hora').val()},function($data){
					/*$("#cod_vuelo").html($data);*/
					$("#cod_vuelo").val($data);
				});
			});
			
		});
		
		$("#selector_clase").change(function(){
			
			$("#selector_clase").each(function(){
				hora=$(this).val();
				
				$.post("../controlador/controller.php",{clase:$('#selector_clase').val()},function($data){
					/*$("#cod_vuelo").html($data);*/
					$("#precio").val($data);
				});
			});
			
		});


	});
</script>