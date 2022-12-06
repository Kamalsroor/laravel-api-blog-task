<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone_number|numeric|digits:11',
            'password' => 'required',
        ]);


        if($validator->fails()){
            return response()->error('Validation Error.', $validator->errors());
        }


        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){

            $user = Auth::user();
            if(!$user->hasVerifiedMobile()){
                return response()->error('Your mobile number is not verified.' );
            }

            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  new UserResource($user);

            return response()->success('User login successfully.' , $success);
        }else{
            return response()->error('Unauthorised.' ,  ['error'=>'Unauthorised']);

        }

    }

}
