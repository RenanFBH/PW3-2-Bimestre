<?php
	include('functions.php'); 
	session_start();
	if (!isset($_GET['id'])) {
		$_SESSION["message"] = "ID inválido ou não fornecido!";
		$_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
		exit;
	} else if (!isset($_SESSION["user"])) {
		$_SESSION["message"] = "Você deve estar logado para acessar esse recurso!";
		$_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
		exit;
	}
	if (isset($_GET['id'])) {
		delete($_GET['id']);
	} 
?>