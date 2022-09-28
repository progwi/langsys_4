<?php

require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Person.php';
require_once __DIR__ . '/../models/User.php';

class UserService
{
	private $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function create(
		$data = [
			"name" => "",
			"email" => "",
			"password" => "123456",
			"roles" => [],
			"person" => [
				"firstName" => "",
				"lastName" => "",
				"height" => 1.80,
				"birthDate" => ""
			]
		]
	) {
		$user = $this->exists($data['email']);
		if ($user) {
			return $user;
		}
		$person = new Person($data['person']);
		$user = new User($data);
		$user->setPerson($person);
		$this->setUserRoles($user, $data['roles']);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		return $user;
	}

	public function setUserRoles($user, $roles)
	{
		foreach ($roles as $role) {
			$roleEntity = $this->entityManager->find('Role', $role);
			$user->addRole($roleEntity);
		}
	}

	public function update($id, $data)
	{
		$user = $this->entityManager->find('User', $id);
		$user->setName($data['name']);
		$user->setEmail($data['email']);
		$user->setPassword($data['password']);
		$person = $user->getPerson();
		$person->setFirstName($data['firstName']);
		$person->setLastName($data['lastName']);
		$person->setHeight($data['height']);
		$person->setBirthDate($data['birthDate']);
		$this->entityManager->flush();
	}

	public function delete($id)
	{
		$user = $this->entityManager->find('User', $id);
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}

	public function list()
	{
		$users = $this->entityManager->getRepository('User')->findAll();
		return $users;
	}

	public function find($id)
	{
		$user = $this->entityManager->find('User', $id);
		return $user;
	}

	public function exists($email)
	{
		$user = $this->entityManager->getRepository('User')->findOneBy(['email' => $email]);
		return $user;
	}
}
