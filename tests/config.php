<?php
return [
    'mongo' => [
        'db' => 'vegas_test',
    ],
    'db'    =>  [
        "host" => "localhost",
        "dbname" => "vegas",
        "port" => 3306,
        "username" => "root",
        "password" => "root",
        "options" => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ]
    ]
];