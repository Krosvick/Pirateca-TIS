<?php
    require 'functions.php';
    require_once __DIR__ . '/vendor/autoload.php';

    #needed rows
    $needed_rows = ['First name', 'Identifier'];

    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ";";
    $csv->parse('username.csv');
    $csv->data = array_map(function($row) use ($needed_rows) {
        return array_intersect_key($row, array_flip($needed_rows));
    }, $csv->data);
    
    
    dd($csv->data);