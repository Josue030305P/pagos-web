<?php

require_once '../models/Pago.php';
require_once '../models/helpers.php';
header('Content-Type: application/json; charset=utf-8');


$pagoModel = new Pago();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            try {
                $pago = $pagoModel->getById($id);

                if ($pago['status'] && $pago['data']) {
                    $contratoData = $pago['data'];


                    $monto = $contratoData['monto'];
                    $interes = $contratoData['interes'];
                    $fechainicio = $contratoData['fechainicio'];
                    $diapago = $contratoData['diapago'];
                    $numcuotas = $contratoData['numcuotas'];

                    $tasaPeriodica = $interes / 100;
                    $cuotaCalculada = round(Pago($tasaPeriodica, $numcuotas, $monto), 2);

                    $cronograma = [];
                    $saldoCapital = $monto;


                    $fechaActualCuota = new DateTime($fechainicio);
                    $fechaActualCuota->modify('+1 month');


                    $year = (int) $fechaActualCuota->format('Y');
                    $month = (int) $fechaActualCuota->format('m');
                    $day = min($diapago, (int) $fechaActualCuota->format('t'));
                    $fechaActualCuota->setDate($year, $month, $day);


                    for ($i = 1; $i <= $numcuotas; $i++) {
                        $interesPeriodo = round($saldoCapital * $tasaPeriodica, 2);
                        $abonoCapital = round($cuotaCalculada - $interesPeriodo, 2);
                        $saldoCapitalTemp = round($saldoCapital - $abonoCapital, 2);


                        if ($i == $numcuotas) {
                            $abonoCapital = $saldoCapital;
                            $cuotaCalculada = $interesPeriodo + $abonoCapital;
                            $saldoCapitalTemp = 0.00;
                        }

                        $cronograma[] = [
                            'ITEM' => $i,
                            'FECHA_PAGO' => $fechaActualCuota->format('j/n/Y'),
                            'INTERES_DEL_PERIODO' => $interesPeriodo,
                            'ABONO_A_CAPITAL' => $abonoCapital,
                            'VALOR_CUOTA' => $cuotaCalculada,
                            'SALDO_CAPITAL' => $saldoCapitalTemp
                        ];

                        $saldoCapital = $saldoCapitalTemp;
                        $fechaActualCuota->modify('+1 month');


                        $year = (int) $fechaActualCuota->format('Y');
                        $month = (int) $fechaActualCuota->format('m');
                        $day = min($diapago, (int) $fechaActualCuota->format('t'));
                        $fechaActualCuota->setDate($year, $month, $day);

                    }

                    $response = [
                        "status" => true,
                        "data" => $contratoData,
                        "cronograma" => $cronograma
                    ];
                    echo json_encode($response);

                } else {

                    echo json_encode(["status" => false, "message" => "Contrato no encontrado."]);
                }
            } catch (PDOException $e) {

                echo json_encode(["status" => false, "message" => "Error al obtener contrato por ID: " . $e->getMessage()]);
            } catch (Exception $e) {

                echo json_encode(["status" => false, "message" => "Error al generar cronograma: " . $e->getMessage()]);
            }
        }

        break;


}