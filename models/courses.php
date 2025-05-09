<?php

require_once('../../includes/db_connection.php');


class Course {
    private static $conn; // Conexión a la base de datos

    private static $teacher_courses = "
        SELECT
            c.nombre,
            c.duracion,
            c.descripcion


            FROM asignacion AS a
            INNER JOIN curso AS c ON a.cursonum = c.cursonum
            INNER JOIN docente AS d ON a.numEmpleado = d.numEmpleado
            WHERE d.numEmpleado = ? AND c.numEstado = ?
            GROUP BY d.numEmpleado;
    ";

    
    
    // Attributes
    private $numEmpleado;
    private $curso;
    private $duracion;
    private $descripcion;
    private $numEstado;


    // Set the database connection
    public static function setConnection($connection) {
        self::$conn = $connection;
    }

    // Getters
    public function getNumEmpleado() { return $this->numEmpleado; }
    public function getCurso() { return $this->curso; }
    public function getDuracion() { return $this->duracion; }
    public function getDescripcion() { return $this->descripcion; }
    public function getNumEstado() { return $this->numEstado; }
    
    


    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->numEmpleado = 0;
            $this->curso = '';
            $this->duracion = '';
            $this->descripcion = '';
            $this->numEstado = '';
        } elseif ($numArgs == 3) {
            $this->curso = $args[0];
            $this->duracion = $args[1];
            $this->descripcion = $args[2];       
        }
    }

    
    public static function getCourses($numEmpleado, $numEstado) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        // Preparar y ejecutar la consulta con el término de búsqueda
        $command = self::$conn->prepare(self::$teacher_courses);
        $command->bind_param('si', $numEmpleado, $numEstado);
        $command->execute();
        $command->bind_result(
            $curso,
            $duracion,
            $descripcion
        );
    
        $courses = [];
        while ($command->fetch()) {
            $courses[] = new Course($curso, $duracion, $descripcion);
        }
    
        return $courses;
    }

}
Course::setConnection($conn);
?>