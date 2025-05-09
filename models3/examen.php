<?php

class examen {

    private static $select = 'SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen';

    private static $update = 'UPDATE examen SET titulo = ? WHERE numExam = ?';

    private static $selectone = 'SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen WHERE numExam = ?';

    private static $updateInicio = 'UPDATE examen SET inicioExamen = ? WHERE numExam = ?';

    private static $updateFinal = 'UPDATE examen SET finalExamen = ? WHERE numExam = ?';

    private static $select_docente = 'SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen WHERE numEmpleado = ?';

    private static $insert = 'INSERT INTO examen (numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado, cursonum) VALUES (?, ?, ?, ?, ?, ?, ?)';

    private static $curso_examen = 'SELECT e.numExam, e.titulo, c.cursonum, c.nombre, e.numEstado, e.numEmpleado
    FROM examen as e JOIN tema_examen as te ON e.numExam = te.numExam
    JOIN tema t ON te.numTema = t.numTema
    JOIN curso c ON t.cursonum = c.cursonum
    WHERE e.numExam = ?
    GROUP BY c.cursonum;';

    private static $sin_realizar = " SELECT e.numExam, e.titulo , e.inicioExamen, DATE_FORMAT(e.finalExamen, '%d %b %y'), e.numEstado, e.numEmpleado
    FROM alumno a JOIN registro r ON a.matricula = r.matricula
    JOIN asignacion asg ON r.grupo = asg.grupo
    JOIN curso c ON asg.cursonum = c.cursonum
    JOIN docente d ON asg.numEmpleado = d.numEmpleado
    JOIN examen e ON e.numempleado = d.numEmpleado
    LEFT JOIN alumno_examen ae ON a.matricula = ae.matricula AND e.numExam = ae.numExam
    WHERE a.matricula = ? AND c.cursonum = ?";

private static $todos_sin_realizar = " SELECT e.numExam, e.titulo , e.inicioExamen, DATE_FORMAT(e.finalExamen, '%d %b %y'), e.numEstado, e.numEmpleado
FROM alumno a JOIN registro r ON a.matricula = r.matricula
JOIN asignacion asg ON r.grupo = asg.grupo
JOIN curso c ON asg.cursonum = c.cursonum
JOIN docente d ON asg.numEmpleado = d.numEmpleado
JOIN examen e ON e.numempleado = d.numEmpleado
LEFT JOIN alumno_examen ae ON a.matricula = ae.matricula AND e.numExam = ae.numExam
WHERE a.matricula = ?";

private static $ultimo = " SELECT numExam, titulo, inicioExamen, finalExamen, numEstado, numEmpleado FROM examen WHERE numExam = (SELECT MAX(numExam) FROM examen); ";



    private $numExam;
    private $titulo;
    private $inicioExamen;
    private $finalExamen;
    private $numEstado;
    private $numEmpleado;
    private $numCurso;

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

    public function getnumCurso() { return $this->numCurso; }
    public function setnumCurso($numCurso) { $this->numCurso = $numCurso; }



    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $examresults = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);

            while ($command->fetch()) {
                array_push($examresults, new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
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
                $examresults = new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
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
            $matrcula = func_get_arg(0);
            $curso = func_get_arg(1);
            $command->bind_param('si', $matrcula, $curso);
            $command->execute();
            
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
            while($command->fetch()) {

                array_push($exams, new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $exams;
        }
        return null;
    }

    public static function get_examenes_docente() {

        $connection = connection::get_connection();

        if (func_num_args() == 1) {
            $exams = [];
            
            $command = $connection->prepare(self::$select_docente);
            $numEmpleado = func_get_arg(0);
            $command->bind_param('i', $numEmpleado);
            $command->execute();
            
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
            while($command->fetch()) {

                array_push($exams, new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
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
            $matrcula = func_get_arg(0);
            $command->bind_param('s', $matrcula);
            $command->execute();
            
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
            while($command->fetch()) {

                array_push($exams, new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $exams;
        }
        return null;
    }

    public static function get_ultimo_examen() {

        $connection = connection::get_connection();

        if (func_num_args() == 0) {
            $examresults = null;
            
            $command = $connection->prepare(self::$ultimo);
            $command->execute();
            
            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
    
            if ($command->fetch()) {
                $examresults = new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
                mysqli_stmt_close($command);
                $connection->close();
                return $examresults;
            }
        }
        return null;
    }

    public static function insert() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 7) {
    
            $command = $connection->prepare(self::$insert);
    
            $numExam = func_get_arg(0);
            $titulo = func_get_arg(1);
            $inicioExamen = func_get_arg(2);
            $finalExamen = func_get_arg(3);
            $numEstado = func_get_arg(4);
            $numEmpleado = func_get_arg(5);
            $numCurso = func_get_arg(6);

            $command->bind_param('isssiii', $numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado, $numCurso);
    
            $command->execute();
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return true;
        } else {
            return false;
        }
    }

    public static function get_curso_examen() {

        $connection = connection::get_connection();

        if (func_num_args() == 1) {
            $exams = null;

            $command = $connection->prepare(self::$curso_examen);
            $examen = func_get_arg(0);
            $command->bind_param('i', $examen);
            $command->execute();

            $command->bind_result($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);

            if ($command->fetch()) {
                $exams = new examen($numExam, $titulo, $inicioExamen, $finalExamen, $numEstado, $numEmpleado);
                return $exams;
            }
            mysqli_stmt_close($command);
            $connection->close();
        }
        return null;
    }
    

    public static function updateTitulo($nuevoTitulo, $numExam) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$update);

        $command->bind_param('si', $nuevoTitulo, $numExam);
    

        if ($command->execute()) {
            return true;
        } else {
            return false;
        }

        mysqli_stmt_close($command);
        $connection->close();
    }
    

    public static function updateInicioExamen($nuevoInicio, $numExam) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$updateInicio);
        $command->bind_param('si', $nuevoInicio, $numExam);

        $result = $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

        return $result;
    }


    public static function updateFinalExamen($nuevoFinal, $numExam) {
        $connection = connection::get_connection();

        $command = $connection->prepare(self::$updateFinal);
        $command->bind_param('si', $nuevoFinal, $numExam);

        $result = $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

        return $result;
    }


}


?>