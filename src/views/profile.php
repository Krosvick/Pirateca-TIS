<?php

use Core\Application;
?>

<div class="bg-gray-200 h-screen max-w-screen flex items-center justify-center movie-container ">
    <div class="form-container glass rounded-lg shadow-lg p-8 ">


        <div class="max-w-4xl mx-auto my-8 px-10 py-8 text-black shadow-md border-white border-4 rounded-md bg-white flex items-center flex-col">
            <?php if(Application::$app->user->is_following($userProfileData->get_id())): ?>
                <p class="badge badge-primary badge-outline font-bold">Following</p>
            <?php endif; ?>
            <?php if($userProfileData->is_following(Application::$app->user->get_id())): ?>
                <p class="badge badge-success gap-2 text-white">Follows you</p>
            <?php endif; ?>
            <h1 class="text-2xl font-bold text-blue-600 mb-2">
              PROFILE:
              <?= $userProfileData->get_username(); ?> 
            </h1>
             <span><i class="fa fa-calendar"><?= formatDate($userProfileData->get_created_at());  // Outputs: Joined December 202
            ?></i> 
           
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <p class="text-gray-700 font-bold mb-2">Username</p>
                    <p class="border border-gray-300 rounded-md py-2 px-3">
                        <?= $userProfileData->get_username(); ?>
                    </p>
                </div>
                <br></br>
                <div class="mb-4">
                    <p class="text-gray-700 font-bold mb-2">Followers</p>
                    <p class="border border-gray-300 rounded-md py-2 px-3">
                        <?= $userProfileData->get_follower_count() ?>
                    </p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 font-bold mb-2">Following</p>
                    <p class="border border-gray-300 rounded-md py-2 px-3">
                        <?= $userProfileData->get_following_count() ?>
                    </p>
                </div>
                <div class="col-span-2 mb-4">
                    <a href="/profile/<?= $userProfileData->get_id(); ?>/likedpost">
                    <button class="bg-gray-500 text-white hover:bg-white hover:text-gray-500 border border-gray-300 rounded-md py-2 px-3 w-full">Reviewed Movies</button>
                    </p>
                    </a>
                    <a href="/follow/<?= $userProfileData->get_id(); ?>">
                </div>
            </div>
        </div>
    </div>
</div>
