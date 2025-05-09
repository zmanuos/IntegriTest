<?php
class Examen {

    private $preguntas = [];

    public function setPreguntas($preguntas) {
        $this->preguntas = $preguntas;
    }

    public function getPreguntas() {
        return $this->preguntas;
    }

    private static $select = 'SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen';
    private static $selectone = 'SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen WHERE numExam = ?';
    private static $sin_realizar = " 
        SELECT e.numExam, e.titulo , e.inicioExamen, DATE_FORMAT(e.finalExamen, '%d %b %y'), e.numEstado, e.numEmpleado
        FROM alumno a 
        JOIN registro r ON a.matricula = r.matricula
        JOIN asignacion asg ON r.grupo = asg.grupo
        JOIN curso c ON asg.cursonum = c.cursonum
        JOIN docente d ON asg.numEmpleado = d.numEmpleado
        JOIN examen e ON e.numempleado = d.numEmpleado
        LEFT JOIN alumno_examen ae ON a.matricula = ae.matricula AND e.numExam = ae.numExam
        WHERE a.matricula = ? AND c.cursonum = ?";
    private static $todos_sin_realizar = " 
        SELECT e.numExam, e.titulo , e.inicioExamen, DATE_FORMAT(e.finalExamen, '%d %b %y'), e.numEstado, e.numEmpleado
        FROM alumno a 
        JOIN registro r ON a.matricula = r.matricula
        JOIN asignacion asg ON r.grupo = asg.grupo
        JOIN curso c ON asg.cursonum = c.cursonum
        JOIN docente d ON asg.numEmpleado = d.numEmpleado
        JOIN examen e ON e.numempleado = d.numEmpleado
        LEFT JOIN alumno_examen ae ON a.matricula = ae.matricula AND e.numExam = ae.numExam
        WHERE a.matricula = ?";
    private static $selectTemas = "
        SELECT t.nombre 
        FROM tema t
        JOIN tema_examen te ON t.numTema = te.numTema
        WHERE te.numExam = ?";

    private $numExam;
    private $titulo;
    private $inicioExamen;
    private $finalExamen;
    private $numEstado;
    private $numEmpleado;

    public function __construct($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado) {
        $this->numExam = $numExam;
        $this->titulo = $titulo;
        $this->inicioExamen = $inicioExamen;
        $this->finalExamen = $finalExamen;
        $this->numEstado = $numEstado;
        $this->numEmpleado = $numEmpleado;
    }

