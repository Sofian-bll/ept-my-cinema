<?php

namespace App\Models;

use App\Core\Model;

class Movies extends Model
{
    /**
     *### PROPERTIES
     */

    protected static string $table = 'movies';
    protected ?string $title;
    protected ?string $description;
    protected ?int $duration;
    protected ?int $release_year;
    protected ?string $genre;
    protected ?string $director;
    protected ?string $created_at;
    protected ?string $updated_at;

    //region Getters & Setters

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     */
    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return int|null
     */
    public function getReleaseYear(): ?int
    {
        return $this->release_year;
    }

    /**
     * @param int|null $release_year
     */
    public function setReleaseYear(?int $release_year): void
    {
        $this->release_year = $release_year;
    }

    /**
     * @return string|null
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * @param string|null $genre
     */
    public function setGenre(?string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return string|null
     */
    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * @param string|null $director
     */
    public function setDirector(?string $director): void
    {
        $this->director = $director;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    //endregion


    public function hasScreenings(): bool
    {
        $database = self::getConnection();

        $sql  = "SELECT id FROM screenings WHERE movies_id = :id LIMIT 1";
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);
        $column = $stmt->fetchColumn();

        return $column !== false;
    }

    public function getScreenings(): array
    {
        $database = self::getConnection();

        $sql  = 'SELECT * FROM screenings WHERE movies_id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Screenings::class);
    }

    public function getRooms(): array
    {
        $database = self::getConnection();

        $sql  = 'SELECT DISTINCT r.* FROM rooms r JOIN screenings s ON s.room_id = r.id WHERE s.movies_id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Rooms::class);
    }
}