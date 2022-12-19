<?php 

/**
 * Clase tiquete
 */
include_once ("Conexion.php");

class Tiquete
{
	private $id_pasajero;
	private $cod_vuelo;
	private $cod_clase;
	private $cod_tiquete;
	private $asiento;
	private $metodo_pago;
	private $precio; //VARIABLE TEMPORAL

	//VARIABLES AUXILIARES

	private $sql;	
	private $respuesta;
	private $objcon;

	function __construct()
	{
		$this->objcon=new ConexionBD();
		/*
		$this->id_pasajero=$id_pasajero;
		$this->cod_vuelo=$this->buscar_cod_vuelo($destino,$fecha,$hora);
		$this->cod_clase=$cod_clase;
		$this->cod_tiquete=$this->generador_codigo($id_pasajero,$cod_clase);
		$this->asiento=$asiento;
		$this->metodo_pago=$metodo_pago;
		*/
	}


	public function nuevo_tiquete($id_pasajero,$destino,$cod_clase,$fecha,$hora,$asiento,$metodo_pago,$precio){
		
		$this->id_pasajero=$id_pasajero;
		$this->cod_vuelo=$this->buscar_cod_vuelo($destino,$fecha,$hora);
		$this->cod_clase=$cod_clase;
		$this->cod_tiquete=$this->generador_codigo($id_pasajero,$cod_clase);
		$this->asiento=$asiento;
		$this->metodo_pago=$metodo_pago;
		$this->precio=$precio;


		$this->sql="insert into tiquete(id_pasajero,cod_vuelo,cod_clase,cod_tiquete,asiento,metodo_pago,precio) VALUES('$this->id_pasajero','$this->cod_vuelo','$this->cod_clase','$this->cod_tiquete','$this->asiento','$this->metodo_pago','$this->precio')";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		return $this->respuesta;
	}

	public function llenar_tabla_tiquetes(){
		$this->sql="select v.cod_vuelo,o.fecha as fecha_origen, cd.nombre as destino,o.hora as hora_origen from vuelos v JOIN  origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades co ON co.cod_ciu=o.cod_ciu JOIN ciudades cd ON cd.cod_ciu=d.cod_ciu where o.fecha >= (select current_date)";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		return $this->respuesta;
		
	}

	private function generador_codigo($var1,$var2){
		$time=time();
		$codigo=$var1."-".$var2.date("-His-dmY",$time);
		return $codigo;
	}

	private function buscar_cod_vuelo($destino,$fecha,$hora){
			
		$this->sql="select o.cod_vuelo from destino d JOIN vuelos v ON v.cod_vuelo=d.cod_vuelo JOIN origen o ON v.cod_vuelo=o.cod_vuelo where o.fecha='$fecha' and o.hora='$hora' and d.cod_ciu='$destino'";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		$row=pg_fetch_array($this->respuesta);
		return $row['cod_vuelo'];

	}

	private function obtener_puestos($destino,$fecha,$hora){
			
		$this->sql="select o.cod_vuelo from destino d JOIN vuelos v ON v.cod_vuelo=d.cod_vuelo JOIN origen o ON v.cod_vuelo=o.cod_vuelo where o.fecha='$fecha' and o.hora='$hora' and d.cod_ciu='$destino'";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		$row=pg_fetch_array($this->respuesta);
		return $row['cod_vuelo'];

	}

	public function clases_disponibles(){
		$this->sql="select * from clase";
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);

		return $this->respuesta;
	}

	public function precio_vuelo($clase){
		$this->sql="select precio FROM clase where cod_clase='$clase'";
		
		$this->respuesta=$this->objcon->consulta_retorno($this->sql);
		$row=pg_fetch_array($this->respuesta);
		
		return $row['precio'];
	}

	public function puestos_disponibles(){
		$this->sql="select ";
	}

	public function capacidad_vuelo($destino,$fecha,$hora){
		$this->sql="select a.capacidad from vuelos v JOIN avion a ON v.matricula=a.matricula JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_velo WHERE d.cod_ciu='$destino' and o.fecha='$fecha' and o.hora='$hora'";
	}
	
	public function obtener_vuelo($ciudad,$fecha,$hora){
		return $this->buscar_cod_vuelo($ciudad,$fecha,$hora);
	}
	

}



?>