<?php
namespace App\Controllers;

use App\Core\Database\Database;
use App\Core\Request;
use App\Models\Manager;
use App\Core\App;

class ActionController {

    /**
     * Handle a request to signup a new user.
     *
     * @return void
     */
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
            (new PagesController())->generalSignup($value, $valid);
        } else {
            Database::addUser($value);
            App::redirect('/first-login');
        }
    }

    /**
     * Handle a request to login a user.
     *
     * @return void
     */
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
            (new PagesController())->generalGuest(true, false, false, $value, $valid);
        } else if (! Database::userExists($value['email'], $value['password'])) {
            (new PagesController())->generalGuest(true, false, true, $value, $valid);
        } else {
            App::loginByEmail($value['email']);
            App::redirectIndex();
        }
    }

    /**
     * Handle a request to add a project.
     *
     * @return void
     */
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
            (new PagesController())->generalIndex(0, true, false, $value, $valid);
        }
    }

    /**
     * Handle a request to add a task.
     *
     * @return void
     */
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
            if (array_key_exists('file', $_FILES)) {
                $fileName = App::saveFile();
                $value['file'] = $fileName;
            }
            Database::addTask($value);
            App::redirectIndex();
        } else {
            (new PagesController())->generalIndex(0, false, true, $value, $valid);
        }
    }

    /**
     * Handle a request to search tasks.
     *
     * @return void
     */
    public function search()
    {
        App::loggedOnly();

        if (! array_key_exists('q', $_GET) || ! $_GET['q']) {
            App::error('Предоставьте текст для поиска');
        } else {
            $query = trim($_GET['q']);
            (new PagesController())->generalIndexSearch($query);
        }
    }

    /**
     * Handle a request to complete/uncomplete a task.
     *
     * @return void
     */
    public function complete()
    {
        App::loggedOnly();

        if (! array_key_exists('id', $_GET) || ! $_GET['id']) {
            App::error('Предоставьте ID');
        } else if (! Database::userHasTaskWithId(App::$userId, $_GET['id'])) {
            App::error('У вас нет такой задачи');
        } else {
            Database::switchTaskDone($_GET['id']);
            App::redirect('/');
        }
    }
}
