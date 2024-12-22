<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Customer;
use App\Models\RoomReservation;
use App\Models\BedspaceReservation;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReservationController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::get();
        return $this->okResponse(['reservations' => [ReservationResource::collection($reservations)]]);
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
    // public function store(StoreReservationRequest $request)
    // {

    //     // التحقق من البيانات المدخلة في الحجز
    //     $validator = Validator::make($request->all(), [
    //         'room_id' => 'required|exists:rooms,id',
    //         'type_id' => 'required',
    //         'check_in_date' => 'required|date',
    //         'is_bedspace' => 'required|boolean',
    //         'check_out_date' => 'required|date|after_or_equal:check_in_date',

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // التحقق من بيانات العميل إذا كانت جديدة أو موجودة
    //     $customer = null;
    //     if ($request->has("customer_id")) {
    //         $customer = Customer::find($request->customer_id);
    //     } elseif ($request->has("customer")) {
    //         $customerValidator = Validator::make($request->customer, [
    //             'first_name' => 'required|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'nullable|email|unique:customers,email',
    //             'phone_number' => 'required|string|max:20',
    //             'nationalId' => 'nullable|string|max:20',
    //             'gender_id' => 'required|in:1,2', // 1 for Male, 2 for Female
    //         ]);

    //         if ($customerValidator->fails()) {
    //             return response()->json(['errors' => $customerValidator->errors()], 422);
    //         }
    //         $validatedData = [] ;

    //         if ($request->has('id_photo'))
    //             $validatedData['id_photo'] = $this->storeFile($request->file('id_photo'), 'id_photo');

    //         if ($request->has('passport_photo'))
    //             $validatedData['passport_photo'] = $this->storeFile($request->file('passport_photo'), 'passport_photo');

    //         // if ($request->has('personal_photo'))
    //         //     $validatedData['personal_photo'] = $this->storeFile($request->file('personal_photo'), 'personal_photo');

    //         // إنشاء العميل إذا لم يكن موجودًا
    //         $customer = Customer::create([
    //             'first_name' => $request->customer['first_name'],
    //             'last_name' => $request->customer['last_name'],
    //             'email' => $request->customer['email'],
    //             'phone_number' => $request->customer['phone_number'],
    //             'nationalId' => $request->customer['nationalId'],
    //             'gender_id' => $request->customer['gender_id'],
    //             'id_photo' =>  $validatedData['id_photo'],
    //             'passport_photo' =>  $validatedData['passport_photo'],



    //         ]);
    //     } else {

    //         return response()->json(['errors' => "customer required"], 422);
    //     }

    //     // إذا لم يكن العميل موجودًا نتحقق من صحة بيانات العميل الجديد


    //     // إنشاء الحجز
    //     $reservation = Reservation::create([
    //         'check_in_date' => $request->check_in_date,
    //         'check_out_date' => $request->check_out_date,
    //         'customer_id' => $customer->id,
    //         'type_id' => $request->type_id,
    //         'reservation_date' =>  now(),
    //         'user_id' => auth()->user()->id,
    //         'total_amount' => $request->total_amount, // تأكد من إرسال السعر في الطلب
    //     ]);

    //     if ($request->is_bedspace == 0) {
    //         RoomReservation::create([
    //             'reservation_id' => $reservation->id,
    //             'room_id' => $request->room_id,
    //             'price_at_booking' => $request->total_amount, 
    //         ]);
    //     }




    //     return response()->json([
    //         'message' => 'Reservation successfully created!',
    //         'booking' => $reservation,
    //     ], 201);
    // }



    public function store(StoreReservationRequest $request)
    {
        // Validate main reservation data
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'type_id' => 'required|integer',
            'check_in_date' => 'required|date',
            'is_bedspace' => 'required|boolean',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
        ]);

        if ($validator->fails()) {
            return $this->unprocessableResponse($validator);
        }

        // Handle customer validation and creation
        $customer = null;
        if ($request->has("customer_id")) {
            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                return $this->notFoundResponse(['customer_id' => ['Customer not found']], 'Customer not found');
            }
        } elseif ($request->has("customer")) {
            $customerValidator = Validator::make($request->customer, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'phone_number' => 'required|string|max:20',
                'nationalId' => 'nullable|string|max:20',
                'gender_id' => 'required|in:1,2',
            ]);

            if ($customerValidator->fails()) {
                return $this->unprocessableResponse($customerValidator->errors(), 'Invalid customer data');
            }

            $validatedData = [];
            if ($request->has('id_photo')) {
                $validatedData['id_photo'] = $this->storeFile($request->file('id_photo'), 'id_photo');
            }
            if ($request->has('passport_photo')) {
                $validatedData['passport_photo'] = $this->storeFile($request->file('passport_photo'), 'passport_photo');
            }

            try {
                $customer = Customer::create([
                    'first_name' => $request->customer['first_name'],
                    'last_name' => $request->customer['last_name'],
                    'email' => $request->customer['email'],
                    'phone_number' => $request->customer['phone_number'],
                    'nationalId' => $request->customer['nationalId'],
                    'gender_id' => $request->customer['gender_id'],
                    'id_photo' => $validatedData['id_photo'] ?? null,
                    'passport_photo' => $validatedData['passport_photo'] ?? null,
                ]);
            } catch (\Exception $e) {
                return $this->errorResponse(['database' => ['Failed to create customer']], 'Customer creation failed');
            }
        } else {
            return $this->badRequestResponse(['customer' => ['Customer information is required']], 'Missing customer data');
        }

        try {
            // Create reservation
            $reservation = Reservation::create([
                'status_id' => 1,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'customer_id' => $customer->id,
                'type_id' => $request->type_id,
                'reservation_date' => now(),
                'user_id' => auth()->user()->id,
                'total_amount' => $request->total_amount,
            ]);

            if ($request->is_bedspace == 0) {
                RoomReservation::create([
                    'reservation_id' => $reservation->id,
                    'room_id' => $request->room_id,
                    'price_at_booking' => $request->total_amount,
                ]);
            }



            return $this->okResponse(['reservation' => new ReservationResource($reservation)], "Reservation successfully created!");
        } catch (\Exception $e) {
            return $this->errorResponse(['database' => ['Failed to create reservation']], $e->getMessage());
        }
    }


    public function updateStatus(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'status_id' => 'required|integer', // Adjust based on your status column
        ]);

        try {
            // Find the reservation by ID
            $reservation = Reservation::find($validatedData['reservation_id']);

            // Update the status
            $reservation->status_id = $validatedData['status_id'];
            $reservation->save();

            // Return success response
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Reservation status updated successfully',
            //     'data' => new ReservationResource($reservation),
            // ], 200);

            return $this->okResponse(['reservation' => new ReservationResource($reservation)], "Reservation status updated successfully");


        } catch (\Exception $e) {
            return $this->errorResponse(
                [
                    'error' => ['Failed to update reservation status']
                ],
                $e->getMessage()
            );
        }
    }
    public function todayCheckIn(Request $request)
    {
        $buildingId = $request->input('building_id');
        $apartmentId = $request->input('apartment_id');
        $roomTypeId = $request->input('room_type_id');

        $reservations = Reservation::with([
            'room_reservations.room.apartment.building',
            'bedspace_reservations.bed_space.room.apartment.building'
        ])
            ->whereDate('check_in_date', today())
            ->when($buildingId, function ($query, $buildingId) {
                return $query->whereHas('room_reservations.room.apartment.building', function ($q) use ($buildingId) {
                    $q->where('building_id', $buildingId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.apartment', function ($q) use ($buildingId) {
                        $q->where('building_id', $buildingId);
                    });
            })
            ->when($apartmentId, function ($query, $apartmentId) {
                return $query->whereHas('room_reservations.room.apartment', function ($q) use ($apartmentId) {
                    $q->where('id', $apartmentId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.apartment', function ($q) use ($apartmentId) {
                        $q->where('id', $apartmentId);
                    });
            })
            ->when($roomTypeId, function ($query, $roomTypeId) {
                return $query->whereHas('room_reservations.room.room_type', function ($q) use ($roomTypeId) {
                    $q->where('id', $roomTypeId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.room_type', function ($q) use ($roomTypeId) {
                        $q->where('id', $roomTypeId);
                    });
            })
            ->get();
        // return $this->okResponse(['reservations'=> $reservations]);
        return $this->okResponse(['reservations' => [ReservationResource::collection($reservations)]]);
    }
    public function todayCheckOut(Request $request)
    {
        $buildingId = $request->input('building_id');
        $apartmentId = $request->input('apartment_id');
        $roomTypeId = $request->input('room_type_id');

        $reservations = Reservation::with([
            'room_reservations.room.apartment.building',
            'bedspace_reservations.bed_space.room.apartment.building'
        ])
            ->whereDate('check_out_date', today())
            ->when($buildingId, function ($query, $buildingId) {
                return $query->whereHas('room_reservations.room.apartment.building', function ($q) use ($buildingId) {
                    $q->where('building_id', $buildingId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.apartment', function ($q) use ($buildingId) {
                        $q->where('building_id', $buildingId);
                    });
            })
            ->when($apartmentId, function ($query, $apartmentId) {
                return $query->whereHas('room_reservations.room.apartment', function ($q) use ($apartmentId) {
                    $q->where('id', $apartmentId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.apartment', function ($q) use ($apartmentId) {
                        $q->where('id', $apartmentId);
                    });
            })
            ->when($roomTypeId, function ($query, $roomTypeId) {
                return $query->whereHas('room_reservations.room.room_type', function ($q) use ($roomTypeId) {
                    $q->where('id', $roomTypeId);
                })
                    ->orWhereHas('bedspace_reservations.bed_space.room.room_type', function ($q) use ($roomTypeId) {
                        $q->where('id', $roomTypeId);
                    });
            })
            ->get();
        // return $this->okResponse(['reservations'=> $reservations]);
        return $this->okResponse(['reservations' => [ReservationResource::collection($reservations)]]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }


    public function storeFile($file, $path, $old = null)
    {
        if ($old !== null) {
            Storage::delete($old);
        }
        $fileStorage = Storage::disk('local')->put($path, $file);
        return $fileStorage;
    }
}
