create database mailManager;

use mailManager;

create table temporaryAccounts
(
    name     varchar not null primary key,
    password varchar not null,
    expires  int     not null default CURRENT_TIMESTAMP
);