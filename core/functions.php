<?php

/**
 * Run a view.
 *
 * @param string
 * @param array
 *
 * @return void
 */
function view($name, $data = [])
{
    extract($data);
    require "app/view/{$name}.view.php";
}

/**
 * Render a template.
 *
 * @param string
 * @param array
 *
 * @return string
 */
function renderTemplate($path, $data)
{
    extract($data);
    ob_start();
    include 'app/view/' . $path . '.php';
    $result = ob_get_clean();

    return $result;
}

/**
 * Validate an email.
 *
 * @param string
 *
 * @return boolean
 */
function validateEmail($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate a password.
 *
 * @param string
 *
 * @return boolean
 */
function validatePassword($value)
{
    return $value !== '';
}

/**
 * Validate a name.
 *
 * @param string
 *
 * @return boolean
 */
function validateName($value)
{
    return $value !== '';
}

/**
 * Validate a project name.
 *
 * @param string
 *
 * @return boolean
 */
function validateProjectName($value)
{
    return $value !== '';
}

/**
 * Validate a date.
 *
 * @param string
 *
 * @return boolean
 */
function validateDate($str)
{
    $timestamp = strtotimestamp($str);
    $currentTime = strtotimestamp('now');
    return (bool) $timestamp && $timestamp >= $currentTime;
}

/**
 * Convert an SQL datetime to a date in format 'dd.mm.yyyy'
 *
 * @param string
 *
 * @return string
 */
function datetimeToDate($dt)
{
    return (new DateTime($dt))->format('d.m.Y');
}

/**
 * Check if a given associative array has at least one false value.
 *
 * @param array
 *
 * @return boolean
 */
function failed($valid)
{
    foreach ($valid as $key => $value) {
        if (! $value) {
            return true;
        }
    }
    return false;
}

/**
 * Convert a string to a timestamp.
 *
 * @param string
 *
 * @return integer
 */
function strtotimestamp($str)
{
    $what = ['послезавтра', 'сегодня', 'завтра', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье', 'в'];
    $to = ['tomorrow + 1 day', 'now', 'tomorrow', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', ''];
    $processed = str_replace($what , $to , $str);
    $timestamp = strtotime($processed);
    return $timestamp;
}

/**
 * Convert a string to the SQL datetime format.
 *
 * @param string
 *
 * @return string
 */
function strtodatetime($str)
{
    $timestamp = strtotimestamp($str);
    $datetime = date("Y-m-d H:i:s", $timestamp);
    return $datetime;
}
