<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\BedSpace;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Http\Resources\BedSpacesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class RoomController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::get();
        return $this->okResponse(['rooms'=> [RoomResource::collection($rooms)]]);
    }

    public function getBedSpaces($id)
    {
        Log::info('Room ID: ' . $id);

        $room = Room::findOrFail($id); // Find room by ID
         $bedSpaces = $room->bed_spaces;  // Fetch the related bed spaces for the room
        return $this->okResponse(['bed_spaces'=> [BedSpacesResource::collection($bedSpaces)]]);
    }


    public function checkAvailability(Request $request)
    {
        // Validate incoming filters
        $validated = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'building_id' => 'nullable|integer',
            'apartment_id' => 'nullable|integer',
            'room_type_id' => 'nullable|integer',
            'has_balcony' => 'nullable|boolean',
            'has_bathroom' => 'nullable|boolean',
        ]);
    
        // استعلام الغرف المتاحة بناءً على تواريخ الحجز
        $query = Room::with(['room_type', 'apartment', 'bed_spaces'])
        ->whereDoesntHave('room_reservations.reservation', function($q) use ($request) {
            $q->where(function($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhere(function($q) use ($request) {
                        $q->where('check_in_date', '<=', $request->check_in_date)
                          ->where('check_out_date', '>=', $request->check_out_date);
                    });
            })
            ->where('is_checked_out', false);
        });

    // Apply filters
    if ($request->room_type_id) {
        $query->where('room_type_id', $request->room_type_id);
    }

    if ($request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->building_id) {
        $query->whereHas('apartment', function($q) use ($request) {
            $q->where('building_id', $request->building_id);
        });
    }
    
    if ($request->apartment_id) {
        $query->where('apartment_id', $request->apartment_id);
    }
    if ($request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->has_bathroom) {
        $query->where('has_bathroom', $request->has_bathroom);
    }

    if ($request->has_balcony) {
        $query->where('has_balcony', $request->has_balcony);
    }

    // For bedspace rooms
    if ($request->room_type_id == 3) { // Assuming 3 is bedspace type
        $query->whereHas('bed_spaces', function($q) use ($request) {
            $q->whereDoesntHave('bedspace_reservations.reservation', function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                        ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                        ->orWhere(function($q) use ($request) {
                            $q->where('check_in_date', '<=', $request->check_in_date)
                              ->where('check_out_date', '>=', $request->check_out_date);
                        });
                })
                ->where('is_checked_out', false);
            });
        });
    }

    return response()->json([
        'data' => [
            'rooms' => [$query->get()]
        ]
    ]);
    
     
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
     
    // Validate the input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required_if:room_type_id,!=,bed_space|nullable|numeric|min:0',
        'room_type_id' => 'required|exists:room_types,id',
        'apartment_id' => 'required|exists:apartments,id',
        'has_bathroom' => 'boolean',
        'has_balcony' => 'boolean',
        'bed_spaces' => 'nullable|array', // For bed spaces
        'bed_spaces.*.bed_number' => 'required_with:bed_spaces|string|max:20',
        'bed_spaces.*.position_description' => 'nullable|string',
        'bed_spaces.*.price' => 'required_with:bed_spaces|numeric|min:0',
    ]);

    try {
        // Create the room
        $room = Room::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'] ?? null, // Null for Bed Spaces
            'room_type_id' => $validatedData['room_type_id'],
            'apartment_id' => $validatedData['apartment_id'],
            'has_bathroom' => $validatedData['has_bathroom'] ?? false,
            'has_balcony' => $validatedData['has_balcony'] ?? false,
        ]);

        // Check if the room type is Bed Space
        if ($request->has('bed_spaces') && is_array($validatedData['bed_spaces'])) {
            foreach ($validatedData['bed_spaces'] as $bedSpace) {
                BedSpace::create([
                    'room_id' => $room->id,
                    'bed_number' => $bedSpace['bed_number'],
                    'position_description' => $bedSpace['position_description'] ?? null,
                    'price' => $bedSpace['price'],
                ]);
            }
        }
        return $this->okResponse(null,'Saved Saccsesfull');
    } catch (\Exception $e) {
        // return response()->json(['error' => '', 'details' => $e->getMessage()], 500);
        return $this->okResponse(null, $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
