-- Sélection de la base de données
USE
    my_cinema;

-- Réinitialisation de l'auto-increment (pour repartir de l'ID 1)
ALTER TABLE screenings
    AUTO_INCREMENT = 1;
ALTER TABLE rooms
    AUTO_INCREMENT = 1;
ALTER TABLE movies
    AUTO_INCREMENT = 1;


-- ==========================================
-- 1. INSERTION DES FILMS (MOVIES)
-- ==========================================
INSERT INTO movies (title, description, duration, release_year, genre, director, created_at, updated_at)
VALUES ('Dune : Deuxième Partie',
        'Paul Atreides s’unit à Chani et aux Fremen pour mener la révolte contre ceux qui ont anéanti sa famille.', 166,
        2024, 'Science-Fiction', 'Denis Villeneuve', NOW(), NOW()),
       ('Oppenheimer', 'Le parcours de J. Robert Oppenheimer, le physicien à la tête du projet Manhattan.', 180, 2023,
        'Biopic/Drame', 'Christopher Nolan', NOW(), NOW()),
       ('Barbie', 'Barbie vit à Barbie Land, jusqu’à ce qu’une crise existentielle la mène dans le monde réel.', 114,
        2023, 'Comédie', 'Greta Gerwig', NOW(), NOW()),
       ('Le Parrain', 'L’histoire de la famille Corleone et de la montée de Michael Corleone au pouvoir.', 175, 1972,
        'Crime/Drame', 'Francis Ford Coppola', NOW(), NOW()),
       ('Pulp Fiction', 'Les vies de deux tueurs à gages, d’un boxeur et de la femme d’un gangster s’entrecroisent.',
        154, 1994, 'Crime', 'Quentin Tarantino', NOW(), NOW());


-- ==========================================
-- 2. INSERTION DES SALLES (ROOMS)
-- ==========================================
INSERT INTO rooms (name, capacity, type, active, created_at, updated_at)
VALUES ('Salle 1 - Le Grand Rex', 500, 'IMAX', TRUE, NOW(), NOW()),
       ('Salle 2 - Confort', 150, 'Standard', TRUE, NOW(), NOW()),
       ('Salle 3 - 4DX Experience', 100, '4DX', TRUE, NOW(), NOW()),
       ('Salle 4 - VIP Lounge', 30, 'VIP', TRUE, NOW(), NOW()),
       ('Salle 5 - Maintenance', 80, 'Standard', FALSE, NOW(), NOW());
-- Celle-ci est inactive


-- ==========================================
-- 3. INSERTION DES SÉANCES (SCREENINGS)
-- ==========================================
-- Note : On suppose ici que les IDs suivent l'ordre d'insertion (Dune=1, Oppenheimer=2, etc.)

INSERT INTO screenings (movies_id, room_id, start_time, created_at)
VALUES
-- Dune (ID 1) dans la salle IMAX (ID 1)
(1, 1, '2024-10-25 14:00:00', NOW()),
(1, 1, '2024-10-25 18:00:00', NOW()),
(1, 1, '2024-10-25 21:30:00', NOW()),

-- Oppenheimer (ID 2) dans la salle Standard (ID 2)
(2, 2, '2024-10-25 19:00:00', NOW()),

-- Barbie (ID 3) dans la salle 4DX (ID 3)
(3, 3, '2024-10-26 15:30:00', NOW()),
(3, 3, '2024-10-26 20:00:00', NOW()),

-- Le Parrain (ID 4) dans la salle VIP (ID 4)
(4, 4, '2024-10-27 20:00:00', NOW());