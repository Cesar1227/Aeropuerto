<?php 

	
/**
 * 
 */

	include_once("Conexion.php");

	class Tripulante
	{
		private $sql;	
		private $respuesta;
		private $objcon;

		public function __construct()
		{
			$this->objcon= new ConexionBD();
		}

		public function listar_tripulantes($desde,$por_pagina){
			$this->sql="select * from tripulacion LIMIT '$por_pagina' OFFSET '$desde'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
		}

		public function cantidad_tripulantes(){
			$this->sql="select COUNT(*) from tripulacion";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
		}

		public function nuevo_tripulante($id_tripulante,$nombre,$categoria){
			$this->sql="insert into tripulacion (id_tripulacion,nombre,categoria) VALUES('$id_tripulante','$nombre','$categoria')";

			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
		}

		public function buscar_tripulante($id){
			$this->sql="select * from tripulacion where id_tripulacion='$id'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;

		}
		public function actualizar_tripulante($id_tripulante,$nombre,$categoria){
			$this->sql="update tripulacion SET nombre='$nombre', categoria='$categoria' WHERE id_tripulacion='$id_tripulante' ";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;
		}

		public function eliminar_tripulante($id_tripulante){
			$this->sql="delete from tripulacion where id_tripulacion='$id_tripulante' ";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;
		}

	}

?>