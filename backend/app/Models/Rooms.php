<?php

namespace App\Models;

use App\Core\Model;
use App\Traits\SoftDeleteTrait;

class Rooms extends Model
{
    use SoftDeleteTrait;

    protected static string $table = 'rooms';
    protected ?string $name;
    protected ?int $capacity;
    protected ?string $type;
    protected ?bool $active;
    protected ?string $created_at;
    protected ?string $updated_at;

    //region Getter & Setter

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    /**
     * @param int|null $capacity
     */
    public function setCapacity(?int $capacity): void
    {
        $this->capacity = $capacity;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     */
    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
    //endregion

    public function hasScreenings(): bool
    {
        $database = self::getConnection();

        $sql  = 'SELECT id FROM screenings WHERE room_id = :id LIMIT 1';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);
        $column = $stmt->fetchColumn();

        return $column !== false;
    }

    public function getScreenings(): array
    {
        $database = self::getConnection();

        $sql  = 'SELECT * FROM screenings WHERE room_id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Screenings::class);
    }

    public function getMovies(): array
    {
        $database = self::getConnection();

        $sql  = 'SELECT DISTINCT m.* FROM movies m JOIN screenings s ON s.movies_id = m.id WHERE s.room_id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, Movies::class);
    }
}