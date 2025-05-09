<?php
class asignacion {

    private static $select = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion';

    private static $selectone = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion WHERE cursonum = ? and grupo = ?';

    private static $select_curso_docente = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion WHERE numEmpleado = ?';

    private $cursonum;
    private $grupo;
    private $promedio;
    private $numEmpleado;

    public function __construct($cursonum, $grupo, $promedio, $numEmpleado) {
        $this->cursonum = $cursonum;
        $this->grupo = $grupo;
        $this->promedio = $promedio;
        $this->numEmpleado = $numEmpleado;
    }


    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }

    public function getGrupo() { return $this->grupo; }
    public function setGrupo($grupo) { $this->grupo = $grupo; }

    public function getPromedio() { return $this->promedio; }
    public function setPromedio($promedio) { $this->promedio = $promedio; }

    public function getNumEmpleado() { return $this->numEmpleado; }
    public function setNumEmpleado($numEmpleado) { $this->numEmpleado = $numEmpleado; }



    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $examresults = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($cursonum, $grupo, $promedio, $numEmpleado);

            while ($command->fetch()) {
                array_push($examresults, new asignacion($cursonum, $grupo, $promedio, $numEmpleado));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $examresults;

        } else if (func_num_args() == 2) {
            $examresults = null;
            
            $command = $connection->prepare(self::$selectone);
            $cursonum = func_get_arg(0);
            $grupo = func_get_arg(1);
            $command->bind_param('ii', $cursonum, $grupo);
            $command->execute();
            
            $command->bind_result($cursonum, $grupo, $promedio, $numEmpleado);
    
            if ($command->fetch()) {
                $examresults = new asignacion($cursonum, $grupo, $promedio, $numEmpleado);
                mysqli_stmt_close($command);
                $connection->close();
                return $examresults;
            }
        }
        return null;
    }

    public static function get_curso_docente() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 1) {
            $curso_docente_list = array();
    
            $command = $connection->prepare(self::$select_curso_docente);
            $numEmpleado = func_get_arg(0);
            $command->bind_param('i', $numEmpleado);
            $command->execute();
    
            $command->bind_result($cursonum, $grupo, $promedio, $numEmpleado);

            while ($command->fetch()) {
                array_push($curso_docente_list, new asignacion($cursonum, $grupo, $promedio, $numEmpleado));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $curso_docente_list;

        }
    }
    public static function insert($cursonum, $grupo, $promedio, $numEmpleado) {
        $connection = connection::get_connection();
        
        $sql = "INSERT INTO asignacion (cursonum, grupo, promedio, numEmpleado) VALUES (?, ?, ?, ?)";
        
        $command = $connection->prepare($sql);
        
        $command->bind_param('iidi', $cursonum, $grupo, $promedio, $numEmpleado);
        
        $command->execute();

        $command->close();
        $connection->close();
    }
    


}


?>