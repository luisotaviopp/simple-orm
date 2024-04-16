<?php 
	require_once './UserModel.php';

	// Instantiating the model
	$new_user = new UserModel(
		"Du",
		"12345",
		"du@test.com",
		"48 9 9999 9999"
	);

	// Creating an entity (insert) -> Fields are defined by Model's constructor;
	$new_user->create_user();

	// Updating an entity -> Send the id and fields to update;
	$new_user->update_user(8, ['name' => 'Amanda']);

	// Select a unique user by id -> Send the desired fields to select;
	$user = $new_user->get_user(1, ['name', 'id', 'is_active']);
	var_dump($user);

	// Soft delete an entity (set is_active = false) -> Send the entity id;
	$new_user->delete_user(9);

	// List entity -> Send the limit and the desired fields to select;
	$users = $new_user->list_users(10, ['name', 'id', 'is_active']);

	// Select return is an associative array ['name' => 'example'], you can convert it in a JSON using plain PHP json_encode() method.
	echo json_encode(['users' => $users]);