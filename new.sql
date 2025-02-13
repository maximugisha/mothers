CREATE TABLE dioceses
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(3) NOT NULL UNIQUE
);

CREATE TABLE archdeaconries
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(100) NOT NULL,
    diocese_id INT          NOT NULL,
    FOREIGN KEY (diocese_id) REFERENCES dioceses (id)
);

CREATE TABLE parishes
(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    name            VARCHAR(100) NOT NULL,
    archdeaconry_id INT          NOT NULL,
    FOREIGN KEY (archdeaconry_id) REFERENCES archdeaconries (id)
);

CREATE TABLE churches
(
    id        INT PRIMARY KEY AUTO_INCREMENT,
    name      VARCHAR(100) NOT NULL,
    parish_id INT          NOT NULL,
    FOREIGN KEY (parish_id) REFERENCES parishes (id)
);

CREATE TABLE cgroups
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(3) NOT NULL UNIQUE
);

CREATE TABLE users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    email      VARCHAR(100)                       NOT NULL UNIQUE,
    password   VARCHAR(255)                       NOT NULL,
    firstname  VARCHAR(255)                       NOT NULL,
    lastname   VARCHAR(255)                       NOT NULL,
    role       ENUM ('admin', 'editor', 'viewer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE members
(
    id             INT PRIMARY KEY AUTO_INCREMENT,
    first_name     VARCHAR(50)  NOT NULL,
    last_name      VARCHAR(50)  NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    phone          VARCHAR(20),
    address        TEXT,
    join_date      DATETIME     NOT NULL,
    status         ENUM ('paid', 'unpaid') DEFAULT 'unpaid',
    created_at     TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    church_id      INT,
    cgroup_id       INT,
    next_of_kin    VARCHAR(100),
    member_number  VARCHAR(50),
    number_of_kids INT,
    FOREIGN KEY (church_id) REFERENCES churches (id),
    FOREIGN KEY (cgroup_id) REFERENCES cgroups (id)
);