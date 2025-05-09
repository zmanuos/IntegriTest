<?php

class temaExamen {

    private static $select = 'SELECT numTema, numExam FROM tema_examen';

    private static $selectone = 'SELECT numTema, numExam FROM tema_examen WHERE numTema = ? and numExam = ?';

    private static $select_tema_nombre = 'SELECT te.numTema, t.nombre  FROM tema_examen as te INNER JOIN tema t ON te.numTema = t.numTema WHERE te.numExam = ?';


    private $numTema;
    private $numExam;

    public function __construct($numTema, $numExam) {
        $this->numTema = $numTema;
        $this->numExam = $numExam;
    }


    public function getNumTema() { return $this->numTema; }
    public function setNumTema($numTema) { $this->numTema = $numTema; }

    public function getNumExam() { return $this->numExam; }
    public function setNumExam($numExam) { $this->numExam = $numExam; }


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $temaexamenlist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numTema, $numExam);

            while ($command->fetch()) {
                array_push($temaexamenlist, new temaExamen($numTema, $numExam));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $temaexamenlist;

        } else if (func_num_args() == 1) {
            $temaexamenlist = null;
            
            $command = $connection->prepare(self::$select_tema_nombre);
            $examen = func_get_arg(0);
            $command->bind_param('i', $examen);
            $command->execute();
            
            $command->bind_result($numTema, $numExam);

            if ($command->fetch()) {
                $temaexamenlist = new temaExamen($numTema, $numExam);
                mysqli_stmt_close($command);
                $connection->close();
                return $temaexamenlist;
            }
        } else if (func_num_args() == 2) {
            $temaexamenlist = null;
            
            $command = $connection->prepare(self::$selectone);
            $tema = func_get_arg(0);
            $examen = func_get_arg(1);
            $command->bind_param('ii', $tema, $examen);
            $command->execute();
            
            $command->bind_result($numTema, $numExam);

            if ($command->fetch()) {
                $temaexamenlist = new temaExamen($numTema, $numExam);
                mysqli_stmt_close($command);
                $connection->close();
                return $temaexamenlist;
            }
        }
        return null;

    }


}


?>