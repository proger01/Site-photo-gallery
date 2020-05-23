<?php

namespace app\controllers\admin;

use app\controllers\Controller as MainController;
use app\services\Roles;

class Controller extends MainController
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->auth->hasRole(Roles::ADMIN)) {
            abort(404);
        }
    }
}