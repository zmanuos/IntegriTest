<?php

class admin {


    private static $select = 'SELECT id_admin, nombre, apellidoP, apellidoM, telefono, correo, foto, fecha_creacion, contador, genero, fechaNacimiento FROM administrador';

    private static $selectone = 'SELECT id_admin, nombre, apellidoP, apellidoM, telefono, correo, foto, fecha_creacion, contador, genero, fechaNacimiento FROM administrador WHERE id_admin = ?';
    
    private static $updatetelefono = 'UPDATE administrador SET telefono = ? WHERE id_admin = ?';
    
    private static $updatecontra = 'UPDATE usuarios SET password = ? WHERE identificador_usuario = ?';

    private $id_admin;
    private $nombre;
    private $apellidoP;
    private $apellidoM;
    private $telefono;
    private $correo;
    private $foto;
    private $fechacreacion;
    private $contador;
    private $genero;
    private $fechaNacimiento;

        public function __construct($id_admin, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $foto, $fechacreacion, $contador , $genero, $fechaNacimiento) {
        $this->id_admin = $id_admin;
        $this->nombre = $nombre;
        $this->apellidoP = $apellidoP;
        $this->apellidoM = $apellidoM;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->foto = $foto;
        $this->fechacreacion = $fechacreacion;
        $this->contador = $contador;
        $this->genero = $genero;
        $this->fechaNacimiento = $fechaNacimiento;
    }


    public function getid_admin() { return $this->id_admin;}
    public function setid_admin($id_admin) { $this->id_admin = $id_admin; }

    public function getNombre() {return $this->nombre;}
    public function setNombre($nombre) {$this->nombre = $nombre;}

    public function getApellidoP() {return $this->apellidoP;}
    public function setApellidoP($apellidoP) {$this->apellidoP = $apellidoP;}

    public function getApellidoM() {return $this->apellidoM;}
    public function setApellidoM($apellidoM) {$this->apellidoM = $apellidoM;}

    public function getTelefono() {return $this->telefono;}
    public function setTelefono($telefono) {$this->telefono = $telefono;}

    public function getCorreo() {return $this->correo;}
    public function setCorreo($correo) {$this->correo = $correo;}

    public function getFoto() {return $this->foto;}
    public function setFoto($foto) {$this->foto = $foto;}

    public function getfechacreacion() {return $this->fechacreacion;}
    public function setfechacreacion($fechacreacion) {$this->fechacreacion = $fechacreacion;}

    public function getcontador() {return $this->contador;}
    public function setcontador($contador) {$this->contador = $contador;}

    public function getGenero() {return $this->genero;}
    public function setGenero($genero) {$this->genero = $genero;}

    public function getFechaNacimiento() {return $this->fechaNacimiento;}
    public function setFechaNacimiento($fechaNacimiento) {$this->fechaNacimiento = $fechaNacimiento;}


    public static function get() {

        $connection = connection::get_connection();
    
        if (func_num_args() == 0) {
            $adminlist = array();
    
            $command = $connection->prepare(self::$select);
            $command->execute();
    
            $command->bind_result($id_admin, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $foto, $fechacreacion, $contador , $genero, $fechaNacimiento);

            while ($command->fetch()) {
                array_push($adminlist, new admin($id_admin, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $foto, $fechacreacion, $contador , $genero, $fechaNacimiento));
            }
    
            mysqli_stmt_close($command);
            $connection->close();
    
            return $adminlist;

        } else if (func_num_args() == 1) {
            $admin = null;
            
            $command = $connection->prepare(self::$selectone);
            $numEmpleado = func_get_arg(0);
            $command->bind_param('s', $numEmpleado);
            $command->execute();
            
            $command->bind_result($id_admin, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $foto, $fechacreacion, $contador , $genero, $fechaNacimiento);
    
            if ($command->fetch()) {
                $admin = new admin($id_admin, $nombre, $apellidoP, $apellidoM, $telefono, $correo, $foto, $fechacreacion, $contador , $genero, $fechaNacimiento);
                mysqli_stmt_close($command);
                $connection->close();
                return $admin;
            }
            if (!$command->execute()) {
                echo "Error al ejecutar la consulta: " . $command->error;
                return null;
            }
            
        }
        return null;
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
            $id_admin = func_get_arg(1);
    
            $command = $connection->prepare(self::$updatecontra);
            $command->bind_param("ss", $contra, $id_admin);
            $command->execute();
    
            mysqli_stmt_close($command);
            $connection->close();
        }
    }
}

?>