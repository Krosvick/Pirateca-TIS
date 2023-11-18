<?php 
/*
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_usuario = "usuario"; 
    $contrasena = "contraseña"; 

    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    
    if ($input_user === $nombre_usuario && $input_contra === $contrasena) {
        
        $_SESSION['username'] = $nombre_usuario;
        
        //cambiarlo al index normal despues

        header("Location: src/views/index.php");
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos";
    }
}*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Pirateca</title>
    <link rel="stylesheet" href="styles_login.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>
  <body>
    <div class="bg"></div>
    <div class="form-container">
      <div class="login-container">
        <h1 class="text-center text-white font-bold text-2xl"><u>Iniciar sesion</u></h2>
        <p>&nbsp</p>
        <?php if(isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>
        <form>
          <div class="mb-6">
            <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-white">Email</label>
            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@email.com" required for="usuario">
          </div>
          <div class="mb-6">
            <label for="password" class="block mb-2 text-sm font-medium text-white dark:text-white">Contraseña</label>
            <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
          </div>
          <button class?="boton" type="submit" class="text-white bg-stone-800 hover:bg-white hover:text-blue-60 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ingresar</button>
        </form>
      </div>
    </div>
  </body>
</html>