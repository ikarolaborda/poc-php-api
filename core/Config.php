<?php

namespace Core;

class Config {
    private static array $config = [];

    public static function get($key, $default = null) {
        if (empty(self::$config)) {
            self::loadConfig();
        }
        return self::$config[$key] ?? $default;
    }

    private static function loadConfig(): void {
        self::$config = require __DIR__ . '/../config/config.php';
    }
}
