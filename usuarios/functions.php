<?php

	include("../config.php");
	include(DBAPI);

	$usuarios = null;
	$usuario = null;

	/**
	 *  Listagem de Usuarios
	 */
	function index() {
		global $usuarios;
		if (!empty($_POST['user'])) {
			$search = $_POST['user'];
			$usuarios = filter("usuarios", "nome LIKE '%" . $search . "%'");
		} else {
			$usuarios = find_all("usuarios");
		}
	}
	
	function upload($pasta_destino, $arquivo_destino, $tipo_arquivo, $nome_temp, $tamanho_arquivo) {
		try {
			$nomearquivo = basename($arquivo_destino);
			$uploadOk = 1;

			// Verifica se o arquivo é uma imagem
			$check = getimagesize($nome_temp);
			if ($check !== false) {
				$_SESSION['message'] = "File is an image - " . $check["mime"] . ".";
				$_SESSION['type'] = "info";
				$uploadOk = 1;
			} else {
				throw new Exception("O arquivo não é uma imagem!");
			}

			// Verifica se o arquivo já existe
			if (file_exists($arquivo_destino)) {
				$uploadOk = 0;
				throw new Exception("Desculpe, o arquivo já existe!");
			}

			// Verifica o tamanho do arquivo
			if ($tamanho_arquivo > 5000000) {
				$uploadOk = 0;
				throw new Exception("Desculpe, o arquivo é muito grande!");
			}

			// Verifica o tipo de arquivo
			if ($tipo_arquivo != "jpg" && $tipo_arquivo != "png" && $tipo_arquivo != "jpeg" && $tipo_arquivo != "gif") {
				$uploadOk = 0;
				throw new Exception("Desculpe, só são permitidos arquivos de imagem JPG, JPEG, PNG e GIF!");
			}

			// Se o arquivo for válido, faz o upload
			if ($uploadOk == 1) {
				if (move_uploaded_file($nome_temp, $arquivo_destino)) {
					$_SESSION['message'] = "O arquivo " . htmlspecialchars($nomearquivo) . " foi armazenado.";
					$_SESSION['type'] = "success";
				} else {
					throw new Exception("Desculpe, o arquivo não pode ser enviado!");
				}
			} else {
				throw new Exception("Desculpe, o arquivo não pode ser enviado!");
			}
		} catch (Exception $e) {
			$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
			$_SESSION['type'] = "danger";
		}
	}

	
	/**
	 *  Cadastro de Usuarios
	 */
	function add() {
		if (!empty($_POST['usuario'])) {
			try {
				$usuario = $_POST['usuario'];
				$today = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
				
				if (!empty($_FILES["foto"]["name"])) {
					$pasta_destino = "fotos/";
					$arquivo_destino = $pasta_destino . basename($_FILES["foto"]["name"]);
					$nomearquivo = basename($_FILES["foto"]["name"]);
					$nome_temp = $_FILES["foto"]["tmp_name"];  // Corrigido para tmp_name
					$tipo_arquivo = strtolower(pathinfo($arquivo_destino, PATHINFO_EXTENSION));
					$tamanho_arquivo = $_FILES["foto"]["size"];

					// Verifique se o arquivo foi carregado corretamente antes de usar getimagesize
					if ($nome_temp && getimagesize($nome_temp)) {
						upload($pasta_destino, $arquivo_destino, $tipo_arquivo, $nome_temp, $tamanho_arquivo);
						$usuario['foto'] = $nomearquivo;
					} else {
						throw new Exception("Erro no carregamento da imagem.");
					}
				} else {
					// Define uma imagem padrão caso nenhuma seja enviada
					$usuario['foto'] = "semimagem.jpg";
				}

				if (!empty($usuario['password'])) {
					$senha = criptografia($usuario['password']);
					$usuario['password'] = $senha;
				}

				save("usuarios", $usuario);
				clear_messages();
				header("location: index.php");
			} catch (Exception $e) {
				$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
				$_SESSION['type'] = "danger";
			}
		}
	}


	/**
	 *	Atualizacao/Edicao de Usuario
	 */
	function edit() {
		try {
			if (isset($_GET['id'])) {
				$id = $_GET['id'];

				if (isset($_POST['usuario'])) {
					$usuario = $_POST['usuario'];

					// Mantém a senha existente caso o campo esteja vazio
					if (empty($usuario['password'])) {
						$existingUsuario = find('usuarios', $id); // Busca o usuário atual no banco
						$usuario['password'] = $existingUsuario['password']; // Mantém a senha existente
					} else {
						// Se a senha foi alterada, criptografa a nova senha
						$senha = criptografia($usuario['password']);
						$usuario['password'] = $senha;
					}

					// Processamento do upload de foto, caso haja uma nova
					if (!empty($_FILES["foto"]["name"])) {
						$pasta_destino = "fotos/";
						$arquivo_destino = $pasta_destino . basename($_FILES["foto"]["name"]);
						$nomearquivo = basename($_FILES["foto"]["name"]);
						$nome_temp = $_FILES["foto"]["tmp_name"];
						$tipo_arquivo = strtolower(pathinfo($arquivo_destino, PATHINFO_EXTENSION));
						$tamanho_arquivo = $_FILES["foto"]["size"];

						// Verifica se a imagem enviada já existe
						if (file_exists($arquivo_destino)) {
							// Se a imagem antiga não for padrão, exclua-a
							$existingUsuario = find('usuarios', $id);
							if ($existingUsuario['foto'] !== "semimagem.jpg" && $existingUsuario['foto'] !== $nomearquivo) {
								unlink($pasta_destino . $existingUsuario['foto']);
							}
							// Atualiza o nome da imagem no banco de dados
							$usuario['foto'] = $nomearquivo;
						} else {
							// Faz o upload da nova imagem
							upload($pasta_destino, $arquivo_destino, $tipo_arquivo, $nome_temp, $tamanho_arquivo);

							// Se o upload foi bem-sucedido, atualiza o nome da imagem
							if ($_SESSION['type'] == "success") {
								$existingUsuario = find('usuarios', $id);
								if ($existingUsuario['foto'] !== "semimagem.jpg") {
									unlink($pasta_destino . $existingUsuario['foto']); // Remove a imagem antiga
								}
								$usuario['foto'] = $nomearquivo;
							} else {
								unset($usuario['foto']); // Mantém a foto antiga em caso de falha
							}
						}
					} else {
						// Mantém a foto existente caso nenhuma nova foto tenha sido enviada
						$existingUsuario = find('usuarios', $id);
						$usuario['foto'] = $existingUsuario['foto'];
					}

					// Atualiza as informações no banco de dados
					update('usuarios', $id, $usuario);
					clear_messages();
					// Redireciona para a página inicial após a atualização
					header('location: index.php');
				} else {
					// Caso o formulário não tenha sido enviado, carrega as informações do usuário para o formulário
					global $usuario;
					$usuario = find('usuarios', $id);
				}
			} else {
				// Se não encontrar o ID, redireciona para a página inicial
				header('location: index.php');
			}
		} catch (Exception $e) {
			$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
			$_SESSION['type'] = "danger";
		}
	}
	
	/**
	* Gerando PDF
	*/

	function pdf($p = null) {
		// Instanciação da classe herdada
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		$usuarios = null;
		
		if($p) {
			$usuarios = filter("usuarios", "home like '%" . $p . "%'");
		} else {
			$usuarios = find_all("usuarios");
		}
		
		foreach ($usuarios as $usuario) {
			$pdf->Cell(0,10, $usuario['id'] . " - " . $usuario['home'] . " - " . $usuario['user']/0,1);
		}
		
		//
		/*
		for($i=1;$i<=40;$i++)
		$pdf->Cell(0,10,'Printing line number '.$i/0,1);
		*/
		$pdf->Output();
	}



	
	/**
	 *  Visualização de um Usuario
	 */
	function view($id = null) {
		global $usuario;
		$usuario = find('usuarios', $id);
	}
		
	//Exclusão de um Usuario

	function delete($id = null)
	{

		global $usuario;
		$usuario = remove('usuarios', $id);
		clear_messages();
		header('location: index.php');
	}

?>