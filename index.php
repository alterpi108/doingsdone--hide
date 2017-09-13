<?php

include 'functions.php';

// показывать или нет выполненные задачи
//$show_complete_tasks = rand(0, 1);

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// текущая метка времени
$current_ts = strtotime('now midnight');







if (!isset($_SESSION['user'])) {
    $modal = false;
    if (isset($_GET['login'])) {
        $modal = true;
    }

    $page = template_render('templates/guest.php', [
        'modal' => $modal
    ]);
    print($page);
    die();
}









$projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'Задача' => 'Собеседование в IT компании',
        'Дата выполнения' => '01.06.2018',
        'Категория' => 'Работа',
        'Выполнен' => 'Нет'
    ],
    [
        'Задача' => 'Выполнить тестовое задание',
        'Дата выполнения' => '25.05.2018',
        'Категория' => 'Работа',
        'Выполнен' => 'Нет'
    ],
    [
        'Задача' => 'Сделать задание первого раздела',
        'Дата выполнения' => '21.04.2018',
        'Категория' => 'Учеба',
        'Выполнен' => 'Да'
    ],
    [
        'Задача' => 'Встреча с другом',
        'Дата выполнения' => '22.04.2018',
        'Категория' => 'Входящие',
        'Выполнен' => 'Нет'
    ],
    [
        'Задача' => 'Купить корм для кота',
        'Дата выполнения' => 'Нет',
        'Категория' => 'Домашние дела',
        'Выполнен' => 'Нет'
    ],
    [
        'Задача' => 'Заказать пиццу',
        'Дата выполнения' => 'Нет',
        'Категория' => 'Домашние дела',
        'Выполнен' => 'Нет'
    ]
];


$name = '';
$project = '';
$date = '';

$name_valid = true;
$project_valid = true;
$date_valid = true;

$show_modal = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $project = $_POST['project'];
    $date = $_POST['date'];

    if ($name == '') {
        $name_valid = false;
    }
    if ($project == '') {
        $project_valid = false;
    }
    if ($date == '') {
        $date_valid = false;
    }

    if ($name_valid && $project_valid && $date_valid) {
        array_unshift($tasks, [
            'Задача' => $name,
            'Дата выполнения' => $date,
            'Категория' => $project,
            'Выполнен' => 'Нет'
        ]);

        // сохранение файла
        if (isset($_FILES['preview'])) {
            $file_name = $_FILES['preview']['name'];
            move_uploaded_file($_FILES['preview']['tmp_name'], __DIR__ . '/' . $file_name);
        }
    } else {
        $show_modal = true;
    }
}


$page_main = template_render('templates/index.php', [
    'projects' => $projects,
    'tasks' => $tasks
]);


if (isset($_GET['add'])) {
    $show_modal = true;
}


if ($show_modal) {
    $modal = template_render('templates/modal.php', [
        'name'    => $name,
        'project' => $project,
        'date'    => $date,
        'name_valid'    => $name_valid,
        'project_valid' => $project_valid,
        'date_valid'    => $date_valid
    ]);
    $overlay = true;
} else {
    $modal = '';
    $overlay = false;
}


$page = template_render('templates/layout.php', [
    'page_title' => 'Дела в порядке',
    'projects' => $projects,
    'tasks' => $tasks,
    'page_main' => $page_main,
    'overlay' => $overlay,
    'modal' => $modal
]);

print($page);
