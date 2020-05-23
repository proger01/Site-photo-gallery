<?php

namespace app\services;

use Delight\Auth\Auth;

class Profile
{
    private $auth;
    private $notifications;
    private $imageManager;
    private $database;

    public function __construct(Auth $auth, Notifications $notifications, ImageManager $imageManager, Database $database)
    {
        $this->auth = $auth;
        $this->notifications = $notifications;
        $this->imageManager = $imageManager;
        $this->database = $database;
    }

    public function chanegInformation($newEmail, $newUsername = null, $newImage)
    {
        if ($newEmail != $this->auth->getEmail()) {
            $this->auth->changeEmail($newEmail, function ($selector, $token) use ($newEmail) {
                $this->notifications->emailWasChanged($newEmail, $selector, $token);
                flash()->success(['На вашу почту ' . $newEmail . ' был отправлен код с подтверждением.']);
            });
        }

        $user = $this->database->find('users', $this->auth->getuserId());
        $image = $this->imageManager->uploadUserImage($newImage, $user['image']);

        $this->database->update('users', $this->auth->getUserId(), [
            'username' => isset($newUsername) ? $newUsername : $this->auth->getUsername(),
            'image' => $image,
        ]);
    }
}