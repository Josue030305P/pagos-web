<?php

require_once '../models/helpers.php';

if (isset($_GET['operation'])) {

  switch ($_GET['operation']) {
    case 'creaCronograma':
      $fechaRecibida = $_GET['fechaRecibida'];
      $fechaInicio = new DateTime($fechaRecibida);
      $monto = floatval($_GET['monto']);
      $tasa = floatval($_GET['tasa']) / 100;
      $numeroCuotas = floatval($_GET['numeroCuotas']);

      $cuota = round(Pago($tasa, $numeroCuotas, $monto), 2);
    
      //FILA 0:
      echo "
      <tr>
        <td>0</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
     
          
       </tr>";

       $saldoCapital = $monto;
       $interesPeriodo = 0;
       $abonoCapital = 0;
       $sumatoriaIntereses = 0;

      for ($i = 1; $i <= $numeroCuotas; $i++) {

        $interesPeriodo  = $saldoCapital * $tasa;
        $abonoCapital = $cuota - $interesPeriodo;
        $saldoCapitalTemp = $saldoCapital - $abonoCapital;

        $sumatoriaIntereses += $interesPeriodo;
        // Variable a renderizar 
        $interesPeriodoPrint = number_format($interesPeriodo,2,'.',',');
        $abonoCapitalPrint = number_format($abonoCapital,2,'.',',');
        $cuotaPrint = number_format($cuota,2,'.',','); 
        $saldoCapitalPrint = number_format($saldoCapitalTemp,2,'.',',');
        
        if ($i == $numeroCuotas) {
          $saldoCapitalPrint = 0.00;
        }

        echo "
        <tr>
          <td>{$i}</td>
          <td>{$fechaInicio->format('d-m-Y')}</td>
          <td>{$interesPeriodoPrint}</td>
          <td>{$abonoCapitalPrint}</td>
          <td>{$cuotaPrint}</td>
          <td>{$saldoCapitalPrint}</td>
       
            
       </tr>";

       $fechaInicio->modify('+1 month');
       $saldoCapital = $saldoCapitalTemp;

      };

      $sumatoriaInteresPrint = number_format($sumatoriaIntereses,2,'.',',');


      

      // FILA RESUMEN
      echo "
      <tr>
        <td></td>
        <td></td>
        <td>{$sumatoriaInteresPrint}</td>
        <td></td>
        <td></td>
        <td></td>
    
       </tr>";

      break;
  }
}

?>