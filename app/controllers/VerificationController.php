<?php

namespace app\controllers;

use app\services\Notifications;

class VerificationController extends Controller
{
    private $notifications;

    public function __construct(Notifications $notifications)
    {
        parent::__construct();
        $this->notifications = $notifications;
    }

    public function showForm()
    {
        echo $this->view->render('auth/verification-form');
    }

    public function verify()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
        
            flash()->success(['Ваш email подвержден! Милости просим :)']);
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error(['Неверный токен']);
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error(['Срок действия токена закончился']);
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error(['Email уже существует']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Куда ломишься!??!']);
        }

        return redirect("/login");
    }

    public function reconfirmEmailForm()
    {
        try {
            $this->auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
                $this->notifications->emailWasChanged($_POST['email'], $selector, $token);
                flash()->success(['Код подтверждения почты был отправлен Вам на почту']);
            });
            return redirect('/login');
        
        }
        catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
            flash()->error(['Не найдено более раннего запроса, который можно отправить повторно']);
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Слишком много запросов']);
        }

        return back();
    }
}
