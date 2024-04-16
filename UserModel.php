<?php
	require_once './Model.php';
	class UserModel extends Model {
		public int $id;
		public string $name;
		public string $password;
		public string $email;
		public string $phone;
		
		protected $table = "user";

		protected $db = 'database.db';

		public function __construct(string $name, string $password, string $email, string $phone) {
			parent::__construct("user", "database.db");
		
			$this->name = $name;
			$this->password = $password;
			$this->email = $email;
			$this->phone = $phone;
		
			$new_db = !file_exists($this->db);
			
			$this->db = new SQLite3($this->db);
		
			if ($new_db) {
				$this->create_table();
			}
		}

		protected function create_table(): void {
			$query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				name TEXT NOT NULL,
				password TEXT NOT NULL,
				email TEXT NOT NULL,
				phone TEXT NOT NULL,
				created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
				is_active INTEGER NOT NULL DEFAULT 1
			)";

			$this->db->exec($query);
		}

		public function get_user(int $id, array $fields = []): array {
			return $this->select($id, $fields);
		}

		public function list_users(int $limit, array $fields = []): array {
			return $this->list($limit, $fields);
		}

		public function create_user(): bool {

			$data = [
				"name" => $this->name,
				"password" => $this->password,
				"email" => $this->email,
				"phone" => $this->phone,
			]; 

			unset($data['id']);

			return $this->insert($data);
		}

		public function update_user(int $id, array $new_data): bool {
			return $this->update($id, $new_data);
		}

		public function delete_user(int $id): bool {
			return $this->delete($id);
		}

		public function reactivate_user(int $id): bool {
			return $this->reactivate($id);
		}
	}