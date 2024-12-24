<?php

namespace Libs\View;

class View{
    public $text;
    public $inline_keyboard;
    public function __construct($text, $inline_keyboard = null) {
        $this->text = $text;
        $this->inline_keyboard = $inline_keyboard;
    }
    public static function Parse($text){
        $parses = [];
        $current = "text";

        foreach (explode("\n", $text) as $key => $value) {
            if (preg_match("/^\[(\w+)\]$/", $value, $matches)) {
                $current = $matches[1];
                $parses[$current] = "";
            } else {
                if (!isset($parses[$current])) {
                    $parses[$current] = "";
                }
                $parses[$current] .= $value . "\n";
            }
        }

        foreach ($parses as $key => $value) {
            $parses[$key] = rtrim($value);
        }

        return $parses;
    }

    public static function returnView($name, $datas = []){
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../../views");
        $twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . "/../../twigcache"
        ]);

        $content = $twig->render($name, $datas);
        $parse = self::Parse($content);
        $inline_keyboard = isset($parse["InlineKeyboard"]) ? json_decode($parse["InlineKeyboard"], true) : null;
        return new View($parse["text"], $inline_keyboard);
    }
}