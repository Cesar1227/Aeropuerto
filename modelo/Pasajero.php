<?php 
	
	/**
	 * 
	 */
	include_once("Conexion.php");

	class Pasajero{

		private $sql;	
		private $respuesta;
		private $objcon;

		private $idPasajero;
		private $nombPasajero;
		private $apelliPasajero;
		private $fechaNacPasajero;
		private $pasPasajero;
		private $telPasajero;

		public function __construct()
		{
			$this->objcon=new ConexionBD();
		}



		public function nuevo_pasajero($idPasajero,$nombPasajero,$apelliPasajero,$fechaNacPasajero,$pasPasajero,$telPasajero){

			$this->idPasajero=$idPasajero;
			$this->nombPasajero=$nombPasajero;
			$this->apelliPasajero=$apelliPasajero;
			$this->fechaNacPasajero=$fechaNacPasajero;
			$this->pasPasajero=$pasPasajero;
			$this->telPasajero=$telPasajero;

			$this->sql="insert into pasajero (id_pasajero,nombre,apellidos,fecha_nac,pasaporte,telefono) VALUES('$this->idPasajero','$this->nombPasajero','$this->apelliPasajero','$this->fechaNacPasajero','$this->pasPasajero','$this->telPasajero')" ;
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;
			
		}

		public function buscarPasajero($id){
			$this->sql="select p.nombre, p.apellidos, p.pasaporte, p.id_pasajero, p.telefono, p.fecha_nac from pasajero p where id_pasajero='$id'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			
			return $this->respuesta;
		}

		public function listar_pasajeros($desde,$cant){
			$this->sql="select * from pasajero LIMIT '$cant' OFFSET '$desde'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			
			return $this->respuesta;
		}

		public function consulta($sql){
			$this->respuesta=$this->objcon->consulta_retorno($sql);
		return $this->respuesta;
		}
		
		public function cantidad_pasajeros(){
			$this->sql="select COUNT(*) AS cantidad from pasajero";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			$this->respuesta=pg_fetch_array($this->respuesta);
			return $this->respuesta['cantidad'];
		}
		
		public function actualizar_pasajero($id,$nombre,$apellidos,$fecha_nac,$pasaporte,$telefono){
			$this->sql="update pasajero SET id_pasajero='$id', nombre='$nombre', apellidos='$apellidos', fecha_nac='$fecha_nac', pasaporte='$pasaporte', telefono='$telefono' WHERE id_pasajero='$id' ";
			
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;

		}	

		public function eliminar_pasajero($id){
			$this->sql="delete from pasajero where id_pasajero='$id'";

			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
			return $this->respuesta;
		}

	}


?>