<?php
class GrupoHistorial
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM grupohistorial";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function buscarPorGrupoNumero($grupoNumero)
    {
        $sql = "SELECT * FROM grupohistorial WHERE grupo_numero = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $grupoNumero);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function buscarPorGrupoNombre($grupoNombre)
    {
        $sql = "SELECT * FROM grupohistorial WHERE grupo_nombre LIKE ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $likeGrupoNombre = "%$grupoNombre%";
            $stmt->bind_param("s", $likeGrupoNombre);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function buscarPorMatricula($matricula)
    {
        $sql = "SELECT * FROM grupohistorial WHERE alumno_matricula = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $matricula);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
}
?>
