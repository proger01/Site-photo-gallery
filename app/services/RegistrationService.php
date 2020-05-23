<?php

namespace app\services;

use Delight\Auth\Auth;

class RegistrationService
{
    private $auth;
    private $notifications;

    public function __construct(Auth $auth, Database $database, Notifications $notifications)
    {
        $this->auth = $auth;
        $this->database = $database;
        $this->notifications = $notifications;
    }

    public function make($email, $password, $username)
    {
        $userId = $this->auth->register($email, $password, $username, function($selector, $token) {
            $this->notifications->emailWasChanged($email, $selector, $token);
        });

        $this->database->update('users', $userId, ['roles_mask' => ROLES::USER]);

        return $userId;
    }
}