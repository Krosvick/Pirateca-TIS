<!-- arreglar el tema del directorio -->
<?php

use Core\Application;
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
                    <img src="https://image.tmdb.org/t/p/w780<?= $Movie->get_poster_path() ?>" alt="Movie Poster" class="max-w-full min-fit rounded-md shadow-xl h-full" onerror="this.onerror=null; this.src='/images/poster-holder.jpg';">
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
                <div class="flex flex-row items-center justify-between w-full">
                    <?php
                    if (!Application::isGuest()) {
                        if (isset($hasRated) && $hasRated == true) {
                            echo '';
                        }else{
                        echo '<label for="my_modal_7" class="btn mt-5">Rate this movie</label>';
                        }
                    }

                    if (Application::isAdmin()) {
                        echo '<a class="btn btn-error mt-5 hover:scale-110" href="/movie/' . $Movie->get_id() . '/delete">Delete movie</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-full w-full p-8 rating-item">
        <div class="w-full mt-8 px-10 py-8 text-black shadow-md border-white border-4 glass rounded-md h-fit">
            <?php if (!isset($noRatings) || !$noRatings): ?>
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
                $Ratings = $Movie->get_ratings();

                foreach ($Ratings as $rating) :
                    //echo $rating->get_rating();
                    //$starsGiven = $rating->rating;
                    //print_r("movie");

                    $crrt++;
                    $starsGiven = $rating->get_rating(); ?>



                    <div class="max-w-screen-md mx-auto mt-8 text-white">

                        <!-- Single Review Component -->

                        <div class="bg-gray-900 shadow-md rounded-lg p-4 flex items-start w-full">
                            <article class="w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <img class="w-10 h-10 me-4 rounded-full" src="https://img.icons8.com/nolan/64/user-default.png" alt="">
                                        <div class="font-medium text-white">
                                            <a class="ml-1.5 font-bold" href="/profile/<?= $rating->get_user()->get_id() ?>">
                                                <?= $rating->get_user()->get_username() ?>
                                            </a>
                                        </div>
                                    </div>
                                    <?php 
                                    if(!Application::isGuest() && 
                                       !$user->is_following($rating->get_user()->get_id()) && 
                                       Application::$app->user->get_id() !== $rating->get_user()->get_id()): 
                                    ?>
                                        <a class="btn" href="/follow/<?= $rating->get_user()->get_id() ?>">
                                            Follow
                                        </a>
                                    <?php elseif(isset(Application::$app->user) && Application::$app->user->get_id() !== $rating->get_user()->get_id()): 
                                    ?>
                                        <p class="badge badge-primary badge-outline">Followed</p>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-4">
                                    <p class="text-gray-300 dark:text-gray-200 mb-2">
                                        <?= $rating->get_review() ?>
                                    </p>
                                </div>

                                <div class="flex items-center mb-1 space-x-1 rtl:space-x-reverse justify-between w-full">
                                    <div class="flex items-center">
                                        <?php displaystar($starsGiven);?>
                                    </div>
                                    <?= Application::isAdmin() ? '<a class="btn btn-error mt-5 hover:scale-110" href="/movie/' . $Movie->get_id() . '/review/' . $rating->get_id() . '/delete">Nuke review</a>' : '' ?>
                                </div>
                            </article>
                        </div>
                        <div class="w-full bg-gray-900 mt-1 rounded-lg p-3" aria-label="commentary" hx-trigger="click">
                            <div class="w-full flex flex-col items-center gap-3">
                                <form id="commentform-<?=$rating->get_id()?>" hx-post="/comments" class="w-full flex m-0 gap-1" hx-target=".comment-list-<?= $rating->get_id() ?>" hx-swap="beforeend" hx-trigger="submit" hx-on:submit="this.reset()">
                                    <input type="hidden" name="rating_id" value="<?= $rating->get_id() ?>" />
                                    <input type="text" name="comment" placeholder="Leave a comment on this review" class="input input-ghost w-full" required autocomplete="off" />
                                    <button class="btn btn-info text-white place-self-end" type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                        <div class="w-full pl-20 comment-list-<?= $rating->get_id() ?>" hx-get="/comments/<?= $rating->get_id()?>" hx-trigger="load, every 1s" hx-indicator=".loader">
                            <p>&nbsp;</p>
                            <div class="loader justify-self-center">
                                <div role="status">
                                    <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-purple-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

                <div class="join flex justify-center items-center min-w-full mt-5">
                    <?php if ($totalRows > 10 && $offset > 0) : ?>
                        <a href="/movie/<?= $Movie->get_id(); ?>/offset/0" class="join-item btn hover:outline hover:outline-1 bg-gray-900 text-white hover:bg-white hover:text-black hover:outline-black w-1/3">«</a>
                    <?php endif; ?>
                    <?php if ($firstId < $lastResult) : ?>
                        <a href="/movie/<?= $Movie->get_id(); ?>/offset/<?= $lastId ?>" class="join-item btn btn-outline outline-1 outline-black bg-gray-900 text-white hover:bg-white hover:text-black w-1/3">Next page</a>
                        <a href="/movie/<?= $Movie->get_id(); ?>/offset/<?= $lastResult ?>" class="join-item btn outline outline-1 bg-gray-900 text-white hover:bg-white hover:text-black outline-black w-1/3">»</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <p class="text-black font-bold">
                    <?= $message ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
if(isset($hasRated) && $hasRated == true){
    echo '';
}else{
echo '
<input type="checkbox" id="my_modal_7" class="modal-toggle" />
<div class="modal" role="dialog">
    <div class="modal-box w-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <h3 class="font-bold text-lg text-black center">RATE THIS MOVIE</h3>
        <p class="py-4 text-black"></p>
        <div class="rating w-full">
            <form method="post" action="/movie/' . $Movie->get_id().'" class="w-full">
                <input type="radio" name="rating" value="1" class="mask mask-star-2 bg-orange-400" />
                <input type="radio" name="rating" value="2" class="mask mask-star-2 bg-orange-400" />
                <input type="radio" name="rating" value="3" class="mask mask-star-2 bg-orange-400" />
                <input type="radio" name="rating" value="4" class="mask mask-star-2 bg-orange-400" />
                <input type="radio" name="rating" value="5" class="mask mask-star-2 bg-orange-400" checked />
                <p class="py-4 text-black">Review this movie!</p>
                <input type="text" name="review" placeholder="Write your opinion..." class="w-full px-4 py-2 rounded-lg text-gray-500 bg-white border-2 border-gray-300 outline-none textarea textarea-bordered">
                <p>&nbsp</p>
                <button type="submit" class="bg-purple-900 text-white px-4 py-2 rounded-lg ml-2" href="/#">Rate</button>
            </form>
        </div>
    </div>
    <label class="modal-backdrop" for="my_modal_7"></label>
</div>';
}
?>