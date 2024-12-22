<?php

require_once "autoload.php";

use Libs\Router;
use Libs\Router\CommandHandler;
use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$file = fopen("xd.json", "a");
fwrite($file, json_encode($update));

$datas = new TelegramDecoder\TelegramResponse($update);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/views");
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . "/twigcache"
]);

function view($name, $data = []){
    global $twig;
    return $twig->render($name, $data);
}

function loadControllers($directory = __DIR__ . '/controllers') {
    // اسکن فایل‌ها در پوشه
    $files = glob($directory . '/*.php');

    foreach ($files as $file) {
        require_once $file;
    }
}
loadControllers("controllers");

$router = new \Libs\Router\Router();

include "routes/routes.php";

$router->handle($datas);