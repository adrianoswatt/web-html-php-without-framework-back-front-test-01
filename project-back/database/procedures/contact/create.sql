DROP PROCEDURE IF EXISTS ContactCreate;

CREATE PROCEDURE ContactCreate(
    IN clientId INT,
    IN clientPhone VARCHAR(20),
    IN clientStatus CHAR(1)
)

BEGIN
    INSERT INTO contacts (client, phone, status, createdAt, updatedAt)
    VALUES (clientId, clientPhone, clientStatus, NOW(), NOW());
END;