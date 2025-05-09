<?php
class tipopregunta {

    private static $select = 'SELECT codeTipo, descripcion FROM tipo_pregunta';
    private static $selectone = 'SELECT codeTipo, descripcion FROM tipo_pregunta WHERE codeTipo = ?';

    private $codeTipo;
    private $descripcion;

    public function __construct($codeTipo, $descripcion) {
        $this->codeTipo = $codeTipo;
        $this->descripcion = $descripcion;
    }

    // Getters y Setters
    public function getCodeTipo() { return $this->codeTipo; }
    public function setCodeTipo($codeTipo) { $this->codeTipo = $codeTipo; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    // Método para obtener todos los registros
    public static function get() {
        $connection = connection::get_connection();

        if (func_num_args() == 0) {
            $resultList = array();
            $command = $connection->prepare(self::$select);
            $command->execute();
            $command->bind_result($codeTipo, $descripcion);

            while ($command->fetch()) {
                array_push($resultList, new TipoPregunta($codeTipo, $descripcion));
            }

            mysqli_stmt_close($command);
            $connection->close();

            return $resultList;
        } else if (func_num_args() == 1) {
            $codeTipo = func_get_arg(0);
            $command = $connection->prepare(self::$selectone);
            $command->bind_param('s', $codeTipo);
            $command->execute();
            $command->bind_result($codeTipo, $descripcion);

            if ($command->fetch()) {
                $result = new TipoPregunta($codeTipo, $descripcion);
                mysqli_stmt_close($command);
                $connection->close();
                return $result;
            }
        }
        return null;
    }
}
?>