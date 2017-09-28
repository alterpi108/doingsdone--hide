<?php
namespace App\Controllers;

use App\Core\Database\Database;
use App\Core\App;
use App\Models\Manager;

class PagesController {

    /**
     * Open the index page.
     *
     * @return void
     */
    public function guest()
    {
        if (! App::logged()) {
            $this->generalGuest(false, false, false, [], []);
        } else {
            $this->index();
        }
    }

    /**
     * Open the login page.
     *
     * @return void
     */
    public function login()
    {
        $this->generalGuest(true, false, false, [], []);
    }

    /**
     * Open the login page just after signing up.
     *
     * @return void
     */
    public function firstLogin()
    {
        $this->generalGuest(true, true, false, [], []);
    }

    /**
     * Logout the user.
     *
     * @return void
     */
    public function logout()
    {
        App::logout();
        App::redirectIndex();
    }

    /**
     * Open the signup page.
     *
     * @return void
     */
    public function signup()
    {
        $this->generalSignup([], []);
    }

    /**
     * Open the page with tasks.
     *
     * @return void
     */
    public function index()
    {
        App::loggedOnly();

        $this->generalIndex(null, false, false, [], []);
    }

    /**
     * Show the tasks for a specified project.
     *
     * @param integer
     *
     * @return void
     */
    public function project($projectId)
    {
        App::loggedOnly();

        if (! Database::projectExistsById(App::$userId, $projectId)) {
            App::error("Такой проект не существует");
        }
        $this->generalIndex($projectId, false, false, [], []);
    }

    /**
     * Open the adding project page.
     *
     * @return void
     */
    public function addProject()
    {
        App::loggedOnly();
        $this->generalIndex(0, true, false, [], []);
    }

    /**
     * Open the adding task page.
     *
     * @return void
     */
    public function addTask()
    {
        App::loggedOnly();
        $this->generalIndex(0, false, true, [], []);
    }

    /**
     * Open the page that shows an error message.
     *
     * @param string
     *
     * @return void
     */
    public function error($message = '')
    {
        view('error', ['message' => $message]);
    }

    /**
     * Open the page to add a project.
     *
     * @param boolean
     * @param boolean
     * @param boolean
     * @param array
     * @param array
     *
     * @return void
     */
    public function generalGuest($loginModal, $firstLogin, $loginFailed, $value, $valid)
    {
        view('guest', [
            'loginModal' => $loginModal,
            'firstLogin' => $firstLogin,
            'loginFailed' => $loginFailed,
            'value' => $value,
            'valid' => $valid
        ]);
    }

    /**
     * Open the general signup page.
     *
     * @param array
     * @param array
     *
     * @return void
     */
    public function generalSignup($value, $valid)
    {
        view('signup', [
            'value' => $value,
            'valid' => $valid
        ]);
    }

    /**
     * Open the page with tasks.
     *
     * How the page will look depends on the parameters.
     *
     * @param boolean
     * @param boolean
     * @param boolean
     * @param array
     * @param array
     *
     * @return void
     */
    public function generalIndex($currentProject, $projectModal, $taskModal, $value, $valid)
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

        $showCompleted = (bool) ($_COOKIE['show'] ?? false);
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

    /**
     * Open the page with tasks.
     *
     * @param string
     *
     * @return void
     */
    public function generalIndexSearch($query)
    {
        $filter = $_GET['filter'] ?? 'all';

        $projects = Database::getProjectsByUserId(App::$userId);
        $tasks = Database::getTasksBySearch(App::$userId, $query, $filter);

        $showCompleted = (bool) ($_COOKIE['show'] ?? false);
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
