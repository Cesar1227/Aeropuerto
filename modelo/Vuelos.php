<?php

/**
 * 
 */

include_once("Conexion.php");

class Vuelos
{
	private $sql;	
	private $respuesta;
	private $objcon;

	function __construct()
	{
		$this->objcon=new ConexionBD();
	}

	public function ciudades_disponibles(){
		$this->sql="select c.cod_ciu,c.nombre from ciudades c";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
			
		return $this->respuesta;

	}

	public function rellenar_selects($pk,$condicion,$ciudad){
			
			if ($pk=="d.cod_ciu") {
				
				$this->llenarCMB_fecha($pk,$condicion);
			}else if($pk=="o.fecha"){
				
				$this->llenarCMB_hora($pk,$condicion,$ciudad);
			}
			
			return $this->respuesta;
	}

	public function llenarCMB_fecha($pk,$condicion){
		$this->sql="select v.cod_vuelo,o.fecha AS fecha_origen,o.hora AS hora_origen,o.cod_ciu AS ciudad_origen,d.fecha AS fecha_destino,d.hora AS hora_destino,d.cod_ciu AS ciudad_destino from vuelos v JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo where ".$pk." ='$condicion'";
		
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
	}

	public function llenarCMB_hora($pk,$condicion,$ciudad){
		$this->sql="select v.cod_vuelo,o.fecha AS fecha_origen,o.hora AS hora_origen,o.cod_ciu AS ciudad_origen, d.cod_ciu AS ciudad_destino from vuelos v JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo  where ".$pk." ='$condicion' and d.cod_ciu ='$ciudad'";
		
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
	}

	public function vuelos_disponibles($desde,$cant){



		$this->sql="select a.aerolinea,v.cod_vuelo, c.nombre AS destino, o.fecha AS fecha_salida, d.orden FROM vuelos v JOIN avion a ON v.matricula=a.matricula JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades c ON d.cod_ciu=c.cod_ciu ORDER BY a.aerolinea LIMIT '$cant' OFFSET '$desde' ";
			
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		return $this->respuesta;
	}


	public function cantidad_registros(){
		$this->sql="select COUNT(*) AS cantidad FROM vuelos";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		$this->respuesta=pg_fetch_array($this->respuesta);
		return $this->respuesta['cantidad'];
	}

	public function fechas(){
		
		$hoy=date("Y-m-d");
		
		$this->sql="select o.fecha FROM origen o WHERE fecha >='$hoy'";
		
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		return $this->respuesta;
	}

	public function consulta($sql){
		$this->respuesta=$this->objcon->consulta_retorno($sql);
		return $this->respuesta;
	}

}


?>