<?php

require_once('../../includes/db_connection.php');


class Exam {
    private static $conn; // Conexión a la base de datos

    private static $student_exam = "
        SELECT 
            ae.numExam,
            e.titulo,
            ae.fechaRealizacion,
            ae.calificacion
        FROM alumno_examen AS ae
        INNER JOIN examen AS e ON ae.numExam = e.numExam
        WHERE 
            ae.matricula = ?;
    ";

    
    
    // Attributes
    private $numExam;
    private $titulo;
    private $fechaRealizacion;
    private $calificacion;
    private $matricula;


    // Set the database connection
    public static function setConnection($connection) {
        self::$conn = $connection;
    }

    // Getters
    public function getNumExam() { return $this->numExam; }
    public function getTitulo() { return $this->titulo; }
    public function getFechaRealizacion() { return $this->fechaRealizacion; }
    public function getCalificacion() { return $this->calificacion; }
    public function getMatricula() { return $this->matricula; }
    
    


    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs == 0) {
            $this->numExam = '';
            $this->titulo = '';
            $this->fechaRealizacion = '';
            $this->calificacion = '';
        } elseif ($numArgs == 4) {
            $this->numExam = $args[0];
            $this->titulo = $args[1];       
            $this->fechaRealizacion = $args[2];       
            $this->calificacion = $args[3];       
        }
    }

    
    public static function viewExam($matricula) {
        if (!self::$conn) {
            die("Error: No se ha establecido la conexión a la base de datos.");
        }
    
        // Preparar y ejecutar la consulta con el término de búsqueda
        $command = self::$conn->prepare(self::$student_exam);
        $command->bind_param('s', $matricula);
        $command->execute();
        $command->bind_result(
            $numExam,
            $titulo,
            $fechaRealizacion,
            $calificacion
        );
    
        $exams = [];
        while ($command->fetch()) {
            $exams[] = new Exam($numExam, $titulo, $fechaRealizacion, $calificacion);
        }
    
        return $exams;
    }

}
Exam::setConnection($conn);
?>