<?php  

/**
 * 
 */
class Destinos
{
	private $sql;	
	private $respuesta;
	private $objcon;

	function __construct()
	{
		$this->objcon= new ConexionBD();
	}

	public function listar_destinos($desde,$cant){
			$this->sql="select * from ciudades c JOIN pais p ON c.cod_pais=p.cod_pais LIMIT '$cant' OFFSET '$desde'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
	}

	public function agregar_pais($cod_pais,$nombre_pais){

			$this->sql="insert into pais (cod_pais,nombre_pais) VALUES('$cod_pais','$nombre_pais')";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
	}

	public function agregar_ciudad($cod_ciu,$nombre,$cod_pais){

			$this->sql="insert into ciudades (cod_ciu,nombre,cod_pais) VALUES('$cod_ciu','$nombre','$cod_pais')";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
	}

	public function llenar_paises(){
		$this->sql="select cod_pais,nombre_pais from pais";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
	}

	public function buscar_destino($cod_ciu){

			$this->sql="select c.cod_ciu,c.nombre,p.cod_pais,p.nombre_pais from ciudades c JOIN pais p ON c.cod_pais=p.cod_pais where c.cod_ciu ='$cod_ciu'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function actualizar_pais($cod_pais,$nombre_pais){

			$this->sql="update pais SET cod_pais='$cod_pais',nombre_pais='$nombre_pais' where cod_pais='$cod_pais' ";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function actualizar_ciudad($cod_ciu,$nombre,$cod_pais){

			$this->sql="update ciudades SET cod_ciu='$cod_ciu',nombre='$nombre', cod_pais='$cod_pais' where cod_ciu='$cod_ciu' ";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		

		public function eliminar_ciudad($cod_ciu){

			$this->sql="delete from ciudades where cod_ciu='$cod_ciu'";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}

		public function numero_de_destinos(){
			$this->sql="select count(*) from ciudades c JOIN pais p ON c.cod_pais=p.cod_pais ";
			$this->respuesta=$this->objcon->consulta_retorno($this->sql);

			return $this->respuesta;
		}
		
}



?>