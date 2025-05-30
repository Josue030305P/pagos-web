<?php require_once '../../includes/config.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Cronograma de Pagos</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <link rel="stylesheet" href="<?= BASE_URL ?>public/css/pagos.index.css"">
</head>

<body>

 <div class="container mt-5">
  <h1 class="text-center mb-4">Cronograma de Pagos</h1>

  <div id="contract-details-container">
   <p id="loading-details" class="text-center text-info">Cargando detalles del contrato...</p>
  </div>

  <div>
   <table id="tabla-pagos" class="table table-bordered table-striped">
    <thead>
     <tr>
      <th>Item</th>
      <th>Fecha Pago</th>
      <th>Interés</th>
      <th>Abono Capital</th>
      <th>Valor Cuota</th>
      <th>Saldo Capital</th>
     </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot id="table-footer">
    </tfoot>
   </table>
   <p id="loading-cronograma-message" class="text-center text-info">Cargando cronograma...</p>
   <p id="error-message" class="text-center text-danger" style="display: none;">Error al cargar el cronograma.</p>
  </div>
  <div class="text-center mt-4">
   <a href="../../views/contrato/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a
    Contratos</a>
  </div>
 </div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
 
    <script src="<?= BASE_URL ?>public/js/pagos.index.js"></script>
</body>

</html>