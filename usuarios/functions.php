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
	
					// Verifica se o campo remove_foto foi ativado
					if (isset($_POST['remove_foto']) && $_POST['remove_foto'] == '1') {
						$existingUsuario = find('usuarios', $id);
						if ($existingUsuario['foto'] !== 'semimagem.jpg') {
							unlink("fotos/" . $existingUsuario['foto']); // Apaga a imagem antiga
						}
						$usuario['foto'] = 'semimagem.jpg'; // Define a imagem padrão
					}
	
					// Processamento do upload de nova foto, se não for remoção
					elseif (!empty($_FILES["foto"]["name"])) {
						$pasta_destino = "fotos/";
						$arquivo_destino = $pasta_destino . basename($_FILES["foto"]["name"]);
						$nomearquivo = basename($_FILES["foto"]["name"]);
						$nome_temp = $_FILES["foto"]["tmp_name"];
						$tipo_arquivo = strtolower(pathinfo($arquivo_destino, PATHINFO_EXTENSION));
						$tamanho_arquivo = $_FILES["foto"]["size"];
	
						// Verifica se a imagem enviada já existe
						if (file_exists($arquivo_destino)) {
							$existingUsuario = find('usuarios', $id);
							if ($existingUsuario['foto'] !== "semimagem.jpg" && $existingUsuario['foto'] !== $nomearquivo) {
								unlink($pasta_destino . $existingUsuario['foto']);
							}
							$usuario['foto'] = $nomearquivo;
						} else {
							upload($pasta_destino, $arquivo_destino, $tipo_arquivo, $nome_temp, $tamanho_arquivo);
	
							if ($_SESSION['type'] == "success") {
								$existingUsuario = find('usuarios', $id);
								if ($existingUsuario['foto'] !== "semimagem.jpg") {
									unlink($pasta_destino . $existingUsuario['foto']);
								}
								$usuario['foto'] = $nomearquivo;
							} else {
								unset($usuario['foto']); // Mantém a foto antiga em caso de falha no upload
							}
						}
					} else {
						// Nenhuma nova foto enviada e não marcou remoção, então mantém a atual
						$existingUsuario = find('usuarios', $id);
						$usuario['foto'] = $existingUsuario['foto'];
					}
	
					if ($usuario['user'] == "admin" and !empty($usuario['foto'])) {
						$_SESSION['foto'] = $usuario['foto']; 
					}

					// Atualiza as informações no banco de dados
					update('usuarios', $id, $usuario);
					clear_messages();
					header('location: index.php');
				} else {
					// Carrega os dados para o formulário caso não tenha sido enviado
					global $usuario;
					$usuario = find('usuarios', $id);
				}
			} else {
				header('location: index.php');
			}
		} catch (Exception $e) {
			$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
			$_SESSION['type'] = "danger";
		}
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

	/**
	* Gerando PDF
	*/
	function pdf_usuarios($p = null)
	{
		date_default_timezone_set('America/Sao_Paulo'); 
		ob_start();
		include PDF;
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage('P'); // Paisagem
		$pdf->Titulo("Lista de Usuários");

		// Larguras das colunas (em mm)
		$larguras = [20, 80, 50, 20];
		$headers = ['ID', 'Nome', 'Usuário', 'Foto'];

		$pdf->Cabecalho($headers, $larguras);

		$usuarios = $p ? filter("usuarios", "nome LIKE '%$p%'") : find_all("usuarios");

		foreach ($usuarios as $u) {
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetX(($pdf->GetPageWidth() - array_sum($larguras)) / 2);

			// Cor alternada de fundo
			$pdf->SetFillColor(($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255);

			$alturaLinha = 20;

			$pdf->Cell($larguras[0], $alturaLinha, $u['id'], 1, 0, 'C', true);
			$pdf->Cell($larguras[1], $alturaLinha, $pdf->converteTexto($u['nome']), 1, 0, 'C', true);
			$pdf->Cell($larguras[2], $alturaLinha, $pdf->converteTexto($u['user']), 1, 0, 'C', true);

			// Célula de imagem
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->Cell($larguras[3], $alturaLinha, '', 1, 0, 'C', true);

			$nomeFoto = (!empty($u['foto']) && is_file("fotos/" . $u['foto'])) ? $u['foto'] : "semimagem.png";
			$foto = "fotos/" . $nomeFoto;

			$larguraImagem = 15;
			$alturaImagem = 15;
			$offsetX = $x + ($larguras[3] - $larguraImagem) / 2;
			$offsetY = $y + ($alturaLinha - $alturaImagem) / 2;

			try {
				$pdf->SetDrawColor(0, 0, 0); // Borda preta
				$pdf->SetLineWidth(0.5);
				$pdf->Rect($offsetX - 1, $offsetY - 1, $larguraImagem + 2, $alturaImagem + 2, 'D');
				$pdf->Image($foto, $offsetX, $offsetY, $larguraImagem, $alturaImagem);
			} catch (Exception $e) {
				$pdf->SetXY($x, $y);
				$pdf->Cell($larguras[3], $alturaLinha, 'Imagem não encontrada', 0, 0, 'C');
			}

			$pdf->Ln();
		}

		ob_clean();
		$pdf->Output('D', 'usuarios_' . date('dmY') . '.pdf');
		ob_end_flush();
	}
?>