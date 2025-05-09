<?php

class respuesta {

    private static $select = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta';

    private static $selectone = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta WHERE numRespuesta = ?';

    private static $select_pregunta = 'SELECT numRespuesta, respuestaNum, valor, descripcion, pregunta FROM respuesta WHERE pregunta = ?';

    private static $update0 = 'UPDATE respuesta SET valor = 0 WHERE numRespuesta = ?';

    private static $update_valor = 'UPDATE respuesta SET valor = ? WHERE numRespuesta = ?';

    private static $update_descripcion = 'UPDATE respuesta SET descripcion = ? WHERE numRespuesta = ?';

    private static $insert = 'INSERT INTO respuesta (respuestaNum, valor, descripcion, pregunta) VALUES (?, ?, ?, ?)';


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



    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $respuestalist = array();
    
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

    public static function update_0($numRespuesta) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$update0);

        $command->bind_param('i', $numRespuesta);
        $success = $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

        return $success;
    }

    public static function update_valor($valor, $numRespuesta) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$update_valor);

        $command->bind_param('ii', $valor, $numRespuesta);
        $success = $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

        return $success;
    }
    
    public static function updateDescripcion($numRespuesta, $descripcion) {
        $connection = connection::get_connection();
    
        $command = $connection->prepare(self::$update_descripcion);
    
        $command->bind_param('si', $descripcion, $numRespuesta);
        $success = $command->execute();
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $success;
    }
    

    public static function insert($respuestaNum, $valor, $descripcion, $pregunta) {
        $connection = connection::get_connection();
        
        $command = $connection->prepare(self::$insert);
        $command->bind_param('iisi', $respuestaNum, $valor, $descripcion, $pregunta);
        $success = $command->execute();
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $success;
    }

}




?>