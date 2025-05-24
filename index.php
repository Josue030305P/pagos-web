<?php

require_once './public/includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Beneficiarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

    <div class="container mt-2">
        <?php include './public/includes/navbar.php' ?>
        <h4 class="text-center mt-5">LISTA DE BENEFICIARIOS</h4>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= BASE_URL ?>public/views/beneficiario/beneficiario.add.php" class="btn btn-primary">Agregar</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Nombres</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="body-tabla">
                </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
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

        const loadBeneficiarios = async () => {
            const tbodyTabla = document.getElementById('body-tabla');
            tbodyTabla.innerHTML = `<tr><td colspan="7" class="text-center">Cargando beneficiarios...</td></tr>`;

            try {
                
                const response = await fetch(`http://localhost/pagos-web/app/controllers/BeneficiarioController.php`);

                if (!response.ok) {
                    const errorText = await response.text();
                    let errorMessage = 'Error desconocido al cargar los datos.';
                    try {
                        const errorData = JSON.parse(errorText);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Error: ${response.status} ${response.statusText}. Respuesta: ${errorText.substring(0, 100)}...`;
                    }

                    tbodyTabla.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error al cargar los datos: ${errorMessage}</td></tr>`;
                    showToast('error', `Error al cargar los datos: ${errorMessage}`);
                    return;
                }

                const result = await response.json();

                if (result.status && result.data && result.data.length > 0) {
                    tbodyTabla.innerHTML = '';
                    result.data.forEach((beneficiario, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <th scope="row">${index + 1}</th>
                            <td>${beneficiario.apellidos}</td>
                            <td>${beneficiario.nombres}</td>
                            <td>${beneficiario.dni}</td>
                            <td>${beneficiario.telefono}</td>
                            <td>${beneficiario.direccion || 'N/A'}</td>
                            <td>
                                <a href="<?= BASE_URL ?>public/views/beneficiario/beneficiario.update.php?id=${beneficiario.idbeneficiario}" class="btn btn-sm btn-info me-2">Editar</a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(${beneficiario.idbeneficiario})">Eliminar</button>
                            </td>
                        `;
                        tbodyTabla.appendChild(row);
                    });
                } else {
                    tbodyTabla.innerHTML = `<tr><td colspan="7" class="text-center">No se encontraron beneficiarios.</td></tr>`;
                    showToast('info', result.message || 'No se encontraron beneficiarios registrados.');
                }

            } catch (error) {
                console.error('Error al obtener datos de beneficiarios:', error);
                tbodyTabla.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error de conexión o datos inválidos.</td></tr>`;
                showToast('error', 'Error de conexión al cargar beneficiarios. Intenta de nuevo más tarde.');
            }
        };

        const confirmDelete = (idbeneficiario) => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteBeneficiario(idbeneficiario);
                }
            });
        };

        const deleteBeneficiario = async (idbeneficiario) => {
            try {
                const response = await fetch(`http://localhost/pagos-web/app/controllers/BeneficiarioController.php?id=${idbeneficiario}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (response.ok && result.status) {
                    showToast('success', result.message || 'Beneficiario eliminado exitosamente.');
                    loadBeneficiarios(); 
                } else {
                    showToast('error', result.message || 'Hubo un error al eliminar el beneficiario.');
                }
            } catch (error) {
                console.error('Error al eliminar beneficiario:', error);
                showToast('error', 'Error de conexión al intentar eliminar. Intenta de nuevo más tarde.');
            }
        };

        document.addEventListener('DOMContentLoaded', loadBeneficiarios);
    </script>
</body>
</html>