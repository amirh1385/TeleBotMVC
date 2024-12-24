<?php
require_once "autoload.php";

use Libs\Router;
use Libs\Router\CommandHandler;
use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$datas = new TelegramDecoder\TelegramResponse($update);

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