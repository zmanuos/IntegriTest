<?php

class Grupo {

    private static $select = 'SELECT grupo, nombre, estado, fechaInicio, fechaFinal, cantidadAlumnos FROM grupo WHERE 1';
    private static $select_paginated = 'SELECT grupo, nombre, estado, fechaInicio, fechaFinal, cantidadAlumnos FROM grupo WHERE 1 LIMIT ? OFFSET ?';
    private static $selectone = 'SELECT grupo, nombre, estado, fechaInicio, fechaFinal, cantidadAlumnos FROM grupo WHERE grupo = ?';
    private static $update = 'UPDATE grupo SET nombre = ?, estado = ?, fechaInicio = ?, fechaFinal = ? WHERE grupo = ?';
    private static $insert = 'INSERT INTO grupo (nombre, estado, fechaInicio, fechaFinal) VALUES (?, ?, ?, ?)';

    private $grupo;
    private $nombre;
    private $estado;
    private $fechaInicio;
    private $fechaFinal;
    private $cantidadAlumnos;
    private $promedio;

    public function __construct($grupo, $nombre, $estado, $cantidadAlumnos, $fechaInicio, $fechaFinal,$promedio) {
        $this->grupo = $grupo;
        $this->nombre = $nombre;
        $this->estado = $estado;
        $this->cantidadAlumnos = $cantidadAlumnos;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->promedio = $promedio;

    }

    // Getters y setters
    public function getGrupo() { return $this->grupo; }
    public function setGrupo($grupo) { $this->grupo = $grupo; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getFechaInicio() { return $this->fechaInicio; }
    public function setFechaInicio($fechaInicio) { $this->fechaInicio = $fechaInicio; }

    public function getFechaFinal() { return $this->fechaFinal; }
    public function setFechaFinal($fechaFinal) { $this->fechaFinal = $fechaFinal; }

    public function getCantidadAlumnos() { return $this->cantidadAlumnos; }
    public function setCantidadAlumnos($cantidadAlumnos) { $this->cantidadAlumnos = $cantidadAlumnos; }

    public function getPromedio() {return $this->promedio; }
    
    
    // Métodos estáticos

    public static function create($nombre, $estado, $fechaInicio, $fechaFinal) {
        $connection = connection::get_connection();
        $command = $connection->prepare(self::$insert);
        $command->bind_param('siss', $nombre, $estado, $fechaInicio, $fechaFinal);
        $success = $command->execute();
        mysqli_stmt_close($command);
        $connection->close();
        return $success;
    }

    public static function update($grupoId, $nombre, $estado, $fechaInicio, $fechaFinal) {
        $connection = connection::get_connection();
    
        $query = "UPDATE grupo SET nombre = ?, estado = ?, fechaInicio = ?, fechaFinal = ? WHERE grupo = ?";
    
        $command = $connection->prepare($query);
    
        $command->bind_param('sissi', $nombre, $estado, $fechaInicio, $fechaFinal, $grupoId);
    
        $success = $command->execute();
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $success;
    }
    


    public static function search($grupo = null, $nombre = null, $limit = 10, $offset = 0) {
        $connection = connection::get_connection();
        $examresults = array();
        $query = 'SELECT grupo, nombre, estado, fechaInicio, fechaFinal, promedio, cantidadAlumnos FROM grupo WHERE 1';
        if ($grupo) {
            $query .= ' AND grupo = ?';
        }
        if ($nombre) {
            $query .= ' AND nombre LIKE ?';
        }
        $query .= ' LIMIT ? OFFSET ?';
        $command = $connection->prepare($query);
        if ($grupo && $nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('issi', $grupo, $nombre, $limit, $offset);
        } elseif ($grupo) {
            $command->bind_param('iii', $grupo, $limit, $offset);
        } elseif ($nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('ssi', $nombre, $limit, $offset);
        } else {
            $command->bind_param('ii', $limit, $offset);
        }
        $command->execute();
        $command->bind_result($grupo, $nombre, $estado, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos);
        while ($command->fetch()) {
            array_push($examresults, new Grupo($grupo, $nombre, $estado, $cantidadAlumnos, $fechaInicio, $fechaFinal, $promedio));
        }
        mysqli_stmt_close($command);
        $connection->close();
        return $examresults;
    }

    public static function count($grupo = null, $nombre = null) {
        $connection = connection::get_connection();
        $query = 'SELECT COUNT(*) FROM grupo WHERE 1';
        if ($grupo) {
            $query .= ' AND grupo = ?';
        }
        if ($nombre) {
            $query .= ' AND nombre LIKE ?';
        }
        $command = $connection->prepare($query);
        if ($grupo && $nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('is', $grupo, $nombre);
        } elseif ($grupo) {
            $command->bind_param('i', $grupo);
        } elseif ($nombre) {
            $nombre = "%$nombre%";
            $command->bind_param('s', $nombre);
        }
        $command->execute();
        $command->bind_result($totalGrupos);
        $command->fetch();
        mysqli_stmt_close($command);
        $connection->close();
        return $totalGrupos;
    }
}

?>
