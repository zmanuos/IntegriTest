<?php

class respuesta {

    // Consultas SQL
    private static $select = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta';
    private static $selectone = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta WHERE numRespuesta = ?';
    private static $select_pregunta = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta WHERE pregunta = ?';

    // Nueva consulta para actualizar una respuesta
    private static $update = 'UPDATE respuesta SET descripcion = ?, valor = ? WHERE numRespuesta = ?';

    private $numRespuesta;
    private $respuestaNum;
    private $valor;
    private $descripcion;
    private $pregunta;

    public function __construct($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta) {
        $this->numRespuesta = $numRespuesta;
        $this->respuestaNum = $respuestaNum;
        $this->valor = $valor;
        $this->descripcion = $descripcion;
        $this->pregunta = $pregunta;
    }

    // Métodos getters y setters
    public function getNumRespuesta() { return $this->numRespuesta; }
    public function setNumRespuesta($numRespuesta) { $this->numRespuesta = $numRespuesta; }

    public function getRespuestaNum() { return $this->respuestaNum; }
    public function setRespuestaNum($respuestaNum) { $this->respuestaNum = $respuestaNum; }

    public function getValor() { return $this->valor; }
    public function setValor($valor) { $this->valor = $valor; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getPregunta() { return $this->pregunta; }
    public function setPregunta($pregunta) { $this->pregunta = $pregunta; }

    // Método estático para obtener todas las respuestas
    public static function get() {
        $connection = connection::get_connection();
        $respuestalist = array();
    
        if (func_num_args() == 0) {
            $command = $connection->prepare(self::$select);
            $command->execute();
            $command->bind_result($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta);
    
            while ($command->fetch()) {
                array_push($respuestalist, new respuesta($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $respuestalist;
        } else if (func_num_args() == 1) {
            $respuestalist = null;
            $command = $connection->prepare(self::$selectone);
            $respuesta = func_get_arg(0);
            $command->bind_param('i', $respuesta);
            $command->execute();
            $command->bind_result($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta);
    
            if ($command->fetch()) {
                $respuestalist = new respuesta($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta);
                mysqli_stmt_close($command);
                $connection->close();
                return $respuestalist;
            }
        }
        return null;
    }

    // Método estático para obtener respuestas por pregunta
    public static function get_exam() {
        $connection = connection::get_connection();
        $respuestalist = array();
    
        if (func_num_args() == 1) {
            $command = $connection->prepare(self::$select_pregunta);
            $preguntares = func_get_arg(0);
            $command->bind_param('i', $preguntares);
            $command->execute();
            $command->bind_result($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta);
    
            while ($command->fetch()) {
                array_push($respuestalist, new respuesta($numRespuesta, $respuestaNum, $valor, $descripcion, $pregunta));
            }
            mysqli_stmt_close($command);
            $connection->close();
        }
        return $respuestalist;
    }

    public static function updateRespuesta($numRespuesta, $descripcion, $valor) {
        $connection = connection::get_connection();
    
        // Preparamos la consulta SQL de actualización
        $command = $connection->prepare(self::$update);
    
        // Enlazamos los parámetros con los tipos correctos: 's' para string (descripcion), 'd' para decimal (valor), 'i' para integer (numRespuesta)
        $command->bind_param('sdi', $descripcion, $valor, $numRespuesta);
    
        // Ejecutamos la actualización
        $command->execute();
    
        // Verificamos si se actualizó correctamente
        if ($command->affected_rows > 0) {
            mysqli_stmt_close($command);
            $connection->close();
            return true;  // Actualización exitosa
        } else {
            mysqli_stmt_close($command);
            $connection->close();
            return false; // No se realizó ninguna actualización
        }
    }
}

?>
