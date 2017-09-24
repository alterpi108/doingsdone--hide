<?php
namespace App\Core\Database;

use App\Core\App;
use PDO;
use PDOException;

class Database
{
    protected static $pdo;

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
            die($e->getMessage());
        }
    }

    public static function userWithEmailExists($email)
    {
        $stm = static::$pdo->prepare("SELECT 1 FROM user WHERE email=?");
        $stm->bindParam(1, $email, PDO::PARAM_STR);
        $stm->execute();

        return (bool) $stm->fetchColumn();
    }

    public static function userExists($email, $password)
    {
        if (! static::userWithEmailExists($email)) {
            return false;
        }
        $passwordDb = static::getPasswordByEmail($email);
        return password_verify($password, $passwordDb);
    }

    public static function getPasswordByEmail($email)
    {
        $statement = static::$pdo->prepare("SELECT password FROM user WHERE email=?");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public static function addUser($email, $password, $name)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stm = static::$pdo->prepare('INSERT INTO user SET email=?, password=?, name=?');
        $stm->bindParam(1, $email, PDO::PARAM_STR);
        $stm->bindParam(2, $password, PDO::PARAM_STR);
        $stm->bindParam(3, $name, PDO::PARAM_STR);
        $stm->execute();

        // Проект "Входящие" добавляется для нового пользователя автоматически
        $userId = static::$pdo->lastInsertId();
        static::addProject($userId, 'Входящие');
    }

    public static function getUserIdByEmail($email)
    {
        $statement = static::$pdo->prepare("SELECT id FROM user WHERE email=?");
        $statement->bindParam(1, $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public static function getUserNameById($user)
    {
        $statement = static::$pdo->prepare("SELECT name FROM user WHERE id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public static function getUserEmailById($user)
    {
        $statement = static::$pdo->prepare("SELECT email FROM user WHERE id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public static function getProjectsByUserId($user)
    {
        $statement = static::$pdo->prepare("SELECT id, user, name, count FROM project WHERE user=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
    }

    public static function addProject($user, $name)
    {
        $statement = static::$pdo->prepare('INSERT INTO project SET user=?, name=?');
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $name, PDO::PARAM_STR);
        $statement->execute();
    }

    public static function projectExists($user, $name)
    {
        $statement = static::$pdo->prepare("SELECT * FROM project WHERE user=? AND name=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $name, PDO::PARAM_STR);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return (bool) $row;
    }

    public static function userHasTaskWithId($userId, $taskId)
    {
        $stm = static::$pdo->prepare("SELECT 1 FROM task WHERE id=? AND user=?");
        $stm->bindParam(1, $taskId, PDO::PARAM_INT);
        $stm->bindParam(2, $userId, PDO::PARAM_INT);
        $stm->execute();

        return (bool) $stm->fetchColumn();
    }

    public static function projectExistsById($user, $id)
    {
        $statement = static::$pdo->prepare("SELECT * FROM project WHERE user=? AND id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $id, PDO::PARAM_STR);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return (bool) $row;
    }

    public static function incrementProjectCounter($user, $id)
    {
        $statement = static::$pdo->prepare("UPDATE project SET count=count+1 WHERE user=? AND id=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->bindParam(2, $id, PDO::PARAM_STR);
        $statement->execute();
    }

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

    public static function getTodayTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)=CURDATE()";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    public static function getTomorrowTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)=CURDATE()+INTERVAL 1 DAY";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    public static function getOverdueTasks($userId, $projectId)
    {
        $query = "SELECT * FROM task WHERE user=? AND project=? AND DATE(`deadline`)<CURDATE()";
        $stm = static::$pdo->prepare($query);
        $stm->bindParam(1, $userId, PDO::PARAM_INT);
        $stm->bindParam(2, $projectId, PDO::PARAM_INT);

        $stm->execute();
        return $stm->fetchAll();
    }

    public static function getDueTasks()
    {
        $query = "SELECT * FROM task WHERE deadline > NOW() AND deadline < NOW() + INTERVAL 1 HOUR";
        $stm = static::$pdo->prepare($query);
        $stm->execute();
        return $stm->fetchAll();
    }

    public static function allTasksCountForUser($user)
    {
        $statement = static::$pdo->prepare("SELECT 1 FROM task WHERE user=?");
        $statement->bindParam(1, $user, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

    public static function addTask($value)
    {
        $value['date'] = strtodatetime($value['date']);

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

    public static function switchTaskDone($taskId)
    {
        $statement = static::$pdo->prepare("UPDATE task SET done=IF(done, 0, 1) WHERE id=?");
        $statement->bindParam(1, $taskId, PDO::PARAM_INT);
        $statement->execute();
    }
}
