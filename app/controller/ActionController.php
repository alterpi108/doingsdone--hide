<?php
namespace App\Controllers;

use App\Core\Database\Database;
use App\Core\Request;
use App\Models\Manager;
use App\Core\App;

class ActionController {
    public function signup()
    {
        $value = [
            'email' => Request::email(),
            'password' => Request::password(),
            'name' => Request::name()
        ];

        $valid = [
            'email' => validateEmail($value['email']),
            'password' => validatePassword($value['password']),
            'name' => validateName($value['name'])
        ];

        if ($valid['email'] && Database::userWithEmailExists($value['email'])) {
            $valid['email'] = false;
        }

        if (failed($valid)) {
            (new PagesController())->_signup($value, $valid);
        } else {
            Database::addUser($value['email'], $value['password'], $value['name']);
            App::redirect('/first-login');
        }
    }

    public function login()
    {
        $value = [
            'email' => Request::email(),
            'password' => Request::password()
        ];

        $valid = [
            'email' => validateEmail($value['email']),
            'password' => validatePassword($value['password'])
        ];

        if (failed($valid)) {
            (new PagesController())->_guest(true, false, false, $value, $valid);
        } else if (! Database::userExists($value['email'], $value['password'])) {
            (new PagesController())->_guest(true, false, true, $value, $valid);
        } else {
            App::loginByEmail($value['email']);
            App::redirectIndex();
        }
    }

    public function addProject()
    {
        App::loggedOnly();

        $value = [
            'name' => Request::projectName()
        ];

        $valid = [
            'name' => Manager::canUserAddProject(App::$userId, $value['name'])
        ];

        if (! failed($valid)) {
            Database::addProject(App::$userId, $value['name']);
            App::redirectIndex();
        } else {
            (new PagesController())->_index(0, true, false, $value, $valid);
        }
    }

    public function addTask()
    {
        App::loggedOnly();

        $value = [
            'user' => Request::user(),
            'name' => Request::name(),
            'project' => Request::project(),
            'date' => Request::date(),
            'file' => ''
        ];

        $valid = [];

        $possible = Manager::newTaskValidate($value, $valid);

        if ($possible) {
            // сохранение файла
            if (array_key_exists('file', $_FILES)) {
                $fileName = $_FILES['file']['name'];
                $src = $_FILES['file']['tmp_name'];
                $dst = __DIR__ . '/../../public/userfiles/' . $fileName;
                move_uploaded_file($src, $dst);

                $value['file'] = $fileName;
            }

            Database::addTask($value);
            App::redirectIndex();
        } else {
            (new PagesController())->_index(0, false, true, $value, $valid);
        }
    }

    public function search()
    {
        App::loggedOnly();

        if (! array_key_exists('q', $_GET) || ! $_GET['q']) {
            App::error('Предоставьте текст для поиска');
        } else {
            $query = trim($_GET['q']);
            (new PagesController())->_indexSearch($query);
        }
    }

    public function complete()
    {
        App::loggedOnly();

        if (! $_GET['id']) {
            App::error('Предоставьте ID');
        } else if (! Database::userHasTaskWithId(App::$userId, $_GET['id'])) {
            App::error('У вас нет такой задачи');
        } else {
            Database::switchTaskDone($_GET['id']);
            App::redirect('/');
        }
    }
}
