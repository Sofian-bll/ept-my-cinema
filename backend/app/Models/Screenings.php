<?php

namespace App\Models;

use App\Core\Model;
use App\Helpers\DateHelper;

class Screenings extends Model
{
    protected static string $table = 'screenings';
    protected ?int $movies_id = null;
    protected ?int $room_id = null;
    protected ?string $start_time = null;
    protected ?string $end_time = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;

    //region Getters & Setters

    /**
     * @return int|null
     */
    public function getMoviesId(): ?int
    {
        return $this->movies_id;
    }

    /**
     * @param int|null $movies_id
     */
    public function setMoviesId(?int $movies_id): void
    {
        $this->movies_id = $movies_id;
    }

    /**
     * @return int|null
     */
    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    /**
     * @param int|null $room_id
     */
    public function setRoomId(?int $room_id): void
    {
        $this->room_id = $room_id;
    }

    /**
     * @return int|null
     */
    public function getStartTime(): ?string
    {
        return $this->start_time;
    }

    /**
     * @param int|null $start_time
     */
    public function setStartTime(?string $start_time): void
    {
        $this->start_time = $start_time;
    }

    /**
     * @return string|null
     */
    public function getEndTime(): ?string
    {
        return $this->end_time;
    }

    /**
     * @param string|null $end_time
     */
    public function setEndTime(?string $end_time): void
    {
        $this->end_time = $end_time;
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


    /**
     * @throws \DateMalformedStringException
     */
    public static function hasOverlap(int $roomId, string $startTime, int $movieId): bool

    {
        $movie         = Movies::find($movieId);
        $movieDuration = $movie->getDuration();

        $endTime = DateHelper::addMinutes($startTime, $movieDuration);

        // SQL REQUEST
        $database = self::getConnection();

        $sql  = <<<SQL
            SELECT COUNT(*)
            FROM screenings
            WHERE room_id = :room_id
              AND :new_start < end_time
              AND :new_end > start_time
            SQL;
        $stmt = $database->prepare($sql);
        $stmt->execute([ ':room_id' => $roomId, ':new_start' => $startTime, ':new_end' => $endTime ]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

}