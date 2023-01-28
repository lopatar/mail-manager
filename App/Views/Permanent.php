<?php
declare(strict_types=1);

/**
 * @var PermanentAccount[] $accounts
 */

/**
 * @var View $this
 */

use App\AppConfig;
use App\Models\PermanentAccount;
use Sdk\Render\View;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/temporary">Manage temporary</a>
<h3>Manage permanent e-mails</h3>
<table>
    <thead>
    <tr>
        <th>E-mail address</th>
        <th>Options</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($accounts as $account) {
        ?>
        <tr>
            <td><?= $account->emailAddress ?></td>
            <td>
                <?php if (AppConfig::ROUNDCUBE_LINK !== '') { ?>
                    <a href="<?= AppConfig::ROUNDCUBE_LINK ?>?_user=<?= $account->username ?>">Redirect to Roundcube</a> |
                <?php } ?>
                <a href="/manage/<?= $account->username ?>">Manage</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
