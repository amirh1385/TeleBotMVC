<?php

namespace Libs\Bot;

class Bot{

    public static function getToken(){
        return parse_ini_file("config.ini", true)["bot"]["token"];
    }

    public static function getBaseURL(){
        return parse_ini_file("config.ini", true)["bot"]["base_url"] . "/bot";
    }

    public static function sendGetRequest($url, $params = []) {
        error_log(json_encode($params));
        // error_log($url);
        // اضافه کردن پارامترها به URL
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        // مقداردهی اولیه cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // غیرفعال کردن SSL Verification (در صورت نیاز)
        
        // ارسال درخواست
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // مدیریت خطاهای cURL
        if (curl_errno($ch)) {
            error_log("خطای cURL: " . curl_error($ch));
            curl_close($ch);
            return null; // یا می‌توانید یک استثنا پرتاب کنید
        }
        
        curl_close($ch);
        
        // بررسی پاسخ HTTP
        if ($httpCode !== 200) {
            error_log("خطای HTTP: کد پاسخ " . $httpCode);
            return null; // یا می‌توانید یک استثنا پرتاب کنید
        }
        
        return $response;
    }
    

    public static function sendMessage($chat_id, $text, $reply = null, $reply_keyboard = null) {
        // ساخت URL اصلی
        $url = self::getBaseURL() . self::getToken() . "/sendMessage";
        
        // داده‌های POST
        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        
        if ($reply !== null) {
            $data['reply_to_message_id'] = $reply;
        }
        if ($reply_keyboard !== null) {
            // $data['reply_markup'] = ["inline_keyboard" => $reply_keyboard];
            $data['reply_markup'] = '{"inline_keyboard": [[{ text: "Press here", callback_data: "TEST" }]]}';
        }
        
        self::sendGetRequest($url, $data);
    }    
}