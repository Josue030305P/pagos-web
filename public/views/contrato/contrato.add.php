<?php

require_once '../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Contrato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="container mt-2">
        <?php include '../../../public/includes/navbar.php' ?>
        <h4 class="text-center mt-5">CREAR NUEVO CONTRATO</h4>

        <div class="card p-4 mt-4">
            <form id="formContrato">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dniBeneficiario" class="form-label">DNI Beneficiario:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dniBeneficiario" maxlength="8" required>
                            <button class="btn btn-outline-secondary" type="button" id="buscarBeneficiarioBtn">Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="nombreBeneficiario" class="form-label">Beneficiario:</label>
                        <input type="text" class="form-control" id="nombreBeneficiario" readonly>
                        <input type="hidden" id="idBeneficiario" name="idbeneficiario">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="monto" class="form-label">Monto del Préstamo:</label>
                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                    </div>
                    <div class="col-md-4">
                        <label for="interes" class="form-label">Interés (%):</label>
                        <input type="number" step="0.01" class="form-control" id="interes" name="interes" required>
                    </div>
                    <div class="col-md-4">
                        <label for="numCuotas" class="form-label">Número de Cuotas (meses):</label>
                        <input type="number" class="form-control" id="numCuotas" name="numcuotas" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechainicio" required>
                    </div>
                    <div class="col-md-6">
                        <label for="diaPago" class="form-label">Día de Pago (1-31):</label>
                        <input type="number" class="form-control" id="diaPago" name="diapago" min="1" max="31" required>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Crear Contrato</button>
                    <a href="<?= BASE_URL ?>public/views/contrato/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <script src="<?= BASE_URL ?>public/js/contrato.add.js"></script>
  
</body>
</html>