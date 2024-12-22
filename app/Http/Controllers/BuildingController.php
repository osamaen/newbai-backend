<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BuildingResource;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Validation\ValidationData;

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
        try {
            // تحديث بيانات المبنى بالبيانات الصحيحة
            $building->update($request->validated());
            // إعادة استجابة بنجاح مع البيانات المحدثة
            return $this->okResponse(['building' => $building], 'Building updated successfully');
        } catch (ValidationException  $e) {
            // إذا حدثت أخطاء تحقق من الصحة
            return $this->unprocessableResponse($e, 'Validation Error');
        } catch (Exception $e) {
            // لمعالجة أي أخطاء أخرى غير متوقعة
            return $this->errorResponse([], 'An unexpected error occurred', 500);
        }
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
{
    try {
        // التحقق مما إذا كان المبنى يحتوي على شقق
        if ($building->apartments()->count() > 0) {
            return $this->unprocessableResponse(
                null,
                'Building cannot be deleted because it has associated apartments.'
            );
        }

        // حذف المبنى إذا لم يكن يحتوي على شقق
        $building->delete();
        return $this->okResponse(null, 'Building deleted successfully');
    } catch (Exception $e) {
        return $this->unprocessableResponse($e->getMessage(), 'Failed to delete the building');
    }
}
}
