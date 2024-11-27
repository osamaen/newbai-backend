<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BuildingResource;
class BuildingController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buildings = Building::get();
        return $this->okResponse(['buildings'=> [BuildingResource::collection($buildings)]]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(StoreBuildingRequest $request)
    {
        $rules = [
            'name' =>  'required',
            'location_details' => 'required',
            'address' =>  'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->unprocessableResponse($validator);
        }
        $new_doctor = Building::create([
            'name' => $request->name,
            'location_details' => $request->location_details,
            'address' => $request->address,
        ]);
        return $this->okResponse(null,'Saved Saccsesfull');
    }



    /**
     * Display the specified resource.
     */
    public function show(Building $building)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuildingRequest $request, Building $building)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        //
    }
}
