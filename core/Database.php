<?php
namespace App\Core\Database;

use App\Core\App;
use PDO;
use PDOException;

class Database
{
    protected static $pdo;

    /**
     * Connect to the MySQL database.
     *
     * @return void
     */
    public static function connect()
    {
        $config = App::$config['database'];
        try {
            static::$pdo = new PDO(
                $config['connection'] . ';dbname='.$config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            App::error('Не могу подключиться к базе данных');
        }
    }

    /**
     * Check if a user with a given email exists in the database.
     *
     * @param string
     *
     * @return boolean
     */
    public static function userWithEmailExists($email)
    {
        $stm = static::$pdo->prepare("SELECT 1 FROM user WHERE email=?");
        $stm->bindParam(1, $email, PDO::PARAM_STR);
        $stm->execute();
        return (bool) $stm->fetchColumn();
    }

    /**
     * Check if a user with a given email and password exists in the database.
     *
     * @param string
     * @param string
     *
     * @return boolean
     */
    public static function userExists($email, $password)
    {
        if (! static::userWithEmailExists($email)) {
            return false;
        }
        $passwordDb = static::getPasswordByEmail($email);
        return password_verify($password, $passwordDb);
    }

    /**
     * Get the password (hashed) of the user with a given email.
     *
     * @param string
     *
     * @return string
     */
    public static function getPasswordByEmail($email)
    {
        $statement = static::$pdo->prepare("SELECT password FROM user WHERE email=?");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Add a new user to the database.
     *
     * The data must be valid and email must be new.
     *
     * @param string
     * @param string
     * @param string
     *
     * @return void
     */
    public static function addUser($value)
    {
        $email = $value['email'];
        $password = password_hash($value['password'], PASSWORD_DEFAULT);
        $name = $value['name'];

        $stm = static::$pdo->prepare('INSERT INTO user SET email=?, password=?, name=?');
        $stm->bindParam(1, $email, PDO::PARAM_STR);
        $stm->bindParam(2, $password, PDO::PARAM_STR);
        $stm->bindParam(3, $name, PDO::PARAM_STR);
        $stm->execute();

        // Project "Входящие" is added for a new user automatically
        $userId = static::$pdo->lastInsertId();
        static::addProject($userId, 'Входящие');
    }

    /**
     * Get the ID of the user with a given email.
     *
     * @param string
     *
     * @return integer
     */
    public static function getUserIdByEmail($email)
    {
        $statement = static::$pdo->prepare("SELECT id FROM user WHERE email=?");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Get the name of the user with a given ID.
     *
     * @param int
     *
     * @return string
     */
    public static function getUserNameById($user)
    {
        $statement = static::$pdo->prepare("SELECT name FROM user WHERE id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Get the email of the user with a given ID.
     *
     * @param integer
     *
     * @return string
     */
    public static function getUserEmailById($user)
    {
        $statement = static::$pdo->prepare("SELECT email FROM user WHERE id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Get the list of projects for the user with a given ID.
     *
     * @param integer
     *
     * @return string[]
     */
    public static function getProjectsByUserId($user)
    {
        $statement = static::$pdo->prepare("SELECT id, user, name, count FROM project WHERE user=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
    }

    /**
     * Add a new project for the user with a given ID.
     *
     * @param integer
     * @param string
     *
     * @return void
     */
    public static function addProject($user, $name)
    {
        $statement = static::$pdo->prepare('INSERT INTO project SET user=?, name=?');
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $name, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * Check if the user with a given ID has a project with a given name.
     *
     * @param integer
     * @param string
     *
     * @return boolean
     */
    public static function projectExists($user, $name)
    {
        $statement = static::$pdo->prepare("SELECT * FROM project WHERE user=? AND name=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $name, PDO::PARAM_STR);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return (bool) $row;
    }

    /**
     * Check if there is a user with a given ID in the database.
     *
     * @param integer
     *
     * @return boolean
     */
    public static function userWithIdExists($userId)
    {
        $stm = static::$pdo->prepare("SELECT * FROM user WHERE id=?");
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->execute();
        return (bool) $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if the user with a given ID has a task with a given ID.
     *
     * @param integer
     * @param integer
     *
     * @return boolean
     */
    public static function userHasTaskWithId($userId, $taskId)
    {
        $stm = static::$pdo->prepare("SELECT 1 FROM task WHERE id=? AND user=?");
        $stm->bindParam(1, $taskId, PDO::PARAM_INT);
        $stm->bindParam(2, $userId, PDO::PARAM_INT);
        $stm->execute();

        return (bool) $stm->fetchColumn();
    }

    /**
     * Check if a user with a given ID has a project with a given ID.
     *
     * @param integer
     * @param integer
     *
     * @return boolean
     */
    public static function projectExistsById($user, $id)
    {
        $statement = static::$pdo->prepare("SELECT * FROM project WHERE user=? AND id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $id, PDO::PARAM_STR);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return (bool) $row;
    }

    /**
     * Increment the task counter for a project.
     *
     * @param integer
     * @param integer
     *
     * @return void
     */
    public static function incrementProjectCounter($user, $id)
    {
        $statement = static::$pdo->prepare("UPDATE project SET count=count+1 WHERE user=? AND id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $id, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * Get the tasks for a user's project.
     *
     * @param integer
     * @param integer
     *
     * @return array[]
     */
    public static function getTasksForProjectByUserId($user, $project)
    {
        if ($project === 0) {
            $query = "SELECT * FROM task WHERE user=?";
            $statement = static::$pdo->prepare($query);
            $statement->bindParam(1, $user, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM task WHERE user=? AND project=?";
            $statement = static::$pdo->prepare($query);
            $statement->bindParam(1, $user, PDO::PARAM_INT);
            $statement->bindParam(2, $project, PDO::PARAM_INT);
        }

        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Get tasks that names contain specified words.
     *
     * @param integer
     * @param string
     * @param string
     *
     * @return array[]
     */
    public static function getTasksBySearch($userId, $text, $filter)
    {
        $query = 'SELECT * FROM task WHERE user=? AND name LIKE ?';
        if ($filter === 'today') {
            $query .= ' AND DATE(`deadline`)=CURDATE()';
        } else if ($filter === 'tomorrow') {
            $query .= ' AND DATE(`deadline`)=CURDATE()+INTERVAL 1 DAY';
        } else if ($filter === 'overdue') {
            $query .= ' AND DATE(`deadline`)<CURDATE()';
        }

        $text = "%$text%";

        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $text, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * Get the tasks for the specified user/project that have their deadline today.
     *
     * @param integer
     * @param integer
     *
     * @return array[]
     */
    public static function getTodayTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)=CURDATE()";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * Get the tasks for the specified user/project that have their deadline tomorrow.
     *
     * @param integer
     * @param integer
     *
     * @return array[]
     */
    public static function getTomorrowTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)=CURDATE()+INTERVAL 1 DAY";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * Get the tasks for the specified user/project that have passed their deadline.
     *
     * @param integer
     * @param integer
     *
     * @return array[]
     */
    public static function getOverdueTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)<CURDATE()";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * Get the "hot" tasks that will have passed their deadline in an hour.
     *
     * @return array[]
     */
    public static function getDueTasks()
    {
        $query = "SELECT * FROM task WHERE deadline > NOW() AND deadline < NOW() + INTERVAL 1 HOUR";
        $stm = static::$pdo->prepare($query);
        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * Get the number of tasks for a user.
     *
     * @param integer
     *
     * @return integer
     */
    public static function allTasksCountForUser($user)
    {
        $statement = static::$pdo->prepare("SELECT 1 FROM task WHERE user=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

    /**
     * Add a new task to the database.
     *
     * @param array $value  Contains all data needed to add a new task.
     *
     * @return void
     */
    public static function addTask($value)
    {
        if ($value['date'] !== '') {
            $value['date'] = strtodatetime($value['date']);
        }

        $statement = static::$pdo->prepare('INSERT INTO task SET user=?, name=?, project=?, deadline=?, file=?');
        $statement->bindParam(1, $value['user'], PDO::PARAM_INT);
        $statement->bindParam(2, $value['name'], PDO::PARAM_STR);
        $statement->bindParam(3, $value['project'], PDO::PARAM_INT);
        if ($value['date']) {
            $statement->bindParam(4, $value['date'], PDO::PARAM_STR);
        } else {
            $statement->bindValue(4, null, PDO::PARAM_INT);
        }
        $statement->bindParam(5, $value['file'], PDO::PARAM_STR);
        $statement->execute();

        static::incrementProjectCounter($value['user'], $value['project']);
    }

    /**
     * Check if a task completed.
     *
     * @param integer
     *
     * @return boolean
     */
    public function isTaskDone($taskId)
    {
        $statement = static::$pdo->prepare("SELECT 1 FROM task WHERE id=? AND done=TRUE");
        $statement->bindParam(1, $taskId, PDO::PARAM_INT);
        $statement->execute();
        return (bool) $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * If the task is completed, make it not completed and vice versa.
     *
     * @param integer
     *
     * @return void
     */
    public static function switchTaskDone($taskId)
    {
        if (! Database::isTaskDone($taskId)) {
            $stm = static::$pdo->prepare("UPDATE task SET done=1, finished=NOW() WHERE id=?");
        } else {
            $stm = static::$pdo->prepare("UPDATE task SET done=0, finished=NULL WHERE id=?");
        }
        $stm->bindParam(1, $taskId, PDO::PARAM_INT);
        $stm->execute();
    }
}
