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
            </div>
        </div>
    </nav>
</body>
</html>