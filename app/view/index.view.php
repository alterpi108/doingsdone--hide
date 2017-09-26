<?= renderTemplate('partials/header', ['title' => 'Управление задачами', 'overlay' => ($projectModal || $taskModal)]) ?>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <header class="main-header">
            <a href="/">
                <img src="/public/img/logo.png" width="153" height="42" alt="Логитип Дела в порядке">
            </a>

            <div class="main-header__side">
                <a class="main-header__side-item button button--plus" href="/add-task">Добавить задачу</a>

                <div class="main-header__side-item user-menu">
                    <div class="user-menu__image">
                        <img src="/public/img/user-pic.jpg" width="40" height="40" alt="Пользователь">
                    </div>

                    <div class="user-menu__data">
                        <p><?= $userName ?></p>

                        <a href="/logout">Выйти</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($projects as $project): ?>
                            <?php if ((int) $project['id'] === $currentProject): ?>
                                <li class="main-navigation__list-item main-navigation__list-item--active">
                            <?php else: ?>
                                <li class="main-navigation__list-item">
                            <?php endif; ?>

                                <a class="main-navigation__list-item-link" href="/project/<?= $project['id'] ?>">
                                    <?= $project['name'] ?>
                                </a>
                                <span class="main-navigation__list-item-count"><?= $project['count'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button" href="/add-project">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="/search" method="get">
                    <input class="search-form__input" type="text" name="q" value="<?= $query ?>" placeholder="Поиск по задачам">
                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a class="tasks-switch__item <?php if ($filter === 'all')      echo 'tasks-switch__item--active' ?>" href="?filter=all">Все задачи</a>
                        <a class="tasks-switch__item <?php if ($filter === 'today')    echo 'tasks-switch__item--active' ?>" href="?filter=today">Повестка дня</a>
                        <a class="tasks-switch__item <?php if ($filter === 'tomorrow') echo 'tasks-switch__item--active' ?>" href="?filter=tomorrow">Завтра</a>
                        <a class="tasks-switch__item <?php if ($filter === 'overdue')  echo 'tasks-switch__item--active' ?>" href="?filter=overdue">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($showCompleted) echo 'checked' ?>>
                        <span class="checkbox__text" onclick="showCompleted()">Показывать выполненные</span>
                    </label>
                </div>

                <table class="tasks">
                    <?php
                        foreach($tasks as $task) {
                            $done = (bool) $task['done'];
                            $deadline = $task['deadline'] ? $task['deadline'] : '&mdash;';
                            $important = date('d-m-Y') === date('d-m-Y', (strtotime($deadline)));
                    ?>

                    <?php if ($done): ?>
                        <tr class="tasks__item  task  task--completed">
                    <?php elseif ($important): ?>
                        <tr class="tasks__item  task  task--important">
                    <?php else: ?>
                        <tr class="tasks__item  task">
                    <?php endif; ?>

                            <td class="task__select">
                                <label class="checkbox task__checkbox">
                                    <input class="checkbox__input visually-hidden" type="checkbox"
                                        onclick="completeTask(<?= $task['id'] ?>);"
                                        <?php if ($task['done']) echo 'checked'; ?>>
                                    <span class="checkbox__text"><?= $task['name'] ?></span>
                                </label>
                            </td>

                            <td class="task__file">
                                <?php if ($task['file']): ?>
                                    <a class="download-link" href="/public/userfiles/<?= $task['file'] ?>"><?= $task['file'] ?></a>
                                <?php endif; ?>
                            </td>

                            <td class="task__date"><?= $deadline ?></td>

                            <td class="task__controls">
                                <button class="expand-control" type="button" name="button">Открыть список комманд</button>
                                <ul class="expand-list hidden">
                                    <li class="expand-list__item"><a href="#">Выполнить</a></li>
                                    <li class="expand-list__item"><a href="#">Удалить</a></li>
                                    <li class="expand-list__item"><a href="#">Дублировать</a></li>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </main>
        </div>
    </div>
</div>

<?php if ($projectModal) echo renderTemplate('partials/add-project-modal', ['value' => $value, 'valid' => $valid]); ?>

<?php if ($taskModal) echo renderTemplate('partials/add-task-modal', ['projects' => $projects, 'value' => $value, 'valid' => $valid]); ?>

<?= renderTemplate('partials/footer', ['loadScript' => true]) ?>
