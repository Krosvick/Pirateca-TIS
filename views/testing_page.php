<?php 
//La carpeta 'partials' tiene como unico proposito
//guardar porciones de codigo html y asi
//otorgar un vista mas limpia. Se llaman usando 'require'.
?>

<?php require('partial_testing/head.php')?>
<?php require('partial_testing/nav.php')?>

  <header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 h-auto">
      <!-- Your content -->
      <!-- "?=" is the same as "echo $variable" -->
      <div class="flex justify-center">
        <h1 class="text-5xl font-bold pb-5 inline">Get some</h1>
        <h1 class="inline text-5xl pl-5 ">Movies</h1>
      </div>
      <div class="flex flex-col justify-center items-center h-screen w-full overflow-scroll gap-5 mt-5 snap-y snap-mandatory">
        <?php foreach ($user_movies as $result): ?>
        <div class="h-screen w-1/4 snap-center relative place-items-center">
          <img src="https://th.bing.com/th/id/OIP.Ntc0avgIkLYQdaqiq6OTzwHaK9?pid=ImgDet&rs=1" alt="poster" class="h-full w-full rounded">
          <a href="/another_test?id=<?= $result['id']?>" class="absolute bottom-1 h-1/4 w-full bg-gray-700 opacity-80">
              <?= $result['original_title'] ?>
          </a>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </main>

  
<?php require('partials/footer.php')?>