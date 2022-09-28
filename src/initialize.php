<?php

require_once "bootstrap.php";
require_once __DIR__ . '/models/Role.php';
require_once __DIR__ . '/models/Person.php';
require_once __DIR__ . '/models/User.php';

require_once __DIR__ . '/services/role.service.php';
require_once __DIR__ . '/services/user.service.php';

if (!isset($entityManager)) {
	echo "Entity manager is not set.\n";
	return;
}

$roleService = new RoleService($entityManager);
$userService = new UserService($entityManager);

// First, we take the roles.json file which contains a list of roles, and proccess each
$initialRoles = json_decode(file_get_contents(__DIR__ . '/models/roles.json'), true);

foreach ($initialRoles as $role) {
	$roleService->create($role);
}

// Second, we take the users.json file which contains a list of users, and proccess each
$initialUsers = json_decode(file_get_contents(__DIR__ . '/models/users.json'), true);

foreach ($initialUsers as $user) {
	$userService->create($user);
}

// Querying for all users
$userRepository = $entityManager->getRepository(User::class);
$users = $userRepository->findAll();

foreach ($users as $user) {
	echo $user . PHP_EOL;
}

// Querying for a single user
$user = $userRepository->findOneBy(['id' => 1]);
echo $user . PHP_EOL;

// Querying with DQL
$dql = "SELECT u FROM User u WHERE u.id = ?1 ORDER BY u.name ASC";
$query = $entityManager->createQuery($dql);
$query->setParameter(1, 3);
$user = $query->getSingleResult();

echo $user . " is " . $user->maxRole() . PHP_EOL;

// Query  one user and his bigger role according its id
$dql = "SELECT u, r.id FROM User u JOIN u.roles r WHERE u.id = ?1 ORDER BY r.id DESC";
$query = $entityManager->createQuery($dql);
$query->setParameter(1, 3);
$userRole = $query->getSingleResult();

echo $userRole[0] . ' is ' . $userRole['id'] . PHP_EOL;

