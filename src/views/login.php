<div class="bg"></div>
  <div class="form-container">
    <div class="login-container">
      <h1 class="text-center text-white font-bold text-2xl"><u>Iniciar sesion</u></h2>
        <p>&nbsp</p>
        <?php if (isset($errors)) { ?>
          <?php foreach ($errors["username"] as $error) { ?>
            <p class="text-center text-red-600 font-bold text-xl"><?php echo $error ?></p>
          <?php } ?>
        <?php } ?>
        <form action="" method="post">
          <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-white">Email</label>
            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="nombre de usuario" required for="usuario">
          </div>
          <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-white dark:text-white">Contraseña</label>
            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
          </div>
          <button class?="boton" type="submit" class="text-white bg-stone-800 hover:bg-white hover:text-blue-60 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ingresar</button>
          <button class="text-white hover:text-purple-800 bg-purple-700 hover:bg-purple-600 px-4 py-2 rounded transition duration-300" onclick="window.location.href='register'">Registrarse</button>
        </form>
  </div>
</div>