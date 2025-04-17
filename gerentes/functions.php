<?php

	include("../config.php");
	include(DBAPI);

	$gerentes = null;
	$gerente = null;

	/**
	 *  Listagem de Gerentes
	 */
	function index() {
		global $gerentes;
		if (!empty($_POST['nome'])) {
			$search = $_POST['nome'];
			$gerentes = filter("gerentes", "nome LIKE '%" . $search . "%'");
		} else {
			$gerentes = find_all("gerentes");
		}
	}

	/**
	 *  Cadastro de Gerentes
	 */
	function add() {
		if (!empty($_POST['gerente'])) {
			try {
				$gerente = $_POST['gerente'];
				$today = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
				$gerente['modificacao'] = $gerente['criacao'] = $today->format("Y-m-d H:i:s");

				// Define imagem padrão caso não seja enviada
				$gerente['foto'] = empty($_FILES["foto"]["name"]) ? "semimagem.jpg" : upload_foto($_FILES["foto"]);

				save("gerentes", $gerente);
				clear_messages();
				header("location: index.php");
			} catch (Exception $e) {
				$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
				$_SESSION['type'] = "danger";
			}
		}
	}

	/**
	 *  Atualização/Edição de Gerente
	 */
	function edit() {
		$now = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

		if (isset($_GET['id'])) {
			$id = $_GET['id'];

			if (isset($_POST['gerente'])) {
				$gerente = $_POST['gerente'];
				$gerente['modificacao'] = $now->format("Y-m-d H:i:s");

				if (!empty($_FILES["foto"]["name"])) {
					$foto_antiga = find('gerentes', $id)['foto'];
					if ($foto_antiga && $foto_antiga != "semimagem.jpg") {
						@unlink("fotos/" . $foto_antiga);
					}
					$gerente['foto'] = upload_foto($_FILES["foto"]);
				} else {
					$gerente['foto'] = find('gerentes', $id)['foto'];
				}

				update('gerentes', $id, $gerente);
				clear_messages();
				header('location: index.php');
			} else {
				global $gerente;
				$gerente = find('gerentes', $id);
			}
		} else {
			header('location: index.php');
		}
	}

	/**
	 *  Função auxiliar de upload de foto
	 */
	function upload_foto($foto) {
		$pasta_destino = "fotos/";
		$nomearquivo = basename($foto["name"]);
		$arquivo_destino = $pasta_destino . $nomearquivo;

		if (!file_exists($arquivo_destino)) {
			$tipo_arquivo = strtolower(pathinfo($arquivo_destino, PATHINFO_EXTENSION));
			$tamanho_arquivo = $foto["size"];
			$nome_temp = $foto["tmp_name"];

			if (!getimagesize($nome_temp)) {
				throw new Exception("O arquivo não é uma imagem!");
			}
			if ($tamanho_arquivo > 5000000) {
				throw new Exception("O arquivo é muito grande!");
			}
			if (!in_array($tipo_arquivo, ["jpg", "jpeg", "png", "gif"])) {
				throw new Exception("Tipo de arquivo inválido!");
			}
			if (!move_uploaded_file($nome_temp, $arquivo_destino)) {
				throw new Exception("Erro ao mover o arquivo para o destino.");
			}
		}
		return $nomearquivo;
	}

	/**
	 *  Visualização de um Gerente
	 */
	function view($id = null) {
		global $gerente;
		$gerente = find("gerentes", $id);
	}

	/**
	 *  Exclusão de um Gerente
	 */
	function delete($id = null) {
		global $gerente;
		$gerente = find('gerentes', $id);

		if ($gerente['foto'] && $gerente['foto'] != "semimagem.jpg") {
			@unlink("fotos/" . $gerente['foto']);
		}

		remove('gerentes', $id);
		clear_messages();
		header('location: index.php');
	}

?>