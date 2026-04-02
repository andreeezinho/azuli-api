<?php

namespace App\Infra\Services\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class LogService {

    private static $logger;

    private static function initLogger(){
        if(is_null(self::$logger)){
            self::$logger = new Logger('app_logs');
            
            $logPath = __DIR__ . '/../../../../logs/app.log';

            $dir = dirname($logPath);

            if(!is_dir($dir)){
                mkdir($dir, 0777, true);
            }

            if(!file_exists($logPath)){
                touch($logPath);
                chmod($logPath, 0666);
            }

            if(is_writable($logPath) || is_writeable($dir)){
                self::$logger->pushHandler(new StreamHandler($logPath), Level::Debug);
            }
        }
    }

    public static function logInfo(string $message, array $context = []){
        self::initLogger();
        self::$logger->info($message, $context);
    }

    public static function logError(string $message, array $context = []){
        self::initLogger();
        self::$logger->error($message, $context);
    }

}