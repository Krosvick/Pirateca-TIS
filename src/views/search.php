<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Pirateca</title>
    <link rel="stylesheet" href="styles-movies.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <?php require('C:\xampp\htdocs\Sufrimiento-TIS\src\views\partials\nav.php')?>
    <div class="max-w-lg mx-auto mt-8 p-4 rounded-lg border border-gray-300 bg-white bg-opacity-25">
        <div class="flex items-center">
            <input type="text" placeholder="Search" class="w-full px-4 py-2 rounded-lg text-gray-500 bg-white border-2 border-gray-300 outline-none">
            <button class="bg-purple-900 text-white px-4 py-2 rounded-lg ml-2">Search</button>
        </div>
        <hr class="my-4 border-b-2 border-purple-700">
        <div id="searchResults">
            <a href="movie_page.php" class="block">
                <div class="bg-white bg-opacity-70 p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold"><?php echo $data['Movie']['original_title'] ?></h2>
                    <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </a>
            <hr class="my-4 border-b-2 border-purple-700">
            <a href="movie_page.php" class="block">
                <div class="bg-white bg-opacity-70 p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold">Movie</h2>
                    <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </a>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>