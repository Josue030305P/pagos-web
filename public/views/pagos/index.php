<?php require_once '../../includes/config.php';?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cronograma de Pagos</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Cronograma de Pagos</h1>

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
      </table>
    </div>
    <div class="text-center mt-4">
        <a href="../../public/views/contrato/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a Contratos</a>
    </div>
</div>

<script>
   const BASE_URL = '<?= BASE_URL ?>';
    document.addEventListener('DOMContentLoaded', async () => {
        
        const urlParams = new URLSearchParams(window.location.search);
        const idContrato = urlParams.get('idContrato');

        const tablaBody = document.querySelector('#tabla-pagos tbody');

        if (!idContrato) {
            tablaBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">No se ha especificado un ID de contrato para mostrar el cronograma.</td></tr>';
            return;
        }

        async function obtenerCronogramaDesdeDB(contractId) {
            
            const params = new URLSearchParams();
            params.append('idContrato', contractId); 

            const response = await fetch(`${BASE_URL}app/controllers/PagoController.php?${params}`, {method:'GET'});
            
            if (!response.ok) {
             
                throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
            }
            
            return await response.text(); 
        }

        async function renderCronograma() {
            try {
                
                const cronogramaHtml = await obtenerCronogramaDesdeDB(idContrato);
                tablaBody.innerHTML = cronogramaHtml;
            } catch (error) {
                console.error('Error al renderizar el cronograma:', error);
                tablaBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar el cronograma. Por favor, intenta de nuevo.</td></tr>';
            }
        }

        await renderCronograma();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>