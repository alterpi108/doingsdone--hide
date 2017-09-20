<?php

function template_render($path, $data)
{
    extract($data);

    ob_start();
    include $path;
    $result = ob_get_clean();

    return $result;
}

/**
 * Складывает два числа.
 *
 * @param Task[] $a
 * @param Project $b
 * @return integer
 */
function count_tasks($tasks, $project)
{
    if ($project === 'Все') {
        return sizeof($tasks);
    }

    $count = 0;

    foreach ($tasks as $task) {
        if ($task['Категория'] === $project) {
            $count++;
        }
    }

    return $count;
}
