<?php

class ClientController
{
    public function index()
    {
        try {
            $clients = Client::list();
            echo json_encode(['clients' => $clients]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $name = $data['name'];
            ClientHelper::validateClientName($name);
            $name = strtolower($name);

            $email = $data['email'];
            ClientHelper::validateEmail($email);
            $email = strtolower($email);

            Client::emailExists($email);

            $phone = $data['phone'];
            $cleanPhone = ClientHelper::isValidPhone($phone);

            $password = $data['password'];
            ClientHelper::validatePassword($password);
            
            $passwordConfirm = $data['passwordConfirm'];
            if($password !== $passwordConfirm) {
                throw new ValidationException('The passwords you entered do not match.', 400);
            }

            $hashedPassword = ClientHelper::hashPassword($password);

            $clientCreate = new stdClass();
            $clientCreate->name = $name;
            $clientCreate->email = $email;
            $clientCreate->hashedPassword = $hashedPassword;
            $clientCreate->emailCheck = "n";
            $clientCreate->status = "a";

            $clientId = Client::createWithProcedure($clientCreate);
            if (!$clientId) {
                throw new Exception('Failed to create the register.', 400);
            }

            $contactCreate = new stdClass();
            $contactCreate->clientId = $clientId;
            $contactCreate->clientPhone = $cleanPhone;
            $contactCreate->clientStatus = "a";

            Contact::createWithProcedure($contactCreate);

            echo json_encode(['message' => 'Registration completed successfully.']);

        } catch (ValidationException $e) {
            http_response_code($e->getErrorCode());
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function show()
    {
        try {
            $clientId = $_GET['clientId'];
            ClientHelper::isValidClientId($clientId);

            $client = Client::show($clientId);
            if (!$client) {
                throw new ValidationException("Client not found", 400);
            }

            echo json_encode(['client' => $client]);
        } catch (ValidationException $e) {
            http_response_code($e->getErrorCode());
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}