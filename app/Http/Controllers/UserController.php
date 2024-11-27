<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreRoomTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $roomType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $roomType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $roomType)
    {
        //
    }

    public function login(Request $request)
    {
        
  
        $validate = Validator::make($request->all(),[
            'user_name'=> 'required',
            'password'=> 'required|min:5',
        ]);


        if($validate->fails())
        {
            return $this->unprocessableResponse($validate);
        }



        
        if (auth('admin')->attempt(['user_name'=>$request->user_name , 'password' => $request->password])) {
                $user = auth('admin')->user();


            $user->tokens()->delete();
            
            $token = $user->createToken('MyApp',['admin'])->plainTextToken;

            return $this->okResponse(['token'=>$token , 'user'=> $user],null);
              
        }else{
            // throw new BadRequestException('كلمة المرور او رقم الجوال خطأ');
            return $this->unauthorizedResponse(null,'كلمة المرور او اسم المستخدم  خطأ');
        }
    }
}
