<?php

namespace App\Interfaces;

interface MustVerifyMobile
{
   /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedMobile();

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified();

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification();

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification();
}
