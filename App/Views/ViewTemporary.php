<?php
declare(strict_types=1);
/**
 * @var View $this
 * @var TemporaryAccount[] $acccounts
 */

use App\AppConfig;
use App\Models\TemporaryAccount;
use Sdk\Render\View;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/permanent">View permanent</a>
<h3>View temporary e-mails</h3>
<table>
    <thead>
        <tr>
            <th>E-mail address</th>
            <th>Password</th>
            <th>Expires</th>
            <th>Status</th>
            <th>Options</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($acccounts as $account) { ?>
                <tr>
                    <td><?= $account->emailAddress ?></td>
                    <td><?= $account->password ?>/td>
                    <td><?= $account->expiresString() ?></td>
                    <td><?= $account->status ?></td>
                    <td>
                        <?= $account->getRoundcubeLink() ?>
                        <a href="/manage-temp/<?= $account->username ?>">Manage</a>
                    </td>
                </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
