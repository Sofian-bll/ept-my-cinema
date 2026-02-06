<?php

namespace App\Models;

use App\Core\Model;

class Movies extends Model
{
    /**
     *### PROPERTIES
     */

    protected static string $table = 'movies';
    protected static array $ignored = ['relatedScreenings'];
    protected ?string $title;
    protected ?string $description;
    protected ?int $duration;
    protected ?int $release_year;
    protected ?string $genre;
    protected ?string $director;
    protected ?string $created_at;
    protected ?string $updated_at;

    protected ?array $relatedScreenings;


    /** ### GETTER AND SETTER*/

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

    /**
     * @return array
     */
    public function getRelatedScreenings(): ?array
    {
        // TODO Connect Screenings table return array with screenings object or empty array (false)
        return $this->relatedScreenings;
    }
}