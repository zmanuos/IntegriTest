c<?php

require_once('../../includes/db_connection.php');

class Student {
    private static $conn; // Conexión a la base de datos
    // SQL statements
    private static $select_one = '
        SELECT 
            matricula, 
            nombre, 
            apellidoP, 
            apellidoM, 
            promedio, 
            correo,
            telefono,
            IF(genero = "M", "Hombre", "Mujer"),
            fechaNacimiento,
            curp

        FROM alumno 
        WHERE matricula = ?;
    ';

    private static $select_all = "
        SELECT 
            matricula,
            CONCAT(nombre, ' ', apellidoP, ' ', apellidoM) AS nombreCompleto
        FROM alumno 
        ORDER BY matricula
        LIMIT ? OFFSET ?;
    ";

    private static $search_students = "
        SELECT matricula, CONCAT(nombre, ' ', apellidoP, ' ', apellidoM) AS nombreCompleto 
        FROM alumno 
        WHERE 
            matricula LIKE CONCAT('%', ?, '%') OR 
            nombre LIKE CONCAT('%', ?, '%') OR 
            apellidoP LIKE CONCAT('%', ?, '%') OR 
            apellidoM LIKE CONCAT('%', ?, '%');
    ";

    private static $count_students = "
        SELECT COUNT(*) FROM alumno;
    ";

    // Attributes
    private $matricula;
    private $nombre;
    private $apePat;
    private $apeMat;
    private $promedio;
    private $cantExams;
    private $correo;
    private $telefono;
    private $genero;
    private $fechaNacimiento;
    private $curp;
    private $nombreCompleto;

    // Set the database connection
    public static function setConnection($connection) {
        self::$conn = $connection;
    }

    // Getters
    public function getMatricula() { return $this->matricula; }
    public function getNombre() { return $this->nombre; }
    public function getApePat() { return $this->apePat; }
    public function getApeMat() { return $this->apeMat; }
    public function getPromedio() { return $this->promedio; }
    public function getCantExams() { return $this->cantExams; }
    public function getCorreo() { return $this->correo; }
    public function getTelefono() { return $this->telefono; }
    public function getGenero() { return $this->genero; }
    public function getFechaNacimiento() { return $this->fechaNacimiento; }
    public function getCurp() { return $this->curp; }
    public function getNombreCompleto() { return $this->nombreCompleto; }

    // Constructor
    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->matricula = 0;
            $this->nombreCompleto = '';
            $this->promedio = '';
            $this->cantExams = '';
        } elseif ($numArgs == 2) {
            $this->matricula = $args[0];
            $this->nombreCompleto = $args[1];
        } elseif ($numArgs == 10) {
            $this->matricula = $args[0];
            $this->nombre = $args[1];
            $this->apePat = $args[2];
            $this->apeMat = $args[3];
            $this->promedio = $args[4];
            $this->correo = $args[5];
            $this->telefono = $args[6];
            $this->genero = $args[7];
            $this->fechaNacimiento = $args[8];
            $this->curp = $args[9];
        }
    }
    

    // Static method to get student by matricula
    public static function get($matricula) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$select_one);
        $command->bind_param('i', $matricula);
        $command->execute();
        $command->bind_result( $matricula, $nombre,
            $apePat,
            $apeMat,
            $promedio,
            $correo,
            $telefono,
            $genero,
            $fechaNacimiento,
            $curp
        );

        if ($command->fetch()) {
            return new Student($matricula, $nombre, $apePat, $apeMat, $promedio, $correo, $telefono, $genero, $fechaNacimiento, $curp);
        } else {
            return null;
        }
    }

    public static function getTotalStudents() {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        $command = self::$conn->prepare(self::$count_students);
        $command->execute();
        $command->bind_result($total);
        $command->fetch();
        $command->close();
    
        return $total;
    }
    
    

    // Static method to get all students
    public static function getAll($limit = 10, $offset = 0) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$select_all);
        $command->bind_param("ii", $limit, $offset);
        $command->execute();
        $command->bind_result(
            $matricula,
            $nombreCompleto
        );

        $students = [];
        while ($command->fetch()) {
            $students[] = new Student($matricula, $nombreCompleto);
        }

        return $students;
    }
    
    public static function searchStudent($searchTerm) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        // Preparar y ejecutar la consulta con el término de búsqueda
        $command = self::$conn->prepare(self::$search_students);
        $command->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $command->execute();
        $command->bind_result(
            $matricula,
            $nombreCompleto
        );
    
        $students = [];
        while ($command->fetch()) {
            $students[] = new Student($matricula, $nombreCompleto);
        }
    
        return $students;
    }

    
}

Student::setConnection($conn);
?>
