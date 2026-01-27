-- Sélection de la base de données
USE
    my_cinema;

-- ==========================================
-- 1. INSERTION DES FILMS (MOVIES)
-- ==========================================
INSERT INTO movies (title, description, duration, release_year, genre, director)
VALUES ('Dune : Deuxième Partie',
        'Paul Atreides s’unit à Chani et aux Fremen pour mener la révolte contre ceux qui ont anéanti sa famille.', 166,
        2024, 'Science-Fiction', 'Denis Villeneuve'),
       ('Oppenheimer', 'Le parcours de J. Robert Oppenheimer, le physicien à la tête du projet Manhattan.', 180, 2023,
        'Biopic/Drame', 'Christopher Nolan'),
       ('Barbie', 'Barbie vit à Barbie Land, jusqu’à ce qu’une crise existentielle la mène dans le monde réel.', 114,
        2023, 'Comédie', 'Greta Gerwig'),
       ('Le Parrain', 'L’histoire de la famille Corleone et de la montée de Michael Corleone au pouvoir.', 175, 1972,
        'Crime/Drame', 'Francis Ford Coppola'),
       ('Pulp Fiction', 'Les vies de deux tueurs à gages, d’un boxeur et de la femme d’un gangster s’entrecroisent.',
        154, 1994, 'Crime', 'Quentin Tarantino');


-- ==========================================
-- 2. INSERTION DES SALLES (ROOMS)
-- ==========================================
INSERT INTO rooms (name, capacity, type, active)
VALUES ('Salle 1 - Le Grand Rex', 500, 'IMAX', TRUE),
       ('Salle 2 - Confort', 150, 'Standard', TRUE),
       ('Salle 3 - 4DX Experience', 100, '4DX', TRUE),
       ('Salle 4 - VIP Lounge', 30, 'VIP', TRUE),
       ('Salle 5 - Maintenance', 80, 'Standard', FALSE);
-- Celle-ci est inactive


-- ==========================================
-- 3. INSERTION DES SÉANCES (SCREENINGS)
-- ==========================================
-- Robust seed: do not assume IDs start at 1.
INSERT INTO screenings (movies_id, room_id, start_time)
VALUES
  ((SELECT id FROM movies WHERE title = 'Dune : Deuxième Partie' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 1 - Le Grand Rex' LIMIT 1),
   '2024-10-25 14:00:00'),
  ((SELECT id FROM movies WHERE title = 'Dune : Deuxième Partie' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 1 - Le Grand Rex' LIMIT 1),
   '2024-10-25 18:00:00'),
  ((SELECT id FROM movies WHERE title = 'Dune : Deuxième Partie' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 1 - Le Grand Rex' LIMIT 1),
   '2024-10-25 21:30:00'),

  ((SELECT id FROM movies WHERE title = 'Oppenheimer' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 2 - Confort' LIMIT 1),
   '2024-10-25 19:00:00'),

  ((SELECT id FROM movies WHERE title = 'Barbie' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 3 - 4DX Experience' LIMIT 1),
   '2024-10-26 15:30:00'),
  ((SELECT id FROM movies WHERE title = 'Barbie' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 3 - 4DX Experience' LIMIT 1),
   '2024-10-26 20:00:00'),

  ((SELECT id FROM movies WHERE title = 'Le Parrain' LIMIT 1),
   (SELECT id FROM rooms  WHERE name  = 'Salle 4 - VIP Lounge' LIMIT 1),
   '2024-10-27 20:00:00');