    public function getNumExam() { return $this->numExam; }
    public function setNumExam($numExam) { $this->numExam = $numExam; }

    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }

    public function getInicioExamen() { return $this->inicioExamen; }
    public function setInicioExamen($inicioExamen) { $this->inicioExamen = $inicioExamen; }

    public function getFinalExamen() { return $this->finalExamen; }
    public function setFinalExamen($finalExamen) { $this->finalExamen = $finalExamen; }

    public function getNumEstado() { return $this->numEstado; }
    public function setNumEstado($numEstado) { $this->numEstado = $numEstado; }

    public function getNumEmpleado() { return $this->numEmpleado; }
    public function setNumEmpleado($numEmpleado) { $this->numEmpleado = $numEmpleado; }

    public static function get() {
        $connection = connection::get_connection();
        if (func_num_args() == 0) {
            $examresults = array();
            $command = $connection->prepare(self::$select);
            $command->execute();
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
            while ($command->fetch()) {
                array_push($examresults, new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $examresults;
        } else if (func_num_args() == 1) {
            $examresults = null;
            $command = $connection->prepare(self::$selectone);
            $examen = func_get_arg(0);
            $command->bind_param('i', $examen);
            $command->execute();
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
            if ($command->fetch()) {
                $examresults = new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
                mysqli_stmt_close($command);
                $connection->close();
                return $examresults;
            }
        }
        return null;
    }

    public static function get_examenes_sin_realizar() {
        $connection = connection::get_connection();
        if (func_num_args() == 2) {
            $exams = [];
            $command = $connection->prepare(self::$sin_realizar);
            $matricula = func_get_arg(0);
            $curso = func_get_arg(1);
            $command->bind_param('si', $matricula, $curso);
            $command->execute();
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
            while ($command->fetch()) {
                array_push($exams, new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $exams;
        }
        return null;
    }

    public static function get_examenes_todos_sin_realizar() {
        $connection = connection::get_connection();
        if (func_num_args() == 1) {
            $exams = [];
            $command = $connection->prepare(self::$todos_sin_realizar);
            $matricula = func_get_arg(0);
            $command->bind_param('s', $matricula);
            $command->execute();
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
            while ($command->fetch()) {
                array_push($exams, new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $exams;
        }
        return null;
    }

    public static function getTemas($numExam) {
        $connection = connection::get_connection();
        $temas = [];
        $command = $connection->prepare(self::$selectTemas);
        $command->bind_param('i', $numExam);
        $command->execute();
        $command->bind_result($nombreTema);
        while ($command->fetch()) {
            array_push($temas, $nombreTema);
        }
        mysqli_stmt_close($command);
        $connection->close();
        return $temas;
    }

    public static function getNombreDocente($numEmpleado) {
        $connection = connection::get_connection();
        $command = $connection->prepare("SELECT nombre, apellidoP FROM docente WHERE numEmpleado = ?");
        $command->bind_param('i', $numEmpleado);
        $command->execute();
        $command->bind_result($nombre, $apellidoP);
        $command->fetch();
        mysqli_stmt_close($command);
        $connection->close();
        return $nombre . ' ' . $apellidoP;
    }

    public static function getDocenteDetails($numEmpleado) {
        $connection = connection::get_connection();
        $command = $connection->prepare("SELECT nombre, apellidoP, numEmpleado, correo, telefono FROM docente WHERE numEmpleado = ?");
        $command->bind_param('i', $numEmpleado);
        $command->execute();
        $command->bind_result($nombre, $apellidoP, $numEmpleado, $correo, $telefono);
        $command->fetch();
        mysqli_stmt_close($command);
        $connection->close();
        
        return [
            'nombre' => $nombre,
            'apellido' => $apellidoP,
            'numEmpleado' => $numEmpleado,
            'correo' => $correo,
            'telefono' => $telefono
        ];
    }
    

    public static function searchByDocente($nombreDocente) {
        $connection = connection::get_connection();
        $examResults = [];
        $command = $connection->prepare("
            SELECT e.numExam, e.titulo, e.inicioExamen, e.finalExamen, e.numEstado, e.numEmpleado
            FROM examen e
            JOIN docente d ON e.numEmpleado = d.numEmpleado
            WHERE CONCAT(d.nombre, ' ', d.apellidoP) LIKE ?");
        $searchTerm = "%$nombreDocente%";
        $command->bind_param('s', $searchTerm);
        $command->execute();
        $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
        while ($command->fetch()) {
            array_push($examResults, new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
        }
        mysqli_stmt_close($command);
        $connection->close();
        return $examResults;
    }

    public static function searchByGrupo($nombreGrupo) {
        $connection = connection::get_connection();
        $examResults = [];
        $command = $connection->prepare("
            SELECT e.numExam, e.titulo, e.inicioExamen, e.finalExamen, e.numEstado, e.numEmpleado
            FROM examen e
            JOIN grupo g ON e.numExam = g.numExam
            WHERE g.nombreGrupo LIKE ?");
        $searchTerm = "%$nombreGrupo%";
        $command->bind_param('s', $searchTerm);
        $command->execute();
        $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
        while ($command->fetch()) {
            array_push($examResults, new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
        }
        mysqli_stmt_close($command);
        $connection->close();
        return $examResults;
    }

    public function save() {
        $connection = connection::get_connection();
        $command = $connection->prepare("
            INSERT INTO examen (numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado)
            VALUES (?, ?, ?, ?, ?, ?)");
        $command->bind_param('isssii', $this->numExam, $this->titulo, $this->inicioExamen, $this->finalExamen, $this->numEstado, $this->numEmpleado);
        $command->execute();
        $command->close();
        $connection->close();
    }

    public static function getExamenById($id) {
        $connection = connection::get_connection();
        $exam = null;
    
        $command = $connection->prepare(self::$selectone);
        $command->bind_param('i', $id);
        $command->execute();
        $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
        if ($command->fetch()) {
            $exam = new Examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
            $questions = pregunta::get_exam($id); 
    
            $exam->setPreguntas($questions);
        }
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $exam;
    }
    
    public static function searchByName($searchName) {
        $allExams = self::get(); // Obtener todos los exámenes
        return array_filter($allExams, function($examen) use ($searchName) {
            return stripos($examen->getTitulo(), $searchName) !== false;
        });
    }
}
    

?>
