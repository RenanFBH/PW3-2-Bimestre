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

	/**
	* Gerando PDF
	*/
	function pdf_gerentes($p = null)
	{
		ob_start();
		include PDF;
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage('L'); // Paisagem para caber mais colunas
		$pdf->Titulo("Lista de Gerentes");

		// Definir larguras das colunas (total não deve exceder 280mm em paisagem)
		$larguras = [15, 50, 50, 50, 30, 30];
		$headers = ['ID', 'Nome', 'Endereço', 'Departamento', 'Data Nasc.', 'Foto'];
		
		$pdf->Cabecalho($headers, $larguras);

		$gerentes = $p ? filter("gerentes", "name LIKE '%$p%'") : find_all("gerentes");

		foreach ($gerentes as $u) {
			$pdf->SetFont('Arial', '', 9);
			$pdf->SetX(($pdf->GetPageWidth() - array_sum($larguras)) / 2);
			
			// Alternar cor de fundo entre branco e cinza claro
			$pdf->SetFillColor(($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255);

			$alturaLinha = 30;

			$pdf->Cell($larguras[0], $alturaLinha, $u['id'], 1, 0, 'C', true);
			$pdf->Cell($larguras[1], $alturaLinha, $pdf->converteTexto($u['nome']), 1, 0, 'C', true); // Alinhado ao centro

			$pdf->Cell($larguras[2], $alturaLinha, $pdf->converteTexto($u['endereco']), 1, 0, 'C', true);
			$pdf->Cell($larguras[3], $alturaLinha, $pdf->converteTexto($u['depto']), 1, 0, 'C', true);

			// Formatar data de nascimento
			$formattedDate = !empty($u['datanasc']) ? date('d/m/Y', strtotime($u['datanasc'])) : '';
			$pdf->Cell($larguras[4], $alturaLinha, $formattedDate, 1, 0, 'C', true);

			// Imagem com borda e efeito "rounded"
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->Cell($larguras[5], $alturaLinha, '', 1, 0, 'C', true);

			$nomeFoto = (!empty($u['foto']) && is_file("fotos/" . $u['foto'])) ? $u['foto'] : "semimagem.png";
			$foto = "fotos/" . $nomeFoto;

			$larguraImagem = 25;
			$alturaImagem = 25;
			$offsetX = $x + ($larguras[5] - $larguraImagem) / 2;
			$offsetY = $y + ($alturaLinha - $alturaImagem) / 2;

			try {
				// Simula borda com retângulo atrás
				$pdf->SetDrawColor(0, 0, 0); // Borda preta
				$pdf->SetLineWidth(0.5);
				$pdf->Rect($offsetX - 1, $offsetY - 1, $larguraImagem + 2, $alturaImagem + 2, 'D');

				$pdf->Image($foto, $offsetX, $offsetY, $larguraImagem, $alturaImagem);
			} catch (Exception $e) {
				$pdf->SetXY($x, $y);
				$pdf->Cell($larguras[5], $alturaLinha, 'Imagem não encontrada', 0, 0, 'C');
			}

			$pdf->Ln();
		}

		// Adicionar data de emissão do relatório
		$pdf->Ln(10);
		$pdf->SetFont('Arial', 'I', 10);
		$pdf->SetTextColor(0); // Texto preto
		$pdf->Cell(0, 10, $pdf->converteTexto('Relatório emitido em: ' . date('d/m/Y H:i')), 0, 1, 'R');

		ob_clean();
		$pdf->Output('D', 'gerentes_' . date('dmY') . '.pdf');
		ob_end_flush();
	}


?>