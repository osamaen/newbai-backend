<?php

namespace App\Http\Controllers;

use App\Models\ReservationStatus;
use App\Http\Requests\StoreReservationStatusRequest;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Http\Resources\ReservationStatusResource;
use Illuminate\Support\Facades\Validator;

class ReservationStatusController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation_statuses = ReservationStatus::get();
        return $this->okResponse(['reservation_statuses'=> [ReservationStatusResource::collection($reservation_statuses)]]);
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
    public function store(StoreReservationStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationStatus $reservationStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationStatus $reservationStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationStatusRequest $request, ReservationStatus $reservationStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationStatus $reservationStatus)
    {
        //
    }
}
