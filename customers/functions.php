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
			$today = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

			$customer              = $_POST['customer'];
			$customer['created']  = $today->format("Y-m-d H:i:s");
			$customer['modified'] = $customer['created'];

			save('customers', $customer);
			clear_messages();
			header('location: index.php');
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
				$customer              = $_POST['customer'];
				$customer['modified'] = $now->format("Y-m-d H:i:s");

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

?>
