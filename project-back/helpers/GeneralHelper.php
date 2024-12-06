<?php

class GeneralHelper
{
    public static function loadEnv($path)
    {
        if (!file_exists($path)) {
            throw new Exception(".env file not found at {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }
        }
    }

    public static function getOnlyNumbers($phone) {
        return preg_replace('/\D/', '', $phone);
    }
}
