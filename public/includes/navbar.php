<?php  require_once 'config.php' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="<?= BASE_URL?>">Beneficiario</a>
        <a class="nav-link" href="<?= BASE_URL?>public/views/contrato/">Contratos</a>
        <a class="nav-link" href="<?= BASE_URL?>/app/views/pagos/list.php">Pagos</a>
      
      </div>
    </div>
  </div>
</nav>