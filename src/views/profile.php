<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/styles_login.css">
</head>


<body>
    <div class="bg-gray-200 h-screen flex items-center justify-center">
        <div class="form-container bg-white rounded-lg shadow-lg p-8">
            
            <!-- gpteado cambiar despues -->
            
            <div class="profile-container text-center mb-6">
                <h1 class="text-2xl font-bold text-blue-600 mb-2">
                    <u><?php ['echo $user']['username']; ?></u>
                </h1>
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <p class="text-gray-700 font-bold mb-2">Name</p>
                        <p class="border border-gray-300 rounded-md py-2 px-3">
                            <?php ['echo $user']['username']; ?>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700 font-bold mb-2">Email</p>
                        <p class="border border-gray-300 rounded-md py-2 px-3">
                            <?php ['user']['email']; ?>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700 font-bold mb-2">Followers</p>
                        <p class="border border-gray-300 rounded-md py-2 px-3">
                            <?php ['user']['user_followers']; ?>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-700 font-bold mb-2">Following</p>
                        <p class="border border-gray-300 rounded-md py-2 px-3">
                            <?php ['user']['user_following']; ?>
                        </p>
                    </div>
                    <div class="col-span-2 mb-4">
                        <p class="text-gray-700 font-bold mb-2">Movies Liked</p>
                        <p class="border border-gray-300 rounded-md py-2 px-3">
                            <?php ['user']['liked']; ?>; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
