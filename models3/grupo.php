<?php

 

class grupo {

    private static $select = 'SELECT grupo, nombre, fechaInicio, fechaFinal, promedio, cantidadAlumnos, estado FROM grupo';

    private static $selectone = 'SELECT grupo, nombre, fechaInicio, fechaFinal, promedio, cantidadAlumnos, estado FROM grupo WHERE grupo = ?';

    private $grupo;
    private $nombre;
    private $fechaInicio;
    private $fechaFinal;
    private $promedio;
    private $cantidadAlumnos;
    private $estado;

    public function __construct($grupo, $nombre, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos, $estado) {
        $this->grupo = $grupo;
        $this->nombre = $nombre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->promedio = $promedio;
        $this->cantidadAlumnos = $cantidadAlumnos;
        $this->estado = $estado;
    }


    public function getGrupo() { return $this->grupo; }
    public function setGrupo($grupo) { $this->grupo = $grupo; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getFechaInicio() { return $this->fechaInicio; }
    public function setFechaInicio($fechaInicio) { $this->fechaInicio = $fechaInicio; }

    public function getFechaFinal() { return $this->fechaFinal; }
    public function setFechaFinal($fechaFinal) { $this->fechaFinal = $fechaFinal; }

    public function getPromedio() { return $this->promedio; }
    public function setPromedio($promedio) { $this->promedio = $promedio; }

    public function getCantidadAlumnos() { return $this->cantidadAlumnos; }
    public function setCantidadAlumnos($cantidadAlumnos) { $this->cantidadAlumnos = $cantidadAlumnos; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $grupolist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($grupo, $nombre, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos, $estado);

            while ($command->fetch()) {
                array_push($grupolist, new grupo($grupo, $nombre, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos, $estado));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $grupolist;

        } else if (func_num_args() == 1) {
            $grupolist = null;
            
            $command = $connection->prepare(self::$selectone);
            $grupo = func_get_arg(0);
            $command->bind_param('i', $grupo);
            $command->execute();
            
            $command->bind_result($grupo, $nombre, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos, $estado);
    
            if ($command->fetch()) {
                $grupolist = new grupo($grupo, $nombre, $fechaInicio, $fechaFinal, $promedio, $cantidadAlumnos, $estado);
                mysqli_stmt_close($command);
                $connection->close();
                return $grupolist;
            }
        }
        return null;
    }





}


?>