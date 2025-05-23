<?php

require_once '../config/Database.php';


class Beneficiario
{

  private $conexion;

  public function __construct()
  {

      $this->conexion = Database::getConexion();

  }


  public function getAll() {
    try {

        $result = [];

        $sql = "SELECT apellidos, nombres, dni, telefono, direccion FROM beneficiarios";
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


}

// $beneficiario = new Beneficiario();

// var_dump($beneficiario->getAll());