<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        return $this->okResponse(['users' => [UserResource::collection($users)]]);
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_number' => 'required|string|max:255',
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'personal_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'required|string|max:255|unique:users',
            'hire_date' => 'required|date',
            'password' => 'required|string|min:8',
        ]);

        try {

            if ($request->has('id_photo'))
                $validatedData['id_photo'] = $this->storeFile($request->file('id_photo'), 'id_photo');

            if ($request->has('passport_photo'))
                $validatedData['passport_photo'] = $this->storeFile($request->file('passport_photo'), 'passport_photo');

            if ($request->has('personal_photo'))
                $validatedData['personal_photo'] = $this->storeFile($request->file('personal_photo'), 'personal_photo');



            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'user_name' => $validatedData['user_name'],
                'email' => $validatedData['email'],
                'id_photo' => $validatedData['id_photo'],
                'id_number' => $validatedData['id_number'],
                'passport_photo' => $validatedData['passport_photo'],
                'personal_photo' =>  $validatedData['personal_photo'],
                'phone_number' => $validatedData['phone_number'],
                'hire_date' => $validatedData['hire_date'],
                'password' => bcrypt($validatedData['password']),
            ]);

            return $this->createdResponse($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to create user');
        }
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
    public function update(Request $request, RoomType $roomType)
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


        $validate = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:5',
        ]);


        if ($validate->fails()) {
            return $this->unprocessableResponse($validate);
        }




        if (auth('admin')->attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            $user = auth('admin')->user();


            $user->tokens()->delete();

            $token = $user->createToken('MyApp', ['admin'])->plainTextToken;

            return $this->okResponse(['token' => $token, 'user' => $user], null);
        } else {
            // throw new BadRequestException('كلمة المرور او رقم الجوال خطأ');
            return $this->unauthorizedResponse(null, 'كلمة المرور او اسم المستخدم  خطأ');
        }
    }



    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->currentAccessToken()->delete();
            return $this->successResponse([], 200, 'Logged out successfully');
        } else {
            return $this->errorResponse([], 'User not authenticated', 401);
        }
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
