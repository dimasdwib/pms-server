<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use App\Http\Resources\User as UserResource;

class AuthController extends Controller
{
    /**
     * User authentication
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $username = $request->username; // or email
        $password = $request->password;

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt(['username' => $username, 'password' => $password])) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * Logout current user 
     */
    public function logout() 
    {

    }

    /**
     * Get all user attribute
     */
    public function auth_attributes()
    {   
        $id = $this->getAuthUser()->id;
        $user = User::findOrFail($id);
        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

}