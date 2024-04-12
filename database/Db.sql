DROP TABLE IF EXISTS Admin;

CREATE TABLE Admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);
INSERT INTO Admin (username, password) VALUES ('admin', 'adminpw');