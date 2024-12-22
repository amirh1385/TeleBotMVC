<?php

use Illuminate\Database\Capsule\Manager as Capsule;

require 'vendor/autoload.php'; // اطمینان از اینکه autoloader وجود دارد

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => parse_ini_file("config.ini", true)["database"]["driver"],
    'host'      => parse_ini_file("config.ini", true)["database"]["host"],
    'database'  => parse_ini_file("config.ini", true)["database"]["database"],
    'username'  => parse_ini_file("config.ini", true)["database"]["username"],
    'password'  => parse_ini_file("config.ini", true)["database"]["password"],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// بارگذاری فایل‌های مدل
foreach (scandir("database/Models/") as $key => $value) {
    if ($value === "." || $value === "..") {
        continue;
    }
    
    // بررسی اینکه فایل، یک فایل PHP است
    if (pathinfo($value, PATHINFO_EXTENSION) === 'php') {
        require_once "database/Models/$value";
    }
}