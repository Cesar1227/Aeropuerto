<?php

		include "modelo/Conexion.php";
		class Logueo
		{
			
			/*public $user = $_POST['usuario'];
			public $clave =$_POST['clave'];
*/
			public $user;
			public $clave;

			public $objcon ;

			function __construct($user,$clave){
				$this->user=$user;
				$this->clave=$clave;
				$_SESSION['id_usuario']=$this->user;
				$_SESSION['clave']=$this->clave;
				$this->objcon = new ConexionBD($_SESSION['id_usuario'],$_SESSION['clave']);
				//header('location: vistas/principal.php');
				$this->validar();
				return true;
			}

			 

			/*$query="select * from usuario where id_usuario='$user' and clave='$clave'";
			$result=pg_query($query) or die("DATOS INCORRECTOS");
			$validacion = pg_num_rows($result);*/

			public function validar(){

				$query ="select * from usuario where id_usuario='$this->user' and clave='$this->clave'";
				$result =$this->objcon->consulta_retorno($query);
				$validacion = pg_num_rows($result);

				echo $validacion;
				if($validacion >=1) {

					$data = pg_fetch_array($result);
								
					$_SESSION['activo']=true;
					$_SESSION['id_usuario']=$data['id_usuario'];
					$_SESSION['id_rol']=$data['id_rol'];
					$_SESSION['nomb_usuario']=$data['nomb_usuario'];
					$_SESSION['clave']=$data['clave'];

					header('location: vistas/principal.php');
				}else{
					$alert="USUARIO O CLAVE INCORRECTOS";
					echo "NO SE PUDO";
					//session_destroy();
				}
			}
			
		}
		
?>