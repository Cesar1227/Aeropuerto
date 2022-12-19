CREATE TABLE avion (
	matricula varchar NOT NULL,
	capacidad int4,
	aerolinea varchar
);
ALTER TABLE avion ADD CONSTRAINT pk_avion PRIMARY KEY(matricula);
CREATE TABLE vuelos (
	cod_vuelo varchar NOT NULL,
	tipo varchar,
	matricula varchar
);
ALTER TABLE vuelos ADD CONSTRAINT pk_vuelos PRIMARY KEY(cod_vuelo);
CREATE TABLE asignacion (
	cod_vuelo varchar NOT NULL,
	id_tripulacion varchar NOT NULL,
	puesto varchar
);
ALTER TABLE asignacion ADD CONSTRAINT pk_asignacion PRIMARY KEY(cod_vuelo,id_tripulacion);
CREATE TABLE tripulacion (
	id_tripulacion varchar NOT NULL,
	nombre varchar,
	categoria varchar
);
ALTER TABLE tripulacion ADD CONSTRAINT pk_tripulacion PRIMARY KEY(id_tripulacion);
CREATE TABLE origen (
	cod_vuelo varchar NOT NULL,
	cod_ciu varchar NOT NULL,
	hora time,
	orden varchar,
	fecha date
);
ALTER TABLE origen ADD CONSTRAINT pk_origen PRIMARY KEY(cod_vuelo,cod_ciu);
CREATE TABLE destino (
	cod_vuelo varchar NOT NULL,
	cod_ciu varchar NOT NULL,
	hora time,
	orden varchar,
	fecha date
);
ALTER TABLE destino ADD CONSTRAINT pk_destino PRIMARY KEY(cod_vuelo,cod_ciu);
CREATE TABLE ciudad (
	cod_ciu varchar NOT NULL,
	nombre varchar,
	cod_pais varchar
);
ALTER TABLE ciudad ADD CONSTRAINT pk_ciudad PRIMARY KEY(cod_ciu);
CREATE TABLE tiquete (
	cod_vuelo varchar NOT NULL,
	id_pasajero varchar NOT NULL,
	cod_clase varchar,
	cod_tiquete varchar,
	asiento varchar,
	metodo_pago varchar,
	precio float8
);
ALTER TABLE tiquete ADD CONSTRAINT pk_tiquete PRIMARY KEY(cod_vuelo,id_pasajero);
CREATE TABLE pasajero (
	id_pasajero varchar NOT NULL,
	nombre varchar,
	fecha_nac date,
	pasaporte varchar,
	telefono varchar(18)
);
ALTER TABLE pasajero ADD CONSTRAINT pk_pasajero PRIMARY KEY(id_pasajero);
CREATE TABLE clase (
	cod_clase varchar NOT NULL,
	precio float8 NOT NULL,
	nombre varchar
);
ALTER TABLE clase ADD CONSTRAINT pk_clase PRIMARY KEY(cod_clase,precio);
CREATE TABLE pais (
	cod_pais varchar NOT NULL,
	nombre varchar
);
CREATE TABLE usuario (
	id_usuario varchar NOT NULL,
	nomb_usuario varchar,
    clave varchar,
    id_rol varchar
);

ALTER TABLE usuario ADD CONSTRAINT pk_usuario PRIMARY KEY(id_usuario);
CREATE TABLE roles (
    id_rol varchar not null,
    rol varchar
);

ALTER TABLE roles ADD CONSTRAINT pk_roles PRIMARY KEY(id_rol);
create table bitacora(
usuario varchar NOT NULL,
tabla varchar,
accion char(1),
fecha timestamp, 
key_modified varchar);

ALTER TABLE bitacora ADD CONSTRAINT pk_bitacora PRIMARY KEY (usuario);

ALTER TABLE pais ADD CONSTRAINT pk_pais PRIMARY KEY(cod_pais);
ALTER TABLE vuelos ADD CONSTRAINT fk_ejecuta FOREIGN KEY (matricula) REFERENCES avion(matricula) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE origen ADD CONSTRAINT fk_origenC FOREIGN KEY (cod_ciu) REFERENCES ciudad(cod_ciu) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE destino ADD CONSTRAINT fk_destinoC FOREIGN KEY (cod_ciu) REFERENCES ciudad(cod_ciu) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE tiquete ADD CONSTRAINT fk_cod_vuelo FOREIGN KEY (cod_vuelo) REFERENCES vuelos(cod_vuelo) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE tiquete ADD CONSTRAINT fk_id_pasajero FOREIGN KEY (id_pasajero) REFERENCES pasajero(id_pasajero) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE tiquete ADD CONSTRAINT fk_clase FOREIGN KEY (cod_clase,precio) REFERENCES clase(cod_clase,precio) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE origen ADD CONSTRAINT fk_origen FOREIGN KEY (cod_vuelo) REFERENCES vuelos(cod_vuelo) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE destino ADD CONSTRAINT fk_destino FOREIGN KEY (cod_vuelo) REFERENCES vuelos(cod_vuelo) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE asignacion ADD CONSTRAINT fk_asigna FOREIGN KEY (cod_vuelo) REFERENCES vuelos(cod_vuelo) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE asignacion ADD CONSTRAINT fk_tripula FOREIGN KEY (id_tripulacion) REFERENCES tripulacion(id_tripulacion) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE ciudad ADD CONSTRAINT fk_cod_pais FOREIGN KEY (cod_pais) REFERENCES pais(cod_pais) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE usuarios ADD CONSTRAINT fk_usuarios FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE OR REPLACE FUNCTION llenar_bitacora() RETURNS TRIGGER AS $$
DECLARE

    llave varchar;
    campo text;

