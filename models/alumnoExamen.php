<?php


class alumnoExamen {

    private static $select = 'SELECT numExam, matricula, calificacion, fechaRealizacion FROM alumno_examen';

    private static $selectone = 'SELECT numExam, matricula, calificacion, fechaRealizacion FROM alumno_examen WHERE matricula = ? and numExam = ?';

    private static $insert = 'INSERT INTO alumno_examen (numExam, matricula, calificacion) VALUES ( ?, ?, ?)';


    private $numExam;
    private $matricula;
    private $calificacion;
    private $fechaRealizacion;

    public function __construct($numExam, $matricula, $calificacion, $fechaRealizacion) {
        $this->numExam = $numExam;
        $this->matricula = $matricula;
        $this->calificacion = $calificacion;
        $this->fechaRealizacion = $fechaRealizacion;
    }

    public function getNumExam() { return $this->numExam; }
    public function setNumExam($numExam) { $this->numExam = $numExam; }

    public function getMatricula() { return $this->matricula; }
    public function setMatricula($matricula) { $this->matricula = $matricula; }

    public function getCalificacion() { return $this->calificacion; }
    public function setCalificacion($calificacion) { $this->calificacion = $calificacion; }

    public function getFechaRealizacion() { return $this->fechaRealizacion; }
    public function setFechaRealizacion($fechaRealizacion) { $this->fechaRealizacion = $fechaRealizacion; }


public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $examresults = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numExam, $matricula, $calificacion, $fechaRealizacion);
                            
            while ($command->fetch()) {
                array_push($examresults, new alumnoExamen($numExam, $matricula, $calificacion, $fechaRealizacion));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $examresults;

        } else if (func_num_args() == 2) {
            $examresults = null;
            
            $command = $connection->prepare(self::$selectone);
            $matricula = func_get_arg(0);
            $numExam = func_get_arg(1);
            $command->bind_param('si', $matricula, $numExam);
            $command->execute();
            
            $command->bind_result($numExam, $matricula, $calificacion, $fechaRealizacion);

            if ($command->fetch()) {
                $examresults = new alumnoExamen($numExam, $matricula, $calificacion, $fechaRealizacion);
                mysqli_stmt_close($command);
                $connection->close();
                return $examresults;
            }
        }
        return null;
    }

    public static function insert() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 3) {

            $command = $connection->prepare(self::$insert);

            $numExam = func_get_arg(0);
            $matricula = func_get_arg(1);
            $calificacion = func_get_arg(2);

            $command->bind_param('isd', $numExam, $matricula, $calificacion);
            $command->execute();

            mysqli_stmt_close($command);
            $connection->close();

            return true;
        } else {
            return false;
        }
    }

}
?>