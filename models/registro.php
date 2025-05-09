<?php

class Registro {
    private static $select = 'SELECT matricula, grupo FROM registro';
    private static $selectOne = 'SELECT matricula, grupo FROM registro WHERE matricula = ? AND grupo = ?';
    private static $insertRegistro = 'INSERT INTO registro (matricula, grupo) VALUES (?, ?)';
    private static $selectGrupo = 'SELECT r.matricula, r.grupo, g.nombre 
            FROM registro AS r
            INNER JOIN grupo AS g ON r.grupo = g.grupo 
            WHERE r.matricula = ?';

    private $matricula;
    private $grupo;
    private $grupoNombre;

    public function __construct($matricula, $grupo, $grupoNombre = null) {
        $this->matricula = $matricula;
        $this->grupo = $grupo;
        $this->grupoNombre = $grupoNombre;
    }

    public function getMatricula() { return $this->matricula; }
    public function setMatricula($matricula) { $this->matricula = $matricula; }


    public function getGrupoNombre() { return $this->grupoNombre; }
    public function setGrupoNombre($grupoNombre) { $this->grupoNombre = $grupoNombre; }

    public static function get($matricula = null, $grupo = null) {
        $connection = Connection::get_connection();

        if (is_null($matricula) && is_null($grupo)) {
            $registros = [];
            $command = $connection->prepare(self::$select);
            $command->execute();
            $command->bind_result($matricula, $grupo);

            while ($command->fetch()) {
                $registros[] = new Registro($matricula, $grupo);
            }

            $command->close();
            $connection->close();
            return $registros;
        } else {
            $command = $connection->prepare(self::$selectOne);
            $command->bind_param('si', $matricula, $grupo);
            $command->execute();
            $command->bind_result($matricula, $grupo);

            $registro = null;
            if ($command->fetch()) {
                $registro = new Registro($matricula, $grupo);
            }

            $command->close();
            $connection->close();
            return $registro;
        }
    }

    public static function insertRegistro($matricula, $grupo) {
        if (empty($matricula) || empty($grupo)) {
            throw new Exception("Matrícula o grupo no pueden estar vacíos.");
        }

        $connection = Connection::get_connection();
        $command = $connection->prepare(self::$insertRegistro);
        $command->bind_param('si', $matricula, $grupo);

        $success = $command->execute();
        $command->close();
        $connection->close();

        if (!$success) {
            throw new Exception("Error al insertar el registro.");
        }
        return true;
    }

    public static function getGrupo($matricula) {
        if (empty($matricula)) {
            throw new Exception("La matrícula no puede estar vacía.");
        }

        $connection = Connection::get_connection();
        $command = $connection->prepare(self::$selectGrupo);
        $command->bind_param('s', $matricula);
        $command->execute();
        $command->bind_result($matricula, $grupo, $grupoNombre);

        $registro = null;
        if ($command->fetch()) {
            $registro = new Registro($matricula, $grupo, $grupoNombre);
        }

        $command->close();
        $connection->close();
        return $registro;
    }

    public static function checkAlumnoGrupo($matricula)
    {
        $connection = connection::get_connection();
        $query = "SELECT g.nombre AS nombre_grupo 
                  FROM registro r 
                  INNER JOIN grupo g ON r.grupo = g.grupo 
                  WHERE r.matricula = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $result = $stmt->get_result();
        $grupo = $result->fetch_assoc();
        $stmt->close();
        $connection->close();
    
        return $grupo;
    }

    public static function getAlumnosDeGrupo($grupoId) {
        if (empty($grupoId)) {
            throw new Exception("El ID del grupo no puede estar vacío.");
        }
    
        $connection = Connection::get_connection();
        // Modificar la consulta para obtener cantidadExams de la tabla alumno
        $query = "SELECT a.foto, r.matricula, a.nombre, a.apellidoP, a.promedio, g.nombre AS nombre_grupo, a.cantidadExams
                  FROM registro r
                  INNER JOIN alumno a ON r.matricula = a.matricula
                  INNER JOIN grupo g ON r.grupo = g.grupo
                  WHERE r.grupo = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $grupoId);
        $command->execute();
        $result = $command->get_result();
    
        $alumnos = [];
        $grupoData = null;
    
        while ($row = $result->fetch_assoc()) {
            if ($grupoData === null) {
                // Almacena los datos del grupo solo una vez
                $grupoData = [
                    'nombre_grupo' => $row['nombre_grupo'],
                    'cantidad_examenes' => $row['cantidadExams'], // Aquí tomamos la cantidad de exámenes desde la tabla alumno
                ];
            }
            // Agrega cada alumno al arreglo
            $alumnos[] = $row;
        }
    
        $command->close();
        $connection->close();
    
        return ['alumnos' => $alumnos, 'grupoData' => $grupoData];
    }
    
    public static function eliminarAlumnoDeGrupo($matricula, $grupoId) {
        if (empty($matricula) || empty($grupoId)) {
            throw new Exception("La matrícula o el ID del grupo no pueden estar vacíos.");
        }
    
        $connection = Connection::get_connection();
        $query = "DELETE FROM registro WHERE matricula = ? AND grupo = ?";
        $command = $connection->prepare($query);
        $command->bind_param('si', $matricula, $grupoId);
        
        if ($command->execute()) {
            $command->close();
            $connection->close();
            return true;
        } else {
            $command->close();
            $connection->close();
            throw new Exception("No se pudo eliminar al alumno.");
        }
    }
    
    

}
?>
