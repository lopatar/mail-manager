<?php
declare(strict_types=1);

namespace App;

abstract class AppConfig
{
    public const EMAIL_DOMAIN = 'lopatar.me';
    public const ROUNDCUBE_LINK = 'https://mail.lopatar.me/';
    public const MAX_TEMPORARY_EMAIL_MINUTES = 360;
    public const DEFAULT_TIMEZONE = 'Europe/Prague';
}