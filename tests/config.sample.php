<?php
return [
    'mongo' => [
        'db' => 'vegas_test',
    ],
    'db'    =>  [
        "host" => "localhost",
        "dbname" => "vegas_test",
        "port" => 3306,
        "username" => "root",
        "password" => "",
        "options" => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ]
    ]
];