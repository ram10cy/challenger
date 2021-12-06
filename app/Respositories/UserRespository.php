<?php


namespace App\Respositories;


use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRespository
{

    public function userAuth($credentials){
        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }

        //Request is validated
        //Create token


        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
                'error' =>"$e"
            ], 500);
        }


        //Token created, return with success response and jwt token
        return response()->json([
            'success'=>true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function store($userData){
        //Validate data

        $validator = Validator::make($userData, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'address'=>'required|string'
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }
        //Request is valid, create new user
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'address'=>$userData['address'],
            'password' => bcrypt($userData['password'])
        ]);
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);

    }
    public function info(){
        return response()->json([
            'success'=>true,
            'user' => auth()->user()]);
    }
    public function logout($userData){

        try {
            auth()->logout();;
            JWTAuth::invalidate($userData);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
