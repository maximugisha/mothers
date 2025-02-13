-- Seeder for dioceses
INSERT INTO dioceses (name, code)
VALUES ('Archdiocese of Kampala', 'ACK'),
       ('Diocese of Namirembe', 'DNM'),
       ('Diocese of Luweero', 'DLU'),
       ('Diocese of Mukono', 'DMK'),
       ('Diocese of Masaka', 'DMS'),
       ('Diocese of Fort Portal', 'DFP'),
       ('Diocese of Kasese', 'DKS'),
       ('Diocese of Lango', 'DLA'),
       ('Diocese of Northern Uganda', 'DNU'),
       ('Diocese of West Ankole', 'DWA');

-- Seeder for archdeaconries
INSERT INTO archdeaconries (name, diocese_id)
VALUES ('Central Archdeaconry', 1),
       ('Rubaga Archdeaconry', 2),
       ('Luweero Archdeaconry', 3),
       ('Mukono Archdeaconry', 4),
       ('Masaka Archdeaconry', 5),
       ('Fort Portal Archdeaconry', 6),
       ('Kasese Archdeaconry', 7),
       ('Lira Archdeaconry', 8),
       ('Gulu Archdeaconry', 9),
       ('Bushenyi Archdeaconry', 10);

-- Seeder for parishes
INSERT INTO parishes (name, archdeaconry_id)
VALUES ('St. Pauls Parish, Kampala', 1),
       ('Namirembe Cathedral Parish', 2),
       ('Luweero Town Parish', 3),
       ('Mukono Cathedral Parish', 4),
       ('Masaka Town Parish', 5),
       ('Fort Portal Town Parish', 6),
       ('Kasese Town Parish', 7),
       ('Lira Town Parish', 8),
       ('Gulu Town Parish', 9),
       ('Bushenyi Town Parish', 10);


-- Seeder for churches
INSERT INTO churches (name, parish_id)
VALUES ('All Saints Church, Kampala', 1),
       ('St. Andrews Church, Namirembe', 2),
       ('Holy Trinity Church, Luweero', 3),
       ('St. Philips Church, Mukono', 4),
       ('Christ the King Church, Masaka', 5),
       ('St. Marys Church, Fort Portal', 6),
       ('St. Lukes Church, Kasese', 7),
       ('St. Peters Church, Lira', 8),
       ('Holy Cross Church, Gulu', 9),
       ('Emmanuel Church, Bushenyi', 10);

-- Seeder for cgroups
INSERT INTO cgroups (name, code)
VALUES ('Fellowship of St. John', 'FSJ'),
       ('Womens Guild', 'WG'),
       ('Youth Fellowship', 'YF'),
       ('Mens Fellowship', 'MF'),
       ('Bible Study Group', 'BSG'),
       ('Choir', 'CHR'),
       ('Mothers Union', 'MU'),
       ('Sunday School Teachers', 'SST'),
       ('Ushering Team', 'UT'),
       ('Evangelism Team', 'ET');


-- Seeder for members
INSERT INTO members (first_name, last_name, email, phone, address, join_date, status, church_id, cgroup_id, next_of_kin,
                     member_number, number_of_kids)
VALUES ('Joshua', 'Mugisha', 'joshua.m@example.com', '0772123456', 'Kabalagala', '2023-01-15 00:00:00', 'paid', 1, 1,
        'Sarah Nanyonga', 'ACK-FSJ-2023-1', 2),
       ('Esther', 'Nalubega', 'esther.n@example.com', '0751987654', 'Ntinda', '2023-02-20 00:00:00', 'unpaid', 2, 2,
        'David Kato', 'DNM-WG-2023-2', 3),
       ('Peter', 'Otim', 'peter.o@example.com', '0788112233', 'Wobulenzi', '2023-03-05 00:00:00', 'paid', 3, 3,
        'Grace Akello', 'DLU-YF-2023-3', 1),
       ('Mary', 'Nakitende', 'mary.n@example.com', '0700445566', 'Mukono', '2023-04-10 00:00:00', 'unpaid', 4, 4,
        'John Sserwanga', 'DMK-MF-2023-4', 2),
       ('Joseph', 'Ssemwogerere', 'joseph.s@example.com', '0777990011', 'Masaka', '2023-05-25 00:00:00', 'paid', 5, 5,
        'Annet Nanyondo', 'DMS-BSG-2023-5', 3),
       ('Susan', 'Achan', 'susan.a@example.com', '0755223344', 'Fort Portal', '2023-06-01 00:00:00', 'unpaid', 6, 6,
        'Robert Okello', 'DFP-CHR-2023-6', 1),
       ('David', 'Kato', 'david.k@example.com', '0781556677', 'Kasese', '2023-07-15 00:00:00', 'paid', 7, 7,
        'Sarah Mbabazi', 'DKS-MU-2023-7', 2),
       ('Alice', 'Apio', 'alice.a@example.com', '0702889900', 'Lira', '2023-08-02 00:00:00', 'unpaid', 8, 8,
        'Michael Otim', 'DLA-SST-2023-8', 3),
       ('Michael', 'Oyet', 'michael.o@example.com', '0775112233', 'Gulu', '2023-09-18 00:00:00', 'paid', 9, 9,
        'Christine Akello', 'DNU-UT-2023-9', 1),
       ('Grace', 'Mbabazi', 'grace.m@example.com', '0758445566', 'Bushenyi', '2023-10-05 00:00:00', 'unpaid', 10, 10,
        'John Kagame', 'DWA-ET-2023-10', 2);