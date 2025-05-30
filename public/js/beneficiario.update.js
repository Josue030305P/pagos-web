document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idbeneficiario = urlParams.get('id');

    const beneficiarioForm = document.getElementById('beneficiarioForm');
    const idbeneficiarioInput = document.getElementById('idbeneficiario');
    const apellidosInput = document.getElementById('apellidos');
    const nombresInput = document.getElementById('nombres');
    const dniInput = document.getElementById('dni');
    const telefonoInput = document.getElementById('telefono');
    const direccionInput = document.getElementById('direccion');


    function showToast(message, icon = 'error') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }

    if (idbeneficiario) {
        try {
            const response = await fetch(`http://localhost/pagos-web/app/controllers/BeneficiarioController.php?id=${idbeneficiario}`,{method:'GET'});
            const result = await response.json();

            if (response.ok && result.status) {
                const beneficiario = result.data;

                idbeneficiarioInput.value = beneficiario.idbeneficiario;
                apellidosInput.value = beneficiario.apellidos;
                nombresInput.value = beneficiario.nombres;
                dniInput.value = beneficiario.dni;
                telefonoInput.value = beneficiario.telefono;
                direccionInput.value = beneficiario.direccion || '';

            } else {
                showToast(result.message || 'Error al cargar los datos del beneficiario.', 'error');
                
            }
        } catch (error) {
            
            showToast('Error de conexión al cargar datos del beneficiario. Intenta de nuevo más tarde.', 'error');
            
        }
    } else {
        showToast('ID de beneficiario no proporcionado para edición.', 'warning');
       
    }

    beneficiarioForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        beneficiarioForm.classList.add('was-validated');

        if (!beneficiarioForm.checkValidity()) {
            
            showToast('Por favor, completa todos los campos obligatorios.', 'error');
            return;
        }

        const formData = new FormData(beneficiarioForm);
        const data = Object.fromEntries(formData.entries());
        data.idbeneficiario = idbeneficiarioInput.value;

        try {
            const response = await fetch(`http://localhost/pagos-web/app/controllers/BeneficiarioController.php?id=${idbeneficiarioInput.value}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok && result.status) {
                showToast(result.message, 'success');
                setTimeout(() => window.location.href = 'http://localhost/pagos-web/', 2000);
            } else {
                showToast(result.message || 'Hubo un error al actualizar el beneficiario.', 'error');
            }
        } catch (error) {
            console.error('Error al enviar la actualización:', error);
            showToast('Error de conexión al intentar actualizar. Intenta de nuevo más tarde.', 'error');
        }
    });
});