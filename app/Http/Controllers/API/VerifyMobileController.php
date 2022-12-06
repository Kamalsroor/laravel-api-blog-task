<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class VerifyMobileController extends Controller
{

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        //Redirect user to dashboard if mobile already verified
        if ($request->user()->hasVerifiedMobile()) return response()->error('already verified.' ,  ['error'=>'already verified']);;




        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        // Code correct
        if ($request->code === auth()->user()->phone_verify_code) {
            // check if code is still valide
            $secondsOfValidation = (int) config('mobile.seconds_of_validation');
            if ($secondsOfValidation > 0 &&  $request->user()->phone_verify_code_sent_at->diffInSeconds() > $secondsOfValidation) {
                $request->user()->sendMobileVerificationNotification(true);
                return response()->error(__('mobile.expired') , ['error' => __('mobile.expired')]);
            }else {
                $request->user()->markMobileAsVerified();
                return response()->success(__('mobile.verified') , new UserResource(auth()->user()));
            }
        }

        // Max attempts feature
        if (config('mobile.max_attempts') > 0) {
            if ($request->user()->phone_attempts_left <= 1) {
                if($request->user()->phone_attempts_left == 1) $request->user()->decrement('phone_attempts_left');

                //check how many seconds left to get unbanned after no more attempts left
                $seconds_left = (int) config('mobile.attempts_ban_seconds') - $request->user()->phone_last_attempt_date->diffInSeconds();
                if ($seconds_left > 0) {
                    return response()->error(__('mobile.error_wait', ['seconds' => $seconds_left]) , ['error' => __('mobile.error_wait', ['seconds' => $seconds_left])]);
                }

                //Send new code and set new attempts when user is no longer banned
                $request->user()->sendMobileVerificationNotification(true);
                return response()->error(__('mobile.new_code') , ['error' => __('mobile.new_code')]);
            }

            $request->user()->decrement('phone_attempts_left');
            $request->user()->update(['phone_last_attempt_date' => now()]);
            return response()->error(__('mobile.error_with_attempts', ['attempts' => $request->user()->phone_attempts_left]) , ['error' => __('mobile.error_with_attempts', ['attempts' => $request->user()->phone_attempts_left])]);
        }

        return response()->error(__('mobile.error_code') , ['error' => __('mobile.error_code')]);

    }

}
