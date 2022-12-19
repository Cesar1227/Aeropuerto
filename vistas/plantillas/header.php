<?php 
include_once("./../otros/validacion.php");

 ?>
<header>
		<div class="header">
			
			<a href="#"><img class="logo" src="img/logoElDorado.png" alt="EL DORADO"></a>
			
			<div class="optionsBar">
				<p>Colombia, <?php echo fechaC(); ?></p>
				<span>|</span>

				<span class="user"><?php echo $_SESSION['nomb_usuario'] ?></span>
				<a class="btn btn-secondary dropdown-toggle" <?php if ($_SESSION['id_rol']=="1") { ?> href="view_administracion.php" <?php } ?> role="button" id="dropdownMenuLink" data-toggle="dropdown-toggle" aria-haspopup="true" aria-expanded="false" style="background: #424949; border:none;">
			    	<img class="photouser" src="img/icons/perfil.svg" alt="Usuario">
			  	</a>
				
				<a href="../otros/cerrarsesion.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>

			</div>
		</div>
		
	
</header>
<div class="modal" id="modal" style="">
	<div class="bodyModal">
		
		
		<form method="POST" name="form_form_modal" id="form_form_modal" onsubmit="event.preventDefault();">
			<div class="mensaje">
				<label class="mensaje_msgModal" value="3"></label>
			</div>
			<button type="submit" class="btn_ok cerrar"><i class="fas fa-ban"><a href="#" class="btn_cerrar close-modal" onclick="closeModal();"></a></i>CERRAR</button>
		</form>
		
	</div>
</div>

