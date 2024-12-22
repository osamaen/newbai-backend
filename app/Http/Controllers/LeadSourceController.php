<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use App\Http\Requests\StoreLeadSourceRequest;
use App\Http\Requests\UpdateLeadSourceRequest;
use App\Http\Resources\LeadSourceResource;

class LeadSourceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leadSources = LeadSource::get();
        return $this->okResponse(['lead_sources'=> [LeadSourceResource::collection($leadSources)]]);
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
    public function store(StoreLeadSourceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LeadSource $leadSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeadSource $leadSource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadSourceRequest $request, LeadSource $leadSource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadSource $leadSource)
    {
        //
    }
}
