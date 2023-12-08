<!DOCTYPE html>
<head>

<link rel="stylesheet" href="/css/testing.css"/>
</head>


<?php 
//La carpeta 'partials' tiene como unico proposito
//guardar porciones de codigo html y asi
//otorgar un vista mas limpia. Se llaman usando 'require'.
?>

<?php require('partials/nav.php')?>

  <!-- testeo en cosas de css para quitar el bootstrap lo mas posible-->

  <header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
    </div>
  </header>
  
  <body>
    <section class="container">
      <div clas="slider-wrapper">
        <div class="slider">
          <img id="slide1" src="" />
          <img id="slide2" src="" />
          <img id="slide3" src="" />
        </div>
        <div class="slider-nav">
          <a href="#slide1">
          <a href="#slide2">
          <a href="#slide3">
        </div>
      </div>
    </section>
</body>

<?php require('partials/footer.php')?>



</html>