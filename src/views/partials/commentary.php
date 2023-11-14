<!DOCTYPE html>

<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
<div class="max-w-lg mx-auto mt-8 p-6 rounded-lg border border-gray-300 bg-white bg-opacity-25">
    <h2 class="text-3xl font-semibold text-gray mb-6">Reseña</h2>
    <div class="flex items-center mb-6">
        <textarea placeholder="Escribe aquí..." class="w-full h-32 px-4 py-2 rounded-lg text-gray-500 bg-white border-2 border-gray-300 resize-none focus:outline-none focus:border-purple-700"></textarea>
    </div>
    <div class="flex items-center mb-6">
        <span class="text-white mr-4">Puntación</span>
        <div class="flex items-center">
            <input type="radio" id="star5" name="rating" value="5" class="hidden" />
            <label for="star5" class="text-3xl cursor-pointer">&#9733;</label>
            <input type="radio" id="star4" name="rating" value="4" class="hidden" />
            <label for="star4" class="text-3xl cursor-pointer">&#9733;</label>
            <input type="radio" id="star3" name="rating" value="3" class="hidden" />
            <label for="star3" class="text-3xl cursor-pointer">&#9733;</label>
            <input type="radio" id="star2" name="rating" value="2" class="hidden" />
            <label for="star2" class="text-3xl cursor-pointer">&#9733;</label>
            <input type="radio" id="star1" name="rating" value="1" class="hidden" />
            <label for="star1" class="text-3xl cursor-pointer">&#9733;</label>
        </div>
    </div>
    <button class="bg-purple-900 text-white px-6 py-3 rounded-lg">Enviar</button>
</div>
</body>