<?php
namespace Libs\Bot;

class Bot {

    public static function getToken(){
        return parse_ini_file("config.ini", true)["bot"]["token"];
    }

    public static function getBaseURL(){
        return parse_ini_file("config.ini", true)["bot"]["base_url"] . "/bot";
    }

    public static function sendGetRequest($method, $params = []) {
        $baseUrl = parse_ini_file("config.ini", true)["bot"]["base_url"];
        $token = parse_ini_file("config.ini", true)["bot"]["token"];
        $url = $baseUrl . "/bot" . $token . "/" . $method;

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
        
        // ��یریت خطاهای cURL
        if (curl_errno($ch)) {
            error_log("خطای cURL: " . curl_error($ch));
            curl_close($ch);
            return null; // یا می‌توانید یک استثنا پرتاب کنید
        }
        
        curl_close($ch);
        
        // بررسی پاسخ HTTP
        if ($httpCode !== 200) {
            error_log("خطای HTTP: کد پاسخ " . $httpCode);
            $responseData = json_decode($response, true);
            error_log("Telegram Error: " . $responseData['description']);
            return null; // یا می‌توانید یک استثنا پرتاب کنید
        }
        
        return $response;
    }
    
    public static function sendMessage($chat_id, $text, $reply = null, $reply_keyboard = null) {
        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        
        if ($reply !== null) {
            $data['reply_to_message_id'] = $reply;
        }

        if ($reply_keyboard !== null) {
            $data['reply_markup'] = json_encode(['inline_keyboard' => $reply_keyboard]);
        }
        return json_decode(self::sendGetRequest('sendMessage', $data), true);
    }
    
    public static function answerCallbackQuery($callback_query_id, $text = null, $show_alert = false) {
        $data = [
            'callback_query_id' => $callback_query_id
        ];
        
        if ($text !== null) {
            $data['text'] = $text;
        }
        
        $data['show_alert'] = $show_alert;
        
        return self::sendGetRequest('answerCallbackQuery', $data);
    }

    public static function deleteMessage($chat_id, $message_id) {
        $data = [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ];
        
        return self::sendGetRequest('deleteMessage', $data);
    }

    public static function getUser($user_id) {
        $data = [
            'chat_id' => $user_id
        ];
        
        $response = self::sendGetRequest('getChat', $data);
        return json_decode($response, true);
    }
}