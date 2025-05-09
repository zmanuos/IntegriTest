<?php

class pregunta {

      private static $select = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta';

    private static $selectone = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta WHERE numPregunta = ?';

    private static $select_oneexam = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta WHERE numExam = ?';

    private static $update_descripcion = 'UPDATE pregunta SET descripcion = ? WHERE numPregunta = ?';

    private static $update_valor = 'UPDATE pregunta SET valor = ? WHERE numPregunta = ?';

    private $numPregunta;
    private $descripcion;
    private $valor;
    private $preguntaNumero;
    private $numExam;
    private $codeTipo;

    public function __construct($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo) {
        $this->numPregunta = $numPregunta;
        $this->descripcion = $descripcion;
        $this->valor = $valor;
        $this->preguntaNumero = $preguntaNumero;
        $this->numExam = $numExam;
        $this->codeTipo = $codeTipo;
    }

    public function getNumPregunta() { return $this->numPregunta; }
    public function setNumPregunta($numPregunta) { $this->numPregunta = $numPregunta; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getValor() { return $this->valor; }
    public function setValor($valor) { $this->valor = $valor; }

    public function getPreguntaNumero() { return $this->preguntaNumero; }
    public function setPreguntaNumero($preguntaNumero) { $this->preguntaNumero = $preguntaNumero; }

    public function getNumExam() { return $this->numExam; }
    public function setNumExam($numExam) { $this->numExam = $numExam; }

    public function getCodeTipo() { return $this->codeTipo; }
    public function setCodeTipo($codeTipo) { $this->codeTipo = $codeTipo; }


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $preguntalist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);

            while ($command->fetch()) {
                array_push($preguntalist, new pregunta($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $preguntalist;

        } else if (func_num_args() == 1) {
            $pregunta = null;
            
            $command = $connection->prepare(self::$selectone);
            $numPregunta = func_get_arg(0);
            $command->bind_param('i', $numPregunta);
            $command->execute();
            
            $command->bind_result($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
    
            if ($command->fetch()) {
                $pregunta = new pregunta($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
                mysqli_stmt_close($command);
                $connection->close();
                return $pregunta;
            }
        }
        return null;

    }

    public static function get_exam() {
        $connection = connection::get_connection();
        $preguntasexam = array();
    
        if (func_num_args() == 1) {
            $command = $connection->prepare(self::$select_oneexam);
            $exam = func_get_arg(0);
            $command->bind_param('i', $exam);
            $command->execute();
            $command->bind_result($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
    
            // Usa while para recorrer todos los registros y almacenarlos en el arreglo
            while ($command->fetch()) {
                $pregunta = new pregunta($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
                array_push($preguntasexam, $pregunta);
            }
    
            mysqli_stmt_close($command);
            $connection->close();
        }
        return $preguntasexam; // Devuelve un arreglo, aunque esté vacío
    }

    public static function updateDescripcion($descripcion, $numPregunta) {
        $connection = connection::get_connection();
    
        $command = $connection->prepare(self::$update_descripcion);

        $command->bind_param('si', $descripcion, $numPregunta);
        $success = $command->execute();
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $success;
    }

    public static function updateValor($valor, $numPregunta) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$update_valor);
    
        $command->bind_param('ii', $valor, $numPregunta);
        $success = $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

        return $success;
    }

    public static function getLastPregunta() {
        $connection = connection::get_connection();
        
        $query = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo 
                FROM pregunta WHERE numPregunta = (SELECT MAX(numPregunta) FROM pregunta)';
        
        $command = $connection->prepare($query);
        $command->execute();
        

        $command->bind_result($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
        
        $pregunta = null;
        
        if ($command->fetch()) {
            $pregunta = new pregunta($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
        }
        
        mysqli_stmt_close($command);
        $connection->close();
        
        return $pregunta;
    }

    public static function insert($numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo) {
        $connection = connection::get_connection();
    
        $query = 'INSERT INTO pregunta (numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo) VALUES (?, ?, ?, ?, ?, ?)';
    
        $command = $connection->prepare($query);
    
        $command->bind_param('isiiis', $numPregunta, $descripcion, $valor, $preguntaNumero, $numExam, $codeTipo);
    
        $command->execute();
    
        mysqli_stmt_close($command);
        $success = $connection->close();
    
        return $success;
    }
    

}



?>