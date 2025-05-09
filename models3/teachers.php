<?php

require_once('../../includes/db_connection.php');

class Teacher {
    private static $conn; // Conexión a la base de datos
    // SQL statements
    private static $select_one = '
        SELECT 
            numEmpleado, 
            nombre, 
            apellidoP, 
            apellidoM,  
            correo,
            telefono,
            IF(genero = "M", "Hombre", "Mujer"),
            curp

        FROM docente 
        WHERE numEmpleado = ?;
    ';

    private static $select_all = "
        SELECT 
            numEmpleado,
            CONCAT(nombre, ' ', apellidoP, ' ', apellidoM) AS nombreCompleto
        FROM docente 
        ORDER BY numEmpleado
        LIMIT ? OFFSET ?;
    ";

    private static $search_teachers = "
        SELECT numEmpleado, CONCAT(nombre, ' ', apellidoP, ' ', apellidoM) AS nombreCompleto 
        FROM docente 
        WHERE 
            numEmpleado LIKE CONCAT('%', ?, '%') OR 
            nombre LIKE CONCAT('%', ?, '%') OR 
            apellidoP LIKE CONCAT('%', ?, '%') OR 
            apellidoM LIKE CONCAT('%', ?, '%')
            ORDER BY numEmpleado
            LIMIT ? OFFSET ?;
    ";

    private static $count_teachers = "
        SELECT COUNT(*) FROM docente;
    ";

    // Attributes
    private $numEmpleado;
    private $nombre;
    private $apePat;
    private $apeMat;
    private $correo;
    private $telefono;
    private $genero;
    private $curp;
    private $nombreCompleto;

    // Set the database connection
    public static function setConnection($connection) {
        self::$conn = $connection;
    }

    // Getters
    public function getNumEmpleado() { return $this->numEmpleado; }
    public function getNombre() { return $this->nombre; }
    public function getApePat() { return $this->apePat; }
    public function getApeMat() { return $this->apeMat; }
    public function getCorreo() { return $this->correo; }
    public function getTelefono() { return $this->telefono; }
    public function getGenero() { return $this->genero; }
    public function getCurp() { return $this->curp; }
    public function getNombreCompleto() { return $this->nombreCompleto; }

    // Constructor
    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->numEmpleado = 0;
            $this->nombreCompleto = '';
        } elseif ($numArgs == 2) {
            $this->numEmpleado = $args[0];
            $this->nombreCompleto = $args[1];
        } elseif ($numArgs == 8) {
            $this->numEmpleado = $args[0];
            $this->nombre = $args[1];
            $this->apePat = $args[2];
            $this->apeMat = $args[3];
            $this->correo = $args[4];
            $this->telefono = $args[5];
            $this->genero = $args[6];
            $this->curp = $args[7];
        }
    }
    

    // Static method to get student by matricula
    public static function get($numEmpleado) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$select_one);
        $command->bind_param('i', $numEmpleado);
        $command->execute();
        $command->bind_result(
            $numEmpleado,
            $nombre,
            $apePat,
            $apeMat,
            $correo,
            $telefono,
            $genero,
            $curp
        );

        if ($command->fetch()) {
            return new Teacher($numEmpleado, $nombre, $apePat, $apeMat, $correo, $telefono, $genero, $curp);
        } else {
            return null;
        }
    }

    public static function getTotalTeachers() {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        $command = self::$conn->prepare(self::$count_teachers);
        $command->execute();
        $command->bind_result($total);
        $command->fetch();
        $command->close();
    
        return $total;
    }
    
    

    // Static method to get all students
    public static function getAll($limit = 0, $offset = 0) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }

        $command = self::$conn->prepare(self::$select_all);
        $command->bind_param("ii", $limit, $offset);
        $command->execute();
        $command->bind_result(
            $numEmpleado,
            $nombreCompleto
        );

        $teachers = [];
        while ($command->fetch()) {
            $teachers[] = new Teacher($numEmpleado, $nombreCompleto);
        }

        return $teachers;
    }
    
    public static function searchTeacher($searchTerm, $limit = 0, $offset = 0) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        // Preparar y ejecutar la consulta con el término de búsqueda
        $command = self::$conn->prepare(self::$search_teachers);
        $command->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
        $command->execute();
        $command->bind_result(
            $numEmpleado,
            $nombreCompleto
        );
    
        $teachers = [];
        while ($command->fetch()) {
            $teachers[] = new Teacher($numEmpleado, $nombreCompleto);
        }
    
        return $teachers;
    }

    
}

Teacher::setConnection($conn);
?>
