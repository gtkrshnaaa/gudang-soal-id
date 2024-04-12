DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Author;

-- Tabel Admin
CREATE TABLE Admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

-- Insert Data Admin
INSERT INTO Admin (username, password) VALUES ('admin', 'adminpw');



-- Tabel Author
CREATE TABLE Author (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

-- Insert Data Author
INSERT INTO Author (name, username, password) VALUES ('Gilang', 'gilang', 'password');
