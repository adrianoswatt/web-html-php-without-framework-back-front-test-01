<?php

class Client
{
    public static function emailExists($email)
    {
        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT COUNT(*) FROM clients WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $db->closeConnection();

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("The email is already in use.", 400);
        }

        return true;
    }

    public static function list()
    {
        try {
            $db = new Database();
            $conn = $db->connect();

            $sql = "SELECT name, email, emailCheck, status FROM clients";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $db->closeConnection();

            return $clients;
        } catch (Exception $e) {
            throw new Exception("Error fetching clients: " . $e->getMessage(), 500);
        }
    }

    public static function show($clientId)
    {
        try {
            $db = new Database();
            $conn = $db->connect();

            $sql = "SELECT name, email, emailCheck, status FROM clients WHERE id = $clientId";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            $client = $stmt->fetch(PDO::FETCH_ASSOC);

            $db->closeConnection();

            return $client;
        } catch (Exception $e) {
            var_dump($e);
            throw new Exception("Error fetching client: " . $e->getMessage(), 500);
        }
    }


    public static function create($clientCreate)
    {
        try {
            $db = new Database();
            $conn = $db->connect();

            // Create the SQL
            $sql = "INSERT INTO clients (name, email, password, emailCheck, status, createdAt, updatedAt) 
                    VALUES (:name, :email, :password, :emailCheck, :status, NOW(), NOW())";

            $stmt = $conn->prepare($sql);

            // Bind the params
            $stmt->bindParam(':name', $clientCreate->name);
            $stmt->bindParam(':email', $clientCreate->email);
            $stmt->bindParam(':password', $clientCreate->hashedPassword);
            $stmt->bindParam(':emailCheck', $clientCreate->emailCheck);
            $stmt->bindParam(':status', $clientCreate->status);

            $stmt->execute();

            $clientId = $conn->lastInsertId();

            $db->closeConnection();

            return $clientId;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception("General error: " . $e->getMessage(), 400);
        }
    }

    public static function createWithProcedure($clientCreate)
    {
        try {
            $db = new Database();
            $conn = $db->connect();

            $conn->exec("SET @lastInsertId = 0;");
            
            $sql = "CALL ClientCreate(:name, :email, :hashedPassword, :emailCheck, :status, @lastInsertId)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':name', $clientCreate->name);
            $stmt->bindParam(':email', $clientCreate->email);
            $stmt->bindParam(':hashedPassword', $clientCreate->hashedPassword);
            $stmt->bindParam(':emailCheck', $clientCreate->emailCheck);
            $stmt->bindParam(':status', $clientCreate->status);

            $stmt->execute();

            $stmt = $conn->query("SELECT @lastInsertId AS lastInsertId");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $clientId = $result['lastInsertId'];

            $db->closeConnection();

            return $clientId;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception("General error: " . $e->getMessage(), 400);
        }
    }
}
