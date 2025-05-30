<?php


require_once '../config/Database.php';


class Contrato
{
    private $conexion;


    public function __construct()
    {
        $this->conexion = Database::getConexion();
    }

        public function getConexion(): PDO
    {
        return $this->conexion;
    }


    public function getAll(): array {
        try {
            $sql = "SELECT c.idcontrato, b.apellidos, b.nombres, c.monto, c.interes, c.fechainicio, c.diapago, c.numcuotas, c.estado 
                    FROM contratos c
                    JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => true,
                "data" => $result
            ];
        } catch(PDOException $e) {
            error_log("Error en Contrato::getAll: " . $e->getMessage());
            return [
                "status" => false,
                "message" => "Error al obtener todos los contratos: " . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $sql = "INSERT INTO contratos (idbeneficiario, monto, interes, fechainicio, diapago, numcuotas) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $data['idbeneficiario'],
                $data['monto'],
                $data['interes'],
                $data['fechainicio'],
                $data['diapago'],
                $data['numcuotas']
            ]);

            return ["status" => true, "message" => "Contrato creado exitosamente.", "id" => $this->conexion->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error en Contrato::create: " . $e->getMessage());
            return ["status" => false, "message" => "Error al crear el contrato: " . $e->getMessage()];
        }
    }



    

}

// $contrto = new Contrato();

// var_dump($contrto->getAll());