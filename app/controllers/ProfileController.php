<?php

namespace app\controllers;

use app\services\Profile;

class ProfileController extends Controller
{
    private $profile;

    public function __construct(Profile $profile)
    {
        parent::__construct();
        $this->profile = $profile;
    }

    public function showInfo()
    {
        $user = $this->database->find('users', $this->auth->getUserId());
        echo $this->view->render('profile/info', compact('user'));
    }

    public function postInfo()
    {
        try {
            $this->profile->chanegInformation($_POST['email'], $_POST['username'], $_FILES['image']);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error(['неверный формат имейла']);
            // invalid email address
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // email address already exists
            flash()->error(['имейл уже существует']);
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // account not verified
            flash()->error(['почта не подтверждена']);
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
            flash()->error(['ты не залогинен']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            flash()->error(['куда ломишься!']);
        }

        return back();
    }

    public function showSecurity()
    {
        echo $this->view->render('profile/security');
    }

    public function postSecurity()
    {
        try {
            $this->auth->changePassword($_POST['password'], $_POST['new_password']);
            flash()->success(['Пароль успешно изменен']);
            
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error(['Залогиньтесь!']);
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error(['Неправильный пароль.']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Куда ломишься?!']);
        }

        return back();
    }
}
