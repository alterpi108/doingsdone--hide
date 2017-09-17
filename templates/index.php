<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <div class="radio-button-group">
        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio" checked="">
            <span class="radio-button__text">Все задачи</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Повестка дня</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Завтра</span>
        </label>

        <label class="radio-button">
            <input class="radio-button__input visually-hidden" type="radio" name="radio">
            <span class="radio-button__text">Просроченные</span>
        </label>
    </div>

    <label class="checkbox">
        <input id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox"
            <?php if ($show_completed) print('checked'); ?>>
            <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php
    $id = $_GET['id'] ?? 0;

    foreach ($tasks as $task):

        if ($id != 0 && $task['Категория'] != $projects[$id]) {
            continue;
        }

        if ($task['Выполнен'] === 'Да' && ! $show_completed) {
            continue;
        }
    ?>

        <tr class="tasks__item task <?php if (0) print('task--important'); // на будущее ?>
                                    <?php if ($task['Выполнен'] === 'Да') print('task--completed'); ?>">
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden" type="checkbox">
                    <span class="checkbox__text"><?= $task['Задача'] ?></span>
                </label>
            </td>

            <td class="task__date">
                <?= $task['Дата выполнения'] ?>
            </td>

            <td class="task__controls">
                <button class="expand-control" type="button" name="button"><?= $task['Задача'] ?></button>

                <ul class="expand-list hidden">
                    <li class="expand-list__item">
                        <a href="#">Выполнить</a>
                    </li>

                    <li class="expand-list__item">
                        <a href="#">Удалить</a>
                    </li>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
