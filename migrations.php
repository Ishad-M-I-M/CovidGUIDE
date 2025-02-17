<?php
error_reporting(1); //Report only errors
use app\core\App;

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$config = [
    'db'=>[
        'dsn'=>$_ENV['DB_DSN'].'dbname='.$_ENV['DB_NAME'].';',
        'user'=>$_ENV['DB_USER'],
        'password'=>$_ENV['DB_PASSWORD']
    ]
];

$app = new App(__DIR__,$config);

$app->db->applyMigrations($_ENV['DB_NAME']);



