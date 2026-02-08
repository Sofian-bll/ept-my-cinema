<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ValidationHelper;
use App\Models\Rooms;
use JetBrains\PhpStorm\NoReturn;

class RoomsController extends Controller
{
    #[NoReturn]
    public function list(): void // GET //
    {
        $this->jsonResponse(Rooms::allActive());
    }

    #[NoReturn]
    public function show($id): void // GET //
    {
        $room = $this->findOrFail(Rooms::class, $id, 'Room not found');
        $this->jsonResponse($room);
    }

    #[NoReturn]
    public function create(): void
    {
        $data = $this->getJsonBody();

        $missing = ValidationHelper::required($data, [ 'name', 'capacity', 'type', 'active' ]);
        if (!empty($missing)) {
            $this->error('Missing required fields', 400, [ 'fields' => $missing ]);
        }

        $room = new Rooms();
        $room->setName($data['name']);
        $room->setCapacity($data['capacity']);
        $room->setType((bool)$data['type']);
        $room->setActive($data['active']);
        $room->save();

        $this->jsonResponse('Room created', 201);
    }

    #[NoReturn]
    public function update($id): void // PUT //
    {
        $room = $this->findOrFail(Rooms::class, $id, 'Room not found');

        $data = $this->getJsonBody();


        $room->setName($data['name'] ?? $room->getName());
        $room->setCapacity($data['capacity'] ?? $room->getCapacity());
        $room->setType((bool)($data['type'] ?? $room->getType()));
        $room->setActive($data['active'] ?? $room->getActive());
        $room->save();

        $this->jsonResponse('Room updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $room = $this->findOrFail(Rooms::class, $id, 'Room not found'); // renvoie erreur http si fail
        if (!$room->getActive()) {
            $this->error('Room is already inactive', 410);
        }

        if ($room->hasScreenings()) {
            $this->error('Cannot delete room with existing screenings', 409);
        }

        $room->softDelete();
        // $room->delete();
        $this->jsonResponse('Room deleted');
    }
}