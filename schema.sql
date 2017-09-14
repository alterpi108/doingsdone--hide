/* Проект */

CREATE TABLE project (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    user INT         NOT NULL,    /* пользователь, создавший проект */
    name VARCHAR(60) NOT NULL
);


/* Задача */

CREATE TABLE task (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    user     INT NOT NULL,    /* пользователь, создавший задачу */
    project  INT NOT NULL,    /* к какому проекту принадлежит */
    name     VARCHAR(256) NOT NULL,
    created  DATETIME     NOT NULL,
    finished DATETIME,
    deadline DATETIME,
    file     VARCHAR(1024),
);


/* Пользователь */

CREATE TABLE user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    reg_date DATETIME     NOT NULL,
    name     VARCHAR(256) NOT NULL,
    email    VARCHAR(256) NOT NULL,
    password CHAR(60)     NOT NULL,   
    contacts VARCHAR(1024),
);

/* CREATE UNIQUE INDEX email ON user(email); -- не нужно, потому что задан PRIMARY KEY */

