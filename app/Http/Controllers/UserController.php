<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:255',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) return errorResponse($validator->errors()->first(), 400);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
        ]);

        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }

    public function login(Request $request) {
        $credentials = request(['email', 'password']);

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) return errorResponse($validator->errors()->first(), 400);


        if (! $token = auth()->attempt($credentials)) {
            return errorResponse('Unauthorized', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $user = auth('api')->user();
        if (!$user) return errorResponse('Unauthorized', 401);

        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(new UserResource($user), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
