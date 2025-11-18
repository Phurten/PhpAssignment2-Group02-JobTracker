CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    image VARCHAR(255),
    school_id INT,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE SET NULL
);