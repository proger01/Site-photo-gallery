<?php

namespace app\controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;
use PDO;
use app\services\Database;
use app\services\Roles;

class Controller
{
    protected $view;
    protected $database;
    protected $auth;

    public function __construct()
    {
        $this->database = components(Database::class);
        $this->view = components(Engine::class);
        $this->auth = components(Auth::class);
    }

    public function checkForAccess()
    {
        if ($this->auth->hasRole(Roles::USER)) { return redirect('/'); }
    }
}