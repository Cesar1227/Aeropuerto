<?php 

	
	/**
	 * 
	 */

	include_once ("Conexion.php");

	class Avion
	{
		private $sql;	
		private $respuesta;
		private $objcon;

		function __construct()
		{
			$this->objcon= new ConexionBD();
		}

		public function listar_aviones($desde,$cant){
			$this->sql="select * from avion LIMIT '$cant' OFFSET '$desde'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function nuevo_avion($matricula,$capacidad,$aerolinea){

			$this->sql="insert into avion (matricula,capacidad,aerolinea) VALUES('$matricula','$capacidad','$aerolinea')";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function buscar_avion($matricula){

			$this->sql="select * from avion where matricula='$matricula'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function actualizar_avion($matricula_act,$matriculaN,$capacidad,$aerolinea){

			$this->sql="update avion SET matricula='$matriculaN',capacidad='$capacidad',aerolinea='$aerolinea' where matricula='$matricula_act' ";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function eliminar_avion($matricula){

			$this->sql="delete from avion where matricula='$matricula'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function numero_de_aviones(){
			$this->sql="select count(*) from avion";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}


	}

?>