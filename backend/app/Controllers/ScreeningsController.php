<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\DateHelper;
use App\Models\Movies;
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
        $screenings = Screenings::find($id);

        if (!$screenings) {
            $this->error('Screenings not found', 404);
        }

        $this->jsonResponse($screenings);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[NoReturn]
    public function create(): void
    {
        $data       = $this->getJsonBody();

        if (Screenings::hasOverlap($data['room_id'], $data['start_time'], $data['movies_id'])) {
            $this->error('Screening overlaps with existing screening in this room', 409);
        }

        $screenings = new Screenings();

        $screenings->setMoviesId($data['movies_id']);
        $screenings->setRoomId($data['room_id']);
        $screenings->setStartTime($data['start_time']);

        // End Time Gestion
        $movie = Movies::find($data['movies_id']);
        $endTime = DateHelper::addMinutes($data['start_time'], $movie->getDuration());
        $screenings->setEndTime($endTime);

        $screenings->save();

        $this->jsonResponse('Screenings created', 201);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[NoReturn]
    public function update($id): void // PUT //
    {
        $screenings = Screenings::find($id);

        if (!$screenings) {
            $this->error('Screenings not found', 404);
        }

        $data = $this->getJsonBody();

        if (Screenings::hasOverlap($data['room_id'], $data['start_time'], $data['movies_id'])) {
            $this->error('Screening overlaps with existing screening in this room', 409);
        }


        $screenings->setMoviesId($data['movies_id'] ?? $screenings->getMoviesId());
        $screenings->setRoomId($data['room_id'] ?? $screenings->getRoomId());
        $screenings->setStartTime($data['start_time'] ?? $screenings->getStartTime());

        // End Time Gestion
        $movie = Movies::find($data['movies_id']);
        $endTime = DateHelper::addMinutes($data['start_time'], $movie->getDuration());
        $screenings->setEndTime($endTime);

        $screenings->save();

        $this->jsonResponse('Screenings updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $screenings = Screenings::find($id);
        if (!$screenings) {
            $this->error('Screenings not found', 404);
        }
        $screenings->delete();
        $this->jsonResponse('Screenings deleted');
    }
}