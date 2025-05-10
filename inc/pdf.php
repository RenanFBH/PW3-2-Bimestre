<?php 
    include "fpdf.php";

    class PDF extends FPDF
    {
        function converteTexto($str)
        {
            return iconv("UTF-8", "windows-1252//TRANSLIT", $str);
        }

        function Header()
        {
            $imgWidth = 20;
            $imgY = 6;
        
            // Imagem à direita
            $imgPathRight = '../css/logo.jpg';
            if (is_file($imgPathRight)) {
                $this->Image($imgPathRight, $this->GetPageWidth() - 10 - $imgWidth, $imgY, $imgWidth);
            }
        
            // Espaço esquerdo (simula uma imagem ou só mantém simetria)
            $leftMargin = 10 + $imgWidth;
            $rightMargin = 10 + $imgWidth;
            $usableWidth = $this->GetPageWidth() - $leftMargin - $rightMargin;
        
            // Título centralizado dentro do espaço restante
            $this->SetFont('Arial', 'B', 18);
            $this->SetXY($leftMargin, $imgY);
            $this->Cell($usableWidth, 15, $this->converteTexto('Empresa CRUD-PHP'), 0, 1, 'C');
        
            $this->Ln(2);
        
            // Ajusta a posição Y para ficar abaixo da logo
            $this->SetY($imgY + $imgWidth + 2);  // Adiciona a altura da logo e um pequeno espaçamento
        
            // Linha horizontal
            $this->SetDrawColor(0, 0, 0); 
            $this->SetLineWidth(0.2); 
            $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(0); // Texto preto
            $this->Cell(15, 10, $this->converteTexto('Página ') . $this->PageNo() . $this->converteTexto(' de {nb}'), 0, 0, 'C');
			$this->Cell(0, 10, $this->converteTexto('Relatório emitido em: ' . date('d/m/Y H:i')), 0, 1, 'R');
        }

        function Titulo($titulo) {
            $this->SetFillColor(191, 191, 191);
            $this->SetTextColor(0); 
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, $this->converteTexto($titulo), 0, 1, 'C', true);
            $this->Ln(5);
            $this->SetTextColor(0);
        }

        function Cabecalho($headers, $larguras)
        {
            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(255, 255, 255);
            $this->SetTextColor(0); 
            $this->SetDrawColor(0, 0, 0); 
            $this->SetLineWidth(0.5);

            $this->SetX(($this->GetPageWidth() - array_sum($larguras)) / 2);
            foreach ($headers as $i => $col) {
                $this->Cell($larguras[$i], 8, $this->converteTexto($col), 1, 0, 'C', true);
            }
            $this->Ln();
            $this->SetTextColor(0); 
        }

        function NbLines($w, $txt) {
            $cw = &$this->CurrentFont['cw'];
            if ($w == 0)
                $w = $this->w - $this->rMargin - $this->x;
            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if ($nb > 0 && $s[$nb - 1] == "\n")
                $nb--;
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;
            while ($i < $nb) {
                $c = $s[$i];
                if ($c == "\n") {
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                    continue;
                }
                if ($c == ' ')
                    $sep = $i;
                $l += $cw[$c];
                if ($l > $wmax) {
                    if ($sep == -1) {
                        if ($i == $j)
                            $i++;
                    } else
                        $i = $sep + 1;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                } else
                    $i++;
            }
            return $nl;
        }
        
    }
?>
