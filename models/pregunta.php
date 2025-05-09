<?php

 

class pregunta {

      private static $select = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta';

    private static $selectone = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta WHERE numPregunta = ?';

    private static $select_oneexam = 'SELECT numPregunta, descripcion, valor, preguntaNumero, numExam, codeTipo FROM pregunta WHERE numExam = ?';

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

    public static function updateDescripcion($numPregunta, $descripcion) {
        // Establece la conexión a la base de datos
        $connection = connection::get_connection();

        // Prepara la consulta SQL para actualizar solo la descripción de la pregunta
        $updateQuery = "UPDATE pregunta SET descripcion = ? WHERE numPregunta = ?";
        $command = $connection->prepare($updateQuery);

        // Vincula los parámetros de la consulta
        $command->bind_param('si', $descripcion, $numPregunta);

        // Ejecuta la consulta y verifica si se realizó correctamente
        if ($command->execute()) {
            // Cierra la conexión y el comando
            mysqli_stmt_close($command);
            $connection->close();
            return true;  // Retorna true si la actualización fue exitosa
        }

        // Si ocurrió un error, cierra la conexión y el comando
        mysqli_stmt_close($command);
        $connection->close();
        return false;  // Retorna false si no se pudo actualizar
    }
}



?>