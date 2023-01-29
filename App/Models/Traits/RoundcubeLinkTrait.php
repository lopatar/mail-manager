<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\AppConfig;

trait RoundcubeLinkTrait
{
    function getRoundcubeLink(): string
    {
        $roundcubeLink = AppConfig::ROUNDCUBE_LINK;

        if ($roundcubeLink === '') {
            return '';
        }

        $url = "$roundcubeLink?_user=$this->username";

        return "<a href=\"$url\">Redirect to roundcube | </a>";
    }
}