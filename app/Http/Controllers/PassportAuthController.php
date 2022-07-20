<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\APIHelper;
use Auth;

class PassportAuthController extends Controller
{
     /**
     * Registration
     */
    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
        return APIHelper::makeAPIResponse(true, 'Successfully Registered', $token);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

       
        
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return APIHelper::makeAPIResponse(true, 'Successfully Login', $token);
        } else {
            return APIHelper::makeAPIResponse(false, 'Unauthorised user login', null, 401);
        }
    }   

    public function logout(Request $request) {
       
        $token = $request->user()->token();
        $token->revoke();
        return APIHelper::makeAPIResponse(true, 'You have been succesfully logged out!', null, 200);

    }
}
