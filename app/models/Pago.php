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



    
    public function getById(int $idcontrato): array
    {
        try {
            $sql = "SELECT c.idcontrato, c.idbeneficiario, b.apellidos, b.nombres, c.monto, c.interes, c.fechainicio, c.diapago, c.numcuotas, c.estado 
                    FROM contratos c
                    JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario
                    WHERE c.idcontrato = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idcontrato]);
            $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($contrato) {
                return [
                    "status" => true,
                    "data" => $contrato
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Contrato no encontrado."
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en Contrato::getById: " . $e->getMessage());
            return [
                "status" => false,
                "message" => "Error interno del servidor al obtener contrato por ID: " . $e->getMessage()
            ];
        }
    }
}