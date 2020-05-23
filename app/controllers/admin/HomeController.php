<?php

namespace app\controllers\admin;

class HomeController extends Controller
{
    public function index()
    {
        echo $this->view->render('admin/dashboard');
    }
}
