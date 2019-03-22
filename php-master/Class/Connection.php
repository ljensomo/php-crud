<?php
	class Connection{
		
		private $server;
		private $username;
		private $password;
		private $database;
		protected $conn;

		protected function setDSN($server,$username,$password,$database){
			$this->server = $server;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
		}

		public function openConnection(){
			try {
				$this->conn = new PDO("mysql:host=".$this->server.";dbname=".$this->database,$this->username,$this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				// connection success
			} catch (PDOException $e) {
				echo "Database connection failed! " . $e->getMessage();
			}
		}

		public function closeConnection(){
			$this->conn = null;
		}
	}
?>