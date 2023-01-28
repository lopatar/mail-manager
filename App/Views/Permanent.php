<?php
declare(strict_types=1);

/**
 * @var PermanentAccount[] $accounts
 */

use App\Models\PermanentAccount;

$accounts = $this->getProperty('accounts');
?>
<a href="/"><- Go home</a> | <a href="/temporary">Manage temporary</a>
<h3>Manage permanent e-mails</h3>
<table>
    <thead>
    <tr>
        <th>E-mail account</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($accounts as $account) {
        ?>
        <tr>
            <td><?= $account->emailAddress ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
