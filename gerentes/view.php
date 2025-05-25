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
    if (isset($_SESSION["user"])) {	
    } else {
        $_SESSION["message"] = "Você deve estar logado para acessar esse recurso!";
        $_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
        exit;
    }
    view($_GET["id"]);
    include(HEADER_TEMPLATE); 
?>
			<div class="container">
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Visualizar Gerente <?php echo $gerente['id']; ?></h2>
					<hr>
					<div class="row">
						<div class="col-lg-4">
							<img src="fotos/<?php echo $gerente['foto']; ?>" class="img-fluid rounded-4 mb-3" id="img-border" alt="foto do cliente"/>
						</div>
						<div class="col-lg-8 row mobile-register">
							
							<div class="col-lg-6">
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Nome / Razão Social:</h4>
									<p class="mb-0 p-0"><?php echo $gerente['nome']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Endereço:</h4>
									<p class="mb-0 p-0"><?php echo $gerente['endereco']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Data de Nascimento:</h4>
									<p class="mb-0 p-0"><?php echo formatadata($gerente['datanasc'], "d/m/Y"); ?></p>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Departamento:</h4>
									<p class="mb-0 p-0"><?php echo $gerente['depto']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de cadastro:</h4>
									<p class="mb-0 p-0"><?php echo $gerente['criacao']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de alteração:</h4>
									<p class="mb-0 p-0"><?php echo $gerente['modificacao']; ?></p>
								</div>	
							</div>
						</div>
						<hr>
						<div class="col-lg-12 button-align">
							<a href="edit.php?id=<?php echo $gerente['id']; ?>" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-pen-to-square me-1"></i> Editar</a>
							<a href="index.php" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-circle-left"></i> Voltar</a>
						</div>
					</form>
				</div>
			</div>
<?php include(FOOTER_TEMPLATE); ?>