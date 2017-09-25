<?php
use App\Core\Database\Connection;
use App\Core\Database\Database;
use App\Core\Request;

function view($name, $data = [])
{
    extract($data);
    require "app/view/{$name}.view.php";
}

function renderTemplate($path, $data)
{
    extract($data);
    ob_start();
    include 'app/view/' . $path . '.php';
    $result = ob_get_clean();

    return $result;
}

function validateEmail($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function validatePassword($value)
{
    return $value !== '';
}

function validateName($value)
{
    return $value !== '';
}

function validateProjectName($value)
{
    return $value !== '';
}

function validateDate($str)
{
    $timestamp = strtotimestamp($str);
    $currentTime = strtotimestamp('now');
    return (bool) $timestamp && $timestamp >= $currentTime;
}

function datetimeToDate($dt)
{
    return (new DateTime($dt))->format('d.m.Y');
}

function failed($valid)
{
    foreach ($valid as $key => $value) {
        if (! $value) {
            return true;
        }
    }
    return false;
}

function strtotimestamp($str)
{
    $what = ['послезавтра', 'сегодня', 'завтра', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье', 'в'];
    $to = ['tomorrow + 1 day', 'now', 'tomorrow', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', ''];
    $processed = str_replace($what , $to , $str);
    $timestamp = strtotime($processed);
    return $timestamp;
}

function strtodatetime($str)
{
    $timestamp = strtotimestamp($str);
    $datetime = date("Y-m-d H:i:s", $timestamp);
    return $datetime;
}