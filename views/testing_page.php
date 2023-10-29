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
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
      <!-- Your content -->
      <!-- "?=" is the same as "echo $variable" -->
      <h1>Get some function</h1>
      <?php foreach ($result as $result): ?>
        
      <li>
      <a href="#" class = "text-blue-500 hover:underline">
        <?= $result['original_title'] ?>
      
      </li>
      <?php endforeach; ?>
      </a>
      <h1>Find function</h1>
      <?= $result2['original_title'] ?>
        
    </div>
  </main>

  
<?php require('partial_testing/footer.php')?>