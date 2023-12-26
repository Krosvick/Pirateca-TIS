   <!-- header -->
   <?php //require('partials/nav.php') ?>
   <!-- end header -->

<div>
    <div class="flex justify-center mt-5 mx-5">
        <h1 class="text-4xl font-bold pb-5 inline">Reviewed Movies</h1>
    </div>

<div class="flex flex-wrap justify-center content-around">
    <?php foreach ($user_movies as $movie): ?>

        <div class="card w-1/3 h-2/3 bg-stale-100 shadow-xl">
            <figure><img class="w-full" src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500" . $movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="Movie poster"></figure>
            <div class="card-body bg-slate-200">
                <h2 class="card-title text-black"><?= $movie->get_original_title() ?></h2>
                <p class="text-neutral-800"><?= $movie->get_overview() ?></p>
                <div class="card-actions justify-end">
                <div class="px-6 pt-4 pb-2">
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_release_date() ?></span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_director() ?></span>
                </div>
                <a href="../movie/<?= $movie->get_id() ?>"><button class="btn btn-primary bg-purple-700 text-white border-2 border-purple-800 hover:border-purple-800 hover:bg-white hover:text-black">Vist Page</button></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>