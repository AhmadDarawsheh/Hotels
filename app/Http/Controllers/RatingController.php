<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddingRatingRequest;
use App\Models\Rating;
use App\Models\Room;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function addRating(AddingRatingRequest $request, $roomId)
    {
        $room = Room::findOrFail($roomId);

        $rating = new Rating();
        $rating->room_id = $room->id;
        $rating->price = $request->input('price');
        $rating->start_date = $request->input('start_date');
        $rating->end_date = $request->input('end_date');
        $rating->save();

        return response()->json(['message' => 'Rating added successfully', 'rating' => $rating], 201);
    }

    public function removeRating($roomId, $ratingId)
    {

        $room = Room::findOrFail($roomId);

        $rating = $room->ratings()->where('id', $ratingId)->first(); //finding the rating that belongs to specified room

        if (!$rating) {
            return response()->json(['message' => 'Rating not found for this room'], 404);
        }

        $rating->delete();


        return response()->json(['message' => 'Rating deleted successfully'], 200);
    }

    public function getRatings($roomId)
    {
        $room = Room::findOrFail($roomId);
        $ratings = $room->ratings()->select('id', 'room_id', 'price', 'start_date','end_date')->get();

        if (!$ratings) {
            return response()->json(['message' => 'Rating not found for this room'], 404);
        }


        return response()->json((['message' => "Ratings retrived successfully. ", 'ratings' => $ratings]));
    }
}
