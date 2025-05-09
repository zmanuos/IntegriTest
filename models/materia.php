<?php


class materia {

    private static $select = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion';

    private static $selectone = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion WHERE cursonum = ? and grupo = ?';

    private static $select_curso_docente = 'SELECT cursonum, grupo, promedio, numEmpleado FROM asignacion WHERE numEmpleado = ?';

    private static $select_cursos_alumno = 'SELECT asg.cursonum, asg.grupo, asg.promedio, asg.numEmpleado, c.nombre, g.nombre,
    CONCAT(d.nombre, " ", d.apellidoP, " ", d.apellidoM) AS profesor
    FROM alumno a JOIN registro r ON a.matricula = r.matricula
    JOIN asignacion asg ON r.grupo = asg.grupo
    JOIN curso c ON asg.cursonum = c.cursonum
    JOIN grupo g ON asg.grupo = g.grupo
    JOIN docente d ON asg.numEmpleado = d.numEmpleado
    WHERE a.matricula = ?';


    private $cursonum;
    private $grupo;
    private $promedio;
    private $numEmpleado;

    private $cursoNombre;
    private $grupoNombre;
    private $profesorNombre;

    public function __construct($cursonum, $grupo, $promedio, $numEmpleado, $cursoNombre, $grupoNombre, $profesorNombre) {
        $this->cursonum = $cursonum;
        $this->grupo = $grupo;
        $this->promedio = $promedio;
        $this->numEmpleado = $numEmpleado;
        $this->cursoNombre = $cursoNombre;
        $this->grupoNombre = $grupoNombre;
        $this->profesorNombre = $profesorNombre;
    }


    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }

    public function getGrupo() { return $this->grupo; }
    public function setGrupo($grupo) { $this->grupo = $grupo; }

    public function getPromedio() { return $this->promedio; }
    public function setPromedio($promedio) { $this->promedio = $promedio; }

    public function getNumEmpleado() { return $this->numEmpleado; }
    public function setNumEmpleado($numEmpleado) { $this->numEmpleado = $numEmpleado; }

    public function getgrupoNombre() { return $this->grupoNombre; }
    public function setgrupoNombre($grupoNombre) { $this->grupoNombre = $grupoNombre; }

    public function getcursoNombre() { return $this->cursoNombre; }
    public function setcursoNombre($cursoNombre) { $this->cursoNombre = $cursoNombre; }

    public function getprofesorNombre() { return $this->profesorNombre; }
    public function setprofesorNombre($profesorNombre) { $this->profesorNombre = $profesorNombre; }



    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 1) {
            $materialist = array();
    
            $command = $connection->prepare(self::$select_cursos_alumno);
            $alumno = func_get_arg(0);
            $command->bind_param('s', $alumno);
            $command->execute();
    
            $command->bind_result($cursonum, $grupo, $promedio, $numEmpleado, $grupoNombre, $cursoNombre, $profesorNombre);

            while ($command->fetch()) {
                array_push($materialist, new materia($cursonum, $grupo, $promedio, $numEmpleado, $grupoNombre, $cursoNombre, $profesorNombre));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $materialist;

        }
        return null;
    }


}


?>