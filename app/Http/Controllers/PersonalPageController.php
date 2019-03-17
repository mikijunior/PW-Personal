<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalPageController extends Controller
{
    public function user()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response(['status' => 'success'])->header('Authorization', auth('api')->refresh());
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $oldPassword = md5(auth('api')->user()->name . $request['password']);
        $newPassword = auth('api')->user()->name . $request['newPassword'];
        if ($oldPassword === auth('api')->user()->passwd ) {
            if (auth('api')->user()->answer === $request['answer'])
            {
                auth('api')->user()->update([
                    'password' => md5($newPassword)
                ]);
                return response()->json(['status' => 'Success'], 200);
            }
            return response()->json(['answer' => 'Incorrect answer'], 500);
        }
        return response()->json(['password' => 'Current password not correct.'], 500);
    }
}
