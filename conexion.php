<?php 
//Archivo que contiene una clase que se va a conectar a la base de datos
class conexion{
    //Atributos de clase
    private $server = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'id22369754_proyectopeliculas';
    private $port = '3306';
    private $conexion; 

    //Constructor de la clase, no recibe parámetros
    //Al objeto conexión le asigna el resultado de la conexión con mysql
    function __construct(){
        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->port);
        if($this->conexion->connect_error){
            echo "No funciona la conexión con la base de datos.";
            die();
        }
    }

    //Métodos
    //Método para convertir caracteres a UTF-8
    private function convertirUTF8($array){
        array_walk_recursive($array, function(&$item,$key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    //Método para obtener datos de la base de datos
    //Este método recibe una query select(puede ser: "select * from movies";)
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        if($results){
            foreach($results as $key){
                $resultArray[] = $key;
            }
            return $this->convertirUTF8($resultArray);
        }else{
            return 0;
        }
    }

    //Método para guardar datos en la base de datos
    //Query de update, insert o delete
    //Retorna el valor de cuántas filas fueron afectadas por la query implementada
    public function nonQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }

    //Método para obtener el id de un registro
    //Query de update, insert o delete
    //Retorna el valor de cuántas filas fueron afectadas por la query implementada y de ese valor devuelve el id de la fila afectada. 
    public function nonQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }
}
?>