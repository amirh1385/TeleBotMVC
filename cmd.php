<?php
require_once "autoload.php";
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

switch ($argv[1]) {
    case 'migrate':
        foreach (scandir("database/migrations") as $key => $value) {
            if($value != "." && $value != ".."){
                require "database/migrations/" . $value;
            }
        }
        echo "Migration Successful";
        break;

    case 'migrate:fresh':
        // غیرفعال کردن چک‌های foreign key
        Capsule::connection()->statement('SET FOREIGN_KEY_CHECKS = 0');
    
        // دریافت تمام جداول از پایگاه داده
        $tables = Capsule::connection()->select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [Capsule::connection()->getDatabaseName()]);
    
        // حذف جداول
        foreach ($tables as $table) {
            echo $table->table_name;
            Capsule::connection()->statement("DROP TABLE IF EXISTS {$table->table_name}");
        }
    
        // فعال کردن دوباره چک‌های foreign key
        Capsule::connection()->statement('SET FOREIGN_KEY_CHECKS = 1');
    
        // اجرای مایگریت‌ها
        // foreach (scandir("database/migrations") as $key => $value) {
            // if ($value != "." && $value != "..") {
                // require "database/migrations/" . $value;
            // }
        // }
    
        echo "Migration Successful";
        break;
        
    case 'create:model':
        if(!isset($argv[2])) {
            echo "please enter model name";
            break;
        }
        if(file_exists("database/Models/" . $argv[2] . ".php")){
            echo "model " . $argv[2] . " has exist";
            break;
        }
        if(file_exists("database/migrations/" . $argv[2] . ".php")){
            echo "migration " . $argv[2] . " has exist";
            break;
        }
        $file = fopen("database/Models/" . $argv[2] . ".php", "a");
        fwrite($file, ("<?php\nnamespace Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass " . $argv[2] . " extends Model{\n\n}"));
        $file = fopen("database/migrations/" . $argv[2] . ".php", "a");
        fwrite($file, "<?php\nuse Illuminate\Database\Capsule\Manager as Capsule;\nuse Illuminate\Database\Schema\Blueprint;\n\n// اجرای مایگریشن برای ساخت جدول users\nCapsule::schema()->create(' " . $argv[2] . "', function (Blueprint \$table) {\n    \$table->increments('id');\n    \$table->timestamps();\n});");
        echo "Model creation success";

    default:
        # code...
        break;
}