<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //

    public function sendVerificationEmail(User $user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60), // URL expires in 60 minutes
            ['id' => $user->id, 'hash' => Hash::make($user->email)]
        );

        Mail::to($user->email)->send(new VerificationMail($verificationUrl));
    }

    public  function register(RegisterUserRequest $request)
    {

        $request->validated();





        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->type = $request->input('type');

        $user->save();

        $this->sendVerificationEmail($user);


        return response()->json(['message' => 'User registered successfully!', 'data' => new UserResource($user)]);
    }



    public function login(LoginUserRequest $request)
    {



        $credentials = $request->safe()->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $ttl = config('jwt.ttl');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl * 60,
        ]);
    }
}
