<?php
namespace App\Models;

use App\Core\Database\Database;

class Manager
{
    /**
     * Check if a user can add a project.
     *
     * @param integer
     * @param string
     *
     * @return boolean
     */
    public static function canUserAddProject($userId, $projectName)
    {
        return (validateProjectName($projectName) && ! Database::projectExists($userId, $projectName));
    }

    /**
     * Validate the data for a new task and fill up a given array with the results.
     *
     * @param array
     * @param array
     *
     * @return boolean
     */
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

    /**
     * From a given list of tasks select only those that are not completed.
     *
     * @param array
     *
     * @return boolean
     */
    public static function filterNotCompleted($tasks)
    {
        return array_filter($tasks, function ($task) {
            return ! $task['done'];
        });
    }
}
