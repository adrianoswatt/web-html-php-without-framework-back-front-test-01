<?php

class Contact
{
    public static function createWithProcedure($contactCreate)
    {
        try {
            $db = new Database();
            $conn = $db->connect();

            $sql = "CALL ContactCreate(:client, :phone, :status)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':client', $contactCreate->clientId);
            $stmt->bindParam(':phone', $contactCreate->clientPhone);
            $stmt->bindParam(':status', $contactCreate->clientStatus);

            $stmt->execute();

            $db->closeConnection();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception("General error: " . $e->getMessage(), 400);
        }
    }
}
