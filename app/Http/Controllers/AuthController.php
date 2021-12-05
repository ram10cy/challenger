<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JwtMiddleware;
use App\Respositories\UserRespository;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * @var UserRespository
     */
    private $userRespository;
    public function __construct(UserRespository $userRespository)
    {
        $this->userRespository = $userRespository;
    }

    // Register new user(worker)
    public function register(Request $request)
    {
         return $this->userRespository->store($request->only('name', 'email', 'password'));
    }
   // Login/ create/check token
    public function authenticate(Request $request)
    {

       return $this->userRespository->userAuth($request->only('email', 'password'));
    }
   //info
    public function info(Request $request)
    {
    //    return response()->json(['user' => auth()->user()]);
      return  $this->userRespository->info();
   /*     $user = JWTAuth::authenticate($request->token);
        return response()->json(['user' => $user]);*/
    }
    // Logout
    public function logout(Request $request)
    {
       return $this->userRespository->logout($request->only('token'));
    }

}
