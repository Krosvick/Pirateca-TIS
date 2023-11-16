<!-- arreglar el tema del directorio -->
<?php require('src/views/partials/nav.php')?>

<?php 
    #dd($data);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data['Movie']['original_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/views/partials/styles-movie.css">
</head>
<body>
    <!-- movie descripction-->
    <div class="p-8 text-white">
        <form class="bg-gradient-to-r from-purple-900 via-purple-700 to-purple-900 max-w-4xl mx-auto my-8 px-10 py-8 text-white shadow-lg rounded-lg flex items-center">
            <div class="poster mr-8">
                <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
                <img src="https://image.tmdb.org/t/p/w500<?php echo $data['Movie']['poster_path']?>" alt="Movie Poster" class="w-64 rounded-lg shadow-lg">
            </div>
            <div class="details">
                <h1 class="text-4xl text-white font-bold mb-4"><?php echo $data['Movie']['original_title']?></h1>
                <p class="text-lg text-gray-100 mb-2"><?php echo $data['Movie']['overview']?></p>
                <p class="text-sm text-gray-100">Release: 2011</p>
                <p class="text-sm text-gray-100">Director: Nicolas Winding Refn</p>
            </div>
        </form>
    </div>
    <!-- commentary -->

    <!-- funcion foreach para los comentarios -->
    <!-- HERE SHOULD BE CHANGED TO DYNAMIC FUNCTIONS -->
    <div class="max-w-screen-md mx-auto mt-8">
        <!-- Single Review Component -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-start">
            <img src="src/views/partials/user-profile-pic-1.jpg" alt="Profile Pic" class="h-12 w-12 rounded-full mr-4">
            <div>
                <h2 class="text-lg font-semibold mb-1">User 1</h2>
                <p class="text-gray-700">This movie was fantastic! The storyline was captivating, and the acting was superb.</p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-start">
            <img src="src/views/partials/user-profile-pic-2.jpg" alt="Profile Pic" class="h-12 w-12 rounded-full mr-4">
            <div>
                <h2 class="text-lg font-semibold mb-1">User 2</h2>
                <p class="text-gray-700">Enjoyed watching this movie. The cinematography was excellent!</p>
            </div>
        </div>

        <!-- Add more review components as needed -->
    </div>
</body>
</html>