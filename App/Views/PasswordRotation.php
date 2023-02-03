<?php
declare(strict_types=1);

use Sdk\Render\View;

/**
 * @var $this View
 * @var string $rotatedPassword
 * @var string $username
 */

$username = $this->getProperty('username');
$rotatedPassword = $this->getProperty('rotatedPassword');