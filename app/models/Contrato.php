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
            $sql = "SELECT * FROM list_contratos";
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

    public function add($params = []): array
    {
        try {
            $sql = "CALL sp_add_contrato(?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $params['idbeneficiario'],
                $params['monto'],
                $params['interes'],
                $params['fechainicio'],
                $params['diapago'],
                $params['numcuotas']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $idcontrato = $result['idcontrato'] ?? null;

            return ["status" => true, "message" => "Contrato creado exitosamente.", "id" => $idcontrato ];
        } catch (PDOException $e) {
            return ["status" => false, "message" => "Error al crear el contrato: " . $e->getMessage()];
        }
    }



    

}

// $contrato = new Contrato();

// $params  = [
//     'idbeneficiario' =>7, 
//     'monto' => 3000,
//     'interes' => 5,
//     'fechainicio' => '2025-30-05',
//     'diapago' => '29',
//     'numcuotas' => 12
// ];

// var_dump($contrato->create($params));
