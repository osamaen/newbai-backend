<?php

namespace App\Http\Controllers;

use App\Models\ReservationType;
use App\Http\Requests\StoreReservationTypeRequest;
use App\Http\Requests\UpdateReservationTypeRequest;
use App\Http\Resources\ReservationTypeResource;

class ReservationTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation_type = ReservationType::get();
        return $this->okResponse(['reservation_type'=> [ReservationTypeResource::collection($reservation_type)]]);
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
    public function store(StoreReservationTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationType $reservationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationType $reservationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationTypeRequest $request, ReservationType $reservationType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationType $reservationType)
    {
        //
    }
}
