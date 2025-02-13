-- Seeder for dioceses
INSERT INTO dioceses (name, code) VALUES ('Diocese 1', 'DIO1'), ('Diocese 2', 'DIO2');

-- Seeder for archdeaconries
INSERT INTO archdeaconries (name, diocese_id) VALUES
('Mbarara Archdeaconry ', 1),
('Kabaale Archdeaconry ', 1),
('Kampala Archdeaconry ', 2);

-- Seeder for parishes
INSERT INTO parishes (name, archdeaconry_id) VALUES
('Mayuge Parish 1', 1),
('Parish 2', 1),
('Parish 3', 2),
('Parish 4', 3);

-- Seeder for churches
INSERT INTO churches (name, parish_id) VALUES
('Church 1', 1),
('Church 2', 2),
('Church 3', 3),
('Church 4', 4);

-- Seeder for cgroups
INSERT INTO cgroups (name, code) VALUES ('Group 1', 'GRP1'), ('Group 2', 'GRP2'), ('Group 3', 'GRP3');

-- Seeder for members
INSERT INTO members (first_name, last_name, email, phone, address, join_date, status, church_id, cgroup_id, next_of_kin, member_number, number_of_kids) VALUES
('John', 'Doe', 'john.doe@example.com', '1234567890', '123 Main St', '2023-01-01 00:00:00', 'paid', 1, 1, 'Jane Doe', 'DIO1-GRP1-2023-1', 2),
('Jane', 'Smith', 'jane.smith@example.com', '0987654321', '456 Elm St', '2023-02-01 00:00:00', 'unpaid', 2, 2, 'John Smith', 'DIO1-GRP2-2023-2', 3),
('Alice', 'Johnson', 'alice.johnson@example.com', '1112223333', '789 Oak St', '2023-03-01 00:00:00', 'paid', 3, 3, 'Bob Johnson', 'DIO2-GRP3-2023-3', 1);