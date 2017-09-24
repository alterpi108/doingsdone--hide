<?php
require 'core/bootstrap.php';
require 'vendor/autoload.php';

use App\Core\Database\Database;


$host = 'smtp.mail.ru';
$port = 465;
$user = 'doingsdone@mail.ru';
$password = 'rds7BgcL';
$encryption = 'ssl';

$transport = (new Swift_SmtpTransport($host, $port))
    ->setUsername($user)
    ->setPassword($password)
    ->setEncryption($encryption);

$mailer = new Swift_Mailer($transport);

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

$header = 'Уведомление от сервиса «Дела в порядке»';
$from = ['doingsdone@mail.ru' => 'Doings Done'];

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

    // send email
    $message = (new Swift_Message('header'))
        ->setFrom($from)
        ->setTo($to)
        ->setBody($body);

    $mailer->send($message);
}
