<!--PAGINA DEL LOGIN-->

<?php 
    $alert="";
    session_start();
	if (!empty($_SESSION['activo'])) {
	    	header('location: vistas/principal.php');
	}else{

			if (!empty($_POST)) {
				if (empty($_POST['usuario']) || empty($_POST['clave'])) {
					$alert='INGRESE SU USARIO Y CONTRASEÑA';
				}else{
					require_once "otros/logueo.class.php";
					
					$objlogueo = new Logueo($_POST['usuario'],$_POST['clave']);
					echo $_POST['usuario'];
					//$f=$objlogueo;

					//header('location: vistas/principal.php');
				}
				
			}
		}


 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title> EL DORADO  | LOGIN</title>
	<link rel="stylesheet" type="text/css" href="vistas/css/style_index.css"></link>
</head>
<body>
	
	<section id="container">
		

			<form action="" method="post" class="login">
				<img src="vistas/img/icons/icon_D.png" alt="Login" id="avatar_log">
				<h3> Iniciar Sesion</h3>
				
				<input type="text" name="usuario" placeholder="Usuario" class="input_login">
				<input type="password" name="clave" placeholder="Contraseña" class="input_login" autocomplete="off">
				<div class="alert"> <?php echo isset($alert)? $alert : ""; ?></p>
				<input type="Submit" Value="Ingresar">
			</form>
	</section>

</body>
</html>