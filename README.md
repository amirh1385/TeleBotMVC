# فریمورک بات تلگرام PHP

<div dir="rtl">

یک فریمورک ساده و قدرتمند برای ساخت بات‌های تلگرام با PHP که از معماری MVC استفاده می‌کند.

## ویژگی‌ها
- 🛣️ مدیریت ساده روت‌ها و دستورات
- 📨 پردازش خودکار پیام‌های دریافتی از تلگرام
- 🎨 سیستم قالب‌بندی با Twig
- 💾 ارتباط با دیتابیس توسط Eloquent
- 🛠️ ابزارهای مدیریت مدل و مایگریشن
- 📝 پشتیبانی از callback_query ها
- 🔄 پشتیبانی از پیام‌های فوروارد شده و ریپلای
- 📎 پشتیبانی از فایل‌ها (عکس، ویدیو و اسناد)

## پیش‌نیازها
- PHP 8.2 یا بالاتر
- Composer
- MySQL
- SSL (برای وبهوک تلگرام)

## نصب و راه‌اندازی

1. نصب وابستگی‌ها:
composer create-project your-vendor/telegram-bot-framework your-bot

2. تنظیم فایل `.env`:
cp config.ini.example config.ini

3. ویرایش فایل `config.ini` و تنظیم موارد زیر:

[bot]
base_url = "https://your-domain.com"
token = "YOUR_BOT_TOKEN"
[database]
driver = "mysql"
host = "127.0.0.1"
username = "root"
password = ""
database = "your_database"

4. اجرای مایگریشن‌ها:
php cmd.php migrate

## نحوه استفاده

برای تعریف روت‌های جدید، فایل `routes/routes.php` را ویرایش کنید:

use Controllers\start;
$router->addRoute(new CommandHandler("/start", [start::class, "start"]));

برای ایجاد ویو جدید، یک فایل در پوشه `views` ایجاد کنید:
پیام خوش‌آمدگویی شما
[InlineKeyboard]
[[{"text": "دکمه تست", "url": "https://example.com"}]]

## مستندات
برای اطلاعات بیشتر به [ویکی پروژه](https://github.com/your-vendor/telegram-bot-framework/wiki) مراجعه کنید.

## مشارکت
از مشارکت شما در این پروژه استقبال می‌کنیم. لطفاً قبل از ارسال Pull Request، [راهنمای مشارکت](CONTRIBUTING.md) را مطالعه کنید.

## لایسنس
این پروژه تحت لایسنس MIT منتشر شده است.

</div>
