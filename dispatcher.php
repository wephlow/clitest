<?php
require_once('vendor/autoload.php');

use carlonicora\minimalism\bootstrapper;

$bootstrapper = new bootstrapper('grace\\clitest');
$controller = $bootstrapper->loadController('dispatcher');
$controller->render();

exit;