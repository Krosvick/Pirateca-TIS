<?php require('partials/nav.php') ?>


<?php foreach ($optionals["movies"] as $movie) : ?>

    <h1>
        <?= $movie->id ?>
    </h1>
    <img src="<?= $movie->poster_path ? "https://image.tmdb.org/t/p/w500" . $movie->poster_path : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">

<?php endforeach; ?>


<!--  footer -->
<?php require('partials/footer.php') ?>
<!--  footer -->