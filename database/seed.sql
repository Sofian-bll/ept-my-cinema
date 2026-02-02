USE my_cinema;

-- Clear existing data
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE screenings;
TRUNCATE TABLE movies;
TRUNCATE TABLE rooms;
SET FOREIGN_KEY_CHECKS = 1;

-- Insert Movies
INSERT INTO movies (title, description, duration, release_year, genre, director) VALUES
('The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 142, 1994, 'Drama', 'Frank Darabont'),
('The Godfather', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 175, 1972, 'Crime, Drama', 'Francis Ford Coppola'),
('The Dark Knight', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests.', 152, 2008, 'Action, Crime, Drama', 'Christopher Nolan'),
('Pulp Fiction', 'The lives of two mob hitmen, a boxer, a gangster and his wife intertwine in four tales of violence and redemption.', 154, 1994, 'Crime, Drama', 'Quentin Tarantino'),
('Forrest Gump', 'The presidencies of Kennedy and Johnson, the Vietnam War, and other historical events unfold from the perspective of an Alabama man.', 142, 1994, 'Drama, Romance', 'Robert Zemeckis'),
('Inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea.', 148, 2010, 'Action, Sci-Fi, Thriller', 'Christopher Nolan'),
('The Matrix', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 136, 1999, 'Action, Sci-Fi', 'Lana Wachowski, Lilly Wachowski'),
('Goodfellas', 'The story of Henry Hill and his life in the mob, covering his relationship with his wife and his partners in the crime family.', 145, 1990, 'Biography, Crime, Drama', 'Martin Scorsese'),
('The Lord of the Rings: The Return of the King', 'Gandalf and Aragorn lead the World of Men against Sauron''s army to draw his gaze from Frodo and Sam.', 201, 2003, 'Action, Adventure, Drama', 'Peter Jackson'),
('Fight Club', 'An insomniac office worker and a devil-may-care soap maker form an underground fight club.', 139, 1999, 'Drama', 'David Fincher'),
('Interstellar', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity''s survival.', 169, 2014, 'Adventure, Drama, Sci-Fi', 'Christopher Nolan'),
('The Silence of the Lambs', 'A young FBI cadet must receive the help of an incarcerated cannibal killer to catch another serial killer.', 118, 1991, 'Crime, Drama, Thriller', 'Jonathan Demme'),
('Saving Private Ryan', 'Following the Normandy Landings, a group of U.S. soldiers go behind enemy lines to retrieve a paratrooper.', 169, 1998, 'Drama, War', 'Steven Spielberg'),
('Jurassic Park', 'A pragmatic paleontologist touring an almost complete theme park on an island is tasked with protecting tourists.', 127, 1993, 'Action, Adventure, Sci-Fi', 'Steven Spielberg'),
('Avatar', 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders.', 162, 2009, 'Action, Adventure, Fantasy', 'James Cameron'),
('Titanic', 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.', 194, 1997, 'Drama, Romance', 'James Cameron'),
('The Avengers', 'Earth''s mightiest heroes must come together to stop Loki and his alien army from enslaving humanity.', 143, 2012, 'Action, Adventure, Sci-Fi', 'Joss Whedon'),
('Spider-Man: No Way HomeController', 'With Spider-Man''s identity now revealed, Peter asks Doctor Strange for help, causing the multiverse to break open.', 148, 2021, 'Action, Adventure, Fantasy', 'Jon Watts'),
('Dune', 'Paul Atreides arrives on Arrakis after his father accepts the stewardship of the dangerous planet.', 155, 2021, 'Action, Adventure, Drama', 'Denis Villeneuve'),
('Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', 180, 2023, 'Biography, Drama, History', 'Christopher Nolan'),
('Dune: Part Two', 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.', 166, 2024, 'Action, Adventure, Drama', 'Denis Villeneuve'),
('Demon Slayer: Mugen Train', 'Tanjiro and his friends embark on a new mission aboard the Mugen Train to investigate demon activities.', 117, 2020, 'Animation, Action, Adventure', 'Haruo Sotozaki'),
('Spirited Away', 'During her family''s move, a sullen 10-year-old girl wanders into a world ruled by gods, witches, and spirits.', 125, 2001, 'Animation, Adventure, Fantasy', 'Hayao Miyazaki'),
('Your Name', 'Two teenagers share a profound connection after they find themselves mysteriously swapping bodies.', 106, 2016, 'Animation, Drama, Fantasy', 'Makoto Shinkai'),
('Howl''s Moving Castle', 'When an unconfident young woman is cursed with an old body by a witch, her only chance of breaking the spell lies with a wizard.', 119, 2004, 'Animation, Adventure, Fantasy', 'Hayao Miyazaki'),
('Demon Slayer: To the Swordsmith Village', 'Tanjiro and his friends travel to the Swordsmith Village to obtain new swords and face powerful demons.', 110, 2023, 'Animation, Action, Adventure', 'Haruo Sotozaki'),
('Suzume', 'A modern girl embarks on a journey across Japan to close mysterious doors that are releasing disasters.', 122, 2022, 'Animation, Adventure, Fantasy', 'Makoto Shinkai'),
('Princess Mononoke', 'On a journey to find the cure for a Tatarigami''s curse, Ashitaka finds himself in the middle of a war.', 134, 1997, 'Animation, Action, Adventure', 'Hayao Miyazaki'),
('Weathering with You', 'A high school boy runs away to Tokyo and befriends a girl who can manipulate the weather.', 112, 2019, 'Animation, Drama, Fantasy', 'Makoto Shinkai'),
('Jujutsu Kaisen 0', 'Yuta Okkotsu gains control of a cursed spirit and enrolls at Tokyo Jujutsu High to become a sorcerer.', 105, 2021, 'Animation, Action, Fantasy', 'Sunghoo Park'),
('The Boy and the Heron', 'A young boy named Mahito ventures into a fantastical world shared by the living and the dead.', 124, 2023, 'Animation, Adventure, Fantasy', 'Hayao Miyazaki');

-- Insert Rooms
INSERT INTO rooms (name, capacity, type, active) VALUES
('Salle 1', 150, 'Standard', TRUE),
('Salle 2', 200, 'Premium', TRUE),
('Salle 3', 100, 'Standard', TRUE),
('Salle 4', 120, 'IMAX', TRUE),
('Salle 5', 80, 'VIP', TRUE),
('Salle 6', 180, 'Standard', TRUE),
('Salle 7', 90, '4DX', TRUE),
('Salle 8', 110, 'Standard', TRUE),
('Salle 9', 200, 'Dolby Atmos', TRUE),
('Salle 10', 75, 'VIP', FALSE);

-- Insert Screenings (Dates en janvier 2026)
INSERT INTO screenings (movies_id, room_id, start_time) VALUES
-- Oppenheimer - Salle IMAX
(20, 4, '2026-01-28 14:00:00'),
(20, 4, '2026-01-28 18:30:00'),
(20, 4, '2026-01-29 20:00:00'),

-- Dune - Salle Premium et IMAX
(19, 2, '2026-01-28 15:00:00'),
(19, 4, '2026-01-29 14:30:00'),
(19, 2, '2026-01-30 19:00:00'),

-- Inception - Salle Dolby Atmos
(6, 9, '2026-01-28 16:00:00'),
(6, 9, '2026-01-29 18:00:00'),
(6, 9, '2026-01-30 21:00:00'),

-- The Dark Knight - Multiple Salles
(3, 1, '2026-01-28 17:00:00'),
(3, 6, '2026-01-29 19:30:00'),
(3, 8, '2026-01-30 20:30:00'),

-- Interstellar - Salle IMAX
(11, 4, '2026-01-28 21:00:00'),
(11, 4, '2026-01-30 15:00:00'),

-- Spider-Man: No Way HomeController - Salle 4DX
(18, 7, '2026-01-28 19:00:00'),
(18, 7, '2026-01-29 16:00:00'),
(18, 7, '2026-01-30 18:00:00'),

-- Avatar - Salle Premium
(15, 2, '2026-01-28 20:00:00'),
(15, 2, '2026-01-29 21:00:00'),

-- The Avengers - Salle Standard
(17, 1, '2026-01-28 13:00:00'),
(17, 6, '2026-01-29 15:00:00'),
(17, 8, '2026-01-30 14:00:00'),

-- The Matrix - Salle Standard
(7, 3, '2026-01-28 22:00:00'),
(7, 8, '2026-01-29 22:30:00'),

-- Pulp Fiction - Salle VIP
(4, 5, '2026-01-28 21:30:00'),
(4, 5, '2026-01-29 23:00:00'),

-- Jurassic Park - Salle Standard
(14, 1, '2026-01-29 13:00:00'),
(14, 3, '2026-01-30 16:00:00'),

-- Titanic - Salle Premium
(16, 2, '2026-01-29 17:00:00'),
(16, 9, '2026-01-30 17:30:00'),

-- The Lord of the Rings - Salle Dolby Atmos
(9, 9, '2026-01-28 13:30:00'),
(9, 2, '2026-01-30 13:00:00'),

-- Fight Club - Salle Standard
(10, 3, '2026-01-29 21:00:00'),
(10, 8, '2026-01-30 22:00:00'),

-- Saving Private Ryan - Salle Premium
(13, 2, '2026-01-29 14:00:00'),
(13, 6, '2026-01-30 15:30:00'),

-- The Shawshank Redemption - Salle VIP
(1, 5, '2026-01-30 19:30:00'),

-- Goodfellas - Salle Standard
(8, 6, '2026-01-30 21:30:00'),

-- Dune: Part Two - Salle IMAX et Premium
(21, 4, '2026-01-28 12:00:00'),
(21, 4, '2026-01-29 13:00:00'),
(21, 2, '2026-01-30 14:00:00'),

-- Demon Slayer: Mugen Train - Salle Standard
(22, 1, '2026-01-28 14:30:00'),
(22, 3, '2026-01-29 16:30:00'),
(22, 8, '2026-01-30 13:30:00'),

-- Spirited Away - Salle Premium
(23, 2, '2026-01-28 11:00:00'),
(23, 9, '2026-01-29 12:00:00'),

-- Your Name - Salle Standard
(24, 3, '2026-01-28 18:00:00'),
(24, 6, '2026-01-29 17:00:00'),
(24, 1, '2026-01-30 15:00:00'),

-- Howl's Moving Castle - Salle Premium
(25, 2, '2026-01-28 13:00:00'),
(25, 9, '2026-01-30 11:00:00'),

-- Demon Slayer: To the Swordsmith Village - Salle Standard
(26, 3, '2026-01-28 20:00:00'),
(26, 8, '2026-01-29 14:00:00'),
(26, 1, '2026-01-30 17:00:00'),

-- Suzume - Salle Dolby Atmos
(27, 9, '2026-01-28 15:30:00'),
(27, 9, '2026-01-29 19:00:00'),
(27, 9, '2026-01-30 12:00:00'),

-- Princess Mononoke - Salle Premium
(28, 2, '2026-01-29 11:00:00'),
(28, 6, '2026-01-30 18:00:00'),

-- Weathering with You - Salle Standard
(29, 3, '2026-01-28 16:30:00'),
(29, 8, '2026-01-29 15:30:00'),

-- Jujutsu Kaisen 0 - Salle Standard et VIP
(30, 1, '2026-01-28 19:30:00'),
(30, 5, '2026-01-29 20:00:00'),
(30, 3, '2026-01-30 20:00:00'),

-- The Boy and the Heron - Salle Premium
(31, 2, '2026-01-28 10:00:00'),
(31, 9, '2026-01-29 10:30:00'),
(31, 2, '2026-01-30 10:00:00');
