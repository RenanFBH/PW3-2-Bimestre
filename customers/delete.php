<?php
	include('functions.php'); 
	session_start();
	if (!isset($_GET['id']) || empty($_GET['id'])) {
		$_SESSION["message"] = "ID inválido ou não fornecido!";
		$_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
		exit;
	}  
	if (!isset($_SESSION["user"])) {
		$_SESSION["message"] = "Você deve estar logado para acessar esse recurso!";
		$_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
		exit;
	}
	try {
		$customer = find("customers", $_GET['id']);
		if ($customer) { 
            $caminho_arquivo = "fotos/" . $customer['foto'];
            delete($_GET['id']);
            if (file_exists($caminho_arquivo) && $caminho_arquivo != "fotos/semimagem.jpg") {
                unlink($caminho_arquivo);
            }
        } else {
            $_SESSION["message"] = "Cliente não encontrado!";
            $_SESSION["type"] = "warning";
            header("Location: " . BASEURL . "index.php");
            exit;
        }
	} catch (Exception $e) {
		$_SESSION['message'] = "Não foi possível realizar a operação: " . $e->getMessage();
        $_SESSION['type'] = "danger";
        header("Location: " . BASEURL . "index.php");
        exit;
	}

	if (isset($_GET['id'])) {
		delete($_GET['id']);
	} 
?>