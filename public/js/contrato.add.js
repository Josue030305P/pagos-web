document.addEventListener('DOMContentLoaded', async () => {
    
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
                const response = await fetch(`http://localhost/pagos-web/app/controllers/BeneficiarioController.php?dni=${dni}`);
                const result = await response.json();

                if (result.status) {
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
            const data = Object.fromEntries(formData.entries());

       
            if (isNaN(data.monto) || data.monto <= 0 ||
                isNaN(data.interes) || data.interes < 0 ||
                isNaN(data.numcuotas) || data.numcuotas <= 0 ||
                isNaN(data.diapago) || data.diapago < 1 || data.diapago > 31 ||
                !data.fechainicio) {
                showToast('error', 'Por favor, complete todos los campos con valores válidos.');
                return;
            }

            try {
                const response = await fetch(`http://localhost/pagos-web/app/controllers/ContratoController.php`, {
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
});
