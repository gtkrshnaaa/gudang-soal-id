-- Menghapus tabel yang ada jika sudah ada
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS Author;
DROP TABLE IF EXISTS Admin;

-- Membuat tabel Admin
CREATE TABLE Admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

-- Memasukkan data ke dalam tabel Admin
INSERT INTO Admin (name, username, password) VALUES ('Gilang Admin', 'admin', 'adminpw');

-- Membuat tabel Author
CREATE TABLE Author (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

-- Memasukkan data ke dalam tabel Author
INSERT INTO Author (name, username, password) VALUES ('Gilang', 'gilang', 'password');

-- Membuat tabel subjects
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Memasukkan data ke dalam tabel subjects
INSERT INTO subjects (name) VALUES ('Matematika');

-- Membuat tabel articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(1000) NOT NULL,
    content TEXT NOT NULL,
    adlink VARCHAR(1800) NULL,
    subject_id INT NOT NULL,
    view_count INT DEFAULT 0,
    posting_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    admin_id INT,
    author_id INT,
    author_type ENUM('admin', 'author') NOT NULL DEFAULT 'admin',
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (admin_id) REFERENCES Admin(id),
    FOREIGN KEY (author_id) REFERENCES Author(id)
);