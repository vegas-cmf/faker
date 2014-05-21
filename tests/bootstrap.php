<?php
//Test Suite bootstrap
include __DIR__ . "/../vendor/autoload.php";

define('TESTS_ROOT_DIR', dirname(__FILE__));

$configArray = require_once dirname(__FILE__) . '/config.php';

$config = new \Phalcon\Config($configArray);
$di = new \Phalcon\DI\FactoryDefault();

$di->set('mongo', function() use ($config) {
    $mongo = new \MongoClient();
    return $mongo->selectDb($config->mongo->db);
}, true);

$di->set('collectionManager', function(){
    return new \Phalcon\Mvc\Collection\Manager();
}, true);

$di->set('db', function() use ($config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql($config->db->toArray());
});

\Phalcon\DI::setDefault($di);