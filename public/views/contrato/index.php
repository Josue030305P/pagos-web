<?php

require_once '../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contratos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="container mt-2">
        <?php include '../../../public/includes/navbar.php' ?> <h4 class="text-center mt-5">LISTA DE CONTRATOS</h4>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= BASE_URL ?>public/views/contrato/contrato.add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Contrato
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Beneficiario</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Interés (%)</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Día Pago</th>
                    <th scope="col">Cuotas</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="body-tabla-contratos">
                </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/contrato.index.js"></script>
   
</body>
</html>