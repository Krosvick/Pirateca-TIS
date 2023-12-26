<!-- arreglar el tema del directorio -->
<?php

use Models\Movie;

?>
<?php
#dd($Ratings[0]->get_user()->get_username());
?>

<section class="flex flex-col items-center justify-center min-w-screen min-h-screen mx-auto">
    <!-- movie descripction-->
    <div class="p-8">
        <div class="bg-gradient-to-r from-purple-900 via-purple-700 to-purple-900 max-w-4xl mx-auto my-8 px-10 py-8 shadow-lg rounded-lg flex flex-col gap-5 items-center lg:flex-row">
            <div class="lg:w-1/4 h-full">
                <div class="poster h-full">
                    <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
                    <img src="https://image.tmdb.org/t/p/w780<?= $Movie->get_poster_path() ?>" alt="Movie Poster" class="max-w-full min-fit rounded-md shadow-xl h-full">
                </div>
            </div>
            <div class="shadow-md bg-gray-900 rounded-lg p-10 my-4 flex flex-col items-start lg:w-3/4">
                <h1 class="text-4xl font-bold mb-4">
                    <?= $Movie->get_original_title() ?>
                </h1>
                <p class="text-md mb-2">
                    <?= $Movie->get_overview() ?>
                </p>
                <p class="text-sm">Release:
                    <?php
                    $date = $Movie->get_release_date();
                    $year = substr($date, 0, 4);
                    echo $year;
                    ?>
                </p>
                <p class="text-sm font-bold">Director:
                    <?=
                    $Movie->get_director();
                    ?>
                </p>

                <!-- The button to open modal -->
                <label for="my_modal_7" class="btn mt-5">Rate this movie</label>

                <!-- Put this part before </body> tag -->



            </div>
        </div>
    </div>
    <div class="max-w-full w-full p-8">
        <div class="w-full mt-8 px-10 py-8 text-black shadow-md border-white border-4 glass rounded-md h-fit">

            <button class="btn btn-active">
                Reviews
                <span class="badge badge-secondary"><?= isset($noRatings) ? '0' : $totalRows ?></span>
            </button>

            <?php
            //funcion para mostrar cuantas estrellas le dio el suaer a la pelicula

            function displaystar($numStars)
            {
                $maxStars = 5;

                for ($i = 1; $i <= $maxStars; $i++) {
                    if ($i <= $numStars) {

                        echo '<svg class="w-4 h-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                                </svg>';
                    } else {

                        echo '<svg class="w-4 h-4 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                                </svg>';
                    }
                }
            } ?>
            <?php $crrt = 1; ?>

            <?php
            if (!isset($noRatings) || !$noRatings) {
                $Ratings = $Movie->get_ratings();

                foreach ($Ratings as $rating) :
                    //echo $rating->get_rating();
                    //$starsGiven = $rating->rating;
                    //print_r("movie");

                    $crrt++;
                    $starsGiven = $rating->get_rating(); ?>



                    <div class="max-w-screen-md mx-auto mt-8">

                        <!-- Single Review Component -->

                        <div class="bg-gray-900 shadow-md rounded-lg p-4 mb-4 flex items-start">
                            <article>
                                <div class="flex items-center mb-4">
                                    <img class="w-10 h-10 me-4 rounded-full" src="https://cdn.discordapp.com/attachments/324358291561906186/1172908205068800160/image.png?ex=656206e3&is=654f91e3&hm=ca8e71b36e8f7c2afb64674c51780e94bca641beb6adb0a7ede617da1e3a5d1c&" alt="">
                                    <div class="font-medium text-white">
                                        <p class="ml-1.5 font-bold">
                                            <?= $rating->get_user()->get_username() ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse">
                                    <?php displaystar($starsGiven); ?>
                                </div>

                                <p class="mb-2 text-gray-500 dark:text-gray-400"></p>


                            </article>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="join grid grid-cols-3">
                    <a href="/movie/<?= $Movie->get_id(); ?>/offset/0" class="join-item btn hover:outline hover:outline-1 bg-gray-900 text-white hover:bg-white hover:text-black hover:outline-black">«</a>
                    <?php if ($firstId < $lastResult) : ?>
                        <a href="/movie/<?= $Movie->get_id(); ?>/offset/<?= $lastId ?>" class="join-item btn btn-outline outline-1 outline-black bg-gray-900 text-white hover:bg-white hover:text-black">Next page</a>
                        <a href="/movie/<?= $Movie->get_id(); ?>/offset/<?= $lastResult ?>" class="join-item btn outline outline-1 bg-gray-900 text-white hover:bg-white hover:text-black outline-black">»</a>
                    <?php endif; ?>
                </div>
            <?php } else {
                echo "<p class='text-2xl text-center'>No ratings found for this movie.</p>";
            } ?>
        </div>
    </div>
</section>
<?php require('partials/footer.php') ?>

<input type="checkbox" id="my_modal_7" class="modal-toggle" />
<div class="modal" role="dialog">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-black center">RATE THIS MOVIE</h3>
        <p class="py-4 text-black"></p>
        <div class="rating">
        <form method="post" action="">
            <input type="radio" name="rating" value="1" class="mask mask-star-2 bg-orange-400" />
            <input type="radio" name="rating" value="2" class="mask mask-star-2 bg-orange-400" />
            <input type="radio" name="rating" value="3" class="mask mask-star-2 bg-orange-400" />
            <input type="radio" name="rating" value="4" class="mask mask-star-2 bg-orange-400" />
            <input type="radio" name="rating" value="5" class="mask mask-star-2 bg-orange-400" checked />
            <p class="py-4 text-black">Review this movie!</p>
            <input type="text" name="review" placeholder="Write your opinion..." class="w-full px-4 py-2 rounded-lg text-gray-500 bg-white border-2 border-gray-300 outline-none">
            <p>&nbsp</p>
            <button type="submit" class="bg-purple-900 text-white px-4 py-2 rounded-lg ml-2" href="/#">Rate</button>
        </form>
        </div>
        <label class="modal-backdrop" for="my_modal_7">Close</label>
    </div>
</div>