<?php require_once '../../includes/config.php' ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Beneficiario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-2">
        <?php include '../../includes/navbar.php' ?>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-7">
                <div class="card p-4 shadow-lg border-0">
                    <h4 class="card-title text-center mb-4 text-primary fw-bold">REGISTRAR NUEVO BENEFICIARIO</h4>
                    <form id="beneficiarioForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    <div class="invalid-feedback">
                                        Los apellidos son obligatorios.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    <div class="invalid-feedback">
                                        Los nombres son obligatorios.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni" maxlength="8"
                                        pattern="[0-9]{8}" required>
                                    <div class="invalid-feedback">
                                        El DNI debe contener 8 dígitos numéricos.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="9"
                                        pattern="[0-9]{9}" required>
                                    <div class="invalid-feedback">
                                        El teléfono debe contener 9 dígitos numéricos.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4"> <label for="direccion" class="form-label">Dirección (Opcional)</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary btn px-4">Guardar Beneficiario</button>
                            <a href="<?=BASE_URL?>" class="btn btn-secondary btn px-4">Cancelar y Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="alertContainer"></div>


    <script src="<?= BASE_URL ?>public/js/beneficiario.add.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>