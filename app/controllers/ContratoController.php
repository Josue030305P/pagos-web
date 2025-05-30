<?php
require_once '../models/Contrato.php';
require_once '../models/Pago.php';
require_once '../models/helpers.php';
header('Content-Type: application/json; charset=utf-8');

$contratoModel = new Contrato();
$pagoModel = new Pago();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $result = $contratoModel->getAll();
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["status" => false, "message" => "Error al obtener todos los contratos: " . $e->getMessage()]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $idbeneficiario = (int) $data['idbeneficiario'];
        $monto = (float) $data['monto'];
        $interes = (float) $data['interes'];
        $fechainicio = $data['fechainicio'];
        $diapago = (int) $data['diapago'];
        $numcuotas = (int) $data['numcuotas'];

        if ($monto <= 0 || $interes < 0 || $numcuotas <= 0 || $diapago < 1 || $diapago > 31) {
            echo json_encode(["status" => false, "message" => "Valores de contrato inválidos."]);
            exit;
        }

        try {
            $contratoModel->getConexion()->beginTransaction();

            $contratoResult = $contratoModel->add([
                'idbeneficiario' => $idbeneficiario,
                'monto' => $monto,
                'interes' => $interes,
                'fechainicio' => $fechainicio,
                'diapago' => $diapago,
                'numcuotas' => $numcuotas
            ]);

            if (!$contratoResult['status']) {
                $contratoModel->getConexion()->rollBack();
                echo json_encode($contratoResult);
                exit;
            }

            $idcontrato = $contratoResult['id'];
            $tasaPeriodica = $interes / 100;
            $valorCuotaFija = round(Pago($tasaPeriodica, $numcuotas, $monto), 2);

            $fechaInicio = new DateTime($fechainicio);

            for ($i = 1; $i <= $numcuotas; $i++) {
               
                $fechaCuota = clone $fechaInicio;
                $fechaCuota->modify("+" . ($i - 1) . " month");

                
                $ultimoDiaMes = (int) $fechaCuota->format('t');
                $diaPagoReal = min($diapago, $ultimoDiaMes);
                $fechaCuota->setDate(
                    (int) $fechaCuota->format('Y'),
                    (int) $fechaCuota->format('m'),
                    $diaPagoReal
                );

                $fechaPagoStr = $fechaCuota->format('Y-m-d');

                $pagoResult = $pagoModel->saveCuota($idcontrato, $i, $fechaPagoStr, $valorCuotaFija);

                if (!$pagoResult['status']) {
                    $contratoModel->getConexion()->rollBack();
                    echo json_encode([
                        "status" => false,
                        "message" => "Error al guardar la cuota {$i} en la tabla de pagos: " . $pagoResult['message']
                    ]);
                    exit;
                }
            }

            $contratoModel->getConexion()->commit();
            echo json_encode(["status" => true, "message" => "Contrato y cuotas iniciales creados exitosamente.", "idcontrato" => $idcontrato]);

        } catch (PDOException $e) {
            $contratoModel->getConexion()->rollBack();
            echo json_encode(["status" => false, "message" => "Error interno del servidor al crear contrato: " . $e->getMessage()]);
        } catch (Exception $e) {
            $contratoModel->getConexion()->rollBack();
            echo json_encode(["status" => false, "message" => "Error inesperado: " . $e->getMessage()]);
        }
        break;
}