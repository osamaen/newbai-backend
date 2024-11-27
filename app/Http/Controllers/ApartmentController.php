<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use Illuminate\Support\Facades\Validator;

class ApartmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apartments = Apartment::get();
        return $this->okResponse(['apartments'=> [ApartmentResource::collection($apartments)]]);
    }
    public function getApartments($id)
    {
        
        try {
            $apartments = Apartment::where('building_id', $id)->get();
            return $this->okResponse(['apartments'=> [ApartmentResource::collection($apartments)]]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching apartments: ' . $e->getMessage(),
            ], 500);
        }
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
    public function store(StoreApartmentRequest $request)
    {
        $rules = [
            'name' =>  'required',
            'building_id' => 'required',
            'floor_number' => 'required',
            'total_rooms' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->unprocessableResponse($validator);
        }
        $new_doctor = Apartment::create([
            'name' => $request->name,
            'building_id' => $request->building_id,
            'floor_number' => $request->floor_number,
            'total_rooms' => $request->total_rooms,
        ]);
        return $this->okResponse(null,'Saved Saccsesfull');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        //
    }
}
