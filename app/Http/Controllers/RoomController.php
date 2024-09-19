<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Hotel;
use App\Models\Rating;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function addRoom(RoomRequest $request, $hotelId)
    {



        $room = new Room();
        $room->hotel_id = $hotelId;
        $room->type = $request->input('type');
        $room->available_rooms = $request->input('available_rooms');

        $room->save();

        return response()->json(['message' => 'Room added successfully'], 201);
    }


    public function removeRoom(Request $request, $hotelId, $roomId)
    {
        $room = Room::where('id', $roomId)->where('hotel_id', $hotelId)->firstOrFail();

        $room->delete();


        return response()->json(['message' => 'Room deleted successfully'], 200);
    }

    public function getRooms($hotelId)
    {
        $rooms = Room::select('type', 'available_rooms')->where('hotel_id', $hotelId)->get();

        return response()->json(['Rooms:' => $rooms]);
    }
}
