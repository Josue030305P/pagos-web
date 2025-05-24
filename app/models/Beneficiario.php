<?php

require_once '../config/Database.php';


class Beneficiario
{

  private $conexion;

  public function __construct()
  {

      $this->conexion = Database::getConexion();

  }


  public function getByDni(string $dni): array
    {
        try {
            $sql = "SELECT idbeneficiario, apellidos, nombres, dni FROM beneficiarios WHERE dni = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$dni]);
            $beneficiario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($beneficiario) {
                return ["status" => true, "data" => $beneficiario];
            } else {
                return ["status" => false, "message" => "Beneficiario no encontrado."];
            }
        } catch (PDOException $e) {
            error_log("Error en Beneficiario::getByDni: " . $e->getMessage());
            return ["status" => false, "message" => "Error interno del servidor al buscar beneficiario por DNI."];
        }
    }



  public function getAll() {
    try {

        $result = [];

        $sql = "SELECT idbeneficiario, apellidos, nombres, dni, telefono, direccion FROM beneficiarios";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
          "status" => true,
          "data" => $result
        ];


    } catch(PDOException $e) {
      throw new PDOException($e->getMessage());
    }
  }


  public function add($params = []): array{
    try {
      $sql = "CALL sp_add_beneficiario(?,?,?,?,?)"; 
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute(array(
        $params['apellidos'],
        $params['nombres'],
        $params['dni'],
        $params['telefono'],
        $params['direccion']
      ));

      return [
        "status" => true,
        "message" => "Se ha agregado el beneficiario"
      ];

    } catch(PDOException $e) {
      throw new PDOException($e->getMessage());
    }
  }

 public function update($params = []): array{
        try {
        
            $sql = "CALL sp_update_beneficiario(?,?,?,?,?,?)"; 
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute(array(
                $params['idbeneficiario'],
                $params['apellidos'],
                $params['nombres'],
                $params['dni'],
                $params['telefono'],
                $params['direccion'] ?? null
            ));

            if ($stmt->rowCount() > 0) {
                 return [
                    "status" => true,
                    "message" => "Se ha actualizado el beneficiario"
                 ];
            } else {
                return [
                    "status" => false,
                    "message" => "No se encontró el beneficiario con ID " . $params['idbeneficiario'] . " o no hubo cambios en los datos."
                ];
            }

        } catch(PDOException $e) {
          
            throw new PDOException($e->getMessage());
        }
    }



    public function getById(int $idbeneficiario): array
    {
        try {
            $sql = "SELECT idbeneficiario, apellidos, nombres, dni, telefono, direccion FROM beneficiarios WHERE idbeneficiario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idbeneficiario]);
            $beneficiario = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($beneficiario) {
                return [
                    "status" => true,
                    "data" => $beneficiario
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Beneficiario no encontrado."
                ];
            }
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener beneficiario por ID: " . $e->getMessage());
        }
    }


    public function delete($id) {
      try {

        $sql = "DELETE FROM beneficiarios WHERE idbeneficiario=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);

        return [
          "status" => true,
          "message" => "Se ha elimnado correctamente"
        ];

      } catch(PDOException $e) {
        throw new PDOException($e->getMessage());
      }
    }









}
// $beneficiario = new Beneficiario();
// var_dump($beneficiario->getByDni('71774455'));
// var_dump($beneficiario->getById(1));
//   $params = [
//     'idbeneficiario' => 1,
//    'apellidos' => 'Tasayco Hernandez',
//   'nombres' => 'Juan Rodolfo',
//    'dni' => '44333222',
//    'telefono' => '909099878',
//    'direccion' => 'Av San martin #445'
// ];

// var_dump($beneficiario->update($params));
//  var_dump($beneficiario->add($params));

// var_dump($beneficiario->getAll());a