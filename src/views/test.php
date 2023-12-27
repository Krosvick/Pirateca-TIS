<head>


<h1>Aqui va una prueba</h1>


<form action="search" method="post">
    <input type="text" name="busqueda"> <br>
    <input type="submit" name="enviar" value="Submit">
</form>


<?php 
if (isset($movies)): ?>
    <?php foreach ($movies as $movie):?>    
        <div class="movie-image">
            <a href="/movie/<?= $movie->get_id(); ?>">    
                 <img src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500".$movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
            </a>
        </div>
<?php endforeach; ?>
<?php endif; ?>

    
