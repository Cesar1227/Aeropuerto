<?php  

/**
 * 
 */
class Administracion
{
	private $sql;	
		private $respuesta;
		private $objcon;
	function __construct()
	{
		$this->objcon= new ConexionBD();
	}

	public function listar_usuarios($desde,$por_pagina){
			$this->sql="select u.id_usuario,u.nomb_usuario,u.clave,r.rol from usuario u JOIN roles r ON u.id_rol=r.id_rol LIMIT '$por_pagina' OFFSET '$desde'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function cantidad_usuarios(){
			$this->sql="select COUNT(*) from usuario";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function nuevo_usuarios($id_usuarios,$nombre,$clave,$id_rol){
			$this->sql="insert into usuario VALUES($id_usuarios,$nombre,$clave,$id_rol)";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function actualizar_usuario($idactual,$id_usuarios,$nombre,$clave,$id_rol){
			$this->sql="update usuario set id_usuario='$id_usuarios', nomb_usuario='$nombre', clave='$clave',id_rol='$id_rol' where id_usuario='$idactual' ";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function eliminar_usuario($id_usuarios){
			$this->sql="delete from usuario where id_usuario='$id_usuarios' ";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function listar_bitacora($desde,$cant){
			$this->sql="select * from bitacora LIMIT '$cant' OFFSET '$desde'";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}

	public function cantidad_registros_bit(){
			$this->sql="select COUNT(*) from bitacora ";
			$this->respuesta= $this->objcon->consulta_retorno($this->sql);
				
			return $this->respuesta;
	}


}

?>