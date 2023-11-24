<?php require ('nav.php')?>


<h1>Aqui va una prueba</h1>

<?php
//on form submit redirext to /test/create
    $busqueda = "";
    $movies = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
        #redirect to /test/{search}
        $busqueda = $_POST['busqueda'];
        header('Location: /test/'.$busqueda);
    }
?>


<form action="test" method="post">
    <input type="text" name="busqueda"> <br>
    <input type="submit" name="enviar" value="Submit">
</form>

   <?php foreach ($movies as $result): ?>    
    <img src="<?= $result["poster_path"] ? "https://image.tmdb.org/t/p/w500".$result['poster_path'] : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
    <?php endforeach; ?>
    

