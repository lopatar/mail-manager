<?php
declare(strict_types=1);

use Sdk\Render\View;

/**
 * @var $this View
 * @var string $rotatedPassword
 * @var string $email
 */

$email = $this->getProperty('email');
$rotatedPassword = $this->getProperty('rotatedPassword');
?>
<a href="/permanent"><- View permanent</a> | <a href="/temporary">View temporary</a>
<p>E-mail: <?= $email ?> has been scheduled to get a new password: <b><?= $rotatedPassword ?></b></p>
</body>
</html>
