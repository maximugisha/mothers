CREATE TABLE dioceses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE archdeaconries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    diocese_id INT NOT NULL,
    FOREIGN KEY (diocese_id) REFERENCES dioceses(id)
);

CREATE TABLE parishes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    archdeaconry_id INT NOT NULL,
    FOREIGN KEY (archdeaconry_id) REFERENCES archdeaconries(id)
);

CREATE TABLE churches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    parish_id INT NOT NULL,
    FOREIGN KEY (parish_id) REFERENCES parishes(id)
);

CREATE TABLE cgroups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

ALTER TABLE members
ADD COLUMN church_id INT,
ADD COLUMN group_id INT,
ADD COLUMN next_of_kin VARCHAR(100),
ADD COLUMN member_number VARCHAR(50),
ADD COLUMN number_of_kids INT,
ADD FOREIGN KEY (church_id) REFERENCES churches(id),
ADD FOREIGN KEY (cgroup_id) REFERENCES cgroups(id);

ALTER TABLE cgroups
    ADD COLUMN code VARCHAR(100) NOT NULL UNIQUE;

ALTER TABLE dioceses
    ADD COLUMN code VARCHAR(100) NOT NULL UNIQUE;