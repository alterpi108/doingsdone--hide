/* PhpStorm */
-- noinspection SqlNoDataSourceInspectionForFile
-- noinspection SqlDialectInspectionForFile


/************
 *  INSERT  *
 ************/

INSERT INTO user SET name = 'Игнат',
                     email = 'ignat.v@gmail.com',
                     password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka';

INSERT INTO user SET name = 'Леночка',
                     email = 'kitty_93@li.ru',
                     password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa';

INSERT INTO user SET name = 'Руслан',
                     email = 'warrior07@mail.ru',
                     password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW';

INSERT INTO user SET name = 'Роман',
                     email = 'mosceo@gmail.com',
                     password = '$2y$10$pPr7toZvGPe6X6VqeTUxxu42/M2DZHReJoDjCKKMihTiN5au6lZOC';


-- Добавлю пока проекты для пользователя Роман.
-- Пока непонятна работа с проектами. Какие проекты должны быть по умолчанию
-- у нового пользователя. К примеру, проект "Все" должен точно быть.
INSERT INTO project SET user = 4, name = "Все";
INSERT INTO project SET user = 4, name = "Входящие";
INSERT INTO project SET user = 4, name = "Учеба";
INSERT INTO project SET user = 4, name = "Работа";
INSERT INTO project SET user = 4, name = "Домашние дела";
INSERT INTO project SET user = 4, name = "Авто";


-- Все задачи только для пользователя Роман.
INSERT INTO task SET user = 4,
                     project = 4,
                     name = 'Собеседование в IT компании',
                     deadline = '2018-06-01';

INSERT INTO task SET user = 4,
                     project = 4,
                     name = 'Выполнить тестовое задание',
                     deadline = '2018-05-25';

INSERT INTO task SET user = 4,
                     project = 3,
                     name = 'Сделать задание первого раздела',
                     is_finished = TRUE,
                     deadline = '2018-04-21';

INSERT INTO task SET user = 4,
                     project = 2,
                     name = 'Встреча с другом',
                     deadline = '2018-04-22';

INSERT INTO task SET user = 4,
                     project = 5,
                     name = 'Купить корм для кота';

INSERT INTO task SET user = 4,
                     project = 5,
                     name = 'Заказать пиццу';


/**********
 *  CRUD  *
 **********/

-- получить список из всех проектов для одного пользователя
SELECT * FROM project WHERE user = 4;

-- получить список из всех задач для одного проекта
SELECT * FROM task WHERE user = 4 AND project = 4;

-- пометить задачу как выполненную
UPDATE task SET is_finished = TRUE WHERE id = 1000;

-- получить все задачи для завтрашнего дня
-- (! трудно понять, что тут требуется, по дедлайну искать или по другому полю)
SELECT * FROM task WHERE deadline >= NOW() + INTERVAL 1 DAY AND deadline < NOW() + INTERVAL 2 DAY;

-- обновить название задачи по её идентификатору
UPDATE task SET name = 'Постирать носки' WHERE id = 1000;
