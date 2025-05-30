document.addEventListener('DOMContentLoaded', async () => {
    const beneficiarioForm = document.getElementById('beneficiarioForm');
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

    beneficiarioForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        beneficiarioForm.classList.add('was-validated');

        if (!beneficiarioForm.checkValidity()) {
            
            showToast('warning', 'Por favor, completa todos los campos requeridos correctamente.');
            return;
        }

        const formData = new FormData(beneficiarioForm);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('http://localhost/pagos-web/app/controllers/BeneficiarioController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status) {
                showToast('success', result.message || 'Beneficiario agregado exitosamente.');
                beneficiarioForm.reset();
                beneficiarioForm.classList.remove('was-validated');
            } else {
                showToast('error', result.message || 'Hubo un error al agregar el beneficiario.');
            }
        } catch (error) {
            showToast('error', 'Error de conexión. Intenta de nuevo más tarde.');
            console.error('Fetch error:', error);
        }
    });
});