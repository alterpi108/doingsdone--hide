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


-- Проект "Входищие" появляется у нового пользователя по умолчанию
INSERT INTO project SET user = 1, name = "Входящие";
INSERT INTO project SET user = 2, name = "Входящие";
INSERT INTO project SET user = 3, name = "Входящие";
INSERT INTO project SET user = 4, name = "Входящие";
