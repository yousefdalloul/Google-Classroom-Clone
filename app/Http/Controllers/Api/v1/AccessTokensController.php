<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use PhpParser\Lexer\TokenEmulator\ReadonlyTokenEmulator;

class AccessTokensController extends Controller
{

    public function index()
    {
        return Auth::guard('sanctum')->user()->tokens;
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'device_name' => ['sometimes','required'],
            'abilities' => ['array'],
        ]);

        $user = User::whereEmail($request->email)->first();

        if ($user && Hash::check($request->password,$user->password)) {
            $name = $request->post('device_name',$request->userAgent());
            $abilities = $request->post('abilities',['*']);
            $token = $user->createToken($name,$abilities,now()->addDays(90));

            return Response::json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ],201);
        }
        return Response::json([
            'message' => __('Invalid Credentials'),
        ],401);
    }

    public function destroy($id = null)
    {
        $user = Auth::guard('sanctum')->user();
        if ($id) {
            //Revoke (logout from current device)
            if ($id == 'current') {
                $user->currentAccessToken()->delete();
            } else {
                $user->tokens()->findOrFail($id)->delete();
            }
        }else{
                //Logout from all devices
                $user->tokens()->delete();
            }
        }

}
