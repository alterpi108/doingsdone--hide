/* PhpStorm */
-- noinspection SqlDialectInspectionForFile
-- noinspection SqlNoDataSourceInspectionForFile


CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8;


CREATE TABLE project (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    user INT         NOT NULL,    /* пользователь, создавший проект */
    name VARCHAR(60) NOT NULL
);

CREATE TABLE task (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    user     INT NOT NULL,    /* пользователь, создавший задачу */
    project  INT NOT NULL,    /* к какому проекту принадлежит */
    name     VARCHAR(256) NOT NULL,
    created  DATETIME     NOT NULL,
    finished DATETIME,
    deadline DATETIME,
    file     VARCHAR(1024)
);

CREATE TABLE user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    reg_date DATETIME     NOT NULL   DEFAULT NOW(),
    name     VARCHAR(256) NOT NULL,
    email    VARCHAR(256) NOT NULL,
    password CHAR(60)     NOT NULL,    /* хешированный */
    contacts VARCHAR(1024)
);
