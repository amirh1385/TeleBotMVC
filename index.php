<?php

require_once "autoload.php";

use Libs\Router;
use Libs\Router\CommandHandler;
use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$datas = new TelegramDecoder\TelegramResponse($update);

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/views");
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . "/twigcache"
]);

// function view($name, $data = []) {
//     global $twig;
//     $viewtext = $twig->render($name, $data);
//     $doc = new DOMDocument();

//     libxml_use_internal_errors(true);
//     $doc->loadHTML($viewtext);
//     libxml_clear_errors();

//     // گرفتن تگ main
//     $maintag = $doc->getElementsByTagName("main")->item(0);

//     // مقداردهی پیش‌فرض به text
//     $text = '';
//     if ($maintag && $maintag->getElementsByTagName("text")->item(0)) {
//         $text = $maintag->getElementsByTagName("text")->item(0)->nodeValue;
//     }

//     // مقداردهی به keyboard
//     $keyboard = [];
//     if ($maintag && $maintag->getElementsByTagName("inlinekeyboard")->item(0)) {
//         $keyb = $maintag->getElementsByTagName("inlinekeyboard")->item(0);
        
//         foreach ($keyb->getElementsByTagName("keyboardRow") as $row) {
//             $keys = [];
//             foreach ($row->getElementsByTagName("key") as $key) {
//                 $keys[] = ["text" => trim($key->nodeValue)];
//             }
//             $keyboard[] = $keys;
//         }
//     }

//     // ساخت آرایه پاسخ
//     $resp = ["text" => $text];
//     if (!empty($keyboard)) {
//         $resp["keyboard"] = $keyboard;
//     }

//     return $resp;
// }


function view($name, $data = []) {
    global $twig;
    // بارگذاری XML به شیء SimpleXMLElement
    $xmlObject = simplexml_load_string($twig->render($name, $data));
    
    // استخراج متن از تگ <text>
    $text = (string) $xmlObject->text;
    
    // ایجاد آرایه برای اینلاین کیبورد
    $keyboard = [];
    
    // پیمایش هر ردیف از کیبورد
    foreach ($xmlObject->inlinekeyboard->keyboardRow as $row) {
        $rowArray = [];
        
        // استخراج هر کلید از ردیف
        foreach ($row->key as $key) {
            $rowArray[] = ['text' => (string)$key];
        }
        
        // افزودن ردیف به آرایه کیبورد
        $keyboard[] = $rowArray;
    }
    
    // برگشت داده‌ها به صورت آرایه
    return [
        'text' => $text,
        'keyboard' => $keyboard
    ];
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