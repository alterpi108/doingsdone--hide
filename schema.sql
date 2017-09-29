-- Выполните этот код, чтобы подготовить базу данных для проекта.
-- И сразу можете регистрировать нового пользователя и пользоваться приложением.

CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8;

USE doingsdone;

CREATE TABLE project (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    user  INT         NOT NULL,
    name  VARCHAR(60) NOT NULL,
    count INT         NOT NULL  DEFAULT 0
);

CREATE TABLE task (
    id          INT  AUTO_INCREMENT PRIMARY KEY,
    user        INT  NOT NULL,
    project     INT  NOT NULL,
    name        VARCHAR(256) NOT NULL,
    done        BOOLEAN  DEFAULT FALSE,
    created     DATETIME DEFAULT NOW(),
    deadline    DATETIME,
    finished    DATETIME,
    file        VARCHAR(1024)
);

CREATE TABLE user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    reg_date DATETIME     NOT NULL   DEFAULT NOW(),
    name     VARCHAR(256) NOT NULL,
    email    VARCHAR(256) NOT NULL,
    password CHAR(60)     NOT NULL,
    contacts VARCHAR(1024)
);

CREATE UNIQUE INDEX email ON user(email);