BEGIN
    campo:=TG_ARGV[0];

    IF(TG_OP='DELETE')THEN
        EXECUTE 'SELECT($1).' || quote_ident(campo) || '::text' INTO llave USING OLD;
        INSERT INTO bitacora SELECT user,TG_TABLE_NAME,'D',now(),llave;
    RETURN OLD;
    ELSEIF(TG_OP='UPDATE')THEN
        EXECUTE 'SELECT($1).' || quote_ident(campo) || '::text' INTO llave USING NEW;
        INSERT INTO bitacora SELECT user,TG_TABLE_NAME,'U',now(), llave;
    RETURN NEW;    
    ELSEIF(TG_OP='INSERT')THEN
        EXECUTE 'SELECT($1).' || quote_ident(campo) || '::text' INTO llave USING NEW;
        INSERT INTO bitacora SELECT user,TG_TABLE_NAME,'I',now(),llave;
    RETURN NEW;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_bitacora_tiquetes 
AFTER INSERT OR UPDATE OR DELETE ON tiquete
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('cod_tiquete');

CREATE TRIGGER trg_bitacora_pasajero
AFTER INSERT OR UPDATE OR DELETE ON pasajero
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('id_pasajero');

CREATE TRIGGER trg_bitacora_tripulacion 
AFTER INSERT OR UPDATE OR DELETE ON tripulacion
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('id_tripulacion');

CREATE TRIGGER trg_bitacora_usuario
AFTER INSERT OR UPDATE OR DELETE ON usuario
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('id_usuario');

CREATE TRIGGER trg_bitacora_pais
AFTER INSERT OR UPDATE OR DELETE ON pais
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('cod_pais');

CREATE TRIGGER trg_bitacora_ciudades
AFTER INSERT OR UPDATE OR DELETE ON ciudades
FOR EACH ROW EXECUTE PROCEDURE llenar_bitacora('cod_ciu');

CREATE GROUP administrador NOSUPERUSER INHERIT CREATEROLE NOCREATEDB NOREPLICATION;
CREATE GROUP empleado NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB NOREPLICATION;

CREATE USER adminElDorado WITH PASSWORD 'eldorado' IN GROUP administrador;
CREATE USER cesar WITH PASSWORD 'cesar' IN GROUP administrador;
CREATE USER vendedor WITH PASSWORD 'vendedor' IN GROUP empleado;

GRANT all ON table usuario to group administrador;
GRANT all ON table asignacion to group administrador;
GRANT all ON table avion to group administrador;
GRANT all ON table bitacora to group administrador;
GRANT all ON table ciudades to group administrador;
GRANT all ON table clase to group administrador;
GRANT all ON table destino to group administrador;
GRANT all ON table origen to group administrador;
GRANT all ON table pais to group administrador;
GRANT all ON table pasajero to group administrador;
GRANT all ON table roles to group administrador;
GRANT all ON table tiquete to group administrador;
GRANT all ON table tripulacion to group administrador;
GRANT all ON table usuario to group administrador;
GRANT all ON table vuelos to group administrador;

GRANT all ON table usuario to group empleado;
GRANT all ON table asignacion to group empleado;
GRANT all ON table avion to group empleado;
GRANT insert ON table bitacora to group empleado;
GRANT all ON table ciudades to group empleado;
GRANT all ON table clase to group empleado;
GRANT all ON table destino to group empleado;
GRANT all ON table origen to group empleado;
GRANT all ON table pais to group empleado;
GRANT all ON table pasajero to group empleado;
GRANT all ON table roles to group empleado;
GRANT all ON table tiquete to group empleado;
GRANT all ON table tripulacion to group empleado;
GRANT all ON table vuelos to group empleado;

ALTER TABLE bitacora ADD CONSTRAINT pk_bitacora PRIMARY KEY (fecha);

CREATE OR REPLACE MATERIALIZED VIEW vista_destinos AS select distinct c.cod_pais,p.nombre_pais,c.cod_ciu,c.nombre FROM ciudades c JOIN pais p ON c.cod_pais=p.cod_pais;

CREATE or REPLACE RULE insertar_view AS ON insert TO View_destinos INSTEAD insert into pais values (new.cod_pais,new.nombre_pais) INSTEAD insert into ciudades values (new.cod_ciu,new.nombre);
