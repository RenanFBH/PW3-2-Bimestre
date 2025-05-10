<?php
	include("../config.php");
	include(DBAPI);

	if(!empty($_POST) AND (empty($_POST["login"]) OR empty($_POST["senha"]))) {
		if (!isset($_SESSION)) session_start();
		$_SESSION["message"] = "Por favor, preencha todos os campos!";
		$_SESSION["type"] = "danger";
	}

	$bd = open_database();

	try {
		$usuario = $_POST["login"];
		$senha = $_POST["senha"];
		
		if(!empty($usuario) && !empty($senha)) {
			$senha = criptografia($_POST["senha"]);
			
			$sql = "SELECT id, nome, user, password, foto FROM usuarios WHERE (user = '" . $usuario . "') AND (password = '" . $senha . "') LIMIT 1";
			$query = $bd->query($sql);

			if ($query->rowCount() > 0) {
				$dados = $query->fetch(PDO::FETCH_ASSOC);
				
				if(!empty($dados["user"])) {
					if (!isset($_SESSION)) session_start();
					$_SESSION["type"] = "info";
					$_SESSION["id"] = $dados["id"];
					$_SESSION["nome"] = $dados["nome"];
					$_SESSION["user"] = $dados["user"];
					$_SESSION["foto"] = $dados["foto"];

					// 🔴 Aqui ainda não foi enviado conteúdo — redirecionamento OK
					header("Location: " . BASEURL . "index.php");
					exit; // Sempre bom usar exit após redirecionamento
				} else {
					throw new Exception("Não foi possível se conectar!<br>Verifique seu usuário e senha.");
				}
			} else {
				throw new Exception("Não foi possível se conectar!<br>Verifique seu usuário e senha.");
			}
		} else {
			throw new Exception("Não foi possível se conectar!<br>Verifique seu usuário e senha.");
		}	
	} catch (Exception $e) {
		if (!isset($_SESSION)) session_start();
		$_SESSION["message"] = "Ocorreu um erro: " . $e->getMessage();
		$_SESSION["type"] = "danger";
	}

	// Só aqui agora inclui o header e footer
	include(HEADER_TEMPLATE);
?>
<?php if(!empty($_SESSION["message"])) :?>
			<div class="error">
				<div class="alert alert-<?php echo $_SESSION["type"]; ?> alert-dismissible fade show mx-auto w-75 mt-3" role="alert">
					<h4 class="alert-heading text-center">ERRO!</h4>
					<hr>
					<p class="justify"><?php echo $_SESSION["message"]; ?></p>
					<a href="<?php BASEURL ?>login.php" type="button" class="btn-close"></a>
				</div>
			</div>
<?php clear_messages(); ?>
<?php endif; ?>
<?php include(FOOTER_TEMPLATE); ?>	
