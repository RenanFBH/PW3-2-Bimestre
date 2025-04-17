<?php
	
	/** funções do bd **/
	
	function open_database() {
		try {
			$conn = new PDO("mysql:host=". DB_HOST .";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (Exception $e) {
			throw $e;
			return null;
		}
	}

	function close_database($conn) {
		$conn = null;
	}

	function filter($table = null, $condition = null) {
		$database = open_database();
		$found = null;

		try {
			$sql = "SELECT * FROM " . $table;
			if ($condition) {
				$sql .= " WHERE " . $condition;
			}
			$stmt = $database->query($sql);
			$found = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$_SESSION['message'] = $e->getMessage();
			$_SESSION['type'] = 'danger';
		}

		close_database($database);
		return $found;
	}

	function find($table = null, $id = null) {
		try {
			$database = open_database();
			$stmt = $database->prepare("SELECT * FROM " . $table . " WHERE id = ?");
			$stmt->bindParam(1, $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$_SESSION['message'] = $e->getMessage();
			$_SESSION['type'] = 'danger';
			return null;
		}
	}

	function find_all($table) {
		return filter($table);
	}

	function save($table = null, $data = null) {
		$database = open_database();

		$columns = implode(',', array_keys($data));
		$placeholders = ':' . implode(', :', array_keys($data));

		$sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

		try {
			$stmt = $database->prepare($sql);
			$stmt->execute($data);
			$_SESSION['message'] = 'Registro cadastrado com sucesso.';
			$_SESSION['type'] = 'success';
		} catch (Exception $e) {
			$_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
			$_SESSION['type'] = 'danger';
		}

		close_database($database);
	}

	function update($table = null, $id = 0, $data = null) {
		$database = open_database();

		$items = '';
		foreach ($data as $key => $value) {
			$items .= "$key = :$key,";
		}
		$items = rtrim($items, ',');

		$sql = "UPDATE $table SET $items WHERE id = :id";

		try {
			$stmt = $database->prepare($sql);
			$data['id'] = $id;
			$stmt->execute($data);
			$_SESSION['message'] = 'Registro atualizado com sucesso.';
			$_SESSION['type'] = 'success';
		} catch (Exception $e) {
			$_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
			$_SESSION['type'] = 'danger';
		}

		close_database($database);
	}

	function remove($table = null, $id = null) {
		$database = open_database();

		try {
			$stmt = $database->prepare("DELETE FROM $table WHERE id = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$_SESSION['message'] = "Registro Removido com Sucesso.";
			$_SESSION['type'] = 'success';
		} catch (Exception $e) {
			$_SESSION['message'] = $e->getMessage();
			$_SESSION['type'] = 'danger';
		}

		close_database($database);
	}
	
	/** funções gerais **/
	
	function criptografia($senha) {
		
		$custo = "08";
		$salt = "CflfilePArK1BJomM0F6aJ";
		
		$hash = crypt($senha, "2a$" . $custo . "$" . $salt . "$");
		
		return $hash;
	}
	
	function clear_messages() {
		$_SESSION["message"] = null;
		$_SESSION["type"] = null;
	}
	
	function telefone( $dado ) {
		$tel = "(" . substr($dado, 0, 2) . ") " . substr($dado, 2, 5) . "-" .  substr($dado, 7, 4);	
		return $tel; 
	}
	
	function formatadata( $data, $formato) {
		$dt = new DateTime ($data, new DateTimeZone("America/Sao_Paulo"));
		return $dt->format($formato);
	}
	
	function cep( $cepdado ) {
		$cep = substr($cepdado, 0, 5) . "-" . substr($cepdado, 5, 3);		
		return $cep;
	}
	
	function format_cpf_cnpj($value) {
	
		$value = preg_replace("/[^0-9]/", "", $value);
		
		if (strlen($value) === 11) {
			// CPF: 000.000.000-00
			return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $value);
		} elseif (strlen($value) === 14) {
			// CNPJ: 00.000.000/0000-00
			return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $value);
		}
		
		return $value;
	}

?>

	