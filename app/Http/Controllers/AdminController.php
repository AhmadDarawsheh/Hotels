<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmployeeRequest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //

    public function createHotel(Request $request)
    {

        $hotel = new Hotel();
        $hotel->name = $request->input('name');
        $hotel->address = $request->input('address');

        $hotel->save();

        $hotel->creators()->attach(auth()->user()->id, [
            'role' => 'creator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json($hotel, 201);
    }

    public function deleteHotel(Request $request, string $id)
    {

        $hotel = Hotel::find($id);

        Log::info($hotel);

        if (!$hotel) {
            return response()->json(["Hotel doesn't exists"]);
        }

        $hotel->delete();

        return response()->json(['message' => 'Hotel has been deleted successfully', $hotel]);
    }



    public function addEmployeeToHotel(AddEmployeeRequest $request, string $id)
    {




        if (auth()->user()->type !== 'employee') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $employee = User::where('email', $request->safe()->only('email'))->first();
        if (!$employee || !$employee->isEmployee()) {
            return response()->json(['message' => 'Employee not found or not an employee type'], 404);
        }

        if ($employee->hotels()->where('hotel_id', $id)->exists()) {
            return response()->json(['message' => 'Employee is already assigned to a hotel', 400]);
        }

        $employee->hotels()->attach($id, [
            'role' => 'employee',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return response()->json(['message' => 'Employee added to hotel successfully']);
    }


    public function deleteEmployeeFromHotel(AddEmployeeRequest $request, string $id)
    {

        if (auth()->user()->type !== 'employee') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $employee = User::where('email', $request->safe()->only('email'))->first();
        if (!$employee || !$employee->isEmployee()) {
            return response()->json(['message' => 'Employee not found or not an employee type'], 404);
        }

        if (!$employee->hotels()->where('hotel_id', $id)->exists()) {
            return response()->json(['message' => 'Employee not found!', 400]);
        }

        $employee->hotels()->detach($id);

        return response()->json(['message' => 'Employee deleted from hotel successfully']);
    }


    // public function getHotels()
    // {
    //     $hotels = Hotel::select('name', 'address')->get();

    //     return response()->json(['Hotels' => $hotels]);
    // }


    public function searchHotels(Request $request)
    {
        $searchQuery = $request->input('q');

        if ($searchQuery) {
            $hotels = Hotel::where('name', 'LIKE', '%' . $searchQuery . '%')
                ->select('name', 'address','id')
                ->get();
        } else {
            $hotels = Hotel::select('name', 'address','id')->get();
        }

        return response()->json(['hotels' => $hotels]);
    }
}
