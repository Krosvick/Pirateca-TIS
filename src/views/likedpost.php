
<div class="min-size-screen">
    <div class="flex justify-center mt-5 mx-5">
        <h1 class="text-3xl font-bold pb-5 inline">Reviewed Movies</h1>
    </div>

<div class="flex flex-wrap justify-center min-h-screen">
    <?php foreach ($user_movies as $movie): ?>
        <div class="max-w-sm rounded overflow-hidden shadow-lg m-5">
            <a href="/movie/<?= $movie->get_id() ?>">
                <img class="w-full border-4 border-t-purple-600 border-l-purple-600 border-r-purple-800 border-b-purple-800" src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500" . $movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="Movie page" onerror="this.onerror=null; this.src='/images/poster-holder.jpg';">
            </a>
            <div class="px-6 py-4 bg-slate-100 border-slate-200 h-full">
                <div class="font-bold text-xl mb-2 text-black"><?= $movie->get_original_title() ?></div>
                <p class="text-gray-700 text-base">
                    <?= $movie->get_overview() ?>
                </p>
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_release_date() ?></span>
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2"><?= $movie->get_director() ?></span>
                <p>&nbsp</p>
                <hr class="color-black">
                <p>&nbsp</p>
                <div class="flex justify-right">
                <a href="/movie/<?= $movie->get_id() ?>">   
                    <button class="btn btn-primary bg-purple-700 text-white border-2 border-purple-800 hover:border-purple-800 hover:bg-white hover:text-black w-full">Visit Page</button>
                </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php
    if (empty($user_movies)) {
            echo '<div class="flex justify-center mt-5 mx-5">
            <h1 class="text-3xl font-bold pb-5 inline">You have not reviewed any movies yet!</h1>
            </div>';
    }
    ?>
</div>
<div class="w-full flex justify-center">
<?php if (isset($totalPages)): ?>
    <div class="join flex justify-center w-full max-w-md">
        <a href="/profile/<?= $userData->get_id() ?>/likedpost/1" class="join-item btn" <?= $page == 1 ? 'disabled' : '' ?>>«</a>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i >= $page - 3 && $i <= $page + 3): ?>
                <?php if ($i == $page): ?>
                    <a class="join-item btn btn-active"><?= $i ?></a>
                <?php else: ?>
                    <a class="join-item btn" href="/profile/<?= $userData->get_id() ?>/likedpost/<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>
        <a href="/profile/<?= $userData->get_id() ?>/likedpost/<?= $totalPages ?>" class="join-item btn" <?= $page == $totalPages ? 'disabled' : '' ?>>»</a>
    </div>
<?php endif; ?>
</div>