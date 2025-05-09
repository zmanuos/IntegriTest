<?php

class tema {

    private static $select = 'SELECT numTema, nombre, descripcion, cursonum FROM tema';

    private static $selectone = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE numTema = ?';

    private static $select_temacurso = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE cursonum = ?';

//    private static $select_temaexamen = 'SELECT numTema, nombre, descripcion, cursonum FROM tema as t inner join tema_examen as te on t.numTema = te.numTema WHERE te.numTema = ?';

    private $numTema;
    private $nombre;
    private $descripcion;
    private $cursonum;

    public function __construct($numTema, $nombre, $descripcion, $cursonum) {
        $this->numTema = $numTema;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cursonum = $cursonum;
    }


    public function getNumTema() { return $this->numTema; }
    public function setNumTema($numTema) { $this->numTema = $numTema; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $temaexamenlist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numTema, $nombre, $descripcion, $cursonum);

            while ($command->fetch()) {
                array_push($temaexamenlist, new tema($numTema, $nombre, $descripcion, $cursonum));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $temaexamenlist;

        } else if (func_num_args() == 1) {
            $temaexamenlist = null;
            
            $command = $connection->prepare(self::$selectone);
            $tema = func_get_arg(0);
            $command->bind_param('i', $tema);
            $command->execute();
            
            $command->bind_result($numTema, $nombre, $descripcion, $cursonum);

            if ($command->fetch()) {
                $temaexamenlist = new tema($numTema, $nombre, $descripcion, $cursonum);
                mysqli_stmt_close($command);
                $connection->close();
                return $temaexamenlist;
            }
        }
        return null;

    }


    public static function get_temacurso() {
        $connection = connection::get_connection();
        $temas = array();
    
        if (func_num_args() == 1) {
            $command = $connection->prepare(self::$select_temacurso);
            $curso = func_get_arg(0);
            $command->bind_param('i', $curso);
            $command->execute();
            $command->bind_result($numTema, $nombre, $descripcion, $cursonum);
    
            // Usa while para recorrer todos los registros y almacenarlos en el arreglo
            while ($command->fetch()) {
                array_push($temas, new tema($numTema, $nombre, $descripcion, $cursonum));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
        }
        return $temas; // Devuelve un arreglo, aunque esté vacío
    }



}

?>