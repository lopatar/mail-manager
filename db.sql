CREATE TABLE Accounts
(
    name     VARCHAR(255) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    expires  INT                   DEFAULT NULL, /** If null, its an permanent account */
    status   int          NOT NULL DEFAULT 0
);

CREATE TABLE PanelUsers
(
    username VARCHAR(255) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);
