<?php

namespace BotCache;

class BotCache {
    private static $cacheDir = 'cache/';
    
    public static function getCache($chat_id, $user_id, $key) {
        $filename = self::getCacheFilename($chat_id, $user_id);
        
        // اگر فایل وجود نداشت، یک فایل خالی بساز
        if (!file_exists($filename)) {
            self::createCacheFile($filename);
            return null;
        }
        
        $data = json_decode(file_get_contents($filename), true);
        return isset($data[$key]) ? $data[$key] : null;
    }

    public static function setCache($chat_id, $user_id, $key, $value) {
        $filename = self::getCacheFilename($chat_id, $user_id);
        
        // اگر فایل وجود نداشت، یک فایل خالی بساز
        if (!file_exists($filename)) {
            self::createCacheFile($filename);
        }
        
        $data = json_decode(file_get_contents($filename), true) ?: [];
        $data[$key] = $value;
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }
    
    private static function getCacheFilename($chat_id, $user_id = null) {
        if ($user_id === null) {
            // فقط برای چت
            return self::$cacheDir . 'chat_' . $chat_id . '.json';
        } elseif ($chat_id === null) {
            // فقط برای کاربر
            return self::$cacheDir . 'user_' . $user_id . '.json';
        }
        // برای ترکیب چت و کاربر
        return self::$cacheDir . 'cache_' . $chat_id . '_' . $user_id . '.json';
    }
    
    private static function createCacheFile($filename) {
        // ساخت پوشه اگر وجود نداشت
        if (!file_exists(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
        
        // ساخت فایل با یک آرایه خالی
        file_put_contents($filename, json_encode([], JSON_PRETTY_PRINT));
    }

    // متدهای جدید برای دسترسی به کش چت
    public static function getChatCache($chat_id, $key) {
        return self::getCache($chat_id, null, $key);
    }

    public static function setChatCache($chat_id, $key, $value) {
        return self::setCache($chat_id, null, $key, $value);
    }

    // متدهای جدید برای دسترسی به کش کاربر
    public static function getUserCache($user_id, $key) {
        return self::getCache(null, $user_id, $key);
    }

    public static function setUserCache($user_id, $key, $value) {
        return self::setCache(null, $user_id, $key, $value);
    }
}
