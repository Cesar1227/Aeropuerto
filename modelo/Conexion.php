<?php
	//error_reporting(0);
	
	if(isset($_SESSION['id_usuario'])){

	}else {
		session_start();
	} 
	
	class ConexionBD{
		

		private $host ="localhost";
		private $dbname ="aeropuerto";
		private $user;
		private $password;

		public $conexion;
		function __construct()
		{
			$this->user=$_SESSION['id_usuario'];
			$this->password=$_SESSION['clave'];
			$this->conexion=pg_connect("host='$this->host' dbname='$this->dbname' user='$this->user' password='$this->password'") or die("NO ES POSIBLE CONECTAR MEDIANTE SU USUARIO A LA BD");
			
		}

		public function consulta_simple($sql){
			pg_query($sql);// or die ("NO SE PUEDO REALIZAR LA CONSULTA");
		}

		public function consulta_retorno($sql){
			$datos=pg_query($sql);// or die ("NO SE PUDO REALIZAR LA CONSULTA");

			return $datos;
		}

	}

?>
