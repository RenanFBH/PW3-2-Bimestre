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
					$foto_antiga = find('gerentes', $id)['foto'];
					if ($foto_antiga && $foto_antiga != "semimagem.jpg") {
						@unlink("fotos/" . $foto_antiga);
					}
					$customer['foto'] = upload_foto($_FILES["foto"]);
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
		ob_start();
		include PDF;
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage('L'); // Paisagem para caber mais colunas
		$pdf->Titulo("Lista de Clientes");

		// Definir larguras das colunas (total não deve exceder 280mm em paisagem)
		$larguras = [15, 50, 30, 30, 30, 25, 30, 30];
		$headers = ['ID', 'Nome', 'CPF/CNPJ', 'Cidade', 'Estado', 'Telefone', 'Modificado', 'Foto'];
		
		$pdf->Cabecalho($headers, $larguras);

		$customers = $p ? filter("customers", "name LIKE '%$p%'") : find_all("customers");

		foreach ($customers as $u) {
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetX(($pdf->GetPageWidth() - array_sum($larguras)) / 2);
			
			// Alternar cor de fundo entre branco e cinza claro
			$pdf->SetFillColor(($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255);
		
			$alturaLinha = 30;

			$pdf->Cell($larguras[0], $alturaLinha, $u['id'], 1, 0, 'C', true);
			$pdf->Cell($larguras[1], $alturaLinha, $pdf->converteTexto($u['name']), 1, 0, 'C', true); // Alinhado ao centro

			$formattedCPFCNPJ = format_cpf_cnpj($u['cpf_cnpj']);
			$pdf->Cell($larguras[2], $alturaLinha, $pdf->converteTexto($formattedCPFCNPJ), 1, 0, 'C', true);
			$pdf->Cell($larguras[3], $alturaLinha, $pdf->converteTexto($u['city']), 1, 0, 'C', true); // Alinhado ao centro
			$pdf->Cell($larguras[4], $alturaLinha, $pdf->converteTexto($u['state']), 1, 0, 'C', true);

			$formattedPhone = telefone($u['phone']);
			$pdf->Cell($larguras[5], $alturaLinha, $formattedPhone, 1, 0, 'C', true);
		
			$modified = !empty($u['modified']) ? date('d/m/Y H:i', strtotime($u['modified'])) : '';
			$pdf->Cell($larguras[6], $alturaLinha, $modified, 1, 0, 'C', true);
		
			// Imagem com borda e efeito "rounded"
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->Cell($larguras[7], $alturaLinha, '', 1, 0, 'C', true);
		
			$nomeFoto = (!empty($u['foto']) && is_file("fotos/" . $u['foto'])) ? $u['foto'] : "semimagem.png";
			$foto = "fotos/" . $nomeFoto;
		
			$larguraImagem = 25;
			$alturaImagem = 25;
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
		// Adicionar data de emissão do relatório
		$pdf->Ln(10);
		$pdf->SetFont('Arial', 'I', 10);
		$pdf->SetTextColor(0); // Texto preto
		$pdf->Cell(0, 10, $pdf->converteTexto('Relatório emitido em: ' . date('d/m/Y H:i')), 0, 1, 'R');

		ob_clean();
		$pdf->Output('D', 'clientes_' . date('dmY') . '.pdf');
		ob_end_flush();
	}
?>
