<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\AppConfig;
use App\Models\Enums\AccountStatus;

trait AccountManageUtils
{
    function getManagementControls(bool $permanentAccount): string
    {
        $roundcubeLink = AppConfig::ROUNDCUBE_LINK;

        if ($roundcubeLink !== null) {
            $roundcubeLink = "<a target=\"_blank\" href=\"$roundcubeLink?_user=$this->username\"><button>Redirect to Roundcube</button></a>";
        }

        $api = ($permanentAccount) ? 'permanent' : 'temporary';

        $deleteForm = "<form method=\"POST\" action=\"/api/$api/delete\"><input type=\"hidden\" value=\"$this->username\" name=\"username\"><button type=\"submit\">Schedule deletion</button></form>";

        return "$roundcubeLink$deleteForm";
    }

    function isCreated(): bool
    {
        return $this->status === AccountStatus::CREATED;
    }
}