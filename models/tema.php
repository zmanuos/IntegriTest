<?php

class tema {

    private static $select = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE 1';
    private static $select_paginated = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE 1 LIMIT ? OFFSET ?';
    private static $selectone = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE numTema = ?';
    private static $select_temacurso = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE cursonum = ?';
    private static $update = 'UPDATE tema SET nombre = ?, descripcion = ?, cursonum = ? WHERE numTema = ?';

    private $numTema;
    private $nombre;
    private $descripcion;
    private $cursonum;

    public function __construct($numTema, $nombre, $descripcion, $cursonum) {
        $this->numTema = $numTema;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cursonum = $cursonum;
    }

    public function getNumTema() { return $this->numTema; }
    public function setNumTema($numTema) { $this->numTema = $numTema; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getCursonum() { return $this->cursonum; }
    public function setCursonum($cursonum) { $this->cursonum = $cursonum; }

    public static function get($page = 1, $limit = 5, $id = null, $nombre = null) {
        $connection = connection::get_connection();
        $temas = array();

        $offset = ($page - 1) * $limit;
        $query = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE 1';

        if ($id) {
            $query .= ' AND numTema = ?';
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
        $command->bind_result($numTema, $nombre, $descripcion, $cursonum);

        while ($command->fetch()) {
            array_push($temas, new tema($numTema, $nombre, $descripcion, $cursonum));
        }

        mysqli_stmt_close($command);
        $connection->close();

        return $temas;
    }

    public static function count($id = null, $nombre = null) {
        $connection = connection::get_connection();

        $query = 'SELECT COUNT(*) FROM tema WHERE 1';
        
        if ($id) {
            $query .= ' AND numTema = ?';
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
        $command->bind_result($totalTemas);
        $command->fetch();

        mysqli_stmt_close($command);
        $connection->close();

        return $totalTemas;
    }

    public static function get_temacurso($cursonum) {
        $connection = connection::get_connection();
        $temas = array();

        $command = $connection->prepare(self::$select_temacurso);
        $command->bind_param('i', $cursonum);
        $command->execute();
        $command->bind_result($numTema, $nombre, $descripcion, $cursonum);

        while ($command->fetch()) {
            array_push($temas, new tema($numTema, $nombre, $descripcion, $cursonum));
        }

        mysqli_stmt_close($command);
        $connection->close();

        return $temas;
    }

    public static function update($numTema, $nombre, $descripcion, $cursonum) {
        $connection = connection::get_connection();
    
        $updateQuery = "UPDATE tema SET nombre = ?, descripcion = ?, cursonum = ? WHERE numTema = ?";
    
        $command = $connection->prepare($updateQuery);
    
        $command->bind_param('ssii', $nombre, $descripcion, $cursonum, $numTema);
    
        $success = $command->execute();
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $success; 
    }
    

    public static function create($nombre, $descripcion, $cursonum) {
        $connection = connection::get_connection();
        $insertQuery = 'INSERT INTO tema (nombre, descripcion, cursonum) VALUES (?, ?, ?)';
        $command = $connection->prepare($insertQuery);
        $command->bind_param('ssi', $nombre, $descripcion, $cursonum);
        $success = $command->execute();
        mysqli_stmt_close($command);
        $connection->close();
        return $success;
    }

    public static function search($id = null, $nombre = null, $limit = 10, $offset = 0) {
        $connection = connection::get_connection();
        $temas = array();

        $query = 'SELECT numTema, nombre, descripcion, cursonum FROM tema WHERE 1';

        if ($id) {
            $query .= ' AND numTema = ?';
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
        $command->bind_result($numTema, $nombre, $descripcion, $cursonum);

        while ($command->fetch()) {
            array_push($temas, new tema($numTema, $nombre, $descripcion, $cursonum));
        }

        mysqli_stmt_close($command);
        $connection->close();

        return $temas;
    }
}

?>
