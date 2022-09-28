<?php
require_once __DIR__ . '/../models/Role.php';

class RoleService
{
	private $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function create($data = [
		"id" => "",
		"name" => ""
	])
	{
		$role = $this->exists($data['id']);
		if ($role) {
			return $role;
		}
		$role = new Role($data['id'], $data['name']);
		$this->entityManager->persist($role);
		$this->entityManager->flush();
		return $role;
	}

	public function exists($id)
	{
		$role = $this->entityManager->find('Role', $id);
		return $role != null;
	}

	public function update($id, $data)
	{
		$role = $this->entityManager->find('Role', $id);
		$role->setName($data['name']);
		$this->entityManager->flush();
	}

	public function delete($id)
	{
		$role = $this->entityManager->find('Role', $id);
		$this->entityManager->remove($role);
		$this->entityManager->flush();
	}
}