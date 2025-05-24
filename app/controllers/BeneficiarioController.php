<?php
require_once '../models/Beneficiario.php';

if (isset($_SERVER['REQUEST_METHOD'])) {
  header('Content-Type: application/json; charset=utf-8');

  $beneficiario = new Beneficiario();

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      if (isset($_GET['id'])) {

        $idbeneficiario = (int) $_GET['id'];
        try {
          $result = $beneficiario->getById($idbeneficiario);
          if ($result['status']) {
            echo json_encode([
              "status" => true,
              "data" => $result['data']
            ]);
          } else {

            echo json_encode([
              "status" => false,
              "message" => $result['message']
            ]);
          }
        } catch (PDOException $e) {

          echo json_encode([
            "status" => false,
            "message" => "Error interno del servidor al obtener beneficiario por ID: " . $e->getMessage()
          ]);
        }
      } else if (isset($_GET['dni'])) { 
        $dni = $_GET['dni'];
        if (!empty($dni)) {
          $result = $beneficiario->getByDni($dni);
          if ($result['status'] && $result['data']) {
            echo json_encode(["status" => true, "data" => $result['data']]);
          } else {
            http_response_code(404);
            echo json_encode(["status" => false, "message" => "Beneficiario con DNI {$dni} no encontrado."]);
          }
        } else {
          http_response_code(400);
          echo json_encode(["status" => false, "message" => "DNI no proporcionado."]);
        }

      } else {

        try {
          $result = $beneficiario->getAll();
          echo json_encode([
            "status" => true,
            "data" => $result['data']
          ]);
        } catch (PDOException $e) {

          echo json_encode([
            "status" => false,
            "message" => "Error interno del servidor al obtener todos los beneficiarios: " . $e->getMessage()
          ]);
        }
      }
      break;


    case 'POST':
      $input = file_get_contents('php://input');
      $dataJSON = json_decode($input, true);

      $registro = [

        'apellidos' => htmlspecialchars($dataJSON['apellidos'] ?? ''),
        'nombres' => htmlspecialchars($dataJSON['nombres'] ?? ''),
        'dni' => htmlspecialchars($dataJSON['dni'] ?? ''),
        'telefono' => htmlspecialchars($dataJSON['telefono'] ?? ''),
        'direccion' => htmlspecialchars($dataJSON['direccion'] ?? null),
      ];

      try {

        $result = $beneficiario->add($registro);

        echo json_encode([
          "status" => true,
          "message" => "Se ha agregado el beneficiario"

        ]);


      } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
      }
      break;


    case 'PUT':
      $input = file_get_contents('php://input');
      $dataJSON = json_decode($input, true);


      if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
          "status" => false,
          "message" => "ID del beneficiario no proporcionado en la URL para la actualización."
        ]);
        break;
      }

      $registro = [
        'idbeneficiario' => (int) $_GET['id'],
        'apellidos' => htmlspecialchars($dataJSON['apellidos'] ?? ''),
        'nombres' => htmlspecialchars($dataJSON['nombres'] ?? ''),
        'dni' => htmlspecialchars($dataJSON['dni'] ?? ''),
        'telefono' => htmlspecialchars($dataJSON['telefono'] ?? ''),
        'direccion' => htmlspecialchars($dataJSON['direccion'] ?? null),
      ];

      try {
        $result = $beneficiario->update($registro);

        if ($result['status']) {

          echo json_encode([
            "status" => true,
            "message" => $result['message']
          ]);
        }
      } catch (PDOException $e) {

        echo json_encode([
          "status" => false,
          "message" => "Error del servidor al intentar actualizar el beneficiario."
        ]);
      }
      break;


    case 'DELETE':
      if ($id) {
        $result = $beneficiario->delete($id);
        echo json_encode($result);
      } else {
        echo json_encode(['status' => false, 'message' => 'ID de beneficiario no proporcionado para eliminar.']);
      }
      break;


  }


}