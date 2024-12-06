DROP PROCEDURE IF EXISTS ClientCreate;

CREATE PROCEDURE ClientCreate(
    IN clientName VARCHAR(100),
    IN clientEmail VARCHAR(320),
    IN clientHashedPassword VARCHAR(250),
    IN clientEmailCheck CHAR(1),
    IN clientStatus CHAR(1),
    OUT lastInsertId INT
)
BEGIN
    INSERT INTO clients (name, email, password, emailCheck, status, createdAt, updatedAt)
    VALUES (clientName, clientEmail, clientHashedPassword, clientEmailCheck, clientStatus, NOW(), NOW());

    SET lastInsertId = LAST_INSERT_ID();
END;
