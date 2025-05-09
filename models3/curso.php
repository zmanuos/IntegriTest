<?php


class curso {

    private static $select = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso';

    private static $selectone = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE cursonum = ?';

    private static $select_cursos_que_no_estan_en_grupo = 'SELECT c.cursonum, c.nombre, c.duracion, c.descripcion, c.numEstado
    FROM curso AS c LEFT JOIN asignacion AS a ON c.cursonum = a.cursonum AND a.grupo = ?
    WHERE a.cursonum IS NULL;';

    private static $promedio_cuatri = 'SELECT c.cursonum, c.nombre, AVG(alex.calificacion) AS promedio, c.descripcion, c.numEstado FROM alumno AS al INNER JOIN
    registro AS rg ON al.matricula = rg.matricula INNER JOIN
    grupo AS g ON rg.grupo = g.grupo INNER JOIN
    asignacion AS ag ON g.grupo = ag.grupo INNER JOIN
    curso AS c ON ag.cursonum = c.cursonum INNER JOIN
    tema AS t ON t.cursonum = c.cursonum INNER JOIN
    tema_examen AS te ON te.numTema = t.numTema INNER JOIN
    examen AS ex ON te.numExam = ex.numExam LEFT JOIN
    alumno_examen AS alex ON ex.numExam = alex.numExam AND al.matricula = alex.matricula
    WHERE al.matricula = ? and c.cursonum = ?
    GROUP BY al.matricula, c.nombre';


    private $cursonum;
    private $nombre;
    private $duracion;
    private $descripcion;
    private $numEstado;

    public function __construct($cursonum, $nombre, $duracion, $descripcion, $numEstado) {
        $this->cursonum = $cursonum;
        $this->nombre = $nombre;
        $this->duracion = $duracion;
        $this->descripcion = $descripcion;
        $this->numEstado = $numEstado;
    }


    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDuracion() { return $this->duracion; }
    public function setDuracion($duracion) { $this->duracion = $duracion; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getNumEstado() { return $this->numEstado; }
    public function setNumEstado($numEstado) { $this->numEstado = $numEstado; }


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $examresults = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);

            while ($command->fetch()) {
                array_push($examresults, new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $examresults;

        } else if (func_num_args() == 1) {
            $examresults = null;
            
            $command = $connection->prepare(self::$selectone);
            $curso = func_get_arg(0);
            $command->bind_param('i', $curso);
            $command->execute();
            
            $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);
    
            if ($command->fetch()) {
                $examresults = new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado);
                mysqli_stmt_close($command);
                $connection->close();
                return $examresults;
            }
        }
        return null;
    }



    public static function get_cursos_que_no_estan_en_un_grupo() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 1) {
            $examresults = array();

            $command = $connection->prepare(self::$select_cursos_que_no_estan_en_grupo);
            $grupo = func_get_arg(0);
            $command->bind_param('i', $grupo);
            $command->execute();

            $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);

            while ($command->fetch()) {
                array_push($examresults, new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $examresults;

        }
    }


    public static function get_promedios() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 2) {
            $examresults = array();

            $command = $connection->prepare(self::$promedio_cuatri);
            $matricula = func_get_arg(0);
            $curso = func_get_arg(1);
            $command->bind_param('si', $matricula, $curso);
            $command->execute();

            $command->bind_result($cursonum, $nombre, $promedio, $descripcion, $numEstado);

            if ($command->fetch()) {

                $examresults = new curso($cursonum, $nombre, $promedio, $descripcion, $numEstado);

                return $examresults;
            }

            mysqli_stmt_close($command);
            $connection->close();
    
            return $examresults;

        }
    }

}


?>