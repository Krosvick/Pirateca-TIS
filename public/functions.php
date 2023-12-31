<?php


function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

function base_path($path)
{
    return BASE_PATH . $path;
}

function redirect($path)
{
    header("location: {$path}");
    exit();
}
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return 'Joined ' . $date->format('F Y');
}