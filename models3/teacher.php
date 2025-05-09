<?php


class teacher {

    private static $select = 'SELECT numEmpleado, nombre, apellidoP, apellidoM, correo, telefono, foto, genero, fechaNacimiento, curp FROM docente';

    private static $selectone = 'SELECT numEmpleado, nombre, apellidoP, apellidoM, correo, telefono, foto, genero, fechaNacimiento, curp FROM docente WHERE numEmpleado = ?';

    private static $select_sin_grupo = 'SELECT d.numEmpleado, d.nombre, d.apellidoP, d.apellidoM, d.correo, d.telefono, d.foto, d.genero, d.fechaNacimiento, d.curp 
    FROM docente AS d LEFT JOIN asignacion AS a ON d.numEmpleado = a.numEmpleado
    WHERE a.numEmpleado IS NULL';

private static $updatetelefono = "UPDATE docente SET telefono = ? WHERE numEmpleado = ?";

private static $updatecontra = 'UPDATE usuarios SET password = ? WHERE identificador_usuario = ?';


    private $numEmpleado;
    private $nombre;
    private $apellidoP;
    private $apellidoM;
    private $correo;
    private $telefono;
    private $foto;
    private $genero;
    private $fechaNacimiento;
    private $curp;


// Constructor
    public function __construct($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp) {
        $this->numEmpleado = $numEmpleado;
        $this->nombre = $nombre;
        $this->apellidoP = $apellidoP;
        $this->apellidoM = $apellidoM;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->foto = $foto;
        $this->genero = $genero;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->curp = $curp;
    }

    // Getters and Setters
    public function getnumEmpleado() { return $this->numEmpleado;}
    public function setnumEmpleado($matricula) { $this->numEmpleado = $matricula; }

    public function getNombre() {return $this->nombre;}
    public function setNombre($nombre) {$this->nombre = $nombre;}

    public function getApellidoP() {return $this->apellidoP;}
    public function setApellidoP($apellidoP) {$this->apellidoP = $apellidoP;}

    public function getApellidoM() {return $this->apellidoM;}
    public function setApellidoM($apellidoM) {$this->apellidoM = $apellidoM;}

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
            $teacherlist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                            
            while ($command->fetch()) {
                array_push($teacherlist, new teacher($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $teacherlist;

        } else if (func_num_args() == 1) {
            $teacher = null;
            
            $command = $connection->prepare(self::$selectone);
            $numEmpleado = func_get_arg(0);
            $command->bind_param('s', $numEmpleado);
            $command->execute();
            
            $command->bind_result($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
    
            if ($command->fetch()) {
                $teacher = new teacher($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                mysqli_stmt_close($command);
                $connection->close();
                return $teacher;
            }
        }
        return null;
    }


    public static function get_docentes_sin_grupo() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $teacherlist = array();
    
            $command = $connection->prepare(self::$select_sin_grupo);
            $command->execute();
    
            $command->bind_result($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp);
                            
            while ($command->fetch()) {
                array_push($teacherlist, new teacher($numEmpleado, $nombre, $apellidoP, $apellidoM, $correo, $telefono, $foto, $genero, $fechaNacimiento, $curp));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $teacherlist;
        }
    }



    public static function actualizartelefono() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 2) {
    
            $telefono = func_get_arg(0);
            $numEmpleado = func_get_arg(1);
    
            $command = $connection->prepare(self::$updatetelefono);
            $command->bind_param("ss", $telefono, $numEmpleado);
            $command->execute();
    
            mysqli_stmt_close($command);
            $connection->close();
    
        }
    }
    
    public static function actualizarcontra() {
    
        $connection = connection::get_connection();
    
        if (func_num_args() == 2) {
    
            $contra = func_get_arg(0);
            $numEmpleado = func_get_arg(1);
    
            $command = $connection->prepare(self::$updatecontra);
            $command->bind_param("ss", $contra, $numEmpleado);
            $command->execute();
    
            mysqli_stmt_close($command);
            $connection->close();
        }
    }
    
    
    public static function get_cursos_docente($numEmpleado) {
        $connection = connection::get_connection();
        $cursoList = [];
    
        $query = "
            SELECT c.cursonum, c.nombre, c.descripcion, c.duracion, c.estado
            FROM curso c
            JOIN asignacion a ON c.cursonum = a.cursonum
            WHERE a.numEmpleado = ?
        ";
    
        $command = $connection->prepare($query);
        $command->execute();
    
        $command->bind_result($cursonum, $nombre, $descripcion, $duracion, $estado);
    
        while ($command->fetch()) {
            array_push($cursoList, [
                'cursonum' => $cursonum,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'duracion' => $duracion,
                'estado' => $estado
            ]);
        }
    
        mysqli_stmt_close($command);
        $connection->close();
    
        return $cursoList;
    }
    
    
    


}
?>