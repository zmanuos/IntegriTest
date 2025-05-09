<?php
class asignacion {

    private static $select = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion';

    private static $selectone = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion WHERE cursonum = ? and grupo = ?';

    private static $select_curso_docente = 'SELECT a.cursonum, a.grupo, a.promedio, a.numEmpleado
    FROM asignacion AS a
    INNER JOIN curso AS c ON a.cursonum = c.cursonum
    WHERE a.numEmpleado = ?';

    private static $cursos_imparte = 'SELECT a.cursonum, c.nombre, a.promedio, a.numEmpleado
    FROM asignacion AS a INNER JOIN curso AS c ON a.cursonum = c.cursonum
    WHERE a.numEmpleado = ?
    group by a.cursonum, c.cursonum';




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


    public static function get_curso_imparte_docente() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 1) {
            $curso_docente_list = array();
    
            $command = $connection->prepare(self::$cursos_imparte);
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

    private static $promedios = "
    SELECT a.cursonum, g.nombre, AVG(ae.calificacion),a.numEmpleado
    FROM asignacion a JOIN grupo g ON a.grupo = g.grupo
    JOIN registro r ON r.grupo = g.grupo
    JOIN alumno_examen ae ON ae.matricula = r.matricula
    WHERE a.cursonum = ? AND a.numEmpleado = ? AND g.grupo = ?
    GROUP BY a.cursonum, g.grupo
    ORDER BY a.cursonum, g.grupo;
";

    public static function getPromediosPorCurso($cursonum, $numEmpleado, $grupo) {
        $connection = connection::get_connection();


        $command = $connection->prepare(self::$promedios);

        $command->bind_param('iii', $cursonum, $numEmpleado, $grupo);
    
        $command->execute();

        $command->bind_result($cursonum, $grupo_nombre, $promedio_general, $numEmpleado2);

        $promediosList = null;

        if ($command->fetch()) {
            $promediosList = new asignacion($cursonum, $grupo_nombre, $promedio_general, $numEmpleado2);
        }

        mysqli_stmt_close($command);
        $connection->close();

        return $promediosList;
    }


    

    public static function get_promedios_cursos_docente($numEmpleado) {
        $connection = connection::get_connection();
        if (!$connection) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    
        $cursoPromediosList = [];
    
    
        $query = "
            SELECT c.cursonum, c.nombre, AVG(ae.calificacion) AS promedio_curso
            FROM curso c
            JOIN asignacion a ON c.cursonum = a.cursonum
            JOIN examen e ON e.cursonum = c.cursonum
            JOIN alumno_examen ae ON ae.numExam = e.numExam
            WHERE a.numEmpleado = ?
            GROUP BY c.cursonum, c.nombre
        ";
    
        $command = $connection->prepare($query);
        if (!$command) {
            die("Error: No se pudo preparar la consulta. Revisa la sintaxis de la consulta SQL. " . $connection->error);
        }
    
        $command->bind_param('i', $numEmpleado);
        if (!$command->execute()) {
            die("Error: No se pudo ejecutar la consulta. " . $command->error);
        }
    
        $command->bind_result($cursonum, $nombre, $promedio_curso);
    
        while ($command->fetch()) {
            array_push($cursoPromediosList, [
                'cursonum' => $cursonum,
                'nombre' => $nombre,
                'promedio_curso' => $promedio_curso
            ]);
        }
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $cursoPromediosList;
    }



    public static function get_cursos_docente($numEmpleado) {
        $connection = connection::get_connection();
        $cursoList = [];
    
        $query = "
            SELECT c.cursonum, c.nombre, c.descripcion, c.duracion, c.estado
            FROM curso c
            JOIN asignacion a ON c.cursonum = a.cursonum
            WHERE a.numEmpleado = ?
        ";
    
        $command = $connection->prepare($query);
        $command->bind_param('i', $numEmpleado);
        $command->execute();
    
        $command->bind_result($cursonum, $nombre, $descripcion, $duracion, $estado);
    
        while ($command->fetch()) {
            array_push($cursoList, [
                'cursonum' => $cursonum,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'duracion' => $duracion,
                'estado' => $estado
            ]);
        }
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $cursoList;
    }
    

}
?>