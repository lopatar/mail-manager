# Mail manager

# NOT FINISHED

Personal service, that is able to manage e-mail accounts, it is also able to generate temporary e-mail addresses

# TODO

- Fetch permanent accounts once on startup to prevent unnecessary calls to getent, re-fetch on management

# Features

- List all permanent e-mail accounts
    - Create new e-mail accounts
    - Rotate passwords - **TODO**
    - Redirect to Roundcube instance (if URL configured in AppConfig.php)
    - IMAP support - **TODO**
    - Delete accounts
- List all temporary e-mail accounts
    - Create new temporary e-mail accounts
        - Custom name
        - Custom expiration
    - Redirect to Roundcube instance (if URL configured in AppConfig.php)
    - IMAP support - **TODO**
    - Delete accounts

# Requirements - TODO

- Debian 11
- E-mail accounts in the "email" group
- Web server
- PHP 8.2
- MySQL/MariaDB database

# Installation - TODO
