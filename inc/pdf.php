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
            $imgPath = '../fotos/carro.jpg';
            if (is_file($imgPath)) {
                $this->Image($imgPath, 10, 6, 15);
            }

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, $this->converteTexto('Relatório de Clientes'), 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, $this->converteTexto('Página ') . $this->PageNo() . $this->converteTexto(' de {nb}'), 0, 0, 'C');
        }

        function Titulo($titulo)
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, $this->converteTexto($titulo), 0, 1, 'C');
            $this->Ln(3);
        }

        function Cabecalho($headers, $larguras)
        {
            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(79, 129, 189); // Azul mais escuro
            $this->SetTextColor(255); // Texto branco
            $this->SetDrawColor(49, 79, 129); // Bordas mais escuras
            $this->SetLineWidth(0.3);
            
            $this->SetX(($this->GetPageWidth() - array_sum($larguras)) / 2);
            foreach ($headers as $i => $col) {
                $this->Cell($larguras[$i], 8, $this->converteTexto($col), 1, 0, 'C', true);
            }
            $this->Ln();
            $this->SetTextColor(0); // Restaura cor do texto para preto
        }
    }
?>