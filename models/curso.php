<?php

class curso {

    private static $select = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE 1';
    private static $select_paginated = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE 1 LIMIT ? OFFSET ?';
    private static $selectone = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE cursonum = ?';
    private static $select_cursos_que_no_estan_en_grupo = 'SELECT c.cursonum, c.nombre, c.duracion, c.descripcion, c.numEstado
    FROM curso AS c LEFT JOIN asignacion AS a ON c.cursonum = a.cursonum AND a.grupo = ?
    WHERE a.cursonum IS NULL;';
    private static $update = 'UPDATE curso SET nombre = ?, descripcion = ?, duracion = ?, numEstado = ? WHERE cursonum = ?';

    private $cursonum;
    private $nombre;
    private $duracion;
    private $descripcion;
    private $numEstado;

    public function __construct($cursonum, $nombre, $duracion, $descripcion, $numEstado) {
        $this->cursonum = $cursonum;
        $this->nombre = $nombre;
        $this->duracion = $duracion;
        $this->descripcion = $descripcion;
        $this->numEstado = $numEstado;
    }

    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDuracion() { return $this->duracion; }
    public function setDuracion($duracion) { $this->duracion = $duracion; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getNumEstado() { return $this->numEstado; }
    public function setNumEstado($numEstado) { $this->numEstado = $numEstado; }

    public static function get($page = 1, $limit = 5, $id = null, $nombre = null) {
        $connection = connection::get_connection();
        $examresults = array();
        
        $offset = ($page - 1) * $limit;
        
        $query = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE 1';
        
        if ($id) {
            $query .= ' AND cursonum = ?';
        }
        if ($nombre) {
            $query .= ' AND nombre LIKE ?';
        }
        
        $query .= ' LIMIT ? OFFSET ?';
        
        $command = $connection->prepare($query);
        
        if ($id && $nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('issi', $id, $nombre, $limit, $offset); 
        } elseif ($id) {
            $command->bind_param('iii', $id, $limit, $offset); 
        } elseif ($nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('ssi', $nombre, $limit, $offset); 
        } else {
            $command->bind_param('ii', $limit, $offset); 
        }
        
        $command->execute();
        
        $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);
        
        while ($command->fetch()) {
            array_push($examresults, new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado));
        }
        
        mysqli_stmt_close($command);
        $connection->close();
        
        return $examresults;
    }
    
    
    public static function count($id = null, $nombre = null) {
        $connection = connection::get_connection();
        
        $query = 'SELECT COUNT(*) FROM curso WHERE 1';
        
        if ($id) {
            $query .= ' AND cursonum = ?';
        }
        if ($nombre) {
            $query .= ' AND nombre LIKE ?';
        }
        
        $command = $connection->prepare($query);
        
        if ($id && $nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('is', $id, $nombre);
        } elseif ($id) {
            $command->bind_param('i', $id);
        } elseif ($nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('s', $nombre);
        }
        
        $command->execute();
        
        $command->bind_result($totalCursos);
        $command->fetch();
        
        mysqli_stmt_close($command);
        $connection->close();
        
        return $totalCursos;
    }
    
    

    public static function get_cursos_que_no_estan_en_un_grupo() {
        $connection = connection::get_connection();
        if (func_num_args() == 1) {
            $examresults = array();
            $command = $connection->prepare(self::$select_cursos_que_no_estan_en_grupo);
            $grupo = func_get_arg(0);
            $command->bind_param('i', $grupo);
            $command->execute();
            $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);
            while ($command->fetch()) {
                array_push($examresults, new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $examresults;
        }
    }

    public static function update($id, $nombre, $descripcion, $duracion, $numEstado) {
        $connection = connection::get_connection();
        $command = $connection->prepare(self::$update);
        $command->bind_param('sssii', $nombre, $descripcion, $duracion, $numEstado, $id);
        $success = $command->execute();
        mysqli_stmt_close($command);
        $connection->close();
        return $success;
    }

    public static function create($nombre, $descripcion, $duracion, $numEstado) {
        $connection = connection::get_connection();
        $insertQuery = 'INSERT INTO curso (nombre, descripcion, duracion, numEstado) VALUES (?, ?, ?, ?)';
        $command = $connection->prepare($insertQuery);
        $command->bind_param('sssi', $nombre, $descripcion, $duracion, $numEstado);
        $success = $command->execute();
        mysqli_stmt_close($command);
        $connection->close();
        return $success;
    }

    public static function search($id = null, $nombre = null, $limit = 10, $offset = 0) {
        $connection = connection::get_connection();
        $examresults = array();
        
        $query = 'SELECT cursonum, nombre, duracion, descripcion, numEstado FROM curso WHERE 1';
        
        if ($id) {
            $query .= ' AND cursonum = ?';
        }
        if ($nombre) {
            $query .= ' AND nombre LIKE ?';
        }
        
        $query .= ' LIMIT ? OFFSET ?';
        
        $command = $connection->prepare($query);
        
        if ($id && $nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('issi', $id, $nombre, $limit, $offset);
        } elseif ($id) {
            $command->bind_param('iii', $id, $limit, $offset);
        } elseif ($nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('ssi', $nombre, $limit, $offset);
        } else {
            $command->bind_param('ii', $limit, $offset);
        }
        
        $command->execute();
        $command->bind_result($cursonum, $nombre, $duracion, $descripcion, $numEstado);
        
        while ($command->fetch()) {
            array_push($examresults, new curso($cursonum, $nombre, $duracion, $descripcion, $numEstado));
        }
        
        mysqli_stmt_close($command);
        $connection->close();
        
        return $examresults;
    }
    
}

?>
