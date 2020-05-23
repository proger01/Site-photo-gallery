<?php

namespace app\services;

class Notifications
{
    private $mailer;

    public function __construct(Mail $mailer)
    {
        $this->mailer = $mailer;
    }

    public function emailWasChanged($email, $selector, $token)
    {
        $message = 'https://localhost:5000/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
        // $this->mailer->send('dr.pustowit2013@gmail.com', $message); //$email
        flash()->success("The message $message was sended to $email"); //custom substitude resolution
    }

    public function passwordReset($email, $selector, $token)
    {
        $message = 'https://localhost:5000/password-recovery/form?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
        // $this->mailer->send('dr.pustowit2013@gmail.com', $message); //$email
        flash()->success("The message $message was sended to $email"); //custom substitude resolution
    }
}
