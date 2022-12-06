<?php

namespace App\Listeners;

use App\Interfaces\MustVerifyMobile;
use Illuminate\Auth\Events\Registered;

class SendMobileVerificationNotification
{
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyMobile && ! $event->user->hasVerifiedMobile()) {
            $event->user->sendMobileVerificationNotification(true);
        }
    }
}
