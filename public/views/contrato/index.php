<?php
// public/views/contrato/contrato.list.php
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

            Toast.fire({
                icon: icon,
                title: title
            });
        };

        const loadContratos = async () => {
            const tbodyTabla = document.getElementById('body-tabla-contratos');
            tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center">Cargando contratos...</td></tr>`;

            try {
            
                const response = await fetch(`${BASE_URL}app/controllers/ContratoController.php`);

                if (!response.ok) {
                    const errorText = await response.text();
                    let errorMessage = 'Error desconocido al cargar los contratos.';
                    try {
                        const errorData = JSON.parse(errorText);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Error: ${response.status} ${response.statusText}. Respuesta: ${errorText}`;
                    }

                    tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error al cargar los contratos: ${errorMessage}</td></tr>`;
                    showToast('error', `Error al cargar los contratos: ${errorMessage}`);
                    return;
                }

                const result = await response.json();

                if (result.status && result.data && result.data.length > 0) {
                    tbodyTabla.innerHTML = ''; 
                    result.data.forEach((contrato, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <th scope="row">${index + 1}</th>
                            <td>${contrato.apellidos} ${contrato.nombres}</td>
                            <td>${parseFloat(contrato.monto).toFixed(2)}</td>
                            <td>${parseFloat(contrato.interes).toFixed(2)}%</td>
                            <td>${contrato.fechainicio}</td>
                            <td>${contrato.diapago}</td>
                            <td>${contrato.numcuotas}</td>
                            <td>${contrato.estado === 'ACT' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Finalizado</span>'}</td>
                            <td>
                                <a href="${BASE_URL}public/views/contrato/contrato.edit.php?id=${contrato.idcontrato}" class="btn btn-sm btn-info me-2" title="Editar Contrato">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn me-2" data-id="${contrato.idcontrato}" title="Eliminar Contrato">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a href="${BASE_URL}public/views/pagos/index?idContrato=${contrato.idcontrato}" class="btn btn-sm btn-primary" title="Ver Cronograma">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                            </td>
                        `;
                        tbodyTabla.appendChild(row);
                    });
                   
                    attachDeleteEventListeners(); 
                } else {
                    tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center">No se encontraron contratos.</td></tr>`;
                    showToast('info', result.message || 'No se encontraron contratos registrados.');
                }

            } catch (error) {
                console.error('Error al obtener datos de contratos:', error);
                tbodyTabla.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error de conexión o datos inválidos.</td></tr>`;
                showToast('error', 'Error de conexión al cargar contratos. Intenta de nuevo más tarde.');
            }
        };

        const attachDeleteEventListeners = () => {
            document.querySelectorAll('.delete-btn').forEach(link => {
                link.removeEventListener('click', handleDeleteClick);
                link.addEventListener('click', handleDeleteClick);
            });
        };

        const handleDeleteClick = (event) => {
            event.preventDefault();
            const idcontrato = event.target.closest('.delete-btn').dataset.id;
            confirmDelete(idcontrato);
        };

        const confirmDelete = (idcontrato) => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esto eliminará el contrato y todos sus pagos asociados!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteContrato(idcontrato);
                }
            });
        };

        const deleteContrato = async (idcontrato) => {
            try {
                const response = await fetch(`${BASE_URL}app/controllers/ContratoController.php?id=${idcontrato}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (response.ok && result.status) {
                    showToast('success', result.message || 'Contrato eliminado exitosamente.');
                    loadContratos();
                } else {
                    showToast('error', result.message || 'Hubo un error al eliminar el contrato.');
                }
            } catch (error) {
                console.error('Error al eliminar contrato:', error);
                showToast('error', 'Error de conexión al intentar eliminar el contrato. Intenta de nuevo más tarde.');
            }
        };

        document.addEventListener('DOMContentLoaded', loadContratos);
    </script>
</body>
</html>