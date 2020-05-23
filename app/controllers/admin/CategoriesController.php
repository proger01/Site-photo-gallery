<?php

namespace app\controllers\admin;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = $this->database->all('categories');
        echo $this->view->render('admin/categories/index', ['categories' => $categories]);
    }

    public function create()
    {
        echo $this->view->render('admin/categories/create');
    }

    public function store()
    {
        $validator = v::key('title', v::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле названия'
        ]);

        $this->database->create('categories', $_POST);

        return redirect('/admin/categories');
    }

    public function edit($id)
    {
        $category = $this->database->find('categories', $id);
        echo $this->view->render('admin/categories/edit', ['category' => $category]);
    }

    public function update($id)
    {
        $validator = v::key('title', v::stringType()->notEmpty());
        $this->validate($validator, $_POST, [
            'title' => 'Заполните поле названия'
        ]);

        $this->database->update('categories', $id, $_POST);
        return redirect('/admin/categories');
    }

    public function delete($id)
    {
        $this->database->delete('categories', $id);
        return back();
    }

    private function validate($validator, $data, $message)
    {
        try {
            $validator->assert($data);
            
        } catch (ValidationException $exception) {
            flash()->error($exception->getMessages($message));

            return back();
        }
    }
}
