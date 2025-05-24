<?php


require_once '../models/Contrato.php';
require_once '../models/Pago.php';
require_once '../models/helpers.php';

header('Content-Type: application/json; charset=utf-8');

$contratoModel = new Contrato();
$pagoModel = new Pago();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {
    case 'GET':

        if ($id) {
            try {
                $result = $contratoModel->getById($id);
                echo json_encode($result);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["status" => false, "message" => "Error al obtener contrato por ID: " . $e->getMessage()]);
            }
        } else {

            try {
                $result = $contratoModel->getAll();
                echo json_encode($result);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["status" => false, "message" => "Error al obtener todos los contratos: " . $e->getMessage()]);
            }
        }
        break;


    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        // 1. Validar datos de entrada
        if (!isset($data['idbeneficiario'], $data['monto'], $data['interes'], $data['fechainicio'], $data['diapago'], $data['numcuotas'])) {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Faltan datos para crear el contrato."]);
            exit;
        }

        // Conversión y validación básica de tipos
        $idbeneficiario = (int) $data['idbeneficiario'];
        $monto = (float) $data['monto'];
        $interes = (float) $data['interes'];
        $fechainicio = $data['fechainicio']; // Formato 'YYYY-MM-DD'
        $diapago = (int) $data['diapago'];
        $numcuotas = (int) $data['numcuotas'];

        if ($monto <= 0 || $interes < 0 || $numcuotas <= 0 || $diapago < 1 || $diapago > 31) {
            http_response_code(400);
            echo json_encode(["status" => false, "message" => "Valores de contrato inválidos."]);
            exit;
        }

        try {
            $contratoModel->getConexion()->beginTransaction();

            // 2. Crear el contrato
            $contratoResult = $contratoModel->create([
                'idbeneficiario' => $idbeneficiario,
                'monto' => $monto,
                'interes' => $interes,
                'fechainicio' => $fechainicio,
                'diapago' => $diapago,
                'numcuotas' => $numcuotas
            ]);

            if (!$contratoResult['status']) {
                $contratoModel->getConexion()->rollBack();
                http_response_code(500);
                echo json_encode($contratoResult);
                exit;
            }

            $idcontrato = $contratoResult['id'];

            // 3. Generar y guardar el cronograma de pagos
            $tasaPeriodica = $interes / 100;
            $cuotaCalculada = round(Pago($tasaPeriodica, $numcuotas, $monto), 2);

            $fechaActualCuota = new DateTime($fechainicio);

            $fechaActualCuota->setDate($fechaActualCuota->format('Y'), $fechaActualCuota->format('m'), $diapago);


            $fechaPrimerPago = new DateTime($fechainicio);
            $fechaPrimerPago->modify('+1 month');
            $fechaPrimerPago->setDate($fechaPrimerPago->format('Y'), $fechaPrimerPago->format('m'), $diapago);
            $fechaActualCuota = $fechaPrimerPago;


            $saldoCapital = $monto;

            for ($i = 1; $i <= $numcuotas; $i++) {
                $interesPeriodo = $saldoCapital * $tasaPeriodica;
                $abonoCapital = $cuotaCalculada - $interesPeriodo;
                $saldoCapitalTemp = $saldoCapital - $abonoCapital;

                // Ajustar el último saldo para evitar imprecisiones de flotante
                if ($i == $numcuotas) {
                    $saldoCapitalTemp = 0.00;
                }

                // Guardar la cuota
                $pagoResult = $pagoModel->saveCuota($idcontrato, $i, $cuotaCalculada);

                if (!$pagoResult['status']) {
                    $contratoModel->getConexion()->rollBack();
                    http_response_code(500);
                    echo json_encode(["status" => false, "message" => "Error al guardar la cuota {$i} del cronograma: " . $pagoResult['message']]);
                    exit;
                }

                $saldoCapital = $saldoCapitalTemp;
                $fechaActualCuota->modify('+1 month');
            }


            $contratoModel->getConexion()->commit();


            echo json_encode(["status" => true, "message" => "Contrato y cronograma creados exitosamente.", "idcontrato" => $idcontrato]);

        } catch (PDOException $e) {
            $contratoModel->getConexion()->rollBack();

            echo json_encode(["status" => false, "message" => "Error interno del servidor al crear contrato y cronograma: " . $e->getMessage()]);
        } catch (Exception $e) { 
            $contratoModel->getConexion()->rollBack();

            echo json_encode(["status" => false, "message" => "Error inesperado: " . $e->getMessage()]);
        }
        break;




}