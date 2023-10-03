<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    //Request to store the token
    public function store(Request $request)
    {
        $request->validate([
           'token' => ['required','string']
        ]);
        $user = $request->user();
        $exiest = $user->devices()
            ->where('token','=',$request->post('token'))
            ->exiest;
        if (!$exiest) {
            $token = $user->devices()->create([
                'token'=> $request->post('token'),
            ]);
            return $token;

        }
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->devices()
            ->where('token','=',$request->post('token'))
            ->exiest;
        return [];
    }
}
