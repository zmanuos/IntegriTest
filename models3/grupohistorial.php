<?php
class grupohistorial {

    private static $select = 'SELECT numero, grupo_numero, grupo_nombre, fechaInicio, fechaFinal, alumno_matricula, alumno_nombre, promedio_alumno FROM grupohistorial';

    private static $selectone = 'SELECT numero, grupo_numero, grupo_nombre, fechaInicio, fechaFinal, alumno_matricula, alumno_nombre, promedio_alumno FROM grupohistorial WHERE alumno_matricula = ?';

    private $numero;
    private $grupo_numero;
    private $grupo_nombre;
    private $fechaInicio;
    private $fechaFinal;
    private $alumno_matricula;
    private $alumno_nombre;
    private $promedio_alumno;

    public function __construct($numero, $grupo_numero, $grupo_nombre, $fechaInicio, $fechaFinal, $alumno_matricula, $alumno_nombre, $promedio_alumno) {
        $this->numero = $numero;
        $this->grupo_numero = $grupo_numero;
        $this->grupo_nombre = $grupo_nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->alumno_matricula = $alumno_matricula;
        $this->alumno_nombre = $alumno_nombre;
        $this->promedio_alumno = $promedio_alumno;
    }

    // Getters y Setters
    public function getNumero() { return $this->numero; }
    public function setNumero($numero) { $this->numero = $numero; }

    public function getGrupoNumero() { return $this->grupo_numero; }
    public function setGrupoNumero($grupo_numero) { $this->grupo_numero = $grupo_numero; }

    public function getGrupoNombre() { return $this->grupo_nombre; }
    public function setGrupoNombre($grupo_nombre) { $this->grupo_nombre = $grupo_nombre; }

    public function getFechaInicio() { return $this->fechaInicio; }
    public function setFechaInicio($fechaInicio) { $this->fechaInicio = $fechaInicio; }

    public function getFechaFinal() { return $this->fechaFinal; }
    public function setFechaFinal($fechaFinal) { $this->fechaFinal = $fechaFinal; }

    public function getAlumnoMatricula() { return $this->alumno_matricula; }
    public function setAlumnoMatricula($alumno_matricula) { $this->alumno_matricula = $alumno_matricula; }

    public function getAlumnoNombre() { return $this->alumno_nombre; }
    public function setAlumnoNombre($alumno_nombre) { $this->alumno_nombre = $alumno_nombre; }

    public function getPromedioAlumno() { return $this->promedio_alumno; }
    public function setPromedioAlumno($promedio_alumno) { $this->promedio_alumno = $promedio_alumno; }


    public static function get() {
        $connection = connection::get_connection();

        if (func_num_args() == 0) {
            $resultList = array();
            $command = $connection->prepare(self::$select);
            $command->execute();
            $command->bind_result($numero, $grupo_numero, $grupo_nombre, $fechaInicio, $fechaFinal, $alumno_matricula, $alumno_nombre, $promedio_alumno);

            while ($command->fetch()) {
                array_push($resultList, new Grupohistorial($numero, $grupo_numero, $grupo_nombre, $fechaInicio, $fechaFinal, $alumno_matricula, $alumno_nombre, $promedio_alumno));
            }

            mysqli_stmt_close($command);
            $connection->close();

            return $resultList;
        } else if (func_num_args() == 1) {
            $matricula = func_get_arg(0);
            $command = $connection->prepare(self::$selectone);
            $command->bind_param('s', $matricula);
            $command->execute();
            $command->bind_result($numero, $grupo_numero, $grupo_nombre, $fechaInicio, $fechaFinal, $alumno_matricula, $alumno_nombre, $promedio_alumno);

            if ($command->fetch()) {
                $result = new Grupohistorial($numero, $grupo_numero, $grupo_nombre, $fechaInicio, $fechaFinal, $alumno_matricula, $alumno_nombre, $promedio_alumno);
                mysqli_stmt_close($command);
                $connection->close();
                return $result;
            }
        }
        return null;
    }
}
?>