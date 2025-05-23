<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
</head>
<body>

<h1>Cronograma de Pagos</h1>

<div>
  <table id="tabla-pagos" class="table">
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


<script>
  document.addEventListener('DOMContentLoaded', async () => {

    async function obtenerCronograma() {

      const params = new URLSearchParams();
      params.append('operation', 'creaCronograma');
      params.append('fechaRecibida','2025-10-10');
      params.append('monto', 3000);
      params.append('tasa', 5);
      params.append('numeroCuotas',12);

      const response = await fetch(`../../../app/controllers/PagoController.php?${params}`,{method:'GET'});
      return await response.text();  
    }


    async function renderCronograma() {
      const tabla = document.querySelector('#tabla-pagos tbody');
      tabla.innerHTML = await obtenerCronograma();
    }

    await renderCronograma();

  });
</script>
  
</body>
</html>