<?php

class registro {

    private static $select = 'SELECT matricula, grupo FROM registro';

    private static $selectone = 'SELECT matricula, grupo FROM registro WHERE matricula = ? and grupo = ?';

    private static $insert_registro = 'INSERT INTO `registro` (`matricula`, `grupo`) VALUES ( ?, ? )';

    private static $select_grupo = 'SELECT r.matricula, r.grupo, g.nombre FROM registro AS r
    INNER JOIN grupo AS g ON r.grupo = g.grupo WHERE r.matricula = ?';


    private $matricula;
    private $grupo;
    private $gruponombre;

    public function __construct() {

        $args = func_get_args();

        if (func_num_args() == 2) {
        $this->matricula = $args[0];
        $this->grupo = $args[1];
        }

        if (func_num_args() == 3) {
        $this->matricula = $args[0];
        $this->grupo = $args[1];
        $this->gruponombre = $args[2];
        }
}

    public function getMatricula() { return $this->matricula; }
    public function setMatricula($matricula) { $this->matricula = $matricula; }

    public function getGrupo() { return $this->grupo; }
    public function setGrupo($grupo) { $this->grupo = $grupo; }

    public function getGruponombre() { return $this->gruponombre; }
    public function setGruponombre($gruponombre) { $this->gruponombre = $gruponombre; }



    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $registrolist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($matricula, $grupo);

            while ($command->fetch()) {
                array_push($registrolist, new registro($matricula, $grupo));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $registrolist;

        } else if (func_num_args() == 2) {
            $registro = null;
            
            $command = $connection->prepare(self::$selectone);
            $matriculaAlumno = func_get_arg(0);
            $grupoalumno = func_get_arg(1);
            $command->bind_param('si', $matriculaAlumno, $grupoalumno);
            $command->execute();
            
            $command->bind_result($matricula, $grupo);
    
            if ($command->fetch()) {
                $registro = new registro($matricula, $grupo);
                mysqli_stmt_close($command);
                $connection->close();
                return $registro;
            }
        }
        return null;
    }

    
    public static function insert_registro() {
    
        $connection = connection::get_connection();
    
        if (func_num_args() == 2) {
    
            $matricula = func_get_arg(0);
            $grupo = func_get_arg(1);
    
            $command = $connection->prepare(self::$insert_registro);
            $command->bind_param("si", $matricula, $grupo);
            $command->execute();
    
            mysqli_stmt_close($command);
            $connection->close();

            echo "ALUMNO REGISTRADO CORRECTAMENTE";
        }
        echo "NO SE PDDO REGISTRAR AL ALUMNO";
    }

    public static function get_grupo() {

        $connection = connection::get_connection();

        if (func_num_args() == 1) {
            $registro = null;

            $command = $connection->prepare(self::$select_grupo);
            $matriculaAlumno = func_get_arg(0);
            $command->bind_param('s', $matriculaAlumno);
            $command->execute();

            $command->bind_result($matricula, $grupo, $gruponombre);

            if ($command->fetch()) {
                $registro = new registro($matricula, $grupo, $gruponombre);
                mysqli_stmt_close($command);
                $connection->close();
                return $registro;
            }
        }
        return null;
    }

}

?>