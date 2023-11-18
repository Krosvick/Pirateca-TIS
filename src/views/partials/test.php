<?php require ('nav.php')?>


<h1>Aqui va una prueba</h1>


<form action="test" method="post">
    <input type="text" name="busqueda"> <br>
    <input type="submit" name="enviar" value="Submit">
</form>

<?php

if(isset($_POST['enviar'])){
    $busqueda = $_POST['busqueda'];
    //$busqueda = "Driv";
    $movies = $movieDAO->dummytest_fulltext($busqueda);
    foreach($movies as $row){
        echo $row['original_title']. '<br>';
    }
}
else{
    echo "escribe algo";
}

?>
