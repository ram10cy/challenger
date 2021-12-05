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
         return $this->userRespository->store($request->all());
    }
   // Login/ create/check token
    public function authenticate(Request $request)
    {

       return $this->userRespository->userAuth($request->all());
    }
   //info
    public function info(Request $request)
    {
      return  $this->userRespository->info();
    }
    // Logout
    public function logout(Request $request)
    {
       return $this->userRespository->logout($request->only('token'));
    }

}
