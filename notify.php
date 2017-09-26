<?php
require 'core/bootstrap.php';
require 'vendor/autoload.php';

use App\Core\Database\Database;

/**
 * From a list of tasks select those tasks that belong to a given user.
 *
 * @param array[]
 * @param integer
 *
 * @return array[]
 */
function userTasks($tasks, $userId)
{
    $filtered = [];
    foreach ($tasks as $task) {
        if ($task['user'] == $userId) {
            $filtered[] = $task;
        }
    }
    return $filtered;
}

/**
 * Send an email.
 *
 * @param string
 * @param string
 * @param string
 * @param string
 * @param Swift_Mailer
 *
 * @return void
 */
function sendMessage($subject, $from, $to, $body, $mailer)
{
    $message = (new Swift_Message($subject))
        ->setFrom($from)
        ->setTo($to)
        ->setBody($body);

    $mailer->send($message);
}

// constant information
$host = 'smtp.mail.ru';
$port = 465;
$user = 'doingsdone@mail.ru';
$password = 'rds7BgcL';
$encryption = 'ssl';
$subject = 'Уведомление от сервиса «Дела в порядке»';
$from = ['doingsdone@mail.ru' => 'Doings Done'];

// set up the mailer
$transport = (new Swift_SmtpTransport($host, $port))
    ->setUsername($user)
    ->setPassword($password)
    ->setEncryption($encryption);

$mailer = new Swift_Mailer($transport);

// select due tasks and handle for each user
$tasks = Database::getDueTasks();
$userIds = array_unique(array_column($tasks, 'user'));

foreach ($userIds as $userId) {
    $userEmail = Database::getUserEmailById($userId);
    $userName = Database::getUserNameById($userId);

    $to = [$userEmail => $userName];
    $body = "Уважаемый, $userName.\n\n";

    $userTasks = userTasks($tasks, $userId);
    foreach ($userTasks as $task) {
        $body .= "У вас запланирована задача '" . $task['name'] . "' на " . $task['deadline'] . ".\n";
    }

    sendMessage($subject, $from, $to, $body, $mailer);
}
