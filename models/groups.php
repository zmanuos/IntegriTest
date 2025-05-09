<?php

require_once('../../includes/db_connection.php');


class Group {
    private static $conn; // Conexión a la base de datos

    private static $teacher_groups = "
        SELECT
            c.nombre,
            g.nombre,
            g.fechaInicio,
            g.fechaFinal,
            g.cantidadAlumnos
          


            FROM asignacion AS a
            INNER JOIN grupo AS g ON a.grupo = g.grupo
            INNER JOIN docente AS d ON a.numEmpleado = d.numEmpleado
            INNER JOIN curso AS c ON a.cursonum = c.cursonum

            WHERE d.numEmpleado = ? AND g.estado = ?;
    ";

    
    private static $students_group = "
    SELECT 
    a.matricula,
    CONCAT(a.nombre, ' ', a.apellidoP, ' ', a.apellidoM) AS nombreCompleto
    FROM registro AS r
    INNER JOIN alumno AS a ON r.matricula = a.matricula
    INNER JOIN grupo AS g ON r.grupo = g.grupo
    WHERE g.nombre = ?
    ORDER BY matricula
    ";
    
    private static $course_group = "
        SELECT 
            c.nombre
        FROM asignacion AS a
        INNER JOIN curso AS c ON a.cursonum = c.cursonum
        INNER JOIN grupo AS g ON a.grupo = g.grupo

        WHERE g.nombre = ? AND a.numEmpleado = ?;
       
    ";
    
    // Attributes
    private $numEmpleado;
    private $curso;
    private $grupo;
    private $fechaInicio;
    private $fechaFinal;
    private $cantidadAlumnos;
    private $grupoEstado;
    private $matricula;
    private $nombreCompleto;


    // Set the database connection
    public static function setConnection($connection) {
        self::$conn = $connection;
    }

    // Getters
    public function getNumEmpleado() { return $this->numEmpleado; }
    public function getCurso() { return $this->curso; }
    public function getGrupo() { return $this->grupo; }
    public function getFechaInicio() { return $this->fechaInicio; }
    public function getFechaFinal() { return $this->fechaFinal; }
    public function getCantidadAlumnos() { return $this->cantidadAlumnos; }
    public function getGrupoEstado() { return $this->grupoEstado; }
    public function getMatricula() { return $this->matricula; }
    public function getNombreCompleto() { return $this->nombreCompleto; }


    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->numEmpleado = 0;
            $this->curso = '';
            $this->grupo = '';
            $this->fechaInicio = '';
            $this->fechaFinal = '';
            $this->cantidadAlumnos = '';
        } elseif ($numArgs == 1) {
            $this->curso = $args[0];
        } elseif ($numArgs == 2) {
            $this->matricula = $args[0];
            $this->nombreCompleto = $args[1];
        } elseif ($numArgs == 5) {
            $this->curso = $args[0];
            $this->grupo = $args[1];
            $this->fechaInicio = $args[2];
            $this->fechaFinal = $args[3];
            $this->cantidadAlumnos = $args[4];
       
        }
    }

    
    public static function getGroups($numEmpleado, $grupoEstado) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        // Preparar y ejecutar la consulta con el término de búsqueda
        $command = self::$conn->prepare(self::$teacher_groups);
        $command->bind_param('si', $numEmpleado, $grupoEstado);
        $command->execute();
        $command->bind_result(
            $curso,
            $grupo,
            $fechaInicio,
            $fechaFinal,
            $cantidadAlumnos,
        );
    
        $groups = [];
        while ($command->fetch()) {
            $groups[] = new Group($curso, $grupo, $fechaInicio, $fechaFinal, $cantidadAlumnos);
        }
    
        return $groups;
    }


    public static function getTotalGroups() {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        $query = "SELECT COUNT(*) FROM grupo";
        $command = self::$conn->prepare($query);
        $command->execute();
        $command->bind_result($totalGroups);
        $command->fetch();
        $command->close();
    
        return $totalGroups;
    }
    

    public static function getStudents($grupo) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$students_group);
        $command->bind_param("s", $grupo);
        $command->execute();
        $command->bind_result(
            $matricula,
            $nombreCompleto
        );

        $groups = [];
        while ($command->fetch()) {
            $groups[] = new Group($matricula, $nombreCompleto);
        }

        return $groups;
    }

    public static function getCourses($grupo, $numEmpleado) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$course_group);
        $command->bind_param("ss", $grupo, $numEmpleado);
        $command->execute();
        $command->bind_result(
            $curso
        );

        $groups = [];
        while ($command->fetch()) {
            $groups[] = new Group($curso);
        }

        return $groups;
    }

    public static function getTopGroupAverage() {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        $query = "
            SELECT 
                nombre,
                promedio
            FROM grupo
            ORDER BY promedio DESC
            LIMIT 1;
        ";
    
        $command = self::$conn->prepare($query);
        $command->execute();
        $command->bind_result($grupo, $promedio);
        $command->fetch();
        $command->close();
    
        return [
            'grupo' => $grupo,
            'promedio' => round($promedio, 2)
        ];
    }
    

}
Group::setConnection($conn);
?>