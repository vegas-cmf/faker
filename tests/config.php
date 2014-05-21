<?php
return array(
    'mongo' => array(
        'db' => 'vegas_test',
    ),
    'db'    =>  array(
        "host" => "localhost",
        "dbname" => "vegas",
        "port" => 3306,
        "username" => "root",
        "password" => "root",
        "options" => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        )
    )
);