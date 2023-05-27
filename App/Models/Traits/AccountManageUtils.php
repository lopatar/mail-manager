<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\AppConfig;
use App\Models\Enums\AccountStatus;
use Sdk\Database\MariaDB\Connection;

trait AccountManageUtils
{
    function getManagementControls(bool $permanentAccount): string
    {
        $roundcubeLink = AppConfig::ROUNDCUBE_LINK;

        if ($roundcubeLink !== null) {
            $roundcubeLink = "<a target=\"_blank\" href=\"$roundcubeLink?_user=$this->username\"><button>Redirect to Roundcube</button></a>";
        }

        $api = ($permanentAccount) ? 'permanent' : 'temporary';

        $rotateForm = '';

        if ($permanentAccount) {
            $rotateForm = "<form method=\"POST\" action=\"/api/$api/rotate-password\"><input type=\"hidden\" value=\"$this->username\"><button type=\"submit\">Rotate password</button></form>";
        }

        $deleteForm = "<form method=\"POST\" action=\"/api/$api/delete\"><input type=\"hidden\" value=\"$this->username\" name=\"username\"><button type=\"submit\">Schedule deletion</button></form>";

        return "$roundcubeLink$rotateForm$deleteForm";
    }

    function isCreated(): bool
    {
        return $this->status === AccountStatus::CREATED;
    }

    function schedulePasswordRotation(bool $permanentAccount, string $password): void
    {
        if ($permanentAccount) {
            $query = Connection::query('SELECT name FROM Accounts WHERE name=?', [$this->username]);

            if ($query->num_rows === 1) {
                return;
            }

            Connection::query('INSERT INTO Accounts(name, password, status) VALUES(?,?,?)', [$this->username, $password, AccountStatus::WAITING_FOR_PASSWORD_CHANGE->value], 'ssi');
            return;
        }

        Connection::query('UPDATE Accounts SET password=?, status=? WHERE name=?', [$password, AccountStatus::WAITING_FOR_PASSWORD_CHANGE->value],'ssi');
    }

    function scheduleDeletion(bool $permanentAccount): void
    {
        if ($permanentAccount) {
            $query = Connection::query('SELECT name FROM Accounts WHERE name=?', [$this->username]);

            if ($query->num_rows === 1) {
                return;
            }

            Connection::query('INSERT INTO Accounts(name, password, status) VALUES(?,?,?)', [$this->username, 'BLANK-PASSWORD', AccountStatus::WAITING_FOR_DELETION->value], 'ssi');
            return;
        }

        Connection::query('UPDATE Accounts SET status=? WHERE name=?', [AccountStatus::WAITING_FOR_DELETION->value, $this->username], 'is');
    }
}