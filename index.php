
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Beneficiarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>

  <div class="container mt-2">
    <?php include './public/includes/navbar.php' ?>
    <h4 class="text-center mt-5">LISTA DE BENEFICIARIOS</h4>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Apellidos</th>
          <th scope="col">Nombres</th>
          <th scope="col">DNI</th>
          <th scope="col">Telefono</th>
          <th scope="col">Dirección</th>

        </tr>
      </thead>
      <tbody id="body-tabla">

        
      </tbody>
    </table>
  </div>





  <script>

    document.addEventListener('DOMContentLoaded', async () => {
      const response  = await fetch(`http://localhost/pago-web/app/controllers/BeneficiarioController.php`);
      return await response.text();

      
    })
  </script>
</body>

</html>