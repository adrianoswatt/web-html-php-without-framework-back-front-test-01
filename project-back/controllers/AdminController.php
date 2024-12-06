<?php

class AdminController {
    public static function initProcedures() {
        try {
            GeneralHelper::loadEnv(__DIR__ . '/../.env');

            $data = json_decode(file_get_contents("php://input"), true);
            $password = $data['password'];
            $envPassword = $_ENV['ADMIN_SECRET'];
            if ($password !== $envPassword) {
                throw new ValidationException("Incorrect Admin password!", 400);
            }

            ProcedureLoader::install();

            echo json_encode(['message' => 'Procedures installed successfully']);
        } catch (ValidationException $e) {
            http_response_code($e->getErrorCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}