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
	if (!isset($_SESSION["user"]) != "admin"){
		$_SESSION["message"] = "Você estar logado e ser administrador para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location:" . BASEURL . "index.php");
	}
	try {
		$usuario = find("usuarios", $_GET['id']);
		if ($usuario) { 
            $caminho_arquivo = "fotos/" . $usuario['foto'];
            delete($_GET['id']);
            if (file_exists($caminho_arquivo) && $caminho_arquivo != "fotos/semimagem.jpg") {
                unlink($caminho_arquivo);
            }
        } else {
            $_SESSION["message"] = "Usuário não encontrado!";
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