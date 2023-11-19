<?php require 'src\views\partials\nav.php'?>


<h1>Aqui va una prueba</h1>


<form action="test" method="post">
    <input type="text" name="busqueda"> <br>
    <input type="submit" name="enviar" value="Submit">
</form>

   <?php foreach ($movie as $result): ?>    
    <img src="<?= $result["poster_path"] ? "https://image.tmdb.org/t/p/w500".$result['poster_path'] : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
    <?php endforeach; ?>
    

