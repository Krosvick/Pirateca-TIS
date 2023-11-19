
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Pirateca</title>
    <link rel="stylesheet" href="/css/styles_login.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>
  <body>
    <div class="bg"></div>
    <div class="form-container">
      <div class="login-container">
        <div class="title-container bg-transparent p-4 mb-6">
          <h2 class="text-white text-xl font-bold">Registro de Cuenta</h2>
        </div>
        <form>
          <div class="mb-6">
            <label for="username" class="block mb-2 text-sm font-medium text-white dark:text-white">Usuario</label>
            <input type="text" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required>
          </div>
          <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-white">Email</label>
            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required>
          </div>
          <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-white dark:text-white">Contraseña</label>
            <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
          </div>
          <button type="submit" class="text-white bg-stone-800 hover:bg-white hover:text-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrarse</button>
        </form>
      </div>
    </div>
  </body>
</html>