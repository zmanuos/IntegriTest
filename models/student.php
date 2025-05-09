<?php

 

class student {

   private static $select = 'SELECT matricula, nombre, apellidoP, apellidoM, promedio, cantidadExams, correo, telefono, foto, genero, fechaNacimiento, curp FROM alumno';

private static $selectone = 'SELECT matricula, nombre, apellidoP, apellidoM, promedio, cantidadExams, correo, telefono, foto, genero, fechaNacimiento, curp FROM alumno WHERE matricula = ?';

private static $select_sin_grupo = 'SELECT al.matricula, al.nombre, al.apellidoP, al.apellidoM, al.promedio, al.cantidadExams, al.correo, al.telefono, al.foto, al.genero, al.fechaNacimiento, al.curp
FROM alumno AS al LEFT JOIN registro AS r ON al.matricula = r.matricula
WHERE r.matricula IS NULL';

private static $select_sin_grupo_especifico = 'SELECT al.matricula, al.nombre, al.apellidoP, al.apellidoM, al.promedio, al.cantidadExams, al.correo, al.telefono, al.foto, al.genero, al.fechaNacimiento, al.curp
FROM alumno AS al LEFT JOIN registro AS r ON al.matricula = r.matricula
WHERE al.matricula = ? AND r.matricula IS NULL; r
';

private static $updatetelefono = "UPDATE alumno SET telefono = ? WHERE matricula = ?";

private static $updatecontra = 'UPDATE usuarios SET password = ? WHERE identificador_usuario = ?';


    private $matricula;
    private $nombre;
    private $apellidoP;
    private $apellidoM;
    private $promedio;
    private $cantidadExams;
    private $correo;
    private $telefono;
    private $foto;
    private $genero;
    private $fechaNacimiento;
    private $curp;

    public function __construct($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp) {
        $this->matricula = $matricula;
        $this->nombre = $nombre;
        $this->apellidoP = $apellidoP;
        $this->apellidoM = $apellidoM;
        $this->promedio = $promedio;
        $this->cantidadExams = $cantidadExams;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->foto = $foto;
        $this->genero = $genero;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->curp = $curp;
    }

    public function getMatricula() { return $this->matricula;}
    public function setMatricula($matricula) { $this->matricula = $matricula; }

    public function getNombre() {return $this->nombre;}
    public function setNombre($nombre) {$this->nombre = $nombre;}

    public function getApellidoP() {return $this->apellidoP;}
    public function setApellidoP($apellidoP) {$this->apellidoP = $apellidoP;}

    public function getApellidoM() {return $this->apellidoM;}
    public function setApellidoM($apellidoM) {$this->apellidoM = $apellidoM;}

    public function getPromedio() {return $this->promedio;}
    public function setPromedio($promedio) {$this->promedio = $promedio;}

    public function getCantidadExams() {return $this->cantidadExams;}
    public function setCantidadExams($cantidadExams) {$this->cantidadExams = $cantidadExams;}

    public function getCorreo() {return $this->correo;}
    public function setCorreo($correo) {$this->correo = $correo;}

    public function getTelefono() {return $this->telefono;}
    public function setTelefono($telefono) {$this->telefono = $telefono;}

    public function getFoto() {return $this->foto;}
    public function setFoto($foto) {$this->foto = $foto;}

    public function getGenero() {return $this->genero;}
    public function setGenero($genero) {$this->genero = $genero;}

    public function getFechaNacimiento() {return $this->fechaNacimiento;}
    public function setFechaNacimiento($fechaNacimiento) {$this->fechaNacimiento = $fechaNacimiento;}

    public function getCurp() {return $this->curp;}
    public function setCurp($curp) {$this->curp = $curp;}



    public static function get() {

        $connection = connection::get_connection();

        if (func_num_args() == 0) {
            $studentslist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                            
            while ($command->fetch()) {
                array_push($studentslist, new student($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $studentslist;

        } else if (func_num_args() == 1) {
            $alumno = null;
            
            $command = $connection->prepare(self::$selectone);
            $matricula = func_get_arg(0);
            $command->bind_param('s', $matricula);
            $command->execute();
            
            $command->bind_result($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
    
            if ($command->fetch()) {
                $alumno = new student($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                mysqli_stmt_close($command);
                $connection->close();
                return $alumno;
            }
        }
        return null;
    }


    public static function get_alumnos_sin_grupo() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $studentslist = array();
            $command = $connection->prepare(self::$select_sin_grupo);
            $command->execute();
            $command->bind_result($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                            
            while ($command->fetch()) {
                array_push($studentslist, new student($matricula, $nombre, $apellidoP, $apellidoM, $promedio, $cantidadExams, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp));
            }
            mysqli_stmt_close($command);
            $connection->close();
            return $studentslist;
        }
}

public static function actualizartelefono() {

    $connection = connection::get_connection();

    if (func_num_args() == 2) {

        $telefono = func_get_arg(0);
        $matricula = func_get_arg(1);

        $command = $connection->prepare(self::$updatetelefono);
        $command->bind_param("ss", $telefono, $matricula);
        $command->execute();

        mysqli_stmt_close($command);
        $connection->close();

    }
}

public static function actualizarcontra() {

    $connection = connection::get_connection();

    if (func_num_args() == 2) {

        $contra = func_get_arg(0);
        $matricula = func_get_arg(1);

        $command = $connection->prepare(self::$updatecontra);
        $command->bind_param("ss", $contra, $matricula);
        $command->execute();

        mysqli_stmt_close($command);
        $connection->close();
    }
}


}
?>