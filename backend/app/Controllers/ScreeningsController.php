<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\DateHelper;
use App\Helpers\ValidationHelper;
use App\Models\Movies;
use App\Models\Rooms;
use App\Models\Screenings;
use JetBrains\PhpStorm\NoReturn;

class ScreeningsController extends Controller
{
    #[NoReturn]
    public function list(): void // GET //
    {
        $this->jsonResponse(Screenings::all());
    }

    #[NoReturn]
    public function show($id): void // GET //
    {
        $screening = $this->findOrFail(Screenings::class, $id, 'Screening not found');
        $this->jsonResponse($screening);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[NoReturn]
    public function create(): void
    {
        $data = $this->getJsonBody();

        $missing = ValidationHelper::required($data, ['room_id', 'start_time', 'movies_id']);
        if (!empty($missing)) {
            $this->error('Missing required fields', 400, ['fields' => $missing]);
        }

        $movie = $this->findOrFail(Movies::class, $data['movies_id'], 'Movie not found');
        $this->findOrFail(Rooms::class, $data['room_id'], 'Room not found');

        if (Screenings::hasOverlap($data['room_id'], $data['start_time'], $data['movies_id'])) {
            $this->error('Screening overlaps with existing screening in this room', 409);
        }

        $screenings = new Screenings();
        $screenings->setMoviesId($data['movies_id']);
        $screenings->setRoomId($data['room_id']);
        $screenings->setStartTime($data['start_time']);

        $endTime = DateHelper::addMinutes($data['start_time'], $movie->getDuration());
        $screenings->setEndTime($endTime);

        $screenings->save();

        $this->jsonResponse('Screenings created', 201);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[NoReturn]
    public function update($id): void
    {
        $screenings = $this->findOrFail(Screenings::class, $id, 'Screening not found');

        $data = $this->getJsonBody();

        $missing = ValidationHelper::required($data, ['room_id', 'start_time', 'movies_id']);
        if (!empty($missing)) {
            $this->error('Missing required fields', 400, ['fields' => $missing]);
        }

        $movie = $this->findOrFail(Movies::class, $data['movies_id'], 'Movie not found');
        $this->findOrFail(Rooms::class, $data['room_id'], 'Room not found');

        if (Screenings::hasOverlap($data['room_id'], $data['start_time'], $data['movies_id'])) {
            $this->error('Screening overlaps with existing screening in this room', 409);
        }

        $screenings->setMoviesId($data['movies_id']);
        $screenings->setRoomId($data['room_id']);
        $screenings->setStartTime($data['start_time']);

        $endTime = DateHelper::addMinutes($data['start_time'], $movie->getDuration());
        $screenings->setEndTime($endTime);

        $screenings->save();

        $this->jsonResponse('Screenings updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $screening = $this->findOrFail(Screenings::class, $id, 'Screening not found');
        $screening->delete();
        $this->jsonResponse('Screening deleted');
    }
}