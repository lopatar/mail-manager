<?php
declare(strict_types=1);

/**
 * @var View $this
 * @var PermanentAccount[] $acccounts
 */

use App\Models\PermanentAccount;
use Sdk\Render\View;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/temporary">View temporary</a>
<h3>View permanent e-mails</h3>
<table>
    <thead>
    <tr>
        <th>E-mail address</th>
        <th>Status</th>
        <th>Options</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($acccounts as $account) {
        ?>
        <tr>
            <td><?= $account->emailAddress ?></td>
            <td><?= $account->status->name ?></td>
            <td>
                <?= $account->getRoundcubeLink() ?>
                <a href="/manage/<?= $account->username ?>">Manage</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
