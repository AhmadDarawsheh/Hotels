<?php

namespace App\Http\Controllers;

use App\Events\ReservationEvent;
use App\Http\Requests\ReservationRequest;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

use function PHPUnit\Framework\isNull;

class ReservationController extends Controller
{
    protected function overlappedRatings($startDate, $endDate, $ratings)
        {
            $appliedRatings = [];
            $totalPrice = 0;


            if ($ratings->isEmpty()) {
                return [$appliedRatings, $totalPrice];
            }

            $rating = $ratings->first();
            $ratingStartDate = $rating->start_date ? Carbon::parse($rating->start_date) : null;
            $ratingEndDate = $rating->end_date ? Carbon::parse($rating->end_date) : null;


            if (($ratingStartDate <= $endDate && $ratingEndDate >= $startDate) || (is_null($ratingStartDate) && is_null($ratingEndDate))) {
                $overlappedStartDate = max($startDate, $ratingStartDate ?? $startDate);
                $overlappedEndDate = min($endDate, $ratingEndDate ?? $endDate);

                $overlappedRatingDays = $overlappedStartDate->diffInDays($overlappedEndDate) + 1;

                $totalPrice += $overlappedRatingDays * $rating->price;

                $appliedRatings[] = [
                    'rating_id' => $rating->id,
                    'rating_start_date' => $overlappedStartDate->toDateString(),
                    'rating_end_date' => $overlappedEndDate->toDateString(),

                ];

                if ($overlappedStartDate->greaterthan($startDate)) {
                    [$leftAppliedRating, $leftTotalPrice] =  $this->overlappedRatings($startDate, $overlappedStartDate->subDay(), $ratings->slice(1));
                    $appliedRatings = array_merge($appliedRatings, $leftAppliedRating);
                    $totalPrice += $leftTotalPrice;
                }

                if ($overlappedEndDate->lessthan($endDate)) {
                    [$rightAppliedRating, $rightTotalPrice] = $this->overlappedRatings($overlappedEndDate->addDay(), $endDate, $ratings->slice(1));
                    $appliedRatings = array_merge($appliedRatings, $rightAppliedRating);
                    $totalPrice += $rightTotalPrice;
                }
            }
            return [$appliedRatings, $totalPrice];
        }



    public function createReservation(ReservationRequest $request, $roomId, $hotelId)
    {
        $user = auth()->user();
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json(['message' => 'Please choose the correct hotel!']);
        }

        $room = Room::findOrFail($roomId);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $reservationCount = Reservation::where('room_id', $roomId)->where(function ($query) use ($startDate, $endDate) {
            $query->where('start_date', '<', $endDate)
                ->where('end_date', '>', $startDate);
        })->count(); // check the room counts within the reservation dates 
        if ($reservationCount >= $room->available_rooms) {
            return response()->json(['message' => 'No rooms available all reserved!']);
        }


        $ratings = $room->ratings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                })
                    ->orWhere(function ($query) {
                        $query->whereNull('start_date')
                            ->whereNull('end_date');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();


        [$appliedRatings, $totalPrice] = $this->overlappedRatings($startDate, $endDate, $ratings);

        Logger([$appliedRatings, $totalPrice]);

        $reservation = new Reservation();
        $reservation->user_id = $user->id;
        $reservation->room_id = $room->id;
        $reservation->start_date = $request->input('start_date');
        $reservation->end_date = $request->input('end_date');
        $reservation->price = $totalPrice;
        $reservation->save();

        foreach ($appliedRatings as $rating) {
            $reservation->ratings()->attach($rating['rating_id'], [
                'rating_start_date' => $rating['rating_start_date'],
                'rating_end_date' => $rating['rating_end_date']
            ]);
        }


        ReservationEvent::dispatch($reservation, $user);

        return response()->json(['message' => 'Your reservation has been created successfully', 'reservation' => $reservation], 201);
    }
}
