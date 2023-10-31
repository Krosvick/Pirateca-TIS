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
    <div>
      <!-- Your content -->
      <!-- "?=" is the same as "echo $variable" -->
      <div class="flex justify-left mt-5 mx-5">
        <h1 class="text-3xl font-bold pb-5 inline">Recomendado para</h1>
        <h1 class="inline text-3xl pl-5 italic">ti</h1>
      </div>
      <div class="flex flex-row items-center h-auto snap-x snap-mandatory overflow-x-auto">
        <?php foreach ($user_movies as $result): ?>
        <div class="flex justify-center h-screen min-w-full w-full snap-center relative my-5">
          <img src="<?= $result["poster_path"] ? "https://image.tmdb.org/t/p/w500".$result['poster_path'] : '/views/images/scplogo.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
          <a href="/another_test?id=<?= $result['id']?>" class="absolute bottom-0 h-10 w-1/2 bg-slate-900 border-t-2 border-x-2 border-zinc-300 rounded text-white text-center lg:w-1/4">
              <?= $result['original_title'] ?>
          </a>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </main>

  
<?php require('partials/footer.php')?>