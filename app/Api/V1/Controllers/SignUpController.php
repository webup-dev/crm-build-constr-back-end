<?php

namespace App\Api\V1\Controllers;

use App\Models\User_profile;
use Config;
use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }

        $createUserProfile = $this->createUserProfile($user);

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }

    private function createUserProfile(User $user)
    {
        $namesArr = explode(' ', $user->name);
        $data = [
            'user_id'          => $user->id,
            'first_name'       => $namesArr[0],
            'last_name'        => $namesArr[1],
            'title'            => null,
            'department_id'    => 1,
            'phone_home'       => null,
            'phone_work'       => null,
            'phone_extension'  => null,
            'phone_mob'        => null,
            'email_personal'   => null,
            'email_work'       => null,
            'address_line_1'   => 'default',
            'address_line_2'   => null,
            'city'             => 'default',
            'state'            => 'NY',
            'zip'              => '99999',
            'status'           => 'inactive',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null
        ];
        $userProfile = new User_profile();
        $userProfile->fill($data);

        if(!$userProfile->save()) {
            throw new HttpException(500);
        }

        return true;
    }
}
