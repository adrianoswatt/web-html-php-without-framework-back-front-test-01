<?php

class ClientHelper {
    public static function isValidClientId($clientId) {
        if (!$clientId || empty($clientId)) {
            throw new ValidationException('The client id can\'t be empty or null.', 400);
        }

        if (!is_numeric($clientId)) {
            throw new ValidationException('The client id must be a number value.', 400);
        }

        if ($clientId <= 0) {
            throw new ValidationException('The client id must be a positive number.', 400);
        }

        return true;
    }
    
    public static function validateEmail($email) {
        if (empty($email)) {
            throw new ValidationException('Email is required.', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('The email address you entered is invalid.', 400);
        }
        return true;
    }

    public static function validateClientName($name) {
        if (empty($name)) {
            throw new ValidationException('Name is required.', 400);
        }
    
        if (strlen($name) < 3) {
            throw new ValidationException('Name must be at least 3 characters long.', 400);
        }

        return true;
    }

    public static function validatePassword($password) {
        $minPasswordLength = 8;
        if (!$password || empty($password)) {
            throw new ValidationException('Password is required.', 400);
        }
    
        if (strlen($password) < $minPasswordLength) {
            throw new ValidationException('Password must be at least ' . $minPasswordLength . ' characters long.', 400);
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new ValidationException("Password must include at least one lowercase letter.", 400);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new ValidationException("Password must include at least one uppercase letter.", 400);
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new ValidationException("Password must include at least one number.", 400);
        }

        if (!preg_match('/[\W]/', $password)) {
            throw new ValidationException("Password must include at least one special character (e.g., @, #, $).", 400);
        }

        if (preg_match('/\s/', $password)) {
            throw new ValidationException("Password must not contain spaces.", 400);
        }

        return true;
    }

    public static function isValidPhone($phone) {
        if (!$phone || empty($phone)) {
            throw new ValidationException('Phone is required.', 400);
        }

        $phone = GeneralHelper::getOnlyNumbers($phone);
        if (strlen($phone) < 10) {
            throw new ValidationException('Phone number must be at least 10 digits long.', 400);
        }

        return $phone;
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}