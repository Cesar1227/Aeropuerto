<?php

	include_once ("../modelo/Tiquete.php");
	include_once ("../modelo/Vuelos.php");
	include_once ("../modelo/Pasajero.php");
	include_once ("../modelo/Tripulante.php");
	include_once ("../modelo/Avion.php");
	include_once ("../modelo/Destinos.php");
	include_once ("../modelo/Administracion.php");
	
	
	$objControl = new Controller();

	if (!empty($_POST)) {
		if(!empty($_POST['ciudad'])){
			$seleccion=$_POST['ciudad'];
			
			$objControl->rellenar_select("d.cod_ciu",$seleccion,$seleccion);
			
		}elseif (!empty($_POST['hora'])) {
			
			if(isset($_POST['hora'])){
				$objControl->obtener_cod_vuelo($_POST['ciudadE'],$_POST['fecha'],$_POST['hora']);
			}else{
				$objControl->obtener_puestos($_POST['ciudadE'],$_POST['fecha'],$_POST['hora']);
			}
			

		}else if(!empty($_POST['fecha'])){
			$seleccion=$_POST['fecha'];
			$ciudad=$_POST['ciudadE'];
			$objControl->rellenar_select("o.fecha",$seleccion,$ciudad);


		}else if (!empty($_POST['clase'])) {
			$objControl->precio_vuelo($_POST['clase']);

		} 
		
		
	}
	
	class Controller
	{
		
		private $datos;
		private $datos2;

		private $objtique;
		private $objvuelo;
		private $objpasaj;
		private $objtri;
		private $objdest;
		private $objadmin;

		function __construct()
		{
			
			
		}

		#FUNCIONES TIQUETES

		public function llenar_tabla_tiquetes(){
			/*$this->sql="select v.cod_vuelo,o.fecha as fecha_origen, cd.nombre as destino,o.hora as hora_origen from vuelos v JOIN  origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades co ON co.cod_ciu=o.cod_ciu JOIN ciudades cd ON cd.cod_ciu=d.cod_ciu where o.fecha >= (select current_date)";*/
			$this->objtique= new Tiquete();
			$this->datos = $this->objtique->llenar_tabla_tiquetes();

			return $this->datos;
		}


		public function rellenar_select($pk,$cambio,$ciudad){
			$this->objvuelo= new Vuelos();

			$this->datos=$this->objvuelo->rellenar_selects($pk,$cambio,$ciudad);
			
			$cadena="<option selected>Seleccione una opción</option>";
			if($pk=="d.cod_ciu") {
				
				while ($row=pg_fetch_array($this->datos)) {
					$dato=$row['fecha_origen'];
					$cadena=$cadena.'<option value='.$dato.'>'.$dato.'</option>';
							  
				}
			}else if ($pk=="o.fecha") {
				
				while ($row=pg_fetch_array($this->datos)) {
					
					$dato=$row['hora_origen'];
					$cadena=$cadena.'<option value='.$dato.'>'.$dato.'</option>';
							  
				}
			}
			
			echo $cadena;
		}


		public function facturacion($id_pasajero,$cod_clase,$precio,$asiento,$metodo_pago,$destino,$fecha,$hora){
			
			if(empty($id_pasajero) || $destino=="Seleccione una opción" || $fecha=="Seleccione una opción" || $hora=="Seleccione una opción" || $cod_clase=="Seleccione una opción" || $metodo_pago=="Seleccione una opción"){
				
				return "TODOS LOS CAMPOS SON OBLIGATORIOS";
			}else{

				$objtique = new Tiquete();
				
				$this->datos=$objtique->nuevo_tiquete($id_pasajero,$destino,$cod_clase,$fecha,$hora,$asiento,$metodo_pago,$precio);
				

				if($this->datos){
					return "SU TIQUETE SE HA GENERADO CORRECTAMENTE";
				}else{
					return "NO SE HA PODIDO GENERAR SU TIQUETE, ES POSIBLE QUE YA TENGA UN TIQUETE PARA ESTE VUELO";
				}

				
			}
			
		}

		public function llenar_clase(){

			$objtique = new Tiquete();
			
			return $objtique->clases_disponibles();
		}

		public function precio_vuelo($clase){
			$objtique = new Tiquete();
			$this->datos=$objtique->precio_vuelo($clase);

			echo $this->datos;
		}

		public function obtener_cod_vuelo($ciudad,$fecha,$hora){
			$objtique=new Tiquete();
			$this->datos=$objtique->obtener_vuelo($ciudad,$fecha,$hora);
			
			echo $this->datos;
		}

		public function obtener_puestos($ciudad,$fecha,$hora){
			$objtique=new Tiquete();
			$this->datos=$objtique->obtener_puestos($ciudad,$fecha,$hora);
			




			echo $this->datos;
		}


		//FUNCIONES VUELOS

		public function llenar_ciudades(){
			$this->objvuelo=new Vuelos();
			$this->datos=$this->objvuelo->ciudades_disponibles();
			
			return $this->datos;
		}

		public function llenar_vuelos_disponibles($desde,$cant){

			$this->objvuelo=new Vuelos();
			$this->datos=$this->objvuelo->vuelos_disponibles($desde,$cant);
			return $this->datos;
		}

		public function cantidad_registros(){
			$this->objvuelo=new Vuelos();
			$this->datos=$this->objvuelo->cantidad_registros();
			return $this->datos;
		}

		public function filtro_vuelos($destino,$fecha,$cant,$desde){
			$this->objvuelo=new Vuelos();
			
			if (empty($destino) && empty($fecha)) {
				$this->datos=$this->llenar_vuelos_disponibles(0,100);
			}else if (!empty($destino) && !empty($fecha)) {
				$this->sql="select a.aerolinea,v.cod_vuelo, c.nombre AS destino, o.fecha AS fecha_salida, d.orden FROM vuelos v JOIN avion a ON v.matricula=a.matricula JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades c ON d.cod_ciu=c.cod_ciu where d.cod_ciu='$destino' and o.fecha ='$fecha' ORDER BY a.aerolinea";
				$this->datos=$this->objvuelo->consulta($this->sql);
			}else if (!empty($destino) && empty($fecha)) {
				$this->sql="select a.aerolinea,v.cod_vuelo, c.nombre AS destino, o.fecha AS fecha_salida, d.orden FROM vuelos v JOIN avion a ON v.matricula=a.matricula JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades c ON d.cod_ciu=c.cod_ciu where d.cod_ciu='$destino' ORDER BY a.aerolinea";
				$this->datos=$this->objvuelo->consulta($this->sql);
			}else if (empty($destino) && !empty($fecha)) {
				$this->sql="select a.aerolinea,v.cod_vuelo, c.nombre AS destino, o.fecha AS fecha_salida, d.orden FROM vuelos v JOIN avion a ON v.matricula=a.matricula JOIN origen o ON v.cod_vuelo=o.cod_vuelo JOIN destino d ON v.cod_vuelo=d.cod_vuelo JOIN ciudades c ON d.cod_ciu=c.cod_ciu where o.fecha ='$fecha' ORDER BY a.aerolinea";
				$this->datos=$this->objvuelo->consulta($this->sql);
			}
			return $this->datos;
		}

		public function fechas_salidas(){
			$this->objvuelo=new Vuelos();
			$this->datos=$this->objvuelo->fechas();
			return $this->datos;
		}

		//FUNCIONES PASAJEROS

		public function listar_pasajeros($desde,$cant){
			$this->objpasaj= new Pasajero();
			$this->datos= $this->objpasaj->listar_pasajeros($desde,$cant);
			return $this->datos;
		}

		public function buscarPasajero($id){
			$this->objpasaj= new Pasajero();

			$this->datos= $this->objpasaj->buscarPasajero($id);;
			
			return $this->datos;
		}

		public function filtro_pasajeros($id_pasajero,$apellidos,$cant,$desde){
			$this->objpasaj= new Pasajero();
			if (empty($id_pasajero) && empty($apellidos)) {
				$this->datos=$this->listar_pasajeros();
			}else if (!empty($id_pasajero) && !empty($apellidos)) {
				$this->sql="select * from pasajero where id_pasajero LIKE '$id_pasajero' and apellido LIKE '$apellidos'";
				$this->datos=$this->objpasaj->consulta($this->sql);

			}else if (!empty($id_pasajero) && empty($apellidos)) {
				$this->sql="select * from pasajero where id_pasajero LIKE '$id_pasajero'";
				$this->datos=$this->objpasaj->consulta($this->sql);
			}else if (empty($id_pasajero) && !empty($apellidos)) {
				$this->sql="select * from pasajero where apellidos LIKE '$apellidos'";
				$this->datos=$this->objpasaj->consulta($this->sql);
			}
			return $this->datos;

		}

		public function cantidad_pasajeros(){
			$this->objpasaj= new Pasajero();

			$this->datos= $this->objpasaj->cantidad_pasajeros();
			
			return $this->datos;
		}


		// NUEVO PASAJERO

		public function ingresar_pasajero($id,$nombre,$apellido,$fechaNac,$pasaporte,$telefono){

			$this->objpasaj= new Pasajero();

			$this->datos= $this->objpasaj->nuevo_pasajero($id,$nombre,$apellido,$fechaNac,$pasaporte,$telefono);
			$row=pg_affected_rows($this->datos);
			if ($row!=0) {
				$row="EL USUARIO HA SIDO INGRESADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
			}else{
				$row="OCURRIO UN ERROR AL INGRESAR EL USUARIO, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}

		}


		//ACTUALIZAR PASAJERO

		public function actualizar_pasajero($id,$nombre,$apellidos,$fecha_nac,$pasaporte,$telefono){
			$this->objpasaj= new Pasajero();

			$this->datos= $this->objpasaj->actualizar_pasajero($id,$nombre,$apellidos,$fecha_nac,$pasaporte,$telefono);
			$row=pg_affected_rows($this->datos);
			if ($row!=0) {
				$row="LOS DATOS FUERON ACTUALIZADOS CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
			}else{
				$row="OCURRIO UN ERROR AL ACTUALIZAR LOS DATOS, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			

		}

		public function eliminar_pasajero($id){
			$this->objpasaj= new Pasajero();

			$this->datos= $this->objpasaj->eliminar_pasajero($id);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL USUARIO HA SIDO ELIMINADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ELIMINAR USUARIO, INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}


		//FUNCIONES TRIPULANTES

		public function llenar_tabla_tripulantes($desde,$por_pagina){
			$this->objtri= new Tripulante();
			$this->datos=$this->objtri->listar_tripulantes($desde,$por_pagina);
			return $this->datos;

		}
		public function cantidad_tripulantes(){
			$this->objtri= new Tripulante();
			$this->datos=$this->objtri->cantidad_tripulantes();
			
			return $this->datos;
		}

		public function agregar_tripulante($id_tripulante,$nombre,$categoria){
			$this->objtri= new Tripulante();
			$this->datos=$this->objtri->nuevo_tripulante($id_tripulante,$nombre,$categoria);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL TRIPULANTE HA SIDO AGREGADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR TRIPULANTE, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}

		public function filtrar_tripulante($id){
			$this->objtri= new Tripulante();
			$this->datos=$this->objtri->buscar_tripulante($id);
			
			return $this->datos;
		}

		public function actualizar_tripulante($id_tripulante,$nombre,$categoria){
			$this->objtri= new Tripulante();
			$this->datos=$this->objtri->actualizar_tripulante($id_tripulante,$nombre,$categoria);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL TRIPULANTE HA SIDO ACTUALIZADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ACTUALIZAR TRIPULANTE, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}

		public function eliminar_tripulante($id_tripulante){

			$this->objtri= new Tripulante();

			$this->datos=$this->objtri->eliminar_tripulante($id_tripulante);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL TRIPULANTE HA SIDO ELIMINADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ELIMINAR TRIPULANTE USUARIO, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}


		//FUNCIONES AVIONES

		public function listar_aviones($desde,$cant){
			$this->objavion = new Avion();
			$this->datos=$this->objavion->listar_aviones($desde,$cant);
			return $this->datos;
		}

		public function agregar_avion($matricula,$capacidad,$aerolinea){
			$this->objavion = new Avion();
			$this->datos=$this->objavion->nuevo_avion($matricula,$capacidad,$aerolinea);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA MATRICULA DE AVION HA SIDO AGREGADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR LA MATRICULA DE AVION, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function filtrar_avion($matricula){
			$this->objavion = new Avion();
			$this->datos=$this->objavion->buscar_avion($matricula);
			return $this->datos;
		}

		public function actualizar_avion($matricula_act,$matriculaN,$capacidad,$aerolinea){
			$this->objavion = new Avion();
			$this->datos=$this->objavion->actualizar_avion($matricula_act,$matriculaN,$capacidad,$aerolinea);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA MATRICULA DE AVION HA SIDO ACTUALIZADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ACTUALIZAR LA MATRICULA DE AVION, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function eliminar_avion($matricula){
			$this->objavion = new Avion();
			$this->datos=$this->objavion->eliminar_avion($matricula);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA MATRICULA DE AVION HA SIDO ELIMINADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ELIMINAR LA MATRICULA DE AVION, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function cantidad_aviones(){
			$this->objavion= new Avion();
			$this->datos=$this->objavion->numero_de_aviones();
			
			return $this->datos;
		}

		//FUNCIONES DESTINOS

		public function listar_destinos($desde,$por_pagina){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->listar_destinos($desde,$por_pagina);
			return $this->datos;
		}
		public function cantidad_destinos(){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->numero_de_destinos();
			return $this->datos;
		}

		public function agregar_pais($cod_pais,$nombre){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->agregar_pais($cod_pais,$nombre);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL PAIS HA SIDO AGREGADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR PAIS, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function agregar_ciudad($cod_ciu,$nombre,$cod_pais){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->agregar_ciudad($cod_ciu,$nombre,$cod_pais);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA CIUDAD HA SIDO AGREGADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR CIUDAD, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function llenar_paises(){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->llenar_paises();
			return $this->datos;
		}

		public function filtrar_destino($cod_ciu){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->buscar_destino($cod_ciu);
			return $this->datos;
		}

		public function actualizar_pais($cod_pais,$nombre){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->actualizar_pais($cod_pais,$nombre);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL PAIS HA SIDO ACTUALZIADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ACTUALIZAR PAIS, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function actualizar_ciudad($cod_ciu,$nombre,$cod_pais){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->actualizar_ciudad($cod_ciu,$nombre,$cod_pais);

			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA CIUDAD HA SIDO ACTUALIZADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ACTUALIZAR CIUDAD, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		public function eliminar_ciudad($cod_ciu){
			$this->objdest = new Destinos();
			$this->datos= $this->objdest->eliminar_ciudad($cod_ciu);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "LA CIUDAD HA SIDO ELIMINADA CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ELIMINAR CIUDAD, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
		}

		//FUNCIONES ADMINISTRACION

		public function llenar_usuarios($desde,$por_pagina){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->listar_usuarios($desde,$por_pagina);
			return $this->datos;

		}
		public function cantidad_usuarios(){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->cantidad_usuarios();
			
			return $this->datos;
		}

		public function agregar_usuario($id_usuarios,$nombre,$clave,$id_rol){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->nuevo_usuario($id_usuarios,$nombre,$clave,$id_rol);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL USUARIO HA SIDO AGREGADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR USUARIO, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}

		public function actualizar_usuario($idactual,$id_usuarios,$nombre,$clave,$id_rol){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->actualizar_usuario($idactual,$id_usuarios,$nombre,$clave,$id_rol);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL USUARIO HA SIDO AGREGADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL AGREGAR USUARIO, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}

		public function eliminar_usuario($id_usuarios){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->actualizar_usuario($id_usuarios);
			$row=pg_affected_rows($this->datos);
			
			if ($row!=0) {
				
				$row= "EL USUARIO HA SIDO ACTUALIZADO CORRECTAMENTE";
				return "<p class=msg_save>".$row."</p>";
				
			}else{
				$row= "ERROR AL ACTUALZIAR USUARIO, POR FAVOR REVISE E INTENTE DE NUEVO";
				return "<p class=msg_error>".$row."</p>";
			}
			
		}

		public function listar_bitacora($desde,$cant){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->listar_bitacora($desde,$cant);
			return $this->datos;
		}

		public function cantidad_registros_bit(){
			$this->objadmin= new Administracion();
			$this->datos=$this->objadmin->cantidad_registros_bit();
			return $this->datos;
		}

	}


?>