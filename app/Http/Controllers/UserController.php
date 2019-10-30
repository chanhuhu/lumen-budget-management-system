<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->where('status_id', Status::$ACTIVE)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $this->jwt($user);
            $user['api_token'] = $token;
            return $this->responseRequestSuccess($user);

        }
        return $this->responseRequestError('The credentials provided are invalid.', 500);

    }

    public function register(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'first' => 'required',
            'last' => 'required',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseRequestError($errors,400);
        } else {
            $user = new User();
            $form = [
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'first' => $request->get('first'),
                'last' => $request->get('last'),
                'role_id' => $request->get('role_id'),
            ];
            $user->fill($form);
            if ($user->save()) {
                $token = $this->jwt($user);
                $user['api_token'] = $token;
                   return $this->responseRequestSuccess($user);
            } else {
                return $this->responseRequestError('Cannot Register', 500);
            }
        }
    }

    public function logout(Request $request)
    {
        //
    }

    private function jwt($user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + env('JWT_EXPIRE_HOUR') * 60 * 60, // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }


    protected function responseRequestError($message = 'Bad request', $statusCode = 200)
    {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestSuccess($ret)
    {
        return response()->json(['status' => 'success', 'data' => $ret], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

}
