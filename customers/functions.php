<?php

	include("../config.php");
	include(DBAPI);

	$customers = null;
	$customer  = null;

	/**
	 * Listagem de Clientes
	 */
	function index() {
		global $customers;
		if (!empty($_POST['name'])) {
			$search = $_POST['name'];
			$customers = filter("customers", "name LIKE '%" . $search . "%'");
		} else {
			$customers = find_all("customers");
		}
	}

	/**
	 * Cadastro de Clientes
	 */
	function add() {
		if (!empty($_POST['customer'])) {
			try {
				$today = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

				$customer = $_POST['customer'];
				$customer['created']  = $today->format("Y-m-d H:i:s");
				$customer['modified'] = $customer['created'];

				$customer['foto'] = empty($_FILES["foto"]["name"]) ? "semimagem.jpg" : upload_foto($_FILES["foto"]);

				save('customers', $customer);
				clear_messages();
				header('location: index.php');
			} catch (Exception $e) {
				$_SESSION['message'] = "Aconteceu um erro: " . $e->getMessage();
				$_SESSION['type'] = "danger";
			}
		}
	}

	/**
	 * Atualização/Edicao de Cliente
	 */
	function edit() {
		$now = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

		if (isset($_GET['id'])) {
			$id = $_GET['id'];

			if (isset($_POST['customer'])) {
				$customer = $_POST['customer'];
				$customer['modified'] = $now->format("Y-m-d H:i:s");
				
				if (!empty($_FILES["foto"]["name"])) {
					$foto_antiga = find('customers', $id)['foto'];
					if ($foto_antiga && $foto_antiga != "semimagem.jpg") {
						@unlink("fotos/" . $foto_antiga);
					}
					$customer['foto'] = upload_foto($_FILES["foto"]);
				} elseif (!empty($_POST['remove_foto']) && $_POST['remove_foto'] == '1') {
					$foto_antiga = find('customers', $id)['foto'];
					if ($foto_antiga && $foto_antiga != "semimagem.jpg") {
						@unlink("fotos/" . $foto_antiga);
					}
					$customer['foto'] = "semimagem.jpg";
				} else {
					$customer['foto'] = find('customers', $id)['foto'];
				}				

				update('customers', $id, $customer);
				clear_messages();
				header('location: index.php');
			} else {
				global $customer;
				$customer = find('customers', $id);
			}
		} else {
			header('location: index.php');
		}
	}

	/**
	 * Função auxiliar de upload de foto
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
	 * Visualização de um Cliente
	 */
	function view($id = null) {
		global $customer;
		$customer = find("customers", $id);
	}

	/**
	 * Exclusão de um Cliente
	 */
	function delete($id = null) {
		global $customer;
		$customer = remove('customers', $id);
		clear_messages();
		header('location: index.php');
	}


	/**
	* Gerando PDF
	*/
	function pdf_clientes($p = null)
	{
		date_default_timezone_set('America/Sao_Paulo'); 
		ob_start();
		include PDF;
		$pdf = new PDF('L', 'mm', 'A4');
		$pdf->AliasNbPages();
		$pdf->AddPage('L'); // Paisagem para caber mais colunas
		$pdf->Titulo("Lista de Clientes");

		// Definir larguras das colunas (total não deve exceder 280mm em paisagem)
		$larguras = [15, 50, 30, 30, 30, 25, 30, 21];
		$headers = ['ID', 'Nome', 'CPF/CNPJ', 'Cidade', 'Estado', 'Telefone', 'Modificado', 'Foto'];

		$pdf->Cabecalho($headers, $larguras);

		$customers = $p ? filter("customers", "name LIKE '%$p%'") : find_all("customers");

		$alturaFixa = 21;

		foreach ($customers as $u) {

			// Calcular altura necessária para o nome (caso queira aplicar MultiCell também depois)
			$name = $pdf->converteTexto($u['name']);
			$nbLinhasNome = $pdf->NbLines($larguras[1], $name);
			$alturaNome = $nbLinhasNome * 6;

			$city = $pdf->converteTexto($u['city']);
			$nbLinhasEndereco = $pdf->NbLines($larguras[3], $city); // Corrigido para usar larguras[3] para a cidade
			$alturaEndereco = $nbLinhasEndereco * 6;

			// Maior altura entre os dois ou alturaFixa
			$alturaLinha = max($alturaEndereco, $alturaNome, $alturaFixa);

			// Verificar se há espaço na página atual
			$margemInferior = 15; // ajusta conforme o rodapé
			if ($pdf->GetY() + $alturaLinha > $pdf->GetPageHeight() - $margemInferior) {
				$pdf->AddPage();
			}

			$pdf->SetFont('Arial', '', 9);
			$pdf->SetX(($pdf->GetPageWidth() - array_sum($larguras)) / 2);

			// Alternar cor de fundo entre branco e cinza claro
			$pdf->SetFillColor(($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255);

			// Posição inicial da linha
			$xInicio = $pdf->GetX();
			$yInicio = $pdf->GetY();

			// ID
			$pdf->Cell($larguras[0], $alturaFixa, $u['id'], 1, 0, 'C', true);

			// Nome
			$xNome = $pdf->GetX();
			$pdf->Rect($xNome, $yInicio, $larguras[1], $alturaFixa, 'DF');
			$nome = $pdf->converteTexto($u['name']);
			$nbNome = $pdf->NbLines($larguras[1], $nome);
			$alturaNome = 6 * $nbNome;
			$offsetYNome = $yInicio + ($alturaFixa - $alturaNome) / 2;
			$pdf->SetXY($xNome, $offsetYNome);
			$pdf->MultiCell($larguras[1], 6, $nome, 0, 'C');
			$pdf->SetXY($xNome + $larguras[1], $yInicio);

			// CPF/CNPJ
			$formattedCPFCNPJ = format_cpf_cnpj($u['cpf_cnpj']);
			$pdf->Cell($larguras[2], $alturaLinha, $pdf->converteTexto($formattedCPFCNPJ), 1, 0, 'C', true);

			// Cidade
			$xCity = $pdf->GetX();
			$pdf->Rect($xCity, $yInicio, $larguras[3], $alturaFixa, 'DF'); // Corrigido para larguras[3]
			$city = $pdf->converteTexto($u['city']);
			$nbCity = $pdf->NbLines($larguras[3], $city); // Corrigido para larguras[3]
			$alturaCity = 6 * $nbCity;
			$offsetYCity = $yInicio + ($alturaFixa - $alturaCity) / 2;
			$pdf->SetXY($xCity, $offsetYCity);
			$pdf->MultiCell($larguras[3], 6, $city, 0, 'C');
			$pdf->SetXY($xCity + $larguras[3], $yInicio);

			// Estado
			$pdf->Cell($larguras[4], $alturaLinha, $pdf->converteTexto($u['state']), 1, 0, 'C', true);

			// Telefone
			$formattedPhone = telefone($u['phone']);
			$pdf->Cell($larguras[5], $alturaLinha, $formattedPhone, 1, 0, 'C', true);

			// Modificado
			$modified = !empty($u['modified']) ? date('d/m/Y H:i', strtotime($u['modified'])) : '';
			$pdf->Cell($larguras[6], $alturaLinha, $modified, 1, 0, 'C', true);

			// Imagem com borda e efeito "rounded"
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->Cell($larguras[7], $alturaLinha, '', 1, 0, 'C', true);

			$nomeFoto = (!empty($u['foto']) && is_file("fotos/" . $u['foto'])) ? $u['foto'] : "semimagem.png";
			$foto = "fotos/" . $nomeFoto;

			$larguraImagem = 15;
			$alturaImagem = 15;
			$offsetX = $x + ($larguras[7] - $larguraImagem) / 2;
			$offsetY = $y + ($alturaLinha - $alturaImagem) / 2;

			try {
				// Simula borda com retângulo atrás
				$pdf->SetDrawColor(0, 0, 0); // Borda preta
				$pdf->SetLineWidth(0.5);
				$pdf->Rect($offsetX - 1, $offsetY - 1, $larguraImagem + 2, $alturaImagem + 2, 'D');

				$pdf->Image($foto, $offsetX, $offsetY, $larguraImagem, $alturaImagem);
			} catch (Exception $e) {
				$pdf->SetXY($x, $y);
				$pdf->Cell($larguras[7], $alturaLinha, 'Imagem não encontrada', 0, 0, 'C');
			}

			$pdf->Ln();
		}
		ob_clean();
		$pdf->Output('D', 'clientes_' . date('dmY') . '.pdf');
		ob_end_flush();
	}
?>
