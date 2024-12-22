<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CustomerResource;

class CustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::get();
        return $this->okResponse(['customers' => [CustomerResource::collection($customers)]]);
    }



    public function getImage(Request $request)
    {
        $user = auth()->user();

        if ($request->has('type')) {
            $type = $request->type;
            if ($user->$type !== null && Storage::exists($user->$type))
                return Storage::get($user->$type);
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
    public function store(StoreCustomerRequest $request)
    {

        $validatedData = $request->validated();

        if ($request->has('id_photo'))
            $validatedData['id_photo'] = $this->storeFile($request->file('id_photo'), 'id_photo');

        if ($request->has('passport_photo'))
            $validatedData['passport_photo'] = $this->storeFile($request->file('passport_photo'), 'passport_photo');



        try {
            Customer::create($validatedData);
        } catch (\Exception $e) {
            return $this->errorResponse(
                [
                    'database' =>
                    ['Failed to create customer']
                ],
                $e->getMessage()
            );
        }
    }



    public function search(Request $request)
    {

        $searchTerm = $request->input('search'); // استلام مصطلح البحث من الطلب

        if (!$searchTerm) {
            return response()->json(['message' => 'Please provide a search term'], 400);
        }

        // البحث عن العملاء بالاسم أو رقم الهاتف
        $customers = Customer::where('first_name', 'like', "%{$searchTerm}%")
            ->orWhere('last_name', 'like', "%{$searchTerm}%")
            ->orWhere('phone_number', 'like', "%{$searchTerm}%")
            ->limit(10) // تحديد عدد النتائج إلى 10
            ->get();

        return $this->okResponse(['customers' => [CustomerResource::collection($customers)]]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
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
