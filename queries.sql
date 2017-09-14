/* PhpStorm */
-- noinspection SqlNoDataSourceInspectionForFile
-- noinspection SqlDialectInspectionForFile


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



/* Добавлю пока проекты для пользователя Роман.

   Пока непонятна работа с проектами. Какие проекты должны быть по умолчанию
   у нового пользователя. К примеру, проект "Все" должен точно быть.
 */
INSERT INTO project SET user = 4, name = "Все";
INSERT INTO project SET user = 4, name = "Входящие";
INSERT INTO project SET user = 4, name = "Учеба";
INSERT INTO project SET user = 4, name = "Работа";
INSERT INTO project SET user = 4, name = "Домашние дела";
INSERT INTO project SET user = 4, name = "Авто";










CREATE TABLE project (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    user INT         NOT NULL,    /* пользователь, создавший проект */
    name VARCHAR(60) NOT NULL
);