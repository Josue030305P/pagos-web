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
                    <a href="<?= BASE_URL ?>public/views/contrato/contrato.list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';

        const showToast = (icon, title) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({ icon: icon, title: title });
        };

        
        document.getElementById('buscarBeneficiarioBtn').addEventListener('click', async () => {
            const dni = document.getElementById('dniBeneficiario').value.trim();
            if (dni.length === 0) {
                showToast('warning', 'Ingrese un DNI para buscar.');
                return;
            }

            try {
                const response = await fetch(`${BASE_URL}app/controllers/BeneficiarioController.php?dni=${dni}`);
                const result = await response.json();

                if (result.status && result.data) {
                    document.getElementById('nombreBeneficiario').value = `${result.data.nombres} ${result.data.apellidos}`;
                    document.getElementById('idBeneficiario').value = result.data.idbeneficiario;
                    showToast('success', 'Beneficiario encontrado.');
                } else {
                    document.getElementById('nombreBeneficiario').value = '';
                    document.getElementById('idBeneficiario').value = '';
                    showToast('error', result.message || 'Beneficiario no encontrado.');
                }
            } catch (error) {
                console.error('Error buscando beneficiario:', error);
                showToast('error', 'Error de conexión al buscar beneficiario.');
            }
        });

       
        document.getElementById('formContrato').addEventListener('submit', async (event) => {
            event.preventDefault();

            const idBeneficiario = document.getElementById('idBeneficiario').value;
            if (!idBeneficiario) {
                showToast('error', 'Debe buscar y seleccionar un beneficiario.');
                return;
            }

            const formData = new FormData(event.target);
            const data = {
                idbeneficiario: parseInt(idBeneficiario),
                monto: parseFloat(formData.get('monto')),
                interes: parseFloat(formData.get('interes')),
                fechainicio: formData.get('fechainicio'),
                diapago: parseInt(formData.get('diapago')),
                numcuotas: parseInt(formData.get('numcuotas'))
            };

       
            if (isNaN(data.monto) || data.monto <= 0 ||
                isNaN(data.interes) || data.interes < 0 ||
                isNaN(data.numcuotas) || data.numcuotas <= 0 ||
                isNaN(data.diapago) || data.diapago < 1 || data.diapago > 31 ||
                !data.fechainicio) {
                showToast('error', 'Por favor, complete todos los campos con valores válidos.');
                return;
            }

            try {
                const response = await fetch(`${BASE_URL}app/controllers/ContratoController.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.status) {
                    showToast('success', result.message);
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}public/views/contrato/`; 
                    }, 2000);
                } else {
                    showToast('error', result.message || 'Error desconocido al crear el contrato.');
                }
            } catch (error) {
                console.error('Error creando contrato:', error);
                showToast('error', 'Error de conexión al intentar crear el contrato. Intenta de nuevo más tarde.');
            }
        });
    </script>
</body>
</html>