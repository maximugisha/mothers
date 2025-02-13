CREATE TABLE users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    username   VARCHAR(50)                        NOT NULL,
    email      VARCHAR(100)                       NOT NULL UNIQUE,
    password   VARCHAR(255)                       NOT NULL,
    firstname   VARCHAR(255)                       NOT NULL,
    lastname   VARCHAR(255)                       NOT NULL,
    role       ENUM ('admin', 'editor', 'viewer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);