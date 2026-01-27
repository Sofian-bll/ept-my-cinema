CREATE DATABASE IF NOT EXISTS my_cinema;
USE my_cinema;

DROP TABLE IF EXISTS movies;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS screenings;

CREATE TABLE movies
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(100) NOT NULL,
    description  TEXT,
    duration     INT          NOT NULL COMMENT 'Duration in minutes',
    release_year INT          NOT NULL,
    genre        VARCHAR(200),
    director     VARCHAR(100),
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE rooms
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    capacity   INT          NOT NULL,
    type       VARCHAR(100),
    active     BOOLEAN      NOT NULL DEFAULT TRUE COMMENT 'Soft delete flag',
    created_at DATETIME              DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE screenings
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    movies_id  INT      NOT NULL,
    room_id    INT      NOT NULL,
    start_time DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movies_id) REFERENCES movies (id),
    FOREIGN KEY (room_id) REFERENCES rooms (id)
)