<!DOCTYPE html>

<html>
<head>
   <meta charset="UTF-8">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
   <link rel="stylesheet" href="/css/styles-movie.css">
</head>
<body>
    <nav class="bg-white shadow">
        <div class="max-w-screen-xl mx-auto px-4 py-2 flex items-center justify-between">
            <div>
                <img src="/images/logo.png" alt="Logo" class="h-8">
            </div>
            <div>
                <button class="text-white hover:text-purple-800 bg-purple-700 hover:bg-purple-600 px-4 py-2 rounded transition duration-300" onclick="window.location.href='login'">Login</button>
                <button class="text-purple-800 hover:text-gray-900 hover:bg-gray-100 px-4 py-2 rounded transition duration-300" onclick="window.location.href='index'">Home</button>
                <button class="text-purple-800 hover:text-gray-900 hover:bg-gray-100 px-4 py-2 rounded transition duration-300" onclick="window.location.href='search'">Search</button>
                <button class="text-purple-800 hover:text-gray-900 hover:bg-gray-100 px-4 py-2 rounded transition duration-300" onclick="window.location.href='likedmovies'">Liked Posts</button>
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Perfil <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                    </button>
                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a href="../profile" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Perfil</a>
                        </li>
                        <li>
                            <a href="../likedpost" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Liked</a>
                        </li>
                        <li>
                            <a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Followers: <?php ['user']['user_followers']?></a>
                        </li>
                        <li>
                            <a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Following: <?php ['user']['user_following']?></a>
                        </li>
                        </ul>
                    </div>
            </div>
        </div>
    </nav>
</body>
</html>