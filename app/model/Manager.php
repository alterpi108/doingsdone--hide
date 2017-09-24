<?php
namespace App\Models;

use App\Core\App;
use App\Core\Database\Database;

class Manager
{
    public static function signup($email, $password, $name)
    {
        Database::addUser($email, $password, $name);
    }

    public static function userWithEmailExists($email)
    {
        return Database::userWithEmailExists($email);
    }

    public static function userExists($email, $password)
    {
        return Database::userExists($email, $password);
    }

    public static function getUserIdByEmail($email)
    {
        return Database::getUserIdByEmail($email);
    }

    public static function canUserAddProject($user, $project)
    {
        $valid = validateProjectName($project);

        if ($valid && Database::projectExists($user, $project)) {
            $valid = false;
        }

        return $valid;
    }

    public static function newTaskValidate($value, &$valid)
    {
        $validName = validateName($value['name']);
        $validProject = Database::projectExistsById($value['user'], $value['project']);
        $validDate = validateDate($value['date']);

        $valid['name'] = $validName;
        $valid['project'] = $validProject;
        $valid['date'] = $validDate;

        return ! failed($valid);
    }

    public static function filterNotCompleted($tasks)
    {
        $filtered = [];

        foreach ($tasks as $task) {
            if (! $task['done']) {
                $filtered[] = $task;
            }
        }

        return $filtered;
    }
}
