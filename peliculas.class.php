<?php
    require_once 'conexion.php';
    //Hereda de conexión, entonces puede usar los métodos dentro de la clase padre.
    class Pelicula extends conexion{

        //Cuando no tengo la clase constructor, por defecto es vacío y no hace nada
        //Atributo para devolver una respuesta al front
        public $response = ['status' => "ok", "result" => array()];

        //Inserta una película en la base de datos
        public function insertarPelicula($json){
            //Convertimos el json a un array asociativo
            $datos = json_decode($json, true);
            //Si no están los datos requeridos
            if (!isset($datos['titulo']) || !isset($datos['genero']) || !isset($datos['duracion']) || !isset($datos['imagen'])){
                $this->response['status'] = "error";
                $this->response['result'] = array("error_id" => "400", "error_msg" => "Datos enviados incompletos o con formato incorrecto.");
                //Devolvemos un bad request
                return $this->response;
            }else{
                $titulo = $datos['titulo'];
                $duracion = $datos['duracion'];
                $genero = $datos['genero'];
                $imagen = $datos['imagen'];
                //Código para levantar la imagen y guardarla en el servidor y guardar la ruta en la base de datos
                
                //id_pelicula autonumérico
                $query = "INSERT INTO peliculas (id_pelicula, titulo, genero, duracion, imagen) VALUES (NULL, '$titulo', '$genero', '$duracion', '$imagen')";
                $datos = $this->nonQuery($query);
                if ($datos) {
                    $respuesta = $this->response;
                    $respuesta["result"] = array(
                        "id" => $datos
                    );
                    return $respuesta;
                }else{
                    $respuesta = $this->response;
                    $respuesta['status'] = "error";
                    $respuesta['result'] = array(
                        "error_id" => "500",
                        "error_msg" => "Error interno del servidor"
                    );
                    //Devolvemos un 500
                    return $respuesta;
                }
            }
        }    
        public function listarPeliculasSinPaginar(){
            $query = "SELECT * FROM id22369754_proyectopeliculas";
            $datos = $this->obtenerDatos($query);
            return $datos;
        }           

        //Para listar todas las películas y que en cada página vengan 16
        public function listarPeliculas($pagina){
            //*$cantidad es el número de registros que deseamos mostrar por página. 
            $inicio = 0;
            $cantidad = 16;
            if ($pagina > 1){
                $inicio = ($cantidad * ($pagina - 1)) + 1;
                $cantidad = $cantidad * $pagina;
            }
            $query = "SELECT * FROM id22369754_proyectopeliculas LIMIT $inicio, $cantidad";
            $datos = $this->obtenerDatos($query);
            return $datos;
        }

        //Para buscar una película por su id
        public function obtenerPelicula($id){
            $query = "SELECT * FROM id22369754_proyectopeliculas WHERE id_pelicula = '$id'";
            $datos = $this->obtenerDatos($query);
            if ($datos) {
                return $datos;
            }else{
                return 0;
            }
        }

        //Para buscar una película por una parte de su nombre
        public function buscarPelicula($nombre){
            //Pasar a minúscula el nombre y el campo de la base también
            $nombre = strtolower($nombre);
            $query = "SELECT * FROM id22369754_proyectopeliculas WHERE LOWER(titulo) LIKE '%$nombre%'";
            $datos = $this->obtenerDatos($query);
            if ($datos) {
                return $datos;
            }else{
                return 0;
            }
        }

        //Actualiza una película en la base de datos
        public function actualizarPelicula($json){
            //Convertimos el json a un array asociativo
            $datos = json_decode($json, true);

            //Si no están los datos requeridos
            if (!isset($datos['id_pelicula']) || !isset($datos['genero']) || !isset($datos['duracion']) || !isset($datos['imagen'])){
                $this->response['status'] = "error";
                $this->response['result'] = array(
                    "error_id" => "400",
                    "error_msg" => "Datos enviados incompletos o con formato incorrecto."
                );
                //Devolvemos un bad request
                return $this->response;
            }else{
                $id = $datos['id_pelicula'];
                $titulo = $datos['titulo'];
                $genero = $datos['genero'];
                $duracion = $datos['duracion'];
                $imagen = $datos['imagen'];
                //Código para levantar la imagen y guardarla en el server y guardar la ruta en la base de datos
                //Faltan validaciones para las fechas de lanzamiento
                $query = "UPDATE id22369754_proyectopeliculas SET titulo = '$titulo', genero = '$genero', duracion = '$duracion', imagen = '$imagen' WHERE id_pelicula = '$id'";
                $datos = $this->nonQuery($query);
                if ($datos >= 1){
                    $respuesta = $this->response;
                    $respuesta["result"] = array(
                        "mensaje" => "Registro actualizado correctamente."
                    );
                    return $respuesta;
                }else{
                    $this->response['status'] = "error";
                    $this->response['result'] = array(
                        "error_id" => "500",
                        "error_msg" => "Error intento del servidor."
                    );
                    //Devolvemos un 500
                    return $this->response;
                }
            } 
        }

        //Eliminar una película por su id de la base de datos
        public function eliminarPelicula($json){
            //Convertimos el json en un array asociativo
            $datos = json_decode($json, true);
            if (!isset($datos['id_pelicula'])){
                $this->response['status'] = "error";
                $this->response['result'] = array(
                    "error_id" => "400",
                    "error_msg" => "Datos enviados incompletos o con formato incorrecto."
                );
                //Devolvemos un bad request
                return $this->response;
            }else{
                $id_pelicula = $datos ['id_pelicula'];
                $query = "DELETE FROM id22369754_proyectopeliculas WHERE id_pelicula = '$id_pelicula'";
                $datos = $this->nonQuery($query);
                if ($datos >= 1) {
                    $respuesta = $this->response;
                    $respuesta['result'] = array(
                        "mensaje" => "Registro eliminado correctamente."
                    );
                    return $respuesta;
                }else{
                    $this->response['status'] = "error";
                    $this->response['result'] = array(
                        "error_id" => "500",
                        "error_msg" => "Error interno del servidor."
                    );
                    //Devolvemos un 500
                    return $this->response; 
                }       
            }       
        }
    }
?>