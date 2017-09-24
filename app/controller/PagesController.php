<?php
namespace App\Controllers;

use App\Core\Database\Database;
use App\Core\Request;
use App\Core\App;
use App\Models\Manager;

class PagesController {
    public function guest()
    {
        if (! App::logged()) {
            $this->_guest(false, false, false, [], []);
        } else {
            $this->index();
        }
    }

    public function login()
    {
        $this->_guest(true, false, false, [], []);
    }

    public function firstLogin()
    {
        $this->_guest(true, true, false, [], []);
    }

    public function logout()
    {
        App::logout();
        App::redirectIndex();
    }

    public function signup()
    {
        $this->_signup([], []);
    }

    public function index()
    {
        App::loggedOnly();

        $this->_index(null, false, false, [], []);
    }

    public function project($projectId)
    {
        App::loggedOnly();

        if (! Database::projectExistsById(App::$userId, $projectId)) {
            App::error("Такой проект не существует");
        }
        $this->_index($projectId, false, false, [], []);
    }

    public function addProject()
    {
        App::loggedOnly();
        $this->_index(0, true, false, [], []);
    }

    public function addTask()
    {
        App::loggedOnly();
        $this->_index(0, false, true, [], []);
    }

    public function error($message = '')
    {
        view('error', ['message' => $message]);
    }

    public function _guest($loginModal, $firstLogin, $loginFailed, $value, $valid)
    {
        view('guest', [
            'loginModal' => $loginModal,
            'firstLogin' => $firstLogin,
            'loginFailed' => $loginFailed,
            'value' => $value,
            'valid' => $valid
        ]);
    }

    public function _signup($value, $valid)
    {
        view('signup', [
            'value' => $value,
            'valid' => $valid
        ]);
    }

    public function _index($currentProject, $projectModal, $taskModal, $value, $valid)
    {
        $projects = Database::getProjectsByUserId(App::$userId);
        if (! $currentProject) {
            $currentProject = $projects[0]['id'];
        }

        $filter = $_GET['filter'] ?? 'all';

        if ($filter === 'all') {
            $tasks = Database::getTasksForProjectByUserId(App::$userId, $currentProject);
        } else if ($filter === 'today') {
            $tasks = Database::getTodayTasks(App::$userId, $currentProject);
        } else if ($filter === 'tomorrow') {
            $tasks = Database::getTomorrowTasks(App::$userId, $currentProject);
        } else if ($filter === 'overdue') {
            $tasks = Database::getOverdueTasks(App::$userId, $currentProject);
        }

        $showCompleted = (bool) $_COOKIE['show'];
        if (! $showCompleted) {
            $tasks = Manager::filterNotCompleted($tasks);
        }

        view('index', [
            'projectModal' => $projectModal,
            'taskModal' => $taskModal,
            'userName' => App::$userName,
            'currentProject' => (int) $currentProject,
            'currentDate' => App::date(),
            'projects' => $projects,
            'tasks' => $tasks,
            'filter' => $filter,
            'showCompleted' => $showCompleted,
            'value' => $value,
            'valid' => $valid
        ]);
    }

    // много дублирования кода с предыдущим методом
    public function _indexSearch($query)
    {
        $filter = $_GET['filter'] ?? 'all';

        $projects = Database::getProjectsByUserId(App::$userId);
        $tasks = Database::getTasksBySearch(App::$userId, $query, $filter);

        $showCompleted = (bool) $_COOKIE['show'];
        if (! $showCompleted) {
            $tasks = Manager::filterNotCompleted($tasks);
        }

        view('index', [
            'projectModal' => false,
            'taskModal' => false,
            'userName' => App::$userName,
            'currentProject' => 0,
            'currentDate' => App::date(),
            'projects' => $projects,
            'tasks' => $tasks,
            'filter' => $filter,
            'showCompleted' => $showCompleted,
            'query' => $query,
            'value' => [],
            'valid' => []
        ]);
    }
}
