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
				} elseif (!empty($_POST['remove_foto']) && $_POST['remove_foto'] == '1') {
					$foto_antiga = find('gerentes', $id)['foto'];
					if ($foto_antiga && $foto_antiga != "semimagem.jpg") {
						@unlink("fotos/" . $foto_antiga);
					}
					$gerente['foto'] = "semimagem.jpg";
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
		date_default_timezone_set('America/Sao_Paulo'); 
		ob_start();
		include PDF;
		$pdf = new PDF('L', 'mm', 'A4');
		$pdf->AliasNbPages();
		$pdf->AddPage('L'); // Paisagem para caber mais colunas
		$pdf->Titulo("Lista de Gerentes");

		// Definir larguras das colunas (total não deve exceder 280mm em paisagem)
		$larguras = [15, 50, 50, 50, 30, 21];
		$headers = ['ID', 'Nome', 'Endereço', 'Departamento', 'Data Nasc.', 'Foto'];
		
		$pdf->Cabecalho($headers, $larguras);

		$gerentes = $p ? filter("gerentes", "nome LIKE '%$p%'") : find_all("gerentes");

		$alturaFixa = 21;

		foreach ($gerentes as $u) {

			// Calcular altura necessária para o endereço
			$endereco = $pdf->converteTexto($u['endereco']);
			$nbLinhasEndereco = $pdf->NbLines($larguras[2], $endereco);
			$alturaEndereco = $nbLinhasEndereco * 6;

			// Calcular altura necessária para o nome (caso queira aplicar MultiCell também depois)
			$nome = $pdf->converteTexto($u['nome']);
			$nbLinhasNome = $pdf->NbLines($larguras[1], $nome);
			$alturaNome = $nbLinhasNome * 6;

			// Maior altura entre os dois ou alturaFixa
			$alturaLinha = max($alturaEndereco, $alturaNome, $alturaFixa);

			// Verificar se há espaço na página atual
			$margemInferior = 15; // ajusta conforme o rodapé
			if ($pdf->GetY() + $alturaLinha > $pdf->GetPageHeight() - $margemInferior) {
				$pdf->AddPage();
			}

			$pdf->SetFont('Arial', '', 9);
			$pdf->SetX(($pdf->GetPageWidth() - array_sum($larguras)) / 2);
		
			// Alternar cor de fundo
			$pdf->SetFillColor(($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255, ($u['id'] % 2 == 0) ? 240 : 255);
		
			// Posição inicial da linha
			$xInicio = $pdf->GetX();
			$yInicio = $pdf->GetY();
		
			// ID
			$pdf->Cell($larguras[0], $alturaFixa, $u['id'], 1, 0, 'C', true);
		
			// Nome
			$xNome = $pdf->GetX();
			$pdf->Rect($xNome, $yInicio, $larguras[1], $alturaFixa, 'DF');
			$nome = $pdf->converteTexto($u['nome']);
			$nbNome = $pdf->NbLines($larguras[1], $nome);
			$alturaNome = 6 * $nbNome;
			$offsetYNome = $yInicio + ($alturaFixa - $alturaNome) / 2;
			$pdf->SetXY($xNome, $offsetYNome);
			$pdf->MultiCell($larguras[1], 6, $nome, 0, 'C');
			$pdf->SetXY($xNome + $larguras[1], $yInicio);
		
			// Endereço
			$xEndereco = $pdf->GetX();
			$pdf->Rect($xEndereco, $yInicio, $larguras[2], $alturaFixa, 'DF');
			$endereco = $pdf->converteTexto($u['endereco']);
			$nbEndereco = $pdf->NbLines($larguras[2], $endereco);
			$alturaEnd = 6 * $nbEndereco;
			$offsetYEnd = $yInicio + ($alturaFixa - $alturaEnd) / 2;
			$pdf->SetXY($xEndereco, $offsetYEnd);
			$pdf->MultiCell($larguras[2], 6, $endereco, 0, 'C');
			$pdf->SetXY($xEndereco + $larguras[2], $yInicio);
		
			// Departamento
			$xDepto = $pdf->GetX();
			$pdf->Rect($xDepto, $yInicio, $larguras[3], $alturaFixa, 'DF');
			$depto = $pdf->converteTexto($u['depto']);
			$nbDepto = $pdf->NbLines($larguras[3], $depto);
			$alturaDepto = 6 * $nbDepto;
			$offsetYDepto = $yInicio + ($alturaFixa - $alturaDepto) / 2;
			$pdf->SetXY($xDepto, $offsetYDepto);
			$pdf->MultiCell($larguras[3], 6, $depto, 0, 'C');
			$pdf->SetXY($xDepto + $larguras[3], $yInicio);
		
			// Data Nasc.
			$xData = $pdf->GetX();
			$pdf->Rect($xData, $yInicio, $larguras[4], $alturaFixa, 'DF');
			$dataNasc = !empty($u['datanasc']) ? date('d/m/Y', strtotime($u['datanasc'])) : '';
			$offsetYData = $yInicio + ($alturaFixa - 6) / 2;
			$pdf->SetXY($xData, $offsetYData);
			$pdf->Cell($larguras[4], 6, $dataNasc, 0, 0, 'C');
			$pdf->SetXY($xData + $larguras[4], $yInicio);
		
			// Foto
			$xFoto = $pdf->GetX();
			$yFoto = $pdf->GetY();
			$pdf->Cell($larguras[5], $alturaFixa, '', 1, 0, 'C', true);
			$nomeFoto = (!empty($u['foto']) && is_file("fotos/" . $u['foto'])) ? $u['foto'] : "semimagem.png";
			$foto = "fotos/" . $nomeFoto;
			$larguraImagem = 15;
			$alturaImagem = 15;
			$offsetXImg = $xFoto + ($larguras[5] - $larguraImagem) / 2;
			$offsetYImg = $yFoto + ($alturaFixa - $alturaImagem) / 2;
			try {
				$pdf->SetDrawColor(0, 0, 0);
				$pdf->SetLineWidth(0.5);
				$pdf->Rect($offsetXImg - 1, $offsetYImg - 1, $larguraImagem + 2, $alturaImagem + 2, 'D');
				$pdf->Image($foto, $offsetXImg, $offsetYImg, $larguraImagem, $alturaImagem);
			} catch (Exception $e) {
				$pdf->SetXY($xFoto, $yFoto);
				$pdf->Cell($larguras[5], $alturaFixa, 'Erro na imagem', 0, 0, 'C');
			}
		
			$pdf->Ln();
		}

		ob_clean();
		$pdf->Output('D', 'gerentes_' . date('dmY') . '.pdf');
		ob_end_flush();
	}


?>