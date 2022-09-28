<?php

require_once "bootstrap.php";
require_once __DIR__ . '/services/user.service.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$userService = new UserService($entityManager);

$list = $userService->list(1, 10);
echo "List of users: " . PHP_EOL;
$array = json_decode($list, true);
foreach ($array as $user) {
	echo $user['id'] . " - " . $user['person'] . PHP_EOL;
}
