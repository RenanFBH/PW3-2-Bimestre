<?php 
	include("functions.php");
	session_start();
	if (!isset($_SESSION)) session_start();
	if (!isset($_GET['id']) || empty($_GET['id'])) {
        $_SESSION["message"] = "ID inválido ou não fornecido!";
        $_SESSION["type"] = "danger";
        header("Location: " . BASEURL . "index.php");
        exit;
    }
	if (isset($_SESSION["user"])){
		if ($_SESSION["user"] != "admin") {
			$_SESSION["message"] = "Você precisa ser administrador para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location: " . BASEURL . "index.php");
			exit;
		}
	} else {
		$_SESSION["message"] = "Você deve estar logado e ser administrador para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location: " . BASEURL . "index.php");
			exit;
	}
	view($_GET["id"]);
	include(HEADER_TEMPLATE);
?>
			<div class="container">
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Visualizar Usuário <?php echo $usuario['id']; ?></h2>
					<hr>
					<div class="row">
						<div class="col-lg-4">
							<img src="fotos/<?php echo $usuario['foto']; ?>" class="img-fluid rounded-4 mb-3" id="img-border" alt="foto do cliente"/>
						</div>
						<div class="col-lg-8 row mobile-register">						
							<div class="col-lg-6">
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Nome / Razão Social:</h4>
									<p class="mb-0 p-0"><?php echo $usuario['nome']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Login:</h4>
									<p class="mb-0 p-0"><?php echo $usuario['user']; ?></p>
								</div>
								<hr>
							
							</div>
							<div class="col-lg-6">
								
							</div>
						</div>
						<hr>
						<div class="col-lg-12 button-align">
							<a href="edit.php?id=<?php echo $usuario['id']; ?>" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-pen-to-square me-1"></i> Editar</a>
							<a href="index.php" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-circle-left"></i> Voltar</a>
						</div>
					</div>
				</div>
			</div>
<?php include(FOOTER_TEMPLATE); ?>