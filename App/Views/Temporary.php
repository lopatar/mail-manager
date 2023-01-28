<?php
declare(strict_types=1);
/**
 * @var TemporaryAccount[] $accounts
 */

/**
 * @var View $this
 */

use App\AppConfig;
use App\Models\TemporaryAccount;
use Sdk\Render\View;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/permanent">Manage permanent</a>
<h3>Manage temporary e-mails</h3>
<table>
    <thead>
        <tr>
            <th>E-mail address</th>
            <th>Expires</th>
            <th>Options</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($accounts as $account) { ?>
                <tr>
                    <td><?= $account->emailAddress ?></td>
                    <td><?= $account->expiresString() ?></td>
                    <td>
                        <?php if (AppConfig::ROUNDCUBE_LINK !== '') { ?>
                            <a href="<?= AppConfig::ROUNDCUBE_LINK ?>?_user=<?= $account->username ?>">Redirect to Roundcube</a> |
                        <?php } ?>
                        <a href="/manage-temp/<?= $account->username ?>">Manage</a>
                    </td>
                </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
