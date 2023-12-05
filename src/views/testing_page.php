<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<?php 
//La carpeta 'partials' tiene como unico proposito
//guardar porciones de codigo html y asi
//otorgar un vista mas limpia. Se llaman usando 'require'.
?>

<?php require('partials/nav.php')?>


  <link rel="stylesheet" href="css/style_testing.css">
  <!-- testeo en cosas de css para quitar el bootstrap lo mas posible-->

  <header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
    </div>
  </header>
  <main>
  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="https://cdn.discordapp.com/attachments/324358291561906186/1175922700015906887/image.png?ex=657638db&is=6563c3db&hm=4b8eef5524c4a7f9f506508c87384c1fe7cc3d07ea418e81f0ec7ed4139ff8b9&" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="https://phantom-marca.unidadeditorial.es/f20fe5f4858f02bf9eb4f7537a449972/resize/640/assets/multimedia/imagenes/2020/05/06/15887913123047.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="https://cdn.discordapp.com/attachments/324358291561906186/1175922700015906887/image.png?ex=657638db&is=6563c3db&hm=4b8eef5524c4a7f9f506508c87384c1fe7cc3d07ea418e81f0ec7ed4139ff8b9&" class="d-block w-100" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </main>

  
<?php require('partials/footer.php')?>

</html>