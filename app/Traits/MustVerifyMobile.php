<?php

namespace App\Traits;

use App\Notifications\SendVerifySMS;
use Illuminate\Support\Facades\Storage;

trait MustVerifyMobile
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return ! is_null($this->phone_verified_at);
    }




    public function markMobileAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verify_code' => NULL,
            'phone_verified_at' => $this->freshTimestamp(),
            'phone_attempts_left' => 0,
        ])->save();
    }

    public function sendMobileVerificationNotification(bool $newData = false): void
    {
        if($newData)
        {
            $this->forceFill([
                'phone_verify_code' => random_int(111111, 999999),
                'phone_attempts_left' => config('mobile.max_attempts'),
                'phone_verify_code_sent_at' => now(),
            ])->save();
        }

        Storage::disk('local')->append('mobile_verify.txt', 'the code is : ' . $this->phone_verify_code);


        // $this->notify(new SendVerifySMS);
    }


    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification()
    {
        return $this->phone;
    }
}
