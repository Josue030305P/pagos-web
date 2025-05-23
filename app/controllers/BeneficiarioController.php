<?php
require_once '../models/Beneficiario.php';

if (isset($_SERVER['REQUEST_METHOD'])) {
    header('Content-Type: application/json; charset=utf-8');

    $beneficiario = new Beneficiario();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

          try {
            $result = $beneficiario->getAll();

            echo json_encode( [
              "status" => true,
              "data" => $result['data']
            ]);

          }catch(PDOException $e) {
            throw new PDOException($e->getMessage());
          }

           
            break;


          }
        
        
        }