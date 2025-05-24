<?php
// app/models/Pago.php
require_once '../config/Database.php';

class Pago
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConexion();
    }

    public function saveCuota(int $idcontrato, int $numcuota, float $monto, float $penalidad = 0.00): array
    {
        try {
            $sql = "INSERT INTO pagos (idcontrato, numcuota, monto, penalidad) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idcontrato, $numcuota, $monto, $penalidad]); 

            return ["status" => true, "message" => "Cuota guardada exitosamente."];
        } catch (PDOException $e) {
            error_log("Error en Pago::saveCuota: " . $e->getMessage());
            return ["status" => false, "message" => "Error al guardar cuota: " . $e->getMessage()];
        }
    }

}