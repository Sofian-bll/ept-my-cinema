<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Rooms;
use JetBrains\PhpStorm\NoReturn;

class RoomsController extends Controller
{
    #[NoReturn]
    public function list(): void // GET //
    {
        $this->jsonResponse(Rooms::all());
    }

    #[NoReturn]
    public function show($id): void // GET //
    {
        $room = Rooms::find($id);

        if (!$room) {
            $this->error('Room not found', 404);
        }

        $this->jsonResponse($room);
    }

    #[NoReturn]
    public function create(): void
    {
        $data  = $this->getJsonBody();
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
        $room = Rooms::find($id);
        if (!$room) {
            $this->error('Room not found', 404);
        }

        $data = $this->getJsonBody();


        $room->setName($data['name'] ?? $room->getName());
        $room->setCapacity($data['capacity'] ?? $room->getCapacity());
        $room->setType((bool)$data['type'] ?? $room->getType());
        $room->setActive($data['active'] ?? $room->getActive());
        $room->save();

        $this->jsonResponse('Room updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $room = Rooms::find($id);
        if (!$room) {
            $this->error('Room not found', 404);
        }
        $room->delete();
        $this->jsonResponse('Room deleted');
    }
}