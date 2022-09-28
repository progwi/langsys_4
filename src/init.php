<?php

require_once "bootstrap.php";
require_once __DIR__ . '/services/user.service.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$userService = new UserService($entityManager);

$list = $userService->list();

foreach ($list as $user) {
	echo $user->getName() . "\n";
}