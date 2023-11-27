<?php

class Configuration {

    private static ?array $parameters = null;

    public static function get(string $name, ?string $defaultValue = null) : null|string|array {
        return isset(self::get_parameters()[$name]) ? self::get_parameters()[$name] : $defaultValue;
    }

    private static function get_parameters() : array {
        if (self::$parameters == null) {
            $file_path = "config/dev.ini";
            if (!file_exists($file_path)) {
                $file_path = "config/prod.ini";
            }
            if (!file_exists($file_path)) {
                throw new Exception("Config file is missing.");
            } else {
                self::$parameters = parse_ini_file($file_path);
            }
        }
        return self::$parameters;
    }

    public static function is_dev(): bool {
        return file_exists("config/dev.ini");
    }

}
