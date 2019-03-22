<?php
	class Query extends Connection{
		
		function __construct(){
			Connection::setDSN("localhost","root","","db_test");
			Connection::openConnection();
		}

		private $table;
		private $where;
		private $values;
		private $query;
		private $result;
		private $error;
		private $error_message;
		private $insert_columns;
		private $insert_values;
		private $update_columns;
		private $update_where;

		public function select($table, $where = null){
			$this->table = $table;
			self::setWhereClause($where);
			self::setQuery("select");
			self::executeQuery();
			return self::getResult();
		}

		public function insert($table,$request){
			$this->table = $table;
			$columns = self::getColumns(["table_name"=>$table]);
			$array_count = count($request);
			$x=1;
			$data = array();
			foreach ($request as $key => $value) {
				$this->insert_columns .= $key;
				$this->insert_values .= "?";
				array_push($data,$value);
				if($x<$array_count){
					$this->insert_columns .= ",";
					$this->insert_values .= ",";					
				}
				$x++;
			}
			self::setValues($data);
			self::setQuery("insert");
			self::executeQuery();
		}

		public function delete($table, $where = null){
			$this->table = $table;
			self::setWhereClause($where);
			self::setQuery("delete");
			self::executeQuery();
		}

		public function update($table,$columns,$where = null){
			$this->table = $table;
			$this->update_where = "WHERE ";
			$x=1;
			$y=1;
			$array_count = count($columns);
			$data = array();
			foreach ($columns as $key => $value) {
				$this->update_columns .= $key . " = ?";
				array_push($data,$value);
				if($x<$array_count){
					$this->update_columns .= ",";
				}
				$x++;
			}
			foreach ($where as $key2 => $value2) {
				$this->update_where .= $key2 . " = ?";
				array_push($data,$value2);
			}
			self::setValues($data);
			self::setQuery("update");
			self::executeQuery();
		}

		public function getColumns($where = null){
			self::setWhereClause($where);
			$query = "SELECT column_name FROM information_schema.COLUMNS " . $this->where;
			try {
				$result = $this->conn->prepare($query);
				$result->execute($this->values);
				$rows = $result->fetchAll(PDO::FETCH_ASSOC);
				self::setErrors(0,"");
				return $rows;
			} catch (Exception $e) {
				self::setErrors(1,$e->getMessage());
				echo $e->getMessage();
			}
		}

		private function setWhereClause($where){
			if(isset($where)){
				$data = array();
				$this->where = "WHERE";
				foreach ($where as $key => $value) {
					$this->where .= " " . $key . " = ?";
					array_push($data,$value);
				}
				self::setValues($data);
			}
		}

		private function executeQuery(){
			try {
				$result = $this->conn->prepare($this->query);
				$result->execute($this->values);
				$this->result = $result;
				self::unsetQueryVariables();
				self::setErrors(0,"");
			} catch (Exception $e) {
				self::setErrors(1,$e->getMessage());
			}
		}

		private function getResult(){
			$rows = $this->result->fetchAll(PDO::FETCH_ASSOC);
			return $rows;
		}

		public function rowCount(){
			$count = $this->result->rowCount();
			return $count;
		}

		private function setQuery($query){
			switch ($query){
				case "select":
					$this->query = "SELECT * FROM " . $this->table . " " . $this->where;
					break;
				case "insert":
					$this->query = "INSERT INTO " . $this->table . "(" . $this->insert_columns . ") VALUES (" . $this->insert_values .")";
				case "delete":
					$this->query = "DELETE FROM " . $this->table . " " . $this->where;
					break;
				case "update":
					echo $this->query = "UPDATE " . $this->table . " SET " . $this->update_columns . " " . $this->update_where;
			}
		}

		private function setValues($values){
			$this->values = $values;
		}

		private function unsetQueryVariables(){
			$this->query = "";
			$this->table = "";
			$this->where = "";
			$this->values = "";
			$this->insert_columns = "";
			$this->insert_values = "";
			$this->update_columns = "";
			$this->update_columns = "";
		}

		private function setErrors($error,$error_message){
			$this->error = $error;
			$this->error_message = $error_message;
		}

		public function getErrors(){
			return array("error"=>$this->error,"message"=>$this->error_message);
		}

		public function printErrorsJSON(){
			echo json_encode(
				array(
					"error"=>$this->error,
					"message"=>$this->error_message
				)
			);
		}

	}
?>